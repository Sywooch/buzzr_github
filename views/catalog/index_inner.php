<?
use yii\helpers\Url;
use yii\helpers\Html;

?>
<section class="catalog">
	<div class="container narrow">
		<? echo $this->render('/layouts/_breadcrumbs', ['cats'=>$parent_cat->breadcrumbs, 'notlinklast'=>true]); ?>
	<table class="categories-inner">
	<? $cell_index = 0; foreach($model as $category):
		$slugList = $category->slugPath;
	?>
		<? if($cell_index % 3 == 0) : ?>
			<tr>
		<? endif ?>
			<td>
				<div class="item-wrap">
					<div class="title-wrap">
						<img src="/uploads/catalog/<?=$category->slug?>.png" alt="">
						<div class="title">
							<?=Html::encode($category->title)?>
						</div>
						<a href="<?=Url::toRoute(['catalog/index', 'path'=>join($slugList, '/')])?>" class="hoverage"></a>
					</div>
					<ul>
						<? foreach($category->getChildren(true) as $child) :?>
						<li>
							<a href="<?=Url::toRoute(['catalog/index',
								'path'=>join(array_merge($slugList, [$child->slug]), '/')])?>">
									<?=$child->title?></a>&nbsp;<? if($child->count) :?><span class="count">(<?=$child->count?>)</span><? endif ?>
						</li>
						<? endforeach ?>
					</ul>
				</div>
			</td>
		<? if($cell_index++ % 3 == 2) : ?>
			</tr>
		<? endif ?>
	<? endforeach ?>
	<? if($cell_index % 3 != 2)echo '</tr>'; ?>
	</table>
	</div>
</section>