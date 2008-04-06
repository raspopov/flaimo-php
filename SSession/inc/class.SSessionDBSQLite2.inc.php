<?php
require_once 'interface.SSessionDB.inc.php';

/**
* session backend class for sqlite 2. implements the methods from the interface needed for redefining the session handler
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/sample.php  example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package SSession
* @version 1.0
*/
class SSessionDBSQLite2 implements SSessionDB {

	const SSESSION_DB = 'C:\Dokumente und Einstellungen\michael.wimmer\Eigene Dateien\HTML\SSession\inc\ssession.sqlite2';
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
      $this->conn = sqlite_popen(self::SSESSION_DB, 0777, $this->error);
		} // end if
	} // end function
	
	public function _close() {
		if (isset($this->conn)) {
      sqlite_close($this->conn);
      unset($this->conn);
		} // end if
	} // end function
	
	public function _read($id) {
		$id = sqlite_escape_string($id);
		$sql = 'SELECT data FROM ' . self::SSESSION_TABLE . " WHERE id = '" . $id . "'";
		$result = sqlite_query($this->conn, $sql);
		if (sqlite_num_rows($result) > 0) {
			return sqlite_fetch_single($result);
		} // end if
		return '';
	} // end function
	
	public function _write($id, $data) {
		$access = time();
		$id = sqlite_escape_string($id);
		$data = sqlite_escape_string($data);
		$sql = 'REPLACE INTO ' . self::SSESSION_TABLE . " (id, access, data) VALUES ('" . $id . "', '" . $access . "', '" . $data . "')";
		return sqlite_query($this->conn, $sql);
	} // end function
	
	public function _destroy($id) {
    $id = sqlite_escape_string($id);
    $sql = 'DELETE FROM ' . self::SSESSION_TABLE . " WHERE id = '" . $id . "'";
    return sqlite_query($this->conn, $sql);
	} // end function
	
	public function _clean($max) {
		$old = time() - $max;
    $sql = 'DELETE FROM ' . self::SSESSION_TABLE . " WHERE access < '" . $old . "'";
		$success = sqlite_query($this->conn, $sql);
		sqlite_query('VACUUM ' . self::SSESSION_TABLE, $sql);
		return $success;
	} // end function
	/**#@-*/
} // end class
?>
