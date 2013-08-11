<?php

/**
 * Response factory
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Factory_Response
{
    private static $_objResponse = NULL;

    /**
     * Get instance of current response instance or create new one if none exsists.
     * 
     * @return Core_Web_Request
     */
    public static function getResponse() {
        if(self::$_objResponse === NULL) {
            self::_doLoad();
        }

        return self::$_objResponse;
    }

    /**
     * Load new response instance for corrensponding request type (http or xhttp)
     * 
     */
    private static function _doLoad() {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            self::$_objResponse = new Core_Web_XHttpResponse();
        } else {
            self::$_objResponse = new Core_Web_HttpResponse();
        }
    }
}