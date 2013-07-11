<?php

/**
 * Description of Core_Command
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Base_Command
{
    protected $_objRequest;
    protected $_objResponse;

    /**
     *
     * @param Core_Web_Request $objRequest
     */
    public function __construct($objRequest) {
        $this->_objRequest  = $objRequest;
        $this->_objResponse = App_Factory_Response::getResponse();

        $this->_doInit();
    }

    abstract protected function _doInit();
}