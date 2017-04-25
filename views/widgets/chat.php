<?
use yii\helpers\Url;
?>
<a href="<?=Url::toRoute(['cabinet/chat', 'receiver_id'=>$receiver, 'ajax'=>1])?>" data-target="#chat_modal">
	<button type="button" class="btn btn-xs chat pull-left">
		<span class="fa fa-envelope"></span>
	</button>
</a>
