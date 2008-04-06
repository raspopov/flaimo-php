<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                  |
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
* include used classes
*/
require_once 'class.Thumbnail.inc.php';
require_once 'class.PictureCache.inc.php';

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
class CachedThumbnail extends Thumbnail {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* amount of seconds thumbs should be cached. 0 = no cache
	*
	* @var int
	*/
	protected $cache_time = 0;

	/**
	* flipped formats array
	*
	* @var array
	*/
	protected $types;

	/**
	* holds PictureCache object
	*
	* @var object
	*/
	protected $cache;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @param string $file  path/filename of picture
	* @param int $seconds  amount of seconds thumbs should be cached. 0 = no cache
	* @return void
	* @uses Thumbnail::Thumbnail()
	* @uses $cache_time
	* @uses $types
	*/
	function __construct($file = '', $seconds = 0) {
  		parent::__construct($file);
		$this->cache_time = (int) $seconds;
		$this->types = array_flip($this->formats);
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* fills the cache variable with the cache object
	*
	* @return void
	* @uses PictureCache
	* @uses $cache_time
	* @uses $cache
	* @uses $image_path
	* @uses $thumbnail_type
	*/
	protected function setCache() {
		if (!isset($this->cache)) {
			$this->cache = new PictureCache($this->image_path,
											$this->thumbnail_type,
											$this->cache_time,
											parent::getThumbHeight() . parent::getThumbWidth());
		} // end if
	} // end function

	/**
	* outputs the thumbnail to the browser
	*
	* overrides method of base class
	*
	* @param string $format gif, jpg, png, wbmp
	* @param int $quality jpg-quality: 0-100
	* @return mixed
	* @uses setOutputFormat()
	* @uses setCache()
	* @uses $cache_time
	* @uses Thumbnail::isPictureCached()
	* @uses Thumbnail::createThumbnail()
	* @uses PictureCache::writePictureCache()
	* @uses Thumbnail::outputThumbnail()
	* @uses $thumbnail
	* @uses PictureCache::returnPictureCache()
	*/
	public function outputThumbnail($format = 'png', $quality = 75) {
		parent::setOutputFormat($format);
		$this->setCache();
		if ($this->cache_time === 0 || $this->cache->isPictureCached() === FALSE) {
			parent::createThumbnail();
			if ($this->cache_time > 0) {
				$this->cache->writePictureCache($this->thumbnail, $quality);
			} // end if
			parent::outputThumbnail($format, $quality);
		} else {
		    $ct = array(1 => 'gif', 2 => 'jpeg', 3 => 'png', 15 => 'vnd.wap.wbmp');
		    if (array_key_exists($this->thumbnail_type, $ct)) {
		    	header('Content-type: image/' . $ct[$this->thumbnail_type]);
		    } // end if

		    header('Expires: ' . date("D, d M Y H:i:s", time() + $this->cache_time) . ' GMT');
			header('Cache-Control: public');
			header('Cache-Control: max-age=' . $this->cache_time);
			echo $this->cache->returnPictureCache();
		} // end if
	} // end function

	/**
	* returns the variable with the thumbnail image
	*
	* @param string $format gif, jpg, png, wbmp
	* @return mixed
	* @uses setOutputFormat()
	* @uses Thumbnail::setCache()
	* @uses $cache_time
	* @uses Thumbnail::isPictureCached()
	* @uses Thumbnail::createThumbnail()
	* @uses Thumbnail::writePictureCache()
	* @uses $thumbnail_type
	* @uses $thumbnail
	* @uses Thumbnail::returnCachePicturename()
	*/
	public function returnThumbnail($format = 'png') {
		$this->setOutputFormat($format);
		$this->setCache();
		if ($this->cache_time === 0 || $this->cache->isPictureCached() === FALSE) {
			parent::createThumbnail();
			if ($this->cache_time > 0) {
				$this->cache->writePictureCache($this->thumbnail, 100);
			} // end if
		} else {
		    switch ($this->thumbnail_type) {
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
		    $this->thumbnail = $function($this->cache->returnCachePicturename());
		} // end if
		return $this->thumbnail;
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

		$this->setOutputFormat($format);
		$this->setCache();

		$path = $this->cache->getCacheFilepath($format, $quality);
		if ($path != FALSE) {
			return (string) $path;
		} else { // trys to create cache and return filename
			parent::createThumbnail();
			$this->cache->writePictureCache($this->thumbnail, $quality);
			return $this->cache->getCacheFilepath($format, $quality);
		} // end if
	} // end function
} // end class CachedThumbnail
?>