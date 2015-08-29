<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_t_tyre".
 *
 * @property integer $goods_id
 * @property string $name
 * @property string $discription
 * @property string $photo
 *
 * @property Goods $goods
 */
class GoodsT extends \yii\db\ActiveRecord {

    public static $tabName;

    function __construct($type, $config = array()) {
        self::$tabName = $type;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function setGoodsType($type) {
        self::$tabName = $type;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'goods_t_tyre';
        print ('<pre>');print_r(self::$tabName);print('</pre>');exit('100');
        return self::$tabName;
//        return $this->tabName;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['goods_id'], 'required'],
            [['goods_id'], 'integer'],
            [['discription'], 'string'],
            [['name'], 'string', 'max' => 150],
            [['photo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'goods_id' => 'Goods ID',
            'name' => 'Название',
            'discription' => 'Описание',
            'photo' => 'Фото',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods() {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

}
