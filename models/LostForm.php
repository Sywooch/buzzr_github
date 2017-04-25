<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LostForm extends Model
{
	public $username;
	public $reCaptcha;

	
	public function attributeLabels(){
		return [
			'username' => 'Логин или электронная почта',
			'reCaptcha' => 'Подтвердите, что Вы - не робот',
		];
	}
	
	public function rules(){
		return [
			['username', 'required'],
			[['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className()]
		];
	}
}
