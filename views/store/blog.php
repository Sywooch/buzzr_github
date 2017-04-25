<?
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use yii\widgets\ListView;

$url = Url::toRoute(['stores/view', 'id'=>$store->id]);

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="blog-list-items">
				<?= ListView::widget([
				        'dataProvider' => $blogDataProvider,
				        'itemOptions' => ['class' => 'blog-article'],
	        			'layout' => "{items}\n{pager}",
				        'itemView' => '_blog_view_item'
				]) ?>
			</div>
		</div>
	</div>
</section>
