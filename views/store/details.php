<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Product;
use app\models\Order;
use yii\helpers\Html;

?>

<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="order-details">
				<? $form = ActiveForm::begin(); ?>
				<ul>
					<li>Имя: <?=Html::encode($order->name)?></li>
					<li>Почта: <?=Html::encode($order->email)?></li>
					<li>Тел: <?=Html::encode($order->phone)?></li>
					<li>Адрес: <?=Html::encode($order->address)?></li>
					<li>Создан: <?=Html::encode($order->created)?></li>
					<li>Изменен: <?=Html::encode($order->created)?></li>
				</ul>
				<table class="table table-hover table-bordered">
					<tr>
						<th>Наименование</th>
						<th>Цена</th>
						<th>Количество</th>
					</tr>
				<? foreach($products as $product) : $product_model = Product::findById($product['product_id'])?>
				<tr class="order-details-item">
					<td><?=Html::encode($product_model->title)?></td>
					<td><?=Html::encode($product['price'])?></td>
					<td><?=Html::encode($product['count'])?></td>
				</tr>
				<? endforeach ?>
				</table>
				<div class="buttons">
					<? echo $form->field($order, 'comment')->textArea(); ?>
					<div class="row">
						<div class="col-sm-4">
							<? echo $form->field($order, 'status')->dropDownList(Order::getStatusList()); ?>
						</div>
						<div class="col-sm-4">
							<label>&nbsp;</label>
							<div class="form-group">
								<button class="btn btn-primary">Сохранить</button>
							</div>
						</div>
					</div>
				</div>
				<? ActiveForm::end(); ?>
			</div>

		</div>
	</div>
</section>
