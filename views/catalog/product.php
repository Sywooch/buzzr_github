<?
use yii\helpers\Url;
use app\widgets\ShareWidget;
use app\widgets\RateWidget;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\components\MediaLibrary;
use app\models\Product;
use yii\helpers\Html;

?>

<section class="product">
	<div class="container narrow">
		<div class="bordered">
		<? if($product->store->can_edit()) : ?>
			<div class="pull-right">
				Просмотров: <?=$product->_visit->total; ?>
			</div>
		<? endif ?>
		<? echo $this->render('/layouts/_breadcrumbs', ['cats'=>$product->category->breadcrumbs]); ?>
		<div class="row">
			<div class="col-sm-6 ongallery-enlarge">
				<? if($product->photos) : ?>
				<div class="gallery">
					<div class="photo-large">
						<span class="enlarge-wrap">
							<img src="<?=$product->getPhotoUrl('-resize "890x890>"'); ?>" alt="" data-full="<?=$product->getPhotoUrl()?>">
							<span class="enlarge-clicker">
								<i class="fa fa-search-plus"></i>
								<i class="fa fa-search-minus"></i>
							</span>
						</span>
					</div>
    				<? $photos = $product->getPhotos(); $p = 0; ?>
					<div class="photo-thumbs <? if(count($photos) < 2)echo 'hidden';?>">
    				<? foreach($photos as $photo): ?>
    					<a class="photo-thumb <? if($p++ == 0)echo ' active '; ?>"
    						href="<?=MediaLibrary::getByFilename($photo)->getUrl(); ?>"
    						data-large="<?=MediaLibrary::getByFilename($photo)->getResizedUrl('-resize "890x890>"');?>">
							<img src="<?=MediaLibrary::getByFilename($photo)->getResizedUrl('-resize 80x80'); ?>" alt="">
						</a>
					<? endforeach ?>
					</div>
				</div>
				<? endif ?>
			</div>
			<div class="col-sm-6 ongallery-hide">

				<div class="product-nav-line text-right">
					<? if(Url::previous()) : ?>
					<a class="back btn btn-default" href="<?=Url::previous();?>"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Назад</a>
					<? endif ?>
					<a class="btn btn-default" href="<?=Url::toRoute(['store/main', 'code'=>$product->store->slug])?>">В магазин: <span class="limit-title-width"><?=$product->store->title?></span></a>
				</div>
				<div class="title">
					<?=Html::encode($product->title)?>
				</div>
				<? if(!$product->available) : ?>
				<div class="notavailable">
					Нет в наличии
				</div>
				<div class="notavailable-text">
					<?=Html::encode($product->unavailable_comment); ?>
				</div>
				<? endif ?>
				<div class="row">
					<div class="col-sm-7">
						<? if($product->discount_amount && $product->amount) :?>
							<div class="price">
								цена:
								<span class="price-old"><?=$product->amount?></span>
								<span class="nowrap"><?=$product->discount_amount?>&nbsp;<i class="fa fa-rub"></i></span>&nbsp;/&nbsp;<?=$product->priceMeasure; ?>
							</div>
						<? elseif($product->discount_amount || $product->amount) :?>
							<div class="price">
								цена: <span class="nowrap"><?=$product->discount_amount ? $product->discount_amount : $product->amount ?>&nbsp;<i class="fa fa-rub"></i></span>&nbsp;/&nbsp;<?=$product->priceMeasure; ?>
							</div>
						<? endif ?>
					</div>
					<div class="col-sm-5">
						<? if($product->store->is_supplier) : ?>
							<div class="supplier-label">Оптом</div>
						<? else : ?>
						<div class="rate">
							<? echo RateWidget::widget([
									'value' => $product->rating
								]);
							?>
						</div>
						<? endif ?>
						<div class="share">
							<? echo ShareWidget::widget([
									'url' => $_SERVER['REQUEST_URI'],
									'title' => $product->title
								]);
							?>
						</div>
					</div>
				</div>
				<hr>
				<? if($product->store->is_supplier && $product->supplier_prices) : ?>
					<div class="supplier-prices-data"><?=Html::encode($product->supplier_prices); ?></div>
				<? endif ?>
				
				<? $attributes = $product->getAllProductAttributes(); ?>
				<? if(!empty($attributes) || !empty($product->manufactures) || !empty($product->manufacturer_country)) : ?>
				<div class="chars">
					Характеристики
					<ul>
						<? if(!empty($product->manufactures)) : ?>
						<li><span class="key">Производитель:</span> <span class="value"><?=Html::encode($product->manufactures)?></span></li>
						<? endif ?>
						<? if(!empty($product->manufacturer_country)) : ?>
							<li><span class="key">Страна производитель:</span> <span class="value"><?=Html::encode($product->manufacturer_country)?></span></li>
						<? endif ?>
						<? foreach($attributes as $char) : ?>
						<li><span class="key"><?=Html::encode($char['key'])?>:</span> <span class="value"><?=Html::encode($char['val'])?></span></li>
						<? endforeach ?>
					</ul>
				</div>
				<? endif ?>
				<? if($product->photo_link) : ?>
				<div class="link">
					<hr>
					<p>
						<a target="_blank" class="btn btn-default" href="<?=$product->photo_link; ?>">Ссылка на фотогалерею</a>
					</p>
				</div>
				<? endif ?>
				<? if($product->store->sell_deliver) : ?>
					<? if($product->store->delivery_text) : ?>
					<div class="bordered smallpad">
						<div class="delivery-text-title">Условия доставки:</div>
						<div class="delivery-text">
							<?php echo(nl2br($product->store->delivery_text)); ?>
						</div>
					</div>
					<br>
					<? endif ?>
					<? if($cart)echo $this->render('_cart', ['product'=>$product, 'cart'=>$cart]); ?>
				<? else : ?>
					<div class="address-well">
						<div class="icon"><i class="fa fa-map-marker"></i></div>
						<?=Html::encode($product->store->address); ?>
						<div class="clearfix"></div>
					</div>
					<div class="address-well">
						<div class="icon roundit"><i class="fa fa-phone"></i></div>
						<?=Html::encode($product->store->phone); ?>
						<div class="clearfix"></div>
					</div>
					<div id="main_map" data-lat="<?=Html::encode($product->store->lat)?>" data-lng="<?=Html::encode($product->store->lng)?>"></div>
				<? endif ?>
			</div>
		</div>

		<? $actions = $product->_actions; ?>
		<? if(!empty($actions)) : ?>
			<div class="sales">
				<table>
				<? foreach($actions as $action) : $action_product = Product::findById($action['stock_product_id']); ?>
					<tr>
					<? $action_url = Url::toRoute(['catalog/product', 'path'=>$action_product->categoryPath, 'product_code'=>$action_product->slug]); ?>
					<td>
						<? if($action_product->photos) : ?>
						<div class="img-wrap">
							<a href="<?=$action_url?>">
								<img src="<?=MediaLibrary::getByFilename($action_product->getPhotos()[0])->getResizedUrl('-resize 80x80'); ?>" alt="" class="sale-image">
							</a>
						</div>
						<? endif ?>
					</td>
					<td>
						<div class="sales-title">Акция:</div>
						<a href="<?=$action_url?>">
						as<?php echo(nl2br(Html::encode($action['description']))); ?>
						</a>
					</td>
				</tr>
				<? endforeach ?>
				</table>
			</div>
		<? endif ?>

		<div class="tabs">
			<a class="tab active" href="#description">
				Описание
			</a>
			<a class="tab" href="#comments">
				Отзывы
			</a>
		</div>
		<div class="tab-content">
			<div class="description tab active" id="description">
				<?php echo(nl2br($product->description)); ?>
			</div>
			<div class="product-comments tab" id="comments">
				<? Pjax::begin(); ?>
				<? if(empty($product->productComments)) : ?>
				Нет отзывов
				<? else : ?>
				<? foreach($product->productComments as $comment) : ?>
					<? echo $this->render('_comment', ['comment'=>$comment]); ?>
				<? endforeach ?>
				<? endif ?>
				<? if(!Yii::$app->user->getIsGuest()) : ?>
				<div class="comment-form well">
					Оставить отзыв:
					<? $form = ActiveForm::begin(['options'=>['data-pjax'=>1]]); ?>
					<? echo $form->field($commentModel, 'text')->textArea(); ?>
					<button class="blue-grad">Оставить комментарий</button>
					<? ActiveForm::end(); ?>
				</div>
				<? endif ?>
				<? Pjax::end(); ?>
			</div>
		</div>
	</div>
	</div>

</section>