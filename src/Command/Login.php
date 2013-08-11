<?php

/**
 * Login command and action collection
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Login extends Core_Base_Command implements IHttpRequest
{
    /**
     * Display login form
     * 
     */
    public function getMain() {
        $this->_objResponse->tplContent     = 'Login_GET_Main';

        $this->_objResponse->strWellcome    = '';
    }

    /**
     * Try to login user. If there are currently running games the user is
     * redirected to the list.
     * 
     */
    public function postMain() {
        if(!App_Web_Security::tryLogin($this->_objRequest->username, $this->_objRequest->password)) {
            $this->_objResponse->tplContent = 'Login_POST_Main';

            $this->_objResponse->strResult  = 'Fehlgeschlagen!';
        } else {
            $this->_objResponse->tplContent = 'Login_POST_Main';

            $this->_objResponse->strResult  = 'Erfolgreich';
            
            if(count(tblGame::getAllownrunning()) > 0) {
                header("Location: " . CFG_WEB_ROOT . "/Game/Laufende");
            }
        }
    }

    /**
     * Logout user
     * 
     */
    public function getLogout() {
        $this->_objResponse->tplContent     = 'Login_GET_Logout';

        App_Factory_Security::getSecurity()->doDestroysession();
    }

    /**
     * basic init methode
     * 
     */
    protected function _doInit() {
        $this->_objResponse->strTitle = 'Login';
    }
}