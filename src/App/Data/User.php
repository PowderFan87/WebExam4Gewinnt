<?php

/**
 * Description of App_Data_User
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_User extends App_Data_Base
{
    const   TABLE_CLASS = 'tblUser';
    const   TABLE_PK    = 'UID';
    
    protected function getEmpryarray() {
        return array(
            'strUsername',
            'strPassword',
            'strEmail',
            'blnLoggedin' => false,
            'dtmLastaction'
        );
    }
}