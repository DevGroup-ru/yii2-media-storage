<?php

use yii\helpers\Url;
use app\modules\media\assets\IndexAsset;

/**
 * @var yii\web\View $this
 */

IndexAsset::register($this);

$this->title = 'Media Storage';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-storage-library">
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary" href="<?= Url::to(['media/new-item-form']) ?>">Add new item</a>
            <div class="spacer"></div>
            <a class="btn btn-primary" href="<?= Url::to(['media/new-group-form']) ?>">Add new group</a>
        </div>
    </div>
    <div class="row mt20">
        <div class="col-md-12">
            <?php if (count($mediaLibrary) > 0) { ?>

            <?php foreach($mediaLibrary as $media) { ?>
            <div class="thumbnail">
                <?= $media->showThumb() ?>

                <div class="caption">
                    <h3><?= $media->title ? : "File #{$media->id}" ?></h3>
                    <p>File group: <?= $media->group->name ?></p>
                    <p>
                        <a class="btn btn-default" href="<?= Url::to(['media/edit-item', 'id' => $media->id]) ?>">Edit</a>
                        <a class="btn btn-danger" href="#" data-id="<?= $media->id ?>">Delete</a>
                    </p>
                </div>
            </div>
            <?php } ?>

            <?php } else { ?>

            <p class="alert alert-warning">Media library is empty.</p>

            <?php } ?>
        </div>
    </div>
</div>
