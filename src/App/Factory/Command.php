<?php

/**
 * Factory for command
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Command
{
    /**
     * Try to create an instance of a command based on passed command name and check
     * if the command is allowed to be executed by requesttype (http or xhttp) by
     * checking for the coresponding interface.
     * 
     * @param String $strCommand
     * @return Core_Base_Command
     * @throws App_Factory_Exception
     */
    public static function getCommand($strCommand) {
        $objCommand = new $strCommand(App_Factory_Request::getRequest());

        $strInterface = 'I' . App_Factory_Request::getRequest()->getRequesttype();

        if(!($objCommand instanceof Core_Base_Command)) {
            throw new App_Factory_Exception('Command is invalid');
        }

        if(!($objCommand instanceof $strInterface)) {
            throw new App_Factory_Exception('Command interface not allowd for request type ' . App_Factory_Request::getRequest()->getRequesttype());
        }

        return $objCommand;
    }
}