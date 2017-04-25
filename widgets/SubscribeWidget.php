<?
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class SubscribeWidget extends Widget
{
	
	public $toggleUrl, $isSubscribed;
	
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('/widgets/subscribe', ['toggleUrl'=>$this->toggleUrl, 'isSubscribed'=>$this->isSubscribed]);
    }
}