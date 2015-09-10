<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\offersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Offers';
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
            $(this).parents('[data-row-prop]').find('[data-prop]').removeClass('btn-primary').addClass('btn-default');
            $(this).parents('[data-row-prop]').find('[data-prop="' + selProp + '"]').removeClass('btn-default').addClass('btn-primary');
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
