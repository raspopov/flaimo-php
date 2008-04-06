<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2004 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//| (License : Free for non-commercial use)                              |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo 'at' gmx 'dot' net>                  |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package Thumbnail
*/
/**
* Creates a thumbnail from a source image and caches it for a given time
*
* Tested with Apache 1.3.29 and PHP 5.0.0RC1
* Last change: 2004-03-25
*
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Thumbnail
* @example sample_thumb.php Sample script
* @version 2.000
*/
class PictureCache {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* name of the cache dir
	*
	* @var string
	*/
	const CACHE_DIR = 'pic_cache';

	/**
	* prefix for every cache file
	*
	* @var string
	*/
	const PREFIX = 'tc_';

	/**
	* check if cache dir is available
	*
	* @var boolean
	*/
	const CHECK_CACHE_DIR = FALSE;

	/**
	* amount of seconds thumbs should be cached. 0 = no cache
	*
	* @var int
	*/
	protected $cache_time = 0;

	/**
	* possible image formats
	*
	* @var array
	*/
	protected $types = array(1 => 'gif', 2 => 'jpg', 3 => 'png', 15 => 'wbmp', 999 => 'string');

	/**
	* name/path of picture
	*
	* @var string
	*/
	protected $image_path;

	/**
	* image type of cached thumbnail
	*
	* @var int
	*/
	protected $image_type = 3;


	/**
	* salt for generating cache filename
	*
	* @var int
	*/
	protected $salt = '';

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @param string $file  path/filename of picture
	* @param int $type  image type
	* @param int $seconds  amount of seconds thumbs should be cached. 0 = no cache
	* @return void
	* @uses checkCacheDir()
	* @uses $cache_time
	* @uses $image_path
	* @uses $image_type
	*/
	function __construct($file = '', $type = 3, $seconds = 0, $salt = '') {
  		if ($this->checkCacheDir() === FALSE) {
			die('error creating cache directory');
		} // end if
		$this->cache_time = (int) $seconds;
		$this->image_path = (string) $file;
		$this->image_type = (int) $type;
		$this->salt = (int) $salt;
	} // end constructor


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Checks if the cache directory exists, else trys to create it
	*
	* @return boolean
	* @uses $check_cache_dir
	* @uses $cache_dir
	*/
	protected function checkCacheDir() {
		if (self::CHECK_CACHE_DIR == FALSE || is_dir(self::CACHE_DIR)) {
			return (boolean) TRUE;
		} else {
			return (boolean) ((!mkdir(self::CACHE_DIR, 0700)) ? FALSE : TRUE);
		} // end if
	} // end function

	/**
	* Sticks together filename + path
	*
	* @return string
	* @uses $cache_dir
	* @uses $prefix
	* @uses $image_path
	* @uses $types
	* @uses $image_type
	*/
	public function returnCachePicturename() {
		return (string) self::CACHE_DIR . '/' . self::PREFIX . md5($this->salt . $this->image_path) . '.' . $this->types[$this->image_type];
	} // end function

	/**
	* Checks if a picture is cached and up to date
	*
	* @return boolean
	* @uses returnCachePicturename()
	* @uses $cache_time
	*/
	public function isPictureCached() {
		$filetime = @filemtime($this->returnCachePicturename());
		if (!isset($filetime)) {
		 	return (boolean) FALSE;
		} // end if
		if ((time() - $filetime) > $this->cache_time) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* Returns a cached picture
	*
	* @return mixed
	* @uses returnCachePicturename()
	*/
	public function returnPictureCache() {
		return readfile($this->returnCachePicturename(), 'r');
	} // end function

	/**
	* writes a thumbnail to a file
	*
	* @param $image variable with image
	* @param int $quality jpg-quality: 0-100
	* @return void
	* @uses $thumbnail_type
	* @uses $thumbnail
	* @uses returnCachePicturename()
	*/
	public function writePictureCache($image = '', $quality = 75) {
	    if (strlen(trim($image)) > 0) {
		    switch ($this->image_type) {
		        case 1:
		        	imagegif($image, $this->returnCachePicturename());
		            break;
		        case 2:
		        	$quality = (int) $quality;
		        	if ($quality < 0 || $quality > 100) {
						$quality = 75;
		        	} // end if
					imagejpeg($image, $this->returnCachePicturename(), $quality);
		            break;
		        case 3:
		            imagepng($image, $this->returnCachePicturename());
		            break;
		        case 15:
		        	imagewbmp($image, $this->returnCachePicturename());
		            break;
		    } // end switch
		}
	} // end function

	/**
	* returns the path/filename of the cached thumbnail
	*
	* if cached pic is not available returns false
	*
	* @param string $format gif, jpg, png, wbmp
	* @param int $quality jpg-quality: 0-100
	* @return mixed string or FALSE if no cached pic is available
	* @uses returnCachePicturename()
	*/
	public function getCacheFilepath($format = 'png', $quality = 75) {
		if (file_exists($this->returnCachePicturename())) {
			return $this->returnCachePicturename();
		} else {
			return (boolean) FALSE;
		}
	} // end function

	/**
	* returns the cache time in seconds
	*
	* @return int $cache_time
	* @uses $cache_time
	*/
	public function getCacheTime() {
		return (int) $this->cache_time;
	} // end function
} // end class PictureCache
?>