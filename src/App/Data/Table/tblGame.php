<?php

/**
 * Access class for tblGame table
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class tblGame extends App_Data_Table_Base
{
    const TABLE_NAME    = 'tblgame';
    const TABLE_PK      = 'UID';
    const TABLE_ARCLASS = 'Game';

    /**
     * Get all joinable games for current logged in user.
     * If parameter blnObject is true an array of AR-Class objects is returned
     * 
     * @param boolean $blnObjects
     * @return null|App_Data_Base|array
     */
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
    
    /**
     * Get all open games of current logged in user.
     * If parameter blnObject is true an array of AR-Class objects is returned
     * 
     * @param boolean $blnObjects
     * @return null|App_Data_Base|array
     */
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
    
    /**
     * Get all currently running games of logged in user.
     * If parameter blnObject is true an array of AR-Class objects is returned
     * 
     * @param boolean $blnObjects
     * @return null|App_Data_Base|array
     */
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
    
    /**
     * Get count of running games and games where the current user has a active
     * turn.
     * 
     * @return int
     */
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
    
    /**
     * Get contestor data of a game by AR-Class object value.
     * 
     * @param App_Data_Game $objGame
     * @return array
     */
    public static function getContestors(App_Data_Game $objGame) {
        $strQuery   = '
SELECT
    p1.strUsername AS strName1,
    pf1.lngPoints AS lngPoints1,
    p2.strUsername AS strName2,
    pf2.lngPoints AS lngPoints2
FROM
    ' . self::TABLE_NAME . ' as g
LEFT JOIN
    tbluser AS p1 ON (g.lngPlayer1 = p1.UID)
LEFT JOIN
    tbluser AS p2 ON (g.lngPlayer2 = p2.UID)
LEFT JOIN
    tbluserprofile AS pf1 ON (p1.UID = pf1.lngUser)
LEFT JOIN
    tbluserprofile AS pf2 ON (p2.UID = pf2.lngUser)
WHERE
    g.UID = ' . $objGame->getUID() . '
';
        
        try {
            $arrData = App_Factory_Resource::getResource()->readSingle($strQuery);
            
            return array($arrData);
        } catch (Resource_Exception $e) {
            return array();
        }
    }
}