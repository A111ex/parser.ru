<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'template_view')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
