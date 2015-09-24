<?php

namespace app\controllers;

use Yii;
use app\models\offers;
use app\models\OffersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OffersController implements the CRUD actions for offers model.
 */
class OffersController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all offers models.
     * @return mixed
     */
    public function actionIndex() {
        $gt = \app\models\GoodsType::find()->orderBy('name')->all();
        $gp = \app\models\Providers::find()->orderBy('id')->all();

        $goodsType = \yii\helpers\ArrayHelper::map($gt, 'type', 'name');
        $goodsProvider = \yii\helpers\ArrayHelper::map($gp, 'id', 'name');
        $goodsProvider = ['' => ' - Все - '] + $goodsProvider;

        return $this->render('index', [
                'goodsType' => $goodsType,
                'goodsProvider' => $goodsProvider,
        ]);
    }

    public function actionGetAddFilerRows($goodsType) {

        $paramsName = \app\models\GoodsParamsName::find()->where('goods_type_type=:goods_type_type', [':goods_type_type' => $goodsType])->orderBy('sort')->all();
        $arRes = \yii\helpers\ArrayHelper::toArray($paramsName, [
                'app\models\GoodsParamsName' => [
                    'title' => 'name',
                    'name' => 'id',
                    'data' => function ($paramsName) {
                        $data = \yii\helpers\ArrayHelper::map($paramsName->getGoodsParams()->all(), 'id', 'value');
                        return ['' => ' - Все - '] + $data;
                    },
                        'parent_param' => 'parent_param',
                        'ch' => function() {
                        return '';
                    }
                    ],
                    ]
            );
            header('Content-Type: utf-8');
            foreach ($arRes as $value) {
                print $this->renderPartial('_row_filter', $value);
            }
        }

        public function actionLinkFilerRows($paramId, $parentParamId) {

            $paramsName = \app\models\GoodsParamsName::findOne($paramId);
            if ($parentParamId) {
                $paramsValues = \app\models\GoodsParams::find()->where('goods_params_name_id = :goods_params_name_id AND link_category = :link_category', [':goods_params_name_id' => $paramId, ':link_category' => $parentParamId])->all();
            } else {
                $paramsValues = \app\models\GoodsParams::find()->where('goods_params_name_id = :goods_params_name_id', [':goods_params_name_id' => $paramId])->all();
            }
            $data = \yii\helpers\ArrayHelper::map($paramsValues, 'id', 'value');
            $arRes = [
                'title' => $paramsName->name,
                'name' => $paramsName->id,
                'data' => ['' => ' - Все - '] + $data,
                'parent_param' => $paramsName->parent_param,
                'ch' => ''
            ];


            header('Content-Type: utf-8');
//        print $this->renderPartial('_row_filter', $arRes);
            $arRres = ['paramId' => $paramId, 'html' => $this->renderPartial('_row_filter', $arRes)];
            print json_encode($arRres);
        }

        public function actionDoFiler($goodsType, $goodsProvider = false) {
            header('Content-Type: utf-8');
            $oGoodsType = \app\models\GoodsType::findOne($goodsType);
            if (!($oGoodsType instanceof \app\models\GoodsType)) {
                throw new Exception('Не верный тип товара');
            }

            $GoodsParamsName = $oGoodsType->getGoodsParamsNames()->orderBy('sort')->all();
            $arParamsType = \yii\helpers\ArrayHelper::map($GoodsParamsName, 'id', 'name');
//                $arParamsType = [['id'=>'providers_id', 'name'=>'Поставщик']] + $arParamsType;


            $sql = 'select * from offers as O INNER JOIN goods as G INNER JOIN goods_t_' . $goodsType . ' as GT ' .
                'where O.goods_id = G.id and G.id = GT.goods_id ';
            $arAnd = ['G.goods_type_type = :GT'];
            $arParams = [':GT' => $goodsType];
            if ($goodsProvider) {
                $arAnd[] = 'O.providers_id = :providers_id';
                $arParams[':providers_id'] = $goodsProvider;
            }
            foreach ($_GET as $k => $v) {
                if (strlen($v) > 0 && isset($arParamsType[$k])) {
                    $arAnd[] = "GT.$k = :$k";
                    $arParams[":$k"] = $v;
                }
            }
            $sql .= ' and ' . implode(' and ', $arAnd);

            $connection = \Yii::$app->db;
            $command = $connection->createCommand($sql);
            $command->bindValues($arParams);

            $items = $command->queryAll();

            $goodsValues = \app\models\GoodsParams::find()->all();
//                $arrGoodsValues = \yii\helpers\ArrayHelper::toArray($goodsValues, ['app\models\GoodsParams'=>['id', 'value', 'public_value']]);
            $arrGoodsValues = \yii\helpers\ArrayHelper::map($goodsValues, 'id', 'value');
//                print ('<pre>');print_r($arrGoodsValues);print('</pre>');exit('100');

            $providers = \app\models\Providers::find()->all();
            $arProviders = \yii\helpers\ArrayHelper::map($providers, 'id', 'name');

            print $this->renderPartial('list', [
                    'arParamsType' => $arParamsType,
                    'items' => $items,
                    'arrGoodsValues' => $arrGoodsValues,
                    'arProviders' => $arProviders,
            ]);
        }

        /**
         * Deletes an existing offers model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }
    }
    