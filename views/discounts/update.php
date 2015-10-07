<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Discounts */

$this->title = 'Изменить наценку цены "' . $typePriceName . '" поставщика "'.$providerName.'"';
$this->params['breadcrumbs'][] = ['label' => 'Наценки', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="discounts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'arTypes' =>$arTypes,
    ]) ?>

</div>
