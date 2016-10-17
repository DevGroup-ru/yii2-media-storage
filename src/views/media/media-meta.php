<?php

use DevGroup\MediaStorage\models\Media;
use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var DevGroup\MediaStorage\models\Media $model
 * @var yii\web\View $this
 * @codeCoverageIgnore
 */

$this->title = Yii::t('app', 'Media meta');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div class="box-body">
        <div class="context-index">
            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model,
                    'columns' => [
                        'id',
                        'path',
                        'mime',
                        [
                            'class' => 'kartik\grid\EditableColumn',
                            'attribute' => 'alt',
                        ],
                        [
                            'class' => 'kartik\grid\EditableColumn',
                            'attribute' => 'title',
                        ],
                        [
                            'format' => 'raw',
                            'value' => function (Media $data) {
                                if ($data->isImage()) {
                                    return Html::a(
                                        Html::img(
                                            [
                                                '/media/file/send',
                                                'mediaId' => $data->id,
                                                'config' => ['imageConfig' => ['w' => 150]],
                                            ]
                                        ),
                                        ['/media/file/send', 'mediaId' => $data->id,]
                                    );
                                } else {
                                    return '';
                                }
                            },
                        ],
                    ],
                    'export' => false,
                ]
            ); ?>
        </div>
    </div>
    <div class="box-footer">
        <?php if (Yii::$app->user->can('multilingual-create-context')) : ?>
            <div class="pull-right">
                <?= Html::a(Yii::t('app', 'Create'), ['edit'], ['class' => 'btn btn-success']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
