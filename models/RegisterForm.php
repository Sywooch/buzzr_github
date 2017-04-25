<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\UloginData;
use app\models\Notification;
use app\components\MediaLibrary;

use yii\helpers\Url;

class RegisterForm extends Model {
	public $name, $username, $password, $password_confirm, $use_social;
	
	public function rules(){
		return [
			[
				['name', 'username', 'password', 'password_confirm'], 'required'],
				['use_social', 'safe'],
				['password', 'password_validate'],
				['password_confirm', 'password_validate'],
				['username', 'email'],
				['name', 'string', 'min'=>2],
				['password', 'string', 'min'=>6],
				['user_type', 'validateUserType']
		];
	}
	public function attributeLabels(){
		return [
			'name' => 'Имя',
			'username' => 'e-mail',
			'password' => 'Пароль',
			'password_confirm' => 'Пароль повторно'
		];
	}
	
	public function scenarios(){
		return [
			'default' => ['name', 'username', 'password', 'password_confirm'],
			'social' => ['use_social']
		];
	}
	
	public function password_validate($attribute){
		if($this->password || $this->password_confirm){
			if($this->password != $this->password_confirm){
				$this->addError('password', 'Пароли должны совпадать');
				return false;
			}
		}
	}
	
	public function validateUserType(){
		if(!in_array($this->user_type, ['seller', 'customer', 'service']))
			$this->addError('user_type', 'Недопустимый тип пользователя');
	}
	
	public function register(){
		
		$user = new User;

		if($this->scenario == 'social'){
			$ulogin = new UloginData;
			$user->username = UloginData::parse_ulogin_identity($ulogin->ulogin['identity']);
			$user->password = uniqid();
			$user->name = $ulogin->uName;
			$user->email = $ulogin->uEmail;
			if($ulogin->uAvatar)
				$user->avatar = MediaLibrary::saveFromString(file_get_contents($ulogin->uAvatar))->filename;
		} else {
			$user->username = $this->username;
			$user->email = $this->username;
			$user->password = $this->password;
			$user->name = $this->name;
			$user->verify_key = Yii::$app->security->generateRandomString().uniqid();
		}

		if(User::find()->where(['username'=>$user->username])->count() > 0)
			return false;

		$user->save();
		
		$link = Yii::$app->request->hostInfo . Url::toRoute(['user/confirm', 'username' => $user->email, 'key' => $user->verify_key]);
		$login_link = Yii::$app->request->hostInfo . Url::toRoute(['user/login']);
		$virefy_link = Yii::$app->request->hostInfo . Url::toRoute(['user/confirm']);
		$notify = new Notification();
		$notify->setOptions($user->email, 'Активация пользователя');
		$notify->compose('register', ['link' => $link, 'login_link' => $login_link, 'virefy_link' => $virefy_link, 'name' => $user->name, 'username' => $user->username, 'password' => $this->password, 'scenario' => $this->scenario, 'verify_key' => $user->verify_key]);
		
		return true;
	}

}
