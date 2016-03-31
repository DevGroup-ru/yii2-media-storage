<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\media\assets\UploadingAsset;

/**
 * @var yii\web\View $this
 */

UploadingAsset::register($this);

$this->title = 'Media Storage';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <?php
        $form = ActiveForm::begin([
            'action' => '/media/upload',
            'options' => [
                'class' => 'media-storage-upload-form mt20',
            ]
        ]);
        ?>
            <div class="dz-message">
                <p>Put files here</p>
            </div>
            <input type="hidden" name="media-title" autocomlete="off">
        <?php ActiveForm::end(); ?>

        <div class="mt20">
            <div class="form-group">
                <?= Html::input('text', 'media-title', null, ['id' => 'media-title-input', 'placeholder' => 'File Hint']) ?>
            </div>
            <?= Html::button('Submit', ['class' => 'media-storage-submit-item btn btn-normal']) ?>
        </div>
    </div>
</div>
