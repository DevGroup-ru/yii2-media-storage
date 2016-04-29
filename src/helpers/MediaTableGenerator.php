<?php

namespace DevGroup\MediaStorage\helpers;

use DevGroup\MediaStorage\models\ApplicableMediaModels;
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
    private $migration;
    /**
     * @var Connection
     */
    public $db = null;

    public function init()
    {
        if (is_null($this->db)) {
            $this->db = Yii::$app->db;
        }
        $this->migration = new Migration(['db' => $this->db]);
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

        $this->migration->insert(
            ApplicableMediaModels::tableName(),
            ['class_name' => $className::className(), 'name' => $className::tableName()]
        );

        $this->migration->createTable(
            $mediaTable,
            [
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
        $this->migration->delete(ApplicableMediaModels::tableName(), ['class_name' => $className::className()]);
    }

    /**
     * @param $className
     *
     * @return string
     * @throws \yii\base\NotSupportedException
     */
    protected function getMediaTableName($className)
    {
        $mediaTable = $this->db->getSchema()->getRawTableName($className::tableName()) . '_media';
        return $mediaTable;
    }

}
