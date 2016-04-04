<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Dropdown;
use app\modules\media\assets\IndexAsset;

/**
 * @var yii\web\View $this
 */

IndexAsset::register($this);

$this->title = 'Media Storage | Edit Item';

$this->params['breadcrumbs'][] = ['label' => 'Media Storage', 'url' => Url::to(['media/index'])];
$this->params['breadcrumbs'][] = 'Edit Item';
?>
<div class="media-storage-library">
    <div class="row">
    </div>
    <div class="row mt20">
        <div class="col-md-12">
            <?php
            if ($media->isImage()) {
                $media->show();
            }

            ActiveForm::begin([
                'action' => Url::to(['media/add-item']),
                'method' => 'post',
                'options' => [
                    'class' => 'media-storage-upload-form mt20',
                ]
            ]);

            echo Html::beginTag('div', ['class' => 'media-storage-items-fields mt20']);
                echo Html::beginTag('div', ['class' => 'form-group']);
                    Html::input('text', 'media-title', null, ['id' => 'media-title-input', 'placeholder' => 'File Hint']);
                echo Html::endTag('div');

                echo Html::beginTag('div', ['class' => 'dropdown']);
                    echo Html::button('<span>Select group</span> <span class="caret"></span>', ['class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown']);

                    echo Dropdown::widget([
                        'items' => $media_groups,
                    ]);
                echo Html::endTag('div');

                echo Html::button('Submit', ['class' => 'media-storage-submit-item btn btn-normal mt20']);
            echo Html::endTag('div');

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
