<?php

/**
 * Description of Core_Base_Hook
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Base_Hook implements Iterator, Countable
{
    protected $_lngPosition = 0;
    protected $_arrElements = array();

    public function __construct() {
        $this->_lngPosition = 0;
    }
    
    public function count() {
        return count($this->_arrElements);
    }
    
    public function current() {
        return $this->_arrElements[$this->_lngPosition];
    }
    
    public function key() {
        return $this->_lngPosition;
    }
    
    public function next() {
        $this->_lngPosition++;
    }
    
    public function rewind() {
        $this->_lngPosition = 0;
    }
    
    public function valid() {
        return isset($this->_arrElements[$this->_lngPosition]);
    }
}