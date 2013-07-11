<?php

/**
 * Description of App_Factory_Resource
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Resource
{
    private static $_objResource = NULL;

    /**
     *
     * @return IResource
     * @throws App_Factory_Exception
     */
    public static function getResource() {
        if(self::$_objResource === NULL) {
            self::_doLoad();
        }

        if(!(self::$_objResource instanceof IResource)) {
            throw new App_Factory_Exception("Current instance of Resource is invalid");
        }

        return self::$_objResource;
    }

    /**
     *
     */
    private static function _doLoad() {
        $strResourcename = "Resource_" . RESOURCE_TYPE . "_" . RESOURCE_SYSTEM;

        self::$_objResource = new $strResourcename();
    }
}