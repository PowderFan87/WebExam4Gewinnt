<?php

/**
 * Base AR class for all AR objects common factors
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class App_Data_Base
{
    const   TABLE_CLASS = 'undefined';
    const   TABLE_PK    = 'UID';

    protected $_arrData;
    protected $_blnAltered = false;
    protected $_blnUpdated = false;

    /**
     * Init AR object. If arrData is null the empty array is loaded instead
     * of the given value (for new entries into DB).
     * 
     * @param array|null $arrData
     */
    public function __construct($arrData = NULL) {
        if(is_array($arrData)) {
            $this->_arrData = $arrData;
        } else {
            $this->_arrData = $this->getEmpryarray();
        }
    }

    /**
     * Get full data array
     * 
     * @return array
     */
    public function getArrdata() {
        return $this->_arrData;
    }

    /**
     * Magic function is called when a non existing function is executed on an
     * AR instance. If so we check if it was get or set and if the attribute
     * exsists in our data array. If so we perform the get or set action (with
     * the exception of set not working for PK attribute).
     * 
     * @param string $strName
     * @param array $arrArguments
     * @return boolean|mixed
     */
    public function __call($strName, $arrArguments) {
        switch(substr($strName, 0, 3)) {
            case 'get':
                $strAttrname = str_replace('get', '', $strName);

                if(array_key_exists($strAttrname, $this->_arrData)) {
                    return $this->_arrData[$strAttrname];
                }

                break;

            case 'set':
                $strAttrname = str_replace('set', '', $strName);

                if($strAttrname == self::TABLE_PK) {
                    return false;
                }

                if(array_key_exists($strAttrname, $this->_arrData)) {
                    $this->_arrData[$strAttrname]   = $arrArguments[0];
                    $this->_blnAltered              = true;

                    return true;
                }

                return false;

                break;

            default:

                return false;

                break;
        }
    }

    /**
     * Update full AR into DB
     * 
     * @return boolean
     */
    public function doFullupdate() {
        $strClass   = get_called_class();
        $strTable   = $strClass::TABLE_CLASS;

        if($strTable::doUpdateallbypk($this)) {
            $this->_blnUpdated = true;
            $this->_blnAltered = false;
        } else {
            $this->_blnUpdated = false;
            $this->_blnAltered = true;
        }

        return $this->_blnUpdated;
    }

    /**
     * Insert new row with AR values into DB
     * 
     * @return boolean
     */
    public function doInsert() {
        $strClass   = get_called_class();
        $strTable   = $strClass::TABLE_CLASS;

        if($strTable::doInsert($this)) {
            $this->_blnUpdated = true;
            $this->_blnAltered = false;
        } else {
            $this->_blnUpdated = false;
            $this->_blnAltered = true;
        }

        return $this->_blnUpdated;
    }

    /**
     * define abstract methode getEmptyarray() to be implemented by extending class
     */
    abstract protected function getEmpryarray();
}