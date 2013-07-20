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

    protected function _doInit() {
        ;
    }
}