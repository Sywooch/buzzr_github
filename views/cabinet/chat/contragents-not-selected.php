<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
?>

		<div class="row">
			<div class="col-sm-3">
				<? echo $this->render('_contragents', ['contragents'=>$contragents]); ?>
			</div>
			<div class="col-sm-9">
				<? if(!empty($contragents)) : ?>
				<div class="messages-area not-selected">
					<div class="iconized-text">
						<div class="bg-icon">
							<i class="fa fa-comments-o"></i>
						</div>
						<div class="text">
							Выберите, с кем хотите вести переписку
						</div>
					</div>
				</div>
				<? endif ?>
			</div>
		</div>
