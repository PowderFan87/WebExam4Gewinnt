<?php

/**
 * Description of App_Factory_Response
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Response
{
    private static $_objResponse = NULL;

    /**
     *
     * @return Core_Web_Request
     */
    public static function getResponse() {
        if(self::$_objResponse === NULL) {
            self::_doLoad();
        }

        return self::$_objResponse;
    }

    private static function _doLoad() {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            self::$_objResponse = new Core_Web_XHttpResponse();
        } else {
            self::$_objResponse = new Core_Web_HttpResponse();
        }
    }
}