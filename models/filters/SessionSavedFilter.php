<?php

namespace app\models\filters;

use Yii;
use yii\base\Model;

class SessionSavedFilter extends Model {
	
	public function session_key(){
		return get_class($this);
	}
	
	public function loadFilter($data){
		$this->fromSession();
		$this->load($data);
		
		$this->saveToSession();
	}
	
	public function fromSession(){
		$this->attributes = \Yii::$app->session->get($this->session_key());
	}
	
	public function saveToSession(){
		\Yii::$app->session->set($this->session_key(), $this->attributes);
	}

}