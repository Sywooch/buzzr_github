<?
namespace app\traits;

use yii\helpers\Url;
use Yii;
use app\components\MediaLibrary;

/*
У родителя должны быть определены:
	image_list - список картинок в формате JSON
	getDestDir() - куда складывать картинки
*/

trait MultiplePhotos {
	
	public $photos;
	
	public function upload($resize = false){
	    if ($this->validate(['image'])) {
	    	$file = MediaLibrary::saveFromString(file_get_contents($this->image->tempName));
            $image = [
            	'name' => $file->filename,
                'size' => $this->image->size,
                'geometry' => getimagesize($this->image->tempName),
                "url" => $resize ? $file->getResizedUrl($resize) : $file->getUrl(),
                "url_full" => $file->getUrl()
                ];
                
            return ['files'=>$image];
	    }
	    return ['errors'=>$this->errors['image']];

	}
	
	function multiPhotoAfterSave($table, $field, $max = 1000){
		
		if($this->id && $this->image_list){
			$image_list = JSON_decode($this->image_list, true);

			Yii::$app->db->createCommand()
				->delete($table, [$field=>$this->id])
				->execute();
		
			$count = 0;	
			foreach($image_list as $image){
				if(++$count > $max)
					break;

				$fields = [
						$field=>$this->id,
						'filename'=>$image['name']
					];
					
				if(isset($image['crop']))
					$fields['crop'] = $image['crop'];
					
				if(isset($image['ratio'])){
					$ratio = $image['ratio'];
					
					if($ratio){
						$x1 = round($image['x1'] / $ratio);
						$y1 = round($image['y1'] / $ratio);		
						$height = round($image['height'] / $ratio);		
						$width = round($image['width'] / $ratio);
						
						$fields['crop'] = "-crop {$width}x{$height}+{$x1}+{$y1} -gravity NorthWest";
					}
				}
				
				Yii::$app->db->createCommand()
					->insert($table, $fields)
					->execute();
			}
		}
	}

	public function getPhotoUrl($resize_cmd = false){
		
		$photos =  $this->getPhotos();
		
		if(!$photos)
			return $photos;
			
		if(false !== $resize_cmd)
			return MediaLibrary::getByFilename($photos[0])->getResizedUrl($resize_cmd);
		else
			return MediaLibrary::getByFilename($photos[0])->getUrl();
	}

	public function getPhotos(){

		if(!$this->photos)
			return '';
			
		return preg_split('%,%', $this->photos);
	}

}

