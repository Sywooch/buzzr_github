<?
namespace app\controllers\admin;

use app\controllers\admin\BaseAdminController as Controller;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Banners;
use yii\web\UploadedFile;

class BannersController extends Controller {
	public function actionIndex(){
		Url::remember();
		$banners = new ActiveDataProvider(['query' => Banners::find(),
		    'pagination' => [
		        'pageSize' => 10,
		    ],
		]);

		return $this->render('banners', ['banners'=>$banners]);
	}
	public function actionBannerupdate($id){
		$banner = Banners::findOne(['id'=>$id]);
		
		if($banner->load($_POST) && $banner->validate()){
			
			if($banner->update_banner)
				$banner->file = $banner->update_banner;

			$banner->save();
			return $this->goBack();
		}
		
		return $this->render('banner-update', ['banner'=>$banner]);

	}
	public function actionBannerupload(){
		$model = new Banners;
	    $model->image = UploadedFile::getInstance($model, 'image');
		if($result = $model->upload()){
			return JSON_encode($result);
		}
	
	}
}