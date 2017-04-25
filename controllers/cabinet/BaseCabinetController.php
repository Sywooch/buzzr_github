<?
namespace app\controllers\cabinet;

use app\controllers\BaseController as Controller;
use Yii;

class BaseCabinetController extends Controller {
	
	public function beforeAction($a){
		if(Yii::$app->user->getIsGuest())
			return $this->goHome()->send();
			
		return parent::beforeAction($a);
	}
	
	public function render($template, $params = []){
		$params['child_template'] = '/' . $this->id . '/' . $template;
		$params['params'] = $params;
		return parent::render('/cabinet/common', $params);
	}
}