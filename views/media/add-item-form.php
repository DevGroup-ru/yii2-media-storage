<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Dropdown;
use app\modules\media\assets\UploadingAsset;

/**
 * @var yii\web\View $this
 */

UploadingAsset::register($this);

$this->title = 'Media Storage | New Item';

$this->params['breadcrumbs'][] = ['label' => 'Media Storage', 'url' => Url::to(['media/index'])];
$this->params['breadcrumbs'][] = 'New Item';
?>
<div class="row">
    <div class="col-md-12">
        <?php
        ActiveForm::begin([
            'action' => Url::to(),
            'method' => 'post',
            'options' => [
                'class' => 'media-storage-upload-form mt20',
            ]
        ]);

            echo Html::beginTag('div', ['class' => 'dz-message']);
                echo Html::tag('p', 'Put files here');
            echo Html::endTag('div');

            echo Html::hiddenInput('media-title', null, ['autocomlete' => 'off']);
            echo Html::hiddenInput('media-group', 1, ['autocomlete' => 'off']);

        ActiveForm::end();

        echo Html::beginTag('div', ['class' => 'media-storage-items-fields mt20']);
            echo Html::beginTag('div', ['class' => 'form-group']);
                echo Html::input('text', 'media-title', null, ['id' => 'media-title-input', 'placeholder' => 'File Hint']);
            echo Html::endTag('div');

            echo Html::beginTag('div', ['class' => 'dropdown']);
                echo Html::button('<span>Select group</span> <span class="caret"></span>', ['class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown']);

                echo Dropdown::widget([
                    'items' => $media_groups,
                ]);
            echo Html::endTag('div');

            echo Html::button('Submit', ['class' => 'media-storage-submit-item btn btn-normal mt20']);
        echo Html::endTag('div');
        ?>
    </div>
</div>
