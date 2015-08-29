<?php
//   \Yii::$app->params['emptyStringParam']
return [
    'adminEmail' => 'admin@example.com',
    'uploadFolder' => $_SERVER["DOCUMENT_ROOT"]. '/uploads/',
    'cacheFolder' => $_SERVER["DOCUMENT_ROOT"]. '/c/',
    'emptyStringParam' => '===', // замена на пустую строку параметра товара в публичном значении
    
];
