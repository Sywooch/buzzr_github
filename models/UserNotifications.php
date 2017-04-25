<?
namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserNotifications extends Model {
	public static function getNotifications($user_id = null){
		
		if(!$user_id)
			$user_id = \Yii::$app->user->id;
		
		$notifications = [];
		
		$user = User::findIdentity($user_id);
		
		$notifications['messages']  = self::getLastMessagesCount($user_id);
		$notifications['orders']  = self::getLastOrdersCount($user_id, 0 + $user->last_order);
		$notifications['comments'] = self::getLastReviewsCount($user_id, 0 + $user->last_comment);

		$nt = [];
		foreach($notifications as $k=>$v){
			if($v)
				$nt[$k] = $v;
		}		
		
		return $nt;
	}
	
	public static function shutup($keys = ['last_messages', 'last_order', 'last_comment'], $user_id = null){
		
		if(!is_array($keys))
			$keys = [$keys];
		
		if(!$user_id)
			$user_id = \Yii::$app->user->id;
			
		$user = User::findIdentity($user_id);
		
		foreach($keys as $key){
			$user->$key = time();
			$user->save(false);
		}
			
	}
	
	public function getLastMessagesCount($user_id){
		
		$contragents = Contragent::getContragents($user_id);
		$count = 0;
		
		foreach($contragents as $c){
			$count += $c->unread;
		}
		
		return $count;
		
	}

	public function getLastOrdersCount($user_id, $ts){
		$stores_ids = ArrayHelper::getColumn(Store::find()->where(['user_id'=>$user_id])->all(), 'id');
		return Order::find()->where(['store_id'=>$stores_ids])->andWhere("updated > from_unixtime($ts)")->count();
	}
	
	public function getLastReviewsCount($user_id, $ts){
		$stores_ids = ArrayHelper::getColumn(Store::find()->where(['user_id'=>$user_id])->all(), 'id');
		return ProductComment::find()->leftJoin('products', 'products.id = product_comment.product_id')
			->andWhere(['products.store_id'=>$stores_ids])
			->andWhere("product_comment.created > from_unixtime($ts)")
			->count();
	}
}