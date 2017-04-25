<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<? Pjax::begin(); ?>
		<?= GridView::widget([
		    'dataProvider' => $users,
		    'columns' => [
		        'id',
		        'name',
		        'email',
		        'phone',
		        [
		        	'label'=>'Акт',
		        	'contentOptions' => ['class'=>'text-center'],
		        	'content'=>function($model){
		        		return '<a href="'.Url::toRoute(['useract', 'id'=>$model->id]).'">' .
		        			($model->active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>') .
		        			'</a>';
		        	}
	        	],
		        [
		        	'label'=>'Бан',
		        	'contentOptions' => ['class'=>'text-center'],
		        	'content'=>function($model){
		        		return '<a href="'.Url::toRoute(['userban', 'id'=>$model->id]).'">' .
		        			($model->banned ? '<i class="fa fa-ban"></i>' : '<i class="fa fa-check"></i>') .
		        			'</a>';
		        	}
	        	],
		        'created',
	            [
		        	'contentOptions' => ['class'=>'text-center'],
		            	'content' => function ($model){
		            		return 
		            			'<a href="'.Url::toRoute(['userview', 'id'=>$model->id]).'"><i class="fa fa-eye"></i></a>&nbsp;'.
		            			'<a href="'.Url::toRoute(['userenter', 'id'=>$model->id]).'"><i class="fa fa-hand-o-right"></i></a>&nbsp;'.
		            			'';
		            		
		            	}
			    ],
		    ],
		]) ?>
<? Pjax::end(); ?>
