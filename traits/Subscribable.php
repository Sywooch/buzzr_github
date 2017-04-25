<?
namespace app\traits;
use yii\db\Query;


trait Subscribable {
	public function toggleSubscribe($user_id){
		
		$table = $this->subscribeTableName();
		$field = $this->subscribeEntityName();
		$thisid = $this->id;
		if($subscribed = $this->isSubscribedBy($user_id)){
			// delete
			$query = \Yii::$app->db->createCommand("DELETE FROM $table WHERE ($field = $thisid) AND (user_id = '$user_id')")->execute();
		} else {
			// add
			$query = \Yii::$app->db->createCommand("INSERT INTO $table ($field, user_id) VALUES ('$thisid', '$user_id')")->execute();
		}
		
		return ['subscribed'=>!$subscribed];
	}
	
	public function isSubscribedBy($user_id){
		
		if(!$user_id)
			$user_id = '';
		
		$query = new Query;
		$query = $query -> select('*')
				->from($this->subscribeTableName())
				->where([$this->subscribeEntityName()=>$this->id, 'user_id'=>$user_id])
				->count();
				
		return (0 != $query);
	}

	public function getSubscribers(){
		$query = new Query;
		$subscribers = $query -> select('count(*)')
			->from($this->subscribeTableName())
			->where([$this->subscribeEntityName()=>$this->id])
			->column();
			
		return $subscribers[0];
	}
}