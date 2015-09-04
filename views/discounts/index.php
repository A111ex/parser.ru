<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Скидки и наценки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discounts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Discounts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'providers_id',
            [
                'attribute' => 'test1',
                'label' => 'Параметр',
                'format' => 'html',
                'value' => function($model) {
                    return $model->params . ' ' . $model->values;
                }
                ],
            [
                'attribute' => 'coef1',
                'label' => 'Коэффициент',
                'format' => 'html',
                'value' => function($model) {
                    return $model->coef;
                }
                ],
              'goods_type_type',
//            'params',
//            'values',
//            'coef',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
