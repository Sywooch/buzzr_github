<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class BlockProduct extends ActiveRecord {


 public static function tableName(){
        return 'block_product';
    }

	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';

     public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['sort', 'active'],
            self::SCENARIO_UPDATE => ['sort', 'active'],
        ];
    }


    public function rules(){
        return [
            [['sort', 'active'], 'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'sort' => 'Порядок очередности отображения',
            'active' => 'Активность',
        ];
    }

}