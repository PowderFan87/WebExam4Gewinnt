<?php

/**
 * Description of Core_Web_Request
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Web_Request
{
    protected $_strRequestmethode;
    protected $_arrData;

    public function __construct() {
        $this->_doFetchdata();

        $this->_doSanatize();
    }

    public function __destruct() {
        ;
    }

    public function __get($strProperty) {
        if(isset($this->_arrData[$strProperty])) {
            return $this->_arrData[$strProperty];
        }
    }

    public function getStrrequestmethode() {
        return $this->_strRequestmethode;
    }

    abstract public function getRequesttype();

    abstract protected function _doFetchdata();

    private function _doSanatize() {
        foreach($this->_arrData as $mixKey => $mixValue) {
            $this->_arrData[$mixKey] = @mysql_escape_string(strip_tags($mixValue));
        }
    }

    private function __clone() {
        ;
    }
}