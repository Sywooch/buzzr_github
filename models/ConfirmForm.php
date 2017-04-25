<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Notification;

use yii\helpers\Url;

class ConfirmForm extends Model {
	public $verify_key, $username, $user;
	
	public function rules(){
		return [
			[
				['verify_key', 'username'], 'required'],
				['username', 'email'],
				['username', 'verifyUser']
		];
	}
	public function attributeLabels(){
		return [
			'verify_key' => 'Ключ активации',
			'username' => 'e-mail'
		];
	}
	
	public function verifyUser($attribute, $params){
		if (!$this->hasErrors()) {
            $this->user = User::findOne(['username'=>$this->username]);
			
			if(!$this->user || $this->user->active){
				$this->addError('username', 'Не существующий или уже активированный пользователь.');
				return false;
			}
        }
	}
	
	public function verify(){
		if($this->user->verify_key != $this->verify_key){
			$this->addError('verify_key', 'Не верный ключ активации.');
			return false;
		}
		else{
			$this->user->active = 1;
			$this->user->save();
			Yii::$app->user->login($this->user, 3600*24*30);
			return true;
		}
	}
}