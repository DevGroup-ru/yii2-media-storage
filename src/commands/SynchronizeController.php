<?php

namespace DevGroup\MediaStorage\commands;

use creocoder\flysystem\Filesystem;
use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class SynchronizeController extends Controller
{
    public function actionIndex()
    {
        $medias = Media::find()->all();
        foreach ($medias as $media) {
            /**
             * @var Filesystem $fs
             */

            $fs = MediaHelper::getFlysystemByMedia($media);
            if (is_null($fs)) {
                $media->delete();
            }
        }
        $activeFsNames = ArrayHelper::getValue(Yii::$app->params, 'activeFsNames', ['protectedFilesystem']);
        foreach ($activeFsNames as $activeFsName) {
            /**
             * @var Filesystem $fs
             * @todo check cache folder
             */
            $fs = Yii::$app->get($activeFsName);
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
}
