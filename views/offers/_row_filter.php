<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OffersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row show-grid" data-row-prop="<?=$name?>">
    <div class="col-md-2 text-right"><?=$title?>:</div>
    <div class="col-md-2"><?=Html::dropDownList($name, $ch, $data, ['class'=>'btn btn-default btn-xs col-md-12 sel-prop'])?></div>
    <div class="col-md-8"><?php
    foreach ($data as $k => $v){
        ?><div class="btn btn-default btn-xs" data-prop="<?=$k?>"><?=$v?></div><?php
    }
        ?></div>
</div>
