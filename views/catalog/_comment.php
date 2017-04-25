<?
use yii\helpers\Html;
use app\components\MediaLibrary;
use yii\helpers\Url;
use app\models\User;
?>					
					<div class="comment bordered">
						<? if(User::isAdmin() || User::isThisId($comment->user->id)) : ?>
						<div class="pull-right actions">
							<a href="<?=Url::toRoute(['site/editcomment', 'id'=>$comment->id]);?>"><i class="fa fa-edit"></i></a>
							<a href="<?=Url::toRoute(['site/deletecomment', 'id'=>$comment->id]);?>" data-confirm="Удалить комментарий?"><i class="fa fa-close"></i></a>
						</div>
						<? endif ?>
						<? $avatar = $comment->user->avatar; ?>
						<? if($avatar) : ?>
						<div class="avatar">
							<div class="avatar-round"><img src="<?=MediaLibrary::getByFilename($avatar)->getResizedUrl($comment->user->avatar_crop . ' -resize 55x55')?>?>" alt=""></div>
						</div>
						<? endif ?>
						<div class="nameline">
							<div class="nameline-underline">
								<span class="name"><?=Html::encode($comment->user->name)?></span>
								<span class="date"><?=Html::encode($comment->created)?></span>
							</div>
						</div>
						<div class="text">
							<?=Html::encode($comment->text)?>
						</div>
						<? if($comment->answer) : ?>
						<div class="answer">
							Ответ: <?=Html::encode($comment->answer);?>
						</div>
						<? endif ?>
						<div class="clearfix"></div>
					</div>
