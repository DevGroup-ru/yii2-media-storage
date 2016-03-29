<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;

class Media extends ActiveRecord
{
	public $id;
	public $author;
	public $file;
	public $path;
	public $type;
	public $size;
	public $width;
	public $height;
	public $title;

	public function rules()
	{
		return [
		];
	}

	public function attributeLabels()
	{
        return [
            'id' => Yii::t('media', 'ID'),
            'author' => Yii::t('media', 'Uploader'),
			'file' => Yii::t('media', 'Filename'),
			'path' => Yii::t('media', 'File Location'),
			'type' => Yii::t('media', 'File Type'),
			'size' => Yii::t('media', 'File Size'),
			'width' => Yii::t('media', 'Image Width'),
			'height' => Yii::t('media', 'Image Height'),
            'title' => Yii::t('media', 'File Hint'),
        ];
    }
}
