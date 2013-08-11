<?php

/**
 * Security pre hook
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Hook_Security implements IPrehook
{
    /**
     * Pre hook for security stuff like session hijacking and session timeout.
     * 
     * @throws App_Hook_Security_Exception
     */
    public function runPre() {
        $objSecurity = App_Factory_Security::getSecurity();
        
        if(!$objSecurity->doIpcheck()) {
            throw new App_Hook_Security_Exception('Invalid IP for Session');
        }
        
        if(!$objSecurity->doTimeoutcheck()) {
            throw new App_Hook_Security_Exception('Timout because inactive');
        }
    }
}