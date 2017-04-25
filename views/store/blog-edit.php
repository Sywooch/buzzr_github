<?
use app\widgets\LikeWidget;
use app\widgets\ShareWidget;
use yii\helpers\Url;
use yii\widgets\ListView;

$url = Url::toRoute(['stores/view', 'id'=>$store->id]);

?>
<section class="store">
	<div class="container narrow">
		<? echo $this->render('_header_edit', ['store'=>$store]); ?>
		<div class="subpage-content">
			<div class="action-buttons text-right">
				<a href="<?=Url::toRoute(['news/create', 'store_id'=>$store->id])?>" class="orange-grad">Добавить публикацию</a>
			</div>
			<div class="blog-list-items">
				<?= ListView::widget([
				        'dataProvider' => $blogDataProvider,
	        			'layout' => "{items}\n{pager}",
				        'itemOptions' => ['class' => 'blog-article'],
				        'itemView' => '_blog_edit_item'
				]) ?>
			</div>
		</div>
	</div>
</section>
