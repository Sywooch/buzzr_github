<?
use yii\helpers\Url;
use yii\widgets\ListView;
use app\components\MediaLibrary;
use yii\helpers\Html;

function banner($banner, $class='inner'){ ?>
	<div class="<?=$class; ?>" style="background-image: url('<?=$banner->img; ?>')">
		<a href="<?=$banner->url; ?>" class="hoverage"></a>
		<div class="caption" style="color: <?=$banner->color;?>"><?=$banner->title; ?></div>
	</div>
<? }

?>
<section class="home-categories">
	<div class="container">
		<div class="home-categories_row">
			<div class="thecol-2">
				<? banner($banners[0]); ?>
			</div>
			<div class="thecol">
				<? banner($banners[1]); ?>
			</div>
			<div class="thecol">
				<? banner($banners[2]); ?>
			</div>
			<div class="thecol">
				<? banner($banners[3]); ?>
			</div>
			<div class="thecol">
				<? banner($banners[4]); ?>
			</div>
			<div class="thecol">
				<? banner($banners[5]); ?>
			</div>
			<div class="thecol">
				<? banner($banners[6]); ?>
			</div>
			<div class="thecol">
				<div class="inner have-half">
					<? banner($banners[7], 'inner-top'); ?>
					<? banner($banners[8], 'inner-bottom'); ?>
				</div>
			</div>
			<div class="thecol">
				<? banner($banners[9]); ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</section>
<section class="catalog-products">
	<div class="container">
		<? echo $this->render('/site/_home_products.php', [
			'blocks'=>$blocks,
			'popularProducts'=>$popularProducts,
			'itemClass'=>'col-lg-2 col-md-3 col-sm-4',
		]); ?>
	</div>
</section>
<section class="stores">
	<div class="container">
		<? echo $this->render('/site/_home_stores.php', ['popularStores'=>$popularStores, 'itemClass'=>'col-lg-2 col-md-3 col-sm-4']); ?>
		</div>
	</div>
</section>
