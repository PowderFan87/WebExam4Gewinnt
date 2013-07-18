<?php

/**
 * Description of Command_Main
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Main extends Core_Base_Command implements IHttpRequest
{

    public function getMain() {
        $this->_objResponse->tplContent     = 'Main_GET_Main';

        $this->_objResponse->strTitle       .= ' - Home';
        $this->_objResponse->strWellcome    = 'Willkommen beim 4-Gewinnt';

        $this->_objResponse->arrTest = array(
            array(
                'strTitle' => 'Title 1',
                'strValue' => 'Value 1'
            ),
            array(
                'strTitle' => 'Title 2',
                'strValue' => 'Value 2'
            ),
            array(
                'strTitle' => 'Title 3',
                'strValue' => 'Value 3'
            ),
            array(
                'strTitle' => 'Title 4',
                'strValue' => 'Value 4'
            )
        );

        $this->_objResponse->arrTest2 = array(
            array(
                'strVorname' => 'Holger',
                'strName' => 'Szüsz'
            ),
            array(
                'strVorname' => 'Sandro',
                'strName' => 'Kuckert'
            ),
            array(
                'strVorname' => 'Daniel',
                'strName' => 'Beifuss'
            )
        );
    }

    public function get404() {
        $this->_objResponse->tplContent    = 'Main_GET_404';

        $this->_objResponse->strTitle       .= ' - 404';
        $this->_objResponse->strWellcome    = 'UPS! Das gibt es nicht...';
    }

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Main';
    }
}