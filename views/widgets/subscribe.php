<?
use app\assets\SubscribeAsset;

SubscribeAsset::register($this);
?>
<span class="subscribe-widget">
<button type="button" class="subscribe btn btn-xs <?=$isSubscribed ? 'subscribed' : ''?>" data-toggle-url="<?=$toggleUrl?>">
	<span class="do-subscribe">Подписаться</span>
	<span class="do-unsubscribe">Отписаться</span>
</button>
</span>