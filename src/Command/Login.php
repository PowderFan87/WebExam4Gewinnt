<?php

/**
 * Description of Command_Login
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Login extends Core_Base_Command implements IHttpRequest
{

    public function getMain() {
        $this->_objResponse->tplContent     = 'Login_GET_Main';

        $this->_objResponse->strWellcome    = '';
    }

    public function postMain() {
        App_Web_Security::tryLogin($this->_objRequest->username, $this->_objRequest->password);
        
        $this->_objResponse->tplContent     = 'Login_POST_Main';

        $this->_objResponse->strWellcome    = '';
    }

    public function getLogout() {
        App_Factory_Security::getSecurity()->doDestroysession();
    }

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Login';
    }
}