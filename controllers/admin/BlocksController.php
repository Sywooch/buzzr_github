<?
namespace app\controllers\admin;

use app\controllers\admin\BaseAdminController as Controller;
use app\models\filters\ProductFilter;
use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\Block;
use app\models\Product;
use app\models\BlockProduct;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BlocksController extends Controller {

	public function actionIndex(){
		
		$blocks = new ActiveDataProvider(['query' => Block::find(),
		    'pagination' => [
		        'pageSize' => 10,
		    ],
		]);

		return $this->render('index', ['blocks'=>$blocks]);
	}


	public function actionCreate()
    {
        $model = new Block(['scenario' => Block::SCENARIO_CREATE]);
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	
	public function actionUpdate($id){

		$model = $this->findModel($id);
		$model->scenario = Block::SCENARIO_UPDATE;

		if($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect('index');
		}
	
		return $this->render('update', ['model'=>$model]);
	}

	public function actionView($id){

		$model = Product::findOne($id);

		return $this->render('view', ['model'=>$model]);
	}


	public function actionDelete($id)
    {
        $this->findModel($id)->delete();
 
        return $this->redirect(['index']);
    }

    public function actionBlockProductUpdate($id, $block_id){

		$model = BlockProduct::find()->where(['product_id' => $id, 'block_id' => $block_id])->one();
		$model->scenario = BlockProduct::SCENARIO_UPDATE;

		if($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['block-products', 'id' => $block_id]);
		}
	
		return $this->render('block-product-update', ['model'=>$model]);
	}




    public function actionRemoveSelectProduct()
    {
    	
    	 if (\Yii::$app->request->isAjax) {
			\Yii::$app->response->format = Response::FORMAT_JSON;
    	 	$result = false;
    	 	$product_id = Yii::$app->request->post('product_id');
    	 	
    	 	if ($product_id) {
    	 		\Yii::$app->db->createCommand()->update('products', ['select' => 0], 'id=' . $product_id)->execute();
    	 		\Yii::$app->db->createCommand()->delete('block_product', ['product_id' => $product_id])->execute();
    	 		$result = true;
    	 	}

			

        	 return ['result' => $result];
		}
      
    }

       protected function findModel($id)
    {
        if (($model = Block::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRemoveBlockProduct()
    {
        if (\Yii::$app->request->isAjax) {

            \Yii::$app->response->format = Response::FORMAT_JSON;
            $result = false;
            $product_id = Yii::$app->request->post('product_id');
            $block_id = Yii::$app->request->post('block_id');

            if($product_id){
            	\Yii::$app->db->createCommand()->delete('block_product', ['product_id' => $product_id, 'block_id' => $block_id])->execute();
            	 $result = true;
            }
           return ['result' => $result];
        }
        else
            throw new HttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionBlockProducts($id){

    	$i = -1;
		$block = Block::find()->with('products')->where(['id' => $id])->one();
		$blockProduct = BlockProduct::find()->where(['block_id' => $id])->all();
		$limit = BlockProduct::find()->where(['block_id' => $id])->count();

        $filter_popular = new ProductFilter();
        $popularProducts = Product::getBlockProducts($filter_popular, $limit, $block);

		foreach ($blockProduct as $key => $value) {
			$i++;
			if($value->product_id == $block->products[$i]->id){
				$block->products[$i]->sort = $value->sort;
			}
		}
		

		return $this->render('block-products', ['block'=>$block, 'popularProducts' => $popularProducts]);
	}



	public function actionProductsList(){

		$model = Block::find()->with('products')->where(['id' => $_GET['block_id']])->one();
		$products = Product::find()->where(['select' => 1])->all();

		if(isset($_POST['block_id'])) {

			$product_ids = $_POST['product_id'];
			$arrRecords = null;
			$i = -1;

			if(isset($product_ids)){
				foreach ($product_ids as $value) {
					$i++;
					$arrRecords[$i][] = $_POST['block_id'];
					$arrRecords[$i][] = $value;
				}

				Yii::$app->db->createCommand()->batchInsert('block_product', ['block_id', 'product_id'], $arrRecords)->execute();
			}
		
			return $this->redirect(['block-products', 'id' => $model->id]);

		}

		return $this->render('products-list', ['products'=>$products, 'model'=>$model ]);
	}

}