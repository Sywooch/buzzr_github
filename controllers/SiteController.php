<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController as Controller;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Store;
use app\models\Banners;
use app\models\Product;
use app\models\Block;
use app\models\Category;
use app\models\Region;
use app\models\Article;
use app\models\InfoPage;
use app\models\filters\ProductFilter;
use app\models\filters\ArticleFilter;
use app\models\filters\SearchModel;
use app\models\ProductComment;
use app\models\User;
use yii\helpers\Url;


class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionInfo($page){
    	
    	$path = Yii::getAlias("@app/views/site/static/$page.php");
    	
    	if(file_exists($path)){
    		$codeback = Url::previous() ? '<a href="'.Url::previous().'" class="btn btn-default pull-right"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Назад</a>' : '';
    		return $this->render("/site/static/_main", ['template'=>$page, 'codeback'=>$codeback]);
    	}
    	
    	$model = InfoPage::findOne(['page'=>$page]);
    	if(!$model)
    		throw new \yii\web\HttpException(404, 'Page not found');
    	Url::remember();
    	return $this->render('infopage', ['model'=>$model]);
    }

	public function actionDeletecomment($id){
		$comment = ProductComment::findOne(['id'=>$id]);
		if(!$comment)
				throw new \yii\web\HttpException(404, 'Page not found');
				
		$product = Product::findOne(['id'=>$comment->product_id]);
		
		User::mustBeId($comment->user_id);

		$comment->delete();
		
		return $this->redirect(['catalog/product', 'path'=>$product->getCategoryPath(), 'product_code'=>$product->slug]);
		
	}

	public function actionEditcomment($id){
		$comment = ProductComment::findOne(['id'=>$id]);
		if(!$comment)
				throw new \yii\web\HttpException(404, 'Page not found');
		
		User::mustBeId($comment->user_id);
		
		if($comment->load($_POST) && $comment->validate()){
			if(isset($_POST['save']))
				$comment->save();
				
			$product = Product::findOne(['id'=>$comment->product_id]);
			return $this->redirect(['catalog/product', 'path'=>$product->getCategoryPath(), 'product_code'=>$product->slug]);
		}
	
		return $this->render('/catalog/edit-comment', ['comment'=>$comment]);
		
	}

   public function actionIndex()
    {
        $product_filter = new ProductFilter;
        $product_filter->sort_order = ProductFilter::SORT_POPULAR;

        $popularProducts = Product::getBlockProducts($product_filter, null, null);
        $blocks = Block::find()->with('products')->all();

        return $this->render('index', [
            'popularProducts' => $popularProducts,
            'blocks' => $blocks,
            'popularStores' => Store::jointQuery(['active'=>1, 'blocked'=>0])->orderBy(['visits'=>SORT_DESC])->limit(12)->all(),
            'banners' => Banners::find()->all()
            ]);
    }

	public function actionAddshop(){
		if(Yii::$app->user->getIsGuest()){
			Yii::$app->getSession()->setFlash('message', 'Для добавления магазина необходимо зарегистрироваться или войти в систему');
			return $this->redirect(['user/register']);
		}
		
		$me = Yii::$app->user->id;
		
		$store = Yii::$app->user->identity->mystore;
		
		if(!$store){
			$store = new Store();
			$store->user_id = $me;
			$store->save(false);
		}
		return $this->redirect(['store/aboutedit', 'id'=>$store->id]);
	}

    public function actionMap($id = null)
    {
    	$search = new SearchModel;
    	$search->loadFilter($_POST);

    	$product_filter = new ProductFilter;
    	$product_filter->city = $search->city;
    	$product_filter->sort_order = ProductFilter::SORT_POPULAR;

    	$filter = ['active'=>1, 'city'=>$search->city];
    	
    	$store = $id ? Store::findOne(['id'=>$id]) : null;
    	
        return $this->render('map', [
        	'popularProducts' => Product::getDataProvider($product_filter, 12),
        	'popularStores' => Store::jointQuery($filter)->orderBy(['visits'=>SORT_DESC])->limit(12)->all(),
        	'singleStore' => $store
        ]);
    }

    public function actionMapjson()
    {
    	$search = new SearchModel;
    	$search->loadFilter($_POST);
    	$filter = ['active'=>1, 'city'=>$search->city];
    	$data = Store::jointQuery($filter)->all();
        return $this->renderAjax('mapjson', ['data'=>$data]);
    }
    
    public function actionRegionsjson(){
    	return JSON_encode(Region::find()->asArray()->all());
    }
    
    public function actionSearch($limit_products = 4, $json = false, $query = null){
    	$search = new SearchModel;
    	$search->loadFilter($_POST);
    	
    	
    	$product_filter = new ProductFilter;
    	$product_filter->city = $search->city;
    	$product_filter->query = $search->search_query;
    	
    	$article_filter = new ArticleFilter;
    	$article_filter->city = $search->city;
    	$article_filter->search_query = $search->search_query;

		$store_filter = [];

		if($search->city)		
			$store_filter['city'] = $search->city;
			
		$store_filter['query'] = $search->search_query;
		$store_filter['active'] = 1;
		
		if($json){
			
			$result = [];
			
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			foreach(Category::find()->andWhere(['like', 'title', $query])->all() as $r){
				$result[$r->title] = true;
			}

			foreach(Product::find()->andWhere(['like', 'title', $query])->all() as $r){
				$result[$r->title] = true;
			}

			foreach(Store::find()->andWhere(['like', 'title', $query])->all() as $r){
				$result[$r->title] = true;
			}

			foreach(Article::find()->andWhere(['like', 'title', $query])->all() as $r){
				$result[$r->title] = true;
			}

			return array_keys($result);
		}
		Url::remember();
    	return $this->render('search', [
    		'category' => Category::getDataProvider($search->search_query, 40),
    		'products' => Product::getDataProvider($product_filter, $limit_products),
    		'stores' => Store::getDataProvider($store_filter),
    		'articles' => Article::getDataProvider($article_filter, 3),
		]);
    	
    }
    
    public function actionPing(){
    	return $this->render('ping');
    }

}
