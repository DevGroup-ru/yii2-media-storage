<?php

use yii\helpers\Url;
use app\modules\media\widgets\MediaLibrary;

$this->title = 'New thing';

$this->params['breadcrumbs'][] = ['label' => 'Things', 'url' => Url::to(['thing/index'])];
$this->params['breadcrumbs'][] = 'New';
?>
<div class="things-container">
    <div class="row">
        <div class="col-md-4">
            <form method="post" action="<?= Url::to(['thing/save']) ?>">
                <div class="form-group">
                    <input type="text" name="thing-prop" placeholder="Thing prop" class="form-control">
                </div>
                <div class="form-group">
                    <?= MediaLibrary::widget() ?>
                </div>
                <div class="form-group">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" autocomlete="off">
                    <input type="submit" value="Create">
                </div>
            </form>
        </div>
    </div>
</div>
