<?
namespace app\controllers\admin;

use app\controllers\admin\BaseAdminController as Controller;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\InfoPage;


class PagesController extends Controller {
	public function actionIndex(){
		Url::remember();
		$pages = new ActiveDataProvider(['query' => InfoPage::find(),
		    'pagination' => [
		        'pageSize' => 10,
		    ],
		]);

		return $this->render('pages', ['pages'=>$pages]);
	}
	
	public function actionPage($id){
		$page = InfoPage::findOne(['id'=>$id]);
		if($page->load($_POST) && $page->validate()){
			$page->save();
			return $this->goBack();
		}
		
		return $this->render('page', ['page'=>$page]);
	}
}