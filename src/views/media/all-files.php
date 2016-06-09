<?php
use mihaildev\elfinder\ElFinder;

/**
 * @var yii\web\View $this
 */

$this->title = Yii::t('devgroup.media-storage', 'Media Storage');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="media-storage-view">
    <div class="row">
        <?= ElFinder::widget(
            [
                'language' => 'ru',
                'controller' => 'media/elfinder',
                'frameOptions' => ['style' => 'width: 100%; height: 100%; border: 0;min-height: 750px;'],
            ]
        ) ?>
    </div>
</div>
