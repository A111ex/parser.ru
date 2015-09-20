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

    private function profileBitrix($mode = 'run') {
        if ($mode == 'info') {
            return [
                'name' => 'Экспорт в Битрикс',
                'description' => 'Экспорт в Битрикс',
            ];
        }
    }

}
