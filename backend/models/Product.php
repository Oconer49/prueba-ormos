<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $description
 * @property string $reference
 * @property int $stock
 * @property string $currency
 * @property float $price
 * @property string|null $imageURL URL de la imagen del producto
 */
class Product extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const CURRENCY_USD = 'USD';
    const CURRENCY_COP = 'COP';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imageURL'], 'default', 'value' => null],
            [['description', 'reference', 'stock', 'currency', 'price'], 'required'],
            [['stock'], 'integer'],
            [['currency'], 'string'],
            [['price'], 'number'],
            [['description'], 'string', 'max' => 248],
            [['reference'], 'string', 'max' => 30],
            [['imageURL'], 'string', 'max' => 255],
            ['currency', 'in', 'range' => array_keys(self::optsCurrency())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'reference' => 'Reference',
            'stock' => 'Stock',
            'currency' => 'Currency',
            'price' => 'Price',
            'imageURL' => 'Image Url',
        ];
    }


    /**
     * column currency ENUM value labels
     * @return string[]
     */
    public static function optsCurrency()
    {
        return [
            self::CURRENCY_USD => 'USD',
            self::CURRENCY_COP => 'COP',
        ];
    }

    /**
     * @return string
     */
    public function displayCurrency()
    {
        return self::optsCurrency()[$this->currency];
    }

    /**
     * @return bool
     */
    public function isCurrencyUsd()
    {
        return $this->currency === self::CURRENCY_USD;
    }

    public function setCurrencyToUsd()
    {
        $this->currency = self::CURRENCY_USD;
    }

    /**
     * @return bool
     */
    public function isCurrencyCop()
    {
        return $this->currency === self::CURRENCY_COP;
    }

    public function setCurrencyToCop()
    {
        $this->currency = self::CURRENCY_COP;
    }
}
