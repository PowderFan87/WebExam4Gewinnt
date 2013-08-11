<?php
error_reporting(0);
ini_set('display_errors', '0');

// Basic project directory
define('BASE_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

// Directory configuration
define('SRC_DIR',       BASE_DIR . 'src' . DIRECTORY_SEPARATOR);
define('INTERFACE_DIR', SRC_DIR . 'Interface' . DIRECTORY_SEPARATOR);
define('TBL_CLASS_DIR', SRC_DIR . 'App' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Table' . DIRECTORY_SEPARATOR);
define('TEMPLATE_DIR',  SRC_DIR . 'Template' . DIRECTORY_SEPARATOR);

// Template Config
define('TPL_MODE_HTML_FULL',        'html/full');
define('TPL_MODE_HTML_ACTION_ONLY', 'html/action');
define('TPL_MODE_JSON',             'json');
define('TPL_HTML_BASE',             TEMPLATE_DIR . 'base.html');
define('TPL_JSON_BASE',             TEMPLATE_DIR . 'jsonBase.html');

// Resource Config 
define('RESOURCE_TYPE',         'DB');
define('RESOURCE_SYSTEM',       'MySQL');
define('RESOURCE_DB_HOST',      '127.0.0.1');
define('RESOURCE_DB_PORT',      '3306');
define('RESOURCE_DB_NAME',      'webexam');
define('RESOURCE_DB_USER',      'webexam');
define('RESOURCE_DB_PASSWORD',  'meinpw');

// Security Config
define('MD5_MOD', '4GeWiNnT');

// Misc Config
define('CFG_WEB_ROOT', '/WebExam4Gewinnt');

// Hook configuration
$GLOBALS['arrCFGPrehooks']  = array(
    'App_Hook_Security'
);
$GLOBALS['arrCFGPosthooks'] = array(
    'App_Hook_User',
    'App_Hook_Infowidget'
);

// @TODO THIS IS JUST FOR MY XAMPP
if(isset($_SERVER['REDIRECT_URL'])) {
    $_SERVER['REDIRECT_URL'] = str_replace(CFG_WEB_ROOT, '', $_SERVER['REDIRECT_URL']);
}

/**
 * PHP Autoloader function
 *
 * @author Holger Szüsz <hszuesz@live.com>
 * @param String $strClassname
 */
function __autoload($strClassname) {
    if(substr($strClassname, 0, 1) === 'I') {
        include_once INTERFACE_DIR . str_replace('_', DIRECTORY_SEPARATOR, $strClassname) . '.php';
    } else if(substr($strClassname, 0, 3) === 'tbl') {
        include_once TBL_CLASS_DIR . $strClassname . '.php';
    } else {
        include_once SRC_DIR . str_replace('_', DIRECTORY_SEPARATOR, $strClassname) . '.php';
    }
}