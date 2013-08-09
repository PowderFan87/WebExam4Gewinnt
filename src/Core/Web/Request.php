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

    private function _doSanatize($arrData = NULL) {
        if($arrData === NULL) {
            foreach($this->_arrData as $mixKey => $mixValue) {
                if(is_array($this->_arrData[$mixKey])) {
                    $this->_arrData[$mixKey] = $this->_doSanatize($this->_arrData[$mixKey]);
                } else {
                    $this->_arrData[$mixKey] = @mysql_escape_string(strip_tags($mixValue));
                }
            }
        } else {
            foreach($arrData as $mixKey => $mixValue) {
                $arrData[$mixKey] = @mysql_escape_string(strip_tags($mixValue));
            }
            
            return $arrData;
        }
    }

    private function __clone() {
        ;
    }
}