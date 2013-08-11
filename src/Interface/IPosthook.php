<?php

/**
 * Mark a hook class as a post hook
 * 
 * @author Holger Szüsz <hszuesz@live.com>
 */
interface IPosthook
{
    public function runPost();
}