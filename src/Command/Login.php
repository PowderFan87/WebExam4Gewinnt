<?php

/**
 * Description of Command_Login
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Login extends Core_Base_Command implements IHttpRequest
{

    public function getMain() {

    }

    public function postMain() {

    }

    public function postLogout() {

    }

    protected function _doInit() {
        $this->_objResponse->strTitle = "Login";
    }
}