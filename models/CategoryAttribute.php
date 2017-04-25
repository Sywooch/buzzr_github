<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;


class CategoryAttribute extends ActiveRecord {
	
	public static function tableName(){
		return 'category_attributes';
	}
	
	public static function findIdByName($name){
    	$query = new Query;
    	$res = $query->select('id')->from('attributes_list')->where(['name'=>$name])->column();
		return !empty($res) ? $res[0] : null;
	}
	
	public function rules(){
		return [
			[['attribute_id', 'attribute_id'], 'required']
		];
	}
	
	public static function addAttributeValues($name, $title, $attribute_vals){
    	$query = new Query;
    	$res = $query->select('id')->from('attributes_list')->where(['name'=>$name])->column();
    	
    	// find or create attribute list
    	$id = !empty($res) ? $res[0] : null;
    	if(!$id){
	    	Yii::$app->db->createCommand()->insert('attributes_list', ['name'=>$name, 'title'=>$title])->execute();
	    	$id = Yii::$app->db->getLastInsertID();
	    	
	    	foreach($attribute_vals as $label=>$value)
		    	Yii::$app->db->createCommand()->insert('attribute_value', ['attribute_id'=>$id, 'label'=>$label, 'value'=>$value])->execute();
    	}
	}
	
	public static function listForCategory($category_id){
		
		$query = new Query;
		$query = $query
			->select([
				'attribute_value.id as id',
				'attributes_list.title as attributeTitle',
				'attributes_list.id as attributeId',
				'attribute_value.label as attributeLabel',
				'attribute_value.id as attributeValueId'
				])
			->from(self::tableName())
			->where(['category_id'=>$category_id])
			->leftJoin('attributes_list', 'category_attributes.attribute_id = attributes_list.id')
			->leftJoin('attribute_value', 'attribute_value.attribute_id = category_attributes.attribute_id')
			->all();
			
		$attributes = [];
		$attributesIdToTitle = [];
		foreach($query as $qresult){

			$attributesIdToTitle[$qresult['attributeId']] = $qresult['attributeTitle'];

			if(!isset($attributes[$qresult['attributeId']]))
				$attributes[$qresult['attributeId']] = [];
				
			$attributes[$qresult['attributeId']]
				[$qresult['attributeValueId']] =
					$qresult['attributeLabel'];
		}
		
		$retval = [];
		
		foreach($attributes as $k=>$e){
			$retval[$k] = [$e, $attributesIdToTitle[$k]];
		}
		
		
		return $retval;
			
	}
	
}
