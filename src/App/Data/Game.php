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
    
    protected function getEmpryarray() {
        return array(
            'strName',
            'lngPlayer1',
            'lngPlayer2',
            'lngTurn' => 0,
            'enmStatus' => 'open',
            'txtGamegrid' => array(
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0),
                array(0,0,0,0,0,0)
            ),
            'lngPointsleft',
            'lngWinner'
        );
    }
}