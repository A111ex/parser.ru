<?php
/* @var $this yii\web\View */
?>
<h1>Выберите профиль выгрузки</h1>
<table class="table table-striped">
    <tr>
        <th>Профиль</th>
        <th>Описание</th>
        <th></th>
    </tr>
    <?php
    foreach ($arProfiles as $profile => $arDescr) {
        ?>
        <tr>
            <td><?= $arDescr['name'] ?></td>
            <td><?= $arDescr['description'] ?></td>
            <!--<td><button class="btn btn-success" data-profile="<?= $profile ?>">Выгрузить</button></td>-->
            <td><a href="/<?= $this->context->id ?>/unload?profile=<?= $profile ?>" class="btn btn-success" data-profile="<?= $profile ?>">Выгрузить</a></td>
        </tr>
        <?php
    }
    ?>
</table>

<script>
<?php ob_start(); ?>
    $('[data-profile]').click(function () {
//        $.post('/<?= $this->context->id ?>/unload',
        $.get('/<?= $this->context->id ?>/unload?profile=' + $(this).attr('data-profile'),
                function (data) {
                    console.log(data);
                }
        );
    });
<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
?>
