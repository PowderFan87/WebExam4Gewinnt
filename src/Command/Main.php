<?php

/**
 * Main command and action collection
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Main extends Core_Base_Command implements IHttpRequest
{
    /**
     * Main action for application
     * 
     */
    public function getMain() {
        $this->_objResponse->tplContent     = 'Main_GET_Main';

        $this->_objResponse->strTitle       .= ' - Home';
        $this->_objResponse->strWellcome    = 'Willkommen beim 4-Gewinnt';
    }

    /**
     * 404 fallback action for application
     * 
     */
    public function get404() {
        $this->_objResponse->tplContent    = 'Main_GET_404';

        $this->_objResponse->strTitle       .= ' - 404';
        $this->_objResponse->strWellcome    = 'UPS! Das gibt es nicht...';
    }

    /**
     * Basic init methode
     * 
     */
    protected function _doInit() {
        $this->_objResponse->strTitle = 'Main';
    }
}