<?php


namespace DevGroup\MediaStorage\actions;

use devgroup\dropzone\UploadAction;
use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\models\Media;
use Yii;

/**
 * Class UploadFromDropZoneAction
 * @package DevGroup\MediaStorage\actions
 * @todo afterUploadEvent
 */
class UploadFromDropZoneAction extends UploadAction
{
    public function init()
    {
        $this->uploadDir = MediaHelper::getTmpDir();
        $tmpHandler = null;
        if (!empty($this->afterUploadHandler)) {
            $tmpHandler = $this->afterUploadHandler;
        }
        $this->afterUploadHandler = function ($data) use ($tmpHandler) {
            if (is_callable($tmpHandler)) {
                call_user_func($tmpHandler, $data);
            }
            $filenameFullPath = $data['dirName'] . $data['filename'];
            $uploadFilename = MediaHelper::saveFileFromLocalToFlysystem(
                $filenameFullPath,
                MediaHelper::getProtectedFilesystem()
            );
            MediaHelper::removeTmpFile($filenameFullPath);
            //save file to DB
            $media = new Media();
            $media->loadDefaultValues();
            $media->path = $uploadFilename;
            $media->mime = MediaHelper::getProtectedFilesystem()->getMimetype($uploadFilename);
            $media->save();
            // \Yii::$app->trigger(Media::class, 'afterUploadEvent');
        };
    }
}
