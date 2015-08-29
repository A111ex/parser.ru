<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GoodsParamsName */

$this->title = 'Создание параметра';
$this->params['breadcrumbs'][] = ['label' => 'Типы товаров', 'url' => ['/goods-type']];
$this->params['breadcrumbs'][] = ['label' => $this->context->goodTypeName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-params-name-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'listTypesParams' => $listTypesParams,
        'model' => $model,
        'arParams' => $arParams,
    ]) ?>

</div>
