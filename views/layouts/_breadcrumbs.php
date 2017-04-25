<?
use yii\helpers\Url;
use yii\helpers\Html;

?>
		<ul class="breadcrumbs">
			<? $i = 0; foreach($cats as $item) :
				$last = (++$i == count($cats));
			?>
			<li>
			<? if($last && isset($notlinklast)) : ?>
				<span class="last"><?=Html::encode($item['title'])?></span>
			<? else : ?>
				<a href="<?=Url::toRoute(['catalog/product', 'path'=>$item['path']]);?>"><?=Html::encode($item['title'])?></a>
			<? endif ?>
			</li>
			<? if(!$last) : ?>
			<li><span class="separator">/</span></li>
			<? endif ?>
			<? endforeach ?>
		</ul>
