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
    
    public static function getAllownopen($blnObjects = false) {
        $strARClass = 'App_Data_' . self::TABLE_ARCLASS;
        $strQuery   = '
SELECT
    *
FROM
    ' . self::TABLE_NAME . '
WHERE
    enmStatus = "open"
AND
    lngPlayer1 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
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
    
    public static function getAllownrunning($blnObjects = false) {
        $strARClass = 'App_Data_' . self::TABLE_ARCLASS;
        $strQuery   = '
SELECT
    *
FROM
    ' . self::TABLE_NAME . '
WHERE
    enmStatus = "started"
AND
    (
        lngPlayer1 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
        OR
        lngPlayer2 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
    )
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
    
    public static function Getrunninggamecount() {
        $strQueryopen   = '
SELECT
    COUNT(*) as lngGamesopen
FROM
    ' . self::TABLE_NAME . '
WHERE
    enmStatus = "started"
AND
    (
        lngPlayer1 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
        OR
        lngPlayer2 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
    )
';
        
        $strQueryturn   = '
SELECT
    COUNT(*) as lngGamesturn
FROM
    ' . self::TABLE_NAME . '
WHERE
    enmStatus = "started"
AND
    (
        (
            lngPlayer1 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
            AND
            (lngTurn % 2) != 0
        )
        OR
        (
            lngPlayer2 = ' . App_Factory_Security::getSecurity()->getObjuser()->getUID() . '
            AND
            (lngTurn % 2) = 0
        )
    )
';
        
        $arrCounts = array(
            'lngGamesopen' => 0,
            'lngGamesturn' => 0
        );
        
        try {
            $arrData = App_Factory_Resource::getResource()->readSingle($strQueryopen);
            
            $arrCounts['lngGamesopen'] = $arrData['lngGamesopen'];
            
            $arrData = App_Factory_Resource::getResource()->readSingle($strQueryturn);
            
            $arrCounts['lngGamesturn'] = $arrData['lngGamesturn'];
            
            return $arrCounts;
        } catch (Resource_Exception $e) {
            return $arrCounts;
        }
    }
}