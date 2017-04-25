<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class StoreBanner extends ActiveRecord {
	public static function tableName(){
		return 'store_banners';
	}

}