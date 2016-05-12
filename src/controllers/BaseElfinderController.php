<?php


namespace DevGroup\MediaStorage\controllers;


use DevGroup\MediaStorage\models\Media;
use dosamigos\transliterator\TransliteratorHelper;
use mihaildev\elfinder\Controller;
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
        $this->roots = ArrayHelper::merge(
            [
                'baseRoot' => [
                    'basePath' => '@webroot/files/',
                    'path' => '',
                    'name' => '',
                    'options' => [
                        'attributes' => [
                            [
                                'pattern' => '#.*(\.tmb|\.quarantine)$#i',
                                'read' => false,
                                'write' => false,
                                'hidden' => true,
                                'locked' => false,
                            ],
                            [
                                'pattern' => '#.+[^/]$#',
                                'read' => false,
                                'write' => true,
                                'hidden' => true,
                                'locked' => false,
                            ],
                        ],
                        'uploadOverwrite' => false,
                    ],

                ],
            ],
            $this->roots
        );

        $this->connectOptions = ArrayHelper::merge(
            [
                'bind' => [
                    'mkfile mkdir upload' => [$this, 'createRecord'],
                    'rm' => [$this, 'removeRecord'],
                    'mkdir.pre mkfile.pre rename.pre archive.pre' => [
                        'Plugin.Normalizer.cmdPreprocess',
                        'Plugin.Sanitizer.cmdPreprocess',
                        [$this, 'cmdPreprocessNameTransliteration'],
                        [$this, 'cmdPreprocessNameToLowercase'],
                    ],
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
                        'targets' => ['\\', '/', ':', '*', '?', '"', '<', '>', '|', ' ', '-'],
                        'replace' => '_',
                    ],
                ],
            ],
            $this->connectOptions
        );
        $this->roots = ArrayHelper::merge(
            $this->roots,
            ['baseRoot' => ['options' => ['attributes' => static::getAllMedias()]]]
        );

    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     **
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
            $relatedPath = str_replace($volume->path($volume->defaultPath()) . '/', '', $volume->path($added['hash']));
            $media->path = $relatedPath;
            $media->save();
        }
        return true;
    }

    /**
     * @param  string $cmd command name
     * @param  array $result command result
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     **
     *
     * @return bool
     */
    public function removeRecord($cmd, &$result, $args, $elfinder)
    {
        foreach (ArrayHelper::getValue($result, 'removed', []) as $index => $removed) {
            /**
             * @var \elFinderVolumeDriver $volume
             */
            $volume = $elfinder->getVolume($removed['hash']);
            $relatedPath = str_replace(
                $volume->path($volume->defaultPath()) . '/',
                '',
                $volume->path($removed['hash'])
            );
            /**
             * @todo tree fix
             */
            $media = Media::findOne(['path' => $relatedPath]);
            $media->delete();
        }
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
        if ($path) {
            $path = TransliteratorHelper::process($path, '', 'en');
        }
        $name = TransliteratorHelper::process($name, '', 'en');
    }

    /**
     * @param  string $cmd command name
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     *
     **/
    public function cmdPreprocessNameTransliteration($cmd, &$args, $elfinder, $volume)
    {
        if (ArrayHelper::keyExists('name', $args)) {
            $args['name'] = TransliteratorHelper::process($args['name'], '', 'en');
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
        if ($path) {
            $path = strtolower($path);
        }
        $name = strtolower($name);
    }

    /**
     * @param  string $cmd command name
     * @param  array $args command arguments from client
     * @param  \elFinder $elfinder elFinder instance
     * @param \elFinderVolumeDriver $volume elFinderVolumeDriver instance
     *
     **/
    public function cmdPreprocessNameToLowercase($cmd, &$args, $elfinder, $volume)
    {
        if (ArrayHelper::keyExists('name', $args)) {
            $args['name'] = strtolower($args['name']);
        }
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