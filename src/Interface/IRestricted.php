<?php

/**
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
interface IRestricted
{
    public static function getRestriction();
    public function getFallback();
}