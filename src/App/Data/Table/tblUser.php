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
            var_dump($e);
        }

        return false;
    }
}