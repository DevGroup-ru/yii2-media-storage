<?php

namespace app\modules\media\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Request;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\imagine\Image;
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

    # TODO: Add code for checking permissions
    public function actionShowItem()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);

        if (!$id) {
            throw new Exception('Wrong fields in request');
            return;
        }

        $media = Media::findOne($id);

        if ($media === null) {
            throw new Exception('File not found');
            return;
        }

        if ($media->isImage()) {
            $tmpdir   = MediaHelper::getTmpDir();
            $ext      = '.' . pathinfo($media->path, PATHINFO_EXTENSION);
            $filepath = $tmpdir.$media->id.$ext;

            if (!file_exists($filepath)) {
                $file = Yii::$app->fs->read($media->path);
                file_put_contents($filepath, $file);
            }

            # Making thumbnail {
            $img_size = $request->get('size', 'full');

            if ($img_size === 'thumb') {
                $thumb_path = $tmpdir.$media->id.'_thumb'.$ext;

                if (!file_exists($thumb_path)) {
                    Image::thumbnail($filepath, 300, 225)->save($thumb_path);
                }

                $filepath = $thumb_path;
            }
            # }

            Yii::$app->response->xSendFile($filepath, null, ['inline' => true]);
        } else {
            // Code for other types
        }
    }

    public function actionNewItemForm()
    {
        return $this->render('add-item-form', [
            'media_groups' => MediaGroup::getForDropdown(),
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

        $upl_dir  = MediaHelper::getUploadDir();

        $filename = $file->name;
        $i        = 1;

        while(Yii::$app->fs->has($upl_dir.$filename)) {
            $filename = $file->baseName . '_' . $i++ . '.' . $file->extension;
        }

        Yii::$app->fs->write($upl_dir.$filename, file_get_contents($file->tempName));

        $media = new Media([
            'path'      => $upl_dir.$filename,
            'title'     => $title,
            'group_id'  => $group_id,
        ]);
        $media->save();

        $event = new MediaEvent;
        $event->message = [
            'id' => $media->id,
            'mime' => $media->getType(),
        ];
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

        return $this->redirect(['media/index']);
    }

    public function actionEditItem()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);

        if (!$id) {
            throw new Exception('Wrong fields in request');
            return;
        }

        $media = Media::findOne($id);

        if ($media === null) {
            throw new Exception('File not found');
            return;
        }

        return $this->render('edit-item', [
            'media' => $media,
            'media_groups' => MediaGroup::getForDropdown(),
        ]);
    }

    public function actionEditGroup()
    {
        return $this->render('edit-group');
    }

    public function actionDeleteItem($id)
    {
        $result = false;
        $media = Media::findOne($id);

        if ($media !== null) {
            Yii::$app->fs->delete($media->path);
            Yii::$app->fs->delete($media->thumbnail);

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
