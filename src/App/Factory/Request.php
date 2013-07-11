<?php

/**
 * Description of App_Factory_Request
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Request
{
    private static $_objRequest = NULL;

    /**
     *
     * @return Core_Web_Request
     */
    public static function getRequest() {
        if(self::$_objRequest === NULL) {
            self::_doLoad();
        }

        return self::$_objRequest;
    }

    private static function _doLoad() {
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
            self::$_objRequest = new Core_Web_XHttpRequest();
        } else {
            self::$_objRequest = new Core_Web_HttpRequest();
        }
    }
}