<?php

namespace app\modules\media\helpers;

use Yii;

class MediaHelper {
    private static $uplDir = null;

    public static function getUploadDir() {
        if (self::$uplDir === null) {
            self::$uplDir = '/media-storage/'.date('Y/m/');

            if (!Yii::$app->fs->has(self::$uplDir)) {
                Yii::$app->fs->createDir(self::$uplDir);
            }
        }

        return self::$uplDir;
    }
}
