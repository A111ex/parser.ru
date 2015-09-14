<?php
$url= 'https://www.avito.ru/krasnodar/avtomobili/audi?q=%D0%B0%D0%B2%D1%82%D0%BE+%D1%81+%D0%BF%D1%80%D0%BE%D0%B1%D0%B5%D0%B3%D0%BE%D0%BC&f=188_771b.1375_15483b.1374_15775b';
$res = file_get_contents($url);
print $res;

