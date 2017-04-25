<?
use yii\helpers\Html;
?>


<? foreach($actions as $action) : ?>
	<div class="action-item">
		<i class="fa fa-gift"></i>
		<?=Html::encode($action['description']); ?>
	</div>
<? endforeach ?>