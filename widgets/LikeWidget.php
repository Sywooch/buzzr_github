<?
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class LikeWidget extends Widget
{
	
	public $toggleUrl, $initVal, $isLiked;
	
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('/widgets/likes', ['initVal'=>$this->initVal, 'toggleUrl'=>$this->toggleUrl, 'isLiked'=>$this->isLiked]);
    }
}