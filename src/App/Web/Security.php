<?php

/**
 * Description of App_Web_Security
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Web_Security
{
    private $_objUser = NULL;

    public static function isAuthenticated() {
        return isset($_SESSION['loggedin']);
    }

    public static function notAuthenticated() {
        return !isset($_SESSION['loggedin']);
    }

    public static function tryLogin($strUsername, $strPassword) {
        $objUser = tblUser::getUserbystrusername($strUsername);

        if(!($objUser instanceof App_Data_User)) {
            return false;
        }
        
        if(md5(MD5_MOD . $strPassword) === $objUser->getstrPassword() && $objUser->getblnActivated() == 1) {
            self::loginUser($objUser);
            
            return true;
        }
        
        return false;
    }

    private static function loginUser(App_Data_User $objUser) {
        $_SESSION['loggedin'] = $objUser->getUID();

        $objUser->setblnLoggedin(true);
        $objUser->setdtmLastaction(date('Y-m-d H:i:s'));

        if(!$objUser->doFullupdate()) {
            throw new App_Web_Security_Exception('Update on User failed');
        }

    }

    public function __construct() {
        if(isset($_SESSION['loggedin'])) {
            $this->_objUser = tblUser::getBypk($_SESSION['loggedin']);
        }
    }

    public function doIpcheck() {
        if((isset($_SESSION['IP']) && $_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']) ||
           (isset($_SESSION['IP_FORW']) && $_SESSION['IP_FORW'] !== $_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return false;
        }

        return true;
    }
    
    public function doTimeoutcheck() {
        if(!($this->_objUser instanceof App_Data_User)) {
            return true;
        }
        
        $dtmLastaction  = new DateTime($this->_objUser->getdtmLastaction());
        $dtmNow         = new DateTime();
        $dtmDiff        = $dtmLastaction->diff($dtmNow);
        
        if($dtmDiff->y > 0 || $dtmDiff->m > 0 || $dtmDiff->d > 0 || $dtmDiff->h > 0 || $dtmDiff->i > 30) {
            return false;
        }
        
        return true;
    }

    public function doDestroysession() {
        $_SESSION = array();

        if (ini_get('session.use_cookies')) {
            $arrParams = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $arrParams['path'], $arrParams['domain'],
                $arrParams['secure'], $arrParams['httponly']
            );
        }

        session_destroy();

        $this->_objUser->setblnLoggedin(false);
        if(!$this->_objUser->doFullupdate()) {
            throw new App_Web_Security_Exception('Update on User failed');
        }

        unset($this->_objUser);
    }

    /**
     * 
     * @return App_Data_User
     */
    public function getObjuser() {
        return $this->_objUser;
    }
}