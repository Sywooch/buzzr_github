<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController as Controller;
use app\models\Category;
use app\models\Store;
use app\models\Product;
use app\models\filters\SearchModel;
use yii\helpers\Url;

class StoresController extends Controller
{
	public function actionIndex($service = false, $bricks = false){
		$filter = [];

		$search = new SearchModel;
		$search->loadFilter($_POST);

		if($search->city)		
			$filter['city'] = $search->city;
			
		if($service)
			$filter['is_service'] = ($service != 'stores') ? 1 : 0;
			
		$filter['active'] = 1;
		
		$provider = Store::getDataProvider($filter);
		Url::remember();
		return $this->render('index', ['data'=>$provider, 'service'=>$service, 'bricks' => $bricks]);
	}

	public function actionSearch(){
		$filter = [];

		$search = new SearchModel;
		$search->loadFilter($_POST);

		if($search->city)		
			$filter['city'] = $search->city;
			
		$filter['is_service'] = ($search->search_in == SearchModel::ST_ORGS) ? 1 : 0;
			
		$filter['query'] = $search->search_query;

		$filter['active'] = 1;
		Url::remember();
		$provider = Store::getDataProvider($filter);
		return $this->render('index', ['data'=>$provider, 'service'=>$filter['is_service']]);
	}
	
	public function actionLike($id){
		$store = Store::findOne(['id'=>$id]);
		return JSON_encode($store->toggleLike(\Yii::$app->user->id));
	}

	public function actionSubscribe($id){
		$store = Store::findOne(['id'=>$id]);
		return JSON_encode($store->toggleSubscribe(\Yii::$app->user->id));
	}


}