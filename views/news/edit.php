<?
use yii\helpers\Url;
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\widgets\ActiveForm;
use app\widgets\FileUpload as FileUpload;
use dosamigos\ckeditor\CKEditor;

$url = Url::toRoute(['news/view', 'id'=>$model->id]);

?>
<section class="blog-single">
	<div class="container narrow">
		<div class="bordered">
			<a href="<?=Url::previous();?>" class="pull-right btn btn-default"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Назад</a>
			<div class="title">Создание публикации</div>
			<div class="clearfix"></div>
			<br>
			<? $form = ActiveForm::begin(); ?>
			<? echo $form->field($model, 'title'); ?>
			<? echo $form->field($model, 'text')->widget(CKEditor::className(), [
			        'options' => ['rows' => 6],
			        'preset' => 'basic',
			        'clientOptions' => [
			        	'removeButtons' => 'Image,Subscript,Superscript,RemoveFormat,Link,Unlink,Anchor,Table'
    				]
			    ]) ?>
			<div class="bordered">
				<p>Фотография</p>
				<?= FileUpload::widget([
				    'model' => $model,
				    'attribute' => 'image',
				    'templatePath' => '/widgets/uploadButton',
				    'url' => ['news/imageupload', 'id' => $model->id],
				    'options' => ['accept' => 'image/*', 'class'=>'image-upload'],
				    'clientOptions' => [
				        'maxFileSize' => 2000000
				    ],
			        'clientEvents' => [
		            'fileuploaddone' => 'function(e, data) {
		            						window.fileuploaddone(data, $(".imagelist"));
		                                }',
		            ]
				]);?>
				<div class="imagelist" data-limit="1" data-limit-target=".fileinput-button">
					<input type="hidden" class="image-list" name="Article[image_list]" value='<?=JSON_encode($model->images)?>'>
					<? echo $this->render('_images', ['model'=>$model]); ?>
				</div>
			</div>
			<div class="text-center">
				<button class="blue-grad">Сохранить</button>
			</div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</section>