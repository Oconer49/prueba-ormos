<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m250323_222640_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(248)->notNull(),
            'reference' => $this->string(30)->notNull(),
            'stock' => $this->integer()->notNull(),
            'currency' => "ENUM('USD', 'COP') NOT NULL",
            'price' => $this->decimal(10, 2)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
