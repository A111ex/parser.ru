<?php

function normalizeStrOfPrice($arStrCSV, $arrAccords) {
    $arRes = array();
    $arRes['name'] = trim($arStrCSV[$arrAccords['name']]);
    if (isset($arrAccords['identifier']))
        $arRes['identifier'] = trim($arStrCSV[$arrAccords['identifier']]);
    $price = str_replace(array(" ", "-"), array("", ","), $arStrCSV[$arrAccords['price']]);
    $arRes['price'] = floatval($price);
    $arRes['quantity'] = intval(str_replace(array("\"", ">", "более"), '', $arStrCSV[$arrAccords['quantity']]));
    return $arRes;
}
