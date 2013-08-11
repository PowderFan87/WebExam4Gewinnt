<?php

/**
 * Description of Command_Registrieren
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Command_Registrieren extends Core_Base_Command implements IHttpRequest, IRestricted
{
    public static function getRestriction() {
        return 'App_Web_Security::notAuthenticated';
    }

    public function getFallback() {
        $this->_objResponse->tplContent = 'Registrieren_GET_Fallback';
    }

    public function getMain() {
        $this->_objResponse->tplContent = 'Registrieren_GET_Main';
    }

    public function postMain() {
        $this->_objResponse->tplContent = 'Registrieren_POST_Main';

        $arrErrors = $this->_doValidate();

        if(!empty($arrErrors)) {
            $this->_objResponse->tplContent = 'Registrieren_GET_Main';

            foreach($arrErrors as $strField => $blnError) {
                if(!$blnError) {
                    continue;
                }

                $strErrorvariable = 'ERROR_' . $strField;

                $this->_objResponse->$strErrorvariable = 'error';
            }

            $this->_objResponse->strEmail       = $this->_objRequest->strEmail;
            $this->_objResponse->strUsername    = $this->_objRequest->strUsername;
        } else {
            $objUser = new App_Data_User();

            $objUser->setstrUsername($this->_objRequest->strUsername);
            $objUser->setstrEmail($this->_objRequest->strEmail);
            $objUser->setstrPassword(md5(MD5_MOD . $this->_objRequest->strPassword));

            if(!$objUser->doInsert()) {
                $this->_objResponse->strMessage = 'Fehler beim erstellen des Users. Bitte versuchen Sie es erneut';
            } else {
                $txtMail = <<<TXT
Hallo {$objUser->getstrUsername()},

vielen Dank für deine Anmeldung bei 4-Gewinnt.
Um deine Anmeldung zu vervollständigen folge bitte dem Link in der Email.

http://4gewinnt.szüsz.de/Registrieren/Aktivieren?user={$objUser->getstrUsername()}

Vielen Dank und viel Spaß beim 4-Gewinnt spielen :)

Grüße,

4-Gewinnt Team
TXT;

                if(!mail($objUser->getstrEmail(), "4-Gewinnt: Registrierung abschließen", $txtMail)) {
                    $this->_objResponse->strMessage = 'Bitte folge folgendem Link um die Registrierung ab zu schließen.<br /><a href="/WebExam4Gewinnt/htdocs/Registrieren/Aktivieren?user=' . $objUser->getstrUsername() . '">Registrierung abschließen</a>';
                }

                $this->_objResponse->strMessage = 'Registrierung erfolgreich. Bitte checken Sie Ihren Posteingang um die Registrierung abzuschließen';
            }
        }
    }

    public function getAktivieren() {
        $this->_objResponse->tplContent = 'Registrieren_GET_Aktivieren';

        $this->_objResponse->strUsername = $this->_objRequest->user;
    }

    public function postAktivieren() {
        $this->_objResponse->tplContent = 'Registrieren_POST_Aktivieren';

        $objUser = tblUser::getUserbystrusername($this->_objRequest->strUsername);

        if(!$objUser instanceof App_Data_User || md5(MD5_MOD . $this->_objRequest->strPassword) !== $objUser->getstrPassword()) {
            $this->_objResponse->tplContent = 'Registrieren_GET_Aktivieren';

            $this->_objResponse->ERROR_strPassword = 'error';
            $this->_objResponse->strUsername = $this->_objRequest->strUsername;
        } else {
            $objUser->setblnActivated(true);
            
            $objUserprofile = new App_Data_Userprofile();
            
            $objUserprofile->setlngUser($objUser->getUID());

            if(!$objUser->doFullupdate() || !$objUserprofile->doInsert()) {
                $this->_objResponse->strMessage = 'Aktivierung fehlgeschlagen';
            } else {
                $this->_objResponse->strMessage = 'Aktivierung erfolgreich';
            }
        }
    }

    protected function _doInit() {
        $this->_objResponse->strTitle = 'Registrieren';
    }

    private function _doValidate() {
        $arrErrors = array();

        if(!App_Tools_Validator::isValiduser($this->_objRequest->strUsername)) {
            $arrErrors['strUsername'] = true;
        }

        if(!App_Tools_Validator::isEmail($this->_objRequest->strEmail)) {
            $arrErrors['strEmail'] = true;
        }

        if(!App_Tools_Validator::notEmpty($this->_objRequest->strPassword)) {
            $arrErrors['strPassword'] = true;
        }

        if(!App_Tools_Validator::areEqual($this->_objRequest->strPassword, $this->_objRequest->strPassword_reenter) || !App_Tools_Validator::notEmpty($this->_objRequest->strPassword_reenter)) {
            $arrErrors['strPassword_reenter'] = true;
        }

        return $arrErrors;
    }
}