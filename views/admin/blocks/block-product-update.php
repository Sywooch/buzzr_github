<?
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<? Pjax::begin(); ?>
	<? $form = ActiveForm::begin(); ?>
		<div class="panel-body">
			
			<div class="form-group">
				<?=$form->field($model, 'sort'); ?>
			</div>
			
			<div class="form-group">
				<?=$form->field($model, 'active')->checkbox() ?>
			</div>

		</div>
		<button class="blue-grad">Сохранить</button>
	<? ActiveForm::end(); ?>
<? Pjax::end(); ?>