<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController as Controller;
use app\models\Article;
use app\models\Store;
use app\models\User;
use app\models\filters\ArticleFilter;
use app\models\filters\SearchModel;
use yii\web\UploadedFile;
use yii\helpers\Url;

class NewsController extends Controller
{
	public function actionIndex(){
		
		Url::remember();
		
		$filter = new ArticleFilter;

		$filter->loadFilter($_POST);

		$filter->published = 1;

		$search = new SearchModel;
		$search->load($_POST);
		
		$filter->city = $search->city;

		$dataProvider = Article::getDataProvider($filter);
		return $this->render('index', ['data'=>$dataProvider, 'filter'=>$filter]);
	}
	
	public function actionSearch(){
		$filter = new ArticleFilter;

		$filter->loadFilter($_POST);
		
		$filter->published = 1;

		$search = new SearchModel;
		$search->loadFilter($_POST);
		
		$filter->city = $search->city;
		$filter->search_query = $search->search_query;

		$dataProvider = Article::getDataProvider($filter);
		Url::remember();
		return $this->render('index', ['data'=>$dataProvider, 'filter'=>$filter]);
	}
	
	public function actionLike($id){
		$article = Article::findOne(['id'=>$id]);
		return JSON_encode($article->toggleLike(\Yii::$app->user->id));
	}
	
	public function actionView($id){
		$filter = new ArticleFilter;
		$filter-> id = $id;
		$model = Article::jointQuery($filter)->one();
		if(!$model)
			throw new \yii\web\NotFoundHttpException("Article not found");

		return $this->render('view', ['model'=>$model]);
	}

	public function actionEdit($id){
		
		$filter = new ArticleFilter;
		$filter-> id = $id;
		$model = Article::jointQuery($filter)->one();
		if(!$model)
			throw new \yii\web\NotFoundHttpException("Article not found");
			
		if($model->load($_POST) && $model->validate()){
			$model->save();
			return $this->goBack();
		}

		return $this->render('edit', ['model'=>$model]);
	}

	public function actionCreate($store_id){
		$model = new Article;
		$model->store_id = $store_id;
		$model->user_id = Store::findOne(['id'=>$store_id])->user_id;

		if($model->load($_POST) && $model->validate()){
			$model->save();
			return $this->goBack();
		}

		return $this->render('edit', ['model'=>$model]);
			
	}
	
	public function actionDelete($id){
		$filter = new ArticleFilter;
		$filter-> id = $id;
		$model = Article::jointQuery($filter)->one();
		User::mustBeId($model->user_id);
		$model->delete();
		$this->goBack();
	}

	public function actionPublish($id){
		$filter = new ArticleFilter;
		$filter-> id = $id;
		$model = Article::jointQuery($filter)->one();
		User::mustBeId($model->user_id);
		
		if($model->load($_POST) && $model->validate()){
			$model->published = 1;
			$model->save();
			$this->goBack();
		}
		
		return $this->render('publish', ['model'=>$model]);
	}

	public function actionImageupload()
	{
		$model = new Article;
	    $model->image = UploadedFile::getInstance($model, 'image');
		if($result = $model->upload()){
			return JSON_encode($result);
		}
	    return '';
	}
}