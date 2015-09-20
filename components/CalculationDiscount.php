<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use Yii;
use yii\base\Object;

class CalculationDiscount extends Object {

    private static $arDisconts;

    public static function calc($goods_id, $providers_id) {
        //Получить список наценок
        if (!isset(self::$arDisconts)) {
            $arODisconts = \app\models\Discounts::find()->asArray()->all();

            self::$arDisconts = [];
            foreach ($arODisconts as $arDiscont) {
                $arP = explode(';', $arDiscont['params']);
                $arV = explode(';', $arDiscont['values']);
                $arW = [];
                foreach ($arP as $key => $value) {
                    if ($value)
                        $arW[$value] = $arV[$key];
                }
                $arDiscont['arPV'] = $arW;
                self::$arDisconts[$arDiscont['providers_id']][$arDiscont['goods_type_type']][] = $arDiscont;
            }
        }

        //Получить свойства товара 
        $good = \app\models\Goods::findOne($goods_id);
        $goodT = \yii\helpers\ArrayHelper::toArray($good->getGoodsT());
        //Для каждой наценки проверить - подходит ли товар под нее
        $dds = self::$arDisconts[$providers_id][$good->goods_type_type];
        $k = 1;
        $arK = [];
        if (!is_array($dds))
            return 1;
        foreach ($dds as $arDiscount) {
            if (count(array_intersect_assoc($arDiscount['arPV'], $goodT)) == count($arDiscount['arPV'])) {
                $k = $k * $arDiscount['coef'];
                $arK[] = $arDiscount['coef'];
            }
        }
        return ($k > 1) ? $k : 1;
    }

    //put your code here
}
