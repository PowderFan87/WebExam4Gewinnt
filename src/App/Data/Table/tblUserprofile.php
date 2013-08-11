<?php

/**
 * Access class for tblUserprofile table
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class tblUserprofile extends App_Data_Table_Base
{
    const TABLE_NAME    = 'tbluserprofile';
    const TABLE_PK      = 'UID';
    const TABLE_ARCLASS = 'Userprofile';
    
    /**
     * 
     * @param int $lngUser
     * @return boolean|\strARClass
     */
    public static function getUserprofilebylnguser($lngUser) {
        try {
            $strARClass = 'App_Data_' . self::TABLE_ARCLASS;

            $strQuery = '
SELECT
    *
FROM
    ' . self::TABLE_NAME . '
WHERE
    lngUser = ' . $lngUser . '
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
}