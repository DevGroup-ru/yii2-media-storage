<?php

namespace DevGroup\MediaStorage\commands;

use creocoder\flysystem\Filesystem;
use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\console\Controller;

class SynchronizeController extends Controller
{
    public function actionIndex()
    {
        /**
         * @var Filesystem $fs
         */
        $fs = Yii::$app->protectedFilesystem;
        $medias = Media::find()->all();
        foreach ($medias as $media) {
            if ($fs->has($media->path) === false) {
                $media->delete();
            }
        }
        $contents = $fs->listContents('', true);


        foreach ($contents as $content) {
            $media = Media::findOne(['path' => $content['path']]);
            if (is_null($media)) {
                $media = new Media();
                $media->loadDefaultValues();
                $media->mime = $fs->getMimetype($content['path']);
                $media->path = $content['path'];
                $media->save();
            }
        }
    }
}
