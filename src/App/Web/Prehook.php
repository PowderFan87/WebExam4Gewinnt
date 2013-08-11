<?php

/**
 * Class for pre hook collection management
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Web_Prehook extends Core_Base_Hook
{
    /**
     * Load all pre hooks from configuration array
     * 
     */
    public function __construct() {
        parent::__construct();
        
        foreach($GLOBALS['arrCFGPrehooks'] as $strHookclass) {
            $objHook = new $strHookclass();
            
            if($objHook instanceof IPrehook) {
                $this->_arrElements[] = $objHook;
            }
        }
    }
}