<?php

/**
 * Class for post hook collection management
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Web_Posthook extends Core_Base_Hook
{
    /**
     * Load all post hooks from configuration array
     * 
     */
    public function __construct() {
        parent::__construct();
        
        foreach($GLOBALS['arrCFGPosthooks'] as $strHookclass) {
            $objHook = new $strHookclass();
            
            if($objHook instanceof IPosthook) {
                $this->_arrElements[] = $objHook;
            }
        }
    }
}