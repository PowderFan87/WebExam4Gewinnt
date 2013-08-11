<?php

/**
 * Command base class
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Base_Command
{
    protected $_objRequest;
    protected $_objResponse;

    /**
     * Init command with request and load response class
     * 
     * @param Core_Web_Request $objRequest
     */
    public function __construct(Core_Web_Request $objRequest) {
        $this->_objRequest  = $objRequest;
        $this->_objResponse = App_Factory_Response::getResponse();

        $this->_doInit();
    }

    abstract protected function _doInit();
}