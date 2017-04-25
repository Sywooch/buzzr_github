<?php

use yii\db\Migration;

class m170309_034625_cities extends Migration
{
    public function up()
    {
	$this->insert('cities', [
            'id' => '-2',
            'title' => 'Другой',
        ]);

	$this->insert('cities', [
            'id' => '3',
            'title' => 'Керчь',
        ]);
	$this->insert('cities', [
            'id' => '4',
            'title' => 'Ялта',
        ]);
	$this->insert('cities', [
            'id' => '5',
            'title' => 'Феодосия',
        ]);
	$this->insert('cities', [
            'id' => '6',
            'title' => 'Евпатория',
        ]);
    }

    public function down()
    {
        echo "m170309_034625_cities cannot be reverted...\n";

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
