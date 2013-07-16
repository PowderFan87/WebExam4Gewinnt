<?php

/**
 * Description of App_Data_Friendlist
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_Friendlist extends App_Data_Base
{
    const   TABLE_CLASS = 'tblFriendlist';
    const   TABLE_PK    = 'UID';
    
    protected function getEmpryarray() {
        return array(
            'lngUser1',
            'lngUser2',
            'blnAccepted'
        );
    }
}