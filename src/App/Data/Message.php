<?php

/**
 * Description of App_Data_Message
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
final class App_Data_Message extends App_Data_Base
{
    const   TABLE_CLASS = 'tblMessage';
    const   TABLE_PK    = 'UID';

    protected function getEmpryarray() {
        return array(
            'lngGameid'     => 0,
            'lngUseridfrom' => 0,
            'lngUseridto'   => 0,
            'strMessage'    => '',
            'blnRead'       => false
        );
    }
}