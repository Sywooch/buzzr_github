<?php

use yii\db\Migration;

class m170115_153849_update_products_visible extends Migration
{
    public function up()
    {
		$this->alterColumn('products', 'visible', 'TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down()
    {
        $this->alterColumn('products', 'visible', 'TINYINT(1) NOT NULL DEFAULT 0');
    }
}
