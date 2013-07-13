<?php

/**
 * Description of Command_Registrieren
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Registrieren extends Core_Base_Command implements IHttpRequest
{

    public function getMain() {

    }

    public function postMain() {

    }

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Registrieren';
    }
}