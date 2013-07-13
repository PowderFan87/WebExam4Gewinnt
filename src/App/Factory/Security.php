<?php

/**
 * Description of App_Factory_Security
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Security
{
    private static $_objSecurity = NULL;
    
    /**
     * 
     * @return App_Web_Security
     */
    public static function getSecurity() {
        if(self::$_objSecurity === NULL) {
            self::_doLoad();
        }
        
        return self::$_objSecurity;
    }
    
    private static function _doLoad() {
        self::$_objSecurity = new App_Web_Security();
    }
}