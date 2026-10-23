<?php
require dirname(__DIR__) . '/app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    page_start('Use the booking form');
    echo '<section class="confirmation"><h1>Start with a few details.</h1><p><a href="/#book">Go to the booking form</a></p></section>';
    page_end();
    exit;
}
if (!hash_equals($_SESSION['csrf'], posted('csrf'))) {
    http_response_code(403);
    page_start('Please try again');
    echo '<section class="confirmation"><h1>Please refresh the form.</h1><p>Your form has expired. <a href="/#book">Start a new booking request.</a></p></section>';
    page_end();
    exit;
}
$values = [];
foreach (['name', 'email', 'service', 'date', 'message'] as $key) {
    $values[$key] = posted($key);
}
$errors = [];
if ($values['name'] === '' || strlen($values['name']) > 100) $errors[] = 'Enter a name of up to 100 characters.';
if (strlen($values['email']) > 254 || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
if (!isset(SERVICES[$values['service']])) $errors[] = 'Choose one of the listed services.';
$date = preg_match('/^\d{4}-\d{2}-\d{2}$/D', $values['date']) ? DateTimeImmutable::createFromFormat('!Y-m-d', $values['date']) : false;
$today = new DateTimeImmutable('today');
if (!$date || $date->format('Y-m-d') !== $values['date'] || $date < $today || $date > $today->modify('+365 days')) $errors[] = 'Choose a day within the next year.';
if (strlen($values['message']) > 1500) $errors[] = 'Keep your message within 1,500 characters.';
if ($errors) {
    $_SESSION['form_errors'] = $errors;
    // Bound data retained after invalid submissions.
    $_SESSION['form_values'] = array_map(fn(string $v): string => substr($v, 0, 1500), $values);
    header('Location: /#book', true, 303);
    exit;
}
$receipt = 'OAK-' . strtoupper(bin2hex(random_bytes(4)));
$record = ['reference' => $receipt, 'created_at' => date(DATE_ATOM)] + $values;
$dataFile = (getenv('OAK_DATA_DIR') ?: '/tmp') . '/oak-street-bookings.jsonl';
$saved = false;
$handle = fopen($dataFile, 'a');
if ($handle !== false) {
    if (flock($handle, LOCK_EX)) {
        $stat = fstat($handle);
        if ($stat && $stat['size'] < 4 * 1024 * 1024) {
            $line = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) . "\n";
            $saved = fwrite($handle, $line) === strlen($line);
            fflush($handle);
        }
        flock($handle, LOCK_UN);
    }
    fclose($handle);
}
if (!$saved) {
    http_response_code(503);
    page_start('Please try later');
    echo '<section class="confirmation"><h1>We couldn’t save your request.</h1><p>Please try again later. <a href="/#book">Return to the form.</a></p></section>';
    page_end();
    exit;
}
$_SESSION['receipt'] = $record;
$_SESSION['csrf'] = bin2hex(random_bytes(32));
header('Location: /confirmation.php', true, 303);
