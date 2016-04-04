<?php

namespace app\modules\media\helpers;

use Yii;

class MediaHelper {
    private static $upl_dir = null;
    private static $tmp_dir = null;
    private static $storage_folder_name = '/media-storage/';

    public static function getTmpDir() {
        if (self::$tmp_dir === null) {
            self::$tmp_dir = Yii::getAlias('@runtime') . self::$storage_folder_name;

            if (!file_exists(self::$tmp_dir)) {
                mkdir(self::$tmp_dir, 0755, true);
            }
        }

        return self::$tmp_dir;
    }

    public static function getUploadDir() {
        if (self::$upl_dir === null) {
            self::$upl_dir = self::$storage_folder_name.date('Y/m/');

            if (!Yii::$app->fs->has(self::$upl_dir)) {
                Yii::$app->fs->createDir(self::$upl_dir);
            }
        }

        return self::$upl_dir;
    }
}
