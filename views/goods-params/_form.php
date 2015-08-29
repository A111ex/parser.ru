<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsParams */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-params-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'public_value')->textInput(['maxlength' => true]) ?>
    
    <p>Если нужно, чтобы значение было пустое, тогда - <b data-param-id="goodType"><?=Yii::$app->params['emptyStringParam']?></b></p>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?php if ($this->context->oGoodsParamsName->parent_param) { ?>
        <?= $form->field($model, 'link_category')->dropDownList($arParams) ?>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    [data-param-id]{
        cursor: pointer;
    }

</style>
<script>
<?php ob_start(); ?>
    $('[data-param-id]').click(function(){
        $('#goodsparams-public_value').val($(this).text()).focus();
    });
<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
?>
