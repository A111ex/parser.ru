<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OffersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список предложений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    $this->render('_row_filter', [
        'title' => 'Тип товара',
        'name' => 'goodsType',
        'data' => $goodsType,
        'ch' => 'tyre',
    ])
    ?>

    <?=
    $this->render('_row_filter', [
        'title' => 'Поставщик',
        'name' => 'goodsProvider',
        'data' => $goodsProvider,
        'ch' => '',
    ])
    ?>

    <div class="additionsRows"></div>

</div>


<script>
<?php ob_start(); ?>
    initSel = function (obj) {
        obj.find('.sel-prop').change(function () {
            var selProp = $(this).val();
            var parentRow = $(this).parents('[data-row-prop]')
            parentRow.find('[data-prop]').removeClass('btn-primary').addClass('btn-default');
            parentRow.find('[data-prop="' + selProp + '"]').removeClass('btn-default').addClass('btn-primary');
            $('[data-parent-param="' + parentRow.attr('data-row-prop') + '"]').each(function () {
                var parentParamId = $('[data-row-prop="'+$(this).attr('data-parent-param')+'"] select').val();
                $.getJSON('/<?= $this->context->id ?>/link-filer-rows', {paramId: $(this).attr('data-row-prop'), parentParamId: parentParamId},
                function (json) {
                    $('[data-row-prop="'+json.paramId+'"]').html($(json.html).html());
                    initSel($('[data-row-prop="'+json.paramId+'"]'));
                });
            });
        });

        obj.find('[data-prop]').click(function () {
            $(this).parents('[data-row-prop]').find('.sel-prop').val($(this).attr('data-prop')).change();
        });
        obj.find('.sel-prop').change();
    };

    $('[name="goodsType"]').change(function () {

        $.get('/<?= $this->context->id ?>/get-add-filer-rows', {goodsType: $(this).val()},
        function (data) {
            $('.additionsRows').html(data);
            $('.additionsRows [data-row-prop]').each(function () {
                initSel($(this));
            });
        }
        )
    })

    $('[data-row-prop]').each(function () {
        initSel($(this));
    });
<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
?>
