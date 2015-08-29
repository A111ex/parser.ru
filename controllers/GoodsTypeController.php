<?php

namespace app\controllers;

use Yii;
use app\models\GoodsType;
use app\models\GoodsTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoodsTypeController implements the CRUD actions for GoodsType model.
 */
class GoodsTypeController extends Controller {

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
     * Lists all GoodsType models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new GoodsTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsType model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $session = Yii::$app->session;
        $session->open();
        $session['goodType'] = $model->type;
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new GoodsType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new GoodsType();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $GoodsTable = new \app\components\GoodsTable();
            $GoodsTable->addGoodsTable($model->type);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GoodsType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $arObjParams = \app\models\GoodsParamsName::find()->where('goods_type_type=:goods_type_type', [':goods_type_type'=>$id])->all();
            return $this->render('update', [
                        'arObjParams' => $arObjParams,
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GoodsType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $type = $model->type;
        $model->delete();
        $GoodsTable = new \app\components\GoodsTable();
        $GoodsTable->delGoodsTable($type);
        return $this->redirect(['index']);
    }

    /**
     * Finds the GoodsType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GoodsType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = GoodsType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
