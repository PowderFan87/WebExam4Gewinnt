<?php

/**
 * Description of App_Hook_Security
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Hook_Security implements IPrehook
{
    public function runPre() {
        $objSecurity = App_Factory_Security::getSecurity();
        
        if(!$objSecurity->doIpcheck()) {
            throw new App_Hook_Security_Exception('Invalid IP for Session');
        }
        
        
    }
}