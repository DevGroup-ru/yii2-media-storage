<?php


namespace DevGroup\MediaStorage\controllers;

use DevGroup\MediaStorage\helpers\MediaHelper;
use DevGroup\MediaStorage\models\Media;
use dosamigos\transliterator\TransliteratorHelper;
use mihaildev\elfinder\Controller;
use Yii;
use yii\helpers\ArrayHelper;

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
            $media->save();
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
        } else {
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
                $media = Media::findOne(['path' => $volume->getPath($removed['hash'])]);
                $data = $this->getCustomData();
                if (count($data) > 0) {
                } else {
                    $media->delete();
                }
            }
            return true;
        }
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
         * @todo tree
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
            $args = [];
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

    protected function getCustomData($getKeys = ['model', 'model_id', 'property_id'])
    {
        $result = [];
        $get = Yii::$app->request->get();
        foreach ($getKeys as $key) {
            if (ArrayHelper::keyExists($key, $get)) {
                $result[$key] = $get[$key];
            }
        }
        return $result;
    }
}
