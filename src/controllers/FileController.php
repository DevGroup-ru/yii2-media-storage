<?php


namespace DevGroup\MediaStorage\controllers;

use DevGroup\MediaStorage\helpers\GlideHelper;
use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FileController extends Controller
{
    public function actionSend($mediaId, array $config = [])
    {
        $media = Media::findOne($mediaId);
        $fs = MediaHelper::getFlysystemByMedia($media);
        if (is_null($fs)) {
            throw new NotFoundHttpException();
        }
        if (is_null($media)) {
            Yii::$app->response->setStatusCode(400);
        } elseif ($media->mime === 'directory') {
            Yii::$app->response->setStatusCode(403);
        } elseif ($media->isImage()) {
            $config = ArrayHelper::merge(['serverConfig' => ['source' => $fs->getFilesystem()]], $config);
            $server = GlideHelper::getServerByConfig(ArrayHelper::getValue($config, 'serverConfig', []));
            $server->outputImage($media->path, ArrayHelper::getValue($config, 'imageConfig', []));
            exit; // cause of Yii rewrite headers by it's own Response object. Rewrite if Yii will use PSR-7 or HttpFoundation or write custom http://glide.thephpleague.com/1.0/config/responses/
        } else {
            $filename = basename($media->path);
            Yii::$app->response->sendContentAsFile($fs->read($media->path), $filename, ['mimeType' => $media->mime]);
        }
    }

    public function actionXsend($mediaId, $config = [])
    {
        $media = Media::findOne($mediaId);
        if (is_null($media)) {
            Yii::$app->response->setStatusCode(400);
        } elseif ($media->mime === 'directory') {
            Yii::$app->response->setStatusCode(403);
        } else {
            //@todo from conf
            //@todo no more protected FS
            $filename = basename($media->path);
            Yii::$app->response->sendContentAsFile(
                MediaHelper::getProtectedFilesystem()->read($media->path),
                $filename,
                ['mimeType' => $media->mime]
            );
            /**
             * @todo rewrite to this
             * // copy file to tmp dir
             * Yii::$app->response->xSendFile($path, $filename, ['mimeType' => $mime]);
             * // remove file from tmp dir
             */
        }

    }
}
