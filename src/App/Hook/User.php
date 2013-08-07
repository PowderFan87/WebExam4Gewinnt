<?php

/**
 * Description of App_Hook_User
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Hook_User implements IPosthook
{
    public function runPost() {
        $objUser = App_Factory_Security::getSecurity()->getObjuser();
        
        if($objUser instanceof App_Data_User) {
            $objUser->setdtmLastaction(date('Y-m-d H:i:s'));
            $objUser->doFullupdate();
        }
    }
}