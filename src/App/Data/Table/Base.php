<?php

/**
 * Description of App_Data_Table_Base
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class App_Data_Table_Base
{
    const TABLE_NAME    = "undefined";
    const TABLE_PK      = "undefined";

    /**
     *
     * @param App_Data_Base $objEntity
     * @return boolean true or false depending on update result
     */
    public static function doUpdateallbypk($objEntity) {
        $strClass   = get_called_class();
        $strTable   = $strClass::TABLE_NAME;
        $strPkget   = 'get' . $strClass::TABLE_PK;

        $arrConditions[$strClass::TABLE_PK] = array(
            "operator"  => "=",
            "value"     => $objEntity->$strPkget()
        );

        return App_Factory_Resource::getResource()->update($objEntity->getArrdata(), $strTable, $arrConditions);
    }
}