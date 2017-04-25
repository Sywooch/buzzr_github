<?
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\MediaLibrary;

?>

<? Pjax::begin(); ?>
		<?= GridView::widget([
		    'dataProvider' => $banners,
		    'columns' => [
		        'id',
		        'title',
		        'url',
		        [
		        	'content' => function ($model){
		        		return '<img src="'.MediaLibrary::getByFilename($model->file)->getResizedUrl($model->crop . ' -resize 150x150').'" class="img-responsive">';
		        	}
	        	],
	            [
		        	'contentOptions' => ['class'=>'text-center'],
	            	'content' => function ($model){
		            		return 
		            			'<a data-pjax="0" href="'.Url::toRoute(['/admin/banners/bannerupdate', 'id'=>$model->id]).'"><i class="fa fa-edit"></i></a>&nbsp;'.
		            			'';
		            		
	            	}
			    ],
		    ],
		]) ?>
<? Pjax::end(); ?>
