<?php

use DevGroup\MediaStorage\widgets\ElfinderWidget;
use DevGroup\MediaStorage\widgets\ImageWidget;
use DevGroup\MediaStorage\widgets\MediaInput;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use Yii;

/**
 * @var ActiveForm $form
 * @var ActiveRecord $model
 * @var \DevGroup\DataStructure\models\Property $property
 */

echo MediaInput::widget(
    [
        'language' => Yii::$app->language,
        'controller' => 'media/elfinder',
        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        'options' => ['class' => 'form-control'],
        'buttonOptions' => ['class' => 'btn btn-default'],
        'buttonName' => '<i class="fa fa-plus"></i> ' . Yii::t('devgroup.media-storage', 'Open gallery'),
        'multiple' => true,
        'attribute' => $property->key,
        'model' => $model,
    ]
);
if ($property->allow_multiple_values == 1) : ?>
    <?php
    $inputName = Html::getInputName($model, $property->key) . '[]';
    $inputId = Html::getInputId($model, $property->key);
    $values = (array) $model->{$property->key};
    if (count($values) === 0) {
        $values = [''];
    }
    ?>
    <div class="m-form__col multi-media <?= $model->hasErrors($property->key) ? 'has-error' : '' ?>">
        <?php foreach ($values as $index => $value) : ?>

            <?= Html::hiddenInput(
                $inputName,
                $value,
                [
                    'class' => 'form-control',
                    'id' => $inputId . '-' . $value,
                    'name' => $inputName,
                ]
            ) ?>

        <?php endforeach; ?>

    </div>
<?php else : ?>
    <?php echo $form->field($model, $property->key)->hiddenInput(); ?>
<?php endif;

echo ElfinderWidget::widget(
    [
        'language' => Yii::$app->language,
        'controller' => '/media/media-elfinder',
        'customManagerOptions' => [
            'customData' => [
                'model' => $model->className(),
                'model_id' => $model->id,
                'property_id' => $property->id,
            ],
            'handlers' => [
                'upload' => new JsExpression(
                    strtr(
                    /** @lang JavaScript */
                        "function (event, instance) {
    event.data.added.forEach(function (i) {        
        if ($('.multi-media input[value=\"' + i.id + '\"]').length === 0) {
            console.log(instance);
            $('.multi-media', parent.document).append('<input type=\"hidden\" id=\"inputId-' + i.id + '\" class=\"form-control\" name=\"inputName\" value=\"' + i.id + '\">');
        }
    });
}",
                        ['inputId' => $inputId, 'inputName' => $inputName]
                    )
                ),
            ],
        ],
        'frameOptions' => ['style' => 'width: 100%; height: 100%; border: 0;min-height: 350px;'],
    ]
);
