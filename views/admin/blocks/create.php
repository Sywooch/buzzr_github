<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<? $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<?=$form->field($model, 'title'); ?>
			</div>
			
			<div class="form-group">
				<?=$form->field($model, 'slug'); ?>
			</div>

			<div class="form-group">
				<?=$form->field($model, 'sort'); ?>
			</div>
			
			<div class="form-group">
				<?=$form->field($model, 'active')->checkbox() ?>
			</div>

		</div>
		<div class="panel-footer">
			<input type="submit" name="save" class="blue-grad" value="Сохранить">
		</div>
	</div>
<? ActiveForm::end(); ?>
