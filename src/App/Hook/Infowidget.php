<?php

/**
 * Description of App_Hook_Infowidget
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Hook_Infowidget implements IPosthook
{
    public function runPost() {
        $objResponse    = App_Factory_Response::getResponse();
        
        $arrCounts      = tblGame::Getrunninggamecount();
        
        $objResponse->lngGamesopen = $arrCounts['lngGamesopen'];
        $objResponse->lngGamesturn = $arrCounts['lngGamesturn'];
        
        $arrOnline      = tblUser::getCountallonline();
        
        $objResponse->lngOnline = $arrOnline['lngOnline'];
    }
}