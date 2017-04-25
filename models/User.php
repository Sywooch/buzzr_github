<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
	
	public $password_change, $password_confirm, $avatar_tmp, $image, $update_avatar;
	public $x1, $y1, $width, $height, $ratio;
	
	use \app\traits\MultiplePhotos;
	
	public function rules(){
		return [
			[['password_change', 'password_confirm', 'name', 'email', 'phone', 'address', 'update_avatar'], 'safe'],
            [['image', 'avatar_tmp'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
			[['x1', 'y1', 'width', 'height', 'ratio'], 'safe'],
			[['x1', 'y1', 'width', 'height'], 'integer'],
			['password_change', 'password_validate'],
			['password_confirm', 'password_validate'],
		];
	}
	
	public function attributeLabels(){
		return [
			'name' => 'Имя',
			'city' => 'Город',
			'phone' => 'Телефон',
			'address' => 'Адрес',
			'created' => 'Дата регистрации',
			'password_change' => 'Новый пароль',
			'password_confirm' => 'Повторите пароль'
		];
	}

	public function behaviors()
	{
	    return [
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created',
	            'updatedAtAttribute' => 'updated',
	            'value' => new Expression('NOW()'),
	        ],
	    ];
	}	

	public function password_validate($attribute){
		if($this->password_change || $this->password_confirm){
			if($this->password_change != $this->password_confirm){
				$this->addError('password_change', 'Пароли должны совпадать');
				return false;
			}
		}
	}
	
	public function beforeSave($insert){
		
		if($this->password_change && ($this->password_change == $this->password_confirm))
			$this->password = $this->password_change;
			
		if( ($this->email != $this->getOldAttribute('email')) && ($this->getOldAttribute('email') == $this->username)){
			$this->username = $this->email;
		}

		if($this->update_avatar)
			$this->avatar = $this->update_avatar;

		if($this->update_avatar && $this->ratio){
			$x1 = round($this->x1 / $this->ratio);
			$y1 = round($this->y1 / $this->ratio);		
			$height = round($this->height / $this->ratio);		
			$width = round($this->width / $this->ratio);
			$this->avatar_crop = "-crop {$width}x{$height}+{$x1}+{$y1} -gravity NorthWest";
		}
		
		return parent::beforeSave($insert);
	}
	
	public function getIsOnline(){
		return ((time() - $this->activity) < 60);
	}
	
	public function getMystore(){
		return $this->hasOne(Store::className(), ['user_id'=>'id']);
	}

	public function isSocial(){
		return preg_match('%^(fb|vk)@\d+$%', $this->username);
	}
	
	public static function isAdmin(){
		if(Yii::$app->user->isGuest)
			return false;
			
		if(Yii::$app->user->identity->type != 'admin')
			return false;
			
		return true;
	
	}

	public static function isThisId($id){
		if(Yii::$app->user->isGuest)
			return false;
		if(Yii::$app->user->id != $id)
			return false;
			
		return true;
	}

	public static function mustBeId($id, $allowAdmin = true){
		if(User::isThisId($id))
			return true;
			
		if($allowAdmin)
			return User::mustBeAdmin();
				
		Yii::$app->response->redirect(['user/login'])->send();
		
		return false;
			
	}

	public static function mustBeAdmin(){
		
		if(!User::isAdmin()){
			Yii::$app->response->redirect(['user/login'])->send();
		}
			
		return true;
	}

	public static function findIdentity($id){
		return static::findOne($id);
	}
 
	public static function findIdentityByAccessToken($token, $type = null){
		throw new NotSupportedException();//I don't implement this method because I don't have any access token column in my database
	}
 
	public function getId(){
		return $this->id;
	}
 
	public function getAuthKey(){
		return $this->authkey;//Here I return a value of my authKey column
	}
 
	public function validateAuthKey($authKey){
		return $this->authkey === $authKey;
	}
	public static function findByUsername($username){
		return self::findOne(['username'=>$username]);
	}
 
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authkey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
     
    public function hashize($time){
    	return md5($time . "zekret" . $this->id);
    }
     
    public function passwordResetGen()
    {
		$time = time();
		$hash = $this->hashize($time);
		return ['uid'=>$this->id, 'time'=>$time, 'hash'=>$hash];
    }
    
    public function passwordResetCheck($time, $hash){
		return ($hash == $this->hashize($time));
    }
    
    public function getSetting($key){
    	$settings = JSON_decode($this->settings, true);
    	if(isset($settings[$key]))
    		return $settings[$key];
    	else
    		return null;
    	
    }
    
    public function setSetting($key, $val){
    	$settings = JSON_decode($this->settings, true);
    	if(!is_array($settings))$settings = [];
    	$settings[$key] = $val;
    	$this->settings = $settings;
    	$this->save(false, ['settings']);
    }
    
}
