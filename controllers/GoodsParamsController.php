<?php

namespace app\controllers;

use Yii;
use app\models\GoodsParams;
use app\models\GoodsParamsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoodsParamsController implements the CRUD actions for GoodsParams model.
 */
class GoodsParamsController extends Controller {

    public $oGoodsParamsName;
    public $goodTypeName;

    function __construct($id, $module, $config = array()) {
        $session = Yii::$app->session;
        $session->open();
        if (isset(Yii::$app->request->queryParams['goodTypeParam'])) {
            $session['goodTypeParam'] = Yii::$app->request->queryParams['goodTypeParam'];
            return $this->redirect('/' . $id);
        }
        if (isset($session['goodTypeParam']) && strlen($session['goodTypeParam']) > 0) {
//            $this->goodTypeParam = $session['goodTypeParam'];
        } else {
            $this->redirect('/goods-type');
        }

        $this->oGoodsParamsName = \app\models\GoodsParamsName::findOne($session['goodTypeParam']);
        if (!($this->oGoodsParamsName instanceof \app\models\GoodsParamsName)) {
            throw new Exception('Не существует такой тип параметра.');
        }

        $oGoodType = \app\models\GoodsType::findOne($session['goodType']);
        if (!($oGoodType instanceof \app\models\GoodsType)) {
            throw new Exception('Не существует такой тип товара.');
        }
        $this->goodTypeName = $oGoodType->name;


        parent::__construct($id, $module, $config);
    }

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
     * Lists all GoodsParams models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new GoodsParamsSearch();
        $params = Yii::$app->request->queryParams;
        $params['GoodsParamsSearch']['goods_params_name_id'] = $this->oGoodsParamsName->id;
        $dataProvider = $searchModel->search($params);
        
        $parentsValues = false;
        if($this->oGoodsParamsName->parent_param){
            $parentsValues = GoodsParams::find()->where('goods_params_name_id = :goods_params_name_id', [':goods_params_name_id' => $this->oGoodsParamsName->parent_param])->all();
            $parentsValues = \yii\helpers\ArrayHelper::map($parentsValues, 'id', 'value');
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'parentsValues' => $parentsValues,
        ]);
    }

    /**
     * Displays a single GoodsParams model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoodsParams model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new GoodsParams();

        $arPost = Yii::$app->request->post();
        if (Yii::$app->request->getIsPost()) {
            $arPost['GoodsParams']['goods_params_name_id'] = $this->oGoodsParamsName->id;
            if (!$this->oGoodsParamsName->parent_param) {
                $arPost['GoodsParams']['link_category'] = 0;
            }
        }

        if (Yii::$app->request->getIsPost() && $model->load($arPost) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $listTypesParams = ['' => ' - - ',];
            $parentParam = GoodsParams::find()->where('goods_params_name_id=:parent_param', [':parent_param' => $this->oGoodsParamsName->parent_param])->all();
            $arParams = ['' => ' - - '];
//            print ('<pre>');print_r($parentParam);print('</pre>');exit('100');
            foreach ($parentParam as $value) {
                $arParams[$value->id] = $value->value;
            }

            return $this->render('create', [
                        'model' => $model,
                        'arParams' => $arParams,
            ]);
        }
    }

    /**
     * Updates an existing GoodsParams model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $arPost = Yii::$app->request->post();
        if (is_array($arPost)) {
            $arPost['GoodsParams']['goods_params_name_id'] = $this->oGoodsParamsName->id;
        }

        if (Yii::$app->request->isPost && $model->load($arPost) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $listTypesParams = ['' => ' - - ',];
            $parentParam = GoodsParams::find()->where('goods_params_name_id=:parent_param', [':parent_param' => $this->oGoodsParamsName->parent_param])->all();
            $arParams = ['' => ' - - '];
//            print ('<pre>');print_r($parentParam);print('</pre>');exit('100');
            foreach ($parentParam as $value) {
                $arParams[$value->id] = $value->value;
            }
            return $this->render('update', [
                        'model' => $model,
                        'arParams' => $arParams,
            ]);
        }
    }

    /**
     * Deletes an existing GoodsParams model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GoodsParams model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsParams the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = GoodsParams::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
