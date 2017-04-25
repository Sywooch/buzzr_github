<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Block extends ActiveRecord {


 public static function tableName(){
        return 'blocks';
    }

	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';

     public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['sort', 'title', 'active', 'slug'],
            self::SCENARIO_UPDATE => ['sort', 'title', 'active', 'slug'],
        ];
    }


    public function rules(){
        return [
            [['title', 'active', 'sort', 'slug'], 'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'title' => 'Название',
            'slug' => 'Код адреса',
            'sort' => 'Порядок очередности отображения',
            'active' => 'Активность',
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable('block_product', ['block_id' => 'id']);
    }

}