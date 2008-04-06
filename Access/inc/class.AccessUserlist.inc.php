<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';

/**
* generates a list of user objects
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessUserlist extends AccessBase implements Iterator {
	/**#@+
	* @var string
	*/
	protected $direction = 'DESC';
	protected $order = 'id';
	/**#@-*/
	/**#@+
	* @var int
	*/
	protected $size = 20;
	protected $from = 0;
	/**#@-*/
	/**#@+
	* @var array
	*/
	protected $ulist;
	protected $possible_columns = array();
	/**#@-*/
	
	/**
	* Constructor
	* @param int $from starting point of the list
	* @param int $size (max) number of entries in the list
	* @param string $order name of the columns to order the list after
	* @param string $direction direction of the order (ASC or DESC)
	* @return void
	*/
	function __construct($from = 0, $size = 20, $order = 'id', $direction = 'DESC') {
		$add_columns = array('id', 'username', 'password', 'status', 'identifier', 'token', 'timeout');
		$this->possible_columns = array_merge($this->possible_columns, $add_columns);
		$this->setFrom($from);
		$this->setSize($size);
		$this->setOrder($order);
		$this->setDirection($direction);
	} // end constructor

	/**
	* fetched the user IDs from the database, creates user objects from them and puts them in an array
	* @return boolean
	*/
	protected function fetchData() {
		if (isset($this->ulist)) {return TRUE;}
		$sql = 'SELECT `id` FROM `' . $this->tables['base'] . '` ORDER BY `' . $this->order . '` ' . $this->direction  . ' LIMIT ' . $this->from . ', ' . $this->size;

		$result =  parent::getConn()->query($sql);
		if (!$result) {return FALSE;}

		$this->ulist = array();
		while($row = $result->fetch_row()) {
			$this->ulist[$row[0]] = new AccessUser($row[0]);
		} // end while

		$result->close();
		return TRUE;
	} // end function

	/**
	* sets the starting point of the list
	* @param int $from
	* @return boolean
	*/
	public function setFrom($from = 0) {
		if ($from < 0) {return FALSE;}
		$this->from = (int) $from;
		return TRUE;
	} // end function

	/**
	* sets the number of entries
	* @param int $size
	* @return boolean
	*/
	public function setSize($size = 0) {
		$this->size = (int) $size;
		return TRUE;
	} // end function
	
	/**
	* sets the column after which to order the list with
	* @param string $order
	* @return boolean
	*/
	public function setOrder($order = 'id') {
		if (array_search($order, $this->possible_columns) === FALSE) {
			return FALSE;
		} // end if
		
		$this->order = (string) $order;
		return TRUE;
	} // end function
	
	/**
	* sets the direction of the order
	* @param string $direction ASC or DESC
	* @return boolean
	*/
	public function setDirection($direction = 'DESC') {
		if ($direction != 'ASC' && $direction != 'DESC') {
			return FALSE;
		} // end if

		$this->direction = (string) $direction;
		return TRUE;
	} // end function

	/**
	* returns the array with user objects
	* @return array
	*/
	public function getList() {
		$this->fetchData();
		return $this->ulist;
	} // end function

	/**#@+
	* iterator methods. NOT WORKING YET
	* @return mixed
	*/
	public function rewind() {
		$this->fetchData();
		return reset($this->ulist);
	} // end function

	public function key() {
		$this->fetchData();
		return key($this->ulist);
	} // end function

	public function current() {
		$this->fetchData();
		return current($this->ulist);
	} // end function

	public function next() {
		$this->fetchData();
		return next($this->ulist);
	} // end function

	public function valid() {
		$this->fetchData();
		$tmp = next($this->ulist);
		prev($this->ulist);
		return $tmp;
	} // end function
	/**#@-*/
} // end class
?>
