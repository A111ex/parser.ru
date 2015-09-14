<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\PriceloadForm;
use yii\web\UploadedFile;
use app\models\providers;
use app\components\ParserPrice;
use app\components\FindAccords;

/**
 * PriceloadController implements the CRUD actions for providers model.
 */
class PriceloadController extends Controller {

    /**
     * Lists all providers models.
     * @return mixed
     */
    public function actionIndex($providerId) {
        $model = new PriceloadForm();
        if (Yii::$app->request->isPost) {
            $model->fileName = UploadedFile::getInstance($model, 'fileName');
            if ($model->upload($providerId)) {
                // file is uploaded successfully
                $this->redirect($this->id . '/ch-accords-fild?providerId=' . $providerId);
                return;
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    /**
     * Страница проверки соответсвий полей
     * @return mixed
     */
    public function actionChAccordsFild($providerId) {

        $filename = $providerId . '.csv';

        if (Yii::$app->request->isPost) {
            $arAccords = Yii::$app->request->post()['accords'];
            $diff = array_diff(['name', 'price', 'quantity'], $arAccords);
            $dualPosition = array_count_values($arAccords);
            unset($dualPosition[0]);
            $err2 = array();
            foreach ($dualPosition as $key => $value) {
                if ($value !== 1) {
                    $err2[$key] = $value;
                }
            }
            // Прверить правильность заполенния полей
            if (count($diff) == 0 && count($err2) == 0) {
                // Сохранить соответствия полей в запись поставщика - TODO
                $provider = providers::findOne($providerId);
                $metaProv = ($provider->meta) ? unserialize($provider->meta) : [];
                $metaProv['accords'] = $arAccords;
                $provider->meta = serialize($metaProv);
                $provider->update();
                $this->redirect('/' . $this->id . '/ok-collizion?providerId=' . $providerId);
            }
        } else {
            if (($provider = providers::findOne($providerId)) !== null) {
                $metaProv = ($provider->meta) ? unserialize($provider->meta) : [];
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $arAccords = (is_array($metaProv['accords'])) ? $metaProv['accords'] : [];
        }

        $row = 1;
        $rows = [];
        if (($handle = fopen(Yii::$app->params['uploadFolder'] . $filename, "r")) !== FALSE) {
            $cnt = 0;
            while (($data = fgetcsv($handle, 2000, ';')) !== FALSE) {
                $rows[] = $data;
                if ($cnt++ > 20) {
                    break;
                }
            }
            fclose($handle);
        }
        $arFilds = ['0' => ' - Выбрать - ', 'identifier' => 'Артикул', 'name' => 'Наименование', 'price' => 'Цена', 'quantity' => 'Кол-во'];
        // return $this->render('index', ['model' => $model]);
        return $this->render('chAccordsFild', ['rows' => $rows, 'arFilds' => $arFilds, 'arErr' => $diff, 'arAccords' => $arAccords, 'err2' => $err2]);
    }

    /**
     * Страница с ненайденными соответствиям наименований
     * @return mixed
     */
    public function actionOkCollizion($providerId) {

        $filename = $providerId . '.csv';

        // Проверить, существует ли поставщик
        if (($provider = providers::findOne($providerId)) !== null) {
            // если есть - получить таблицу соотвертсвий полей прайса
            $metaProv = ($provider->meta) ? unserialize($provider->meta) : [];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $session = Yii::$app->session;
        $session->open();
        $session['providerId'] = $providerId;
        $session['priceFile'] = $filename;
        $session['priceFile'] = $filename;



        // Запустить парсер
        $FindAccords = new FindAccords($providerId, $filename, $metaProv['accords']);
        $arRes = $FindAccords->run();

        $arRes['arGoodTypes'] = ['' => ' - - '] + $arRes['arGoodTypes'];


        return $this->render('okCollizion', [
                    'arRes' => $arRes,
                    'providerId' => $providerId,
        ]);
    }

    /**
     * Страница с ненайденными соответствиям наименований
     * @return mixed
     */
    public function actionGetItems($count, $last) {
        $session = Yii::$app->session;
        $session->open();
        $providerId = $session['providerId'];
        $filename = $session['priceFile'];

        // Проверить, существует ли поставщик
        if (($provider = providers::findOne($providerId)) !== null) {
            // если есть - получить таблицу соотвертсвий полей прайса
            $metaProv = ($provider->meta) ? unserialize($provider->meta) : [];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // Запустить парсер
        $FindAccords = new FindAccords($providerId, $filename, $metaProv['accords']);
        $arRes = $FindAccords->run();
//        print ('<pre>');print_r($arRes);print('</pre>');exit('100');
        $cnt = 0;
        $arItems = [];
        foreach ($arRes['arrToAccord'] as $row) {
            if (++$cnt <= $last)
                continue;
            $arItems[$cnt] = $row;
            if ($cnt >= $count + $last)
                break;
        }
        print json_encode($arItems);
    }

    /**
     * Форма выбора параметров для типа товара $goodType
     * @return mixed
     */
    public function actionGetParamsForm($goodTypeId, $k) {
        // Получить отсортированный список параметров для выбранного типа товара
        $goodType = \app\models\GoodsType::findOne($goodTypeId);
        $arGoodsParamsNames = $goodType->getGoodsParamsNames()->orderBy('sort')->all();
        $arParams = [];
        foreach ($arGoodsParamsNames as $oParam) {
            $arParams[$oParam->id]['name'] = $oParam->name;
            if ($oParam->required) {
                $arParams[$oParam->id]['required'] = 1;
            }
            if ($oParam->parent_param) {
                $arParams[$oParam->id]['parent'] = $oParam->parent_param;
            } else {
                $arGoodsParams = $oParam->getGoodsParams()->orderBy('sort')->all();
                // Для каждого параметра получить отсортированный список значений, кроме зависимых
                foreach ($arGoodsParams as $oParamV) {
                    $arParams[$oParam->id]['select'][$oParamV->id] = $oParamV->value;
                }
            }
        }
        $arRes = ['k' => $k, 'params' => $arParams];
        print json_encode($arRes);
    }

    /**
     * Список значений параметра зависимого от $parentParam
     * @return mixed
     */
    public function actionGetChildParams($k, $paramId, $parentParamVal) {
        $GoodsParams = \app\models\GoodsParams::find()->where('link_category=:parentId', [':parentId' => $parentParamVal])->orderBy('sort')->all();
        $arParams = [];
        foreach ($GoodsParams as $oParam) {
            $arParams[$oParam->id] = $oParam->value;
        }
        $arRes = ['k' => $k, 'paramId' => $paramId, 'params' => $arParams];
        print json_encode($arRes);
    }

    public function actionIgnoreOnce($k) {
        $post = $_POST;

        // Удалить строку из кеша
        $cach = Yii::$app->cache->get($post['key']);
        unset($cach['arrToAccord'][$k]);
        Yii::$app->cache->set($post['key'], $cach);

        //Вренуть результат
        print json_encode(['res' => 'ok', 'k' => $k]);
    }

    public function actionSaveFull($k, $saveAccord = 'y') {
        $post = $_POST;
        $goodTypeId = $post['type'];
        $cach = Yii::$app->cache->get($post['key']);
        $priceArr = $cach['arrToAccord'][$k];
        //Поиск в таблице Goods
        $code = \app\components\Goods::requiredParameters($post, $goodTypeId);
        $goods = \app\models\Goods::find()->where(['name' => $code])->one();
        if ($goods instanceof \app\models\Goods) {
            //Если нашли возвращаем id товара
            $goodId = $goods->id;
        } else {
            //Если не нашли записываем в Goods и возвращаем id товара
            $goodId = \app\components\Goods::save($goodTypeId, $post);
        }


        //Добавить запись в таблицу Accords
        if ($saveAccord == 'y') {
            $accord = new \app\models\Accords();
            $identifier = (isset($priceArr['identifier']) && strlen($priceArr['identifier']) > 0) ? $priceArr['identifier'] : $priceArr['name'];
            $accord->identifier = $identifier;
            $accord->goods_id = $goodId;
            $accord->providers_id = $cach['providerId'];
            $accord->save();
        }

        //Добавить запись в таблицу Offers
        $offer = new \app\models\Offers();
        $offer->quantity = $priceArr['quantity'];
        $offer->price = $priceArr['price'];
        $offer->goods_id = $goodId;
        $offer->providers_id = $cach['providerId'];
        $offer->save();


        // Удалить строку из кеша
        unset($cach['arrToAccord'][$k]);
        Yii::$app->cache->set($post['key'], $cach);

        //Вренуть результат
        print json_encode(['res' => 'ok', 'k' => $k]);
    }

    public function actionFinish($providerId) {
        $filename = $providerId . '.csv';

        // Проверить, существует ли поставщик
        if (($provider = providers::findOne($providerId)) !== null) {
            // если есть - получить таблицу соотвертсвий полей прайса
            $metaProv = ($provider->meta) ? unserialize($provider->meta) : [];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $FindAccords = new FindAccords($providerId, $filename, $metaProv['accords']);
        $arRes = $FindAccords->run();
        Yii::$app->cache->delete($arRes['key']);
        unlink(Yii::$app->params['uploadFolder'] . $providerId . '.csv');
        $this->redirect('/');
    }

}
