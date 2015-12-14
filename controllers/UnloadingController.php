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

        $arrPriceType = \yii\helpers\ArrayHelper::map(\app\models\PriceType::find()->all(), 'id', 'name');
        $arrPriceType = ['' => ' - Не выбран тип цены - '] + $arrPriceType;

        return $this->render('index', [
                'arProfiles' => $arProfiles,
                'arrPriceType' => $arrPriceType,
        ]);
    }

    public function actionUnload($profile, $priceType) {
        if (!method_exists($this, $profile)) {
            exit('уруру');
        }

        $this->$profile('run', $priceType);
    }

    /*
     * Вспомогательный метод - отдает браузеру на выгрузку файл с именем $fileName и содержимым $body
     */

    private function _unloadStr($fileName, $body) {

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
        header('Content-Length: ' . strlen($body));

        print $body;
        exit;
    }

    private function profileDefault($mode = 'run', $priceType = null) {
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
                $calcPrice = $price * \app\components\CalculationDiscount::calc($offer->goods_id, $offer->providers_id, $priceType);
//                $calcPrice =  \app\components\CalculationDiscount::calc($offer);
                $arCsv[] = "$name;$provider;$price;$calcPrice;$quantity";
            }
            $csv = implode(chr(10), $arCsv);

            $this->_unloadStr('prise.csv', iconv('utf-8', 'windows-1251', $csv));
        }
    }

    public function actionTyreBitrix($priceType) {
        $this->profileTyreBitrix('run', $priceType);
    }

    private function profileTyreBitrix($mode = 'run', $priceType = null) {
        if ($mode == 'info') {
            return [
                'name' => 'Экспорт шин в Битрикс',
                'description' => 'Экспорт шин в Битрикс',
            ];
        }
        if ($mode == 'run') {

            function saveRow($curGoodId, $arOffers, &$arStrToSave) {
                $arrGood = Goods::getName($curGoodId, 'array');

                $arRow['good']['id'] = $curGoodId;
                $arRow['good']['heigth'] = $arrGood['values']['tyre_heigth'];
                $arRow['good']['width'] = $arrGood['values']['tyre_width'];
                $arRow['good']['dia'] = $arrGood['values']['tyre_dia'];
                $arRow['good']['i_load'] = $arrGood['values']['tyre_i_load'];
                $arRow['good']['i_speed'] = $arrGood['values']['tyre_i_speed'];
                $arRow['good']['model'] = $arrGood['values']['tyre_model'];
                $arRow['good']['rf'] = ($arrGood['values']['tyre_rf']) ? 'да' : '';
                $arRow['good']['brand'] = $arrGood['values']['tyre_brand'];
                $arRow['good']['season'] = $arrGood['values']['tyre_season'];
                $arRow['good']['type_auto'] = $arrGood['values']['tyre_type_auto'];
                $arRow['good']['spike'] = ($arrGood['values']['tyre_spike']) ? 'да' : '';

                $arRow['offers'] = $arOffers;

                $arStrToSave[] = serialize($arRow);
            }

            function saveOffer($arOffer, &$arStrToSave) {

                $curGoodId = $arOffer['goods_id'];

                if (!isset($arStrToSave[$curGoodId])) {
                    $arrGood = Goods::getName($curGoodId, 'array');

                    $arRow['id'] = $curGoodId;
                    $arRow['goods_type'] = $arOffer['goods_type_type'];
                    $arRow['name'] = Goods::getName($curGoodId);
                    $arRow['heigth'] = $arrGood['values']['tyre_heigth'];
                    $arRow['width'] = $arrGood['values']['tyre_width'];
                    $arRow['dia'] = $arrGood['values']['tyre_dia'];
                    $arRow['i_load'] = $arrGood['values']['tyre_i_load'];
                    $arRow['i_speed'] = $arrGood['values']['tyre_i_speed'];
                    $arRow['model'] = $arrGood['values']['tyre_model'];
                    $arRow['rf'] = ($arrGood['values']['tyre_rf']) ? 44 : '';
                    $arRow['brand'] = $arrGood['values']['tyre_brand'];
                    $arRow['season'] = $arrGood['values']['tyre_season'];
                    $arRow['type_auto'] = $arrGood['values']['tyre_type_auto'];
                    $arRow['spike'] = ($arrGood['values']['tyre_spike']) ? 3 : '';

                    $arStrToSave[$curGoodId]['g'] = $arRow;
                }

                unset($arOffer['name'], $arOffer['goods_type_type']);

                $arStrToSave[$curGoodId]['o'][$arOffer['providers_id']] = $arOffer;
            }

            function clearFiles($file) {
                file_put_contents($file, '');
            }

            function saveFiles($file, $arStrToSave) {
                return;
                foreach ($arStrToSave as $gId => $arGO) {
                    $arStrToSave[$gId] = json_encode($arGO);
                }
                file_put_contents($file, implode(chr(10), $arStrToSave), FILE_APPEND);
            }

            $folder_local = $_SERVER['DOCUMENT_ROOT'] . '/unload/';
            $file_name = 'tyre_bitrix.txt';
            $file = $folder_local . $file_name;

            clearFiles($file); // Очистить файл

            $arStrToSave = [];

            // Список поставщиков
            $arProviders = \yii\helpers\ArrayHelper::map(\app\models\Providers::find()->all(), 'id', 'name');

            // Список оферов шин
            $sql = "select * from offers as O INNER JOIN goods as G where O.goods_id = G.id and G.goods_type_type = 'tyre'";
            $arOffers = \Yii::$app->db->createCommand($sql)->queryAll();
//            exit();
            $curGoodId = 0;
            foreach ($arOffers as $k => $arOffer) {
                if ($k % 10 == 0){ // Сохранить на диск
                    saveFiles($file, $arStrToSave);
                    $arStrToSave = [];
                }
            }
            if(count($arStrToSave)>0){
                saveFiles($file, $arStrToSave);
            }



            // передать на сайт

            $folder_remout = '/home/s/shina93/shincenter/public_html/upload/';
            copy($file, $folder_remout . $file_name);

//            $host = 'brevitas.timeweb.ru';
//            $user = 'shina93';
//            $password = '64bc9f4f36';
//            $this->sendTrouthFTP($host, $user, $password, $folder_local, $file_name, $folder_remout);
        }
    }

    private function sendTrouthFTP($host, $user, $password, $folder_local, $file_name, $folder_remout) {

// Соединение с удаленным FTP-сервером
        print "Соединение с сервером $host<br>";
        $connect = ftp_connect($host);
        if (!$connect) {
            echo("Ошибка соединения");
            exit;
        } else {
            echo("Соединение установлено<br><br>");
        }


// Регистрация на FTP-сервере
        print "Регистрация на FTP-сервере<br>Логин $user<br>Пароль $password<br>";
        $result = ftp_login($connect, $user, $password);
        if (!$result) {
            echo("Не залогинились");
            exit;
        } else {
            echo("Залогинились<br><br>");
        }
        ftp_pasv($connect, true);


//  $new_dir = "home/i/itwebnetru/rusneon/public_html";
// сохраняем имя текущего рабочего каталога
        print $current_dir = ftp_pwd($connect);

//        $folder_remout = "/rusneon/public_html/";
//        $folder_remout = $current_dir;
// Копируем файл
//        $folder_local = $_SERVER['DOCUMENT_ROOT'] . "/";
//        $file_name = "200812111155_1ddbf8a5.tar.gz";


        $rrr = ftp_put($connect, $folder_local . $file_name, $folder_remout . $file_name, FTP_BINARY);
        print "Копирование файла $file_name<br>";
        if (!$rrr) {
            echo("Завершилось неудачей (((");
        } else {
            echo("Удачно скопировано )))");
        }


// Закрываем соединение
        ftp_quit($connect);
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['tyre-bitrix'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

}
