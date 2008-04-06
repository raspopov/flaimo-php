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
* Tested with Apache 1.3.24 and PHP 4.2.3
*
* @desc Caching of generated html/text or images…
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Cache
* @version 1.002
*/
class Cache {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @var string
	* @access private
	*/
	/**
	* Directory where cached files are saved
	*
	* @desc Directory where cached files are saved
	*/
	var $cache_dir;

	/**
	* Default extention for cache files
	*
	* @desc Default extention for cache files
	*/
	var $ext = 'txt';

	/**
	* Default prefix for cache files
	*
	* @desc Default prefix for cache files
	*/
	var $prefix = '';
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @access private
	*/
	/**
	* Constructor
	*
	* Only job is to set all the variablesnames
	*
	* @desc Constructor
	* @param (string) $cachedir  Directory where cached files are saved. If string is empty “cached” will be used
	* @return (void)
	*/
	function Cache($cachedir = '') {
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
	* @desc Checks if the cache directory exists, else trys to create it
	* @return boolean
	*/
	function checkCacheDir() {
		if (!is_dir($this->cache_dir)) {
			return (boolean) ((!mkdir($this->cache_dir, 0700)) ? FALSE : TRUE);
		} else {
			return (boolean) TRUE;
		} // end if
	} // end function

	/**
	* Encodes the temporary filename
	*
	* @desc Encodes the temporary filename
	* @param string $filename
	* @return string $filename  Encoded string
	* @see decodeFilename()
	*/
	function encodeFilename($filename) {
		//return $filename;
		return (string) md5($filename);
	} // end function

	/**
	* Decodes the temporary filename
	*
	* @desc Decodes the temporary filename
	* @param string $filename
	* @return string $filename  Decoded string
	* @see encodeFilename()
	*/
	function decodeFilename($filename) {
		return (string) $filename;
		//return (string) str_rot13($filename);
	} // end function

	/**
	* Sticks together filename + path
	*
	* @desc Sticks together filename + path
	* @param string $filename
	* @return string
	* @see returnCachePicturename()
	* @uses encodeFilename()
	*/
	function returnCacheFilename($filename) {
		return (string) $this->cache_dir . '/' . $this->prefix . $this->encodeFilename($filename) . '.' . $this->ext;
	} // end function

	/**
	* Sticks together filename + path
	*
	* @desc Sticks together filename + path
	* @param string $filename
	* @param string $filetype
	* @return string
	* @see returnCacheFilename()
	* @uses encodeFilename()
	*/
	function returnCachePicturename($filename, $filetype) {
		return (string) $this->cache_dir . '/' . $this->prefix . $this->encodeFilename($filename) . '.' . $filetype;
	} // end function

	/**
	* Delete old files
	*
	* @desc Delete old files
	* @param int $lastaccess  Minimum age of the files last accesstime (in seconds) before files get deleted
	* @param int $created  Minimum age of the files (in seconds) before files get deleted
	* @return void
	* @see writeCardFile()
	*/
	function deleteOldFiles($lastaccess = 300, $created = 300) {
		if (!is_int($lastaccess) || $lastaccess < 1) {
			$lastaccess = (int) 300;
		} // end if
		if (!is_int($created) || $created < 1) {
			$created = (int) 300;
		} // end if
		$handle = opendir($this->download_dir);
		while ($file = readdir($handle)) {
			if (!eregi("^\.{1,2}$",$file) && !is_dir($this->cache_dir . '/' . $file) && (((time() - filemtime($this->cache_dir . '/' . $file)) > $created) || (((time() - fileatime($this->cache_dir . '/' . $file)) > $lastaccess)))) {
				unlink($this->cache_dir . '/' . $file);
			} // end if
		} // end while
		closedir($handle);
		if (isset($handle)) {
			unset($handle);
		} // end if
		if (isset($file)) {
			unset($file);
		} // end if
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Checks if a cache file is available and up to date
	*
	* @desc Checks if a cache file is available and up to date
	* @param string $file  Name of the cached file
	* @param int $time  Minimum time until cache file has to be renewed
	* @return boolean $cached
	* @see isPictureCached()
	* @uses returnCacheFilename()
	*/
	function isCached($file, $time = 30) {
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
	* @desc Checks if a picture is cached and up to date
	* @param string $file  Name of the cached file
	* @param int $time  Minimum time until cache file has to be renewed
	* @param string $type  Type of picture
	* @return boolean $cached
	* @see isCached()
	* @uses returnCachePicturename()
	*/
	function isPictureCached($file, $time = 30, $type = 'png') {
		if (file_exists($this->returnCachePicturename($file, $type)) && (time() - filemtime($this->returnCachePicturename($file, $type)) < $time)) {
			return (boolean) TRUE;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* Returns a cached file
	*
	* @desc Returns a cached file
	* @param string $file  Name of the cached file
	* @param boolean $passthrough  Return cache into a variable or directly output it to the browser
	* @param boolean $binary  Whether file should be returned in binary mode or not
	* @return mixed $cache
	* @see returnPictureCache()
	* @uses returnCacheFilename()
	*/
	function returnCache($file, $passthrough = FALSE, $binary = FALSE) {
		$returntype = (($binary == FALSE) ? 'r' : 'rb');
		if ($passthrough == TRUE) {
			return readfile($this->returnCacheFilename($file), $returntype);
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
	* @desc Returns a cached picture
	* @param string $file  Name of the cached file
	* @param string $filetype  Type of picture (jpg|gif|png)
	* @param boolean $binary  Whether file should be returned in binary mode or not
	* @return string
	* @see returnCache()
	* @uses returnCachePicturename()
	*/
	function returnPictureCache($file, $filetype = 'png', $binary = FALSE) {
		$returntype = (($binary == FALSE) ? 'r' : 'rb');
		return readfile($this->returnCachePicturename($file, $filetype), $returntype);
	} // end function

	/**
	* Writes a cache file
	*
	* @desc Writes a cache file
	* @param string $file  Name of the cached file
	* @param mixed $content  Content that should be cached
	* @param boolean $binary  Whether file should be returned in binary mode or not
	* @return void
	* @see writePictureCache()
	* @uses returnCacheFilename()
	*/
	function writeCache($file, $content, $binary = FALSE) {
		$writetype = (($binary == FALSE) ? 'w' : 'wb');
		$handle = fopen($this->returnCacheFilename($file), $writetype);
		fputs($handle, $content);
		fclose($handle);
	} // end function

	/**
	* Caches a picture
	*
	* @desc Caches a picture
	* @param string $file  Name of the cached file
	* @param binary $picture  The created picture
	* @param string $filetype  Type of picture (jpg|gif|png)
	* @return void
	* @see writeCache()
	* @uses returnCachePicturename()
	*/
	function writePictureCache($file, $picture, $filetype = 'png') {
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
	/**#@-*/
} // end class Cache
?>
