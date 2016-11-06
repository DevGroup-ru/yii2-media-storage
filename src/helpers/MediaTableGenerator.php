<?php

namespace DevGroup\MediaStorage\helpers;

use DevGroup\DataStructure\models\Property;
use DevGroup\MediaStorage\models\Media;
use Yii;
use yii\base\Object;
use yii\db\Connection;
use yii\db\Migration;

class MediaTableGenerator extends Object
{
    /**
     * @var Migration
     */
    private $migrationInstane;
    /**
     * @var Connection
     */
    public $db = null;

    public function init()
    {
        if (is_null($this->db)) {
            $this->db = Yii::$app->db;
        }
    }

    public function getMigration()
    {
        if ($this->migrationInstane === null) {
            $this->migrationInstane = new Migration(['db' => $this->db]);
        }
        return $this->migrationInstane;
    }
    /**
     * @param string $className
     *
     * @throws \yii\base\NotSupportedException
     */
    public function generate($className = '')
    {
        $tableOptions = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $mediaTable = $this->getMediaTableName($className);

        $this->migration->createTable(
            $mediaTable,
            [
                'property_id' => $this->migration->integer()->notNull(),
                'model_id' => $this->migration->integer()->notNull(),
                'media_id' => $this->migration->integer()->notNull(),
                'sort_order' => $this->migration->integer()->notNull()->defaultValue(0),
            ],
            $tableOptions
        );

        $this->migration->addPrimaryKey('uniquePair', $mediaTable, ['model_id', 'media_id']);
        $this->migration->addForeignKey(
            'fk' . crc32($className) . 'MS',
            $mediaTable,
            ['model_id'],
            $className::tableName(),
            ['id'],
            'CASCADE',
            'CASCADE'
        );
        $this->migration->addForeignKey(
            'fk' . crc32("from-{$mediaTable}-to-" . Property::tableName()) . 'PV',
            $mediaTable,
            'property_id',
            Property::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->migration->addForeignKey(
            'fk' . crc32("from-{$mediaTable}-to-" . $className::tableName()) . 'MV',
            $mediaTable,
            'media_id',
            Media::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    public function drop($className)
    {
        $this->migration->dropTable($this->getMediaTableName($className));
    }

    /**
     * @param $className
     *
     * @return string
     * @throws \yii\base\NotSupportedException
     */
    public function getMediaTableName($className)
    {
        $mediaTable = $this->db->getSchema()->getRawTableName($className::tableName()) . '_media';
        return $mediaTable;
    }
}
