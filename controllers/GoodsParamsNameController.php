<?php

namespace app\controllers;

use Yii;
use app\models\GoodsParamsName;
use app\models\GoodsParamsNameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoodsParamsNameController implements the CRUD actions for GoodsParamsName model.
 */
class GoodsParamsNameController extends Controller {

    public $goodType;
    public $goodTypeName;

    public function __construct($id, $module, $config = array()) {

        if (isset(Yii::$app->request->queryParams['goodType'])) {
            $session = Yii::$app->session;
            $session->open();
            $session['goodType'] = Yii::$app->request->queryParams['goodType'];
            return $this->redirect('/' . $id);
        }

        $session = Yii::$app->session;
        $session->open();
        if (isset($session['goodType']) && strlen($session['goodType']) > 0) {
            $this->goodType = $session['goodType'];
        } else {
            $this->redirect('/goods-type');
        }
        $oGoodType = \app\models\GoodsType::findOne($session['goodType']);
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all GoodsParamsName models.
     * @return mixed
     */
    public function actionIndex() {
        if(!isset($_GET['sort'])){
            $_GET['sort'] = 'sort';
        }
        $searchModel = new GoodsParamsNameSearch();
        $params = Yii::$app->request->queryParams;
        $params['GoodsParamsNameSearch']['goods_type_type'] = $this->goodType;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsParamsName model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoodsParamsName model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new GoodsParamsName();
        $arPost = Yii::$app->request->post();
        if (Yii::$app->request->getIsPost()) {
            $arPost['GoodsParamsName']['goods_type_type'] = $this->goodType;
            $arPost['GoodsParamsName']['id'] = str_replace($this->goodType . '_', '', $arPost['GoodsParamsName']['id']);
            $arPost['GoodsParamsName']['id'] = $this->goodType . '_' . $arPost['GoodsParamsName']['id'];
        }

        if (Yii::$app->request->getIsPost() && $model->load($arPost) && $model->save()) {
            $arPost['GoodsParamsName']['id'] = str_replace($this->goodType . '_', '', $arPost['GoodsParamsName']['id']);
            $arPost['GoodsParamsName']['id'] = $this->goodType . '_' . $arPost['GoodsParamsName']['id'];
            $table = 'goods_t_' . $this->goodType;
            $fild = $arPost['GoodsParamsName']['id'];
            $dataType = $arPost['GoodsParamsName']['data_type'];
            $required = ($arPost['GoodsParamsName']['required']) ? ' NOT NULL' : '';
//            $sql = "ALTER TABLE `$table` ADD `$fild` $dataType $required";
            $sql = "ALTER TABLE `$table` ADD `$fild` int(7) $required";
            \Yii::$app->db->createCommand($sql)->execute();

            // ALTER TABLE `goods_t_tyre` ADD `with` INT NOT NULL
            return $this->redirect(['index']);
        } else {
            $listTypesParams = ['' => ' - - ', 'int' => 'Целое число', 'float' => 'Число с запятой', 'varchar' => 'Строка'];
            $parentParam = GoodsParamsName::find()->where('goods_type_type=:goods_type', [':goods_type' => $this->goodType])->all();
            $arParams = ['' => ' - - '];
            foreach ($parentParam as $value) {
                $arParams[$value->id] = $value->name;
            }
            return $this->render('create', [
                        'listTypesParams' => $listTypesParams,
                        'model' => $model,
                        'arParams' => $arParams,
            ]);
        }
    }

    /**
     * Updates an existing GoodsParamsName model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GoodsParamsName model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        $table = 'goods_t_' . $this->goodType;
        $fild = $this->goodType . '_' . str_replace($this->goodType . '_', '', $id);
        $sql = "ALTER TABLE `$table` DROP `$fild`";
        \Yii::$app->db->createCommand($sql)->execute();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GoodsParamsName model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GoodsParamsName the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = GoodsParamsName::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
