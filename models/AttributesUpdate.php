<?
namespace app\models;

use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class AttributesUpdate extends Model {
	public $category_id;
	
	private $vars, $names, $new_prop, $multi;
	public $addErrors = [];

	private function getVars(){
		
		if(!$this->vars){
			$q = new Query;
			$this->vars = ArrayHelper::map($q->select('label, attribute_value.id as id, category_attributes.attribute_id as a_id')->from('attribute_value')
				->leftJoin('category_attributes', 'attribute_value.attribute_id = category_attributes.attribute_id')
				->where(['category_attributes.category_id'=>$this->category_id])->all(), 'id', 'label', 'a_id');
		}
		return $this->vars;

	}
	
	private function getNames(){
		
		if(!$this->names){
			$q = new Query;
			$this->names = ArrayHelper::map($q->select('attributes_list.id as id, attributes_list.title')->from('attributes_list')
				->leftJoin('category_attributes', 'attributes_list.id = category_attributes.attribute_id')
				->where(['category_attributes.category_id'=>$this->category_id])->all(), 'id', 'title');
		}
		return $this->names;

	}
	
	private function getMulti(){
		
		if(!$this->multi){
			$q = new Query;
			$this->multi = ArrayHelper::map($q->select('attributes_list.id as id, attributes_list.multi')->from('attributes_list')
				->leftJoin('category_attributes', 'attributes_list.id = category_attributes.attribute_id')
				->where(['category_attributes.category_id'=>$this->category_id])->all(), 'id', 'multi');
		}
		return $this->multi;

	}
	
	public function rules(){
		$vals = $this->getVars();
		$retval = [[array_keys($vals), 'safe']];
		$retval[] = ['rename', 'safe'];
		$retval[] = ['multi', 'safe'];
		$retval[] = ['new', 'safe'];
		return $retval;
	}
	
	public function __get($key){
		$vars = $this->getVars();
		$names = $this->getNames();
		$multi = $this->getMulti();
		if(isset($vars[$key]))
			return $vars[$key];
			
		if($key == 'rename'){
			return $names;
		}
		
		if($key == 'multi'){
			return $multi;
		}
		
		if($key == 'new'){
			return [];
		}
		
		return null;
	}

	// не очень красиво - работа с базой из set, а не из какого-нибудь save(). если поменять в форме порядок полей - может аукнуться
	public function __set($key, $val){

		if($key == 'multi'){
			foreach($val as $n_key=>$n_val){
					\Yii::$app->db->createCommand()->update('attributes_list', ['multi'=>$n_val], ['id'=>$n_key])->execute();
			}
			$this->multi = null;
			$this->getMulti();
		}

		$names = $this->getNames();
		if($key == 'new'){
			$this->new_prop = $val;
		}
		
		if($key == 'rename'){
			foreach($val as $n_key=>$n_val){
				
				if($n_key == 'new'){
					$title = $n_val;
					echo "Обрабатываем новое свойство $title ";
					// создаем свойство
					\Yii::$app->db->createCommand()->insert('attributes_list', ['title'=>$n_val, 'name'=>md5($title)])->execute();
					$attr_id = \Yii::$app->db->getLastInsertID();
					// отмечаем его в списке свойств категорий
					\Yii::$app->db->createCommand()->insert('category_attributes', ['category_id'=>$this->category_id, 'attribute_id'=>$attr_id])->execute();
					// добавляем значения в таблицу значений
					foreach($this->new_prop as $n_key=>$n_val){
						\Yii::$app->db->createCommand()->insert('attribute_value', ['label'=>$n_val, 'attribute_id'=>$attr_id, 'value'=>md5($n_val)])->execute();
					}
					$this->vars = null;
					$this->getVars();
				}
				
				if(isset($names[$n_key]) && ($names[$n_key] != $n_val)){
					echo "переименован $n_key в $n_val ";
					\Yii::$app->db->createCommand()->update('attributes_list', ['title'=>$n_val], ['id'=>$n_key])->execute();
				}
			}
			$this->names = null;
			$this->getNames();
		}
		
		$vals = $this->getVars();
		if(isset($vals[$key])){
			$oldVals = $vals[$key];
			// удалить из базы удаленные и изменить измененные
			foreach($oldVals as $o_key=>$o_val){
				if(!isset($val[$o_key])){
					//echo "на удаление $o_key ";
					$q = new Query;
					$count = $q->select('count(*)')->from('product_attribute')->where(['value_id'=>$o_key])->scalar();
					if($count > 0){
						$this->addErrors[$key] = "Невозможно удалить $o_val, значение используется $count в товарах";
					} else {
						\Yii::$app->db->createCommand()->delete('attribute_value', ['id'=>$o_key])->execute();
					}
				} elseif($val[$o_key] != $oldVals[$o_key]){
					//echo "изменено $o_key на {$val[$o_key]} ";
					\Yii::$app->db->createCommand()->update('attribute_value', ['label'=>$val[$o_key]], ['id'=>$o_key])->execute();
				}
			}
			foreach($val as $n_key=>$n_val){
				if(!isset($oldVals[$n_key])){
					//echo "Новый аттр: $n_val ";
					\Yii::$app->db->createCommand()->insert('attribute_value', ['label'=>$n_val, 'attribute_id'=>$key, 'value'=>md5($n_val)])->execute();
				}
			}
			$this->vars = null;
			$this->getVars();
		}
	}
	
	public function tryDelete($attrib){
		
		$q = new Query;
		
		$count_product = $q->select("count(*)")->from('product_attribute')
			->where(['attribute_id'=>$attrib])->scalar();
			
		$count_cat = $q->select("count(*)")->from('category_attributes')
			->where(['attribute_id'=>$attrib])
			->andWhere("category_id != {$this->category_id}")
			->scalar();
			
		if($count_product + $count_cat){
			$this->addErrors[$attrib] = "Удаление запрещено, свойства используются в $count_product товарах и $count_cat других категориях";
			return false;
		} else {
			\Yii::$app->db->createCommand()->delete('attributes_list', ['id'=>$attrib])->execute();
			\Yii::$app->db->createCommand()->delete('attribute_value', ['attribute_id'=>$attrib])->execute();
			\Yii::$app->db->createCommand()->delete('category_attributes', ['attribute_id'=>$attrib])->execute();
			return true;
		}
	}
	
	public function canGetProperty($key){
		return true;
	}
	
}