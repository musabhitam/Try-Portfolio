<?php
declare(strict_types=1);

$config = require __DIR__ . '/config.php';

$site    = $config['site'];
$nav     = $config['nav'];
$intro   = $config['intro'];
$about   = $config['about'];
$skills  = $config['skills'];
$awards  = $config['awards'];
$work    = $config['work'];
$contact = $config['contact'];
$social  = $config['social'];  


function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="<?= e($site['lang']) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body>

<canvas id="gl" aria-hidden="true"></canvas>
<div class="bar" id="bar" aria-hidden="true"></div>

<?php require __DIR__ . '/partials/nav.php'; ?>

<main id="top">
    <?php
        require __DIR__ . '/partials/sections/intro.php';
        require __DIR__ . '/partials/sections/about.php';
        require __DIR__ . '/partials/sections/skills.php';
        require __DIR__ . '/partials/sections/awards.php';
        require __DIR__ . '/partials/sections/work.php';
        require __DIR__ . '/partials/sections/contact.php';
    ?>
</main>

<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>