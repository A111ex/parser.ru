<?php
/* @var $this yii\web\View */
?>
<h1>Выберите профиль выгрузки</h1>
<table class="table table-striped">
    <tr>
        <th>Профиль</th>
        <th>Описание</th>
        <th>Тип цены</th>
        <th></th>
    </tr>
    <?php
    foreach ($arProfiles as $profile => $arDescr) {
        ?>
        <tr>
            <td><?= $arDescr['name'] ?></td>
            <td><?= $arDescr['description'] ?></td>
            <td><?= yii\helpers\Html::dropDownList('type_prce', '', $arrPriceType, ['class' => 'btn btn-default btn-xs col-md-12 sel-prop']) ?></td>
            <!--<td><button class="btn btn-success" data-profile="<?= $profile ?>">Выгрузить</button></td>-->
            <td style="width:143px">
                <a href="/<?= $this->context->id ?>/unload?profile=<?= $profile ?>" class="btn btn-success hidden" data-profile="<?= $profile ?>">Выгрузить</a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<script>
<?php ob_start(); ?>
    $('[data-profile]').click(function () {
        var profile = $(this).attr('data-profile');
        var priceType = $(this).parents('tr').find('[name="type_prce"]').val();
        $(this).attr('href', "/<?= $this->context->id ?>/unload?profile="+profile+'&priceType='+priceType);
    });
    
    $('[name="type_prce"]').change(function (){
        if($(this).val() == ''){
            $(this).parents('tr').find('[data-profile]').addClass('hidden');
        }else{
            $(this).parents('tr').find('[data-profile]').removeClass('hidden');
        }
    })

    
<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
?>
