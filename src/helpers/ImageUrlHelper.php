<?php


namespace DevGroup\MediaStorage\helpers;


use League\Glide\Server;
use League\Glide\ServerFactory;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

class ImageUrlHelper
{
    /**
     * @var Server[]
     */
    protected static $_identityMap = [];

    public static function getImageUrlByPath($path, $config = [])
    {
        //$serverConfig = ArrayHelper::remove($config, 'server');

        return Url::to([$pathTo . $action, $config]);
    }

    public static function getServerByConfig($config = [])
    {
        $hash = md5(Json::encode($config));

        if (ArrayHelper::keyExists($hash, self::$_identityMap) === false) {
            self::$_identityMap[$hash] = ServerFactory::create($config);
        }
        return self::$_identityMap[$hash];
    }
}