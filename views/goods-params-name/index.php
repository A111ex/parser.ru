<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GoodsParamsNameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->goodTypeName;
$this->params['breadcrumbs'][] = ['label' => 'Типы товаров', 'url' => ['/goods-type']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-params-name-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить параметр', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'data_type',
            'required',
            'parent_param',
            // 'goods_param_namecol',
             'sort',
            // 'goods_type_type',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{values} {update} {delete}',
                'buttons' => [
                    'values' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-th-list"></span>', ['/goods-params', 'goodTypeParam' => $model->id], ['class' => '']);
                    }
                        ]
                    ],
        ],
    ]);
    ?>

</div>
