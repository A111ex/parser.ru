<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GoodsParamsName */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-params-name-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'data_type')->hiddenInput(['maxlength' => true]) ?>

    <div class="listTypesParams hidden">
        <div class="">
            <?= Html::dropDownList('listTypesParams', '', $listTypesParams); ?>
            <?= Html::textInput('varcharLength', '10', ['class' => 'hidden']); ?>
        </div>
    </div>



    <?= $form->field($model, 'required')->checkbox() ?>

    <?= $form->field($model, 'parent_param')->dropDownList($arParams)?>

    <?= $form->field($model, 'sort')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
<?php ob_start(); ?>

    $('#goodsparamsname-data_type').after($('.listTypesParams').html());
    $('.listTypesParams').remove();

    $('[name="listTypesParams"]').change(function () {
        var val = $(this).val();
        if (val === 'varchar') {
            $('[name="varcharLength"]').removeClass('hidden');
            var int = parseInt($('[name="varcharLength"]').val());
            if (isNaN(int) || int < 1) {
                int = 1;
            }
            val = val + '(' + int + ')';

        } else {
            $('[name="varcharLength"]').addClass('hidden');
        }

        $('#goodsparamsname-data_type').val(val);
    });

    $('[name="varcharLength"]').keyup(function () {
        $('[name="listTypesParams"]').change();
    });


<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
