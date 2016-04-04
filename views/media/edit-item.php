<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Dropdown;
use app\modules\media\assets\HelperAsset;

/**
 * @var yii\web\View $this
 */

HelperAsset::register($this);

$this->title = 'Media Storage | Edit Item';

$this->params['breadcrumbs'][] = ['label' => 'Media Storage', 'url' => Url::to(['media/index'])];
$this->params['breadcrumbs'][] = 'New Item';
?>
<div class="media-storage-view row">
    <div class="col-md-6">
        <?php
        if ($media->isImage()) {
            $media->showThumb();
        }

        echo Html::tag('hr');

        ActiveForm::begin([
            'action' => Url::to(['media/save-item', 'id' => $media->id]),
            'method' => 'post',
        ]);

            echo Html::beginTag('div', ['class' => 'row']);
                echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
                    echo Html::label('File Hint', 'media-title-input');
                    echo Html::input('text', 'media-title', $media->title, ['id' => 'media-title-input', 'class' => 'form-control']);
                echo Html::endTag('div');
            echo Html::endTag('div');

            echo Html::beginTag('div', ['class' => 'dropdown', 'data-current' => $media->group_id, 'data-input'   => '#media-group-hidden']);
                echo Html::button('<span>Select group</span> <span class="caret"></span>', ['class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown']);

                echo Dropdown::widget([
                    'id'    => 'media-groups-dropdown',
                    'items' => $media_groups,
                ]);
            echo Html::endTag('div');

            echo Html::hiddenInput('media-group', $media->group_id, ['id' => 'media-group-hidden', 'autocomlete' => 'off']);

            echo Html::submitButton('Submit', ['class' => 'media-storage-submit-item btn btn-normal mt20']);

        ActiveForm::end();
        ?>
    </div>
</div>
