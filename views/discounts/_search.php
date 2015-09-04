<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DiscountsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discounts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'providers_id') ?>

    <?= $form->field($model, 'goods_type_type') ?>

    <?= $form->field($model, 'params') ?>

    <?= $form->field($model, 'values') ?>

    <?php // echo $form->field($model, 'coef') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
