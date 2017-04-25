<?
namespace app\models;

use yii\base\Model;
use Yii;

class Notification extends Model {
	
	public $to, $from, $text, $subject;
	
	public function setOptions($to = null, $subject = null, $text = null, $from = null){
		$this->to = $to;
		$this->subject = $subject;
		$this->from = $from;
		$this->text = $text;
		if(is_null($this->from)){
			$name = str_replace('http://', '', Yii::$app->request->hostInfo);
			$name = str_replace('https://', '', $name);
			$name = str_replace('www.', '', $name);
			$name = 'Робот '.$name;
			$email = Yii::$app->params['robotEmail'];
			$this->from = [$email => $name];
		}
	}
	
	public function send(){
		Yii::$app->mailer->compose()
		     ->setFrom($this->from)
		     ->setTo($this->to)
		     ->setSubject($this->subject)
		     ->setTextBody($this->text)
		     ->send();
	}
	
	public function compose($template, $args){
		Yii::$app->mailer->compose($template, $args)
		     ->setFrom($this->from)
		     ->setTo($this->to)
		     ->setSubject($this->subject)
		     ->send();
	}
}