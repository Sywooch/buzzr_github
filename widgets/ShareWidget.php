<?
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ShareWidget extends Widget
{
	
	public $url, $title;
	
    public function init()
    {
		parent::init();
    }

    public function run()
    {
        return $this->render('/widgets/share', ['url'=>$this->url, 'title'=>$this->title]);
    }
}