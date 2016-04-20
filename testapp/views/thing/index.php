<?php

use yii\helpers\Url;

$this->title = 'Things';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="things-container">
    <a class="btn btn-primary" href="<?= Url::to(['thing/new']) ?>">New</a>

    <div class="mt20">&nbsp;</div>

    <ul class="list-group">
        <?php foreach($things as $thing) { ?>
        <li class="list-group-item">
            <?= "Thing #{$thing->id} has prop = {$thing->prop}" ?>
        </li>
        <?php } ?>
    </ul>
</div>
