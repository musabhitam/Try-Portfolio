<?php
/** @var array $awards */
?>
<section class="s r" id="awards">
  <div class="p">
    <div class="tilt">
      <h2>Achievements</h2>
      <ul class="pl">
        <?php foreach ($awards['items'] as $item): ?>
          <li>
            <b><?= e($item['title']) ?></b>
            <span><?= e($item['meta']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="hint"><?= e($awards['note']) ?></p>
    </div>
  </div>
</section>