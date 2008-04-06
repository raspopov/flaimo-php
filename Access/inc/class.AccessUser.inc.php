<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';
require_once 'interface.AccessUserInterface.inc.php';

/**
* user object
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessUser extends AccessBase {
	/**#@+
	* @var int
	*/
	protected $id;
	protected $status;
	protected $timeout;
	/**#@-*/
	/**#@+
	* @var string
	*/
	protected $username;
	protected $password;
	protected $identifier;
	protected $token;
	/**#@-*/
	/**#@+
	* @var array
	*/
	protected $roles;
	protected $original_data;
	protected $history_base;
	/**#@-*/
	/**
	* @var boolean
	*/
	protected $datafetched = FALSE;


	/**
	* Constructor
	* @param int $id id of the user in the database
	* @return void
	*/
	function __construct($id = 0) {
		$this->id = (int) $id;
	} // end constructor

	/**
	* fetched the data from the database and writes it to the class vars
	* @return boolean
	*/
	protected function fetchData() {
		$r_value = FALSE;
		if ($this->datafetched != FALSE) { return TRUE;}
		$sp = 'CALL getUserDataBase(' . parent::getConn()->real_escape_string($this->id) . ');';
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
			$_value = TRUE;
    } // end while
    $result->close();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return $r_value;;
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

		if ($this->updateDataBase() == FALSE) {return -2;} // end if
    if ($this->updateMetadataLastChange() == FALSE) {return -3;} // end if
		return 2;
	} // end function

	/**
	* writes the base data  to the database
	* @return boolean
	*/
	protected function updateDataBase() {
		$sp = 'CALL updateDataBase(' . parent::getConn()->real_escape_string($this->getID()) . ', "' . parent::getConn()->real_escape_string($this->getUsername()) . '", "' . parent::getConn()->real_escape_string($this->getPassword()) . '", ' . parent::getConn()->real_escape_string($this->getStatus()) . ', "' . parent::getConn()->real_escape_string($this->getIdentifier()) . '", "' . parent::getConn()->real_escape_string($this->getToken()) . '", ' . parent::getConn()->real_escape_string($this->getTimeout()) . ');';
		if (!parent::getConn()->multi_query($sp)) {return FALSE;}
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return TRUE;
	} // end function
	
	/**
	* updates the last change flags to the database
	* @return boolean
	*/
	protected function updateMetadataLastChange() {
		if ($this->fetchData() == FALSE) { return -1;}
		$id = parent::getConn()->real_escape_string($this->getID());
		$ip = parent::getConn()->real_escape_string(parent::getCurrentIP());
		$sp = 'CALL updateMetadataLastChange(' . $id . ', "' . $ip . '");';
		if (!parent::getConn()->multi_query($sp)) {return -1;}
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return TRUE;
	} // end function

	/**
	* writes a backup of the original data to the database
	* @return mixed true or errorcode
	*/
	protected function saveSnapshot() {
		if ($this->fetchData() == FALSE) { return -1;}
		$sp = 'CALL saveSnapshotBase(' . parent::getConn()->real_escape_string($this->getID()) . ', "' . $this->original_data[0] . '", "' . $this->original_data[1] . '", ' . $this->original_data[2] . ', "' . $this->original_data[3] . '", "' . $this->original_data[4] . '", ' . $this->original_data[5] . ', "' . parent::getConn()->real_escape_string(parent::getCurrentIP()) . '");';
		if (!parent::getConn()->multi_query($sp)) {return -1;}
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return TRUE;
	} // end function
	
	/**
	* sets the username. checks if min/max length is ok and that name doesn't already exist in DB
	* @param string $string username
	* @return int -1=too long/short, -2=db error, -3=username already taken, 1=OK
	*/
	public function setUsername($string = '') {
    $this->fetchData();
		$min_length = parent::getAccessSetting('username_min_length');
		$max_length = parent::getAccessSetting('username_max_length');
		$length = strlen(trim($string));
		if ($length < $min_length || $min_length > $max_length) {	return -1; } // end if
		$sp = 'CALL usernameExists(' . parent::getConn()->real_escape_string($this->getID()) . ', "' . parent::getConn()->real_escape_string($string) . '");';
		if (!parent::getConn()->multi_query($sp)) {return -2;}
		$result = parent::getConn()->store_result();
		if (!$result) {return -2;}
		$r_value = 1;
		while($row = $result->fetch_row()) {
			if ($row[0] > 0) {
				$r_value = -3;
				break;
			} // end if
    } // end while
    $result->close();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		if ($r_value == 1) { $this->username = (string) $string; }
		return $r_value;
	} // end function
	
	/**
	* sets the password. checks if min/max length is ok and encrypts it
	* @param string $string username
	* @return int -1=too long/short, 1=OK
	*/
	public function setPassword($string = '', $cipher = FALSE) {
// custom reg express noch einbauen
    $this->fetchData();
		$min_length = parent::getAccessSetting('pw_min_length');
		$max_length = parent::getAccessSetting('pw_max_length');
		$length = strlen(trim($string));
		if ($length < $min_length || $min_length > $max_length) {	return -1; } // end if

		if ($cipher != TRUE) {
			$string = parent::generateCipherPassword($string);
		} // end if

		$this->password = (string) $string;
		return 1;
	} // end function

	/**
	* sets the status. checks if it is a valid integer
	* @param int $status
	* @return boolean
	*/
	public function setStatus($status = 2) {
		$status = (int) $status;
    $this->fetchData();
		if (parent::checkStatus($status) == FALSE) { return FALSE; }
		$this->status = $status;
		return TRUE;
	} // end function

	/**
	* sets the identifier. if no string given, a random one is created
	* @param string $string
	* @return boolean
	*/
	public function setIdentifier($string = '') {
	  $this->fetchData();
		if (parent::isFilledString($string) == TRUE) {
		  $this->identifier = (string) $string;
		  return TRUE;
		} // end if

		$this->identifier = md5(parent::getAccessSetting('password_salt') . md5($this->getUsername() . parent::getAccessSetting('password_salt')));
		return TRUE;
	} // end function

	/**
	* sets the token. if no string given, a random one is created
	* @param string $string
	* @return boolean
	*/
	public function setToken($string = FALSE) {
	  $this->fetchData();
		if (parent::isFilledString($string) == TRUE) {
			$this->token = (string) $string;
		  return TRUE;
		} // end if

		$this->token = md5(uniqid(rand(), TRUE));
		return TRUE;
	} // end function

	/**
	* sets the timeout value
	* @param int $int unix_timestamp
	* @return boolean
	*/
	public function setTimeout($int = 0) {
		$int = (int) $int;
		if ($int < 0 || $int > 2147483646) { return FALSE; } // end if
	  $this->fetchData();
		$this->timeout = $int;
		return TRUE;
	} // end function

	/**
	* checks if the given cleartext password is the same as the one in the DB
	* @param string $cleartext
	* @return boolean
	*/
	public function checkPassword($cleartext = '') {
		$cipher = parent::generateCipherPassword($cleartext);
		return ($cipher == $this->getPassword()) ? TRUE : FALSE;
	} // end function

	/**#@+
	* getter methods for user vars
	* @return mixed
	*/
	public function getID() {
		return $this->id;
	} // end function

	public function getUsername() {
		$this->fetchData();
		return $this->username;
	} // end function

	protected function getPassword() {
		$this->fetchData();
		return $this->password;
	} // end function

	public function getStatus() {
		$this->fetchData();
		return $this->status;
	} // end function

	public function getIdentifier() {
		$this->fetchData();
		return $this->identifier;
	} // end function

	public function getTimeout() {
		$this->fetchData();
		return $this->timeout;
	} // end function

	public function getToken() {
		$this->fetchData();
		return $this->token;
	} // end function
	/**#@-*/

	/**
	* fetches the roles (object)) for the user from the DB and returns them
	* @return array
	*/
	public function getRoles() {
		if (isset($this->roles)) {
			return $this->roles;
		} // end if
		
		if (parent::getAccessSetting('cache_roles') == TRUE && isset($_SESSION['access_userroles'])) {
      $this->roles = $_SESSION['access_userroles'];
			return $this->roles;
		} // end if

		$roles = new AccessRoles($this);
		$this->roles = $roles->getRoles();
		
		if (parent::getAccessSetting('cache_roles') == TRUE) {
      $_SESSION['access_userroles'] = $this->roles;
		} // end if
		return $this->roles;
	} // end function

	/**
	* returns an array with all the data history for the user
	* @param boolean $force_update
	* @return array
	*/
	public function getDataHistory($force_update = FALSE) {
		if ($force_update == FALSE && isset($this->history_base)) {
			return $this->history_base;
		} // end if

		$sp = 'CALL getDataHistoryBase(' . parent::getConn()->real_escape_string($this->getID()) . ');';
		if (!parent::getConn()->multi_query($sp)) {return FALSE;}

		$result = parent::getConn()->store_result();
		if (!$result) {return FALSE;}
		$this->history_base = array();
		while($row = $result->fetch_row()) {
			$entry['username'] = $row[0];
     	$entry['password'] = $row[1];
      $entry['status'] = $row[2];
      $entry['identifier'] = $row[3];
      $entry['token'] = $row[4];
      $entry['timeout'] = $row[5];
      $entry['date'] = $row[6];
      $entry['ip'] = $row[7];
      $this->history_base[] = $entry;
    } // end while
		$result->close();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return $this->history_base;
	} // end function

	/**
	* rewrites the data before the current changes back to the user table
	* @return boolean returns false if there was a DB write error
	*/
	public function reviveLastData() {
		$this->saveSnapshot();

		$sp = 'CALL reviveLastDataBase(' . parent::getConn()->real_escape_string($this->getID()) . ');';
		if (!parent::getConn()->multi_query($sp)) {return -1;}
		$result = parent::getConn()->store_result();
		if (!$result) {return -1;}
		$row = $result->fetch_row();
		$result->close();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		$sp = 'CALL updateDataBase(' . parent::getConn()->real_escape_string($this->getID()) . ', "' . $row[0] . '", "' . $row[1] . '", ' . $row[2] . ', "' . $row[3] . '", "' . $row[4] . '", ' . $row[5] . ');';

		if (!parent::getConn()->multi_query($sp)) {return -2;}
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());

		if ($this->updateMetadataLastChange() == FALSE) {
      return FALSE;
		} // end if
		return TRUE;
	} // end function
} // end class

?>
