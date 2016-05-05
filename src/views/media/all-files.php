<?php

use devgroup\dropzone\DropZone;
use devgroup\dropzone\DropZoneAsset;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Url;
use yii\bootstrap\Dropdown;
use branchonline\lightbox\Lightbox;
use DevGroup\MediaStorage\assets\FilesAsset;

/**
 * @var yii\web\View $this
 */

DropZoneAsset::register($this);
FilesAsset::register($this);
$this->title = 'Media Storage';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= DropZone::widget(
    [
        //                            'name' => 'file',
        //                            'url' => ['upload'],
        ////                            'removeUrl' => ['remove'],
        ////                            'uploadDir' => '/theme/resources/product-images',
        //                            'sortable' => true,
        //                            'sortableOptions' => [
        //                                'items' => '.dz-image-preview',
        //                            ],
        //                            'htmlOptions' => [
        //                                'class' => 'table table-striped files',
        //                                'id' => 'previews',
        //                            ],
        //                            'options' => [
        //                                'clickable' => ".fileinput-button",
        //                            ],
        'name' => 'file', // input name or 'model' and 'attribute'
        'url' => Url::to('/media/media/upload'), // upload url
        'storedFiles' => [], // stores files
        'eventHandlers' => [], // dropzone event handlers
        'sortable' => true, // sortable flag
        'sortableOptions' => [], // sortable options
        'htmlOptions' => [], // container html options
        'options' => [], // dropzone js options
    ]
); ?>
<div class="media-storage-view">
    <div class="row">
        <?= ElFinder::widget(
            [
                'language' => 'ru',
                'controller' => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
            ]
        ) ?>
        <div class="col-md-8 media-storage-library">
            <div>
                <a class="media-item-add js-link btn btn-primary" href="#"><?= Yii::t('app', 'Add new item') ?></a>
                <div class="spacer"></div>
                <a class="btn btn-primary" href="<?= Url::to(['/media/media/all-groups']) ?>"><?= Yii::t('app', 'Groups') ?></a>
            </div>


        </div>
        <div class="media-item-new col-md-4">
            <h3 class="mt0"><?= Yii::t('app', 'Add new item') ?></h3>
            <div class="sidebar-form mt20">
                <form method="post" action="<?= Url::to(
                    ['/media/media/save-item', 'id' => 0]
                ) ?>" data-redirect="<?= Url::to(['/media/media/all-files']) ?>">
                    <input type="hidden" name="title" id="media-title-hidden" value="" autocomlete="off">
                    <input type="hidden" name="group" id="media-group-hidden" value="1" autocomlete="off">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken(
                    ) ?>" autocomlete="off">
                </form>
                <div class="media-item-fields mt20">
                    <div class="form-group">
                        <label for="media-title-input">File Hint</label>
                        <input type="text" name="media-title" id="media-title-input" class="form-control">
                    </div>

                    <div class="dropdown" data-input="#media-group-hidden">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span>Select group</span>
                            <span class="caret"></span>
                        </button>
                        <?php
                        echo Dropdown::widget(
                            [
                                'items' => $media_groups,
                            ]
                        );
                        ?>
                    </div>

                    <a class="media-item-save js-link mt20 btn btn-primary" href="#">Save</a>
                </div>
            </div>
        </div>
        <div class="media-item-edit hidden col-md-4">
            <h3 class="mt0">Update item</h3>
            <form method="post" data-url="<?= Url::to(
                ['/media/media/save-item', 'id' => 0]
            ) ?>" class="sidebar-form mt20">
                <?php Lightbox::widget([]) # Output to /dev/null. Need to load Lightbox assets ?>

                <a class="lightbox-link" href="#" data-lightbox="media-item-edit-thumb" data-url="<?= Url::to(
                    ['/media/media/show-item', 'id' => 0]
                ) ?>">
                    <img src="" alt="">
                </a>
                <hr>
                <div class="form-group">
                    <label for="media-title-edit-input">File Hint</label>
                    <input type="text" name="title" class="form-control" id="media-title-edit-input">
                </div>
                <div class="dropdown" data-current="" data-input="#media-group-edit-hidden">
                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span>Select group</span>
                        <span class="caret"></span>
                    </button>
                    <?php
                    echo Dropdown::widget(
                        [
                            'items' => $media_groups,
                        ]
                    );
                    ?>
                </div>

                <input type="hidden" name="id" id="media-id-edit-hidden" autocomplete="off">
                <input type="hidden" name="group" id="media-group-edit-hidden" autocomplete="off">

                <a class="media-item-update js-link mt20 btn btn-primary" href="#">Save</a>
            </form>
        </div>
    </div>
</div>
