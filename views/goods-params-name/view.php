<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsParamsName */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $this->context->goodTypeName, 'url' => ['/goods-type/view', 'id' => $this->context->goodType]];
$this->params['breadcrumbs'][] = ['label' => 'Goods Params Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-params-name-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'data_type',
            'required',
//            'goods_param_namecol',
            'sort',
            'goods_type_type',
        ],
    ]) ?>

</div>
