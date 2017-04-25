<?
namespace app\controllers\admin;

use app\controllers\BaseController as Controller;
use Yii;
use yii\helpers\Url;
use app\models\User;

class BaseAdminController extends Controller {
	
	public function beforeAction($a){
		if(Yii::$app->user->getIsGuest() || !User::isAdmin())
			return $this->goHome()->send();
			
		return parent::beforeAction($a);
	}
	
	public function render($template, $params = []){
		$params['child_template'] = '/' . $this->id . '/' . $template;
		$params['params'] = $params;
		return parent::render('/admin/common', $params);
	}
}