<?
use app\assets\LikeAsset;

LikeAsset::register($this);
?>
<span class="like-widget">
<button type="button" class="like btn btn-xs <?=$isLiked ? 'liked' : ''?>" data-toggle-url="<?=$toggleUrl?>">
	<span class="glyphicon glyphicon-thumbs-up"></span>
</button>
<span class="like-counter"><?=$initVal?></span>
</span>