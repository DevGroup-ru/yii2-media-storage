<?php

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
            ['interactive' => 0, 'migrationPath' => '@vendor/yiisoft/yii2/rbac/migrations']
        );
        Yii::$app->runAction(
            'migrate/up',
            ['interactive' => 0, 'migrationPath' => '@DevGroup/MediaStorage/migrations']
        );
    }

    public function down()
    {
        $this->dropTable('thing');
        Yii::$app->runAction(
            'migrate/down',
            ['interactive' => 0, 'migrationPath' => '@vendor/yiisoft/yii2/rbac/migrations']
        );
        Yii::$app->runAction(
            'migrate/down',
            ['interactive' => 0, 'migrationPath' => '@DevGroup/MediaStorage/migrations']
        );
    }
}
