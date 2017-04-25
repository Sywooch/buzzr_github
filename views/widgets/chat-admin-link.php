<?
use yii\helpers\Url;
?>
<ul style="margin-bottom: 0;">
	<li>
		<a href="<?=Url::toRoute(['cabinet/chat', 'receiver_id'=>$receiver, 'ajax'=>1])?>" data-target="#chat_modal">Связатся с администрацией</a>
	</li>
</ul>
