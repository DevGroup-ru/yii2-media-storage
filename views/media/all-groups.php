<?php

use yii\helpers\Url;
use yii\helpers\Json;
use app\modules\media\assets\GroupsAsset;

/**
 * @var yii\web\View $this
 */

GroupsAsset::register($this);

$this->title = 'Media Storage | Groups';

$this->params['breadcrumbs'][] = ['label' => 'Media Storage', 'url' => Url::to(['media/all-files'])];
$this->params['breadcrumbs'][] = 'Groups';
?>
<div class="media-storage-view">
    <div class="row mt20">
        <div class="col-md-8 media-storage-groups">
            <div>
                <a class="media-group-add js-link btn btn-primary" href="#">Add new group</a>
                <div class="spacer"></div>
                <a class="btn btn-primary" href="<?= Url::to(['media/all-files']) ?>">Items</a>
            </div>
            <ul class="list-group mt20">
                <?php foreach($media_groups as $group) { ?>
                <li class="list-group-item" data-all='<?= Json::encode($group->toArray()) ?>'>
                    <a class="media-group-delete js-link btn btn-danger btn-xs pull-right" href="<?= Url::to(['media/delete-group', 'id' => $group->id]) ?>">Delete</a>

                    <a href="<?= Url::to(['media/show-group', 'id' => $group->id]) ?>"><?= $group->name ?></a>
                    <span class="badge" title="Items in this group"><?= $group->getItemsCount() ?></span>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="media-group-form col-md-4" data-update="false" data-id="0">
            <h3 class="mt0" data-edit="Update group" data-add="Add new group">Add new group</h3>
            <div class="sidebar-form mt20">
                <form method="post" action="<?= Url::to(['media/save-group', 'id' => 0]) ?>">
                    <div class="form-group">
                        <label for="media-group-name">Name</label>
                        <input type="text" name="group-name" id="media-group-name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="media-group-permissions">Permissions</label>
                        <input type="text" name="group-permissions" id="media-group-permissions" class="form-control" disabled="disabled">
                    </div>
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" autocomlete="off">

                    <a class="media-group-save js-link btn btn-primary" href="<?= Url::to(['media/save-group', 'id' => 0]) ?>">Save</a>
                </form>
            </div>
        </div>
    </div>
</div>
