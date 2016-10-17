<?php

/**
 * @var array $mediaIds
 * @var string $singleViewFile
 * @var array $urlOptions
 * @var array $additional
 */
?>
<div class="images">
    <?php foreach ($mediaIds as $mediaId): ?>
        <?= $this->render(
            $singleViewFile,
            ['mediaId' => $mediaId, 'urlOptions' => $urlOptions, 'additional' => $additional]
        ); ?>
    <?php endforeach; ?>
</div>
