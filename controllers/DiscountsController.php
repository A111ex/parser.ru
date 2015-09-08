<?php

namespace app\controllers;

use Yii;
use app\models\Discounts;
use app\models\DiscountsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DiscountsController implements the CRUD actions for Discounts model.
 */
class DiscountsController extends Controller {

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
     * Lists all Discounts models.
     * @return mixed
     */
    public function actionIndex($providerId = null) {
        $session = Yii::$app->session;
        $session->open();
        if ($providerId) {
            $session['providerIdFullName'] = $providerId;
            $provider = \app\models\Providers::findOne($providerId);
            $session['providerIdFullNameName'] = $provider->name;
            $this->redirect('/' . $this->id);
        }

        $searchModel = new DiscountsSearch();

        $arParams = Yii::$app->request->queryParams;
        $arParams['DiscountsSearch']['providers_id'] = $session['providerIdFullName'];

        $dataProvider = $searchModel->search($arParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Discounts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Discounts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Discounts();
        $session = Yii::$app->session;
        $session->open();
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {
            $post['Discounts']['providers_id'] = $session['providerIdFullName'];
//            $post['Discounts']['values'] = $post['Discounts']['values'] . ' ';
            $res = $model->load($post);
        }

        $types = \app\models\GoodsType::find()->all();
        $arTypes = ['' => ' - Не выбран тип товара -'];
        foreach ($types as $type) {
            $arTypes[$type->type] = $type->name;
        }

        if ($model->load($post) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'arTypes' => $arTypes,
                        'providerName' => $session['providerIdFullNameName'],
            ]);
        }
    }

    /**
     * Updates an existing Discounts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $session = Yii::$app->session;
        $session->open();
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {
            $post['Discounts']['providers_id'] = $session['providerIdFullName'];
//            $post['Discounts']['values'] = $post['Discounts']['values'] . ' ';
            $res = $model->load($post);
        }

        $types = \app\models\GoodsType::find()->all();
        $arTypes = ['' => ' - Не выбран тип товара -'];
        foreach ($types as $type) {
            $arTypes[$type->type] = $type->name;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'arTypes' => $arTypes,
                        'providerName' => $session['providerIdFullNameName'],
            ]);
        }
    }

    /**
     * Deletes an existing Discounts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Discounts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Discounts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Discounts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetTypeParams($type) {
        $params = \app\models\GoodsParamsName::find()->where('goods_type_type=:goods_type_type', [':goods_type_type' => $type])->orderBy('sort ASC')->all();
        $arParams = ['' => ' - Добавить параметр - '];
        foreach ($params as $param) {
            $arParams[$param->id] = $param->name;
        }
        print \yii\helpers\BaseHtml::dropDownList('listParams', '', $arParams);
    }

    public function actionGetTypeValues($param) {
        $params = \app\models\GoodsParams::find()->where('goods_params_name_id=:goods_params_name_id', [':goods_params_name_id' => $param])->orderBy('sort ASC')->all();
        $arParams = ['' => ' - Выбрать значение - '];
        foreach ($params as $param) {
            $arParams[$param->id] = $param->value;
        }
        print \yii\helpers\BaseHtml::dropDownList('listValues', '', $arParams);
    }

    public function actionRefreshRender($val) {
        $arRes = [];
        $arVals = explode(';', $val);
        foreach ($arVals as $val) {
            $oVal = \app\models\GoodsParams::findOne($val);
//            print ('<pre>');print_r($oVal);print('</pre>');
//            $oPar = $oVal->getGoodsParamsName();
            $oPar = \app\models\GoodsParamsNameSearch::findOne($oVal->goods_params_name_id);
//            print ('<pre>');print_r($oPar);print('</pre>');exit('100');
            $arRes[$oPar->id] = [
                'paramId' => $oPar->id,
                'paramName' => $oPar->name,
                'valId' => $oVal->id,
                'valValue' => $oVal->value,
            ];
        }
        print json_encode(['res' => 'ok', 'items' => $arRes]);
    }

}
