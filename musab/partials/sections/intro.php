<?php
/** @var array $intro  @var array $site */
?>
<section class="s" id="intro" aria-labelledby="h-intro">
    <div class="p">
        <div class="tilt">
            <img class="avatar"
                 src="<?= e($site['photo']) ?>"
                 alt="Portrait of <?= e($site['name']) ?>"
                 width="104" height="104" loading="eager">
            <h1 id="h-intro"><?= e($intro['heading']) ?></h1>
            <p><?= e($intro['lede']) ?></p>
            <div class="row">
                <a class="btn f" href="#about">About me</a>
                <a class="btn" href="#contact">Contact</a>
            </div>
            <p class="hint">Scroll to move through the scene. Move your mouse to look around.</p>
        </div>
    </div>
</section>