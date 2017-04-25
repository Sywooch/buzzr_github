<?
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\filters\SearchModel;
use app\models\Cart;
use app\components\SEO;
use yii\helpers\Url;

class BaseController extends Controller {
	
	public function render($view, $data = []){

		$search = new SearchModel;
		$search->loadFilter($_POST);
		$this->view->params['headerSearchModel'] = $search;

		if(!Yii::$app->user->getIsGuest()){
			$this->view->params['cartModel'] = Cart::GetList(Yii::$app->user->id);
			$this->view->params['cartCount'] = Cart::GetCount(Yii::$app->user->id);
			$user = Yii::$app->user->identity;
			$user->activity = time();
			$user->save(false, ['activity']);
		}
		
		SEO::PlaceTags($this, $data);
		return parent::render($view, $data);
	}
	
}
