<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Product;
use app\models\Order;
use yii\grid\GridView;
use yii\grid\ActionColumn;
?>

<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="filter">
				<? $form = ActiveForm::begin(); ?>
				<div class="row">
					<div class="col-sm-2">
						<? echo $form->field($filter, 'status')->dropDownList(Order::getStatusList(true))->label(false); ?>
					</div>
					<div class="col-sm-2">
						<button class="blue-grad">Фильтр</button>
					</div>
				</div>
				<? ActiveForm::end(); ?>
			</div>
			<?= GridView::widget([
			    'dataProvider' => $orders,
			    'columns' => [
			        'id',
			        'total',
			        [
			        	'label'=>'Статус',
			        	'content'=>function($model){
			        		$statuses = Order::getStatusList();
			        		return isset($statuses[$model->status]) ? $statuses[$model->status] : $model->status;
			        	}
			        	],
			        'created',
			        'updated',
			        'products_cnt',
			         [
			         	'content' => function($order) use ($store) {
			         		return '<a href="'.Url::toRoute(['store/details', 'order_id'=>$order->id, 'code'=>$store->slug]).'">Просмотр</a>';
			         	}
				    ],
			    ],
			]); ?>
		</div>
	</div>
</section>
