<?php

use yii\db\Migration;

class m180220_074656_cards extends Migration
{
    public function up()
    {
        $this->execute(<<<SQL
            CREATE TABLE `card` (
                  `id` char(36) NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `description` text NOT NULL,
                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `is_deleted` tinyint(1) DEFAULT '0',
                  PRIMARY KEY (`id`)
            );
SQL
);
    }

    public function down()
    {
        echo "m180220_074656_cards cannot be reverted.\n";

        return false;
    }
}
