<?php

use yii\db\Migration;

class m250323_223524_seed_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $this->insert(
                'product',
                [
                    'description' => $faker->sentence(6),
                    'reference' => substr($faker->catchPhrase, 0, 30),
                    'stock' => $faker->numberBetween(1, 100),
                    'currency' => $faker->randomElement(['USD', 'COP']),
                    'price' => $faker->randomFloat(2, 10, 1000),
                ]
            );
        }
    }   

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250323_223524_seed_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250323_223524_seed_product_table cannot be reverted.\n";

        return false;
    }
    */
}
