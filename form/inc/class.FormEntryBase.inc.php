<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.FormBase.inc.php';

/**
* abstract base class for FormEntry* classes
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Form
* @version 1.0
*/
abstract class FormEntryBase extends FormBase {
	/**#@+
	* @var mixed
	*/
	protected $id;
	protected $name;
	protected $required;
	protected $validator;
	protected $default_value;
	protected $arguments;
	protected $range;
	protected $session_name;
	protected $description;
	
	protected $codename;
	protected $value;
	protected $error;
	
	protected static $count_entries = 1;
	/**#@-*/

	/**
	* constructor
	*
	* @param string $name_name of the form entry
	* @param string $description description of the form entry
	* @param int $required whether the user has to fill out the entry or not
	* @param object $validator who should the input of this entry be validated (see FV_* constants in the FormBase file)
	* @param string $session_name
	* @return void
	*/
	public function __construct($id = '', $name = '', $description = '', $required = 0, FormValidatorBase &$val, $session_name = '') {
		$this->setName($name);
		$this->setDescription($description);
		$this->setRequired($required);
		$this->setValidator($val);
		$this->setSessionName($session_name);
		$this->setCodename();
		$this->setID($id);
		$this->error = FALSE;
		self::$count_entries++;
	} // end constructor

	/**
	* generates a random string with 14 characters
	*
	* @return string
	*/
	public static function getRandomString() {
			for($len = 14, $cleartext = ''; strlen($cleartext) < $len; $cleartext .= chr(!mt_rand(0, 2) ? mt_rand(48, 57) : (!mt_rand(0, 1) ? mt_rand(65, 90) : mt_rand(97, 122))));
			return $cleartext;
	} // end function

	/**
	* sets the arguments class var
	*
	* @param string $string
	* @return boolean
	*/
	protected function setArguments($string = '') {
		$this->arguments = $string;
		if(is_string($string) == TRUE && strlen(trim($string)) < 1) {
			$this->arguments = FALSE;
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the name class var. if string is empty uses a random string
	*
	* @param string $name
	* @return boolean
	*/
	protected function setName($name = '') {
		if(strlen(trim($name)) < 1) {
			$this->name = $this->getRandomString();
			return FALSE;
		} // end if
		$this->name = $name;
		return TRUE;
	} // end function

	/**
	* sets the id if the entry
	*
	* @param string $string
	* @return boolean
	*/
	protected function setID($string = '') {
		$this->id = $string;
		if(is_string($string) == TRUE && strlen(trim($string)) < 1) {
			$this->id = $this->getCodename();
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the description class var
	*
	* @param mixed $description
	* @return boolean
	*/
	protected function setDescription($description = '') {
		if ($description instanceOf DomElement) {
		  $this->description = $description;
			return TRUE;
		} // end if

		$this->description = (string) trim($description);
		return TRUE;
	} // end function

	/**
	* sets the required class var true or false
	*
	* @param int $req
	* @return boolean
	*/
	protected function setRequired($req = 0) {
		$req = (int) $req;
		$this->required = TRUE;
		if($req < 1) {
			$this->required = FALSE;
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the validator class var
	*
	* @param object $val validator object
	* @return boolean
	*/
	protected function setValidator(FormValidatorBase &$val) {
		$this->validator = $val;
		return TRUE;
	} // end function

	/**
	* sets the session_name class var. if string is empty sets "default"
	*
	* @param string $string
	* @return boolean
	*/
	protected function setSessionName($string = '') {
		$this->session_name = $string;
		if(strlen(trim($string)) < 1) {
			$this->session_name = 'default';
		} // end if
		return TRUE;
	} // end function

	/**
	* sets the codename for the entryname and writes it to the session
	*
	* @return boolean
	*/
	protected function setCodename() {
		if (isset($_SESSION[$this->session_name]['codenames'][$this->name . '_' . self::$count_entries])) {
			$this->codename = $_SESSION[$this->session_name]['codenames'][$this->name . '_' . self::$count_entries];
			return TRUE;
		} // end if

		$this->codename = $_SESSION[$this->session_name]['codenames'][$this->name . '_' . self::$count_entries] = $this->getRandomString();
		return TRUE;
	} // end function

	/**
	* hands the entry to a validator object and returns the output of the validate function
	*
	* @return boolean
	*/
	protected function validate() {
		if ($this->validator instanceOf FormValidatorNone) {
			return TRUE;
		} // end if
		$success = $this->validator->validate($this);

		if (!$success) {
		 	$this->error = TRUE;
		} // end if
		return $success;
	} // end function

	/**#@+
	* getter methods for user vars
	* @return mixed
	*/
	public function &getName() {
		return $this->name;
	} // end function

	public function &getID() {
		return $this->id;
	} // end function

	public function &getDescription() {
		return $this->description;
	} // end function

	public function &getCodename() {
		return $this->codename ;
	} // end function
	
	public function &hasError() {
		return $this->error;
	} // end function
	
	public function &isRequired() {
		return $this->required;
	} // end function
	
	public function &getValue() {
		return $this->value;
	} // end function

	public function &getArguments() {
		return $this->arguments;
	} // end function

	public function &getRange() {
		return $this->range;
	} // end function
	/**#@-*/
} // end class
?>
