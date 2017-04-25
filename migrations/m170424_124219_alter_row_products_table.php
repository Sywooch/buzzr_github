<?php

use yii\db\Migration;

class m170424_124219_alter_row_products_table extends Migration
{
    public function up()
    {
        $this->addColumn('products', 'select', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('products', 'select');

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
