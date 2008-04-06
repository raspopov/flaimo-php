<?php
require_once 'interface.SSessionDB.inc.php';

/**
* session backend class for sqlite 3. implements the methods from the interface needed for redefining the session handler
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/sample.php  example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package SSession
* @version 1.0
*/
class SSessionDBSQLite3 implements SSessionDB {

	const SSESSION_DB = 'H:\flaimo\html\SSession\inc\ssession.sqlite3';
	const SSESSION_TABLE = 'ssession';
	/**#@+
	* @var resource
	*/
	protected $conn;
	/**#@-*/
	/**#@+
	* @var string
	*/
	protected $error;
	/**#@-*/

	/**#@+
	* session method which is registered with session_set_save_handler
	* @return mixed
	*/
	public function _open() {
		if (!isset($this->conn)) {
      $this->conn = new PDO('sqlite:' . self::SSESSION_DB);
		} // end if
	} // end function
	
	public function _close() {
		if (isset($this->conn)) {
      unset($this->conn);
		} // end if
	} // end function
	
	public function _read($id) {
		$id = sqlite_escape_string($id);
		$sql = 'SELECT data FROM ' . self::SSESSION_TABLE . " WHERE id = '" . $id . "'";
		foreach ($this->conn->query($sql) as $result) {
			return $result[0];
		} // end foreach
		return '';
	} // end function
	
	public function _write($id, $data) {
		$access = time();
		$id = sqlite_escape_string($id);
		$data = sqlite_escape_string($data);
		$sql = 'REPLACE INTO ' . self::SSESSION_TABLE . " (id, access, data) VALUES ('" . $id . "', '" . $access . "', '" . $data . "')";
		return $this->conn->query($sql);
	} // end function
	
	public function _destroy($id) {
    $id = sqlite_escape_string($id);
    $sql = 'DELETE FROM ' . self::SSESSION_TABLE . " WHERE id = '" . $id . "'";
	  return $this->conn->query($sql);
	} // end function
	
	public function _clean($max) {
		$old = time() - $max;
    $sql = 'DELETE FROM ' . self::SSESSION_TABLE . " WHERE access < '" . $old . "'";
		$success = $this->conn->query($sql);
		$this->conn->query('VACUUM ' . self::SSESSION_TABLE);
		return $success;
	} // end function
	/**#@-*/
} // end class
?>
