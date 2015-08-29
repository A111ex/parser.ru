<?php 

$arRes = array();

$arRes['name'] = "Автошина {$arStrCSV[1]}";
$arRes['price'] = floatval($arStrCSV[3]);
$arRes['count'] = intval($arStrCSV[2]);

if ( $arRes['price'] > 0 && $arRes['count'] > 0){
    $sql = "INSERT INTO `opc_temp_pars`(`strpin`, `price`, `tcount`,`provider`) VALUES ('{$arRes['name']}',{$arRes['price']},{$arRes['count']},'{$arResProv['post']}');";
}