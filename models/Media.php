<?php

namespace app\modules\media\models;

use yii\db\ActiveRecord;

class Media extends ActiveRecord
{
	public $type;
	public $size;
	public $width;
	public $height;

	/**
	 * @return string the name of the table associated with this ActiveRecord class.
	 */
	public static function tableName()
	{
		return 'media-storage';
	}

	public function rules()
	{
		return [
		];
	}
}
