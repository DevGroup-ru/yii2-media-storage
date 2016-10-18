<?php

namespace DevGroup\MediaStorage\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;

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
                        'actions' => ['all-files', 'media-meta'],
                        'allow' => true,
                        'roles' => ['mediastorage-administrate'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['*'],
                    ],
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


    public function actionMediaMeta()
    {
        $model = new Media(['scenario' => Media::SCENARIO_SEARCH]);
        $dataProvider = $model->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->post('hasEditable')) {
            $mediaId = Yii::$app->request->post('editableKey');
            $model = Media::findOne($mediaId);
            $out = Json::encode(['output' => '', 'message' => '']);
            $formData = Yii::$app->request->post($model->formName());
            $data = reset($formData);
            if ($model->load([$model->formName() => $data])) {
                $model->save();
            }
            return $out;
        }
        return $this->render(
            'media-meta',
            [
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]
        );
    }
}
