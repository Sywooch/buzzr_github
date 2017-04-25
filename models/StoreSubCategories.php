<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class StoreSubCategories extends ActiveRecord {
	
	public static function tableName(){
		return 'store_sub_categories';
	}
	
	public static function updateForStore($store_id, $keys){
		Yii::$app->db->createCommand('DELETE FROM store_sub_categories WHERE store_id = :store_id', ['store_id'=>$store_id])->execute();
		foreach($keys as $key){
			$record = new StoreSubCategories;
			$record->store_id = $store_id;
			$record->sub_categorie_id = $key;
			$record->save();
		}
	}
	
	public static function updateVisibleProducts($store_id){
		$records = Product::find()->where(['store_id' => $store_id])->all();
		foreach($records as $record){
			$record->save(true, ['visible']);
		}
		
	}
}