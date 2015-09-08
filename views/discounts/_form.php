<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Discounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discounts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'goods_type_type')->dropDownList($arTypes) ?>

    <div class="hidden">
        <?= $form->field($model, 'params')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'values')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="listParamsValues"></div>
    <div class="formiratorListParams">
        <span class="listParams"></span>
        <span class="listValues"></span>
        <div class="btn btn-success btnParams hidden">Ок</div>
    </div>

    <?= $form->field($model, 'coef')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
<?php ob_start(); ?>
    $('#discounts-goods_type_type').change(function () {
        $('.listParams').load('/<?= $this->context->id ?>/get-type-params?type=' + $(this).val(), function () {
            $('[name=listParams]').change(function () {
                if ($(this).val().length > 0) {
                    $('.listValues').load('/<?= $this->context->id ?>/get-type-values?param=' + $(this).val(), function () {
                        $('[name=listValues]').change(function () {
                            if ($(this).val()) {
                                $('.btnParams').removeClass('hidden');
                            } else {
                                $('.btnParams').addClass('hidden');
                            }
                        });
                    });
                } else {
                    $('.listValues').html('');
                    $('.btnParams').addClass('hidden');
                }
            });
        });
    });

    var params = {};

    $('.btnParams').click(function () {
//        ParamValue.params[$('[name=listParams]').val()] = {paramId: $('[name=listParams]').val(), paramName: $('[name=listParams] :selected').text(), valId: $('[name=listValues]').val(), valValue: $('[name=listValues] :selected').text()};
        ParamValue.addParam($('[name=listParams]').val(), {paramId: $('[name=listParams]').val(), paramName: $('[name=listParams] :selected').text(), valId: $('[name=listValues]').val(), valValue: $('[name=listValues] :selected').text()});
        ParamValue.render();
        $('[name=listParams]').val('');
        $('.listValues').html('');
        $('.btnParams').addClass('hidden');
    });

    ParamValue = {
        params: {},
        addParam: function (param, val) {
            this.params[param] = val;
            var arW = [];
            for (var i in this.params) {
                arW.push(i);
            }
            arW.sort();
            var newParams = {};
            for (var i in arW) {
                newParams[arW[i]] = this.params[arW[i]];
            }
            this.params = newParams;

        },
        render: function () {
            var arParams = [];
            var arValues = [];
            var listParamsValues = '';
            for (var i in this.params) {
                arParams.push(this.params[i].paramId);
                arValues.push(this.params[i].valId);
                listParamsValues += '<p data-param-del="' + this.params[i].paramId + '"><span class="btn glyphicon glyphicon-trash"></span>' + this.params[i].paramName + ': <b>' + this.params[i].valValue + '</b></p>';
            }
            $('[name="Discounts[params]"]').val(arParams.join(';'));
            $('[name="Discounts[values]"]').val(arValues.join(';'));
            $('.listParamsValues').html(listParamsValues);
            $('.glyphicon-trash').click(function () {
                delete ParamValue.params[$(this).parent().attr('data-param-del')];
                ParamValue.render();
            });
        }
    }

    function renderParamValues() {

    }

<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
