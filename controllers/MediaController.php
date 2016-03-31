<?php

namespace app\modules\media\controllers;

use Yii;
use yii\web\Request;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\filters\AccessControl;
use app\modules\media\models\Media;
use app\modules\media\models\MediaGroup;
use app\modules\media\events\MediaEvent;
use app\modules\media\helpers\MediaHelper;

class MediaController extends Controller
{
    const EVENT_ADD = 'media-add';
    const EVENT_CHANGE = 'media-change';
    const EVENT_DELETE = 'media-delete';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->accessPermissions,
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $mediaLibrary = Media::find()->all();

        return $this->render('index', [
            'mediaLibrary' => $mediaLibrary,
        ]);
    }

    public function actionShowItem()
    {
        return $this->render('show-item');
    }

    public function actionShowGroup()
    {
        return $this->render('show-group');
    }

    public function actionNewItemForm()
    {
        $media_groups = [];

        foreach(MediaGroup::find()->select(['id', 'name'])->all() as $group) {
            $media_groups[] = [
                'label'   => $group->name,
                'url'     => '#',
                'options' => ['data-id' => $group->id],
            ];
        }

        return $this->render('add-item-form', [
            'media_groups' => $media_groups,
        ]);
    }

    public function actionNewGroupForm()
    {
        return $this->render('add-group-form');
    }

    public function actionAddItem()
    {
        $file = UploadedFile::getInstanceByName('media-file');

        if ($file->hasError) {
            throw new HttpException(500, 'Upload error');
        }

        $request  = Yii::$app->request;
        $title    = $request->post('media-title', null);
        $group_id = $request->post('media-group', 1);

        $uplDir  = MediaHelper::getUploadDir();

        $filename = $file->name;
        $i = 1;

        while(Yii::$app->fs->has($uplDir.$filename)) {
            $filename = $file->baseName . '_' . $i++ . '.' . $file->extension;
        }

        Yii::$app->fs->write($uplDir.$filename, file_get_contents($file->tempName));

        $media = new Media([
            'path'     => $uplDir.$filename,
            'title'    => $title,
            'group_id' => $group_id,
        ]);
        $media->save();

        $event = new MediaEvent;
        $event->message = $media->id;
        $this->trigger(self::EVENT_ADD, $event);

        return Json::encode(['result' => true, 'tmp' => $file->tempName]);
    }

    public function actionAddGroup() {
        $request = Yii::$app->request;

        $name = $request->post('group-name', null);

        if (empty($name)) {
            throw new Exception('Wrong fields in request');
            return;
        }

        $media_group = new MediaGroup([
            'name' => $name,
        ]);
        $media_group->save();

        return $this->redirect(['media/show-group']);
    }

    public function actionDeleteItem($id)
    {
        $result = false;
        $media = Media::findOne($id);

        if ($media !== null) {
            $result = $media->delete();

            if ($result) {
                $event = new MediaEvent;
                $event->message = $id;
                $this->trigger(self::EVENT_DELETE, $event);
            }
        }

        return Json::encode(['result' => $result]);
    }
}
