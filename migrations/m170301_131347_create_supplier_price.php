<?php

use yii\db\Migration;

class m170301_131347_create_supplier_price extends Migration
{
    public function up()
    {
	$this->addColumn('products', 'supplier_prices', 'varchar(1000)');
    }

    public function down()
    {
        $this->dropColumn('products', 'supplier_prices');
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
