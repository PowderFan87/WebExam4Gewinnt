<?php

/**
 * Loading and evaluating command configuration
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Config_Command
{
    /**
     * Load configuration of command by evaluating the request URL
     * 
     * Try to get url from redirect url server parameter. If none is given the
     * default command is evaluated. If we find an url the url path is split
     * into it's componets by /. First component is the command, 2nd the
     * action that is to be executed. If no action is given (count of explode
     * result is only 1 or 0) we try to execute the main action. After that
     * the action methode is build and the command class is evaluated.
     * If the command has the interface IRestricted implemented, we evaluate
     * the restriction and if the restriction is not met we execute the fallback.
     * Last we validate the command and if valid return the configuration array.
     * 
     * @return array
     */
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

    /**
     * Return the 404 command and action configuration array
     * 
     * @return array
     */
    public static function get404command() {
        return array(
            'command'   => 'Command_Main',
            'action'    => 'get404'
        );
    }

    /**
     * Build action methode name based on request type (get or post)
     * 
     * @param String $strAction
     * @param Core_Web_Request $objRequest
     * @return String
     */
    private static function _buildActionmethode($strAction, $objRequest) {
        return strtolower($objRequest->getStrrequestmethode()) . $strAction;
    }

    /**
     * Validate command class and action methode for existence
     * 
     * @param array $arrCommandconf
     * @throws App_Config_Exception
     */
    private static function _doValidatecomamnd($arrCommandconf) {
        if(!class_exists($arrCommandconf['command'])) {
            throw new App_Config_Exception('Can\'t find class for command "' . $arrCommandconf['command'] . '"');
        }

        if(!method_exists($arrCommandconf['command'], $arrCommandconf['action'])) {
            throw new App_Config_Exception('No action ' . $arrCommandconf['action'] . ' for command ' . $arrCommandconf['command'] . '"');
        }
    }

    /**
     * Return default command and action configuration array
     * 
     * @return array
     */
    private static function _getDefaultcommand() {
        return array(
            'command'   => 'Command_Main',
            'action'    => 'getMain'
        );
    }
}