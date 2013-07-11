<?php

/**
 * Description of Core_web_XHttpRequest
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Core_Web_XHttpRequest extends Core_Web_Request
{
    public function getRequesttype() {
        return "XHttpRequest";
    }

    protected function _doFetchdata() {
        $this->_strRequestmethode = $_SERVER['REQUEST_METHOD'];

        $this->_arrData = $_POST;

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
    }
}