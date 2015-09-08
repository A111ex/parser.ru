<?php

namespace app\models;

use Yii;
use yii\validators;
use yii\validators\Validator;

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
class Discounts extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'discounts';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['providers_id', 'goods_type_type', 'coef'], 'required'],
            [['providers_id'], 'integer'],
            [['coef'], 'number'],
            [['goods_type_type'], 'string', 'max' => 45],
            [['params', 'values'], 'string', 'max' => 255],
//            ['values', 'trim'],
            ['goods_type_type', 'compositeUnique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
    public function getProviders() {
        return $this->hasOne(Providers::className(), ['id' => 'providers_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsTypeType() {
        return $this->hasOne(GoodsType::className(), ['type' => 'goods_type_type']);
    }

    public function trim($attribute, $params) {
        $this->$attribute = trim('' . $this->$attribute);
    }

    public function compositeUnique($attribute, $params) {
//        print ('<pre>');
//        print_r(11);
//        print('</pre>');
//        exit('100');

        $ar = [
            ':providers_id' => $this->providers_id,
            ':values' => $this->values,
            ':goods_type_type' => $this->goods_type_type
        ];

        $valid = Discounts::find()->where('providers_id=:providers_id and goods_type_type=:goods_type_type and `values`=:values', $ar)->one();
        if ($valid instanceof Discounts) {
            $this->addError($attribute, 'Такая скидка уже задана');
        }
    }

}
