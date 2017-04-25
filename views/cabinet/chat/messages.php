<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\MediaLibrary;
use yii\helpers\Html;

?>
		<div class="row">
			<div class="col-sm-3">
				<? echo $this->render('_contragents', ['contragents'=>$contragents]); ?>
			</div>
			<div class="col-sm-9">
				<? echo $this->render('_messages', ['messages'=>$messages, 'message'=>$message]); ?>
			</div>
		</div>
