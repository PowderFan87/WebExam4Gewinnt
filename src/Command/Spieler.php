<?php

/**
 * Description of Command_Spieler
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Spieler extends Core_Base_Command implements IHttpRequest, IRestricted
{
    public static function getRestriction() {
        return 'App_Web_Security::isAuthenticated';
    }

    public function getFallback() {
        $this->_objResponse->tplContent = 'Game_GET_Fallback';

        $this->_objResponse->strFoo = 'HOHOHO';
        $this->_objResponse->strTitle .= ' - Not logged in';
    }
    
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

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Main';
    }
}