<?php
/**
* function for automatic including of required classes
*/
function __autoload($class){
    require_once( 'class.' . $class . '.inc.php');
} // end function

/**
* singleton function for getting the i18n settings once
* @return array
*/
function &setAccessSettings() {
    static $settings;
    if(!isset($settings)) {
		$settings = parse_ini_file('access_settings.ini');
    } // end if
    return $settings;
} // end function

/**
* singleton function for getting one db connection
* @return object
*/
function &setMySQLConn($host = '', $user = '', $pw = '', $database = '') {
	static $conn;
	if (!isset($conn)) {
		$conn = new mysqli($host, $user, $pw, $database);
 		$result = $conn->query('SET CHARACTER SET utf8');
	} // end if
	return $conn;
} // end function

define('ACCESS_STATUS_ACTIVE', 1);
define('ACCESS_STATUS_INACTIVE', 2);
define('ACCESS_STATUS_LOCKED_PERM', 3);
define('ACCESS_STATUS_LOCKED_TEMP', 4);
define('ACCESS_STATUS_CONFIRM_REGISTRATION', 5);
define('ACCESS_STATUS_CONFIRM_CHANGE', 6);

/**
* The mother of all (Access) classes :-); provides basic methods used by all other classes
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
abstract class AccessBase {
	/**#@+
	* @var array
	*/
	protected $settings;
	protected $tables = array('aces' => 'aces', 'acls' => 'acls', 'base' => 'base', 'extended' => 'extended', 'groups' => 'groups', 'history_base' => 'history_base', 'history_extended' => 'history_extended', 'meta' => 'meta', 'positions' => 'positions', 'roles' => 'roles', 'status' => 'status', 'logins' => 'logins', 'v_activeusers' => 'v_activeusers');
	protected $allowed_datatypes = array('string', 'int', 'boolean', 'object', 'float', 'array');
	protected $possible_groups;
	protected $possible_positions;
	protected $possible_status;
	/**#@-*/
	/**
	* @var string
	*/
	protected $current_ip;

	/**
	* get the access settings from the ini file once
	* @uses setAccessSettings()
	* @uses AccessBase::$settings
	* @return boolean
	*/
	protected function readAccessSettings() {
		$this->settings = setAccessSettings();
		return (boolean) ($this->settings == FALSE) ? FALSE: TRUE;
	} // end function

	/**
	* returns a value for a requested setting
	* @param string $setting
	* @uses AccessBase::$settings
	* @uses AccessBase::readAccessSettings()
	* @return mixed
	*/
	public function getAccessSetting($setting = '') {
		if (!isset($this->settings)) {
			$this->readAccessSettings();
		} // end if
		if (array_key_exists($setting, $this->settings)) {
			return $this->settings[$setting];
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* fetches the db connection from the singleton function
	* @uses setMySQLConn()
	* @return resource
	*/
	protected function getConn() {
		return setMySQLConn($this->getAccessSetting('db_host'), $this->getAccessSetting('db_user'), $this->getAccessSetting('db_pw'), $this->getAccessSetting('db_database'));
	} // end function

	/**
	* assigns a value to a class var with a given type
	* @param mixed $data
	* @param string $var_name
	* @param string $type
	* @return mixed
	*/
	protected function setVar($data = FALSE, $var_name = '', $type = 'string') {
		if (!in_array($type, $this->allowed_datatypes) ||
			$type != 'boolean' && ($data === FALSE ||
			$this->isFilledString($var_name) === FALSE)) {
			return (boolean) FALSE;
		} // end if

		switch ($type) {
			case 'string':
				if ($this->isFilledString($data) === TRUE) {
					$this->$var_name = (string) trim(stripslashes($data));
					return (boolean) TRUE;
				} // end if
			case 'int':
				//echo $var_name . ':' . $data . '(' . ((int) is_numeric($data)) . ')<br>';
				//if (ctype_digit($data)) {
				if (is_numeric($data)) {
					$this->$var_name = (int) $data;
					return (boolean) TRUE;
				} // end if
			case 'boolean':
				if (is_bool($data)) {
					$this->$var_name = (boolean) $data;
					return (boolean) TRUE;
				}  // end if
			case 'object':
				if (is_object($data)) {
					$this->$var_name = $data;
					return (boolean) TRUE;
				} // end if
			case 'array':
				if (is_array($data)) {
					$this->$var_name = (array) $data;
					return (boolean) TRUE;
				} // end if
		} // end switch
		return (boolean) FALSE;
	} // end function

	/**
	* returns a requested class var
	* @param string $var_name
	* @return mixed
	*/
	protected function getVar($var_name = 'dummy') {
		return (isset($this->$var_name)) ? $this->$var_name: FALSE;
	} // end function

	/**
	* checks if a given string is empty or is shorter than the given limit
	* @param string $var
	* @param int $min_length
	* @return boolean
	*/
	public static function isFilledString($var = '', $min_length = 0) {
	/*	if ($min_length == 0) {
			//echo $var . ':' . ((int) !ctype_space($var)) . '<br>';
			return !ctype_space($var);
		} // end if*/
		return (boolean) (mb_strlen(trim($var)) > $min_length) ? TRUE : FALSE;
	} // end function

	/**
	* returns a confirmation token for a given user id and email address
	* @param int $user_id
	* @param string $mail
	* @return string
	*/
	protected function createConfirmationToken($user_id = 0, $mail = '') {
		return md5($this->getAccessSetting('password_salt') . $user_id . $mail . time());
	} // end function

	/**
	* sets the confirmation-token and a validation-flag for status for a user and triggers a sendmail with the confirmation-token
	*
	* return codes:
	* -5 = requested status isnt a confirmation status
	* -4 = DB Error
	* -3 = confirmation mail counldnt been send
	* -2 = original user status isnt set to active. only active accounts can be changed
	* -1 = no user with the token could be found in the db
	*  1 = ok
	*
	* @param int $user_id
	* @param int $user_id
	* @return int
	*/
	protected function setConfirmation($user_id = 0, $status = 6) {
		$user_id = $this->getConn()->real_escape_string($user_id);
		$user_status = 0;
		$user_mail = '';
		$requested_status = (int) $status;
		if ($requested_status < 5) { return -5; }
		
		$sql = 'SELECT e.`email`, b.`status` FROM `' . $this->tables['extended'] . '` AS e LEFT JOIN `' . $this->tables['base'] . '` AS b ON (e.`user` = b.`id`) WHERE e.user = ' . $user_id;
		$result =  $this->getConn()->query($sql);
    if (!$result) {return -4;}
    
		while($row = $result->fetch_row()) {
      $user_mail = (string) $row[0];
      $user_status = (int) $row[1];
		} // end while
    $result->close();
		unset($result, $sql);
		
		if ($user_id <= 0) {
			return -1;
		} elseif ($user_status <> 1) {
			return -2;
		} // end if
		
		$token = $this->createConfirmationToken($user_id, $user_mail);
		if ($this->sendConfirmationMail($user_mail, $token) == FALSE) {
			return -3;
		} // end if
		

    $sql = 'UPDATE `' . $this->tables['meta'] . '` SET `confirmation_token` = "' . $token . '" WHERE `user` = ' . $user_id;
    $result = $this->getConn()->query($sql);
    if (!$result) {return -4;}
		unset($result, $sql);
		
    $sql = 'UPDATE `' . $this->tables['base'] . '` SET `status` = ' . $requested_status . ' WHERE `id` = ' . $user_id;
		$result = $this->getConn()->query($sql);
    if (!$result) {return -4;}
    return 1;
	} // end function

	/**
	* creates a mail with a validation-url and sends it to the given e-mail address
	* @param string $email
	* @param string $token
	* @return boolean
	*/
	protected function sendConfirmationMail($email = '', $token = '') {
		$from = $this->getAccessSetting('mail_sender');
		$to = $email; // validation noch machen
		$token = $token; // urlencoden noch machen
		$subject = $this->getAccessSetting('mail_text_subject');
		$text = $this->getAccessSetting('mail_text') . "\n\n";
		$text .= $this->getAccessSetting('mail_confirmation_url') . '?ct=' . $token . "\n\n";
		$text .= $this->getAccessSetting('mail_text_signature');
		// sendmail(mail erzeugen und schicken)
    file_put_contents('mailoutput.txt', $text); // debug funktion
    return TRUE;
	} // end function

	/**
	* looks up a verification-token in the database and sets the user status active if the token exists
	*
	* return codes:
	* -3 = DB Error
	* -2 = user status isnt set to a confirmation flag
	* -1 = no user with the token could be found in the db
	*  1 = ok
	*
	* @param string $token
	* @return int
	*/
	public function validateConfirmationToken($token = '') {
		// user holen von token
		$token = $this->getConn()->real_escape_string($token);
		$user_id = 0;
		$user_status = 0;
		$sql = 'SELECT m.`user`, b.`status` FROM `' . $this->tables['meta'] . '` as m LEFT JOIN `' . $this->tables['base'] . '` as b ON (m.`user` = b.`id`) WHERE `confirmation_token` = ' . $token;
		$result =  $this->getConn()->query($sql);
    if (!$result) {return -3;}
    
		while($row = $result->fetch_row()) {
      $user_id = (int) $row[0];
      $user_status = (int) $row[1];
		} // end while

		if ($user_id <= 0) {
			return -1;
		} elseif ($user_status < 5) {
			return -2;
		} // end if

    $sql = 'UPDATE `' . $this->tables['meta'] . '` SET `confirmation_token` = "" WHERE `user` = ' . $user_id;
    $result = $this->getConn()->query($sql);
    if (!$result) {return -3;}
    
		$sql = 'UPDATE `' . $this->tables['base'] . '` SET `status` = ' . ACCESS_STATUS_ACTIVE . ' WHERE `id` = ' . $user_id;
		$result = $this->getConn()->query($sql);
    if (!$result) { return -3; }

		return 1;
	} // end function

	/**
	* returns the id of a group if the group-name exists
	* @param string $group
	* @return mixed false or int
	*/
	protected function checkGroup($group = '') {
		if (!isset($this->possible_groups)) {
      $this->possible_groups = array();
			$sql = 'SELECT `name`, `id` FROM `' . $this->tables['groups'] . '`';
			$result =  $this->getConn()->query($sql);
			while($row = $result->fetch_row()) {
        $this->possible_groups[$row[0]] = $row[1];
			} // end while
			$result->close();
		} // end if

		if (isset($this->possible_groups[$group])) {
			return $this->possible_groups[$group];
		} // end if
		return FALSE;
	} // end function

	/**
	* returns the id of a position if the position-name exists
	* @param string $position
	* @return mixed false or int
	*/
	protected function checkPosition($position = '') {
		if (!isset($this->possible_positions)) {
      $this->possible_positions = array();
			$sql = 'SELECT `name`, `id` FROM `' . $this->tables['positions'] . '`';
			$result =  $this->getConn()->query($sql);
			while($row = $result->fetch_row()) {
        $this->possible_positions[$row[0]] = $row[1];
			} // end while
			$result->close();
		} // end if

		if (isset($this->possible_positions[$position])) {
			return $this->possible_positions[$position];
		} // end if
		return FALSE;
	} // end function

	/**
	* returns the name of a status for a given status-id
	* @param int $status
	* @return mixed false or string
	*/
	protected function checkStatus($status = -1) {
		if (!isset($this->possible_status)) {
      $this->possible_status = array();
			$sql = 'SELECT `id`,`name` FROM `' . $this->tables['status'] . '`';
			$result =  $this->getConn()->query($sql);
			while($row = $result->fetch_row()) {
        $this->possible_status[$row[0]] = $row[1];
			} // end while
			$result->close();
		} // end if

		if (isset($this->possible_status[$status])) {
			return $this->possible_status[$status];
		} // end if
		return FALSE;
	} // end function

	/**
	* returns true if the given string is an e-mail address
	* @param string $string
	* @return boolean
	*/
	protected static function isEmailString($string = '') {
			if (preg_match("!^\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$!", $string) == FALSE) {
				return FALSE;
			} // end if
			return TRUE;
	} // end function

	/**
	* returns the name of a group for a given group-id
	* @param int $id
	* @return mixed string or false
	*/
	public function getGroupName($id = 0) {
    $id = (int) $id;
		$this->checkGroup('void');
    return array_search($id, $this->possible_groups);
	} // end function

	/**
	* returns the name of a position for a given position-id
	* @param int $id
	* @return mixed string or false
	*/
	public function getPositionName($id = 0) {
		$id = (int) $id;
		$this->checkPosition('void');
		return array_search($id, $this->possible_positions);
	} // end function
	
	/**
	* returns the encrypted string for a cleartext-password
	* @param string $cleartext
	* @return string
	*/
	protected function generateCipherPassword($cleartext = '') {
		return md5($this->getAccessSetting('password_salt') . md5($cleartext));
	} // end function
	
	/**
	* returns the current ip address of the user
	* @return string
	*/
	public function getCurrentIP() {
	// muss noch überarbeitet werden um proxies rauszufiltern
		if (!isset($this->current_ip)) {
      $this->current_ip = $_SERVER['REMOTE_ADDR'];
		} // end if
		return $this->current_ip;
	} // end function
} // end class
?>
