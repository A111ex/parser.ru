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
        <?= $form->field($model, 'values')->hiddenInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'params')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="listParamsValues"></div>
    <div class="formiratorListParams">
        <span class="listParams"></span>
        <span class="listValues"></span>
        <div class="btn btn-success btnParams hidden">Ок</div>
    </div>

    <?= $form->field($model, 'coef')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success btn-save' : 'btn btn-primary btn-save']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
<?php ob_start(); ?>

    $('#discounts-goods_type_type').change(function () {
        $('.listParams').load('/<?= $this->context->id ?>/get-type-params?type=' + $(this).val(), function () {
            ParamValue.params = {};
            ParamValue.render();
            $('[name=listParams]').change(function () {
                if ($(this).val().length > 0) {
                    var parentParam = oLinks[$(this).val()];
                    // oLinks - этот объект подргужается при выборе типа товара запросом /get-type-params . в ненм прописаны связи параметров. например {tyre_model;"tyre_brand"} - значит, что значения модели связаны со значениями марки
                    link_category = '';
                    if (typeof parentParam != 'undefined') {
                        var setParentParam = ParamValue.params[parentParam]; // Выбранное ранее значение родительского параметра
                        if (typeof setParentParam != 'undefined') {
                            link_category = '&link_category=' + setParentParam.valId;
                        }
                    }
                    $('.listValues').load('/<?= $this->context->id ?>/get-type-values?param=' + $(this).val() + link_category, function () {
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
        var curVal = $('[name=listParams]').val()
        ParamValue.addParam(curVal, {paramId: curVal, paramName: $('[name=listParams] :selected').text(), valId: $('[name=listValues]').val(), valValue: $('[name=listValues] :selected').text()});
        for(var i in oLinks){
            if(oLinks[i] == curVal){
                delete ParamValue.params[i];
            }
        }
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
                $('[name=listParams]').val('').change();
                ParamValue.render();
            });
        }
    }

    $('.btn-save').click(function () {
        if (!$('.btnParams').hasClass('hidden')) {
            $('.btnParams').click();
        }
    })

    if ($('#discounts-values').val()) {
        console.log($('#discounts-values').val());
        console.log($('#discounts-values').val().length);
        $.getJSON('/<?= $this->context->id ?>/refresh-render?val=' + $('#discounts-values').val(), function (json) {
            if (json.res = 'ok') {
                for (var i in json.items) {
                    ParamValue.addParam(i, json.items[i]);
                }
                ParamValue.render();
            }
        });
    }
    $('#discounts-goods_type_type').change();

<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
