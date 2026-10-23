<?php
require dirname(__DIR__) . '/app/bootstrap.php';
$receipt = $_SESSION['receipt'] ?? null;
if (!$receipt) {
    header('Location: /#book', true, 303);
    exit;
}
page_start('Booking request received');
?>
<section class="confirmation">
    <p class="eyebrow">Booking request received</p>
    <h1>Thanks, <?= h($receipt['name']) ?>.</h1>
    <p class="lead">Your bike is one step closer to a little care.</p>
    <dl class="receipt"><div><dt>Reference</dt><dd><?= h($receipt['reference']) ?></dd></div><div><dt>Service</dt><dd><?= h(SERVICES[$receipt['service']][0]) ?></dd></div><div><dt>Preferred day</dt><dd><?= h((new DateTimeImmutable($receipt['date']))->format('l, j F Y')) ?></dd></div><div><dt>Email</dt><dd><?= h($receipt['email']) ?></dd></div></dl>
    <p class="muted">Your request has been recorded. This is not a confirmed appointment.</p>
    <a class="button" href="/">Back to the workshop</a>
</section>
<?php page_end(); ?>
