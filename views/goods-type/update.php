<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsType */

$this->title = 'Изменить тип товара: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тип товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->type]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="goods-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
        'arObjParams' => $arObjParams,
    ]) ?>

</div>
