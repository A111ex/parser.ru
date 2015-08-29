<?php

$arRes = array();

$arRes['name'] = "{$arStrCSV[0]}";
$arRes['price'] = floatval($arStrCSV[1]);
$arRes['count'] = intval(str_replace(array("более"), '', $arStrCSV[2]));

if ( $arRes['price'] > 0 && $arRes['count'] > 0){
    $sql = "INSERT INTO `opc_temp_pars`(`strpin`, `price`, `tcount`,`provider`) VALUES ('{$arRes['name']}',{$arRes['price']},{$arRes['count']},'{$arResProv['post']}');";
}


