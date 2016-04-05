<?php

namespace app\modules\media\commands;

use Yii;
use yii\console\Controller;
use app\modules\media\helpers\MediaHelper;

class CleanController extends Controller
{
    public function actionIndex()
    {
        $dir = MediaHelper::getTmpDir();
        $now = time();

        foreach(scandir($dir) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $time  = filemtime($dir.$file);
            $delta = $now - $time;

            # Delete the file if it older than 24 hours
            if ($delta > 86400) {
                unlink($dir.$file);
            }
        }
    }
}
