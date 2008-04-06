<?php
function __autoload($class){
	if ($class == 'Smarty') {
		require_once('Smarty.class.php');
	} else {
		require_once('class_' . $class . '_inc.php');
	} // end if
} // end function

function getSQLiteConn($db = '') {
	static $conn = array();
	if (!isset($conn[$db]) && strlen(trim($db)) > 0) {
		$conn[$db] = sqlite_open($db, 0777, $sqliteerror);
	} // end if
	return $conn[$db];
} // end function

function getPollUser() {
	static $user;
	if (!isset($user)) {
		$user = new PollUser();
	} // end if
	return $user;
} // end function

class PollBase {
	protected $database = 'd:/html/php/poll/inc/polls.sqlite';
	protected $tables = array('categories' => 'categories', 'polls' => 'polls', 'polloptions' => 'polloptions', 'surveys' => 'surveys', 'survey_polls' => 'survey_polls');
	protected $images_path = 'd:/html/php/poll/root/images/';
	protected $conn;
	protected $user;

	function __construct($db = '') {
		$this->setDB($db);
	} // end construxtor

	protected function setDB($db = '') {
		if ($this->isFilledString($db) && file_exists($db)) {
			$this->database = (string) $db;
		} // end if
	} // end function

	protected function &getConn() {
		if (!isset($this->conn)) {
			$this->conn =& getSQLiteConn($this->database);
		} // end if
		return $this->conn;
	} // end function

	public function &getUser() {
		if (!isset($this->user)) {
			$this->user =& getPollUser();
		} // end if
		return $this->user;
	} // end function

	public static function isFilledString($var = '', $min_length = 0) {
		if ($min_length == 0) {
			//echo $var . ':' . ((int) !ctype_space($var)) . '<br>';
			return !ctype_space($var);
		} // end if
		return (boolean) (strlen(trim($var)) >= $min_length) ? TRUE : FALSE;
	} // end function

	protected static function prepareSQLdata($var = '') {
		return sqlite_escape_string($var);
	} // end function

	protected static function returnSQLdata($var = '') {
		//return $var;
		return stripslashes($var);
	} // end function

	protected function &getVar($var = '') {
		if ($this->isFilledString($var) == FALSE) {
			return FALSE;
		} // end if

		if (!isset($this->$var)) {
			$this->fetchData();
		} // end if
		return (isset($this->$var)) ? $this->$var : FALSE;
	} // end function

	public function vacuumDB() {
		return sqlite_query($this->getConn(), 'VACUUM');
	} // end function

	protected function isImageUpload(&$file = '') { // $_FILES['xyz'] übergeben
		$allowed_image_types = array('image/jpg', 'image/jpeg', 'image/pjpeg');
		if (!isset($file['type']) || strlen(trim($file['type'])) < 1 ||
			$file['error'] == 4 || !in_array($file['type'], $allowed_image_types)) {
			return FALSE;
			} // end if
		return TRUE;
	} // end function

} // end class
?>