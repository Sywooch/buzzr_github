<?php

namespace app\controllers\cabinet;

use Yii;
use app\controllers\cabinet\BaseCabinetController as Controller;
use app\models\Cart;
use app\models\Order;
use app\models\Store;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

class HistoryController extends Controller {
	public function actionIndex(){
		
		if(Yii::$app->user->getIsGuest())
			return $this->goHome();
			
		$dataProvider = new ActiveDataProvider([
            'query' => Order::find()->where(['user_id'=>Yii::$app->user->id])->orderBy('created DESC'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

		Url::remember();
		
		return $this->render('history', ['listDataProvider' => $dataProvider]);
	}
}
