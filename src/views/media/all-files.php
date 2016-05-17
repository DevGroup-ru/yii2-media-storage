<?php
use mihaildev\elfinder\ElFinder;
/**
 * @var yii\web\View $this
 */

$this->title = 'Media Storage';
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
        <!--        <div class="col-md-8 media-storage-library">-->
        <!--            <div>-->
        <!--                <a class="media-item-add js-link btn btn-primary" href="#">--><? //= Yii::t('app', 'Add new item') ?><!--</a>-->
        <!--                <div class="spacer"></div>-->
        <!--                <a class="btn btn-primary" href="--><? //= Url::to(['/media/media/all-groups']) ?><!--">--><? //= Yii::t('app', 'Groups') ?><!--</a>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</div>
