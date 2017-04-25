<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class Cart extends ActiveRecord {
	
	public $store_id;
	
	public static function tableName(){
		return 'cart';
	}
	
	public function rules(){
		return [
			[['count'], 'integer'],
		];
	}
	
	public static function GetList($me){
		
		$items = Cart::find()
			->select(['cart.*', 'products.store_id as store_id'])
			->leftJoin('products', 'products.id = cart.product_id')
			->where(['cart.user_id'=>$me])
//			->orderBy(['cart.added' => SORT_DESC])
            ->all();
			
		$result = [];
		
		foreach($items as $item)
			$result[$item->store_id][] = $item;
		
		return $result;

	}

	public static function GetCount($me){
		
		$query = new Query;
		$query = $query->select('sum(`count`)')
			->from('cart')
			->where(['cart.user_id'=>$me])->scalar();
			
		return $query;
			

	}
	
	public static function loadProduct($product_id){
		
		if(Yii::$app->user->getIsGuest())
			return false;
		
		$me = Yii::$app->user->id;
		$result = Cart::findOne(['user_id'=>$me, 'product_id'=>$product_id]);
		if(!$result){
			$result = new Cart();
			$result->user_id = $me;
			$result->product_id = $product_id;
			$result->count = 1;
		}
		return $result;
	}
	
	public function get_product(){
		return $this->hasOne(Product::className(), ['id'=>'product_id']);
	}
	
	public static function cartToOrder($order){
		$me = Yii::$app->user->id;

		$cart = Cart::find()
			->leftJoin('products', 'products.id = cart.product_id')
			->where(['cart.user_id'=>$me])
			->andFilterWhere(['products.store_id'=>$order->store_id])
			->andFilterWhere(['products.id'=>$order->single_product])
			->all();
		
		foreach($cart as $cart_item){
			Yii::$app->db->createCommand()->insert('order_product', [
				'product_id' => $cart_item->product_id,
				'order_id' => $order->id,
				'price' =>  $cart_item->_product->price,
				'count' =>  $cart_item->count,
				'type' => 'default'
				]
			)->execute();
			
			$cart_item->delete();
		}
	}

}