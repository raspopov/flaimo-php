<?php
/**
* basic functions every DB backend class has to provide
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package SSession
* @version 1.0
*/
interface SSessionDB {
	public function _open();
	public function _close();
	public function _read($id);
	public function _write($id, $data);
	public function _destroy($id);
	public function _clean($max);
} // end interface
?>
