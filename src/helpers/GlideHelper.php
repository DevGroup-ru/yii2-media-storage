<?php


namespace DevGroup\MediaStorage\helpers;

use League\Glide\Server;
use League\Glide\ServerFactory;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class GlideHelper
{

    /**
     * @var Server[]
     */
    private static $_identityMap = [];

    public static function getServerByConfig($config = [])
    {
        $hash = md5(Json::encode($config));
        if (ArrayHelper::keyExists($hash, self::$_identityMap) === false) {
            $config = (new GlideServerConfigurator(['config' => $config]))->config;
            self::$_identityMap[$hash] = ServerFactory::create($config);
        }
        return self::$_identityMap[$hash];
    }
}
