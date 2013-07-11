<?php

/**
 * Description of App_Factory_Command
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Command
{
    public static function getCommand($strCommand) {
        $objCommand = new $strCommand(App_Factory_Request::getRequest());

        $strInterface = "I" . App_Factory_Request::getRequest()->getRequesttype();

        if(!($objCommand instanceof Core_Base_Command)) {
            throw new App_Factory_Exception("Command is invalid");
        }

        if(!($objCommand instanceof $strInterface)) {
            throw new App_Factory_Exception("Command interface not allowd for request type " . App_Factory_Request::getRequest()->getRequesttype());
        }

        return $objCommand;
    }
}