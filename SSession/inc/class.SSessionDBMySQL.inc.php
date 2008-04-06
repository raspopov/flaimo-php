<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'interface.SSessionDB.inc.php';
/**
* session backend class for mysql. implements the methods from the interface needed for redefining the session handler
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/sample.php  example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package SSession
* @version 1.0
*/
class SSessionDBMySQL implements SSessionDB {

  const SSESSION_HOST = 'localhost';
	const SSESSION_DB = 'ssession';

	/**#@+
	* @var resource
	*/
	protected $conn;
	/**#@-*/
	/**#@+
	* @var string
	*/
	protected $db_user;
	protected $db_pw;
	/**#@-*/

	/**
	* Constructor
	* @return void
	*/
	public function __construct() {
    // better change the pw so it is saved in an apache SetEnv variable and get it with $_SERVER[]
		$this->db_user = 'root';
		$this->db_pw = $_SERVER['FW_PW']; // 'maxmobil';
	} // end constructor

	/**#@+
	* session method which is registered with session_set_save_handler
	* @return mixed
	*/
	public function _open() {
		if (!isset($this->conn)) {
      $this->conn = new mysqli(self::SSESSION_HOST, $this->db_user, $this->db_pw, self::SSESSION_DB);
		} // end if
	} // end function
	
	public function _close() {
		if (isset($this->conn)) {
      $this->conn->close();
			unset($this->conn);
		} // end if
	} // end function
	
	public function _read($id) {
		$id = $this->conn->real_escape_string($id);
		$sp = 'CALL _read("' . $id . '");';
    $r_value = '';
		if (!$this->conn->multi_query($sp)) {return '';}
		if ($result = $this->conn->store_result()) {
			$row = $result->fetch_row();
	    $r_value = $row[0];
		} // if
		$result->close();
		do {/* throw away other results*/} while ($this->conn->next_result());
		return $r_value;
	} // end function
	
	public function _write($id, $data) {
		$id = $this->conn->real_escape_string($id);
		$data = $this->conn->real_escape_string($data);
		$r_value = $this->conn->multi_query('CALL _write("' . $id . '", "' . $data . '");');
		do {/* throw away other results*/} while ($this->conn->next_result());
		return $r_value;
	} // end function

	public function _destroy($id) {
		$r_value = $this->conn->multi_query('CALL _destroy("' . $this->conn->real_escape_string($id) . '");');
		do {/* throw away other results*/} while ($this->conn->next_result());
		return $r_value;
	} // end function

	public function _clean($max) {
		$old = time() - $max;
		$r_value = $this->conn->multi_query('CALL _clean(' . $old . ');');
		do {/* throw away other results*/} while ($this->conn->next_result());
		return $r_value;
	} // end function
	/**#@-*/
} // end class
?>
