<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<? Pjax::begin(); ?>
	<div class="headr" style="padding-bottom: 40px;">
		<a href="<?=Url::toRoute(['create']);?>" class="pull-right orange-grad"><i class="fa fa-plus"></i> Добавить</a>
	</div>
	<?= GridView::widget([
	    'dataProvider' => $blocks,
	    'columns' => [
	        'id',
	        'title',
	        'sort',
            [
	        	'contentOptions' => ['class'=>'text-center'],
            	'content' => function ($model){
	            		return 
	            			'<a data-pjax="0" href="'.Url::toRoute(['update', 'id'=>$model->id]).'"><i class="fa fa-edit"></i></a>&nbsp;'.
	            			'<a data-pjax="0" href="'.Url::toRoute(['block-products', 'id'=>$model->id]).'"><i class="fa fa-chevron-down"></i></a>&nbsp;'.
	            			'<a data-pjax="0" href="'.Url::toRoute(['delete', 'id'=>$model->id]).'"><i class="fa fa-close"></i></a>&nbsp;'.
	            			'';
	            		
            	}
		    ],
	    ],
	]) ?>
<? Pjax::end(); ?>