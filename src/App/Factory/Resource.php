<?php

/**
 * Resource factory
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Resource
{
    private static $_objResource = NULL;

    /**
     * Get current instance of recource (Data connector) of create new on if there
     * is none.
     * 
     * @return IResource
     * @throws App_Factory_Exception
     */
    public static function getResource() {
        if(self::$_objResource === NULL) {
            self::_doLoad();
        }

        if(!(self::$_objResource instanceof IResource)) {
            throw new App_Factory_Exception('Current instance of Resource is invalid');
        }

        return self::$_objResource;
    }

    /**
     * Load new resource instance by configuration
     * 
     */
    private static function _doLoad() {
        $strResourcename = 'Resource_' . RESOURCE_TYPE . '_' . RESOURCE_SYSTEM;

        self::$_objResource = new $strResourcename();
    }
}