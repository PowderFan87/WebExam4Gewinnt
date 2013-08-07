<?php

/**
 * Description of Command_XGame
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_XGame extends Core_Base_Command implements IXHttpRequest, IRestricted
{
    public static function getRestriction() {
        return 'App_Web_Security::isAuthenticated';
    }

    public function getFallback() {
        $this->_objResponse->error = 'RESTRICTED';
    }

    public function postFallback() {
        $this->_objResponse->error = 'RESTRICTED';
    }

    public function postGetlist() {
        $this->_objResponse->setStrresponsetype(TPL_MODE_HTML_ACTION_ONLY);

        $this->_objResponse->tplContent = 'XGame_POST_Getlist';

        $this->_objResponse->arrGames = tblGame::getAlljoinable();
    }

    public function postRefreshgame() {

    }

    public function postMove() {

    }
    
    public function postData() {
        $this->_objResponse->setStrresponsetype(TPL_MODE_JSON);

        $objGame = tblGame::getBypk($this->_objRequest->uid);

        if(!($objGame instanceof App_Data_Game) || $objGame->notAuthenticated()) {
            header("HTTP/1.0 401 Unauthorized");
        } else {
            $arrJSON = array();
            
            $arrJSON['turn'] = $objGame->getlngTurn();
            $arrJSON['grid'] = $objGame->getArrgamegrid();
            $arrJSON['last'] = $objGame->getstrLastchange();
            
            $this->_objResponse->txtJSON = json_encode($arrJSON);
        }
    }

    protected function _doInit() {
        ;
    }
}