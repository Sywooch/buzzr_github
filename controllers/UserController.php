<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController as Controller;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\Message;
use app\models\ProductComment;
use app\models\User;
use app\models\LostForm;
use app\models\ConfirmForm;
use app\models\Order;
use app\models\Cart;
use app\models\UloginData;
use app\models\Notification;
use rmrevin\yii\ulogin\AuthAction;

use yii\helpers\Url;



class UserController extends Controller {
	
	private $_errors = [
		'ulogin_failed' => 'Пользователь не зарегистрирован. Зарегистрируйтесь или войдите под правильной учетной записью',
		'registered' => 'Такой пользователь уже зарегистрирован. Используйте пункт "вход"',
		'username' => 'Неверно указан email или пользователь уже активирован.',
		];
	
	
    public function actions()
    {
        return [
            'ulogin' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'uloginSuccessCallback'],
                'errorCallback' => function($data){
                    \Yii::error($data['error']);
                },
            ],
            'uregister' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'uregisterSuccessCallback'],
                'errorCallback' => function($data){
                    \Yii::error($data['error']);
                },
            ]
        ];
    }
    
    public function beforeAction($action)
	{            
	    if (in_array($action->id, ['ulogin', 'uregister'])) {
	        $this->enableCsrfValidation = false;
	    }
	
	    return parent::beforeAction($action);
	}
	
	public function loginSuccess(){
		return $this->redirect(['cabinet/user'])->send();	
	}

    public function uloginSuccessCallback($attributes)
    {
		$model = new LoginForm();
		if($model->ulogin($attributes['identity'])){
			return $this->loginSuccess();	
		}
		return $this->redirect(['user/login', 'error'=>'ulogin_failed']);
    }

    public function uregisterSuccessCallback($attributes)
    {
		$model = new LoginForm();
		if($model->checkulogin($attributes['identity'])){
			$ulogin = new UloginData();
			$ulogin->saveData($attributes);
			return $this->redirect(['user/register', 'use_social'=>1]);
		}
		
		
		return $this->redirect(['user/register', 'error'=>'registered']);
    }
	
	public function actionLogin($error = null, $ajax = 0){
		
		$model = new LoginForm();
		
		if($model->load($_POST) && $model->validate()){
			$model->login();
			$this->loginSuccess();
		}
		
		if($error && isset($this->_errors[$error]))
			$error = $this->_errors[$error];

		if(!$ajax){
			Url::remember();
			return $this->render('login', ['model'=>$model, 'error'=>$error]);
		}
		else
			return $this->renderAjax('login', ['model'=>$model, 'error'=>$error]);
	}
	
	public function actionLost(){
		$model = new LostForm();
		
		if($model->load($_POST) && $model->validate()){
			
			$user = User::findOne(['username'=>$model->username]);
			
			if($user){
				$link = Yii::$app->request->hostInfo . Url::toRoute(['user/hashlogin'] + $user->passwordResetGen());
				$notify = new Notification();
				$notify->setOptions($user->email, 'Восстановления пароля');
				$notify->compose('restore', ['link' => $link]);
				
				return $this->render('lost-found');
			} else {
				return $this->render('lost-found', ['error'=>'Пользователь не найден']);
			}
		}
		
		return $this->render('lost', ['model'=>$model]);
	}
	
	public function actionHashlogin($uid, $time, $hash){
		$user = User::findIdentity($uid);
		if(!$user)
			return $this->goHome();
			
		if(time() - $time > 60*60*24)
			return $this->render('link-expired');
			
		if($user->hashize($time) != $hash)
			return $this->goHome();
			
		Yii::$app->user->login($user, 0);	
		return $this->goHome();

	}
	
	public function actionRegister($use_social = null, $error = null, $ajax = 0){
		if(!Yii::$app->user->isGuest)
			return $this->goHome();
		
		$model = new RegisterForm();
		$ulogin = new UloginData();
		
		if($use_social)
			$model->scenario = 'social';

		if($model->load($_POST) && $model->validate()){
			if($model->register()){
				if($use_social)
					return $this->redirect(['cabinet/user'])->send();
				else
					return $this->redirect(['user/confirm', 'info'=>'registered', 'username' => $model->username])->send();
			}
			else
				return $this->redirect(['user/register', 'error'=>'registered'])->send();
		}
		
		if($error && isset($this->_errors[$error]))
			$error = $this->_errors[$error];

		if(!$ajax){
			Url::remember();
			return $this->render('register', ['model'=>$model, 'ulogin'=> $ulogin, 'error'=>$error, 'use_social'=> $use_social]);
		}
		else
			return $this->renderAjax('register', ['model'=>$model, 'ulogin'=> $ulogin, 'error'=>$error, 'use_social'=> $use_social]);
	}
	
	public function actionConfirm($info = null, $error = null, $username = null, $key = null, $ajax = 0){
		$model = new ConfirmForm();
		$retry_link = Yii::$app->request->hostInfo . Url::toRoute(['user/retry', 'username'=>$username]);
		
		if(!is_null($key) && !is_null($username) && $model->validate()){
			$model->verify_key = $key;
			$model->username = $username;
			if($model->verify())
				return $this->redirect(['cabinet/user'])->send();
			else
				return $this->redirect(['user/confirm', 'error'=>'invalid_v_key'])->send();
		}
		
		if($model->load($_POST) && $model->validate()){
			if($model->verify())
				return $this->redirect(['cabinet/user'])->send();
		}
		
		if($error && isset($this->_errors[$error]))
			$error = $this->_errors[$error];
		
		if(!$ajax){
			Url::remember();
			return $this->render('confirm', ['model'=>$model, 'info'=>$info, 'retry_link' => $retry_link, 'username' => $username, 'error'=>$error]);
		}
		else
			return $this->renderAjax('confirm', ['model'=>$model, 'info'=>$info, 'retry_link' => $retry_link, 'username' => $username, 'error'=>$error]);
	}
	
	public function actionRetry($username = null, $ajax = 0){
		$user = User::findOne(['username'=>$username]);
		if(!is_null($username) && $user && !$user->active){
			$virefy_link = Yii::$app->request->hostInfo . Url::toRoute(['user/confirm']);
			$notify = new Notification();
			$notify->setOptions($user->email, 'Активация пользователя');
			$notify->compose('retry_confirm', ['link' => $virefy_link, 'virefy_link' => $virefy_link, 'name' => $user->name, 'verify_key' => $user->verify_key]);
			
			return $this->redirect(['user/confirm', 'info'=>'retry', 'username'=>$username])->send();
		}
		else
			return $this->redirect(['user/confirm', 'error'=>'username'])->send();
	
	}
	
	public function actionLogout(){
		Yii::$app->user->logout();
		$this->goHome();
	}
	
}