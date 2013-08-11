<?php

/**
 * Abstract base class for hook collection management
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
abstract class Core_Base_Hook implements Iterator, Countable
{
    protected $_lngPosition = 0;
    protected $_arrElements = array();

    /**
     * Init collection
     * 
     */
    public function __construct() {
        $this->_lngPosition = 0;
    }
    
    /**
     * return count of collection
     * 
     * @return type
     */
    public function count() {
        return count($this->_arrElements);
    }
    
    /**
     * Return hook on current position
     * 
     * @return IPosthook|IPrehook
     */
    public function current() {
        return $this->_arrElements[$this->_lngPosition];
    }
    
    /**
     * Get key on current position
     * 
     * @return type
     */
    public function key() {
        return $this->_lngPosition;
    }
    
    /**
     * Increment position
     * 
     */
    public function next() {
        $this->_lngPosition++;
    }
    
    /**
     * Reset position
     * 
     */
    public function rewind() {
        $this->_lngPosition = 0;
    }
    
    /**
     * Check if current position is set
     * 
     * @return type
     */
    public function valid() {
        return isset($this->_arrElements[$this->_lngPosition]);
    }
}