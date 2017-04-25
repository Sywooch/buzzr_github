<?
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Url;
use app\models\User;
use app\widgets\FileUpload;
use app\components\MediaLibrary;

?>
<section class="cabinet">
	<div class="container narrow">
		<? echo $this->render('/cabinet/header', ['user'=>Yii::$app->user->identity]); ?>
		<div class="user-edit">
			<h3>Настройка учетной записи</h3>
			<div class="row single-picture-widget">
				<div class="col-sm-6">
					
					<? $form = ActiveForm::begin(); ?>
					<p>Фотография</p>
							<div class="current-logo">
								<? if($user->avatar) : ?>
									<img src="<?=MediaLibrary::getByFilename($user->avatar)
										->getResizedUrl($user->avatar_crop . ' -resize 300x200')?>" alt="" class="img-responsive">
								<? else : ?>
									<img src="/img/nophoto.png" alt="" class="img-responsive">
								<? endif ?>
							</div>
							<div class="new-logo">
								<img src="/img/nophoto.png" alt="" class="img-responsive" data-imgcrop='{"aspectRatio":"1:1"}'>
							</div>
							<br>
						<div class="button-row">
							<?= FileUpload::widget([
							    'model' => $user,
							    'templatePath' => '/widgets/logo_upload',
							    'attribute' => 'avatar_tmp',
							    'url' => ['cabinet/user/avatar'],
							    'options' => ['accept' => 'image/*', 'class'=>'image-upload'],
							    'clientOptions' => [
							        'maxFileSize' => 2000000
							    ],
						        'clientEvents' => [
					            'fileuploaddone' => 'function(e, data) {
			            						window.loguploaddone(data, $(".new-logo img"), $("input[name=\"User[update_avatar]\"]"));

					                                }',
					            ]
							]);?>
							<input type="hidden" name="User[update_avatar]" value="0">
							<? foreach(['x1', 'y1', 'width', 'height', 'ratio'] as $attr)
								echo $form->field($user, $attr, ['template'=>'{input}'])->hiddenInput(['class'=>$attr]); ?>

						</div>
						<p class="format">
							Допустимый формат: jpg, jpeg, png
							Допустимый размер изображения: Не более 2 мб
						</p>
						<a href="#" class="remove-photo-btn">Отменить загрузку фотографии</a>
					<button class="blue-grad">СОХРАНИТЬ</button>
					<? ActiveForm::end(); ?>
				</div>
				<div class="col-sm-6">
					<? $form = ActiveForm::begin(); ?>
						<p>Основные данные</p>
						<? echo $form->field($user, 'name'); ?>
						<? echo $form->field($user, 'email'); ?>
						<? echo $form->field($user, 'phone'); ?>
						<? echo $form->field($user, 'address'); ?>
						<button class="blue-grad">СОХРАНИТЬ</button>
						<? ActiveForm::end(); ?>
	
					<? if(!$user->isSocial()) : ?>
					<hr>
					<? $form = ActiveForm::begin(); ?>
						<p>Изменение пароля</p>
						<? echo $form->field($user, 'password_change'); ?>
						<? echo $form->field($user, 'password_confirm'); ?>
						<button class="blue-grad">СОХРАНИТЬ</button>
					<? ActiveForm::end(); ?>
					<? endif ?>
				</div>
			</div>
		</div>
</section>
