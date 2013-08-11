<?php

/**
 * XGame command with collection of all ajax related game actions
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_XGame extends Core_Base_Command implements IXHttpRequest, IRestricted
{
    /**
     * Get restriction for command
     * 
     * @return string
     */
    public static function getRestriction() {
        return 'App_Web_Security::isAuthenticated';
    }

    /**
     * Fallback action if restriction is not met
     */
    public function getFallback() {
        $this->_objResponse->error = 'RESTRICTED';
    }

    /**
     * Alis for getFallback
     * 
     */
    public function postFallback() {
        $this->getFallback();
    }

    /**
     * Get list of joinable games
     * 
     */
    public function postGetlist() {
        $this->_objResponse->setStrresponsetype(TPL_MODE_HTML_ACTION_ONLY);

        $this->_objResponse->tplContent = 'XGame_POST_Getlist';

        $this->_objResponse->arrGames = tblGame::getAlljoinable();
    }

    /**
     * Update game information
     * 
     */
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
            } else if($objGame->getlngTurn() > 6 && App_Web_Game::doWinnercheck($objGame, true)) {
                $arrJSON['msg']             = 'winner';
                $arrJSON['data']['points']  = $objGame->getlngPointsleft();

                $objUserprofile = tblUserprofile::getUserprofilebylnguser(App_Factory_Security::getSecurity()->getObjuser()->getUID());
                $lngPlayedgames = $objUserprofile->getlngPlayedgames() + 1;

                $objUserprofile->setlngPlayedgames($lngPlayedgames);

                $objUserprofile->doFullupdate();
                
                // data
                $arrJSON['data']['turn'] = $objGame->getlngTurn();
                $arrJSON['data']['grid'] = $objGame->getArrgamegrid();
                $arrJSON['data']['last'] = $objGame->getstrLastchange();
                $arrJSON['data']['play'] = $objGame->getPlayertype();
            }  else if($objGame->getlngPointsleft() <= 0) {
                $arrJSON['msg'] = 'draw';

                // data
                $arrJSON['data']['turn'] = $objGame->getlngTurn();
                $arrJSON['data']['grid'] = $objGame->getArrgamegrid();
                $arrJSON['data']['last'] = $objGame->getstrLastchange();
                $arrJSON['data']['play'] = $objGame->getPlayertype();
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

    /**
     * Set new move
     * 
     */
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
              
                if($objGame->getlngTurn() > 6 && App_Web_Game::doWinnercheck($objGame)) {
                    $arrJSON['msg']             = 'winner';
                    $arrJSON['data']['points']  = $objGame->getlngPointsleft();
                    
                    $objUserprofile = tblUserprofile::getUserprofilebylnguser(App_Factory_Security::getSecurity()->getObjuser()->getUID());
                    $lngNewpoints   = $objUserprofile->getlngPoints() + $objGame->getlngPointsleft();
                    $lngPlayedgames = $objUserprofile->getlngPlayedgames() + 1;
                    
                    $objUserprofile->setlngPoints($lngNewpoints);
                    $objUserprofile->setlngPlayedgames($lngPlayedgames);
                    
                    $objUserprofile->doFullupdate();
                    
                    $objGame->setlngWinner(App_Factory_Security::getSecurity()->getObjuser()->getUID());
                    $objGame->setenmStatus('ended');
                    
                    $objGame->doFullupdate();
                } else if($lngPointsleft <= 0) {
                    $arrJSON['msg'] = 'draw';
                    
                    $objGame->setenmStatus('ended');
                    
                    $objGame->doFullupdate();
                }
            }
            
            // data
            $arrJSON['data']['turn'] = $objGame->getlngTurn();
            $arrJSON['data']['grid'] = $objGame->getArrgamegrid();
            $arrJSON['data']['last'] = $objGame->getstrLastchange();
            $arrJSON['data']['play'] = $objGame->getPlayertype();
            
            $this->_objResponse->txtJSON = json_encode($arrJSON);
        }
    }
    
    /**
     * Get basic game data
     * 
     */
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

    /**
     * Basic init methode
     */
    protected function _doInit() {
        ;
    }
}