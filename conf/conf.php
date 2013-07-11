<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Basic project directory
define("BASE_DIR", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);

// Source directory
define("SRC_DIR", BASE_DIR . "src" . DIRECTORY_SEPARATOR);


/**
 * PHP Autoloader function
 *
 * @author Holger Szüsz <hszuesz@live.com>
 * @param String $strClassname
 */
function __autoload($strClassname) {
    require_once SRC_DIR . str_replace("_", DIRECTORY_SEPARATOR, $strClassname) . ".php";
}