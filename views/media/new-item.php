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
<div class="media-storage-view row">
    <div class="col-md-6">
        <?php
        ActiveForm::begin([
            'action' => Url::to(['media/save-item', 'id' => 0]),
            'method' => 'post',
            'options' => [
                'class' => 'media-storage-upload-form mt20',
                'data-redirect' => Url::to(['media/index']),
            ]
        ]);

            echo Html::beginTag('div', ['class' => 'dz-message']);
                echo Html::tag('p', 'Put files here');
            echo Html::endTag('div');

            echo Html::hiddenInput('id', 0, ['autocomlete' => 'off']);
            echo Html::hiddenInput('media-title', null, ['autocomlete' => 'off']);
            echo Html::hiddenInput('media-group', 1, ['id' => 'media-group-hidden', 'autocomlete' => 'off']);

        ActiveForm::end();

        echo Html::beginTag('div', ['class' => 'media-storage-items-fields mt20']);
            echo Html::beginTag('div', ['class' => 'row']);
                echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
                    echo Html::label('File Hint', 'media-title-input');
                    echo Html::input('text', 'media-title', null, ['id' => 'media-title-input', 'class' => 'form-control']);
                echo Html::endTag('div');
            echo Html::endTag('div');

            echo Html::beginTag('div', ['class' => 'dropdown', 'data-input' => '#media-group-hidden']);
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
