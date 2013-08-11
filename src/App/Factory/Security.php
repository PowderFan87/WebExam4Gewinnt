<?php

/**
 * Security factory
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Security
{
    private static $_objSecurity = NULL;
    
    /**
     * Get instance of current security object or create new one if none exsists.
     * 
     * @return App_Web_Security
     */
    public static function getSecurity() {
        if(self::$_objSecurity === NULL) {
            self::_doLoad();
        }
        
        return self::$_objSecurity;
    }
    
    /**
     * Reset security instance
     * 
     */
    public static function doReset() {
        self::$_objSecurity = NULL;
    }
    
    /**
     * Load new security instance
     * 
     */
    private static function _doLoad() {
        self::$_objSecurity = new App_Web_Security();
    }
}