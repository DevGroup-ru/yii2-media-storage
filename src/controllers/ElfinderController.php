<?php


namespace DevGroup\MediaStorage\controllers;


use DevGroup\MediaStorage\models\Media;
use mihaildev\elfinder\Controller;
use yii\helpers\ArrayHelper;

class ElfinderController extends Controller
{
    public function beforeAction($action)
    {
        $this->connectOptions = ArrayHelper::merge(
            $this->connectOptions,
            [
                'bind' => ['mkfile mkdir upload' => [$this, 'createRecord'],],
            ]
        );
        $this->roots = ArrayHelper::merge(
            $this->roots,
            ['baseRoot' => ['options' => ['attributes' => static::getAllMedias()]]]
        );
        return parent::beforeAction($action);
    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     *
     * @return void|true
     **/
    public function createRecord($cmd, $result, $args, $elfinder)
    {
        foreach (ArrayHelper::getValue($result, 'added', []) as $index => $added) {
            $result[$index]['hidden'] = false;
            $media = new Media();
            $media->loadDefaultValues();
            $media->mime = $added['mime'];
            /**
             * @var \elFinderVolumeDriver $volume
             */
            $volume = $elfinder->getVolume($added['hash']);
            $relatedPath = str_replace($volume->path($volume->defaultPath()) . '/', '', $volume->path($added['hash']));
            $media->path = $relatedPath;
            $media->save();
        }
        return $result;
    }

    public static function getAllMedias()
    {
        $medias = Media::find()->all();
        $result = array_reduce(
            $medias,
            function ($total, $item) {
                /**
                 * @var Media $item
                 */
                $total[] = [
                    'pattern' => '#^/' . $item->path . '$#',
                    'read' => true,
                    'write' => true,
                    'hidden' => false,
                    'locked' => false,
                ];
                return $total;
            },
            []
        );
        return $result;

    }
}