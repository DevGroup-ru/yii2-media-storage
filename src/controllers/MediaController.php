<?php

namespace DevGroup\MediaStorage\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use Yii;

class MediaController extends BaseController
{
    const EVENT_ITEM_ADD = 'media-item-add';
    const EVENT_ITEM_CHANGE = 'media-item-change';
    const EVENT_ITEM_DELETE = 'media-item-delete';
    const EVENT_GROUP_ADD = 'media-group-add';
    const EVENT_GROUP_CHANGE = 'media-group-change';
    const EVENT_GROUP_DELETE = 'media-group-delete';


    public function actionAllFiles()
    {
        return $this->render('all-files');
    }

}
