<?php

use yii\db\Migration;

class m170111_222806_update_products_visible extends Migration
{
    public function up()
    {
		$this->update('products', ['visible' => 0]);
		$this->execute('
			UPDATE `products` `p` 
			RIGHT JOIN `store_sub_categories` `ssc` ON `ssc`.`store_id` = `p`.`store_id`
			SET `p`.`visible` = 1
			WHERE `p`.`category_type_id` = `ssc`.`sub_categorie_id`
		');
    }

    public function down()
    {
        echo "m170111_222806_update_products_visible cannot be reverted.\n";

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
