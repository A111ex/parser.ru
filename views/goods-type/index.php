<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GoodsTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-type-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать тип товара', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'type',
            'name',
            'alias',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {params} {delete}',
                'buttons' => [
                    'params' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-th-list"></span>', ['/goods-params-name', 'goodType' => $model->type], ['class' => 'btn btn-lg']);
                    }
                        ]
                    ],
                ],
            ]);
            ?>

</div>