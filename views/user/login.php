<?
use yii\widgets\ActiveForm;
use rmrevin\yii\ulogin\ULogin;
use yii\widgets\Pjax;
use yii\helpers\Url;
$input_template = '<div class="row"><div class="col-sm-5">{label}</div><div class="col-sm-7">{input}{error}{hint}</div></div>';

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Вход в личный кабинет
</div>
<div class="modal-body">
<? Pjax::begin(['enablePushState'=>false, 'enableReplaceState'=>false, 'id'=>'login_ajax']); ?>
<section class="login">
	<div class="container narrow">
		<div class="container-modal">
			<? if($error) : ?>
		    <div class="alert alert-danger">
				<?=$error?>
			</div>
			<? endif ?>
			<? $form = ActiveForm::begin(['id'=>'login_form','options'=>['data-pjax'=>1]]); ?>
			<div class="login-box">
				<? echo $form->field($model, 'username', ['template'=>$input_template]); ?>
				<? echo $form->field($model, 'password', ['template'=>$input_template])->passwordInput(); ?>
				<div class="row">
					<div class="col-sm-5">
						<a class="default-grad" data-pjax="0" href="<?=Url::toRoute(['user/lost']);?>">Забыли пароль?</a>
					</div>
					<div class="col-sm-7">
						<button class="blue-grad">Войти</button>
					</div>
				</div>
			</div>
			<div class="social text-center">
				Вход через социальные сети
				<script src="//ulogin.ru/js/ulogin.js"></script>
				<? $loginurl = Yii::$app->request->hostInfo . Url::toRoute(['user/ulogin']); ?>
				<div id="uLogin-l" class="ulogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name;providers=vkontakte,facebook;redirect_uri=<?=$loginurl?>;mobilebuttons=0;"></div>
			</div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</section>
<? Pjax::end(); ?>
</div>
<script>
	if(!document.getElementById('body'))
		location.href = '/';
</script>