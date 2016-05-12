<?php
use devgroup\dropzone\DropZone;
use mihaildev\elfinder\ElFinder;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var ActiveForm $form
 * @var ActiveRecord $model
 */

// add file(s) button

// select from gallery button

// files

echo ElFinder::widget(
    [
        'language' => 'ru',
        'controller' => 'media/media-elfinder',
        'filter' => ['model' => $model->className(), 'model_id' => $model->id],
        'frameOptions' => ['style' => 'width: 100%; height: 100%; border: 0;min-height: 350px;'],
    ]
);
