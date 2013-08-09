<?php

/**
 * Description of App_Data_Base
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

    public function __construct($arrData = NULL) {
        if(is_array($arrData)) {
            $this->_arrData = $arrData;
        } else {
            $this->_arrData = $this->getEmpryarray();
        }
    }

    public function getArrdata() {
        return $this->_arrData;
    }

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

    abstract protected function getEmpryarray();
}