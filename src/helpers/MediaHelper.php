<?php

namespace DevGroup\MediaStorage\helpers;

use creocoder\flysystem\Filesystem;
use DevGroup\MediaStorage\MediaModule;
use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\base\Object;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class MediaHelper extends Object
{

    /**
     * return default configuration from app params @see
     * DevGroup\MediaStorage\models\MediaStorageConfiguration::appParams
     *
     * @param int|string $number if isset and is int change logic - return only default configuration for current FS
     *     with this index
     *
     * @return array
     */
    public static function getFsDefaultCfg($number = '')
    {
        $result = Yii::$app->params['flysystemDefaultConfigs'];
        if (is_int($number)) {
            $configured = MediaModule::module()->activeFS[$number];
            $result = [ArrayHelper::merge($result[self::getFsCfgDropdown()[$configured['class']]], $configured)];
        }
        return $result;
    }

    /**
     * return array of available flysystems
     * @return array
     */
    public static function getFsCfgDropdown()
    {
        $cfg = self::getFsDefaultCfg();
        $res = [];
        foreach ($cfg as $name => $item) {
            $res[$item['class']] = $name;
        }
        return $res;
    }

    /**
     * return replaceable tpl in configuration form
     *
     * @param ActiveForm $form
     * @param ActiveRecord $model
     * @param string $number
     *
     * @return string
     * @throws \Exception
     */
    public static function getConfigurationTpl($form, $model, $number = '{{number}}')
    {
        $res = [];
        $cfg = self::getFsDefaultCfg($number);
        foreach ($cfg as $name => $item) {
            $necessaryContent = $form->field($model, "activeFS[{$number}][urlRule]")->textInput(
                ['value' => $item['urlRule']]
            )->label(Yii::t('devgroup.media-storage', 'Url rule'));
            foreach (ArrayHelper::getValue($item, 'necessary', []) as $necessaryConfName => $necessaryConfVal) {
                $content = $form->field(
                    $model,
                    "activeFS[{$number}][necessary][{$necessaryConfName}]"
                )->textInput(['value' => $necessaryConfVal])->label(
                    Yii::t('devgroup.media-storage', $necessaryConfName)
                );
                $necessaryContent .= $content;
            }
            $unnecessaryContent = '';
            foreach (ArrayHelper::getValue($item, 'unnecessary', []) as $unnecessaryConfName => $unnecessaryConfVal) {
                $unnecessaryContent .= $form->field(
                    $model,
                    "activeFS[{$number}][unnecessary][{$unnecessaryConfName}]"
                )->textInput(['value' => $unnecessaryConfVal])->label(
                    Yii::t('devgroup.media-storage', $unnecessaryConfName)
                );
            }
            $res[$item['class']] = Tabs::widget(
                [
                    'items' => [
                        ['label' => Yii::t('devgroup.media-storage', 'Necessary'), 'content' => $necessaryContent],
                        ['label' => Yii::t('devgroup.media-storage', 'Unnecessary'), 'content' => $unnecessaryContent],
                    ],
                ]
            );
        }
        return Json::encode($res);
    }

    /**
     * return roots config from app config
     *
     * @param null $where
     *
     * @return array
     */
    public static function loadRoots($where = null)
    {
        $configuredFSnames = ArrayHelper::getValue(Yii::$app->params, 'activeFsNames', []);
        if (count($configuredFSnames) === 0) {
            return [
                'baseRoot' => [
                    'class' => 'mihaildev\elfinder\flysystem\Volume',
                    'component' => [
                        'class' => 'creocoder\flysystem\LocalFilesystem',
                        'path' => '@app/media',
                    ],
                    'name' => 'protected',
                    'options' => [
                        'attributes' => static::loadAttrs($where),
                        'uploadOverwrite' => false,
                    ],

                ],
            ];
        }
        $res = [];
        foreach ($configuredFSnames as $configuredFSname) {
            $res[$configuredFSname] = [
                'class' => 'mihaildev\elfinder\flysystem\Volume',
                'component' => $configuredFSname,
                'name' => $configuredFSname,
                'options' => [
                    'attributes' => static::loadAttrs($where),
                    'uploadOverwrite' => false,
                ],
            ];
        }
        return $res;
    }

    /**
     * @param null|string|array $where
     *
     * @return array
     */
    public static function loadAttrs($where = null)
    {
        $ini = ArrayHelper::merge(
            [
                [
                    'pattern' => '#.*(\.tmb|\.quarantine|\.cache)$#i',
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
            static::loadMediasAttrs($where)
        );
        return $ini;
    }

    /**
     * @param null|string|array $where
     *
     * @return mixed
     */
    public static function loadMediasAttrs($where = null)
    {
        $mediasQuery = Media::find();
        if (is_null($where) === false) {
            $mediasQuery->where(['id' => $where]);
        }
        $medias = $mediasQuery->all();
        $result = array_reduce(
            $medias,
            function ($total, $item) {
                /**
                 * @var Media $item
                 */
                $total[] = [
                    'pattern' => '#^/' . preg_quote($item->path) . '$#',
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

    /**
     * @param Media $media
     *
     * @return Filesystem|null
     * @todo dependency
     */
    public static function getFlysystemByMedia($media)
    {
        return Yii::$app->cache->lazy(
            function () use ($media) {
                $activeFsNames = ArrayHelper::getValue(Yii::$app->params, 'activeFsNames', ['protectedFilesystem']);
                foreach ($activeFsNames as $activeFsName) {
                    /**
                     * @var Filesystem $fs
                     */
                    $fs = Yii::$app->get($activeFsName);
                    if ($fs->has($media->path)) {
                        return $fs;
                    }
                }
                return null;
            },
            'MediaFlysystem:' . $media->id
        );
    }

    public static function compileUrl($mediaId)
    {

    }
}
