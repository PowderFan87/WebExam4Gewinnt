<?php

/**
 * Description of tblUser
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class tblUser extends App_Data_Table_Base
{
    const TABLE_NAME    = "tbluser";
    const TABLE_PK      = "UID";

    public static function getUserbypk($lngPk) {
        try {
            $objResource = App_Factory_Resource::getResource();

            $strQuery = '
SELECT
    *
FROM
    ' . self::TABLE_NAME . '
WHERE
    ' . self::TABLE_PK . ' = ' . $lngPk . '
';

            $arrData = $objResource->readSingle($strQuery);

            return new App_Data_User($arrData);
        } catch(App_Factory_Exception $e) {
            var_dump($e);
        } catch(Resource_Exception $e) {
            var_dump($e);
        }
    }
}