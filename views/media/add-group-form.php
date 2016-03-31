<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\media\assets\IndexAsset;

/**
 * @var yii\web\View $this
 */

IndexAsset::register($this);

$this->title = 'Media Storage Group';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-storage-groups">
    <div class="row">
        <div class="col-md-3">
            <?php
            $form = ActiveForm::begin([
                'action' => Url::to(),
                'method' => 'post',
            ]);

            echo Html::beginTag('div', ['class' => 'form-group']);
                echo Html::label('Group Name', 'media-group-name');
                echo Html::input('text', 'group-name', null, ['class' => 'form-control', 'id' => 'media-group-name', 'autofocus' => 'autofocus']);
            echo Html::endTag('div');

            echo Html::tag('p', 'Some other fields will be here.');

            echo Html::submitButton();


            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

