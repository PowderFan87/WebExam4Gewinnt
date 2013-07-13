<?php

/**
 * Description of App_Web_Posthook
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Web_Posthook extends Core_Base_Hook
{
    public function __construct() {
        parent::__construct();
        
        foreach($GLOBALS['arrCFGPosthooks'] as $strHookclass) {
            $objHook = new $strHookclass();
            
            if($objHook instanceof IHook) {
                $this->_arrElements[] = $objHook;
            }
        }
    }
}