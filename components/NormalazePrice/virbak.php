<?php

$arRes = array();
$arRes['name'] = str_replace(array('а/ш','*1','*2'),array('Автошина','/1','/2'),$arStrCSV[2]);
$arRes['price'] = floatval($arStrCSV[3]);
$arRes['count'] = intval(str_replace("<", '', $arStrCSV[4]));

if ( $arRes['price'] > 0 && $arRes['count'] > 0){
    $sql = "INSERT INTO `opc_temp_pars`(`strpin`, `price`, `tcount`,`provider`) VALUES ('{$arRes['name']}',{$arRes['price']},{$arRes['count']},'{$arResProv['post']}');";
}