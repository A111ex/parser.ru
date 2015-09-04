<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discounts".
 *
 * @property integer $id
 * @property integer $providers_id
 * @property string $goods_type_type
 * @property string $params
 * @property string $values
 * @property double $coef
 *
 * @property Providers $providers
 * @property GoodsType $goodsTypeType
 */
class Discounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['providers_id', 'goods_type_type'], 'required'],
            [['providers_id'], 'integer'],
            [['coef'], 'number'],
            [['goods_type_type'], 'string', 'max' => 45],
            [['params', 'values'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'providers_id' => 'Поставщик',
            'goods_type_type' => 'Тип товара',
            'params' => 'Название параметров товара',
            'values' => 'Значения параметров',
            'coef' => 'Козффициент',
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
    public function getGoodsTypeType()
    {
        return $this->hasOne(GoodsType::className(), ['type' => 'goods_type_type']);
    }
}
