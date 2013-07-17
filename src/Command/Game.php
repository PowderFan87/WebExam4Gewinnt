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
    }

    public function getNewgame() {

    }

    public function postNewgame() {

    }

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Game';
    }
}