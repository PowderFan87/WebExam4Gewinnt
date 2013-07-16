<?php

/**
 * Description of Command_Registrieren
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Registrieren extends Core_Base_Command implements IHttpRequest
{

    public function getMain() {
        $this->_objResponse->tplContent = "Registrieren_GET_Main";
        
        
    }

    public function postMain() {
        $this->_objResponse->tplContent = "Registrieren_POST_Main";
        
        
    }

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Registrieren';
    }
}