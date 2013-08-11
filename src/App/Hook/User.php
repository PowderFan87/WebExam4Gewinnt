<?php

/**
 * User Posthook
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Hook_User implements IPosthook
{
    /**
     * Update user last action at the end of an request
     * 
     */
    public function runPost() {
        $objUser = App_Factory_Security::getSecurity()->getObjuser();
        
        if($objUser instanceof App_Data_User) {
            $objUser->setdtmLastaction(date('Y-m-d H:i:s'));
            $objUser->doFullupdate();
        }
    }
}