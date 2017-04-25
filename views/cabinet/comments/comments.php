<?
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Url;
use app\models\User;

?>
		<div class="comments">
			<div class="row text-center">
				<div class="col-sm-4">
					Товар
				</div>
				<div class="col-sm-6">
					Комментарий
				</div>
			</div>
			<? if($commentsProvider->totalCount) : ?>
			<div class="iconized-text">
				<div class="bg-icon">
					<i class="fa fa-pencil-square-o"></i>
				</div>
				<div class="text">
				Здесь отображаются комментарии, оставленные вами или для вашего товара
				</div>
			</div>
			<? else : ?>
			<? echo ListView::widget([
				'dataProvider'=>$commentsProvider,
				'itemView'=>'_comments_product',
				'options' => [],
				'layout' => "{items}\n{pager}",
				'itemOptions' => ['class'=>'comment'],
				]);
			?>
			<? endif ?>
			</div>
