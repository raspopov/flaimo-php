<?php
function __autoload($class){
    require_once( 'class.' . $class . '.inc.php');
} // end function

class TickerBase {

	CONST TICKER_INI_FILE = 'ticker_settings.ini';
	CONST TICKER_GLOBALNAME = '_TICKER_ini_settings';
	CONST USE_SHARED_MEM = TRUE;
	CONST FLUSH_SM = FALSE;
	var $ticker_shm_size = 1000;
	var $ticker_shm_id = 0xf42; // 0xDEAD, 0xf42, 0xCAFE, 0xff3


	private static function memSegSpot($string) {
		$string = str_pad(str_replace('_', '', $string), 4, 'Z'); // Z = 5a
		$out = '';
		for ($a = 0; $a < 4; $a++) {
	 		$out .= dechex(ord(substr($string, $a , 1))); // ord returns dec, we need hex for shared memory segments
		} // end for
		return substr(('0x' . substr($out, 2)), 0, 8); // prepend it with 0x
	}//end function

	protected function readINIsettings() {
		if (isset($GLOBALS[TICKER_GLOBALNAME])) {
			return (boolean) TRUE;
		} // end if

		if (!file_exists(TICKER_INI_FILE)) {
			return (boolean) FALSE;
		} // end if

		if (!extension_loaded('shmop')) {
			@dl('shmop');
		} // end if

		/* if NOT able to use shared memory */
		if (USE_SHARED_MEM === FALSE || !extension_loaded('shmop')) {
			$GLOBALS[TICKER_GLOBALNAME] = (array) parse_ini_file(TICKER_INI_FILE, TRUE);
			return (boolean) TRUE;
		} // end if

		/* if able to use shared memory */
		$shm = shmop_open($this->ticker_shm_id, 'c', 0644, $this->ticker_shm_size);
		$sm_content = trim(shmop_read($shm, 0, $this->ticker_shm_size));
		if(isset($sm_content) && strlen($sm_content) > 0) {
			$GLOBALS[TICKER_GLOBALNAME] = (array) unserialize($sm_content);
		} else { // if nothing is there, write file to shared mem
			$GLOBALS[TICKER_GLOBALNAME] = (array) parse_ini_file(TICKER_INI_FILE, TRUE);
			$inifile = (string) serialize($GLOBALS[TICKER_GLOBALNAME]);
			$shm_bytes_written = shmop_write($shm, $inifile, 0);
			unset($inifile);
		} // end if
		if (FLUSH_SM === TRUE) {
			shmop_delete($shm);
		} // end if
		shmop_close($shm);
		return (boolean) TRUE;
	} // end function
} // end class TickerBase
?>