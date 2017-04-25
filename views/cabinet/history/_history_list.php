<?
use yii\helpers\Url;
use app\models\Product;
use app\models\Store;
use app\models\Order;
use yii\helpers\Html;
?>
<div class="cart-for-store" id="exclusive">
	<? $store = Store::findOne(['id'=>$model->store_id]); ?>
	<? $status = Order::getStatusList(); ?>
	<? $order = Order::One($model->id); ?>
	<? $products = $order->getProducts(); ?>
	<div class="cart-item panel">
		<? foreach($products as $product) : $product_info = Product::findById($product['product_id']); ?>
		<? $url = Url::toRoute(['catalog/product', 'path'=>$product_info->categoryPath, 'product_code'=>$product_info->slug]); ?>
		<div class="row">
			<div class="col-sm-2">
				<div class="image-wrap">
					<a href="<?=$url; ?>" target="_blank"><img src="<?=$product_info->getPhotoUrl('-resize 170x150')?>" alt="" class="image"></a>
				</div>
			</div>
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-8">
						<div class="upper">
							<div class="title">
								<a href="<?=$url; ?>" class="ib" target="_blank"><?=Html::encode($product_info->title)?></a>
							</div>
							<? $attributes = $product_info->getAllProductAttributes(); ?>
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
					<div class="col-sm-4">
						<div class="upper upper-history">
							<table>
								<tr>
									<td>Дата:</td>
									<td><?=$order->created;?></td>
								</tr>
								<tr>
									<td>Статус:</td>
									<td><?=$status[$order->status];?></td>
								</tr>
								<tr>
									<td>Заказ №:</td>
									<td><?=$order->id;?></td>
								</tr>
								<tr>
									<td>Количество:</td>
									<td><?=$product['count'];?></td>
								</tr>
								<tr>
									<td>Сумма:</td>
									<td><?=$order->total;?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-7">
						Магазин: <a href="<?=$store->url;?>"><?= Html::encode($store->title) ?></a>
					</div>
					<div class="col-sm-5">
						
					</div>
				</div>
			</div>
		</div>
		<? endforeach ?>
	</div>
</div>