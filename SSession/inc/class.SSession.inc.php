<?php
/**
* function for automatic including of required classes
*/
function __autoload($class){
    require_once( 'class.' . $class . '.inc.php');
} // end function

/**
* base class for SSession. use only this class to create objects
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/sample.php  example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package SSession
* @version 1.0
*/
class SSession {
	const MODE = 'MySQL';
	const SALT = 'FLP';
	const TOKEN_NAME = 'ssession_token';
	const USE_IP = TRUE; // use IP for token
  const ALWAYS_REGENERATE_ID = FALSE; // always regenerate session id

	/**#@+
	* @var boolean
	*/
	protected $use_token = TRUE;
	protected $encrypt_session_data = FALSE; // requires my MCrypt class
	/**#@-*/
	/**#@+
	* @var string
	*/
	protected $session_pw;
	/**#@-*/
	/**#@+
	* @var object
	*/
	protected $encryptor;
	protected $backend;
	/**#@-*/

	/**
	* sets the PW if u want ot encrypt sessions and calls the initial methods
	* @param boolean $use_token
	* @return void
	* @uses SSession::$session_pw
	* @uses SSession::useToken()
	* @uses SSession::setSaveHandler()
	* @uses SSession::sessionStart()
	*/
	public function __construct ($use_token = TRUE) {
  // better change the session encryption pw so it is saved in an apache EnvVar and get it with $_SERVER[]
		$this->session_pw = 'maxmobil'; //$_SERVER['FW_PW'];
		$this->useToken($use_token);
		$this->setSaveHandler();
		$this->sessionStart();
	} // end construct

	/**
	* sets the backend class which handles the retreiving of session data. SQLite2, SQLite3 and MySQL are shipped
	* @return void
	* @uses SSession::$backend
	*/
	protected function setBackend() {
		if (!isset($this->backend)) {
		  $class = 'SSessionDB' . self::MODE;
			$this->backend = new $class;
		} // end if
	} // end function

	/**
	* creates an instance of the MCrypt class if it doesnt' exist yet
	* @return void
	* @uses MCryt::__construct()
	* @uses SSession::$session_pw
	*/
	protected function setEncryptor() {
		if (!isset($this->encryptor)) {
			$this->encryptor = new MCrypt($this->session_pw);
		} // end if
	} // end function

	/**
	* manually set the encryption flag and the password if not given in this class
	* @param bool $bool
	* @param string $pw
	* @return void
	* @uses SSession::$encrypt_session_data
	* @uses SSession::$session_pw
	*/
	public function useEncryption($bool = FALSE, $pw = '') {
		$this->encrypt_session_data = (boolean) $bool;
		if (strlen(trim($pw)) > 0) {
      $this->session_pw = $pw;
		} // end if
	} // end function


	/**
	* manually set the flag if a token should be used to secure the session from hijacking or not
	* @param boolean $bool
	* @return void
	* @uses SSession::$use_token
	*/
	protected function useToken($bool = FALSE) {
    $this->use_token = (boolean) $bool;
	} // end function

	/**
	* registers the methods for handling the session transactions
	* @return void
	* @uses SSession::$setBackend()
	*/	
	protected function setSaveHandler() {
		if (self::MODE != 'default') {
			$this->setBackend();
			session_set_save_handler(array($this, '_open'),
															array($this, '_close'),
															array($this, '_read'),
															array($this, '_write'),
															array($this, '_destroy'),
															array($this, '_clean'));
		} // end if
		register_shutdown_function('session_write_close');
	} // end function

	/**#@+
	* wrapper method to connect to the method of the backend class
	* @return mixed
	*/
	public function _open() {
		$this->backend->_open();
	} // end function

	public function _close() {
		$this->backend->_close();
	} // end function

	public function _read($id) {
		$data = $this->backend->_read($id);
		if (strlen(trim($data)) <= 0 ||self::MODE == 'default' || $this->encrypt_session_data == FALSE) {
			return $data;
		} // end if
		$this->setEncryptor();
		return $this->encryptor->decrypt($data);
	} // end function

	public function _write($id, $data) {
		if (self::MODE == 'default' || $this->encrypt_session_data == FALSE) {
			return $this->backend->_write($id, $data);
		} // end if
		$this->setEncryptor();
		return $this->backend->_write($id, $this->encryptor->encrypt($data));
	} // end function

	public function _destroy($id) {
		return $this->backend->_destroy($id);
	} // end function

	public function _clean($max) {
		return $this->backend->_clean($max);
	} // end function
	/**#@-*/
	
	/**
	* initiates the session and checks if the tokens match, else created a new session
	* @return void
	* @uses SSession::checkToken()
	* @uses SSession::setToken()
	*/
	public function sessionStart() {
		session_start();

		if (self::ALWAYS_REGENERATE_ID) {
      session_regenerate_id();
		} // end if

		if ($this->checkToken() == FALSE) {
			session_regenerate_id();
			session_unset();
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			} // end foreach
			$this->setToken();
		} // end if
	} // end function

	/**
	* created the token value based on user agent, first part of the client ip adress and a salt value
	* @return string
	*/
	protected function generateToken() {
    $ip_start = '';
		if (self::USE_IP == TRUE) {
			$ip = explode('.', $_SERVER['REMOTE_ADDR']);
			$ip_start = $ip[0] . '.' . $ip[1]; // if you have problems with AOL users, make this var empty
		} // end if
		return sha1(self::SALT . $ip_start . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	} // end function

	/**
	* writes the token to the session
	* @return void
	* @uses SSession::generateToken()
	*/	
	protected function setToken() {
    $_SESSION[self::TOKEN_NAME] = $this->generateToken();
	} // end function

	/**
	* checks if the token in the session matches the toen of the current request
	* @return boolean
	* @uses SSession::$use_token
	* @uses SSession::generateToken()
	*/	
	protected function checkToken() {
		if ($this->use_token == FALSE || (isset($_SESSION[self::TOKEN_NAME]) && $this->generateToken() == $_SESSION[self::TOKEN_NAME])) {
			return TRUE;
		} // end if
		return FALSE;
	} // end function
} // end class
?>
