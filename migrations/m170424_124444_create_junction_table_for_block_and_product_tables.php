<?php

use yii\db\Migration;

/**
 * Handles the creation for table `block_product`.
 * Has foreign keys to the tables:
 *
 * - `block`
 * - `product`
 */
class m170424_124444_create_junction_table_for_block_and_product_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('block_product', [
            'id' => $this->primaryKey(),
            'block_id' => $this->integer(),
            'product_id' => $this->integer(),
            'sort' => $this->integer(),
            'active' => $this->integer(),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('block_product');
    }
}
