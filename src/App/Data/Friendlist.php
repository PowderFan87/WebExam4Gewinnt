<?php

/**
 * AR class for tblFriendlist table
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_Friendlist extends App_Data_Base
{
    const   TABLE_CLASS = 'tblFriendlist';
    const   TABLE_PK    = 'UID';

    /**
     * Get empty array of AR class
     * 
     * @return array
     */
    protected function getEmpryarray() {
        return array(
            'lngUser1'      => 0,
            'lngUser2'      => 0,
            'blnAccepted'   => false
        );
    }
}