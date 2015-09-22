<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use \app\components\Goods;

class UnloadingController extends Controller {

    public function actionIndex() {
        $methods = get_class_methods(__CLASS__);
        $arProfiles = [];
        foreach ($methods as $methodName) {
            if (strpos($methodName, 'profile') === 0) {
                $arProfiles[$methodName] = $this->$methodName('info');
            }
        }

        return $this->render('index', [
                'arProfiles' => $arProfiles
        ]);
    }

    public function actionUnload($profile) {
        if (!method_exists($this, $profile)) {
            exit('уруру');
        }

        $this->$profile();
    }

    private function profileDefault($mode = 'run') {
        if ($mode == 'info') {
            return [
                'name' => 'Базовый',
                'description' => 'Профиль по умолчанию',
            ];
        }

        if ($mode == 'run') {

            $offers = \app\models\Offers::find()->all();
            $arCsv = ['Название;Поставщик;Исходная цена;Посчитанная цена;Количество'];

            $providers = \app\models\Providers::find()->all();
            $arProviders = [];
            foreach ($providers as $value) {
                $arProviders[$value->id] = $value->name;
            }

            foreach ($offers as $offer) {
                $name = \app\components\Goods::getName($offer->goods_id);
                $provider = $arProviders[$offer->providers_id];
                $price = $offer->price;
                $quantity = $offer->quantity;
//                print $name;
                $calcPrice = $price * \app\components\CalculationDiscount::calc($offer->goods_id, $offer->providers_id);
//                $calcPrice =  \app\components\CalculationDiscount::calc($offer);
                $arCsv[] = "$name;$provider;$price;$calcPrice;$quantity";
            }
            $csv = implode(chr(10), $arCsv);

            $fileName = 'prise.csv';

            if (ob_get_level()) {
                ob_end_clean();
            }
            if (ini_get('zlib.output_compression'))
                ini_set('zlib.output_compression', 'Off');
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $fileName);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($csv));

            print iconv('utf-8', 'windows-1251', $csv);
            exit;
        }
    }

    private function profileTyreBitrix($mode = 'run') {
        if ($mode == 'info') {
            return [
                'name' => 'Экспорт шин в Битрикс',
                'description' => 'Экспорт шин в Битрикс',
            ];
        }

        function saveRow($curGoodId, $arPrise, $arQunt, $files) {
            $price = min($arPrise);
            $quant = array_sum($arQunt);
            $arrGood = Goods::getName($curGoodId, 'array');

//            print ('<pre>');print_r($arrGood['values']);print('</pre>');exit('100');
            $name = $arrGood['name'];
            $brend = $arrGood['values']['tyre_brand'];
            $model = $arrGood['values']['tyre_model'];
            $offerRow = "$name;$brend;$model;$quant;$price;RUB";
            saveRowInOffer($offerRow, $files);

            $IP_PROP2 = $arrGood['values']['tyre_heigth'];
            $IP_PROP23 = $arrGood['values']['tyre_width'];
            $IP_PROP21 = $arrGood['values']['tyre_dia'];
            $IP_PROP3 = $arrGood['values']['tyre_i_load'];
            $IP_PROP4 = $arrGood['values']['tyre_i_speed'];
            $IP_PROP13 = $arrGood['values']['tyre_model'];
            $IP_PROP148 = ($arrGood['values']['tyre_rf']) ? 'да' : '';
            $IP_PROP8 = $arrGood['values']['tyre_brand'];
            $IP_PROP22 = $arrGood['values']['tyre_season'];
            $IP_PROP24 = $arrGood['values']['tyre_type_auto'];
            $IP_PROP2 = $arrGood['values']['tyre_model'];
            $IP_PROP26 = ($arrGood['values']['tyre_spike']) ? 'да' : '';
            $IC_GROUP0 = $arrGood['values']['tyre_brand'];
            $IC_GROUP1 = $arrGood['values']['tyre_model'];

            $goodRow = "$name;$IP_PROP2;$IP_PROP23;$IP_PROP21;$IP_PROP3;$IP_PROP4;$IP_PROP13;$IP_PROP148;$IP_PROP8;$IP_PROP22;$IP_PROP24;$IP_PROP2;$IP_PROP26;$IC_GROUP0;$IC_GROUP1";
            saveRowInGoods($goodRow, $files);
        }

        function saveRowInOffer($row, $files) {
//            print "$row <br>";
            
            $filename = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$files[2];
            file_put_contents($filename, $row.chr(10), FILE_APPEND);
        }

        function saveRowInGoods($row, $files) {
            print "$row <br>";
            $filename = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$files[1];
            file_put_contents($filename, $row.chr(10), FILE_APPEND);
        }
        
        $files = [
          1=>'shina1_'.date('Y.m.d.H.i').'.csv', 
          2=>'shina2_'.date('Y.m.d.H.i').'.csv', 
        ];

        $arOffers = \app\models\Offers::find()->groupBy('goods_id')->all();

        $sql = "select * from offers as O INNER JOIN goods as G where O.goods_id = G.id and G.goods_type_type = 'tyre'";
//        print $sql;
        $arOffers = \Yii::$app->db->createCommand($sql)->queryAll();
//        print ('<pre>');print_r($arOffers);print('</pre>');exit('100');

        $curGoodId = 0;
        saveRowInOffer('IE_NAME;IC_GROUP0;IC_GROUP1;CP_QUANTITY;CV_PRICE_1;CV_CURRENCY_1', $files);
        saveRowInGoods('IE_NAME;IP_PROP2;IP_PROP23;IP_PROP21;IP_PROP3;IP_PROP4;IP_PROP13;IP_PROP148;IP_PROP8;IP_PROP22;IP_PROP24;IP_PROP2;IP_PROP26;IC_GROUP0;IC_GROUP1', $files);
        foreach ($arOffers as $arOffer) {
            if ($arOffer['goods_id'] != $curGoodId) {
                if ($curGoodId != 0) {
                    // Сохранить записи в файлах
                    saveRow($curGoodId, $arPrise, $arQunt, $files);
                }
                $arPrise = [];
                $arQunt = [];
            }
            $curGoodId = $arOffer['goods_id'];

            $arQunt[] = $arOffer['quantity'];
            $arPrise[] = \app\components\CalculationDiscount::calc($arOffer['goods_id'], $arOffer['providers_id']) * $arOffer['price'];
        }



//        $minPriseOffers = \app\models\Offers::find()->groupBy('goods_id')->min('price');
//        $arMinPriseOffers = \yii\helpers\ArrayHelper::map($minPriseOffers, 'goods_id', 'price');
//        $sumQuntOffers = \app\models\Offers::find()->groupBy('goods_id')->sum('quantity');
//        $arSumQuntOffers = \yii\helpers\ArrayHelper::map($sumQuntOffers, 'goods_id', 'quantity');
//array(
//    'IE_NAME'=> ,//Имя товара  (#строка с полным наименованием повар#);
//    'IP_PROP2'=> ,//Свойство "Высота профиля" (#строка#); 
//    'IP_PROP23'=> ,//Свойство "Ширина профиля" (#строка#); 
//    'IP_PROP21'=> ,//Свойство "Радиус шины" (#строка#); 
//    'IP_PROP3'=> ,//Свойство "Индекс нагрузки" (#строка#); 
//    'IP_PROP4'=> ,//Свойство "Индекс скорости" (#строка#); 
//    'IP_PROP13'=> ,//Свойство "Модель автошины"; 
//    'IP_PROP148'=> ,//Свойство "Run Flat"; 
//    'IP_PROP8'=> ,//Свойство "Производитель"; 
//    'IP_PROP22'=> ,//Свойство "Сезонность";  № Летние'=> ,//Лето Зимние'=> ,//Зима Всесезонные №
//    'IP_PROP24'=> ,//Свойство "Тип автошины ";  Легкогрузовая, Легковая,
//    'IP_PROP2'=> ,//Свойство "Конструкция автошины"; -?
//    'IP_PROP26'=> ,//Свойство "Шипы";  (#или Да или пусто#)
//    'IC_GROUP0'=> ,//Группа уровня (1);  (Производитель)
//    'IC_GROUP1'=> ,//Группа уровня (2)  (Модель автошины)
//    'Подразделение'=> ,//Подразделение 1 (по умолчанию)
//);
    }

}
