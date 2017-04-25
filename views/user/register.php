<?
use yii\widgets\ActiveForm;
use rmrevin\yii\ulogin\ULogin;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
$input_template = '<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}{error}{hint}</div></div>';

?>
<div class="modal-header nomodal-hide">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Регистрация
</div>
<div class="modal-body">
<? Pjax::begin(['id'=>'login_ajax']); ?>
<section class="login">
	<div class="container narrow">
		<div class="container-modal">
			<? $form = ActiveForm::begin(['id'=>'register_form','options'=>['data-pjax'=>1]]); ?>
			<? if($error) : ?>
		    <div class="alert alert-danger">
				<?=$error?>
			</div>
			<? endif ?>
			<? if($use_social) : ?>
				<div class="form-row">
					Имя: <?=$ulogin->uName; ?>
				</div>
				<div class="form-row">
					e-mail: <?=$ulogin->uEmail; ?>
				</div>
				<div class="form-row">
					<img src="<?=$ulogin->uAvatar; ?>" alt="">
				</div>
				<? echo $form->field($model, 'use_social')->hiddenInput()->label(false); ?>
				<button class="btn btn-primary">Регистрация на сайте</button>
			<? else : ?>
				<div class="login-box">
					<? echo $form->field($model, 'name', ['template'=>$input_template]); ?>
					<? echo $form->field($model, 'username', ['template'=>$input_template]); ?>
					<? echo $form->field($model, 'password', ['template'=>$input_template])->passwordInput(); ?>
					<? echo $form->field($model, 'password_confirm', ['template'=>$input_template])->passwordInput(); ?>
					<button class="btn btn-primary">Регистрация</button>
				</div>
				<div class="social text-center">
					Регистрация через социальные сети
					<script src="//ulogin.ru/js/ulogin.js"></script>
					<? $loginurl = Yii::$app->request->hostInfo . Url::toRoute(['user/uregister']); ?>
					<div id="uLogin-r" class="ulogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,email,photo_big;providers=vkontakte,facebook;redirect_uri=<?=$loginurl?>;mobilebuttons=0;"></div>
				</div>
			<? endif ?>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</section>
<? Pjax::end(); ?>
</div>
<div class="modal-footer agree text-center">
	<? $agreeurl = Url::toRoute(['info/agreement']); ?>
	<? $conurl = Url::toRoute(['info/agreement']); ?>
	Регистрируясь вы подтверждаете, что<br>
	<?php echo Html::a('принимаете Пользовательское соглашение и', [$agreeurl], ['target' => '_blank', 'data' => ['pjax' => 0]]); ?><br>
	<?php echo Html::a('Политику конфиденциальости', [$conurl], ['target' => '_blank', 'data' => ['pjax' => 0]]); ?>
</div>
<script>
	if(!document.getElementById('body'))
		location.href = '/';
</script>