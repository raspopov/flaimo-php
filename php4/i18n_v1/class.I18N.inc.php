<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/4.3.2)                                    |
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
* @package i18n
* @category FLP
*/
/**
* Abstract class for getting ini preferences
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc Abstract class for getting ini preferences
* @access protected
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @global array $GLOBALS['_I18N_ini_settings']
* @global array $GLOBALS['_I18N_l10n_settings']
* @abstract
* @example i18n_example_script.php Sample script
* @package i18n
* @category FLP
* @version 1.061
*/
class I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* name (+path) of the i18n ini file
	*
	* @desc name (+path) of the i18n ini file
	* @var string
	*/
	var $i18n_ini_file = 'i18n_settings.ini';

	/**
	* name of the l10n ini file (without path)
	*
	* @desc name of the l10n ini file (without path)
	* @var string
	*/
	var $l10n_ini_file = 'l10n.ini';

	/**
	* name of the global variable which holds the i18n settings
	*
	* @desc name of the global variable which holds the i18n settings
	* @var array
	*/
	var $i18n_globalname = '_I18N_ini_settings';

	/**
	* name of the global variable which holds the l10n settings
	*
	* @desc name of the global variable which holds the l10n settings
	* @var array
	*/
	var $l10n_globalname = '_L10N_ini_settings';

	/**
	* name of the shared memory block for the i18n settings string
	*
	* @desc name of the shared memory block for the i18n settings string
	*/
	var $i18n_shm_id = 0xDEAD; // 0xDEAD, 0xf42, 0xCAFE, 0xff3

	/**
	* size of the shared memory block for the i18n settings string
	*
	* @desc size of the shared memory block for the i18n settings string
	* @var int
	*/
	var $i18n_shm_size = 1000;

	/**
	* size of the shared memory block for each l10n settings string.
	*
	* if you have a big badwords string you might want to change this to a
	* bigger value. if it's to small PHP should create an error
	*
	* @desc size of the shared memory block for each l10n settings string
	* @var int
	*/
	var $l10n_shm_size = 1000;

	/**
	* turn on/off shared memory use
	*
	* @desc turn on/off shared memory use
	* @var boolean
	*/
	var $use_shared_mem = FALSE;

	/**
	* set to TRUE, if you have changed one of the files which wer written to shared memory to flush the memry block
	*
	* @desc set to TRUE, if you have changed one of the files which wer written to shared memory to flush the memry block
	* @var boolean
	*/
	var $flush_sm = FALSE;
	/**#@-*/


	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Only job is to set all the variablesnames
	*
	* @desc Constructor
	* @return void
	* @access private
	* @since 1.056 - 2003-05-01
	*/
	function I18N() {
	} // end constructor


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**#@+
	* @access public
	*/
	/**
	* converts a four-letter string to a hex code (for creating a unique ID for the shared memory blocks)
	*
	* @desc converts a four-letter string to a hex code (for creating a unique ID for the shared memory blocks)
	* @param string $string fourletter inputstring
	* @return string
	* @since 1.057 - 2003-05-09
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
	* @return boolean if successful reading ini file
	* @since 1.053 - 2003-04-15
	*/
	function readINIsettings() {
		if (isset($GLOBALS[$this->i18n_globalname])) {
			return (boolean) TRUE;
		} // end if

		if (!file_exists($this->i18n_ini_file)) {
			return (boolean) FALSE;
		} // end if

		if (!extension_loaded('shmop')) {
			@dl('shmop');
		} // end if

		/* if NOT able to use shared memory */
		if ($this->use_shared_mem === FALSE || !extension_loaded('shmop')) {
			$GLOBALS[$this->i18n_globalname] = (array) parse_ini_file($this->i18n_ini_file, TRUE);
			return (boolean) TRUE;
		} // end if

		/* if able to use shared memory */
		$shm = shmop_open($this->i18n_shm_id, 'c', 0644, $this->i18n_shm_size);
		$sm_content = trim(shmop_read($shm, 0, $this->i18n_shm_size));
		if(isset($sm_content) && strlen($sm_content) > 0) {
			$GLOBALS[$this->i18n_globalname] = (array) unserialize($sm_content);
		} else { // if nothing is there, write file to shared mem
			$GLOBALS[$this->i18n_globalname] = (array) parse_ini_file($this->i18n_ini_file, TRUE);
			$inifile = (string) serialize($GLOBALS[$this->i18n_globalname]);
			$shm_bytes_written = shmop_write($shm, $inifile, 0);
			unset($inifile);
		} // end if
		if ($this->flush_sm === TRUE) {
			shmop_delete($shm);
		} // end if
		shmop_close($shm);
	} // end function

	/**
	* Reads the default settings for numbers and dates from the settings file if necessary
	*
	* @desc Reads the default settings for numbers and dates from the settings file if necessary
	* @return boolean if successful reading ini file
	* @since 1.055 - 2003-04-22
	*/
	function readL10NINIsettings($locale = 'en') {
		if ($this->readINIsettings() === FALSE) {
			return (boolean) FALSE;
		} // end if

		if (isset($GLOBALS[$this->l10n_globalname])) {
			return (boolean) TRUE;
		} // end if

		$languagefile_path =& $GLOBALS[$this->i18n_globalname]['Translator']['languagefile_path'];
		if (!file_exists($languagefile_path . '/' . $locale . '/' . $this->l10n_ini_file)) {
			return (boolean) FALSE;
		} // end if

		if (!extension_loaded('shmop')) {
			@dl('shmop');
		} // end if

		/* if NOT able to use shared memory */
		if ($this->use_shared_mem === FALSE || !extension_loaded('shmop')) {
			$GLOBALS[$this->l10n_globalname] = (array) parse_ini_file($languagefile_path . '/' . $locale . '/' . $this->l10n_ini_file, TRUE);
			return (boolean) TRUE;
		} // end if

		/* if able to use shared memory */
		$shm2_id = $this->memSegSpot(substr($locale, 0, 3) . 'L');
		$shm2 = shmop_open($shm2_id, 'c', 0644, $this->l10n_shm_size);
		$sm_content2 = trim(shmop_read($shm2, 0, $this->l10n_shm_size));
		if(isset($sm_content2) && strlen($sm_content2) > 0) {
			$GLOBALS[$this->l10n_globalname] = (array) unserialize($sm_content2);
			//echo '<hr>' . $sm_content2 . '<hr>';
		} else { // if nothing is there, write file to shared mem
			$GLOBALS[$this->l10n_globalname] = (array) parse_ini_file($languagefile_path . '/' . $locale . '/' . $this->l10n_ini_file, TRUE);
			$inifile = (string) serialize($GLOBALS[$this->l10n_globalname]); // hier vorher in global reinschreiben
			$shm2_bytes_written = shmop_write($shm2, $inifile, 0);
			unset($inifile);
			//echo 'l10 drinnen';
		} // end if
		if ($this->flush_sm === TRUE) {
			shmop_delete($shm2);
		} // end if
		shmop_close($shm2);
	} // end function
	/**#@-*/
} // end class i18n
?>