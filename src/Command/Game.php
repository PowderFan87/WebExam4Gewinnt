<?php

/**
 * Description of Game
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Game extends Core_Base_Command implements IHttpRequest, IRestricted
{
    public static function getRestriction() {
        return 'App_Web_Security::isAuthenticated';
    }

    public function getFallback() {
        $this->_objResponse->tplContent = 'Game_GET_Fallback';

        $this->_objResponse->strFoo = 'HOHOHO';
        $this->_objResponse->strTitle .= ' - Not logged in';
    }

    public function getList() {
        $this->_objResponse->tplContent = 'Game_GET_List';

        $this->_objResponse->strTitle .= ' - List';
        $this->_objResponse->arrGames = tblGame::getAllopen();
    }

    public function getNeu() {
        $this->_objResponse->tplContent = 'Game_GET_Neu';

        $this->_objResponse->strTitle .= ' - Neues Spiel';
    }

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

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Game';
    }

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