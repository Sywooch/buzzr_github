<?php

namespace app\controllers\cabinet;

use Yii;
use app\controllers\cabinet\BaseCabinetController as Controller;
use app\models\Store;

class SubscriptionsController extends Controller {
	public function actionIndex(){
		
		$filter = ['subscribed_by'=>Yii::$app->user->id];
		
		return $this->render('subscriptions', ['data'=>Store::getDataProvider($filter)]);
	}
}