<?php
/** @var array $skills */
?>
<section class="s" id="skills" aria-labelledby="h-skills">
    <div class="p">
        <div class="tilt">
            <h2 id="h-skills">Skills</h2>
            <p>What I use today, and where I'm heading next.</p>

            <?php foreach ($skills as $groupName => $items): ?>
                <div class="skill-group">
                    <h3><?= e($groupName) ?></h3>
                    <div class="tags">
                        <?php foreach ($items as $item): ?>
                            <span><?= e($item) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>