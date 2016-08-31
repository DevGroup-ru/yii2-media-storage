<?php

namespace DevGroup\MediaStorage\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;

/**
 * Class MediaController
 *
 * @package DevGroup\MediaStorage\controllers
 */
class MediaController extends BaseController
{
    const EVENT_ITEM_ADD = 'media-item-add';
    const EVENT_ITEM_CHANGE = 'media-item-change';
    const EVENT_ITEM_DELETE = 'media-item-delete';
    const EVENT_GROUP_ADD = 'media-group-add';
    const EVENT_GROUP_CHANGE = 'media-group-change';
    const EVENT_GROUP_DELETE = 'media-group-delete';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['all-files'],
                        'allow' => true,
                        'roles' => ['mediastorage-administrate'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['*'],
                    ]
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionAllFiles()
    {
        return $this->render('all-files');
    }
}
