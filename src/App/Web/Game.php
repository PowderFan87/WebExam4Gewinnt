<?php

/**
 * Description of App_Web_Game
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class App_Web_Game
{
    /**
     * 
     * @param App_Web_Game $objGame
     * @return boolean
     */
    public static function doWinnercheck(App_Data_Game $objGame, $blnInvert = false) {
        list($lngLasty, $lngLastx)  = str_split(str_replace('#f', '', $objGame->getstrLastchange()));
        $lngPlayernumber            = $objGame->getPlayertype();
        $arrGrid                    = $objGame->getArrgamegrid();

        if($blnInvert) {
            $lngPlayernumber = ($lngPlayernumber === 1) ? 2 : 1;
        }
        
        if(self::_doCheckhorizontal($lngLastx, $lngLasty, $lngPlayernumber, $arrGrid)) {
            return true;
        }
        
        if(self::_doCheckvertical($lngLastx, $lngLasty, $lngPlayernumber, $arrGrid)) {
            return true;
        }
        
        if(self::_doCheckdiagonal($lngLastx, $lngLasty, $lngPlayernumber, $arrGrid)) {
            return true;
        }
        
        return false;
    }
    
    private static function _doCheckhorizontal($lngX, $lngY, $lngPlayernumber, $arrGrid) {
        if(
            ($lngX <= 3 && $arrGrid[$lngY][$lngX+1] === $lngPlayernumber && $arrGrid[$lngY][$lngX+2] === $lngPlayernumber && $arrGrid[$lngY][$lngX+3] === $lngPlayernumber) ||
            ($lngX <= 4 && $lngX >= 1 && $arrGrid[$lngY][$lngX-1] === $lngPlayernumber && $arrGrid[$lngY][$lngX+1] === $lngPlayernumber && $arrGrid[$lngY][$lngX+2] === $lngPlayernumber) ||
            ($lngX <= 5 && $lngX >= 2 && $arrGrid[$lngY][$lngX-2] === $lngPlayernumber && $arrGrid[$lngY][$lngX-1] === $lngPlayernumber && $arrGrid[$lngY][$lngX+1] === $lngPlayernumber) ||
            ($lngX <= 6 && $lngX >= 3 && $arrGrid[$lngY][$lngX-3] === $lngPlayernumber && $arrGrid[$lngY][$lngX-2] === $lngPlayernumber && $arrGrid[$lngY][$lngX-1] === $lngPlayernumber)
          ) {
            return true;
        } else {
            return false;
        }
    }
    
    private static function _doCheckvertical($lngX, $lngY, $lngPlayernumber, $arrGrid) {
        if($lngY <= 2 && $lngY+3 < 6 && $arrGrid[$lngY+1][$lngX] === $lngPlayernumber && $arrGrid[$lngY+2][$lngX] === $lngPlayernumber && $arrGrid[$lngY+3][$lngX] === $lngPlayernumber) {
            return true;
        } else {
            return false;
        }
    }
    
    private static function _doCheckdiagonal($lngX, $lngY, $lngPlayernumber, $arrGrid) {
        $lngContinuous = 1;
        
        $lngContinuous += self::_doChecknext(1, -1, $lngX+1, $lngY-1, $lngPlayernumber, $arrGrid);
        
        if($lngContinuous < 4) {
            $lngContinuous += self::_doChecknext(-1, 1, $lngX-1, $lngY+1, $lngPlayernumber, $arrGrid);
        }
        
        if($lngContinuous === 4) {
            return true;
        } else {
            $lngContinuous = 1;
        }
        
        $lngContinuous += self::_doChecknext(1, 1, $lngX+1, $lngY+1, $lngPlayernumber, $arrGrid);
        
        if($lngContinuous < 4) {
            $lngContinuous += self::_doChecknext(-1, -1, $lngX-1, $lngY-1, $lngPlayernumber, $arrGrid);
        }
        
        if($lngContinuous === 4) {
            return true;
        }
        
        return false;
    }
    
    private static function _doChecknext($lngModx, $lngMody, $lngX, $lngY, $lngPlayernumber, $arrGrid) {
        if($lngX > 6 || $lngX < 0 || $lngY > 5 || $lngY < 0) {
            return 0;
        }
        
        if($arrGrid[$lngY][$lngX] === $lngPlayernumber) {
            return 1 + self::_doChecknext($lngModx, $lngMody, $lngX+$lngModx, $lngY+$lngMody, $lngPlayernumber, $arrGrid);
        } else {
            return 0;
        }
    }
}