<?php


namespace DevGroup\MediaStorage\controllers;

use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use DevGroup\MediaStorage\models\Media;
use dosamigos\transliterator\TransliteratorHelper;
use mihaildev\elfinder\Controller;
use Yii;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * Class BaseElfinderController
 * @package DevGroup\MediaStorage\controllers
 * Base controller for media, implements base events to administrate media files in elFinder window.
 * Do not use in production, use ElfinderController instead
 */
class BaseElfinderController extends Controller
{

    public function init()
    {
        $this->managerOptions = Yii::$app->request->get('managerOptions', []);
        $this->roots = ArrayHelper::merge(MediaHelper::loadRoots(), $this->roots);
        $this->connectOptions = ArrayHelper::merge(
            [
                'bind' => [
                    'mkfile mkdir upload duplicate paste' => [$this, 'createRecord'],
                    'rm' => [$this, 'removeRecord'],
                    'rm.pre' => [$this, 'removePre'],
                    'rename' => [$this, 'renameRecord'],
                    //                    'extract' => [],
                    //                    'archive' => [],
                    /**
                     * @todo duplicate create bad name of file "<file> copy N.ext" think how to rename it and write to db; archives
                     */
                    'mkdir.pre mkfile.pre rename.pre archive.pre' => [
                        'Plugin.Normalizer.cmdPreprocess',
                        'Plugin.Sanitizer.cmdPreprocess',
                        [$this, 'cmdPreprocessNameTransliteration'],
                        [$this, 'cmdPreprocessNameToLowercase'],
                    ],
                    'info' => [$this, 'resultModifier'],
                    'upload.presave' => [
                        'Plugin.Normalizer.onUpLoadPreSave',
                        'Plugin.Sanitizer.onUpLoadPreSave',
                        [$this, 'onUpLoadPreSaveNameTransliteration'],
                        [$this, 'onUpLoadPreSaveNameToLowercase'],
                    ],
                ],
                'plugin' => [
                    'Normalizer' => [
                        'enable' => true,
                        'nfc' => true,
                        'nfkc' => true,
                    ],
                    'Sanitizer' => [
                        'enable' => true,
                        'targets' => ['\\', '/', ':', '*', '?', '"', '<', '>', '|', ' ', '-', '(', ')'],
                        'replace' => '_',
                    ],
                ],
            ],
            $this->connectOptions
        );


    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     *
     * @return bool
     */
    public function createRecord($cmd, &$result, $args, $elfinder)
    {
        foreach (ArrayHelper::getValue($result, 'added', []) as $index => $added) {

            $media = new Media();
            $media->loadDefaultValues();
            $media->mime = $added['mime'];
            /**
             * @var \elFinderVolumeDriver $volume
             */
            $volume = $elfinder->getVolume($added['hash']);

            //            $relatedPath = str_replace($volume->path($volume->defaultPath()) . '/', '', $volume->path($added['hash']));
            $media->path = $volume->getPath($added['hash']);

            $data = $this->getCustomData();
            if ($media->save() && $data) {
                $result['added'][$index]['hidden'] = '0';
                $result['added'][$index]['read'] = 1;
                $result['added'][$index]['id'] = $media->id;
                $mediaId = $media->id;
                $tableName = (new MediaTableGenerator())->getMediaTableName($data['model']);

                (new Query())->createCommand()->insert(
                    $tableName,
                    [
                        'model_id' => $data['model_id'],
                        'property_id' => $data['property_id'],
                        'media_id' => $mediaId,
                    ]
                )->execute();
            }

        }
        return true;
    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     *
     * @return bool
     */
    public function resultModifier($cmd, &$result, $args, $elfinder)
    {
        foreach (ArrayHelper::getValue($result, 'files', []) as $index => $item) {
            $volume = $elfinder->getVolume($item['hash']);
            $media = Media::findOne(['path' => $volume->getPath($item['hash'])]);
            $result['files'][$index]['id'] = $media->id;
        }
        return true;
    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     *
     * @return bool
     */
    public function removeRecord($cmd, &$result, $args, $elfinder)
    {
        $data = $this->getCustomData();
        if (count($data) > 0) {
            return false;
        }
        foreach (ArrayHelper::getValue($result, 'removed', []) as $index => $removed) {
            /**
             * @var \elFinderVolumeDriver $volume
             */
            $volume = $elfinder->getVolume($removed['hash']);
            //            $relatedPath = str_replace(
            //                $volume->path($volume->defaultPath()) . '/',
            //                '',
            //                $volume->path($removed['hash'])
            //            );
            /**
             * @todo tree remove fix
             */
            $data = $this->getCustomData();

            if (empty($data) === true) {
                $media = Media::findOne(['path' => $volume->getPath($removed['hash'])]);
                $media->delete();
            }

        }
        return true;

    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     *
     * @return bool
     */
    public function renameRecord($cmd, &$result, $args, $elfinder)
    {
        $this->removeRecord($cmd, $result, $args, $elfinder);
        $this->createRecord($cmd, $result, $args, $elfinder);
        /**
         * @todo tree, rewrite, cause no need to delete records and create
         */
        return true;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $src
     * @param \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     */
    public function onUpLoadPreSaveNameTransliteration(&$path, &$name, $src, $elfinder, $volume)
    {
        $name = TransliteratorHelper::process($name, '', 'en');
    }

    /**
     * @param  string $cmd command name
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     **/
    public function cmdPreprocessNameTransliteration($cmd, &$args, $elfinder, $volume)
    {
        if (ArrayHelper::keyExists('name', $args)) {
            $args['name'] = TransliteratorHelper::process($args['name'], '', 'en');
        }
    }

    /**
     * @param  string $cmd command name
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     **/
    public function removePre($cmd, &$args, $elfinder, $volume)
    {
        $data = $this->getCustomData();
        if (count($data) > 0) {
            $targets = ArrayHelper::getValue($args, 'targets', []);
            $args = [];
            $result = ['preventexec' => true, 'results' => ['error' => false, 'removed' => []]];
            foreach ($targets as $index => $hash) {
                /**
                 * @var \elFinderVolumeDriver $volume
                 */
                $volume = $elfinder->getVolume($hash);
                $mediaId = Media::find()->where(['path' => $volume->getPath($hash)])->scalar();
                $tableName = (new MediaTableGenerator())->getMediaTableName($data['model']);
                (new Query())->createCommand()->delete(
                    $tableName,
                    [
                        'model_id' => $data['model_id'],
                        'property_id' => $data['property_id'],
                        'media_id' => $mediaId,
                    ]
                )->execute();
                $result['results']['removed'][] = ['hash' => $hash];
            }
            return $result;
        }
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $src
     * @param \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     */
    public function onUpLoadPreSaveNameToLowercase(&$path, &$name, $src, $elfinder, $volume)
    {
        $name = strtolower($name);
    }

    /**
     * @param  string $cmd command name
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     **/
    public function cmdPreprocessNameToLowercase($cmd, &$args, $elfinder, $volume)
    {
        if (ArrayHelper::keyExists('name', $args)) {
            $args['name'] = strtolower($args['name']);
        }
    }

    protected function getCustomData($getKeys = ['model', 'model_id', 'property_id'], $managerOptions = false)
    {
        $result = [];

        $get = ArrayHelper::merge(Yii::$app->request->get(), Yii::$app->request->getBodyParams());
        foreach ($getKeys as $key) {
            $customKey = $key;
            if ($managerOptions) {
                $customKey = 'managerOptions.customData.' . $key;
            }
            $val = ArrayHelper::getValue($get, $customKey);
            if (is_null($val) === false) {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    public function actionManager()
    {
        $handlers = ArrayHelper::remove($this->managerOptions, 'handlers', []);
        foreach ($handlers as $handlerName => $handler) {
            if (is_array($handler) && ArrayHelper::keyExists('expression', $handler)) {
                $handler = new JsExpression($handler['expression']);
            }
            $this->managerOptions['handlers'][$handlerName] = $handler;
        }
        return parent::actionManager();
    }
}
