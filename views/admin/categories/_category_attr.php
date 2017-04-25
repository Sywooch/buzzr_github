<?
use unclead\widgets\MultipleInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
$isNewAttr = ($attr_id == 'new');
?>
		<? $form = ActiveForm::begin(['action'=>Url::current(['attrib'=>$attr_id])]); ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="#a_<?=$attr_id;?>" class="orangehover" data-toggle="collapse"><?=Html::encode($attr_name);?></a>
				</div>
				<div class="collapse <? if(isset($attributes_update->addErrors[$attr_id]))echo "in"; ?>" id="a_<?=$attr_id;?>">
					<div class="panel-body">
						<?php
						    echo $form->field($attributes_update, $attr_id)->widget(MultipleInput::classname(), [
						    	'limit' => 10,
						    	'min' => 1,
						        'allowEmptyList'    => false,
						        'enableGuessTitle'  => false,
						        'addButtonPosition' => MultipleInput::POS_FOOTER,
						    ])->label('Варианты значений');
						?>
						<? if(isset($attributes_update->addErrors[$attr_id]))
							echo ($attributes_update->addErrors[$attr_id]); ?>

						<? if(!$isNewAttr) : ?>
						<div id="rename_<?=$attr_id;?>" class="collapse">
							<? echo $form->field($attributes_update, "rename[$attr_id]")->label('Изменить имя'); ?>
						</div>
						<? else : ?>
							<? echo $form->field($attributes_update, "rename[$attr_id]")->label('Изменить имя'); ?>
						<? endif ?>

						<? echo $form->field($attributes_update, "multi[$attr_id]")->dropDownList([0=>'Нет', 1=>'Да'])->label('Мульти-выбор'); ?>

					</div>
					<div class="panel-footer">
								<input type="submit" name="save" class="blue-grad" value="Сохранить">
								<input type="submit" name="delete" class="default-grad" value="Удалить всё" data-confirm="Удалить группу свойств <<<?=Html::encode($attr_name);?>>> полностью? Уверены?">
								<? if(!$isNewAttr) : ?>
									<a href="#rename_<?=$attr_id;?>" data-toggle="collapse" class="default-grad">Переименовать</a>
								<? endif ?>
					</div>
				</div>
			</div>
		<? ActiveForm::end(); ?>
