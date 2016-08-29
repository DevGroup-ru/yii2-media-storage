<?php


namespace DevGroup\MediaStorage;


use DevGroup\MediaStorage\components\MediaRule;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Yii::$classMap['creocoder\flysystem\Filesystem'] = __DIR__ . '/../Filesystem.php';
        if ($app instanceof \yii\console\Application) {
            MediaModule::module()->controllerNamespace = 'DevGroup\MediaStorage\commands';
        } elseif ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([['class' => MediaRule::class,],], false);
        }
    }
}