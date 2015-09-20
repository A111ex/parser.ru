<table class="table table-striped">
    <thead>
        <tr>
            <th>Пост</th>
            <?php foreach ($arParamsType as $nameParam) { ?>
                <th><?= $nameParam ?></th>
            <?php } ?>
            <th>Кол</th>
            <th>Ц вх</th>
            <th>Ц вых</th>
            <th>Выгода</th>
                
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) { ?>
            <?php
            $newPrice = $item['price'] * \app\components\CalculationDiscount::calc($item['goods_id'], $item['providers_id']);
            $vigoda = $newPrice - $item['price'];
            ?>
            <tr>
                <td><?= $arProviders[$item['providers_id']] ?></td>
                <?php foreach ($arParamsType as $key => $nameParam) { ?>
                    <td><?= $arrGoodsValues[$item[$key]] ?></td>
                <?php } ?>
                <td><?= $item['quantity'] ?></td>
                <td><?= $item['price'] ?></td>
                <td><?= $newPrice ?></td>
                <td><?= $vigoda ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>