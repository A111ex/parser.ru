<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_params".
 *
 * @property integer $id
 * @property string $value
 * @property string $public_value
 * @property integer $sort
 * @property integer $link_category
 * @property string $goods_params_name_id
 *
 * @property GoodsParamsName $goodsParamsName
 */
class GoodsParams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'link_category'], 'integer'],
            [['link_category', 'goods_params_name_id'], 'required'],
            [['value', 'public_value'], 'string', 'max' => 100],
            [['goods_params_name_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Значение',
            'public_value' => 'Отображаемое значение',
            'sort' => 'Сортировка',
            'link_category' => 'Привязано к',
            'goods_params_name_id' => 'Goods Params Name ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsParamsName()
    {
        return $this->hasOne(GoodsParamsName::className(), ['id' => 'goods_params_name_id']);
    }
}
