<?

use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<? $form = ActiveForm::begin(['options'=>['data-pjax'=>1]]); ?>
	<div class="row">
		<div class="col-xs-8">
			<?=$form->field($comment, 'answer')->label(false); ?>
			<?=$form->field($comment, 'id')->hiddenInput()->label(false); ?>
		</div>
		<div class="col-xs-4">
			<button class="orange-grad">Отправить</button>
		</div>
	</div>
<? ActiveForm::end(); ?>