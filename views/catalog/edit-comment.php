<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<section class="product">
	<div class="container narrow">
		Редактирование комментария к товару\услуге <b><?=Html::encode($comment->product->title); ?></b>
		<? $form = ActiveForm::begin(); ?>
		<? echo $form->field($comment, 'text')->textArea(); ?>
		<input type="submit" class="blue-grad" name="save" value="Сохранить">
		<input type="submit" class="default-grad" name="cancel" value="Отменить">
		<? ActiveForm::end(); ?>
	</div>
</section>