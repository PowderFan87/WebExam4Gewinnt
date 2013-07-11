<?php

/**
 * Description of Core_Web
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Core_Web
{
    private $_arrCommandconf;
    private $_objCommand;
    private $_objRequest;

    public function __construct() {
        $this->_doInit();
    }

    public function run() {
        $strAction = $this->_arrCommandconf["action"];

        $this->_objCommand->$strAction();
    }

    public function __toString() {
        $objTemplate = new Core_Web_Template(App_Factory_Response::getResponse());

        return (string)$objTemplate;
    }

    private function _doInit() {
        try {
            $this->_objRequest      = App_Factory_Request::getRequest();
            $this->_arrCommandconf  = App_Config_Command::url2command();
            $this->_objCommand      = App_Factory_Command::getCommand($this->_arrCommandconf["command"]);
        } catch (App_Config_Exception $e) {
            $this->_arrCommandconf  = App_Config_Command::get404command();
            $this->_objCommand      = App_Factory_Command::getCommand($this->_arrCommandconf["command"]);
        } catch (App_Factory_Exception $e) {
            $this->_arrCommandconf  = App_Config_Command::get404command();
            $this->_objCommand      = App_Factory_Command::getCommand($this->_arrCommandconf["command"]);
        }
    }
}