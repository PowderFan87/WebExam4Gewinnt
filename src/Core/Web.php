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
    private $_objPrehooks;
    private $_objPosthooks;

    public function __construct() {
        session_start();

        $this->_doInit();
    }

    public function __destruct() {
        session_write_close();
    }

    public function run() {
        foreach($this->_objPrehooks as $objPrehook) {
            try {
                $objPrehook->runPre();
            } catch(App_Hook_Security_Exception $e) {
                if($e->getMessage() === 'Invalid IP for Session' || $e->getMessage() === 'Timout because inactive') {
                    App_Factory_Security::getSecurity()->doDestroysession();
                }
            }
        }

        $strAction = $this->_arrCommandconf['action'];

        $this->_objCommand->$strAction();

        foreach($this->_objPosthooks as $objPosthook) {
            $objPosthook->runPost();
        }
    }

    public function __toString() {
        $objTemplate = new Core_Web_Template(App_Factory_Response::getResponse());

        return (string)$objTemplate;
    }

    private function _doInit() {
        try {
            $this->_objRequest      = App_Factory_Request::getRequest();
            $this->_arrCommandconf  = App_Config_Command::url2command();
            $this->_objCommand      = App_Factory_Command::getCommand($this->_arrCommandconf['command']);
        } catch (App_Config_Exception $e) {
            $this->_arrCommandconf  = App_Config_Command::get404command();
            $this->_objCommand      = App_Factory_Command::getCommand($this->_arrCommandconf['command']);
        } catch (App_Factory_Exception $e) {
            $this->_arrCommandconf  = App_Config_Command::get404command();
            $this->_objCommand      = App_Factory_Command::getCommand($this->_arrCommandconf['command']);
        }

        $this->_objPrehooks     = new App_Web_Prehook();
        $this->_objPosthooks    = new App_Web_Posthook();
    }
}