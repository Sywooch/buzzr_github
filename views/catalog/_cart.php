<?
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\touchspin\TouchSpin;

?>
	<div class="cart-widget">
	<? $form = ActiveForm::begin(['options'=>['data-pjax'=>1]]); ?>
		<div class="cart-row">
			<div class="input-col">
				<? echo $form->field($cart, 'count')->widget(TouchSpin::className(),
					['pluginOptions' => ['verticalbuttons' => true]])->label(false); ?>
			</div>
			<div class="button-col">
				<button class="blue-grad">Добавить в корзину</button>
			</div>
		</div>
	<? ActiveForm::end(); ?>
</div>