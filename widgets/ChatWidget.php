<?
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ChatWidget extends Widget
{
	
	public $receiver, $template = '/widgets/chat';
	
    public function init()
    {
		parent::init();
    }

    public function run()
    {
        return $this->render($this->template, ['receiver'=>$this->receiver]);
    }
}