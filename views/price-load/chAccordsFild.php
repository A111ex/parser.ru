
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if (count($arErr) > 0) {
    print 'Не выбраны поля: ';
    foreach ($arErr as $key => $value) {
        print '"' . $arFilds[$value] . '", ';
    }
}
if (count($err2) > 0) {
    print "<br>Дублируются поля: ";
    foreach ($err2 as $key => $value) {
        print '"' . $arFilds[$key] . ' - ' . $value . ' раза", ';
    }
}

//echo Html::dropDownList('listname', '', $arFilds)
?>

<?php $form = ActiveForm::begin(['method' => 'post']) ?>
<table border = "1" align = "center" class="table-striped">
    <tbody>
        <?php
        $i = 0;
        foreach ($rows as $row) {
            if ($i == 0) {
                echo "<tr>";
                foreach ($row as $key => $ceil) {
                    echo "<td>", $key + 1, "</td>";
                }
                echo "</tr>";
                echo "<tr>";
                foreach ($row as $key => $ceil) {
                    $ch = ($arAccords[$key]) ? $arAccords[$key] : '0';
                    echo "<td>", Html::dropDownList('accords[' . $key . ']', $ch, $arFilds, ['class' => 'selectpicker']), "</td>";
                }
                echo "</tr>";
            }
            echo "<tr>";
            foreach ($row as $ceil) {
                echo "<td>", $ceil, "</td>";
            }
            echo "</tr>";
            $i++;
        }
        ?>
    </tbody>
</table>

<div style="text-align:center; margin-top: 15px;">
    <button class="btn btn-success">Далее</button>
</div>
<?php ActiveForm::end() ?>
