<?php

namespace app\components;

use yii\base\Object;

class Goods extends Object {

    private static $goodTablePefix = 'goods_t_';

    public static function requiredParameters($p, $goodTypeId) {
        // Получить отсортированный список параметров для выбранного типа товара
        $arGoodsParamsNames = self::getGoodTypeParams($goodTypeId);
        $arParams = [];
        foreach ($arGoodsParamsNames as $oParam) {
            $arParams[] = $p[$oParam->id];
        }
        return implode('_', $arParams);
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public static function getName($id, $mode = 'name') {
        $good = \app\models\Goods::findOne($id);
        \app\models\GoodsT::$tabName = self::$goodTablePefix . $good->goods_type_type;
        $goodT = \app\models\GoodsT::findOne($id);
//        $arGoodsParamsNames = self::getGoodTypeParams($good->goods_type_type);
        $goodType = \app\models\GoodsType::findOne($good->goods_type_type);
        $arGoodsParamsNames = $goodType->getGoodsParamsNames()->orderBy('sort')->all();

        $arParams = ['goodType' => $goodType->name];
        foreach ($arGoodsParamsNames as $oParam) {
            $idParamVal = $goodT->{$oParam->id};
            $oParamValue = \app\models\GoodsParams::findOne($idParamVal);
            $arParams[$oParam->id] = ($oParamValue->public_value) ? (($oParamValue->public_value == \Yii::$app->params['emptyStringParam']) ? '' : $oParamValue->public_value) : $oParamValue->value;
        }
        $tpl = $goodType->template_view;
        if ($mode == 'array') {
            return [
                'name' => self::fullName($arParams, $tpl),
                'goodTipe' => $goodType,
                'good' => $good,
                'params' => $arGoodsParamsNames,
                'values' => $arParams,
            ];
        }
        return self::fullName($arParams, $tpl);
    }

    /**
     * 
     * @param type $p
     * @param type $tpl
     * @return type
     */
    public static function fullName($p, $tpl = '') {
        $tpl = trim($tpl);
        $arKeys = [];
        $arVals = [];
        foreach ($p as $k => $v) {
            $arKeys[$k] = '{' . $k . '}';
            $arVals[$k] = ($v == \Yii::$app->params['emptyStringParam']) ? '' : $v;
        }
        if (strlen($tpl) == 0) {
            $tpl = implode(' ', $arKeys);
        }
        return trim(preg_replace('/[ ]{2,}/', ' ', str_replace($arKeys, $arVals, $tpl)));
    }

    /**
     * 
     * @param type $goodTypeId
     * @param type $params
     */
    public static function save($goodTypeId, $params) {
        // Создать запись в таблице goods
        $oGood = new \app\models\Goods();
        $oGood->name = self::requiredParameters($params, $goodTypeId);
        $oGood->goods_type_type = $goodTypeId;
        $oGood->save();
        $goodId = $oGood->id;

        // Создать запись в таблице goods_t_
        $tableName = self::$goodTablePefix . $goodTypeId;
        $oGootT = new \app\models\GoodsT([], $tableName);
        $oGootT->goods_id = $goodId;
        $arGoodsParamsNames = self::getGoodTypeParams($goodTypeId);
        foreach ($arGoodsParamsNames as $oParam) {
            $paramCode = $oParam->id;
            $oGootT->$paramCode = $params[$oParam->id];
        }
        $oGootT->save();
        return $goodId;
    }

    public static function find($type, $cond, $params) {
        \app\models\GoodsT::$tabName = self::$goodTablePefix . $type;
        return \app\models\GoodsT::find()->where($cond, $params);
    }

    public static function getGoodTypeParams($goodTypeId) {
        $goodType = \app\models\GoodsType::findOne($goodTypeId);
        return $goodType->getGoodsParamsNames()->orderBy('sort')->all();
    }

}
