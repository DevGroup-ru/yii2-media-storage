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
<div class="media-storage-library row">
    <div class="col-md-12">
        <?php if (count($mediaLibrary) > 0) { ?>

        <?php foreach($mediaLibrary as $media) { ?>
        <div class="thumbnail">
            <?php if ($media->isImage()) { ?>
            <img src="<?= $media->path ?>" alt="<?= $media->title ?>">
            <?php } ?>

            <div class="caption">
                <h3><?= $media->title ? : "File #{$media->id}" ?></h3>
                <p><?= $media->path ?></p>
                <p>
                    <a class="btn btn-default" href="<?= Url::to(['media/show', 'id' => $media->id]) ?>">Details</a>
                    <a class="btn btn-danger" href="#" data-id="<?= $media->id ?>">Delete</a>
                </p>
            </div>
        </div>
        <?php } ?>

        <?php } else { ?>

        <p class="alert alert-warning">Media library is empty. You can <a href="<?= Url::to(['media/add']); ?>">add new item</a> to it.</p>

        <?php } ?>
    </div>
</div>
