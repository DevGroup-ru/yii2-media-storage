<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var array $additional
 * @var array $mediaAttrs
 */

?>
<div class="image">
    <?= Html::img(ArrayHelper::remove($mediaAttrs, 'src'), $mediaAttrs) ?>
</div>
