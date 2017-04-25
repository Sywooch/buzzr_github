<?php

namespace app\models;
use yii\db\ActiveRecord;

class ArticleImage extends ActiveRecord {

	public static function tableName(){
		return 'store_blog_images';
	}
}
 