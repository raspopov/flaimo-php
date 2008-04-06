<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';
/**
* enhances the user object with more data
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessUserExtended extends AccessUser {
	/**#@+
	* @var string
	*/
	protected $firstname;
	protected $lastname;
	protected $email;
	protected $address;
	protected $zip;
	protected $town;
	protected $phone;
	/**#@-*/
	/**
	* @var int
	*/
	protected $country;
	/**
	* @var array
	*/
	protected $history_extended;

	/**
	* Constructor
	* @param int $id id of the user in the database
	* @return void
	*/
	function __construct($id = 0) {
		parent::__construct($id);
	} // end constructor

	/**
	* fetched the data from the database and writes it to the class vars
	* @return boolean
	*/
	protected function fetchData() {
		$r_value = FALSE;
		if ($this->datafetched != FALSE) { return TRUE;}
		$sp = 'CALL getUserDataExtended(' . parent::getConn()->real_escape_string($this->getID()) . ');';
		if (!parent::getConn()->multi_query($sp)) {return FALSE;}
		$result = parent::getConn()->store_result();
		if (!$result) {return FALSE;}
    $this->datafetched = TRUE;
		while($row = $result->fetch_row()) {
			$this->original_data = $row;
			$this->username = $row[0];
      $this->password = $row[1];
      $this->status = $row[2];
      $this->identifier = $row[3];
      $this->token = $row[4];
      $this->timeout = $row[5];

			$this->firstname = $row[6];
      $this->lastname = $row[7];
			$this->original_email = $row[8];
			$this->email = $row[8];
      $this->address = $row[9];
      $this->zip = $row[10];
      $this->town = $row[11];
      $this->country = $row[12];
      $this->phone = $row[13];
			$_value = TRUE;
    } // end while
    $result->close();
	  do {/*thorw away other results*/} while (parent::getConn()->next_result());
		return $r_value;
	} // end function

	/**
	* writes the data in the class vars to the database
	*
	* return codes:
	* -1 = snapshot of old data couldn't be written to the db
	* -2 = data couldn't be written to db
	* -3 = metadata couldn't be written to the db
	*  1 = data successfully updated, confirmation mail was send
	*  2 = data successfully updated
	*
	* @param boolean $bypass bypass writing history of data
	* @return int
	*/
	public function updateData($bypass = FALSE) {
		if ($bypass == FALSE && parent::getAccessSetting('use_data_history') == TRUE) {
			if ($this->saveSnapshot() != TRUE) {return -1;} // end if
		} // end if

		if (parent::getAccessSetting('confirm_email') == TRUE && ($this->getEmail() != $this->original_data[8])) {
			if ($this->saveSnapshot(TRUE) != TRUE) {return -1;} // end if
			
			$this->setStatus(ACCESS_STATUS_CONFIRM_CHANGE);
			$success = parent::setConfirmation($this->getID(), ACCESS_STATUS_CONFIRM_CHANGE);
			if ($success < 1) {
				return 'm:' . $success; // errorcode vom versenden weitergeben
			} // end if
			
			if (parent::updateDataBase() == FALSE || $this->updateDataExtended() == FALSE) {return -2;} // end if
			if (parent::updateMetadataLastChange() == FALSE) {return -3;} // end if
			return 1;
		} // end if

		if (parent::updateDataBase() == FALSE || $this->updateDataExtended() == FALSE) {return -2;} // end if
    if (parent::updateMetadataLastChange() == FALSE) {return -3;} // end if
		return 2;
	} // end function

	/**
	* writes the extended data to the database
	* @return boolean
	*/
	protected function updateDataExtended() {
		$sql  = 'UPDATE `' . $this->tables['extended'] . '` SET ';
		$sql .= '`firstname` = "' . parent::getConn()->real_escape_string($this->getFirstname()) . '", `lastname` = "' . parent::getConn()->real_escape_string($this->getLastname()) . '", `email` = "' . parent::getConn()->real_escape_string($this->getEmail()) . '", `address` = "' . parent::getConn()->real_escape_string($this->getAddress()) . '", `zip` = "' . parent::getConn()->real_escape_string($this->getZIP()) . '", `town` = "' . parent::getConn()->real_escape_string($this->getTown()) . '", `country` = ' . parent::getConn()->real_escape_string($this->getCountry()) . ', `phone` = "' . parent::getConn()->real_escape_string($this->getPhone()) . '" ';
		$sql .= 'WHERE `user` = ' . parent::getConn()->real_escape_string(parent::getID());
		$result =  parent::getConn()->query($sql);
		if (!$result) {return FALSE;}
    return TRUE;
	} // end function

	/**
	* writes a backup of the original data to the database
	* @return mixed true or errorcode
	*/
	protected function saveSnapshot($bypass = FALSE) {
		$this->fetchData();
		if ($bypass == FALSE) {
			parent::saveSnapshot();
		} // end if
		$sql  = 'INSERT INTO `' . $this->tables['history_extended'] . '` ';
		$sql .= '(`user`, `firstname`, `lastname`, `email`, `address`, `zip`, `town`, `country`, `phone`, `date`, `ip`) ';
		$sql .= 'VALUES (' . parent::getConn()->real_escape_string(parent::getID()) . ', "' . $this->original_data[6] . '", "' . $this->original_data[7] . '", "' . $this->original_data[8] . '", "' . $this->original_data[9] . '", "' . $this->original_data[10] . '", "' . $this->original_data[11] . '", ' . $this->original_data[12] . ', "' . $this->original_data[13] . '", UNIX_TIMESTAMP(), INET_ATON("' . parent::getConn()->real_escape_string(parent::getCurrentIP()) . '"))';
		$result =  parent::getConn()->query($sql);
    if (!$result) {return -1;}
    return TRUE;
	} // end function

	/**
	* sets the first name. checks if min/max length is ok
	* @param string $string username
	* @return boolean
	*/
	public function setFirstname($string = '') {
    $this->fetchData();
		$length = strlen(trim($string));
		if ($length < 0 || $length > 200) { return FALSE; }
		$this->firstname = (string) $string;
		return TRUE;
	} // end function

	/**
	* sets the last name. checks if min/max length is ok
	* @param string $string username
	* @return boolean
	*/
	public function setLastname($string = '') {
		$length = strlen(trim($string));
    $this->fetchData();
		if ($length < 0 || $length > 200) { return FALSE; }
		$this->lastname = (string) $string;
		return TRUE;
	} // end function
	
	/**
	* sets the email address. checks if it is a valid string and if it is unique in the DB
	* @param string $string string
	* @param boolean $bypass whether to bypass validation and DB check or not
	* @return int -1=not a vlaid string, -2=already exists in the DB, 1=OK
	*/
	public function setEmail($string = '', $bypass = FALSE) {
		$length = strlen(trim($string));
    $this->fetchData();
		if ($length < 0 || $length > 255) { return FALSE; }

		if ($bypass == FALSE) {
			if (parent::isEmailString($string) == FALSE) {
				return -1;
			} // end if

			if (parent::getAccessSetting('unique_email') == TRUE) {
				$sql = 'SELECT COUNT(*) FROM `' . $this->tables['extended'] . '` WHERE `email` = "' . parent::getConn()->real_escape_string($string) . '" AND `user` <> ' . parent::getConn()->real_escape_string(parent::getID());
				$result =  parent::getConn()->query($sql);
				$row = $result->fetch_row();
				if ($row[0] > 0) { return -2; } // end if
		    $result->close();
			} // end if
		} // end if

		$this->email = (string) $string;
		return 1;
	} // end function

	/**
	* sets the address. checks if min/max length is ok
	* @param string $string string
	* @return boolean
	*/
	public function setAddress($string = '') {
		$length = strlen(trim($string));
    $this->fetchData();
		if ($length < 0 || $length > 200) { return FALSE; }
		$this->address = (string) $string;
		return TRUE;
	} // end function

	/**
	* sets the ZIP code. checks if min/max length is ok
	* @param string $string string
	* @return boolean
	*/
	public function setZIP($string = '') {
		$length = strlen(trim($string));
    $this->fetchData();
		if ($length < 0 || $length > 10) { return FALSE; }
		$this->zip = (string) $string;
		return TRUE;
	} // end function

	/**
	* sets the town. checks if min/max length is ok
	* @param string $string string
	* @return boolean
	*/
	public function setTown($string = '') {
		$length = strlen(trim($string));
    $this->fetchData();
		if ($length < 0 || $length > 200) { return FALSE; }
		$this->town = (string) $string;
		return TRUE;
	} // end function

	/**
	* sets the country code
	* @param int $int the country code
	* @param boolean $bypass whether to bypass validation and DB check or not
	* @return boolean
	*/
	public function setCountry($int = 0, $bypass = FALSE) {
		$int = (int) $int;
		if ($int < 0 || $int > 9999) { return FALSE; }

		if ($bypass == FALSE) {
			// hier ev code für countryüberprüfung einbauen
		} // end if

    $this->fetchData();
		$this->country = (int) $int;
		return TRUE;
	} // end function

	/**
	* sets the phone number
	* @param string $string
	* @return boolean
	*/
	public function setPhone($string = '') {
		$length = strlen(trim($string));
		if ($length < 0 || $length > 50) { return FALSE; }
    $this->fetchData();
		$this->phone = (string) $string;
		return TRUE;
	} // end function

	/**#@+
	* getter methods for user vars
	* @return mixed
	*/
	public function getFirstname() {
		$this->fetchData();
		return $this->firstname;
	} // end function

	public function getLastname() {
		$this->fetchData();
		return $this->lastname;
	} // end function

	public function getEmail() {
		$this->fetchData();
		return $this->email;
	} // end function

	public function getAddress() {
		$this->fetchData();
		return $this->address;
	} // end function

	public function getZIP() {
		$this->fetchData();
		return $this->zip;
	} // end function

	public function getTown() {
		$this->fetchData();
		return $this->town;
	} // end function

	public function getCountry() {
		$this->fetchData();
		return $this->country;
	} // end function

	public function getPhone() {
		$this->fetchData();
		return $this->phone;
	} // end function
	/**#@-*/

	/**
	* returns an array with all the data history for the user
	* @param boolean $force_update
	* @return array
	*/
	public function getDataHistoryExtended($force_update = FALSE) {
		if ($force_update == FALSE && isset($this->history_extended)) {
			return $this->history_extended;
		} // end if

		$sql = 'SELECT `firstname`, `lastname`, `email`, `address`, `zip`, `town`, `country`, `phone`, `date`, INET_NTOA(`ip`) as `ip` FROM `' . $this->tables['history_extended'] . '` WHERE `user` = ' . parent::getConn()->real_escape_string($this->getID()) . ' ORDER BY `date` DESC';
		$result =  parent::getConn()->query($sql);
		$this->history_extended = array();
		while($row = $result->fetch_row()) {
			$entry['firstname'] = $row[0];
     	$entry['lastname'] = $row[1];
      $entry['email'] = $row[2];
      $entry['address'] = $row[3];
      $entry['zip'] = $row[4];
      $entry['town'] = $row[5];
      $entry['country'] = $row[6];
      $entry['phone'] = $row[7];
		  $entry['date'] = $row[8];
      $entry['ip'] = $row[9];
      $this->history_extended[] = $entry;
		} // end while
		$result->close();
		return $this->history_extended;
	} // end function

	/**
	* rewrites the data before the current changes back to the user table
	* @param boolean $bypass whether the base data should be revived too or not
	* @return boolean returns false if there was a DB write error
	*/
	public function reviveLastData($bypass = TRUE) {
		$bypass = (boolean) $bypass;

		if ($bypass == FALSE) {
		  if (parent::reviveLastData() == FALSE) {return FALSE;} // end if
		} else {
			$this->saveSnapshot(TRUE);
		} // end if

		$old_rec = FALSE;
		$sql = 'SELECT `firstname`, `lastname`, `email`, `address`, `zip`, `town`, `country`, `phone` FROM `' . $this->tables['history_extended'] . '` WHERE `user` = ' . parent::getConn()->real_escape_string($this->getID()) . ' ORDER BY `date` DESC LIMIT 2';

		$result =  parent::getConn()->query($sql);
		while($row = $result->fetch_row()) {
			if ($old_rec == FALSE) {
        $old_rec = TRUE;
				continue;
			} // end if
			$sql2  = 'UPDATE `' . $this->tables['extended'] . '` SET ';
			$sql2 .= '`firstname` = "' . $row[0] . '", `lastname` = "' . $row[1] . '", `email` = "' . $row[2] . '", `address` = "' . $row[3] . '", `zip` = "' . $row[4] . '", `town` = "' . $row[5] . '", `country` = ' . $row[6] . ', `phone` = "' . $row[7] . '" ';
			$sql2 .= 'WHERE `user` = ' . parent::getConn()->real_escape_string($this->getID());
		} // end while
		$result->close();

		$result =  parent::getConn()->query($sql2);
    if (!$result) {return FALSE;}

    if (parent::updateMetadataLastChange() == FALSE) {
      return FALSE;
		} // end if
		return TRUE;
	} // end function
} // end class
?>
