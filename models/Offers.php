<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "offers".
 *
 * @property integer $id
 * @property integer $quantity
 * @property double $fix_price
 * @property integer $providers_id
 * @property integer $goods_id
 * @property double $price
 *
 * @property Providers $providers
 * @property Goods $goods
 */
class Offers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity', 'providers_id', 'goods_id'], 'integer'],
            [['fix_price', 'price'], 'number'],
            [['providers_id', 'goods_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantity' => 'Кол-во',
            'fix_price' => 'Фиксированная цена',
            'providers_id' => 'Providers ID',
            'goods_id' => 'Goods ID',
            'price' => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProviders()
    {
        return $this->hasOne(Providers::className(), ['id' => 'providers_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
