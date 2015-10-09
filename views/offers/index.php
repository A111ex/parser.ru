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
    
    
    <p>Тип цены: 
        <?php
        foreach ($arrPriceType as $k => $v) {
            print Html::a($v, ['index', 'typePrice' => $k], ['class' => ('btn btn-primary' . (($typePrice == $k) ? ' active' : ''))]) . '&nbsp;';
        }
        ?>
    </p>

    <div class="btn btn-success f-open f-op-cl">Закрыть фильтр</div>
    <div class="shortFiltr hide" style='display: inline-block;'></div>
    <div class="fullFilter">
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
    <div class="btn btn-success f-show">Показать</div>
    <div class="listOffer"></div>
</div>


<script>
<?php ob_start(); ?>

    $('.f-show').click(function () {
        var ser = $('.fullFilter select').serializeArray();
        $('.f-open').click();
        $.get('/<?= $this->context->id ?>/do-filer', ser,
                function (data) {
                    $('.listOffer').html(data);
                    //console.log(data)
                }
        );
    });

    $('.f-op-cl').click(function () {
        if ($(this).hasClass('f-open')) {
            $(this).removeClass('f-open').text('Открыть фильтр');
            $('.shortFiltr').removeClass('hide');
            $('.fullFilter').hide();
        } else {
            $(this).addClass('f-open').text('Закрыть фильтр');
            $('.shortFiltr').addClass('hide');
            $('.fullFilter').show();
        }
    });
    initSel = function (obj) {
        obj.find('.sel-prop').change(function () {
            var selProp = $(this).val();
            var parentRow = $(this).parents('[data-row-prop]')
            parentRow.find('[data-prop]').removeClass('btn-primary').addClass('btn-default');
            parentRow.find('[data-prop="' + selProp + '"]').removeClass('btn-default').addClass('btn-primary');
            $('[data-parent-param="' + parentRow.attr('data-row-prop') + '"]').each(function () {
                var parentParamId = $('[data-row-prop="' + $(this).attr('data-parent-param') + '"] select').val();
                $.getJSON('/<?= $this->context->id ?>/link-filer-rows', {paramId: $(this).attr('data-row-prop'), parentParamId: parentParamId},
                function (json) {
                    $('[data-row-prop="' + json.paramId + '"]').html($(json.html).html());
                    initSel($('[data-row-prop="' + json.paramId + '"]'));
                });
            });
            shortFilter();
        });

        obj.find('[data-prop]').click(function () {
            $(this).parents('[data-row-prop]').find('.sel-prop').val($(this).attr('data-prop')).change();
        });
        obj.find('.sel-prop').change();
        shortFilter();
    };

    function shortFilter() {
        var arFilter = [];
        $('[data-row-prop]').each(function () {
            if ($(this).find('select').val()) {
                var name = $(this).find('.name-param').text();
                var val = $(this).find(':selected').text();
                arFilter.push(name + ': <b>' + val + '</b>');
            }
        });
        $('.shortFiltr').html(arFilter.join('; '));
    }

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
