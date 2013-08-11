<?php

/**
 * Spieler command and action collection
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Spieler extends Core_Base_Command implements IHttpRequest, IRestricted
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
     * 
     */
    public function getFallback() {
        $this->_objResponse->tplContent = 'Game_GET_Fallback';

        $this->_objResponse->strFoo = 'Sorry, Sie sind nicht eingeloggt';
        $this->_objResponse->strTitle .= ' - Not logged in';
    }
    
    /**
     * Display player information
     * 
     */
    public function getMain() {
        $this->_objResponse->tplContent     = 'Spieler_GET_Main';

        $this->_objResponse->strTitle       .= ' - Spieler';
        
        $this->_objResponse->strWellcome    .= 'Spielerinformation';
        
        $objUser    = App_Factory_Security::getSecurity()->getObjuser();
        $objProfile = $objUser->getUserprofile();
        
        $this->_objResponse->strName        = $objUser->getstrUsername();
        $this->_objResponse->lngGamecount   = $objProfile->getlngPlayedgames();
        $this->_objResponse->lngPoints      = $objProfile->getlngPoints();
        $this->_objResponse->lngAvgpoints   = round($objProfile->getlngPoints() / $objProfile->getlngPlayedgames());
    }
    
    /**
     * Display highscore
     * 
     */
    public function getHighscore() {
        $this->_objResponse->tplContent = 'Spieler_GET_Highscore';

        $this->_objResponse->strTitle   .= ' - List';
        $this->_objResponse->arrPlayer  = tblUser::getHighscore();
    }

    /**
     * Basic init methode
     * 
     */
    protected function _doInit() {
        $this->_objResponse->strTitle = 'Main';
    }
}