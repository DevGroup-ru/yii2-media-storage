<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 */

$this->title = 'Media Storage | New Group';

$this->params['breadcrumbs'][] = ['label' => 'Media Storage', 'url' => Url::to(['media/index'])];
$this->params['breadcrumbs'][] = 'New Group';
?>
<div class="media-storage-view media-storage-groups">
    <div class="row">
        <div class="col-md-3">
            <?php
            $form = ActiveForm::begin([
                'action' => Url::to(['media/save-group', 'id' => 0]),
                'method' => 'post',
            ]);

            echo Html::beginTag('div', ['class' => 'form-group']);
                echo Html::label('Group Name', 'media-group-name');
                echo Html::input('text', 'group-name', null, ['class' => 'form-control', 'id' => 'media-group-name', 'autofocus' => 'autofocus']);
            echo Html::endTag('div');

            echo Html::tag('p', 'Some other fields will be here.');

            echo Html::hiddenInput('id', 0, ['autocomlete' => 'off']);

            echo Html::submitButton();


            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

