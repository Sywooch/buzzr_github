<?php

use yii\db\Migration;

class m170321_160240_store_cr_up extends Migration
{
    public function up()
    {
	$this->alterColumn('store', 'created', 'timestamp default CURRENT_TIMESTAMP');
	$this->alterColumn('store', 'updated', 'timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP');
    }

    public function down()
    {
        echo "m170321_160240_store_cr_up cannot be reverted.\n";

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
