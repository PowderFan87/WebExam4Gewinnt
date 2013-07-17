<?php

/**
 * Description of App_Data_Userprofile
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_Userprofile extends App_Data_Base
{
    const   TABLE_CLASS = 'tblUserprofile';
    const   TABLE_PK    = 'UID';

    protected function getEmpryarray() {
        return array(
            'lngUser'           => 0,
            'lngPlayedgames'    => 0,
            'lngPoints'         => 0
        );
    }
}