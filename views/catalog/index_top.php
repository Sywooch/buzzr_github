<?
use yii\helpers\Url;
use yii\helpers\Html;

?>
<section class="catalog">
	<div class="container narrow container_nopadding">
	<table class="categories-top">
	<? $cell_index = 0; foreach(array_merge($model, ['companies']) as $category): ?>
	<? if($category != 'companies') $childs = $category->getChildren(true, 5);?>
		<?if(count($childs) > 0) : ?>
			<? if($cell_index % 3 == 0) : ?>
				<tr>
			<? endif ?>
				<td>
				<? if($category != 'companies') : ?>
					<?	$slugList = $category->slugPath; ?>
					<div class="category-wrap">
						<div class="image-wrap text-center">
							<a href="<?=Url::toRoute(['catalog/index', 'path'=>join($slugList, '/')])?>">
								<img src="/uploads/catalog/<?=$category->slug?>.png" alt="">
							</a>
						</div>
						<a class="category-large" href="<?=Url::toRoute(['catalog/index', 'path'=>join($slugList, '/')])?>">
							<?=Html::encode($category->title)?>
						</a>
						<ul>
							<? foreach($childs as $child) :	?>
							<li>
								<a href="<?=Url::toRoute(['catalog/index',
									'path'=>join(array_merge($slugList, [$child->slug]), '/')])?>">
									<?=Html::encode($child->title)?>
								</a>
								<? if($child->count) : ?>
								<span class="count">(<?=$child->count?>)</span>
								<? endif ?>
							</li>
							<? endforeach ?>
						</ul>
						<div class="showall">
							<a href="<?=Url::toRoute(['catalog/index', 'path'=>join($slugList, '/')])?>">
								Показать весь каталог
							</a>
						</div>
					</div>
				<? else : ?>
					<div class="image-wrap text-center">
						<img src="/uploads/catalog/building.png" alt="">
					</div>
					<a class="category-large">
						Компании
					</a>
					<ul>
						<li>
							<a href="<?=Url::toRoute(['stores/index', 'service'=>'stores'])?>">Магазины</a>
						</li>
						<li>
							<a href="<?=Url::toRoute(['stores/index', 'service'=>'organizations'])?>">Организации</a>
						</li>
					</ul>
				<? endif ?>
				</td>
			<? if($cell_index++ % 3 == 2) : ?>
				</tr>
			<? endif ?>
		<? endif ?>
	<? endforeach ?>
	<? if($cell_index % 3 != 2)echo '</tr>'; ?>
	</table>
	</div>
</section>