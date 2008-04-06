<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';

/**
* holds all the rolse for a given user
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessRoles extends AccessBase {

	/**
	* @var object AccessUser object
	*/
	protected $user;

	/**
	* @var array
	*/
	protected $roles;

	/**
	* Constructor
	* @param object $user AccessUser object
	* @return void
	*/
	function __construct(AccessUser &$user = NULL) {
		if ($user instanceOf AccessUser) {
			$this->user = $user;
		} else {
			// error werfen
		} // end if
	} // end constructor

	/**
	* gets the roles from the db and writes them to the array
	* @param boolean $force forces the fetching even if values are already there
	* @return boolean
	*/
	protected function fetchRoles($force = FALSE) {
		$force = (boolean) $force;
		if ($force == TRUE || !isset($this->roles)) {
      $this->roles = array();

			$sp = 'CALL getRolesForUser(' . $this->user->getID() . ');';
	    if (!parent::getConn()->multi_query($sp)) {return FALSE;}
			$result = parent::getConn()->store_result();
			if (!$result) {return FALSE;}

			while($row = $result->fetch_row()) {
				$this->roles[$row[0]] = array('group' => (int) $row[1], 'position' => (int) $row[2]);
	    } // end while
	    $result->close();
		  do {} while (parent::getConn()->next_result());
		  return TRUE;
		} // end if
		return TRUE;
	} // end function

	/**
	* adds an role for the user
	* @param int $group
	* @param int $position
	* @param int $from unix timestamp which tells from what point on the entry is valid
	* @param int $until unix timestamp which tells until what point on the entry is valid
	* @param int $order position of the role in the list
	* @return boolean
	*/
	public function addRole($group = 1, $position = 1, $from = 0, $until = 2147483646, $order = 0) {
    $group = (int) $group;
    $position = (int) $position;
	  $from = (int) $from;
	  $until = (int) $until;
	  
		if (parent::getGroupName($group) == FALSE || parent::getPositionName($position) == FALSE || $from < 0 || $until > 2147483646 || $until < $from) {
			return FALSE;
		} // end if

		$r_value = FALSE;
		$sp = 'CALL addRoleForUser(' . $this->user->getID() . ', ' . $group . ', ' . $position . ', ' . $from . ', ' . $until . ', ' . $order . ');';
    if (parent::getConn()->multi_query($sp)) {
			$this->fetchRoles(TRUE);
			$r_value = TRUE;
		} // end if
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return $r_value;
	} // end function

	/**
	* deletes an role from the user
	* @param int $id the id of the entry in the database
	* @return boolean
	*/
	public function deleteRole($id = 0) {
    $id = (int) $id;
		$r_value = FALSE;
		$sp = 'CALL deleteRole(' . parent::getConn()->real_escape_string($id) . ');';
    if (parent::getConn()->multi_query($sp)) {
    	$this->fetchRoles(TRUE);
			$r_value = TRUE;
		} // end if
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return $r_value;
	} // end function
	
	/**
	* returns the list of roles
	* @return array
	*/
	public function getRoles() {
		$this->fetchRoles();
		return $this->roles;
	} // end function
	

	// iterator
	
	/*
	public function rewind() {
		$this->fetchRoles();
		return reset($this->roles);
	} // end function

	public function key() {
	  $this->fetchRoles();
		return key($this->roles);
	} // end function

	public function current() {
	  $this->fetchRoles();
		return current($this->roles);
	} // end function

	public function next() {
	  $this->fetchRoles();
		return next($this->roles);
	} // end function

	public function valid() {
	  $this->fetchRoles();
		$tmp = next($this->roles);
		prev($this->roles);
		return $tmp;
	} // end function
	*/
	
} // end class


?>
