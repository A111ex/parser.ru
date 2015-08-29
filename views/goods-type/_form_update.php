<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-type-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->hiddenInput() ?><b><?= $model->type ?></b>
    <br><br>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'template_view')->textInput(['maxlength' => true]) ?>

    <?php
    echo '<p>', '<b data-param-id="goodType">{goodType}</b> - Название типи товара ('.$model->name.')</p>';
    foreach ($arObjParams as $arParams) {
        echo '<p>', '<b data-param-id="'.$arParams->id.'">{'.$arParams->id.'}</b> - ', $arParams->name, '</p>';
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
        $('#goodstype-template_view').val($('#goodstype-template_view').val()+$(this).text()).focus();
    });
<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
?>
