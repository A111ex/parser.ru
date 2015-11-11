<?php

namespace app\components;

use Yii;
use yii\base\Object;
use app\models\Accords;

class FindAccords extends Object {

    private $filename;
    private $providerId;
    private $isExistNormalazeFile;
    private $arrAccords;
    private $arAlias;
    private $arGoodTypes;

    public function __construct($providerId, $filename, $arrAccords) {
        $this->filename = $filename;
        $this->providerId = $providerId;
        $this->arrAccords = $arrAccords;
        foreach ($this->arrAccords as $k => $val) {
            if ("$val" == '0')
                unset($this->arrAccords[$k]);
        }
        $this->arrAccords = array_flip($this->arrAccords);
        return parent::__construct();
    }

    public function run() {

        // Ключ кеша
        $key = $this->providerId . '_' . $this->filename . '_' . filectime(Yii::$app->params['uploadFolder'] . $this->filename);
        if (Yii::$app->cache->exists($key)) {
            $arRes = Yii::$app->cache->get($key);
        } else {
            // Удалить старые предложения поставщика
            \app\models\Offers::deleteAll('providers_id = :providers_id', [':providers_id' => $this->providerId]);
            
            // Сохранить дату последней загрузки прайса
            $provider = \app\models\Providers::findOne($this->providerId);
            $provider->date_last_down = time();
            $provider->save();
            
            // Получить файл
            $arrCSV = file(Yii::$app->params['uploadFolder'] . $this->filename);

            // Обработка каждой строки файла
            $arrToAccord = [];
            foreach ($arrCSV as $k => $strCSV) {
                // Строку в массив
                $arStrCSV = str_getcsv($strCSV, ';');
                // Пропустить неполные строки
                if (strlen(trim($arStrCSV[$this->arrAccords['name']])) == 0 || strlen(trim($arStrCSV[$this->arrAccords['price']])) == 0 || strlen(trim($arStrCSV[$this->arrAccords['quantity']])) == 0){
                    \Yii::error($strCSV, 'parser_log');
                    \Yii::error($this->arrAccords, 'parser_log');
                    \Yii::error($arStrCSV, 'parser_log');
                    \Yii::error('================', 'parser_log');
                    continue;
                }
                // Нормализовать массив строки от текущего поставщика
                $arResRow = $this->normalizeStrOfPrice($arStrCSV);
                // Получить тип товара
                $arResRow = $this->getGoodType($arResRow);
                if (isset($this->arrAccords['identifier'])) { // Задан артикул
                    $res = $this->getAccordsByIdentifier($arResRow);
                } else { // Не задан артикул
                    $res = $this->getAccordsByName($arResRow);
                }
                if ($res instanceof Accords) { // Есть соответствие
                    // Пишем в таблицу предложений
                    $offer = new \app\models\Offers();
                    $offer->quantity = $arResRow['quantity'];
                    $offer->price = $arResRow['price'];
                    $offer->goods_id = $res->goods_id;
                    $offer->providers_id = $res->providers_id;
                    $offer->save();
                } else { // Нет соответствия
                    // Вывести на согласование
                    $arResRow['k'] = $k;
                    $arrToAccord[$k] = $arResRow;
                }
//                if ($k > 10)
//                    break;
//            break;
            }
            // Сохранить полученные данные в кеш
            $arRes = [
                'arrToAccord' => $arrToAccord,
                'arGoodTypes' => $this->arGoodTypes,
                'providerId' => $this->providerId,
                'key' => $key,
            ];
            Yii::$app->cache->set($key, $arRes);
        }
        return $arRes;
    }

    private function normalizeStrOfPrice($arStrCSV) {
        // Проверить существование файла нормализации для текущего поставщика (с установкой)
        if (!isset($this->isExistNormalazeFile)) {
            @include_once __DIR__ . '/NormalazePrice/' . $this->providerId . '.php';
            $this->isExistNormalazeFile = function_exists('normalizeStrOfPrice');
            if (!$this->isExistNormalazeFile) {
                include_once __DIR__ . '/NormalazePrice/default.php';
            }
        }
        $arRes = normalizeStrOfPrice($arStrCSV, $this->arrAccords);
        if (isset($this->arrAccords['identifier'])){
            $arRes['identifier'] = $arRes['identifier']. '_' . $arRes['name']; // Это на случай, если у поставщика при обновлении базы артикул будет прислоен другой товар (смешно, конечно, но заказчик сказал, что такое может быть), поэтому добавляем уникальности
        }else{
            unset($arRes['identifier']); // На случай, если в файле нормализации задается поле артикула, а при выборе соответствий полей поле артикула не выбрано - тогда идентификатором будет выступать имя товара
        }
        return $arRes;
    }

    private function getGoodType($arRes) {
        // Получить список всех алиасов
        if (!isset($this->arAlias)) {
            $this->arAlias = [];
            $arGoodTypes = \app\models\GoodsType::find()->all();

            foreach ($arGoodTypes as $gt) {
                $this->arGoodTypes[$gt->type] = $gt->name;
                $arW = explode(',', $gt->alias);
                foreach ($arW as $value) {
                    $this->arAlias[$value] = $gt->type;
                }
            }
        }
        // Привести к нижнему регистру название
        $name = mb_strtolower($arRes['name'], 'UTF-8');
        // Получить код типа товара исходя из найтенного алиаса
        $arRes['goodType'] = '';
        foreach ($this->arAlias as $alias => $goodType) {
            if (mb_strpos($name, $alias) !== false) {
                $arRes['goodType'] = $goodType;
                break;
            }
        }
        return $arRes;
    }

    private function getAccordsByIdentifier($arResRow) {
        return Accords::find()->where('identifier = :identifier AND providers_id = :providers_id', [':identifier' => $arResRow['identifier'], ':providers_id' => $this->providerId])->one();
    }

    private function getAccordsByName($arResRow) {
        return Accords::find()->where('identifier = :identifier AND providers_id = :providers_id', [':identifier' => $arResRow['name'], ':providers_id' => $this->providerId])->one();
    }

}
