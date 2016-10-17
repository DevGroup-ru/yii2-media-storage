<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var array $mediaId
 * @var array $urlOptions
 * @var array $additional
 */

?>
<div class="image">
    <?= Html::img(
        ArrayHelper::merge(['/media/file/send', 'mediaId' => $mediaId], ['config' => ['imageConfig' => $urlOptions]])
    ) ?>
</div>
