<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GoodsParams */

$this->title = 'Добавить значения параметра "'.$this->context->oGoodsParamsName->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Типы товаров', 'url' => ['/goods-type']];
$this->params['breadcrumbs'][] = ['label' => $this->context->goodTypeName, 'url' => ['//goods-params-name']];
$this->params['breadcrumbs'][] = ['label' => $this->context->oGoodsParamsName->name, 'url' => ['/goods-params']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-params-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'arParams' => $arParams,
    ]) ?>

</div>
