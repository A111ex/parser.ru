<?php

function normalizeStrOfPrice($arStrCSV) {
    $arRes = array();
    $name = str_replace(array("Автошина ", "Автошины ", " LT2"), array("", "", " 2"), $arStrCSV[1]);
    $arRes['name'] = "Автошина {$name}";
    $price = str_replace(array(" ", "-"), array("", ","), $arStrCSV[3]);
    $arRes['price'] = floatval($price);
    $arRes['count'] = intval(str_replace(array("\"", ">", "более"), '', $arStrCSV[2]));
    return $arRes;
}