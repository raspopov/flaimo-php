<?php
/**
* Class for creating an Atom-Feed
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @copyright Copyright © 2002-2009, Michael Wimmer
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package Atom
* @version 1.03
*/
class AtomBuilderObjectIterator implements Iterator {

	protected $current = 0;
	protected $objectlist;

	function __construct(AtomBuilderObjectList &$list) {
		$this->objectlist =& $list;
		$this->objectlist->getList();
	} // end constructor

    public function valid() {
    	return ($this->current < $this->size()) ? TRUE : FALSE;
    } // end function

    public function next() {
    	return $this->current++;
    } // end function

    public function &current() {
    	return $this->objectlist->objects[$this->key()];
    } // end function

    public function key() {
    	return $this->current;
    } // end function

    public function size() {
		return count($this->objectlist->objects);
    } // end function

    public function rewind() {
		$this->current = 0;
	} // end function
} // end class
?>