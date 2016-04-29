<?php

use DevGroup\MediaStorage\widgets\MediaLibrary;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Thing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prop')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= MediaLibrary::widget(['form' => $form]) ?>

    <?php ActiveForm::end(); ?>

</div>
