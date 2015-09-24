<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Имя в прайсе';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accords-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'identifier',
//            'goods_id',
//            'providers_id',
            [
                'attribute' => 'providers_id',
                'label' => 'Поставщик',
                'format' => 'html',
                'filter' => yii\helpers\ArrayHelper::map(app\models\Providers::find()->all(), 'id', 'name'),
                'value' => function($model) {
                    return app\models\Providers::findOne($model->providers_id)->name;
                }
            ],
            [
                'attribute' => 'link_category',
                'label' => 'Товар',
                'format' => 'html',
                'value' => function($model) {
                    return \app\components\Goods::getName($model->goods_id);
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]);
    ?>

</div>
