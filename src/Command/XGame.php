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

    public function postUpdate() {
        $this->_objResponse->setStrresponsetype(TPL_MODE_JSON);

        $objGame = tblGame::getBypk($this->_objRequest->uid);
        
        if(!($objGame instanceof App_Data_Game) || $objGame->notAuthenticated()) {
            header("HTTP/1.0 401 Unauthorized");
        } else {
            $blnUpdate = false;
            $arrJSON = array();
            
            // logik
            $lngPlayertype = $objGame->getPlayertype();
            
            if(($lngPlayertype === 1 && ($objGame->getlngTurn() % 2 !== 0)) || ($lngPlayertype === 2 && ($objGame->getlngTurn() % 2 === 0))) {
                $blnUpdate = true;
            }
            
            // message
            if(!$blnUpdate) {
                $arrJSON['msg'] = 'none';
            } else {
                $arrJSON['msg'] = 'update';
                
                // data
                $arrJSON['data']['turn'] = $objGame->getlngTurn();
                $arrJSON['data']['grid'] = $objGame->getArrgamegrid();
                $arrJSON['data']['last'] = $objGame->getstrLastchange();
                $arrJSON['data']['play'] = $objGame->getPlayertype();
            }
            
            $this->_objResponse->txtJSON = json_encode($arrJSON);
        }
    }

    public function postMove() {
        $this->_objResponse->setStrresponsetype(TPL_MODE_JSON);

        $objGame = tblGame::getBypk($this->_objRequest->uid);
        
        if(!($objGame instanceof App_Data_Game) || $objGame->notAuthenticated()) {
            header("HTTP/1.0 401 Unauthorized");
        } else {
            $blnError   = false;
            $arrMove    = $this->_objRequest->move;
            $arrJSON    = array();
            
            // logik
            $arrGrid = $objGame->getArrgamegrid();
            
            if($arrGrid[$arrMove[0]][$arrMove[1]] === 0) {
                $arrGrid[$arrMove[0]][$arrMove[1]] = $objGame->getPlayertype();
                
                $lngTurn        = $objGame->getlngTurn() + 1;
                $lngPointsleft  = $objGame->getlngPointsleft() - 1;
                
                $objGame->setArrgamegrid($arrGrid);
                $objGame->setlngTurn($lngTurn);
                $objGame->setlngPointsleft($lngPointsleft);
                
                $arrJSON['fieldid'] = '#f'.join('',$arrMove);
                
                $objGame->setstrLastchange($arrJSON['fieldid']);
                
                if(!$objGame->doFullupdate()) {
                    $blnError = true;
                }
            } else {
                $blnError = true;
            }
            
            // message
            if($blnError) {
                $arrJSON['msg'] = 'error';
            } else {
                // check winner
            }
            
            // data
            $arrJSON['data']['turn'] = $objGame->getlngTurn();
            $arrJSON['data']['grid'] = $objGame->getArrgamegrid();
            $arrJSON['data']['last'] = $objGame->getstrLastchange();
            $arrJSON['data']['play'] = $objGame->getPlayertype();
            
            $this->_objResponse->txtJSON = json_encode($arrJSON);
        }
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
            $arrJSON['play'] = $objGame->getPlayertype();
            
            $this->_objResponse->txtJSON = json_encode($arrJSON);
        }
    }

    protected function _doInit() {
        ;
    }
}