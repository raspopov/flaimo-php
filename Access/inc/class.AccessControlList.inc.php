<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';

/**
* holds all the ACEs for an ACL
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessControlList extends AccessBase {
	/**
	* @var int id of the ACL in the database
	*/
	protected $id;
	/**
	* @var array holds all the ACEs for the ACL
	*/
	protected $aces;


	/**
	* Constructor
	* @param int $id id of the ACL in the database
	* @return void
	*/
	function __construct($id = 0) {
		$id = (int) $id;
		$this->id = $id;
	} // end constructor

	/**
	* returns the ID of the ACL
	* @return int
	*/
	function getID() {
		return $this->id;
	} // end function
	
	/**
	* fetches the ACEs from the database and writes them to the array
	* @param boolean $force forces the fetching even if values are already there
	* @return boolean
	*/
	protected function fetchACEs($force = FALSE) {
		$force = (boolean) $force;
		if ($force == TRUE || !isset($this->aces)) {
      $this->aces = array();

			$sp = 'CALL getACEs(' . $this->id . ');';
	    if (!parent::getConn()->multi_query($sp)) {return FALSE;}
			$result = parent::getConn()->store_result();
			if (!$result) {return FALSE;}

			while($row = $result->fetch_row()) {
				$this->aces[$row[0]] = array('group' => (int) $row[1], 'position' => (int) $row[2], 'rights' => $row[3]);
	    } // end while
	    $result->close();
		  do {} while (parent::getConn()->next_result());
		  return TRUE;
	  } // end if
	  return TRUE;
	} // end function

	/**
	* adds an ACE to the ACL
	* @param int $group
	* @param int $position
	* @param string $rights comma-seperated string with the rights. possible values debend on DB column type
	* @param int $from unix timestamp which tells from what point on the entry is valid
	* @param int $until unix timestamp which tells until what point on the entry is valid
	* @param int $order position of the ACE in the ACL
	* @return boolean
	*/
	public function addACE($group = 1, $position = 1, $rights = '', $from = 0, $until = 2147483646, $order = 0) {
    $group = (int) $group;
    $position = (int) $position;
    $from = (int) $from;
    $until = (int) $until;
    $order = (int) $order;
    $rights = parent::getConn()->real_escape_string($rights);

		if (parent::getGroupName($group) == FALSE || parent::getPositionName($position) == FALSE || $from < 0 || $until > 2147483646 || $until < $from) {
			return FALSE;
		} // end if

		$r_value = FALSE;
		$sp = 'CALL addACE(' . $this->id . ', ' . $group . ', ' . $position . ', "' . $rights . '", ' . $from . ', ' . $until . ', ' . $order . ');';
		if (parent::getConn()->multi_query($sp)) {
			$this->fetchACEs(TRUE);
			$r_value = TRUE;
		} // end if
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return $r_value;
	} // end function

	/**
	* deletes an ACE from the ACL
	* @param int $id the id of the ACE in the database
	* @return boolean
	*/
	public function deleteACE($id = 0) {
    $id = (int) $id;
		$sql = 'DELETE FROM `' . $this->tables['aces'] . '` WHERE `id` = ' . parent::getConn()->real_escape_string($id);
		if (parent::getConn()->query($sql)) {
    	$this->fetchACEs(TRUE);
    	return TRUE;
		} // end if
		return FALSE;
	} // end function

	/**
	* returns the list of ACEs
	* @return array
	*/
	public function getACEs() {
		$this->fetchACEs();
		return $this->aces;
	} // end function

	/**
	* returns true/falls for a requestet right for a specific user. first checks if there is a matching position/group role entry, then checks if there is a fitting group entry and at last checks if there is an All/All entry
	* @param object $user AccessUser object
	* @param string $right
	* @return boolean
	*/
	public function checkRights(AccessUser $user = NULL, $right = '') {
		$aces = $this->getACEs();
		$roles = $user->getRoles();
		
		// 1 exakte group/pos regelung finden
		foreach ($aces as $ace_id => $ace_data) {
			$group = $ace_data['group'];
			$position = $ace_data['position'];

			foreach ($roles as $role_id => $role_data) {
				if ($role_data['group'] != $group || $role_data['position'] != $position) {
					continue;
				} // end if
				$pos = stripos($ace_data['rights'], $right);
				return (boolean) ($pos !== FALSE) ? TRUE : FALSE;
			} // end foreach
		} // end foreach
		
		// 2 nur passende gruppe und position "all" finden
		foreach ($aces as $ace_id => $ace_data) {
			$group = $ace_data['group'];
			$position = $ace_data['position'];
			if ($position != 1) { continue; }


			foreach ($roles as $role_id => $role_data) {
				if ($role_data['group'] != $group || $role_data['position'] != $position) {
					continue;
				} // end if
				$pos = stripos($ace_data['rights'], $right);
				return (boolean) ($pos !== FALSE) ? TRUE : FALSE;
			} // end foreach
		} // end foreach

		// 3 schaun ob all/all eintrag vorhanden ist
		foreach ($aces as $ace_id => $ace_data) {
			$group = $ace_data['group'];
			$position = $ace_data['position'];
			if ($position != 1 || $group != 1) { continue; }

			$pos = stripos($ace_data['rights'], $right);
			return (boolean) ($pos !== FALSE) ? TRUE : FALSE;
		} // end foreach
		
		// falls gar keine übereinstimmung gefunden false zurückgeben
		return FALSE;
	} // end function
} // end class
?>
