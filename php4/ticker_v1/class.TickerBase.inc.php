<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package Ticker
* @category FLP
*/
/**
* Abstract class for getting ini preferences
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-24
*
* @desc Abstract class for getting ini preferences
* @access protected
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @global array $GLOBALS['_TICKER_ini_settings']
* @abstract
* @package Ticker
* @category FLP
* @version 1.002
*/
class TickerBase {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* name (+path) of the ticker ini file
	*
	* @desc name (+path) of the ticker ini file
	* @var string
	*/
	var $ticker_ini_file = 'ticker_settings.ini';

	/**
	* name of the global variable which holds the ticker settings
	*
	* @desc name of the global variable which holds the ticker settings
	* @var array
	*/
	var $ticker_globalname = '_TICKER_ini_settings';

	/**
	* turn on/off shared memory use
	*
	* @desc turn on/off shared memory use
	* @var boolean
	*/
	var $use_shared_mem = TRUE;

	/**
	* set to TRUE, if you have changed one of the files which wer written to shared memory to flush the memry block
	*
	* @desc set to TRUE, if you have changed one of the files which wer written to shared memory to flush the memry block
	* @var boolean
	*/
	var $flush_sm = FALSE;

	/**
	* size of the shared memory block for the ticker settings string
	*
	* @desc size of the shared memory block for the ticker settings string
	* @var int
	*/
	var $ticker_shm_size = 1000;

	/**
	* name of the shared memory block for the ticker settings string
	*
	* @desc name of the shared memory block for the ticker settings string
	*/
	var $ticker_shm_id = 0xf42; // 0xDEAD, 0xf42, 0xCAFE, 0xff3
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @return void
	* @access private
	*/
	function TickerBase() {
	} // end constructor


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* converts a four-letter string to a hex code (for creating a unique ID for the shared memory blocks)
	*
	* @desc converts a four-letter string to a hex code (for creating a unique ID for the shared memory blocks)
	* @param string $string fourletter inputstring
	* @return string
	* @access public
	* @author joeldegan@yahoo.com <joeldegan@yahoo.com>
	*/
	function memSegSpot($string) {
		$string = str_pad(str_replace('_', '', $string), 4, 'Z'); // Z = 5a
		$out = '';
		for ($a = 0; $a < 4; $a++) {
	 		$out .= dechex(ord(substr($string, $a , 1))); // ord returns dec, we need hex for shared memory segments
		} // end for
		return substr(('0x' . substr($out, 2)), 0, 8); // prepend it with 0x
	}//end function

	/**
	* Reads the default settings from the settings file and loads it to shared memory if necessary
	*
	* @desc Reads the default settings from the settings file and loads it to shared memory if necessary
	* @return void
	* @access public
	*/
	function readINIsettings() {
		if (isset($GLOBALS[$this->ticker_globalname])) {
			return (boolean) TRUE;
		} // end if

		if (!file_exists($this->ticker_ini_file)) {
			return (boolean) FALSE;
		} // end if

		if (!extension_loaded('shmop')) {
			@dl('shmop');
		} // end if

		/* if NOT able to use shared memory */
		if ($this->use_shared_mem === FALSE || !extension_loaded('shmop')) {
			$GLOBALS[$this->ticker_globalname] = (array) parse_ini_file($this->ticker_ini_file, TRUE);
			return (boolean) TRUE;
		} // end if

		/* if able to use shared memory */
		$shm = shmop_open($this->ticker_shm_id, 'c', 0644, $this->ticker_shm_size);
		$sm_content = trim(shmop_read($shm, 0, $this->ticker_shm_size));
		if(isset($sm_content) && strlen($sm_content) > 0) {
			$GLOBALS[$this->ticker_globalname] = (array) unserialize($sm_content);
		} else { // if nothing is there, write file to shared mem
			$GLOBALS[$this->ticker_globalname] = (array) parse_ini_file($this->ticker_ini_file, TRUE);
			$inifile = (string) serialize($GLOBALS[$this->ticker_globalname]);
			$shm_bytes_written = shmop_write($shm, $inifile, 0);
			unset($inifile);
		} // end if
		if ($this->flush_sm === TRUE) {
			shmop_delete($shm);
		} // end if
		shmop_close($shm);
	} // end function
} // end class TickerBase
?>