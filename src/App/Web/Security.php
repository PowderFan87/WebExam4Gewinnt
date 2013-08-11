<?php

/**
 * Security class for web application
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Web_Security
{
    private $_objUser = NULL;

    /**
     * Return if current session has a loggedin value
     * 
     * @return boolean
     */
    public static function isAuthenticated() {
        return isset($_SESSION['loggedin']);
    }

    /**
     * inverse function to isAuthenticated
     * 
     * @return boolean
     */
    public static function notAuthenticated() {
        return !isset($_SESSION['loggedin']);
    }

    /**
     * Try to find and loggin user by username and password
     * 
     * @param string $strUsername
     * @param string $strPassword
     * @return boolean
     */
    public static function tryLogin($strUsername, $strPassword) {
        $objUser = tblUser::getUserbystrusername($strUsername);

        if(!($objUser instanceof App_Data_User)) {
            return false;
        }
        
        if(md5(MD5_MOD . $strPassword) === $objUser->getstrPassword() && $objUser->getblnActivated() == 1) {
            self::loginUser($objUser);
            
            App_Factory_Security::doReset();
            
            return true;
        }
        
        return false;
    }

    /**
     * Lotin user by setting db values on 1 and current timestamp
     * 
     * @param App_Data_User $objUser
     * @throws App_Web_Security_Exception
     */
    private static function loginUser(App_Data_User $objUser) {
        $_SESSION['loggedin'] = $objUser->getUID();

        $objUser->setblnLoggedin(true);
        $objUser->setdtmLastaction(date('Y-m-d H:i:s'));

        if(!$objUser->doFullupdate()) {
            throw new App_Web_Security_Exception('Update on User failed');
        }

    }

    /**
     * Check in init if we have a logged in value in session and try to load
     * user from DB
     * 
     */
    public function __construct() {
        if(isset($_SESSION['loggedin'])) {
            $this->_objUser = tblUser::getBypk($_SESSION['loggedin']);
        }
    }

    /**
     * Make an I check to prevent session hijacking
     * 
     * @return boolean
     */
    public function doIpcheck() {
        if((isset($_SESSION['IP']) && $_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']) ||
           (isset($_SESSION['IP_FORW']) && $_SESSION['IP_FORW'] !== $_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return false;
        }

        return true;
    }
    
    /**
     * Check if user was inactive for to long and logg out if so.
     * 
     * @return boolean
     */
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

    /**
     * Destroy current session by resetting session array and make cookie invalid
     * 
     * @throws App_Web_Security_Exception
     */
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
     * Return AR-Class instance of current loggedin user
     * 
     * @return App_Data_User
     */
    public function getObjuser() {
        return $this->_objUser;
    }
}