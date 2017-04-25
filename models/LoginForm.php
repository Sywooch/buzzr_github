<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

	public function attributeLabels(){
		return [
			'username' => 'Имя пользователя',
			'password' => 'Пароль'
		];
	}

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    
    public function ulogin($identity_str){
    	$username = UloginData::parse_ulogin_identity($identity_str);
    	$user = User::findOne(['username'=>$username]);
    	if(!$user){
    		return false;
    	}

    	return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);	
    	
    }
    
    public function checkulogin($identity_str){
    	$username = UloginData::parse_ulogin_identity($identity_str);
    	$user = User::findOne(['username'=>$username]);
    	if($user)
    		return false;
    		
    	return true;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Не верный логин или пароль.');
            }
        }
    }
    
     /**
     * Check is user confirm email or not
     * @return boolean
     */
    public function beforeValidate()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || (!$user->active && !$user->isSocial())) {
                $this->addError('username', 'Пользователь не активирован, проверьте вашу почту.');
                return false;
            }
        }
        return parent::beforeValidate();
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    
}
