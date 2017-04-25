<?php

namespace app\controllers\cabinet;

use Yii;
use app\controllers\cabinet\BaseCabinetController as Controller;
use app\models\Cart;
use app\models\Order;
use app\models\Store;
use yii\helpers\Url;

class CartController extends Controller {
	public function actionIndex(){
		
		if(Yii::$app->user->getIsGuest())
			return $this->goHome();

		Url::remember();

		$me = Yii::$app->user->id;
		$identity = Yii::$app->user->identity;
		
		$cart = Cart::GetList($me);
		
		$order = new Order;
		$order->user_id = $me;
		$order->name = $identity->name;
		$order->email = $identity->email;
		$order->phone = $identity->phone;
		$order->address = $identity->address;

		if($order->load($_POST) && $order->validate()){
			$order->save();
			Cart::cartToOrder($order);

			$cart = Cart::GetList($me);
			
			$store = Store::findOne(['id'=>$order->store_id]);
			
			Yii::$app->session->setFlash('message', 'Заказ для магазина <b>'.$store->title.'</b> получен');
			
			$order->notify();
			
			//return $this->redirect(['cabinet/cart/ok']);
		}
		
		return $this->render('cart', ['cart'=>$cart, 'order'=>$order]);
	}
	public function actionRemove($remove = null){
		
		if(Yii::$app->user->getIsGuest())
			return $this->goHome();

		$me = Yii::$app->user->id;
		
		if($remove){
			$cart = Cart::find()->where(['user_id'=>$me, 'product_id'=>$remove])->one();
			if($cart)
				$cart->delete();
		}
		
		return $this->redirect(['cabinet/cart']);
		
	}
	
}
