<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "providers".
 *
 * @property integer $id
 * @property string $meta
 * @property string $name
 * @property string $id_script
 * @property integer $date_last_down
 *
 * @property Accords[] $accords
 * @property Offers[] $offers
 */
class Providers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'providers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meta'], 'string'],
            [['date_last_down'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['id_script'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meta' => 'Meta',
            'name' => 'Имя поставщика',
            'id_script' => 'Код скрипта',
            'date_last_down' => 'Дата обновления прайса',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccords()
    {
        return $this->hasMany(Accords::className(), ['providers_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::className(), ['providers_id' => 'id']);
    }
}
