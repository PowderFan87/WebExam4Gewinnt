<?php

/**
 * Description of tblUser
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class tblUser extends App_Data_Table_Base
{
    const TABLE_NAME    = 'tbluser';
    const TABLE_PK      = 'UID';
    const TABLE_ARCLASS = 'User';

    public static function getUserbystrusername($strUsername) {
        try {
            $strARClass = 'App_Data_' . self::TABLE_ARCLASS;

            $strQuery = '
SELECT
    *
FROM
    ' . self::TABLE_NAME . '
WHERE
    strUsername = \'' . $strUsername . '\'
';

            $arrData = App_Factory_Resource::getResource()->readSingle($strQuery);

            return new $strARClass($arrData);
        } catch(App_Factory_Exception $e) {
            var_dump($e);
        } catch(Resource_Exception $e) {
            return false;
        }

        return false;
    }
    
    public static function getCountallonline() {
        $strQuery = '
SELECT
    COUNT(*) as lngOnline
FROM
    ' . self::TABLE_NAME . '
WHERE
    blnActivated = 1
AND
    blnLoggedin = 1
AND
    dtmLastaction > \'' . date('Y-m-d H:i:s', strtotime("-30 Minutes")) . '\'
';

        try {
            $arrData = App_Factory_Resource::getResource()->readSingle($strQuery);

            return $arrData;
        } catch(App_Factory_Exception $e) {
            var_dump($e);
        } catch(Resource_Exception $e) {
            return false;
        }

        return false;
    }
}