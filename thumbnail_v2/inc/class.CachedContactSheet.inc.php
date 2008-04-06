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
* include base class
*/
require_once 'class.ContactSheet.inc.php';

/**
* @package Thumbnail
*/
/**
* Creates a contactsheet from a images in a given folder and/or those added manually and caches it for a given time
*
* Tested with Apache 2.2 and PHP 5.2.
* Last change: 2008-04-06
*
* @access public
* @author Michael Wimmer <flaimo@gmail.com>
* @copyright Michael Wimmer
* @link http://code.google.com/p/flaimo-php
* @package Thumbnail
* @example sample_cs.php Sample script
* @version 2.000
*/
class CachedContactSheet extends ContactSheet {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* caching time
	*
	* @var int
	*/
	protected $cache_time = 10;

	/**
	* holds cache object
	*
	* @var object
	*/
	protected $cache;

	/**
	* folder to search for pics
	*
	* @var string
	*/
	protected $folder;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @param string $folder  folder to search in for images, empty string if no dir should be searched through
	* @param boolean $sub_dirs search sub directories or not
	* @param int $seconds  caching time in seconds
	* @return void
	* @uses ContactSheet::ContactSheet()
	* @uses $cache_time
	*/
	function __constructor($folder = '', $sub_dirs = FALSE, $seconds = 10) {
		$this->folder = (string) $folder;
		parent::__constructor($folder, $sub_dirs);
		$this->cache_time = (int) $seconds;
	} // end constructor

	/**
	* fills the cache variable with the cache object
	*
	* @return void
	* @uses PictureCache
	* @uses $cache_time
	* @uses $cache
	* @uses $cs_type
	*/
	protected function setCache() {
		if (!isset($this->cache)) {
			$this->cache = new PictureCache('cs_' . str_replace('/','', $this->folder) ,
											$this->cs_type, $this->cache_time);
		} // end if
	} // end function

	/**
	* outputs the cs image to the browser
	*
	* @param string $format gif, jpg, png, wbmp
	* @param int $quality jpg-quality: 0-100
	* @return mixed
	* @uses ContactSheet::setOutputFormat()
	* @uses ContactSheet::setContactSheetImage()
	* @uses setCache()
	* @uses PictureCache
	* @uses $cs_type
	* @uses $cs_image
	*/
	public function outputContactSheet($format = 'png', $quality = 75) {
		parent::setOutputFormat($format);
		$this->setCache();
		if ($this->cache_time === 0 || $this->cache->isPictureCached() === FALSE) {
			parent::setContactSheetImage();
			if ($this->cache_time > 0) {
				$this->cache->writePictureCache($this->cs_image, $quality);
			} // end if
			parent::outputContactSheet($format, $quality);
		} else {
		    $ct = array(1 => 'gif', 2 => 'jpeg', 3 => 'png', 15 => 'vnd.wap.wbmp');
		    if (array_key_exists($this->cs_type, $ct)) {
		    	header('Content-type: image/' . $ct[$this->cs_type]);
		    } // end if

		    header('Expires: ' . date("D, d M Y H:i:s", time() + $this->cache_time) . ' GMT');
			header('Cache-Control: public');
			header('Cache-Control: max-age=' . $this->cache_time);
			echo $this->cache->returnPictureCache();
		} // end if
	} // end function

	/**
	* returns the cs image for further processing
	*
	* @param string $format gif, jpg, png, wbmp
	* @return mixed
	* @uses ContactSheet::setOutputFormat()
	* @uses ContactSheet::setContactSheetImage()
	* @uses setCache()
	* @uses PictureCache
	* @uses setContactSheetImage()
	* @uses $cs_image
	*/
	public function returnContactSheet($format = 'png') {
		parent::setOutputFormat($format);
		$this->setCache();
		if ($this->cache_time === 0 || $this->cache->isPictureCached() === FALSE) {
			parent::setContactSheetImage();
			if ($this->cache_time > 0) {
				$this->cache->writePictureCache($this->cs_image, 100);
			} // end if
		} else {
		    switch ($this->cs_type) {
		        case 1:
		            $function = 'imagecreatefromgif';
		            break;
		        case 2:
		            $function = 'imagecreatefromjpeg';
		            break;
		        case 3:
		            $function = 'imagecreatefrompng';
		            break;
		        case 15:
		            $function = 'imagecreatefromwbmp';
		            break;
		        case 999:
		        default:
					$function = 'imagecreatefromstring';
					break;
		    } // end switch
		    $this->cs_image = $function($this->cache->returnCachePicturename());
		} // end if
		return $this->cs_image;
	} // end function

	/**
	* returns the path/filename of the cached thumbnail
	*
	* if cached pic is not available, tries to create it with the given parameters
	*
	* @param string $format gif, jpg, png, wbmp
	* @param int $quality jpg-quality: 0-100
	* @return mixed string or FALSE if no cached pic is available
	* @uses $cache_time
	* @uses PictureCache::isPictureCached()
	* @uses setOutputFormat()
	* @uses PictureCache::writePictureCache()
	* @uses Thumbnail::createThumbnail()
	*/
	public function getCacheFilepath($format = 'png', $quality = 75) {
		if ($this->cache_time === 0) {
			return (boolean) FALSE; // no cached thumb available
		} // end if

		parent::setOutputFormat($format);
		$this->setCache();
		$path = $this->cache->getCacheFilepath($format, $quality);
		if ($path != FALSE) {
			return (string) $path;
		} else { // trys to create cache and return filename
			parent::setContactSheetImage();
			$this->cache->writePictureCache($this->cs_image, $quality);
			return $this->cache->getCacheFilepath($format, $quality);
		} // end if
	} // end function
} // end class CachedContactSheet
?>