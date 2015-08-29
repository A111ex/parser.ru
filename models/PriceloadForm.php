<?php

namespace app\models;

use Yii;
use yii\base\Model;

class PriceloadForm extends Model {

    public $fileName;

    public function rules() {
        return [
            [['fileName'], 'required'],
//            [['fileName'], 'file',  'extensions' => '.csv'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'fileName' => 'Выберите файл',
        ];
    }

    public function upload($providerId) {
        if ($this->validate()) {
            return $res = $this->fileName->saveAs(Yii::$app->params['uploadFolder'] . $providerId . '.csv');
//            return $res = $this->fileName->saveAs(Yii::$app->params['uploadFolder'] . $this->fileName->baseName . '.' . $this->fileName->extension);
//            print var_dump($res);
//            return true;
        } else {
            return false;
        }
    }

}
