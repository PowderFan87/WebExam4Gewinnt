<?php

/**
 * Description of App_Config_Command
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Config_Command
{
    public static function url2command() {
        $arrCommandconf = array();

        if(isset($_SERVER['REDIRECT_URL'])) {
            foreach(explode('/', $_SERVER['REDIRECT_URL']) as $strPart) {
                if(strlen($strPart) <= 0) {
                    continue;
                }

                $arrParts[] = $strPart;
            }

            if(!is_array($arrParts) || count($arrParts) < 2) {
                $arrParts[] = 'Main';
            }

            $arrCommandconf['action']   = self::_buildActionmethode(array_pop($arrParts), App_Factory_Request::getRequest());
            $arrCommandconf['command']  = 'Command_' . join('_', $arrParts);

            if(in_array('IRestricted', class_implements($arrCommandconf['command']))) {
                $strCommand = $arrCommandconf['command'];

                if(!call_user_func($strCommand::getRestriction())) {
                    $arrCommandconf['action']   = self::_buildActionmethode('Fallback', App_Factory_Request::getRequest());
                }
            }
        } else {
            $arrCommandconf = self::_getDefaultcommand();
        }

        self::_doValidatecomamnd($arrCommandconf);

        return $arrCommandconf;
    }

    public static function get404command() {
        return array(
            'command'   => 'Command_Main',
            'action'    => 'get404'
        );
    }

    private static function _buildActionmethode($strAction, $objRequest) {
        return strtolower($objRequest->getStrrequestmethode()) . $strAction;
    }

    private static function _doValidatecomamnd($arrCommandconf) {
        if(!class_exists($arrCommandconf['command'])) {
            throw new App_Config_Exception('Can\'t find class for command "' . $arrCommandconf['command'] . '"');
        }

        if(!method_exists($arrCommandconf['command'], $arrCommandconf['action'])) {
            throw new App_Config_Exception('No action ' . $arrCommandconf['action'] . ' for command ' . $arrCommandconf['command'] . '"');
        }
    }

    private static function _getDefaultcommand() {
        return array(
            'command'   => 'Command_Main',
            'action'    => 'getMain'
        );
    }
}