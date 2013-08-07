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
            } else if($this->_objResponse->getStrresponsetype() === TPL_MODE_HTML_ACTION_ONLY) {
                if(file_exists(TEMPLATE_DIR . str_replace('_', DIRECTORY_SEPARATOR, $this->_objResponse->tplContent) . '.html')) {
                    $this->_txtOutput = file_get_contents(TEMPLATE_DIR . str_replace('_', DIRECTORY_SEPARATOR, $this->_objResponse->tplContent) . '.html');
                } else {
                    var_dump('Can\'t find template "' . TEMPLATE_DIR . str_replace('_', DIRECTORY_SEPARATOR, $this->_objResponse->tplContent) . '.html' . '"');
                }
            } else if($this->_objResponse->getStrresponsetype() === TPL_MODE_JSON) {
                $this->_txtOutput = file_get_contents(TPL_JSON_BASE);
            }
        }
    }

    public function __toString() {
        $this->_txtOutput = str_replace("#ROOT#", CFG_WEB_ROOT, $this->_txtOutput);

        $this->_txtOutput = preg_replace_callback('/#CONST:([\w]*?)#/u', array($this, '_getConstreplacement'), $this->_txtOutput);
        $this->_txtOutput = preg_replace_callback('/#TPL:([\w]*?)#/u', array($this, '_getTplreplacement'), $this->_txtOutput);
        $this->_txtOutput = preg_replace_callback('/#IF:([!=<>:\-\(\)\$\[\]\'\w]*?)#(.*?)#\/IF#/us', array($this, '_getIfreplacement'), $this->_txtOutput);
        $this->_txtOutput = preg_replace_callback('/#FOREACH:([\w]*?)#(.*?)#\/FOREACH#/us', array($this, '_getForeachreplacement'), $this->_txtOutput);
        $this->_txtOutput = preg_replace_callback('/#VAR:([\w]*?)#/u', array($this, '_getVarreplacement'), $this->_txtOutput);

        return $this->_txtOutput;
    }

    protected function _getConstreplacement($arrMatch) {
        return constant($arrMatch[1]);
    }

    protected function _getVarreplacement($arrMatch) {
        return $this->_objResponse->$arrMatch[1];
    }

    protected function _getTplreplacement($arrMatch) {
        $objSubtemplate = new Core_Web_Template_Subtemplate($this->_objResponse->$arrMatch[1], $this->_objResponse);

        return (string)$objSubtemplate;
    }

    protected function _getIfreplacement($arrMatch) {
        $blnResult = false;

        eval('$blnResult = (' . $arrMatch[1] . ');');

        if($blnResult) {
            return $arrMatch[2];
        } else {
            return "";
        }
    }

    protected function _getForeachreplacement($arrMatch) {
        $arrData    = $this->_objResponse->$arrMatch[1];
        $txtTpl     = $arrMatch[2];
        $txtOutput  = "";

        foreach($arrData as $arrInput) {
            $txtTmp = $txtTpl;

            foreach($arrInput as $strKey => $mixValue) {
                $txtTmp = str_replace("#VAR:$strKey#", $mixValue, $txtTmp);
            }

            $txtOutput .= "\n$txtTmp";
        }

        return $txtOutput;
    }
}