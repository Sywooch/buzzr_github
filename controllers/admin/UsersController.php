<?
namespace app\controllers\admin;

use app\controllers\admin\BaseAdminController as Controller;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\User;


class UsersController extends Controller {
	public function actionIndex(){
		
		Url::remember();
		$users = new ActiveDataProvider(['query' => User::find(),
		     'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
		     'pagination' => [
		        'pageSize' => 10,
		    ],
		]);

		return $this->render('users', ['users'=>$users]);
	}
	public function actionUserban($id){
		$user = User::findIdentity($id);
		$user->banned = $user->banned ? 0 : 1;
		$user->save();
		return $this->goBack();
	}

	public function actionUseract($id){
		$user = User::findIdentity($id);
		$user->active = $user->active ? 0 : 1;
		$user->save();
		return $this->goBack();
	}

	public function actionUserenter($id){
		$user = User::findIdentity($id);
		Yii::$app->user->login($user);
		return $this->goBack();
	}
	public function actionUserview($id){
		$user = User::findIdentity($id);
		return $this->render('user', ['user'=>$user]);
	}
}