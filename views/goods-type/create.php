<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GoodsType */

$this->title = 'Создать тип товара';
$this->params['breadcrumbs'][] = ['label' => 'Тип товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
    ]) ?>

</div>
