<?
namespace app\traits;
use yii\db\Query;


trait Likeable {
	public function toggleLike($user_id){
		
		$table = $this->likeTableName();
		$field = $this->likeEntityName();
		$thisid = $this->id;
		if($liked = $this->isLikedBy($user_id)){
			// delete like
			$query = \Yii::$app->db->createCommand("DELETE FROM $table WHERE ($field = $thisid) AND (user_id = '$user_id')")->execute();
		} else {
			// add like
			$query = \Yii::$app->db->createCommand("INSERT INTO $table ($field, user_id) VALUES ('$thisid', '$user_id')")->execute();
		}
		
		$this->updateLikes();
		
		return ['liked'=>!$liked, 'newCount'=>$this->likes];
	}
	
	public function isLikedBy($user_id){
		
		if(!$user_id)
			$user_id = '';
		
		$query = new Query;
		$query = $query -> select('*')
				->from($this->likeTableName())
				->where([$this->likeEntityName()=>$this->id, 'user_id'=>$user_id])
				->count();
				
		return (0 != $query);
	}

	public function updateLikes(){
		$query = new Query;
		$likes = $query -> select('count(*)')
			->from($this->likeTableName())
			->where([$this->likeEntityName()=>$this->id])
			->column();
			
		$this->likes = $likes[0];
		$this->update(false, ['likes']);
	}


}