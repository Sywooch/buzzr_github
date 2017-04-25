<?
use yii\widgets\ListView;
use yii\helpers\Url;
?>

<? if(!$listDataProvider->totalCount) : ?>
		<div class="iconized-text">
			<div class="bg-icon">
				<i class="fa fa-folder-open-o"></i>
			</div>
			<div class="text">
			В истории заказов нет ни одного товара. Для оформления товара перейдите во вкладку <a href="<?=Url::toRoute(['cabinet/cart/index']);?>">Корзина</a>
			</div>
		</div>

<? else : ?>
<? echo ListView::widget([
    'dataProvider' => $listDataProvider,
    'itemView' => '_history_list',
]);
?>
<? endif ?>