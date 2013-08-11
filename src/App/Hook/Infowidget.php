<?php

/**
 * Infowidget post hook
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Hook_Infowidget implements IPosthook
{
    /**
     * Infowidget post hook to collect all information displayed in info
     * widget like online users and game counts and set them in response
     * 
     */
    public function runPost() {
        $objResponse    = App_Factory_Response::getResponse();
        
        $objUser = App_Factory_Security::getSecurity()->getObjuser();
        
        if($objUser instanceof App_Data_User) {
            $arrCounts      = tblGame::Getrunninggamecount();
        
            $objResponse->lngGamesopen = $arrCounts['lngGamesopen'];
            $objResponse->lngGamesturn = $arrCounts['lngGamesturn'];

            $arrOnline      = tblUser::getCountallonline();

            $objResponse->lngOnline = $arrOnline['lngOnline'];
        }
    }
}