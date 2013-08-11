<?php

/**
 * Basic response class
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Web_Response
{
    protected $_strResponsetype;
    protected $_arrData;

    /**
     * Init response set type to full HTML mode on default
     * 
     */
    public function __construct() {
        $this->_strResponsetype = TPL_MODE_HTML_FULL;
    }

    /**
     * Get any given value in response
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
     * Set any value in response
     * 
     * @param string $strName
     * @param mixed $mixValue
     */
    public function __set($strName, $mixValue) {
        $this->_arrData[$strName] = $mixValue;
    }

    /**
     * Get response type
     * 
     * @return string
     */
    public function getStrresponsetype() {
        return $this->_strResponsetype;
    }

    /**
     * Set response type
     * 
     * @param string $strResponsetype
     */
    public function setStrresponsetype($strResponsetype) {
        $this->_strResponsetype = $strResponsetype;
    }

    /**
     * Prevent cloneing
     * 
     */
    private function __clone() {
        ;
    }
}