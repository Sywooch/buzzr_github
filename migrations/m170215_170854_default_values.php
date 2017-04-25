<?php

use yii\db\Migration;

class m170215_170854_default_values extends Migration
{
    public function up()
    {
        $this->alterColumn('orders', 'active', $this->smallInteger(1)->defaultValue(0));
        $this->alterColumn('orders', 'amount', $this->double()->defaultValue(0.0));
    }

    public function down()
    {
        $this->alterColumn('orders', 'active', $this->integer()->dropDefault());
        $this->alterColumn('orders', 'amount', $this->integer()->dropDefault());

        return false;
    }
}
