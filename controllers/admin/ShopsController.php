<?
namespace app\controllers\admin;

use app\controllers\admin\BaseAdminController as Controller;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Store;


class ShopsController extends Controller {
	public function actionIndex(){
		
		Url::remember();
		$shops = new ActiveDataProvider(['query' => Store::find(),
		     'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
		    'pagination' => [
		        'pageSize' => 10,
		    ],
		]);

		return $this->render('shops', ['shops'=>$shops]);
	}
	public function actionShopban($id){
		$store = Store::findOne(['id'=>$id]);
		$store->blocked = $store->blocked ? 0 : 1;
		if($store->blocked)
			$store->active = 0;
			
		$store->save(false, ['blocked', 'active']);
		return $this->goBack();
	}
}