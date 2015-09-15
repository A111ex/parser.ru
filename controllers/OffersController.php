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
        ]);
        header('Content-Type: utf-8');
        foreach ($arRes as $value) {
            print $this->renderPartial('_row_filter', $value);
        }
    }
    
    public function actionLinkFilerRows($paramId, $parentParamId) {

        $paramsName = \app\models\GoodsParamsName::findOne($paramId);
        if($parentParamId){
            $paramsValues = \app\models\GoodsParams::find()->where('goods_params_name_id = :goods_params_name_id AND link_category = :link_category', [':goods_params_name_id'=>$paramId, ':link_category'=>$parentParamId])->all();
        }else{
            $paramsValues = \app\models\GoodsParams::find()->where('goods_params_name_id = :goods_params_name_id', [':goods_params_name_id'=>$paramId])->all();
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
        $arRres = ['paramId'=>$paramId, 'html'=>$this->renderPartial('_row_filter', $arRes)];
        print json_encode($arRres);
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
        