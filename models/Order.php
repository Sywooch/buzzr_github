<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use app\models\filters\OrdersFilter;
use yii\db\Query;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;


class Order extends ActiveRecord {
	
	public $total, $products_cnt, $owner_id;
	public $single_product, $single_count;
	
	public static function getStatusList($andAll = false){
		
		$statuses = [
			'new' => 'Новый', 'done' => 'Закрыт', 'in_progress' => 'В обработке'
			]; 
			
			if($andAll)
				$statuses = ['0' => 'Все'] + $statuses;
			
		return $statuses;
	}
	
	public function rules(){
		return [
			[['status', 'comment', 'store_id', 'single_product', 'single_count'], 'safe'],
			['email', 'email'],
			[['name', 'email', 'phone', 'address'], 'required']
		];
	}

	public function attributeLabels(){
		return [
			'name' => 'Имя',
			'city' => 'Город',
			'phone' => 'Телефон',
			'address' => 'Адрес',
			'status' => 'Статус заказа',
			'total' => 'Общая стоимость',
			'products_cnt' => 'Кол-во товаров',
			'created' => 'Дата заказа',
			'updated' => 'Последнее изменение',
			'comment' => 'Дополнительная информация'
		];
	}
	
	public function behaviors()
	{
	    return [
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created',
	            'updatedAtAttribute' => 'updated',
	            'value' => new Expression('NOW()'),
	        ],
	        [
	            'class' => AttributeBehavior::className(),
	            'attributes' => [
	                ActiveRecord::EVENT_BEFORE_INSERT => 'status',
	            ],
	            'value' => function ($event) {
	                return 'new';
	            },
	        ],
	    ];
	}
	
	
	public function notify(){
		
		$site = Yii::$app->request->hostInfo;
		$order_seller_header = "Покупатель сделал заказ номер {$this->id} с сайта $site\n";
		$order_seller_header .= "-----\n";

		$order_client_header = "Вы сделали заказ номер {$this->id} с сайта $site\n";
		$order_client_header .= "-----\n";

		$order_content = "Данные заказчика:\n";
		$order_content .= "Имя: {$this->name}\n";
		$order_content .= "Почта: {$this->email}\n";
		$order_content .= "Телефон: {$this->phone}\n";
		$order_content .= "Адрес: {$this->address}\n";
		$order_content .= "Комментарий: {$this->comment}\n";
		$order_content .= "-----\n";

		$order_content .= "Товары:\n";
		
		foreach($this->getProducts() as $product_item){
			$product = Product::findById($product_item['product_id']);
			$order_content .= "Товар: {$product->title} количество: {$product_item['count']}\n";
			$actions = $product->_actions;
			if(!empty($actions))foreach($actions as $action){
				$product_action = Product::findById($action['stock_product_id']);
				$order_content .= "Товар по акции: {$product_action->title}\n";
			}
		}
		
		$notification = new Notification;
//		$notification->to = 'buggzy2@mail.ru';
		$notification->to = $this->store->user->email;
		$notification->from = 'buggzy228@gmail.com';
		$notification->text = $order_seller_header . $order_content;
		$notification->subject = "Новый заказ с сайта $site";
		
		$notification->send();

		$notification = new Notification;
		$notification->to = $this->email;
		$notification->from = 'buggzy228@gmail.com';
		$notification->text = $order_client_header . $order_content;
		$notification->subject = "Новый заказ с сайта $site";
		
		$notification->send();
	}
	
	public function getStore(){
		return $this->hasOne(Store::className(), ['id'=>'store_id']);
	}
	
	public static function jointQuery($filter){
		$query = Order::find();
		
		$query = $query -> select(['orders.*', 'store.user_id as owner_id', '(select sum(price*`count`) from order_product where order_id = orders.id) as total',
			'(select count(order_product.id) from order_product where order_id = orders.id) as products_cnt',
			
			]);
			
		$query = $query->leftJoin('store', 'store.id = orders.store_id');
			
		if($filter->id)
			$query = $query->andWhere(['orders.id'=>$filter->id]);

		if($filter->owner_id)
			$query = $query->andWhere(['store.user_id'=>$filter->owner_id]);
			
		if($filter->status)
			$query = $query->andWhere(['orders.status'=>$filter->status]);
			
		$query = $query -> orderBy(['id'=>SORT_DESC]);
			
		return $query;
	}
	
	public static function tableName(){
		return 'orders';
	}
	
	public static function One($id){
		$filter = new OrdersFilter;
		$filter->id = $id;
		return Order::jointQuery($filter)->one();
	}
	
	public function getProducts(){
		$query = new Query;
		$query = $query->select(['product_id', 'price', 'count', 'type'])
			->from('order_product')
			->where(['order_id'=>$this->id]);
		return $query->all();
	}

	public static function getOrdersProvider($filter){
		
		$query = Order::jointQuery($filter);
		
		if($filter->store_id)
			$query = $query->andWhere(['store_id'=>$filter->store_id]);
		
		$provider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
		    ],
		]);
		
		return $provider;
	}
	
	public static function getOrdersByUser(){
		$result = Order::findAll(['user_id'=>Yii::$app->user->id]);
		if(!$result)
			return false;
		
		return $result;
	}
}