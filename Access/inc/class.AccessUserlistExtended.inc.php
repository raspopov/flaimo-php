<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';

/**
* generates a list of extended user objects
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessUserListExtended extends AccessUserList implements Iterator {

	/**
	* Constructor
	* @param int $from starting point of the list
	* @param int $size (max) number of entries in the list
	* @param string $order name of the columns to order the list after
	* @param string $direction direction of the order (ASC or DESC)
	* @return void
	*/
	function __construct($from = 0, $size = 20, $order = 'id', $direction = 'DESC') {
		$add_columns = array('firstname', 'lastname', 'email', 'address', 'zip', 'town', 'country', 'phone');
		$this->possible_columns = array_merge($this->possible_columns, $add_columns);
		parent::__construct($from, $size, $order, $direction);
	} // end constructor

	/**
	* fetched the user IDs from the database, creates user objects from them and puts them in an array
	* @return boolean
	*/
	protected function fetchData() {
		if (isset($this->ulist)) {return TRUE;}
		$sql = 'SELECT b.`id` FROM `' . $this->tables['base'] . '` as b LEFT JOIN `' . $this->tables['extended'] . '` as e ON (b.`id` = e.`user`) ORDER BY `' . $this->order . '` ' . $this->direction  . ' LIMIT ' . $this->from . ', ' . $this->size;
		$result =  parent::getConn()->query($sql);
		if (!$result) {return FALSE;}

		$this->ulist = array();
		while($row = $result->fetch_row()) {
			$this->ulist[$row[0]] = new AccessUserExtended($row[0]);
		} // end while

		$result->close();
		return TRUE;
	} // end function
} // end class
?>
