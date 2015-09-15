<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GoodsParamsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Значения параметра "'.$this->context->oGoodsParamsName->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Типы товаров', 'url' => ['/goods-type']];
$this->params['breadcrumbs'][] = ['label' => $this->context->goodTypeName, 'url' => ['//goods-params-name']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-params-index">

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
//            'id',
            'value',
            'public_value',
            'sort',
//            'link_category',
            [
                'attribute' => 'link_category',
                'label' => 'Привязано к',
                'format' => 'html',
                'value' => function($model) {
                    $pp = app\models\GoodsParams::findOne($model->link_category);
                    return $pp->value;
                }
                    ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>

</div>
