<?php

/**
 * Description of Command_Main
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Main extends Core_Base_Command implements IHttpRequest
{

    public function getMain() {
        $this->_objResponse->tplContent     = "Main_GET_Main";

        $this->_objResponse->strTitle       .= " - Home";
        $this->_objResponse->strWellcome    = "Willkommen beim 4-Gewinnt";
    }

    public function get404() {
        $this->_objResponse->tplContent    = "Main_GET_404";

        $this->_objResponse->strTitle       .= " - 404";
        $this->_objResponse->strWellcome    = "UPS! Das gibt es nicht...";
    }

    protected function _doInit() {
        $this->_objResponse->strTitle = "Main";
    }
}