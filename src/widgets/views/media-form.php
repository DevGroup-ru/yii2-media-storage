<?php
use devgroup\dropzone\DropZone;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var ActiveForm $form
 * @var ActiveRecord $model
 */

// add file(s)
echo DropZone::widget(
    [
        'name' => 'file',
        'url' => Url::to('/media/media/upload'),
        'storedFiles' => [],
        'eventHandlers' => [],
        'sortable' => true,
        'sortableOptions' => [],
        'htmlOptions' => [],
        'options' => [],
    ]
);
// select from gallery

// files

