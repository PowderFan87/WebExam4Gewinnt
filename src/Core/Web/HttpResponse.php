<?php

/**
 * Description of Core_Web_HttpResponse
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Core_Web_HttpResponse extends Core_Web_Response
{

    public function __construct() {
        parent::__construct();

        $this->tplNavigation = "Widget_navigation";
    }
}