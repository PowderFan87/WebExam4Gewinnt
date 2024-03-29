<?php

/**
 * Game command with corresponding action collection
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Game extends Core_Base_Command implements IHttpRequest, IRestricted
{
    /**
     * Get string of command restriction check
     * 
     * @return string
     */
    public static function getRestriction() {
        return 'App_Web_Security::isAuthenticated';
    }

    /**
     * Fallback action if restriction is not met
     * 
     */
    public function getFallback() {
        $this->_objResponse->tplContent = 'Game_GET_Fallback';

        $this->_objResponse->strFoo = 'You are not logged in';
        $this->_objResponse->strTitle .= ' - Not logged in';
    }

    /**
     * List action to display list of jounable games
     * 
     */
    public function getList() {
        $this->_objResponse->tplContent = 'Game_GET_List';

        $this->_objResponse->strTitle .= ' - List';
        $this->_objResponse->arrGames = tblGame::getAlljoinable();
    }

    /**
     * New action to create a new game (get request)
     * 
     */
    public function getNeu() {
        $this->_objResponse->tplContent = 'Game_GET_Neu';

        $this->_objResponse->strTitle .= ' - Neues Spiel';
    }

    /**
     * Spielen action to enter currently running game
     * 
     */
    public function getSpielen() {
        $this->_objResponse->tplContent = 'Game_GET_Spielen';

        $this->_objResponse->strTitle .= ' - Spielen';

        $objGame = tblGame::getBypk($this->_objRequest->uid);

        if(!($objGame instanceof App_Data_Game) || $objGame->notAuthenticated() || in_array($objGame->getenmStatus(), array('ended', 'error', 'closed'))) {
            $this->_objResponse->tplContent = 'Game_GET_Error';
            $this->_objResponse->strMessage = 'Leider können wir das Spiel nicht finden';
        } else {
            $this->_objResponse->lngGameid      = $objGame->getUID();
            $this->_objResponse->strGamename    = $objGame->getstrName();
            $this->_objResponse->lngThemeid     = $objGame->getlngThemeid();

            $arrPlayerinfos = tblGame::getContestors($objGame);
            
            $lngPlayertype = $objGame->getPlayertype();
            
            if($lngPlayertype === 1 && ($objGame->getlngTurn() % 2 !== 0)) {
                $this->_objResponse->strHasturn = 'hasturn';
                $arrPlayerinfos[0]['strTurn1']  = ' hasturn'; 
            } else if($lngPlayertype === 2 && ($objGame->getlngTurn() % 2 === 0)) {
                $this->_objResponse->strHasturn = 'hasturn';
                $arrPlayerinfos[0]['strTurn2']  = ' hasturn';
            } else if($lngPlayertype === 1) {
                $arrPlayerinfos[0]['strTurn2']  = ' hasturn';
            } else if($lngPlayertype === 2) {
                $arrPlayerinfos[0]['strTurn1']  = ' hasturn';
            }
            
            $this->_objResponse->arrPlayerinfos = $arrPlayerinfos;
        }
    }
    
    /**
     * Offene action to display all curently open games of user
     * 
     */
    public function getOffene() {
        $this->_objResponse->tplContent = 'Game_GET_Offene';

        $this->_objResponse->strTitle .= ' - Eigene offene Spiele';
        $this->_objResponse->arrGames = tblGame::getAllownopen();
    }
    
    /**
     * Laufende action to display all currently runing games fo user
     * 
     */
    public function getLaufende() {
        $this->_objResponse->tplContent = 'Game_GET_Laufende';

        $this->_objResponse->strTitle .= ' - Eigene laufende Spiele';
        $this->_objResponse->arrGames = tblGame::getAllownrunning();
    }

    /**
     * Beitreten action do enter an open game (post request)
     * 
     */
    public function postBeitreten() {
        $objGame    = tblGame::getBypk($this->_objRequest->uid);
        $objUser    = App_Factory_Security::getSecurity()->getObjuser();

        if(!($objGame instanceof App_Data_Game) || $objGame->getenmStatus() !== 'open' || $objUser->getUID() === $objGame->getlngPlayer1()) {
            $this->_objResponse->tplContent = 'Game_POST_Error';
            $this->_objResponse->strTitle   .= ' - Fehler';
            $this->_objResponse->strMessage = 'Leider können Sie diesem Spiel nicht beitreten';
        } else {
            $objGame->setlngPlayer2($objUser->getUID());
            $objGame->setenmStatus('started');
            $objGame->setlngTurn(1);

            if(!$objGame->doFullupdate()) {
                $this->_objResponse->tplContent = 'Game_POST_Error';
                $this->_objResponse->strTitle   .= ' - Fehler';
                $this->_objResponse->strMessage = 'Fehler beim Beitreten. Bitte versuchen Sie er erneut.';
            } else {
                header('Location: ' . CFG_WEB_ROOT . '/Game/Spielen?uid=' . $objGame->getUID());
            }
        }
    }
    
    /**
     * Schliessen action to close a former open game of user
     * 
     */
    public function postSchliessen() {
        $objGame    = tblGame::getBypk($this->_objRequest->uid);
        
        if(!($objGame instanceof App_Data_Game) || $objGame->notAuthenticated()) {
            $this->_objResponse->tplContent = 'Game_POST_Error';
            $this->_objResponse->strTitle   .= ' - Fehler';
            $this->_objResponse->strMessage = 'Leider können Sie das Spiel nicht schließen';
        } else {
            $objGame->setenmStatus('closed');
            
            if(!$objGame->doFullupdate()) {
                $this->_objResponse->tplContent = 'Game_POST_Error';
                $this->_objResponse->strTitle   .= ' - Fehler';
                $this->_objResponse->strMessage = 'Fehler beim Schließen. Bitte versuchen Sie er erneut.';
            } else {
                $this->_objResponse->tplContent = 'Game_POST_Schliessen';
                $this->_objResponse->strTitle   .= ' - Geschlossen';
                $this->_objResponse->strMessage = 'Spiel wurde geschlossen.';
            }
        }
    }

    /**
     * New action to create new game in DB (post request)
     * 
     */
    public function postNeu() {
        $this->_objResponse->tplContent = 'Game_POST_Neu';

        $arrErrors  = $this->_doValidate();
        $objUser    = App_Factory_Security::getSecurity()->getObjuser();

        if(!empty($arrErrors) || $objUser === NULL) {
            $this->_objResponse->tplContent = 'Game_GET_Neu';
            $this->_objResponse->strTitle   .= ' - Neues Spiel';

            foreach($arrErrors as $strField => $blnError) {
                if(!$blnError) {
                    continue;
                }

                $strErrorvariable = 'ERROR_' . $strField;

                $this->_objResponse->$strErrorvariable = 'error';
            }

            $this->_objResponse->strName       = $this->_objRequest->strName;
        } else {
            $objGame = new App_Data_Game();

            $objGame->setstrName($this->_objRequest->strName);
            $objGame->setlngThemeid($this->_objRequest->lngThemeid);
            $objGame->setlngPlayer1($objUser->getUID());

            if(!$objGame->doInsert()) {
                $this->_objResponse->strMessage = 'Fehler beim speichern des Spiels. Bitte erneut versuchen.';
            } else {
                $this->_objResponse->strMessage = 'Spiel "' . $this->_objRequest->strName . '" wurde erfolgreich erstellt.';
            }

            $this->_objResponse->strTitle   .= ' - Neues Spiel erstellt';
        }
    }

    /**
     * Basic init methode
     * 
     */
    protected function _doInit() {
        $this->_objResponse->strTitle = 'Game';
    }

    /**
     * Validate request data for new game
     * 
     * @return boolean
     */
    private function _doValidate() {
        $arrErrors = array();

        if(!App_Tools_Validator::hasStringlength($this->_objRequest->strName, 20, 5)) {
            $arrErrors['strName'] = true;
        }

        if(!($this->_objRequest->lngThemeid < 3 && $this->_objRequest->lngThemeid >= 0)) {
            $arrErrors['lngThemeid'] = true;
        }

        return $arrErrors;
    }
}