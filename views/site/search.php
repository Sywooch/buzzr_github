<?
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<section class="search-result">
	<div class="container narrow">
		<? $displayed = 0; ?>
		<? if($category->totalCount) : ?>
		<? $displayed++; ?>
		<? Pjax::begin(['id'=>'search_result_cat']); ?>
		<div class="search-result-category">
			<div class="search-result-title">Категории товаров и услуг</div>
			<div class="catalog-categories">
				<ul>
				<? echo ListView::widget([
					'dataProvider'=>$category,
					'itemView'=> function($model){
						return '<li><a class="orangehover" href="'.Url::toRoute(['catalog/index', 'path'=>join('/', $model->slugPath)]).'">'.$model->title.'</a></li> ';
					},
					'options' => ['class' => 'row'],
					'layout' => '{items}<div class="col-xs-12">{pager}</div>',
					'itemOptions' => ['class'=>'col-sm-3'],
					]);
				?>
				</ul>
			</div>
		</div>
		<? Pjax::end(); ?>
		<? endif ?>
		<? if($products->totalCount) : ?>
		<? $displayed++; ?>
		<? Pjax::begin(['id'=>'search_result_prod']); ?>
		<div class="search-result-category">
			<? if( ($products->totalCount > 4) && ($products->pagination->pageSize < 40)) : ?>
			<? $params = [Yii::$app->controller->id.'/'.Yii::$app->controller->action->id] +
				Yii::$app->controller->actionParams; $params['limit_products'] = 40; ?>
			<a href="<?=Url::toRoute($params); ?>" class="pull-right">Показать всё</a>
			<? endif ?>
			<div class="search-result-title">Товары и услуги</div>
			<div class="catalog-products">
			<? echo ListView::widget([
				'dataProvider'=>$products,
				'itemView'=>'/catalog/_product_list_item',
				'options' => ['class' => 'products-list row'],
				'layout' => '{items}<div class="col-xs-12">{pager}</div>',
				'itemOptions' => ['class'=>'col-sm-3'],
				]);
			?>
			</div>
		</div>
		<? Pjax::end(); ?>
		<? endif ?>
		<? if($stores->totalCount) : ?>
		<? $displayed++; ?>
		<? Pjax::begin(['id'=>'search_result_stores']); ?>
		<div class="search-result-category">
			<div class="search-result-title">Магазины и организации</div>
			<div class="section-stores">
				<? echo ListView::widget([
					'dataProvider'=>$stores,
					'itemView'=>'/stores/_list_item',
					'layout' => "{items}\n{pager}",
					'itemOptions' => ['class'=>'stores-item'],
					]);
				?>
			</div>
		</div>
		<? Pjax::end(); ?>
		<? endif ?>
		<? if($articles->totalCount) : ?>
		<? $displayed++; ?>
		<? Pjax::begin(['id'=>'search_result_news']); ?>
		<div class="search-result-category">
			<div class="search-result-title">Новости</div>
			<div class="section-news">
				<? echo ListView::widget([
					'dataProvider'=>$articles,
					'itemView'=>'/news/_list_item',
					'layout' => "{items}\n{pager}",
					'itemOptions' => ['class'=>'news-item'],
					]);
				?>
			</div>
		</div>
		<? Pjax::end(); ?>
		<? endif ?>
		<? if(!$displayed) : ?>
		<p>Ничего не найдено</p>
		<? endif ?>
	</div>
</section>
