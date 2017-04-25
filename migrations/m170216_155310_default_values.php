<?php

use yii\db\Migration;

class m170216_155310_default_values extends Migration
{
    public function up()
    {
        $this->alterColumn('store_blog_images', 'active', $this->smallInteger(1)->defaultValue(1));
        $this->alterColumn('product_photos', 'active', $this->smallInteger(1)->defaultValue(1));
    }

    public function down()
    {
        $this->alterColumn('store_blog_images', 'active', $this->integer()->dropDefault());
        $this->alterColumn('product_photos', 'active', $this->integer()->dropDefault());
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
