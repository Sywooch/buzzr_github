<?
use yii\helpers\Url;
?>
<a href="<?=Url::toRoute(['cabinet/chat', 'receiver_id'=>$receiver, 'ajax'=>1])?>" class="btn btn-default pull-right" data-target="#chat_modal">
Отправить сообщение
</a>
