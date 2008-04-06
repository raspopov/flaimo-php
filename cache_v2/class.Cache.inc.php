<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP2/2.2/5.2/5.1.0)                                          |
//+----------------------------------------------------------------------+
//| Copyright(c) 2001-2008 Michael Wimmer                                |
//+----------------------------------------------------------------------+
//| Licence: GNU General Public License v3                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmail.com>                           |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package Cache
*/
/**
* Caching of generated html/text or images...
*
* User it like this:
* <code>
* if ($cache->isCached($cache_filename, 14) == true) {
*   echo $cache->returnCache($cache_filename, false, false);
* } else {
*   // create content here and fill $text with it
*   $cache->writeCache($cache_filename, $text, false);
* }
* </code>
*
* Tested with Apache 2.2 and PHP 5.2.0
* Last change: 2008-04-06
*
* @desc Caching of generated html/text or images…
* @access public
* @author Michael Wimmer <flaimo@gmail.com>
* @copyright Michael Wimmer
* @link http://code.google.com/p/flaimo-php
* @package Cache
* @example sample_cache.php Sample script
* @version 2.000
*/
class Cache {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* Directory where cached files are saved
	*
	* @var string
	*/
	var $cache_dir;

	/**
	* Default extention for cache files
	*
	* @const string
	*/
	const EXT = 'txt';

	/**
	* Default prefix for cache files
	*
	* @const string
	*/
	const PREFIX = '';

	/**
	* Check if cache dir is available
	*
	* @const boolean
	*/
	const CHECK_CACHE_DIR = TRUE;

	/* Minimum age of the files last accesstime (in seconds) before files get
	deleted (one day = 86400) */
	//CONST MAX_AGE_FILES = 10;
	//CONST DELETE_OLD_FILES = TRUE;


	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Only job is to set all the variablesnames
	*
	* @param string $cachedir  Directory where cached files are saved. If string is empty “cached” will be used
	* @uses checkCacheDir()
	* @uses Cache::$cache_dir
	* @return void
	*/
	function __construct($cachedir = '') {
		$this->cache_dir  = (string) ((strlen(trim($cachedir)) > 0) ? $cachedir : 'cached');
  		if ($this->checkCacheDir() == FALSE) {
			die('error creating cache directory');
		} // end if
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Checks if the cache directory exists, else trys to create it
	*
	* @return boolean
	* @uses Cache::$cache_dir
	* @uses CHECK_CACHE_DIR
	*/
	private function checkCacheDir() {
		if (self::CHECK_CACHE_DIR === FALSE) {
			return (boolean) TRUE;
		} elseif (is_dir($this->cache_dir)) {
			return (boolean) TRUE;
		} else {
			return (boolean) ((!mkdir($this->cache_dir, 0700)) ? FALSE : TRUE);
		} // end if
	} // end function

	/**
	* Encodes the temporary filename
	*
	* @param string $filename
	* @return string $filename  Encoded string
	*/
	private function encodeFilename($filename) {
		//return $filename;
		return (string) md5($filename);
	} // end function

	/**#@+
	* Sticks together filename + path
	*
	* @param string $filename
	* @return string cache filename
	* @uses Cache::$cache_dir
	* @uses PREFIX
	* @uses encodeFilename()
	*/
	/**
	* @see returnCachePicturename()
	* @uses EXT
	*/
	private function returnCacheFilename($filename) {
		return (string) $this->cache_dir . '/' . self::PREFIX . $this->encodeFilename($filename) . '.' . self::EXT;
	} // end function

	/**
	* @param string $filetype
	* @see returnCacheFilename()
	*/
	private function returnCachePicturename($filename, $filetype) {
		return (string) $this->cache_dir . '/' . self::PREFIX . $this->encodeFilename($filename) . '.' . $filetype;
	} // end function
	/**#@-*/

	/**#@+
	* @param string $file  Name of the cached file
	* @param int $time Minimum time until cache file has to be renewed
	* @return boolean
	*/
	/**
	* Checks if a cache file is available and up to date
	*
	* @see isPictureCached()
	* @uses returnCacheFilename()
	*/
	public function isCached($file, $time = 30) {
		if (($filetime = @filemtime($this->returnCacheFilename($file))) == FALSE) {
		 	return (boolean) FALSE;
		} // end if
		if ((time() - $filetime) > $time) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* Checks if a picture is cached and up to date
	*
	* @param string $type Type of picture
	* @see isCached()
	* @uses returnCachePicturename()
	*/
	public function isPictureCached($file, $time = 30, $type = 'png') {
		if (($filetime = filemtime($this->returnCachePicturename($file, $type))) == FALSE) {
		 	return (boolean) FALSE;
		} // end if
		if ((time() - $filetime) > $time) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function
	/**#@-*/

	/**#@+
	* @param string $file Name of the cached file
	* @param boolean $binary Whether file should be returned in binary mode or not
	* @return mixed
	*/
	/**
	* Returns a cached file
	*
	* @param boolean $passthrough Return cache into a variable or directly output it to the browser
	* @see returnPictureCache()
	* @uses returnCacheFilename()
	*/
	public function returnCache($file, $passthrough = TRUE, $binary = TRUE) {
		$returntype = (($binary == FALSE) ? 'r' : 'rb');
		if ($passthrough == TRUE) {
			return implode('', file($this->returnCacheFilename($file)));
		} else {
			$handle = fopen($this->returnCacheFilename($file), $returntype);
			$cache = fgets($handle);
			fclose($handle);
			return $cache;
		} // end if
	} // end function

	/**
	* Returns a cached picture
	*
	* @param string $filetype Type of picture (jpg|gif|png)
	* @see returnCache()
	* @uses returnCachePicturename()
	*/
	public function returnPictureCache($file, $filetype = 'png', $binary = FALSE) {
		$returntype = (($binary == FALSE) ? 'r' : 'rb');
		return readfile($this->returnCachePicturename($file, $filetype), $returntype);
	} // end function
	/**#@-*/

	/**
	* Writes a cache file
	*
	* @param string $file Name of the cached file
	* @param mixed $content Content that should be cached
	* @param boolean $binary Whether file should be returned in binary mode or not
	* @return void
	* @see writePictureCache()
	* @uses returnCacheFilename()
	*/
	public function writeCache($file, $content, $binary = FALSE) {
		$writetype = (($binary == FALSE) ? 'w' : 'wb');
		$handle = fopen($this->returnCacheFilename($file), $writetype);
		fputs($handle, $content);
		fclose($handle);
	} // end function

	/**
	* Caches a picture
	*
	* @param string $file  Name of the cached file
	* @param binary $picture  The created picture
	* @param string $filetype  Type of picture (jpg|gif|png)
	* @return void
	* @see writeCache()
	* @uses returnCachePicturename()
	*/
	public function writePictureCache($file, $picture, $filetype = 'png') {
		if ($filetype == 'jpg') {
			imagejpeg($picture, $this->returnCachePicturename($file, $filetype));
		} elseif ($filetype == 'gif') {
			imagegif($picture, $this->returnCachePicturename($file, $filetype));
		} elseif ($filetype == 'png') {
			imagepng($picture, $this->returnCachePicturename($file, $filetype));
		} else {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* Delete old files
	*
	* not done yet
	*
	* @return void
	* @uses Cache::$cache_dir
	* @uses MAX_AGE_FILES
	*/
	private function deleteOldFiles() {
		return true; // not working right now
		$handle = opendir($this->cache_dir);
		while ($file = readdir($handle)) {

			if (($filetime = filemtime($this->cache_dir . '/' . $file)) == FALSE) {
			 	continue;
			} // end if
			if ((time() - $filetime) > self::MAX_AGE_FILES) {
				unlink($this->cache_dir . '/' . $file);
			} // end if
		} // end while
		closedir($handle);
		unset($handle);
		if (isset($file)) {
			unset($file);
		} // end if
	} // end function


	/*---------------------*/
	/* D E S T R U C T O R */
	/*---------------------*/

	/**
	* Denstructor
	*
	* @uses deleteOldFiles()
	* @uses DELETE_OLD_FILES
	* @return void
	*/
	function __destruct() {
		//if (DELETE_OLD_FILES === TRUE) {
			//$this->deleteOldFiles();
		//} // end if
	} // end destructor
} // end class Cache
?>