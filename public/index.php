<?php
require dirname(__DIR__) . '/app/bootstrap.php';
$errors = $_SESSION['form_errors'] ?? [];
$old = $_SESSION['form_values'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_values']);
page_start('Your neighbourhood bike workshop');
?>
<section class="intro">
    <div>
        <p class="eyebrow">Your neighbourhood bike workshop</p>
        <h1>Keep your bike<br>in good company.</h1>
        <p class="lead">From the daily commute to the long way home.<br class="desktop-break"> We’ll help you keep rolling.</p>
        <a class="button" href="#book">Book a repair</a>
    </div>
    <aside class="shop-note" aria-label="Workshop approach">
        <p class="note-title">A small workshop.<br>A proper job.</p>
        <p>We fix what needs fixing, explain the options, and agree on the price before we start.</p>
        <p>All everyday bikes welcome.<br>Drop in for a quick look.</p>
    </aside>
</section>
<section id="services" class="services section">
    <div class="section-heading"><h2>What we do</h2><p>Labour prices below. Parts quoted separately.</p></div>
    <?php foreach (SERVICES as [$name, $price, $description]): ?>
    <div class="service-row"><h3><?= h($name) ?></h3><p><?= h($description) ?></p><span class="price"><?= h($price) ?></span></div>
    <?php endforeach; ?>
</section>
<div class="booking-layout section">
    <section id="book">
        <h2>Let’s get it booked in.</h2>
        <p class="muted">Tell us a little about your bike. Choose a day that works for you.</p>
        <?php if ($errors): ?>
        <div class="form-errors" role="alert"><p>Please check your details:</p><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form action="/booking.php" method="post">
            <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
            <div class="field-pair">
                <div class="field"><label for="name">Your name</label><input id="name" name="name" autocomplete="name" maxlength="100" required value="<?= h($old['name'] ?? '') ?>"></div>
                <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" autocomplete="email" maxlength="254" required value="<?= h($old['email'] ?? '') ?>"></div>
            </div>
            <div class="field-pair">
                <div class="field"><label for="service">Service</label><select id="service" name="service" required><option value="">Choose a service</option><?php foreach (SERVICES as $key => [$name]): ?><option value="<?= h($key) ?>" <?= ($old['service'] ?? '') === $key ? 'selected' : '' ?>><?= h($name) ?></option><?php endforeach; ?></select></div>
                <div class="field"><label for="date">Preferred day</label><input id="date" name="date" type="date" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+365 days')) ?>" required value="<?= h($old['date'] ?? '') ?>"></div>
            </div>
            <div class="field"><label for="message">Anything we should know? <span class="optional">Optional</span></label><textarea id="message" name="message" rows="4" maxlength="1500" placeholder="The bike, the problem, or just a question."><?= h($old['message'] ?? '') ?></textarea></div>
            <button class="button" type="submit">Request a booking</button>
            <p class="form-note">No payment needed. Your preferred day is a request, not a confirmed appointment.</p>
        </form>
    </section>
    <aside id="visit" class="visit">
        <h2>Come by the workshop.</h2>
        <p>14 Oak Street<br>Westbridge</p>
        <dl class="hours"><div><dt>Tuesday–Friday</dt><dd>09:00–18:00</dd></div><div><dt>Saturday</dt><dd>10:00–16:00</dd></div><div><dt>Sunday–Monday</dt><dd>Closed</dd></div></dl>
        <p class="muted">Walk-ins welcome for small repairs.<br>Booking is best for a full service.</p>
        <p>hello@oakstreet.example</p>
    </aside>
</div>
<?php page_end(); ?>
