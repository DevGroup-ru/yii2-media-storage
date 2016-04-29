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

        //        $this->insert(
        //            'media-storage',
        //            [
        //                'group_id' => 3,
        //                'author_id' => 101,
        //                'path' => '/media-storage/2016/04/B1rP8NhJmFk.jpg',
        //                '_title' => 'gow',
        //            ]
        //        );
        //        $this->batchInsert(
        //            'media-storage-groups',
        //            ['name', 'permissions',],
        //            [['Main', null], ['Cats', '[\"media-admin\"]'], ['Books', '[\"random-item\"]']]
        //        );

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
