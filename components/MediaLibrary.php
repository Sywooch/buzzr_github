<?
namespace app\components;

use yii\base\Model;
use Yii;

class MediaLibrary extends Model {
	
	public $filename;
	
	public static function getByFilename($filename){
		
		if(!$filename)
			$filename = "c09b6f9d86893e03e9780ca29c3e812b.jpg";
		
		$file = new MediaLibrary;
		$file->filename = $filename;
		return $file;
	}
	
	public static function saveFromString($data){
		$file = new MediaLibrary;
		$file->filename = md5($data) . '.jpg';
		$local = $file->getLocalFilename();
		$path = pathinfo($local, PATHINFO_DIRNAME);
		if(!file_exists($path))
			mkdir($path);
			
		file_put_contents($local, $data);
		
		return $file;
	}
	
	public function getUrl(){
		return '/uploads/media/' . $this->getDirectoryPrefix() . '/' . $this->filename;
	}
	
	public function getResizedUrl($size_cmd){
		
		$resized = new MediaLibrary;
		$resized->filename = md5($this->filename . '-' . $size_cmd) . 'r.jpg';
		if(!file_exists($resized->getLocalFilename())){
			$src = str_replace('/', DIRECTORY_SEPARATOR ,$this->getLocalFilename());
			$dst = str_replace('/', DIRECTORY_SEPARATOR ,$resized->getLocalFilename());
			$dst_path = pathinfo($dst, PATHINFO_DIRNAME);
			if(!file_exists($dst_path))
				mkdir($dst_path);
			system("convert $src $size_cmd $dst");
		}

		return $resized->getUrl();
	}
	
	public function getCropResized($size){
		return $this->getResizedUrl("-resize $size^ -gravity center -crop $size+0+0 +repage");
	}
	
	private function getDirectoryPrefix(){
		return substr($this->filename, 0, 3);
	}
	
	private function getLocalFilename(){
		return Yii::getAlias('@webroot') . $this->getUrl();
	}
}