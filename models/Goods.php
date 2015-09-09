<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $goods_type_type
 *
 * @property GoodsTyre $goodsTyre
 * @property Accords[] $accords
 * @property GoodsType $goodsTypeType
 * @property GoodsTDiscs $goodsTDiscs
 * @property GoodsTTyre $goodsTTyre
 * @property Offers[] $offers
 */
class Goods extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['goods_type_type'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['goods_type_type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'goods_type_type' => 'Goods Type Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsTyre() {
        return $this->hasOne(GoodsTyre::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccords() {
        return $this->hasMany(Accords::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsTypeType() {
        return $this->hasOne(GoodsType::className(), ['type' => 'goods_type_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsT() {
        \app\models\GoodsT::$tabName = 'goods_t_' . $this->goods_type_type;
        return \app\models\GoodsT::findOne($this->id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers() {
        return $this->hasMany(Offers::className(), ['goods_id' => 'id']);
    }

}
