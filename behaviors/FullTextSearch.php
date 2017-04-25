<?
namespace app\behaviors;

use yii\db\ActiveRecord;
use yii\base\Behavior;

class FullTextSearch extends Behavior {
	
	public $fulltext_result_field;
	
	public $fulltext_source_fields;
	
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
        ];
    }
    
    public function beforeSave(){
    	$text = [];
    	foreach($this->fulltext_source_fields as $field){
    		$text[] = $this->owner->$field;
    	}
    	$text = join($text, ' ');
    	$result_field = $this->fulltext_result_field;
    	$this->owner->$result_field = $text;
    }
}