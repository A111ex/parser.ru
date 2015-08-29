<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_type".
 *
 * @property string $type
 * @property string $name
 * @property string $alias
 *
 * @property Goods[] $goods
 * @property GoodsParamsName[] $goodsParamsNames
 */
class GoodsType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'goods_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type'], 'required'],
            [['type'], 'string', 'max' => 45],
            [['type'], 'match', 'pattern' => '/^[a-z0-9][a-z0-9_]{0,}$/', 'message'=>'Можно использовать только маленькие латинские буквы, цифры и символ подчеркивания'],
            [['name'], 'string', 'max' => 30],
            [['alias', 'template_view'], 'string', 'max' => 255],
            [['alias'], 'validateAlias'],
            [['type'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'type' => 'Код типа',
            'name' => 'Название',
            'alias' => 'Алиасы через запятую',
            'template_view' => 'Шаблон вывода названия товара',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods() {
        return $this->hasMany(Goods::className(), ['goods_type_type' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsParamsNames() {
        return $this->hasMany(GoodsParamsName::className(), ['goods_type_type' => 'type']);
    }

    public function validateAlias($attribute, $params) {
        $arAlias = [];
        $arGoodTypes = GoodsType::find()->where("NOT type = :type", [':type'=>$this->type])->all();
        foreach ($arGoodTypes as $value) {
            $arW = explode(',', $value->alias);
            $arAlias = $arAlias + $arW;
        }
        
        $alias = mb_strtolower($this->$attribute, 'UTF-8');
        $arW = explode(',', $alias);
        $arW1 = [];
        foreach ($arW as $value) {
            if(strlen(trim($value))>0){
                $arW1[] = trim($value);
            }
        }
        $arW1 = array_unique($arW1);
        $this->$attribute = implode(',', $arW1);
        
        $arW = explode(',', $this->$attribute);
        $arNotUnique = array_intersect($arAlias, $arW);
        if(count($arNotUnique) > 0){
            $this->addError($attribute, 'Следующие алиасы уже существуют: '.implode(', ', $arNotUnique));
        }
    }

}
