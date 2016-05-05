<?php


namespace DevGroup\MediaStorage\actions;


use devgroup\dropzone\UploadAction;
use DevGroup\MediaStorage\helpers\MediaHelper;
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

        $this->afterUploadHandler = function ($data) {
            //            \Yii::$app->trigger(Media::class, 'afterUploadEvent');
            $filenameFullPath = $data['dirName'] . $data['filename'];
            MediaHelper::saveFileFromLocalToFlysystem($filenameFullPath, MediaHelper::getProtectedFilesystem());
            MediaHelper::removeTmpFile($filenameFullPath);
            //save file to DB
        };
    }

}