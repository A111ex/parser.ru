<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PriceType */

$this->title = 'Создать тип цены';
$this->params['breadcrumbs'][] = ['label' => 'Тип цены', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
