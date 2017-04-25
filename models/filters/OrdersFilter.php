<?php

namespace app\models\filters;

use Yii;
use yii\base\Model;
use yii\db\Query;

class OrdersFilter extends SessionSavedFilter {
	
	public $store_id, $id, $owner_id, $status;
	
	public function rules(){
		return [
			[['status'], 'safe'],
		];
	}
	
	
}