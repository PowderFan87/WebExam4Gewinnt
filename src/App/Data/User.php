<?php

/**
 * AR class for tblUser table
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_User extends App_Data_Base
{
    const   TABLE_CLASS = 'tblUser';
    const   TABLE_PK    = 'UID';

    /**
     * Get empty array of AR class
     * 
     * @return array
     */
    protected function getEmpryarray() {
        return array(
            'strUsername'   => '',
            'strPassword'   => '',
            'strEmail'      => '',
            'blnLoggedin'   => false,
            'dtmLastaction' => date('Y-m-d H:i:s'),
            'dtmRegistered' => date('Y-m-d H:i:s'),
            'blnActivated'  => false
        );
    }
    
    /**
     * 
     * @return App_Data_Userprofile
     */
    public function getUserprofile() {
        return tblUserprofile::getUserprofilebylnguser($this->getUID());
    }
}