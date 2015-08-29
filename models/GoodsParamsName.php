<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_params_name".
 *
 * @property string $id
 * @property string $name
 * @property string $data_type
 * @property integer $required
 * @property string $parent_param
 * @property integer $sort
 * @property string $goods_type_type
 *
 * @property GoodsParams[] $goodsParams
 * @property GoodsType $goodsTypeType
 */
class GoodsParamsName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_params_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'data_type', 'goods_type_type'], 'required'],
            [['required', 'sort'], 'integer'],
            [['id', 'parent_param'], 'string', 'max' => 50],
            [['id'], 'match', 'pattern' => '/^[a-z0-9][a-z0-9_]{0,}$/', 'message'=>'Можно использовать только маленькие латинские буквы, цифры и символ подчеркивания'],
            [['name'], 'string', 'max' => 100],
            [['data_type'], 'string', 'max' => 12],
            [['goods_type_type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код параметра',
            'name' => 'Название параметра',
            'data_type' => 'Тип данных параметра',
            'required' => 'Обязательное ли поле',
            'parent_param' => 'Родительский параметр',
            'sort' => 'Сортировка',
            'goods_type_type' => 'Goods Type Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsParams()
    {
        return $this->hasMany(GoodsParams::className(), ['goods_params_name_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsTypeType()
    {
        return $this->hasOne(GoodsType::className(), ['type' => 'goods_type_type']);
    }
}
