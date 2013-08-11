<?php

/**
 * Mark a command as restricted and that the restriction has to be checked
 * 
 * @author Holger Szüsz <hszuesz@live.com>
 */
interface IRestricted
{
    public static function getRestriction();
    public function getFallback();
}