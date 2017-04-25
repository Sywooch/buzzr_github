<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController as Controller;
use app\models\Category;
use app\models\CategoryAttribute;
use app\models\Store;
use app\models\Product;
use app\models\ProductComment;
use app\models\Cart;
use app\models\CountVisit;
use app\models\filters\ProductFilter;
use app\models\filters\SearchModel;
use yii\helpers\Url;

class CatalogController extends Controller
{
	public function actionIndex($path = ''){

		$parent_cat = Category::path2parent($path);

		if($parent_cat){			
			$model = Category::GetList($parent_cat->id);
			return $this->render('index_inner', ['model'=>$model, 'parent_cat'=>$parent_cat]);
		} elseif($path=='') {
			$model = Category::GetList(0);
			return $this->render('index_top', ['model'=>$model]);
		} else {
			throw new \yii\web\HttpException(404, 'Page not found');
		}
	}
	
	public function actionSearch(){
    	$search = new SearchModel;
    	$search->loadFilter($_POST);
		$filter = new ProductFilter;
		$filter->query = $search->search_query;
		
		if($search->search_in == SearchModel::ST_SERVICES)
			$filter->is_service = 1;
		else
			$filter->is_service = 0;
		
		$data = Product::getDataProvider($filter);
		
		Url::remember();
		
		return $this->render('list', ['data'=>$data, 'path'=>'any/any/any', 'attributes'=>[], 'filter'=>$filter, 'parent_cat'=>0]);
	}
	
	public function actionList($path = ''){
		$parent = Category::path2parent($path);
		$filter = new ProductFilter;
		if($parent)
			$filter->parent = $parent->id;

		$filter->loadFilter($_POST);

		$search = new SearchModel;
		$search->load($_POST);
		
		$filter->city = $search->city;

		$data = Product::getDataProvider($filter);
		$attributes = CategoryAttribute::listForCategory($parent ? $parent->id : -1);

		$subcats = $parent ? $parent->getChildren(true, false, true) : [];

		Url::remember();
		
		return $this->render('list', ['data'=>$data, 'path'=>$path, 'attributes'=>$attributes, 'filter'=>$filter, 'parent_cat'=>$parent, 'subcats'=>$subcats]);
	}
	
	public function actionProduct($path = ''){
		
		$product = Product::path2parent($path);
		
		if(!$product){
			throw new \yii\web\HttpException(404, 'Товар не найден');
		}

		$comment = new ProductComment;
		$comment->product_id = $product->id;
		if(!Yii::$app->user->getIsGuest())
			$comment->name = Yii::$app->user->identity->name;
		
		$cart = Cart::loadProduct($product->id);
		if($cart && $cart->load($_POST) && $cart->validate()){
			$cart->save();
			Yii::$app->session->setFlash('message', 'Товар добавлен в корзину');
		}
		
		if(!$product)
				throw new \yii\web\HttpException(404, 'Page not found');
				
		if($comment->load($_POST) && $comment->validate()){
			$comment->save();
			$comment = new ProductComment;
			$comment->product_id = $product->id;
			if(!Yii::$app->user->getIsGuest())
				$comment->name = Yii::$app->user->identity->name;
		}
		
		CountVisit::count($product->id, CountVisit::TYPE_PRODUCT);
		

		return $this->render('product', ['product'=>$product, 'commentModel'=>$comment, 'cart'=>$cart]);
	}

}
