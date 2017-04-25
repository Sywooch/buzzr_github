<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\Menu;
use yii\helpers\Html;

?>
<div class="cabinet-sidebar">
	<? if(!empty($contragents)) : ?>
	Контакты:
		<? $items = []; foreach($contragents as $contragent) {
			$items[] = [
				'label' =>Html::encode($contragent->contragent->name) . (($contragent->unread) ? ' <span class="unread"><i class="fa fa-envelope"></i> ' . ($contragent->unread) . '</span>' : '') ,
				'url' => ['cabinet/chat/index', 'receiver_id'=>$contragent->contragent->id]
			];
		} ?>
		<? echo Menu::widget([
			'activateItems' => true,
			'encodeLabels' => false,
		    'items' => $items
		  ]); ?>
	<? else : ?>
	Пока нет переписок
	<? endif ?>
</div>
