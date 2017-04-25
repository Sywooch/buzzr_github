<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\MediaLibrary;


class Banners extends ActiveRecord {
	
	public $banner_tmp, $update_banner, $image;
	
	public $x1, $y1, $width, $height, $ratio;
	
	public function attributeLabels(){
		return [
			'title' => 'Подпись',
			'file' => 'Изображение',
			'url' => 'Ссылка',
			'color' => 'Цвет подписи',
			'config' => 'Параметры ресайза'
		];
	}
	
	public function beforeSave($i){
		
		if($this->update_banner && $this->ratio){
			$x1 = round($this->x1 / $this->ratio);
			$y1 = round($this->y1 / $this->ratio);		
			$height = round($this->height / $this->ratio);		
			$width = round($this->width / $this->ratio);
			$this->crop = "-crop {$width}x{$height}+{$x1}+{$y1} -gravity NorthWest";
		}
		
		return parent::beforeSave($i);
	}
	
	public function rules(){
		return [
			[['title', 'url', 'config', 'color'], 'safe'],
			['update_banner', 'safe'],
			[['x1', 'y1', 'width', 'height', 'ratio'], 'safe'],
			[['x1', 'y1', 'width', 'height'], 'integer'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
		];
	}
	
	public function getImg(){
		return MediaLibrary::getByFilename($this->file)->getResizedUrl($this->crop . ' -resize x300');
	}
	
	public function upload($resize = false){
	    if ($this->validate(['image'])) {
	    	$file = MediaLibrary::saveFromString(file_get_contents($this->image->tempName));
            $image = [
            	'name' => $file->filename,
                'size' => $this->image->size,
                'geometry' => getimagesize($this->image->tempName),
                "url" => $resize ? $file->getResizedUrl($resize) : $file->getUrl(),
                ];
                
            return ['files'=>$image];
	    }
	    return ['errors'=>$this->errors['image']];

	}
}