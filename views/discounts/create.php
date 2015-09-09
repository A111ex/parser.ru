<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Discounts */

$this->title = 'Создать наценку для поставщика "'.$providerName.'"';
$this->params['breadcrumbs'][] = ['label' => 'Наценки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discounts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'arTypes' =>$arTypes,
    ]) ?>

</div>
