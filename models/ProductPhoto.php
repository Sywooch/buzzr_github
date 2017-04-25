<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ProductPhoto extends ActiveRecord {
	public static function tableName(){
		return 'product_photos';
	}

}