<?php

/**
 * Description of Core_web_HttpRequest
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Core_Web_HttpRequest extends Core_Web_Request
{
    public function getRequesttype() {
        return "HttpRequest";
    }

    protected function _doFetchdata() {
        $this->_strRequestmethode = $_SERVER['REQUEST_METHOD'];

        switch($this->_strRequestmethode) {
            case 'GET':
                $this->_arrData = $_GET;

                break;

            case 'POST':
                $this->_arrData = $_POST;

                break;
        }

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
    }
}