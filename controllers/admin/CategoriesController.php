<?
namespace app\controllers\admin;

use app\controllers\admin\BaseAdminController as Controller;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Category;
use app\models\CategoryAttribute;
use app\models\AttributesUpdate;
use yii\web\UploadedFile;
use yii\db\Query;

class CategoriesController extends Controller {
	public function actionIndex($parent = 0){
		Url::remember();
		$parent_cat = Category::findOne(['id'=>$parent]);
		$cats = new ActiveDataProvider(['query' => Category::find()->where(['parent_id'=>$parent]),
		    'pagination' => [
		        'pageSize' => 50,
		    ],
		]);

		return $this->render('categories', ['cats'=>$cats, 'parent'=>$parent_cat]);
		
	}
	
	public function actionUpdate($id, $parent = 0, $attrib = null){
		
		if($id == 'new'){
			$model = new Category();
			$model->parent_id = $parent;
		} else {
			$model = Category::findOne(['id'=>$id]);
		}
		
		if(!$model)
			throw new \yii\web\NotFoundHttpException(); 
			
		$attributes = CategoryAttribute::listForCategory($id);
		$attributes_update = new AttributesUpdate();
		$attributes_update->category_id = $id;

		if(isset($_POST['delete']) && $attrib){
			if($attributes_update->tryDelete($attrib)){
				return $this->goBack();
			}
			
		}
		
		if($model->load($_POST) && $model->validate()){
			if(isset($_POST['save']))
				$model->save();
				return $this->goBack();
				
				
		}

		if($attributes_update->load($_POST) && $attributes_update->validate()){
			$attributes = CategoryAttribute::listForCategory($id);
		}
		
		return $this->render('update', ['model'=>$model, 'attributes'=>$attributes, 'attributes_update'=>$attributes_update]);
	}
	
	public function actionRemove($id){
		
		$model = Category::findOne(['id'=>$id]);
		
		if(!$model)
			throw new \yii\web\NotFoundHttpException(); 
			
		$count = 0;
		
		$q = new Query;
		$count += $q->select('count(*)')->from('products')->where(['category_type_id'=>$id])->scalar();
		$count += $q->select('count(*)')->from('store_sub_categories')->where(['sub_categorie_id'=>$id])->scalar();
		$count += $q->select('count(*)')->from('categories')->where(['parent_id'=>$id])->scalar();
		
		
		$status = ($count == 0);
		
		if($status){
			//$model->delete();
		}
			
		return $this->render('remove', ['category'=>$model, 'status'=>$status, 'count'=>$count]);
		
	}
	
}