<?php

use yii\db\Migration;

class m170301_130405_create_supplier_field extends Migration
{

    public function up()
    {
	$this->addColumn('store', 'is_supplier', 'integer default 0 after is_service');
    }

    public function down()
    {
        $this->dropColumn('store', 'is_supplier');
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
