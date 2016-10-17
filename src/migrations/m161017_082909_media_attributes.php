<?php

use DevGroup\MediaStorage\models\Media;
use yii\db\Migration;

class m161017_082909_media_attributes extends Migration
{
    public function up()
    {
        $this->addColumn(Media::tableName(), 'alt', $this->string());
        $this->addColumn(Media::tableName(), 'title', $this->string());
    }

    public function down()
    {
        $this->dropColumn(Media::tableName(), 'title');
        $this->dropColumn(Media::tableName(), 'alt');
    }

}
