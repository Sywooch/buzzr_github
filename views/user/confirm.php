<?
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
$input_template = '<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}{error}{hint}</div></div>';

?>
<div class="modal-header nomodal-hide">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Активация пользователя
</div>
<div class="modal-body">
<? Pjax::begin(['id'=>'login_ajax']); ?>
<section class="login">
	<div class="container narrow">
		<div class="container-modal">
			<? $form = ActiveForm::begin(['id'=>'virefy_form','options'=>['data-pjax'=>1]]); ?>
			<? if($error) : ?>
		    <div class="alert alert-danger">
				<?=$error?>
			</div>
			<? elseif($info) : ?>
			 <div class="alert alert-info">
				<? if($info == 'registered') : ?>
					Вы успешно зарегистрированы!<br>
					Что-бы начать пользоваться нашим сайтом - проверьте вашу почту.<br> 
					На неё должно прийти письмо с ключом активации.<br>
					<a href="<?=$retry_link;?>">Отправить письмо повторно.</a><br>
				<? elseif($info == 'retry') : ?>
					Письмо повторно отправлено!<br>
					Проверьте вашу почту.<br>
				<? endif ?>
			</div>
			<? endif ?>
			<div class="login-box">
				<? if($username) : echo $form->field($model, 'username', ['template'=>$input_template])->textInput(['value'=>$username]); ?>
				<? else : echo $form->field($model, 'username', ['template'=>$input_template]); ?>
				<? endif ?>
				<? echo $form->field($model, 'verify_key', ['template'=>$input_template]); ?>
				<button class="btn btn-primary">Активировать</button>
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