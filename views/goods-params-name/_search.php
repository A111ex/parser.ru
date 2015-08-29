<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsParamsNameSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-params-name-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'data_type') ?>

    <?= $form->field($model, 'required') ?>

    <?= $form->field($model, 'goods_param_namecol') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'goods_type_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
