<?php

use app\models\Thing;
use DevGroup\DataStructure\models\PropertyHandlers;
use DevGroup\DataStructure\models\PropertyStorage;
use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use DevGroup\MediaStorage\properties\MediaHandler;
use DevGroup\MediaStorage\properties\MediaStorage;
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
        Yii::$app->runAction(
            'migrate/up',
            [
                'interactive' => 0,
                'migrationPath' => '@DevGroup/DataStructure/migrations',
                'migrationTable' => 'migrations_data',
            ]
        );

        (new MediaTableGenerator(['db' => $this->db]))->generate(Thing::className());

        \DevGroup\DataStructure\helpers\PropertiesTableGenerator::getInstance()->generate(Thing::className()); //@ todo перенести в базовый генератор
        
    }

    public function down()
    {
        (new MediaTableGenerator(['db' => $this->db]))->drop(Thing::className());
        \DevGroup\DataStructure\helpers\PropertiesTableGenerator::getInstance()->drop(Thing::className());
        Yii::$app->runAction(
            'migrate/down',
            [
                'interactive' => 0,
                'migrationPath' => '@DevGroup/DataStructure/migrations',
                'migrationTable' => 'migrations_data',
            ]
        );
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
