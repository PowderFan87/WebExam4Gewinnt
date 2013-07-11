<?php

/**
 * Description of Core_Web_Response
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Web_Response
{
    protected $_strResponsetype;
    protected $_arrData;

    public function __construct() {
        $this->_strResponsetype = TPL_MODE_HTML_FULL;
    }

    public function __get($strProperty) {
        if(isset($this->_arrData[$strProperty])) {
            return $this->_arrData[$strProperty];
        }
    }

    public function __set($strName, $mixValue) {
        $this->_arrData[$strName] = $mixValue;
    }

    public function getStrresponsetype() {
        return $this->_strResponsetype;
    }

    public function setStrresponsetype($strResponsetype) {
        $this->_strResponsetype = $strResponsetype;
    }

    private function __clone() {
        ;
    }
}