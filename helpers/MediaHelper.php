<?php

namespace app\modules\media\helpers;

use Yii;

class MediaHelper {
    private static $uplDir = null;
    private static $uplPath = null;

    private static function init()
    {
        if (self::$uplDir !== null && self::$uplPath !== null)
            return;

        self::$uplDir = '/media-storage/'.date('Y/m/');
        //self::$uplPath = Yii::getAlias('@app');
        self::$uplPath = Yii::getAlias('@webroot');

        if (!file_exists(self::$uplPath.self::$uplDir)) {
            mkdir(self::$uplPath.self::$uplDir, 0755, true);
        }
    }

    public static function getUploadDir() {
        if (self::$uplDir === null) {
            self::init();
        }

        return self::$uplDir;
    }

    public static function getUploadPath() {
        if (self::$uplPath === null) {
            self::init();
        }

        return self::$uplPath;
    }
}
