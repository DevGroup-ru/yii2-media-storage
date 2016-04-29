<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Thing */

$this->title = Yii::t('app', 'Create Thing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Things'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
