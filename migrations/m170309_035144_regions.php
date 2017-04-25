<?php

use yii\db\Migration;

class m170309_035144_regions extends Migration
{
    public function up()
    {
	$this->insert('regions', [
            'city_id' => '-2',
            'title' => 'не указан',
        ]);

	$this->insert('regions', [
            'city_id' => '3',
            'title' => 'Кировский',
        ]);
	$this->insert('regions', [
            'city_id' => '3',
            'title' => 'Ленинский',
        ]);
	$this->insert('regions', [
            'city_id' => '3',
            'title' => 'Орджоникидзевский',
        ]);

	$this->insert('regions', [
            'city_id' => '4',
            'title' => 'Большая Ялта',
        ]);

	$this->insert('regions', [
            'city_id' => '5',
            'title' => 'Феодосия',
        ]);

	$this->insert('regions', [
            'city_id' => '6',
            'title' => 'Евпатория',
        ]);
    }

    public function down()
    {
        echo "m170309_035144_regions cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
