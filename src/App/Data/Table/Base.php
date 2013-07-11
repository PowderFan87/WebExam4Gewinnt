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
    const TABLE_ARCLASS = "undefined";

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

        try {
            return App_Factory_Resource::getResource()->update($objEntity->getArrdata(), $strTable, $arrConditions);
        } catch(Resource_Exception $e) {
            var_dump($e);
        }

        return false;
    }

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