<?php

/**
 * Description of App_Tools_Validator
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Tools_Validator
{
    public static function isEmail($strEmail) {
        $strPattern = "/^(?:[[:alnum:]ßöäüÖÄÜ]{1})(?:[ßöäüÖÄÜ\w\-\.]*)@(?:[[:alnum:]ßöäüÖÄÜ]+[ßöäüÖÄÜ\w\-]*)\.(?:[a-z]{2,4}$|[ßöäüÖÄÜ\w\-]+\.[a-z]{2,4}$)/u";

        return self::isPattern($strEmail, $strPattern);
    }

    public static function hasStringlength($strValue, $lngMax, $lngMin = 0) {
        return (strlen($strValue) <= $lngMax && strlen($strValue) >= $lngMin);
    }

    public static function notEmpty($mixValue) {
        return !empty($mixValue);
    }

    public static function areEqual($mixValue1, $mixValue2) {
        return ($mixValue1 === $mixValue2);
    }

    public static function isValiduser($strUsername) {
        if(!self::isPattern($strUsername, "/^[[:alnum:]ßöäüÖÄÜ]{5,20}$/u")) {
            return false;
        }

        if(tblUser::getUserbystrusername($strUsername) !== false) {
            return false;
        }

        return true;
    }

    public static function isPattern($strValue, $strPattern) {
        return (preg_match($strPattern, $strValue) === 1);
    }
}