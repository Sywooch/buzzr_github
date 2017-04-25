<?
use yii\helpers\Url;
?>
<? if(!empty($subcats)) : ?>
<div class="subcats">
	<ul>
	<? foreach($subcats as $subcat) : ?>
	<? $path = join('/', array_merge($parent_path, [$subcat->slug])); ?>
		<li><a href="<?=Url::toRoute(['catalog/list', 'path'=>$path]);?>"><?=$subcat->title?></a> (<?=$subcat->count; ?>)</li>
	<? endforeach ?>
	</ul>
</div>
<? endif ?>