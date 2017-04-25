<?
use yii\helpers\Url;
?>
<a href="<?=Url::toRoute(['cabinet/chat', 'receiver_id'=>$receiver, 'ajax'=>1])?>" class="default-grad pull-right" data-target="#chat_modal">
Связатся с администрацией
</a>
