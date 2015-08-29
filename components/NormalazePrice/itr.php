<?php 
$arRes = array();
$arRes['name'] = "Автошина {$arStrCSV[1]} {$arStrCSV[3]}";
$arRes['price'] = floatval($arStrCSV[6]);
$arRes['quantity'] = intval(str_replace(">", '', $arStrCSV[5]));
//$arRes['count'] = intval($arStrCSV[5]);

if ( $arRes['price'] > 0 && $arRes['count'] > 0){
    $sql = "INSERT INTO `opc_temp_pars`(`strpin`, `price`, `tcount`,`provider`) VALUES ('{$arRes['name']}',{$arRes['price']},{$arRes['quantity']},'{$arResProv['post']}');";
}