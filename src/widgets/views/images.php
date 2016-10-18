<?php

/**
 * @var array $medias
 * @var string $singleViewFile
 * @var array $additional
 */
?>
<div class="images">
    <?php foreach ($medias as $mediaAttrs): ?>
        <?= $this->render(
            $singleViewFile,
            [
                'additional' => $additional,
                'mediaAttrs' => $mediaAttrs,
            ]
        ); ?>
    <?php endforeach; ?>
</div>
