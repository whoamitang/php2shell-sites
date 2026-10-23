<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; img-src 'self'; form-action 'self'; frame-ancestors 'none'; base-uri 'none'");
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store');

session_name(getenv('LAB_VARIANT') === 'patched' ? 'OAKPATCHED' : 'OAKLOCAL');
session_start([
    'use_strict_mode' => 1,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_path' => '/',
]);
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

const SERVICES = [
    'puncture' => ['Puncture repair', 'From €18', 'A fresh tube, a tyre check, and you’re on your way.'],
    'tune' => ['Everyday tune-up', '€65', 'Brakes, gears, chain, and all the little adjustments.'],
    'service' => ['Full service', '€120', 'A thorough check and service for a bike you ride often.'],
];

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function posted(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function page_start(string $title): void
{
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Bicycle repairs, honest advice, and everyday servicing at Oak Street Cycles.">
        <title><?= h($title) ?> · Oak Street Cycles</title>
        <link rel="stylesheet" href="/style.css">
        <link rel="icon" href="data:,">
    </head>
    <body>
    <a class="skip" href="#main">Skip to content</a>
    <header class="site-header wrap">
        <a class="wordmark" href="/">Oak Street Cycles<span>Repairs &amp; servicing</span></a>
        <nav aria-label="Main navigation"><a href="/#services">Services</a><a href="/#visit">Visit us</a><a href="/#book">Book a repair</a></nav>
    </header>
    <main id="main" class="wrap">
    <?php
}

function page_end(): void
{
    ?>
    </main>
    <footer class="wrap site-footer"><span>Oak Street Cycles</span><span>A little care goes a long way.</span></footer>
    </body>
    </html>
    <?php
}
