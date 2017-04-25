<?
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\widgets\FileUpload as FileUpload;
use app\components\MediaLibrary;

?>
<div class="banner-edit">
<? Pjax::begin(); ?>
		<? $form = ActiveForm::begin(); ?>
		<div class="row single-picture-widget">
			<div class="col-sm-5">
				<?=$form->field($banner, 'title'); ?>
				<?=$form->field($banner, 'url'); ?>
				<?=$form->field($banner, 'color')->dropDownList([
					'#000' => 'Черный',
					'#fff' => 'Белый'
				]); ?>
				<?=$form->field($banner, 'config'); ?>
				<div class="button-row">
					<?= FileUpload::widget([
					    'model' => $banner,
					    'templatePath' => '/widgets/logo_upload',
					    'attribute' => 'image',
					    'url' => ['/admin/banners/bannerupload', 'id' => $banner->id],
					    'options' => ['accept' => 'image/*', 'class'=>'image-upload'],
					    'clientOptions' => [
					        'maxFileSize' => 2000000
					    ],
				        'clientEvents' => [
			            'fileuploaddone' => 'function(e, data) {
			            						window.loguploaddone(data, $(".new-logo img"), $("input[name=\"Banners[update_banner]\"]"));
			                                }',
			            ]
					]);?>
					<input type="hidden" name="Banners[update_banner]" value="0">
					<button class="default-grad remove-photo-btn">Отменить загрузку</button>
					<div class="clearfix"></div>
					<? foreach(['x1', 'y1', 'width', 'height', 'ratio'] as $attr)
						echo $form->field($banner, $attr, ['template'=>'{input}'])->hiddenInput(['class'=>$attr]); ?>
				</div>
				<div class="format">
					Допустимый формат: jpg, jpeg, png
					Допустимый размер изображения: Не более 2 мб
				</div>
				<button class="blue-grad">Сохранить</button>
			</div>
			<div class="col-sm-7">
				<div class="current-logo">
					<? if($banner->file) : ?>
						<img src="<?=MediaLibrary::getByFilename($banner->file)->getResizedUrl($banner->crop)?>" alt="" class="img-responsive">
					<? else : ?>
						<img src="/img/nophoto.png" alt="" class="img-responsive">
					<? endif ?>
				</div>
				<div class="new-logo">
					<img src="/img/nophoto.png" alt="" class="img-responsive" data-imgcrop='<?=$banner->config;?>'>
				</div>
			</div>
		</div>
		<? ActiveForm::end(); ?>
<? Pjax::end(); ?>
</div>