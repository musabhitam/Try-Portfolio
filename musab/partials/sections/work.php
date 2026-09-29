<?php
/** @var array $work */
?>
<section class="s" id="work" aria-labelledby="h-work">
    <div class="p">
        <div class="tilt">
            <h2 id="h-work">Selected work</h2>
            <p>Projects floating ahead of you and each one a real thing I built or am building.</p>
            <ul class="pl">
                <?php foreach ($work['items'] as $item): ?>
                    <li>
                        <b><?= e($item['title']) ?></b>
                        <span><?= e($item['meta']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="hint"><?= e($work['note']) ?></p>
        </div>
    </div>
</section>