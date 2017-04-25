<?
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class RateWidget extends Widget
{
	
	public $value;
	
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('/widgets/rate', ['value'=>floor($this->value / 5 * 100)]);
    }
}