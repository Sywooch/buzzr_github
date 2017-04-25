<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\MediaLibrary;
use yii\helpers\Html;
?>


<? Pjax::begin(); ?>

		<div class="headr">
			<a href="<?=Url::toRoute(['update', 'id'=>'new', 'parent'=>$parent ? $parent->id : 0]);?>" class="pull-right orange-grad"><i class="fa fa-plus"></i> Добавить</a>
			<ul class="breadcrumbs">
				<? $crumbs = $parent ? $parent->breadcrumbs : []; ?>
				<? $crumbs = array_merge([['id'=>0, 'title'=>'Верх']], $crumbs); ?>
				<? $i = 0; foreach($crumbs as $item) :
					$last = (++$i == count($crumbs));
				?>
				<li>
				<? if($last) : ?>
					<span class="last"><?=Html::encode($item['title'])?></span>
				<? else : ?>
					<a href="<?=Url::toRoute(['index', 'parent'=>$item['id']]);?>"><?=Html::encode($item['title'])?></a>
				<? endif ?>
				</li>
				<? if(!$last) : ?>
				<li><span class="separator">/</span></li>
				<? endif ?>
				<? endforeach ?>
			</ul>
			<div class="clearfix"></div>
		</div>
		<?= GridView::widget([
		    'dataProvider' => $cats,
		    'columns' => [
		        'id',
		        'title',
		        'sort',
	            [
		        	'contentOptions' => ['class'=>'text-center'],
	            	'content' => function ($model){
		            		return 
		            			'<a data-pjax="0" href="'.Url::toRoute(['update', 'id'=>$model->id]).'"><i class="fa fa-edit"></i></a>&nbsp;'.
		            			'<a data-pjax="1" href="'.Url::toRoute(['index', 'parent'=>$model->id]).'"><i class="fa fa-chevron-down"></i></a>&nbsp;'.
		            			'<a data-pjax="0" href="'.Url::toRoute(['remove', 'id'=>$model->id]).'"><i class="fa fa-close"></i></a>&nbsp;'.
		            			'';
		            		
	            	}
			    ],
		    ],
		]) ?>
<? Pjax::end(); ?>
