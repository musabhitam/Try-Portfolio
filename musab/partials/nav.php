<?php
/** @var array $nav  @var array $site */
?>
<nav aria-label="Primary">
    <strong><?= e($site['brand']) ?></strong>
    <div>
        <?php foreach ($nav as $href => $label): ?>
            <a href="<?= e($href) ?>">
                <?= e($label) ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>