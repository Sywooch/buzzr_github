<?php

use yii\db\Migration;

class m170115_155523_add_column_verify_key_to_user extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'verify_key', 'VARCHAR(255) NULL DEFAULT NULL AFTER `active`');
    }

    public function down()
    {
        $this->dropColumn('user', 'verify_key');
    }
}
