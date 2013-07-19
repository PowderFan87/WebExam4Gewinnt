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
            'lngPointsleft' => 42,
            'lngWinner'     => 0
        );
    }
}