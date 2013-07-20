<?php

/**
 * Description of tblGame
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class tblGame extends App_Data_Table_Base
{
    const TABLE_NAME    = 'tblgame';
    const TABLE_PK      = 'UID';
    const TABLE_ARCLASS = 'Game';

    public static function getAlljoinable($blnObjects = false) {
        $strARClass = 'App_Data_' . self::TABLE_ARCLASS;
        $strQuery   = '
SELECT
    *
FROM
    ' . self::TABLE_NAME . '
WHERE
    enmStatus = "open"
AND
    lngPlayer1 != ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
';

        try {
            $arrData        = App_Factory_Resource::getResource()->read($strQuery, true);

            if($blnObjects) {
                $arrResponse    = array();

                foreach($arrData as $arrRow) {
                    $arrResponse[] = new $strARClass($arrRow);
                }

                return $arrResponse;
            } else {
                return $arrData;
            }
        } catch (Resource_Exception $e) {
            return NULL;
        }
    }
}