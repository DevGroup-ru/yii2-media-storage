<?php

use mihaildev\elfinder\ElFinder;
use mihaildev\elfinder\InputFile;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

/**
 * @var ActiveForm $form
 * @var ActiveRecord $model
 */

foreach ($model->media as $key => $value) {
    echo $form->field($model, 'media[' . $key . ']')->hiddenInput()->label(false);
}

//echo $form->field($model, 'media[]')->widget(
//    InputFile::className(),
//    [
//        'language' => 'ru',
//        'controller' => 'media/elfinder',
//        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
//        'options' => ['class' => 'form-control'],
//        'buttonOptions' => ['class' => 'btn btn-default'],
//        'buttonName' => '<i class="fa fa-plus"></i> ' . \Yii::t('app', 'Open gallery'),
//        'multiple' => true,
//    ]
//);

echo ElFinder::widget(
    [
        'language' => 'ru',
        'controller' => 'media/media-elfinder',
        'filter' => ['model' => $model->className(), 'model_id' => $model->id],
        'frameOptions' => ['style' => 'width: 100%; height: 100%; border: 0;min-height: 350px;'],
    ]
);
