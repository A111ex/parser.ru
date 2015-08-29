<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accords".
 *
 * @property integer $id
 * @property string $identifier
 * @property integer $goods_id
 * @property integer $providers_id
 *
 * @property Goods $goods
 * @property Providers $providers
 */
class Accords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accords';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'providers_id'], 'required'],
            [['goods_id', 'providers_id'], 'integer'],
            [['identifier'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identifier' => 'Название или артикул в прайсе',
            'goods_id' => 'Goods ID',
            'providers_id' => 'Providers ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProviders()
    {
        return $this->hasOne(Providers::className(), ['id' => 'providers_id']);
    }
}
