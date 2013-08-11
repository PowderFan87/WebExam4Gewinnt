<?php

/**
 * XHTTP request object
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Core_Web_XHttpRequest extends Core_Web_Request
{
    /**
     * Get request type
     * 
     * @return string
     */
    public function getRequesttype() {
        return 'XHttpRequest';
    }

    /**
     * Fetch data from post (ajax only by post)
     * 
     */
    protected function _doFetchdata() {
        $this->_strRequestmethode = $_SERVER['REQUEST_METHOD'];

        $this->_arrData = $_POST;

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
    }
}