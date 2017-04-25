<?php

use yii\db\Migration;

/**
 * Handles the creation for table `blocks`.
 */
class m170424_122902_create_blocks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('blocks', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'active' => $this->integer(),
            'sort' => $this->integer(),
            'slug' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('blocks');
    }
}
