<?php

use yii\db\Migration;

class m250326_014006_update_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Agregar la columna imageURL a la tabla product
        $this->addColumn('product', 'imageURL', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar la columna imageURL si se revierte la migración
        $this->dropColumn('product', 'imageURL');
    }
}
