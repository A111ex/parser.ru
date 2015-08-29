<?php

namespace app\components;

use yii\base\Object;

class ParserPrice extends Object {

    private $filename;
    private $providerId;

    public function __construct($providerId, $filename) {
        $this->filename = $filename;
        $this->providerId = $providerId;
        return parent::__construct();
    }

    public function run() {
        $this->step1();
    }

    /**
     * 
     * 
     * @param type $param
     */
    public function step1() {
        $arrCSV = file($this->filename);
        include_once dir(__FILE__) . '/' . $this->providerId . '.php';
        
        foreach ($arrCSV as $strCSV) {
            $arStrCSV = str_getcsv($strCSV, ';');
            $arRes = normalizeStrOfPrice($arStrCSV);
        }
    }

}
