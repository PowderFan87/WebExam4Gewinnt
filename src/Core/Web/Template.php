<?php

/**
 * Description of Core_Web_Template
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Core_Web_Template
{
    protected $_txtOutput = '';
    protected $_objResponse;

    /**
     *
     * @param Core_Web_Response $objResponse
     */
    public function __construct($objResponse) {
        $this->_objResponse = $objResponse;

        if(!($this instanceof Core_Web_Template_Subtemplate)) {
            if($this->_objResponse->getStrresponsetype() === TPL_MODE_HTML_FULL) {
                $this->_txtOutput = file_get_contents(TPL_HTML_BASE);
            }
        }
    }

    public function __toString() {
        $this->_txtOutput = preg_replace_callback('/#CONF:([\w].*?)#/', array($this, '_getConfreplacement'), $this->_txtOutput);
        $this->_txtOutput = preg_replace_callback('/#TPL:([\w].*?)#/', array($this, '_getTplreplacement'), $this->_txtOutput);
        $this->_txtOutput = preg_replace_callback('/#VAR:([\w].*?)#/', array($this, '_getVarreplacement'), $this->_txtOutput);

        return $this->_txtOutput;
    }

    protected function _getConfreplacement($arrMatch) {
        return $this->_objResponse->$arrMatch[1];
    }

    protected function _getVarreplacement($arrMatch) {
        return $this->_objResponse->$arrMatch[1];
    }

    protected function _getTplreplacement($arrMatch) {
        $objSubtemplate = new Core_Web_Template_Subtemplate($this->_objResponse->$arrMatch[1], $this->_objResponse);

        return (string)$objSubtemplate;
    }
}