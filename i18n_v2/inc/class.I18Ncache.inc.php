<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* caches translation tables as an serialized array in a text-file
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NCache extends I18Nbase {

	/**
	* @uses I18NCache::checkCacheDir()
	* @return void
	*/
	public function __construct() {
  		if ($this->checkCacheDir() == FALSE) {
			die('error creating cache directory');
		} // end if
	} // end constructor

	/**
	* checks if the cache dir is available, else tries to create it
	* @uses I18Nbase::getI18Nsetting()
	* @return object
	*/
	protected function checkCacheDir() {
		if (parent::getI18Nsetting('check_cache_dir') == FALSE) {
			return (boolean) TRUE;
		} elseif (is_dir(parent::getI18Nsetting('cache_dir'))) {
			return (boolean) TRUE;
		} // end if
		return (boolean) ((!mkdir(parent::getI18Nsetting('cache_dir'), 0700)) ? FALSE : TRUE);
	} // end function

	/**
	* encodes a filename
	* @param string $filename
	* @return string
	*/
	protected static function encodeFilename($filename) {
		return md5($filename);
	} // end function

	/**
	* returns the full filename for a cache-file
	* @param string $filename
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Ncache::encodeFilename()
	* @return string
	*/
	protected function returnCacheFilename($filename) {
		return parent::getI18Nsetting('cache_dir') . '/' . parent::getI18Nsetting('file_prefix') . $this->encodeFilename($filename) . '.' . parent::getI18Nsetting('file_extention');
	} // end function

	/**
	* returns the creationtime of a file
	* @param string $file
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Ncache::encodeFilename()
	* @return mixed
	*/
	public function getFiletime($file) {
		if (($filetime = @filemtime($this->returnCacheFilename($file))) == FALSE) {
		 	return (boolean) FALSE;
		} // end if
		return $filetime;
	} // end function

	/**
	* checks whether a file is cached or not
	* @param string $file
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Ncache::getFiletime()
	* @return boolean
	*/
	public function isCached($file) {
		if (($filetime = $this->getFiletime($file)) == FALSE) {
		 	return (boolean) FALSE;
		} // end if
		if ((time() - $filetime) > parent::getI18Nsetting('cache_time')) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* returns the cached array
	* @param string $file
	* @uses I18Ncache::returnCacheFilename()
	* @return array
	*/
	public function returnCache($file) {
			return implode('', file($this->returnCacheFilename($file)));
	} // end function

	/**
	* writes content to a cachefile
	* @param string $file
	* @param string $content
	* @param boolean $binary
	* @uses I18Ncache::returnCacheFilename()
	* @return array
	*/
	public function writeCache($file, $content) {
		file_put_contents($this->returnCacheFilename($file), $content);
	} // end function
} // end class I18NCache
?>