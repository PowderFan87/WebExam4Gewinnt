<?php

/**
 * Basic request class
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Web_Request
{
    protected $_strRequestmethode;
    protected $_arrData;

    /**
     * Init request
     * 
     */
    public function __construct() {
        $this->_doFetchdata();

        $this->_doSanatize();
    }

    public function __destruct() {
        ;
    }

    /**
     * get any request parameter from collection
     * 
     * @param string $strProperty
     * @return mixed|boolean
     */
    public function __get($strProperty) {
        if(isset($this->_arrData[$strProperty])) {
            return $this->_arrData[$strProperty];
        }
        
        return false;
    }

    /**
     * Get reqeust methode
     * 
     * @return string
     */
    public function getStrrequestmethode() {
        return $this->_strRequestmethode;
    }

    abstract public function getRequesttype();

    abstract protected function _doFetchdata();

    /**
     * Sanatize input for later use. Strip all tags and escape string.
     * If the value is an array the methode is executed recursive.
     * 
     * @param array|null $arrData
     * @return array|null
     */
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

    /**
     * Prevent cloneing
     * 
     */
    private function __clone() {
        ;
    }
}