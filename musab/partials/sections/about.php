<?php
/** @var array $about  @var array $site */
?>
<section class="s r" id="about" aria-labelledby="h-about">
    <div class="p">
        <div class="tilt">
            <img class="avatar"
                 src="<?= e($site['photo']) ?>"
                 alt="Portrait of <?= e($site['name']) ?>"
                 width="104" height="104" loading="lazy">
            <h2 id="h-about">About me</h2>

            <?php foreach ($about['paragraphs'] as $paragraph): ?>
                <p><?= e($paragraph) ?></p>
            <?php endforeach; ?>

            <div class="stats">
                <?php foreach ($about['stats'] as $stat): ?>
                    <div>
                        <b><?= e($stat['value']) ?></b>
                        <span><?= e($stat['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>