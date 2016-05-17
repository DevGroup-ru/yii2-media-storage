<?php
/**
 * @var \yii\db\ActiveRecord $model
 * @var \DevGroup\DataStructure\models\Property $property
 * @var ActiveForm $form
 * @var \DevGroup\DataStructure\propertyHandler\AbstractPropertyHandler | \yii\web\View $this
 */
use DevGroup\MediaStorage\widgets\MediaForm;
use yii\widgets\ActiveForm;

echo MediaForm::widget(['model' => $model, 'form' => $form, 'property' => $property]);
