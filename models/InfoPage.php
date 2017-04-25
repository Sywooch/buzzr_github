<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class InfoPage extends ActiveRecord {
	public static function tableName(){
		return 'pages';
	}
	
	public function rules(){
		return [
			[['title', 'page', 'content'], 'safe']
		];
	}
}