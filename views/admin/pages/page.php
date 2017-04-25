<?
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<? Pjax::begin(); ?>
		<? $form = ActiveForm::begin(); ?>
			<?=$form->field($page, 'page'); ?>
			<?=$form->field($page, 'title'); ?>
			<?=$form->field($page, 'content')->textArea(['style'=>'height:200px']); ?>
			<button class="blue-grad">Сохранить</button>
		<? ActiveForm::end(); ?>
<? Pjax::end(); ?>

