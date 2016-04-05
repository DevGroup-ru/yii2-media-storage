<?php

namespace app\modules\media\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Request;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\imagine\Image;
use app\modules\media\models\Media;
use app\modules\media\models\MediaGroup;
use app\modules\media\events\MediaEvent;
use app\modules\media\helpers\MediaHelper;

class MediaController extends Controller
{
    const EVENT_ITEM_ADD     = 'media-item-add';
    const EVENT_ITEM_CHANGE  = 'media-item-change';
    const EVENT_ITEM_DELETE  = 'media-item-delete';
    const EVENT_GROUP_ADD    = 'media-group-add';
    const EVENT_GROUP_CHANGE = 'media-group-change';
    const EVENT_GROUP_DELETE = 'media-group-delete';

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

    public function actionAllFiles()
    {
        return $this->render('all-files', [
            'media_library' => Media::find()->orderBy(['id' => SORT_DESC])->all(),
            'media_groups' => MediaGroup::getForDropdown(),
        ]);
    }

    public function actionAllGroups()
    {
        return $this->render('all-groups', [
            'media_groups' => MediaGroup::find()->orderBy(['id' => SORT_DESC])->all(),
        ]);
    }

    # TODO: Add code for checking permissions
    public function actionShowItem($id)
    {
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
            $request = Yii::$app->request;
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

    public function actionSaveItem($id)
    {
        if (empty($id)) {
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
                'author'    => Yii::$app->user->getId(),
                'group_id'  => $group_id,
            ]);
            $media->save();
        } else {
            $request  = Yii::$app->request;

            $media = Media::findOne($id);
            $media->title = $request->post('media-title', null);
            $media->group_id    = $request->post('media-group', 1);
            $media->save();
        }

        $event = new MediaEvent;
        $event->message = [
            'id' => $media->id,
            'mime' => $media->getType(),
        ];
        $this->trigger(self::EVENT_ITEM_ADD, $event);

        return Json::encode(['result' => true, 'id' => $media->id, 'redirect' => Url::to(['media/all-files'])]);
    }

    public function actionSaveGroup($id) {
        $request = Yii::$app->request;

        $name = $request->post('group-name', null);

        if (empty($name)) {
            throw new Exception('Wrong fields in request');
            return;
        }

        $group = $id ? MediaGroup::findOne($id) : new MediaGroup();
        $group->name = $name;
        $group->save();

        return Json::encode(['result' => true, 'id' => $group->id, 'redirect' => Url::to(['media/all-groups'])]);
    }

    public function actionDeleteItem($id)
    {
        $result = false;
        $media = Media::findOne($id);

        if ($media !== null) {
            Yii::$app->fs->delete($media->path);

            $result = $media->delete();

            if ($result) {
                $event = new MediaEvent;
                $event->message = $id;
                $this->trigger(self::EVENT_ITEM_DELETE, $event);
            }
        }

        return Json::encode(['result' => $result]);
    }

    public function actionDeleteGroup($id)
    {
        $result = false;
        $group = MediaGroup::findOne($id);

        if ($group !== null) {
            $result = $group->delete();

            if ($result) {
                $event = new MediaEvent;
                $event->message = $id;
                $this->trigger(self::EVENT_GROUP_DELETE, $event);
            }
        }

        return Json::encode(['result' => $result]);
    }
}
