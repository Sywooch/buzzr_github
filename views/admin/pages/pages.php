<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<? Pjax::begin(); ?>
		<?= GridView::widget([
		    'dataProvider' => $pages,
		    'columns' => [
		        'id',
		        'page',
		        'title',
	            [
		        	'contentOptions' => ['class'=>'text-center'],
	            	'content' => function ($model){
		            		return 
		            			'<a data-pjax="0" href="'.Url::toRoute(['page', 'id'=>$model->id]).'"><i class="fa fa-edit"></i></a>&nbsp;'.
		            			'';
		            		
	            	}
			    ],
		    ],
		]) ?>
<? Pjax::end(); ?>
