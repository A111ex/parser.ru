<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Discounts */

$this->title = 'Изменить скидку поставщика "'.$providerName.'"';
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="discounts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'arTypes' =>$arTypes,
    ]) ?>

</div>
