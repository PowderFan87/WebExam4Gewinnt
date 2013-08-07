<?php

/**
 * Description of App_Data_Game
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_Game extends App_Data_Base
{
    const   TABLE_CLASS = 'tblGame';
    const   TABLE_PK    = 'UID';

    public function getArrgamegrid() {
        return unserialize($this->gettxtGamegrid());
    }

    public function setArrgamegrid($arrGrid) {
        $this->setTxtgamegrid(serialize($arrGrid));
    }

    public function notAuthenticated() {
        $objUser = App_Factory_Security::getSecurity()->getObjuser();

        if(!($objUser instanceof App_Data_User)) {
            return false;
        }
        
        if($objUser->getUID() !== $this->getlngPlayer1() && $objUser->getUID() !== $this->getlngPlayer2()) {
            return true;
        }

        return false;
    }
    
    public function getPlayertype() {
        $objUser = App_Factory_Security::getSecurity()->getObjuser();
        
        if(!($objUser instanceof App_Data_User)) {
            return false;
        }
        
        if($this->getlngPlayer1() === $objUser->getUID()) {
            return 1;
        } else if($this->getlngPlayer2() === $objUser->getUID()) {
            return 2;
        }
        
        return false;
    }

    protected function getEmpryarray() {
        return array(
            'strName'       => '',
            'lngPlayer1'    => 0,
            'lngPlayer2'    => 0,
            'lngThemeid'    => 0,
            'lngTurn'       => 0,
            'enmStatus'     => 'open',
            'txtGamegrid'   => serialize(array(
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0)
            )),
            'strLastchange' => '',
            'lngPointsleft' => 42,
            'lngWinner'     => 0
        );
    }
}