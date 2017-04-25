<?
use yii\widgets\ListView;

?>

<section class="news">
	<div class="container narrow">
		<? echo $this->render('_news_filter', ['filter'=>$filter]); ?>
		<? echo ListView::widget([
			'dataProvider'=>$data,
			'itemView'=>'_list_item',
			'layout' => "{items}\n{pager}",
			'itemOptions' => ['class'=>'news-item'],
			]);
		?>
	</div>
</section>