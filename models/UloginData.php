<?php

namespace app\models;

use Yii;
use yii\base\Model;

class UloginData extends Model {

    public static function parse_ulogin_identity($identity){
    	if(preg_match('%id(\d+)%', $identity, $matches)){
    		return 'vk@' . $matches[1];
    	}
    	if(preg_match('%id\/(\d+)\/%', $identity, $matches)){
    		return 'fb@' . $matches[1];
    	}
    	
    	return md5($identity);
    	
    }

	
	public function saveData($data){
		Yii::$app->session->set('ulogin', $data);
	}

	public function getUlogin(){
		return Yii::$app->session->get('ulogin');
	}
	
	public function getUEmail(){
		if(!isset($this->ulogin['email']))
			return '';
			
		return $this->ulogin['email'];
	}
	
	public function getUAvatar(){
		if(!isset($this->ulogin['photo_big']))
			return '';
			
		return $this->ulogin['photo_big'];
	}
	
	public function getUName(){
		$ulogin = $this->ulogin;
		$name = [];
		foreach(['last_name', 'first_name'] as $na)
			if(isset($ulogin[$na]) && $ulogin[$na])
				$name[] = $ulogin[$na];
				
		return join($name, ' ');
	}
}