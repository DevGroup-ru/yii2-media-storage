<?php

namespace app\modules\media\controllers;

use Yii;
use yii\web\Request;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\filters\AccessControl;
use app\modules\media\models\Media;
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

    public function actionShow()
    {
        return 'media item page';
    }

    public function actionSettings()
    {
        return $this->render('settings');
    }

    public function actionAdd()
    {
        return $this->render('upload-form');
    }

    public function actionUpload()
    {
        $file = UploadedFile::getInstanceByName('media-file');

        if ($file->hasError) {
            throw new HttpException(500, 'Upload error');
        }

        $request = Yii::$app->request;
        $title   = $request->post('media-title', null);

        $uplPath = MediaHelper::getUploadPath();
        $uplDir  = MediaHelper::getUploadDir();

        $filename = $file->name;
        $i = 1;

        while(file_exists($uplPath.$uplDir.$filename)) {
            $filename = $file->baseName . '_' . $i++ . $file->extension;
        }

        $file->saveAs($uplPath.$uplDir.$filename);

        $media = new Media([
            'path'  => $uplDir.$filename,
            'title' => $title,
        ]);
        $media->save();

        $event = new MediaEvent;
        $event->message = $media->id;
        $this->trigger(self::EVENT_ADD, $event);

        return Json::encode(['result' => true]);
    }

    public function actionDelete($id)
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
