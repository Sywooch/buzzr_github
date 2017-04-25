<?
use yii\widgets\ActiveForm;
use rmrevin\yii\ulogin\ULogin;
use yii\widgets\Pjax;
use yii\helpers\Url;
$input_template = '<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}{error}{hint}</div></div>';

?>
<section class="lostpass">
	<div class="container narrow">
		<div class="container-modal panel panel-info">
			<div class="panel-heading">Забыли пароль?</div>
			<div class="panel-body">
				<p>Введите логин или электронную почту, и получите инструкции по восстановлению пароля</p>
				<? $form = ActiveForm::begin(['id'=>'login_form','options'=>['data-pjax'=>1]]); ?>
				<div class="login-box">
					<? echo $form->field($model, 'username', ['template'=>$input_template]); ?>
					<?= $form->field($model, 'reCaptcha', ['template'=>$input_template])->widget(
					    \himiklab\yii2\recaptcha\ReCaptcha::className()
					) ?>	
					<button class="blue-grad">Отправить</button>

				</div>
				<? ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</section>