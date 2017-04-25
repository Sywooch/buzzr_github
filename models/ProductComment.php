<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class ProductComment extends ActiveRecord {
	
	public static function tableName(){
		return 'product_comment';
	}
	
	public function attributeLabels(){
		return [
			'name' => 'Ваше имя',
			'text' => 'Ваш отзыв'
		];
	}
	
	public function beforeSave($insert){
		
		if(Yii::$app->user->getIsGuest())
			return false;
		
		$this->created = date('Y-m-d h:i:s');
		$this->user_id = Yii::$app->user->id;
		return parent::beforeSave($insert);
	}
	
	public function rules(){
		return [
			[['text'], 'required'],
			['answer', 'safe'],
			['active', 'default', 'value'=>1]
		];
	}
	
	public function scenarios(){
		return [
			'default' => ['name', 'text'],
			'answer' => ['answer']
			];
	}
	
	public function getUser(){
		return $this->hasOne(User::className(), ['id'=>'user_id']);
	}
	
	public function getDataProvider($me){
		
		// мои комментарии или комментарии к моим товарам
		$query = Product::find()
			->innerJoin('product_comment', 'products.id = product_comment.product_id')
			->leftJoin('store', 'products.store_id = store.id')
			->where(['or', ['product_comment.user_id'=>$me], ['store.user_id'=>$me]])
			->groupBy('products.id');
		
		$provider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
		    ],
		]);
		return $provider;
	}
	
	public function getProduct(){
		return $this->hasOne(Product::className(), ['id'=>'product_id']);
	}
}