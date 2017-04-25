<?
use kartik\touchspin\TouchSpin;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Product;
use app\models\Store;
use app\components\MediaLibrary;
use yii\helpers\Html;

?>
<div class="cart-for-store" id="exclusive">
		<? if(empty($cart)) : ?>
		<div class="iconized-text">
			<div class="bg-icon">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<div class="text">
			Корзина пуста. Начните с <a href="<?=Url::toRoute(['catalog/index']);?>">каталога</a>
			</div>
		</div>
		<? else : ?>
		<? foreach($cart as $store_id=>$cart_store) : $store = Store::findOne(['id'=>$store_id]); ?>
			<? foreach($cart_store as $cart_item) : $product = Product::findById($cart_item->product_id); ?>
			<? $url = Url::toRoute(['catalog/product', 'path'=>$product->categoryPath, 'product_code'=>$product->slug]); ?>
			<? $form = ActiveForm::begin(['options'=>['class'=>'cart-item panel']]); ?>
				<div class="row">
					<div class="col-sm-2">
						<div class="image-wrap">
							<a href="<?=$url; ?>" target="_blank"><img src="<?=$product->getPhotoUrl('-resize 170x150')?>" alt="" class="image"></a>
						</div>
					</div>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-9">
								<div class="upper">
									<div class="title">
										<a href="<?=$url; ?>" class="ib" target="_blank"><?=Html::encode($product->title)?></a>
									</div>
									<? $attributes = $product->getAllProductAttributes(); ?>
									<? if(!empty($attributes)) : ?>
									<div class="chars">
										<ul>
										<? $cc = 0; ?>
										<? foreach($attributes as $char) : if(++$cc > 4)break; ?>
											<li><span class="key"><?=Html::encode($char['key'])?>:</span> <span class="value"><?=Html::encode($char['val'])?></span></li>
										<? endforeach ?>
										</ul>
									</div>
									<? endif ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="upper">
									<a href="<?=Url::toRoute(['cabinet/cart/remove', 'remove'=>$cart_item->product_id]);?>" class="remove pull-right">Удалить</a>
									<div class="prices">
										<? if($product->amount && $product->discount_amount): ?>
											<div class="old-price"><span class="old-value" data-multiple="<?=$product->price?>"><?=$product->price?></span>&nbsp;<i class="fa fa-rouble"></i></div>
											<div class="price"><span class="value" data-multiple="<?=$product->discount_amount?>"><?=$product->discount_amount  * $cart_item->count?></span>&nbsp;<i class="fa fa-rouble"></i><span class="measure">&nbsp;/&nbsp;<?=$product->priceMeasure; ?></span></div>
										<? elseif($product->price) :?>
											<div class="price">
											<span class="value" data-multiple="<?=$product->price?>"><?=$product->price * $cart_item->count?></span>&nbsp;<i class="fa fa-rouble"></i><span class="measure">&nbsp;/&nbsp;<?=$product->priceMeasure; ?></span>
											</div>
										<? endif ?>

									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-7">
								Магазин: <a href="<?=$store->url;?>"><?= Html::encode($store->title) ?></a>
							</div>
							<div class="col-sm-5">
								<div class="spin-wrap">
									<? echo TouchSpin::widget([
										'value' => $cart_item->count,
										'name' => 'Order[single_count]',
										'options' => [
											'id'=>'spinner_for_' . $product->id,
										],
										'pluginOptions' =>
											[
												'verticalbuttons' => true,
												'min' => 1,
											]
										]); ?>
								</div>
								<div class="button-wrap">
									<button type="button" class="default-grad" data-parent="#exclusive" data-toggle="collapse" data-target="#order_popup_<?=$product->id;?>">Оформить заказ</button>
									<input type="hidden" name="Order[store_id]" value="<?=$store_id?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="order-popup collapse" id="order_popup_<?=$product->id;?>">
					<div class="close" data-toggle="collapse" data-parent="body" data-target="#order_popup_<?=$product->id;?>">&times;</div>
					<div class="h4">Заказ <a target="_blank" href="<?=$url;?>"><?=Html::encode($product->title);?></a>
						в магазине
						<a target="_blank" href="<?=$store->url;?>"><?=Html::encode($store->title);?></a></div>
						<br>
					<div class="row">
						<div class="col-sm-3">
							<?=$form->field($order, 'phone')->textInput(['placeholder'=>'+7 903 000 00 00']); ?>
						</div>
						<div class="col-sm-3">
							<?=$form->field($order, 'email')->textInput(['placeholder'=>'Электронная почта']); ?>
						</div>
						<div class="col-sm-6">
							<?=$form->field($order, 'name')->textInput(['placeholder'=>'Имя и фамилия']); ?>
						</div>
						<div class="col-xs-12">
							<?=$form->field($order, 'address')->textInput(['placeholder'=>'Город, улица, дом, квартира. Почтовый адрес']); ?>
						</div>
						<div class="col-xs-12">
							<?=$form->field($order, 'comment')->textArea(['placeholder'=>'Уточните детали покупки']); ?>
						</div>
						<div class="col-xs-12">
							<div class="pull-right">
								<button class="blue-grad">Отправить</button>
							</div>
						</div>
					</div>
				</div>
			<? ActiveForm::end(); ?>
			<? endforeach ?>
		<? endforeach ?>
		<? endif ?>
		</div>
</div>
