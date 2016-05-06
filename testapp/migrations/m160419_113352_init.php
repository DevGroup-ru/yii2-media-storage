<?php

use app\models\Thing;
use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use yii\db\Migration;

class m160419_113352_init extends Migration
{
    public function up()
    {
        $this->createTable(
            'thing',
            [
                'id' => $this->primaryKey(),
                'prop' => $this->string(),
            ]
        );
        Yii::$app->runAction(
            'migrate/up',
            [
                'interactive' => 0,
                'migrationPath' => '@vendor/yiisoft/yii2/rbac/migrations',
                'migrationTable' => 'migrations_rbac',
            ]
        );
        Yii::$app->runAction(
            'migrate/up',
            [
                'interactive' => 0,
                'migrationPath' => '@DevGroup/MediaStorage/migrations',
                'migrationTable' => 'migrations_media',
            ]
        );

        (new MediaTableGenerator(['db' => $this->db]))->generate(Thing::className());
    }

    public function down()
    {
        (new MediaTableGenerator(['db' => $this->db]))->drop(Thing::className());
        Yii::$app->runAction(
            'migrate/down',
            [
                'interactive' => 0,
                'migrationPath' => '@DevGroup/MediaStorage/migrations',
                'migrationTable' => 'migrations_media',
            ]
        );
        Yii::$app->runAction(
            'migrate/down',
            [
                'interactive' => 0,
                'migrationPath' => '@vendor/yiisoft/yii2/rbac/migrations',
                'migrationTable' => 'migrations_rbac',
            ]
        );
        $this->dropTable('thing');
    }
}
