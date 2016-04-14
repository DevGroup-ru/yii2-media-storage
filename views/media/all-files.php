<?php

use yii\helpers\Json;
use yii\helpers\Url;
use yii\bootstrap\Dropdown;
use branchonline\lightbox\Lightbox;
use app\modules\media\assets\FilesAsset;

/**
 * @var yii\web\View $this
 */

FilesAsset::register($this);

$this->title = 'Media Storage';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-storage-view">
    <div class="row">
        <div class="col-md-8 media-storage-library">
            <div>
                <a class="media-item-add js-link btn btn-primary" href="#">Add new item</a>
                <div class="spacer"></div>
                <a class="btn btn-primary" href="<?= Url::to(['/media/media/all-groups']) ?>">Groups</a>
            </div>

            <?php if (count($media_library) > 0) { ?>

            <div class="mt20">
                <?php foreach ($media_library as $media) { ?>
                <?php /*may be convert '_title' to 'title' with model 'fields' method*/ ?>
                <div class="thumbnail" data-all='<?= Json::encode($media->toArray()) ?>'>
                    <?= $media->showThumb(false) ?>

                    <div class="caption">
                        <h3><?= $media->title ? : "File #{$media->id}" ?></h3>

                        <p>
                            File group: <?= $media->group->name ?> <br>
                            File uploader: <?= $media->author_id ?>
                        </p>
                        <div class="mt20">
                            <a class="js-link btn btn-default" href="#">Edit</a>
                            <a class="js-link btn btn-danger" href="<?= Url::to(['/media/media/delete-item', 'id' => $media->id]) ?>">Delete</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <?php } else { ?>

            <p class="alert alert-warning mt20">Media library is empty.</p>

            <?php } ?>
        </div>
        <div class="media-item-new col-md-4">
            <h3 class="mt0">Add new item</h3>
            <div class="sidebar-form mt20">
                <form method="post" action="<?= Url::to(['/media/media/save-item', 'id' => 0]) ?>" data-redirect="<?= Url::to(['/media/media/all-files']) ?>">
                    <div class="dz-message">
                        <p>Put files here</p>
                    </div>

                    <input type="hidden" name="title" id="media-title-hidden" value="" autocomlete="off">
                    <input type="hidden" name="group" id="media-group-hidden" value="1" autocomlete="off">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" autocomlete="off">
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
                        echo Dropdown::widget([
                            'items' => $media_groups,
                        ]);
                        ?>
                    </div>

                    <a class="media-item-save js-link mt20 btn btn-primary" href="#">Save</a>
                </div>
            </div>
        </div>
        <div class="media-item-edit hidden col-md-4">
            <h3 class="mt0">Update item</h3>
            <form method="post" data-url="<?= Url::to(['/media/media/save-item', 'id' => 0]) ?>" class="sidebar-form mt20">
                <?php Lightbox::widget([]) # Output to /dev/null. Need to load Lightbox assets ?>

                <a class="lightbox-link" href="#" data-lightbox="media-item-edit-thumb" data-url="<?= Url::to(['/media/media/show-item', 'id' => 0]) ?>">
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
                    echo Dropdown::widget([
                        'items' => $media_groups,
                    ]);
                    ?>
                </div>

                <input type="hidden" name="id" id="media-id-edit-hidden" autocomplete="off">
                <input type="hidden" name="group" id="media-group-edit-hidden" autocomplete="off">

                <a class="media-item-update js-link mt20 btn btn-primary" href="#">Save</a>
            </form>
        </div>
    </div>
</div>
