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
		<?php foreach($mediaLibrary as $media) { ?>
		<div class="thumbnail">
			<img src="" alt="" width="300">
			<div class="caption">
				<h3><?= $media->title ? : "File #{$media->id}" ?></h3>
				<p>some description</p>
				<p>
					<a class="btn btn-default" href="<?= Url::to(['media/show', 'id' => $media->id]) ?>">Details</a>
					<a class="btn btn-danger" href="#" data-id="<?= $media->id ?>">Delete</a>
				</p>
			</div>
		</div>
		<?php } ?>
    </div>
</div>
