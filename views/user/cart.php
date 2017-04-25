<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Product;
use yii\helpers\Html;
?>

<section class="cabinet">
	<div class="container narrow">
		<? echo $this->render('_cabinet_header', ['user'=>Yii::$app->user->identity]); ?>
		<? $form = ActiveForm::begin(); ?>
		<? foreach($cart as $cart_item) : $product = Product::findById($cart_item->product_id); ?>
			<div class="cart well">
				<div class="row">
					<div class="col-sm-3">
						<img src="<?=$product->photoUrl?>" alt="" class="img-responsive">
					</div>
					<div class="col-sm-4">
						<div class="title">
							<?=Html::encode($product->title); ?>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="amount">
							<?=Html::encode($product->price);?>
						</div>
					</div>
					<div class="col-sm-1">
						<div class="count">
							<?=$cart_item->count; ?>
						</div>
					</div>
					<div class="col-sm-1">
						<a href="<?=Url::toRoute(['user/cart', 'remove'=>$cart_item->product_id]);?>" class="orange-grad">Удалить</a>
					</div>
				</div>
			</div>
		<? endforeach ?>
		<button class="blue-grad">Оформить заказ</button>
		<? ActiveForm::end(); ?>
		</div>
	</div>
</section>
