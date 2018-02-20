<?php

use yii\db\Migration;

class m180220_081000_card_view_count extends Migration
{
    public function up()
    {
        $this->execute(
            <<<SQL
                CREATE TABLE `card_view_count`  (
                  `card_id` char(36) NOT NULL,
                  `value` bigint(0) UNSIGNED NOT NULL DEFAULT 0,
                  PRIMARY KEY (`card_id`),
                  CONSTRAINT `fk_card_card_view_count` 
                    FOREIGN KEY (`card_id`) REFERENCES `card` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
                );
SQL
        );
    }

    public function down()
    {
        echo "m180220_081000_card_view_count cannot be reverted.\n";

        return false;
    }
}
