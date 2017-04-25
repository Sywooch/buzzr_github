<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<? Pjax::begin(); ?>
		<?= GridView::widget([
		    'dataProvider' => $shops,
		    'columns' => [
		        'id',
		        'title',
		        'phone',
		        'created',
		        [
		        	'label'=>'Акт',
		        	'contentOptions' => ['class'=>'text-center'],
		        	'content'=>function($model){
		        		return $model->active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';
		        	}
	        	],
		        [
		        	'label'=>'Бан',
		        	'contentOptions' => ['class'=>'text-center'],
		        	'content'=>function($model){
		        		return '<a href="'.Url::toRoute(['shopban', 'id'=>$model->id]).'">' .
		        			($model->blocked ? '<i class="fa fa-ban"></i>' : '<i class="fa fa-check"></i>') .
		        			'</a>';
		        	}
	        	],
	            [
		        	'contentOptions' => ['class'=>'text-center'],
	            	'content' => function ($model){
		            		return
		            			'<a target="_blank" data-pjax="0" href="'.Url::toRoute(['store/main', 'code'=>$model->slug]).'"><i class="fa fa-eye"></i></a>&nbsp;'.
		            			'';

	            	}
			    ],
		    ],
		]) ?>
<? Pjax::end(); ?>
