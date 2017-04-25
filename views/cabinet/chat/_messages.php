<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\MediaLibrary;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<? Pjax::begin(['id'=>'chat_messages']); ?>
<div class="add-message">
	<? $form = ActiveForm::begin(['options'=>['data-pjax'=>1]]); ?>
		<div class="ava-wrap pull-left">
			<img src="<?=MediaLibrary::getByFilename(Yii::$app->user->identity->avatar)->getCropResized('50x50');?>" alt="">
		</div>
		<div class="ava-wrap pull-right">
			<img src="<?=MediaLibrary::getByFilename($message->_to->avatar)->getCropResized('50x50');?>" alt="">
		</div>
		<div class="center">
				<? echo $form->field($message, 'text')->textArea()->label(false); ?>
				<button class="blue-grad">Отправить</button>
		</div>
	<? ActiveForm::end(); ?>
</div>
<div class="messages-area">
	<? $lastava = false; foreach($messages->all() as $msg) : ?>
		<div class="message">
			<? if($msg->_from->id == Yii::$app->user->id) : ?>
			<a href="<?=Url::toRoute(['cabinet/chat/delete', 'id'=>$msg->id]);?>" class="close" data-confirm="Удалить сообщение?">&times;</a>
			<? endif ?>
				<div class="ava-wrap">
					<? $ava = MediaLibrary::getByFilename($msg->_from->avatar)->getCropResized('50x50'); ?>
					<? if($ava != $lastava) : $lastava = $ava; ?>
					<img src="<?=$ava ?>" alt="">
					<? endif ?>
				</div>
				<div><span class="name"><?=Html::encode($msg->_from->name); ?></span><span class="date"><?=$msg->_date?></span></div>
				<div class="text"><?=Html::encode($msg->text)?></div>
				<div class="clearfix"></div>
		</div>
	<? endforeach ?>
</div>
<? Pjax::end(); ?>