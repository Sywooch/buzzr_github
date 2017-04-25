<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\ListView;

?>
<div class="section-stores">
	<? if(!$data->totalCount) : ?>
		<div class="iconized-text">
			<div class="bg-icon">
				<i class="fa fa-check-square-o"></i>
			</div>
			<div class="text">
			Для добавления магазинов в избранное <a href="<?=Url::toRoute(['stores/index', 'service'=>'stores']);?>">выберите понравившийся вам магазин,</a> и нажмите подписаться.
			</div>
		</div>
	<? else : ?>
		<? echo ListView::widget([
			'dataProvider'=>$data,
			'itemView'=>'/stores/_list_item',
			'viewParams' => ['display_subscribe'=>true],
			'layout' => "{items}\n{pager}",
			'itemOptions' => ['class'=>'stores-item'],
			]);
		?>
	<? endif ?>
</div>