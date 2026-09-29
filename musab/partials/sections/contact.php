<?php
/** @var array $contact  @var array $site  @var array $social */
?>
<section class="s c" id="contact" aria-labelledby="h-contact">
    <div class="p">
        <div class="tilt">
            <img class="avatar center"
                 src="<?= e($site['photo']) ?>"
                 alt="Portrait of <?= e($site['name']) ?>"
                 width="104" height="104" loading="lazy">
            <h2 id="h-contact"><?= e($contact['heading']) ?></h2>
            <a class="big" id="mail" href="mailto:<?= e($site['email']) ?>">
                <?= e($site['email']) ?>
            </a>
            <div class="row" style="justify-content:center">
                <button class="btn f" id="copy" type="button">Copy email</button>
                <?php foreach ($social as $link): ?>
                    <?php $external = stripos($link['url'], 'http') === 0; ?>
                    <a class="btn"
                       href="<?= e($link['url']) ?>"
                       <?= $external ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                        <?= e($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <p class="hint"><?= e($contact['note']) ?></p>
        </div>
    </div>
</section>