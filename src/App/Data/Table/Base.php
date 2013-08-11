<?php

/**
 * Basic table data class with all methodes that all tables have in commen
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class App_Data_Table_Base
{
    const TABLE_NAME    = 'undefined';
    const TABLE_PK      = 'undefined';
    const TABLE_ARCLASS = 'undefined';

    /**
     * Update AR-Object values into Database
     * 
     * @param App_Data_Base $objEntity
     * @return boolean true or false depending on update result
     */
    public static function doUpdateallbypk($objEntity) {
        $strClass   = get_called_class();
        $strPkget   = 'get' . $strClass::TABLE_PK;

        $arrConditions[$strClass::TABLE_PK] = array(
            'operator'  => '=',
            'value'     => $objEntity->$strPkget()
        );

        try {
            return App_Factory_Resource::getResource()->update($objEntity->getArrdata(), $strClass::TABLE_NAME, $arrConditions);
        } catch(Resource_Exception $e) {
            var_dump($e);
        }

        return false;
    }

    /**
     * Insert new row into DB based on AR-Class object values
     * 
     * @param App_Data_Base $objEntity
     * @return boolean
     */
    public static function doInsert($objEntity) {
        $strClass   = get_called_class();

        try {
            return App_Factory_Resource::getResource()->insert($objEntity->getArrdata(), $strClass::TABLE_NAME);
        } catch(Resource_Exception $e) {
            var_dump($e);
        }

        return false;
    }

    /**
     * Get entry from table by PK and return false if none is found or an instance
     * of AR-Class regarding the called table.
     * 
     * @param integer $lngPk
     * @return App_Data_Base|boolean
     */
    public static function getBypk($lngPk) {
        try {
            $strClass   = get_called_class();
            $strARClass = 'App_Data_' . $strClass::TABLE_ARCLASS;

            $strQuery = '
SELECT
    *
FROM
    ' . $strClass::TABLE_NAME . '
WHERE
    ' . $strClass::TABLE_PK . ' = ' . $lngPk . '
';

            $arrData = App_Factory_Resource::getResource()->readSingle($strQuery);

            return new $strARClass($arrData);
        } catch(App_Factory_Exception $e) {
            var_dump($e);
        } catch(Resource_Exception $e) {
            var_dump($e);
        }

        return false;
    }
}