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
* include thumbnail class
*/
require_once 'class.CachedThumbnail.inc.php';

/**
* @package Thumbnail
*/
/**
* Creates a contactsheet from a images in a given folder and/or those added manually
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
class ContactSheet {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @var array
	*/
	/**
	* holds rgb color infos for the pic background
	*/
	protected $bg_color = array(255,255,255);

	/**
	* holds rgb color infos for the thumbnail backgrounds
	*/
	protected $thumb_bg_color = array(255,255,255);

	/**
	* holds rgb color infos for the thumbnail text
	*/
	protected $text_color = array(255,255,255);

	/**
	* possible image formats
	*/
	protected $formats = array('gif' => 1, 'jpg' => 2, 'png' => 3, 'wbmp' => 15, 'string' => 999);

	/**
	* holds thumbnail objects
	*/
	protected $thumbnails = array();
	/**#@-*/

	/**#@+
	* @var int
	*/
	/**
	* caching time
	*/
	protected $cache_time = 0;

	/**
	* maximum width of every thumbnail
	*/
	protected $thumb_width = 100;

	/**
	* maximum height of every thumbnail
	*/
	protected $thumb_height = 100;

	/**
	* number of columns to be displayed
	*/
	protected $columns = 3;

	/**
	* number of row to be displayed, changed automatically if not enough space to display all thumbs
	*/
	protected $rows = 3;

	/**
	* margin between thumbs
	*/
	protected $margin = 10;

	/**
	* margin between thumbs
	*/
	protected $thumb_margin = 4;

	/**
	* extra text space under every thumbnail
	*/
	protected $text_space = 20;

	/**
	* width of the contactsheet pic
	*/
	protected $cs_width;

	/**
	* height of the contactsheet pic
	*/
	protected $cs_height;

	/**
	* type of the contactsheet pic
	*/
	protected $cs_type;
	/**#@-*/

	/**
	* holds the image
	*/
	protected $cs_image;

	/**
	* show file infos under every thumb or not
	*
	* @var boolean
	*/
	protected $show_file_infos = FALSE;

	/**#@+
	* @return void
	*/
	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @param string $folder  folder to search in for images, empty string if no dir should be searched through
	* @param boolean $sub_dirs search sub directories or not
	* @uses readFolder()
	*/
	function __construct($folder = '', $sub_dirs = FALSE) {
			$this->readFolder($folder, $sub_dirs);
	} // end constructor


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* searches through given folder for images and adds them to the list of thumbnails to be displayed
	*
	* @param string $folder folder to search in for images
	* @param boolean $sub_dirs search sub directories or not
	* @uses $cache_time
	* @uses addThumbnail()
	* @todo use scandir() for PHP5
	*/
	public function readFolder($folder = '', $sub_dirs = FALSE) {
		if (strlen(trim($folder)) > 0 && is_dir(trim($folder))) {
		    $folder = (string) trim($folder);
		    $sub_dirs = (boolean) $sub_dirs;
		    $handle = @opendir($folder);
		    while ($file = @readdir($handle)) {
			    if ($sub_dirs === TRUE && !eregi("^\.{1,2}$",$file)
			    	&& is_dir($folder . '/' . $file)) {
			    	$this->readFolder($folder . '/' . $file, $sub_dirs);
			    } elseif (!eregi("^\.{1,2}$",$file) && !is_dir($folder . '/' . $file)
			    		  && eregi("\.(jpg|gif|png|wbmp)", $file)) {
			    	$this->addThumbnail($folder . '/' . $file, $this->cache_time);
			    } // end if
		    } // end while
		    @closedir($handle);
		} // end if
	} // end function

	/**
	* sets the max width for all thumbs
	*
	* @param int $width
	* @uses $thumb_width
	*/
	public function setThumbWidth($width = 100) {
		$this->thumb_width = (int) $width;
	} // end function

	/**
	* sets the max height for all thumbs
	*
	* @param int $height
	* @uses $thumb_height
	* @uses $show_file_info
	* @uses $text_space
	*/
	public function setThumbHeight($height = 100) {
		$this->thumb_height = (int) $height;

		if ($this->show_file_infos === TRUE) { // for adding file info
			$this->thumb_height += $this->text_space;
		} // end if
	} // end function

	/**
	* sets the max width and height for all thumbs
	*
	* @param int $width
	* @param int $height
	* @uses setThumbHeight()
	* @uses setThumbWidth()
	*/
	public function setThumbnailSize($width = 100, $height = 100) {
		$this->setThumbWidth($width);
		$this->setThumbHeight($height);
	} // end function

	/**
	* sets the number of columns to be displayed
	*
	* @param int $columns
	* @uses $columns
	*/
	public function setColumns($columns = 3) {
		$this->columns = (int) $columns;
	} // end function

	/**
	* sets the number of rows to be displayed
	*
	* @param int $rows
	* @uses $rows
	*/
	public function setRows($rows = 3) {
		$this->rows = (int) $rows;
	} // end function

	/**
	* sets the number of columns and rows to be displayed
	*
	* @param int $columns
	* @param int $rows
	* @uses setColumns()
	* @uses setRows()
	*/
	public function setGrid($columns = 3, $rows = 3) {
		$this->setColumns($columns);
		$this->setRows($rows);
	} // end function

	/**
	* whether to show file infos under each thumb or not
	*
	* @param boolean $boolean
	* @uses $show_file_info
	* @uses $thumb_height
	* @uses setThumbHeight()
	*/
	public function showFileInfos($boolean = FALSE) {
		$this->show_file_infos = (boolean) $boolean;
		$this->setThumbHeight($this->thumb_height);
	} // end function

	/**
	* sets the margin between thumbs
	*
	* @param int $margin
	* @uses $margin
	*/
	public function setMargin($margin = 10) {
		$this->margin = (int) $margin;
	} // end function

	/**
	* sets the margin between thumbs
	*
	* @param int $margin
	* @uses $margin
	*/
	public function setThumbMargin($margin = 3) {
		$this->thumb_margin = (int) $margin;
	} // end function

	/**
	* sets the background-color for the pic
	*
	* @param int $red
	* @param int $green
	* @param int $blue
	* @uses $bg_color
	*/
	public function setBGColor($red = 255, $green = 255, $blue = 255) {
		$this->bg_color = array($red, $green, $blue);
	} // end function

	/**
	* sets the background-color for the thumbs
	*
	* @param int $red
	* @param int $green
	* @param int $blue
	* @uses $thumb_bg_color
	*/
	public function setThumbBGColor($red = 225, $green = 225, $blue = 225) {
		$this->thumb_bg_color = array($red, $green, $blue);
	} // end function

	/**
	* sets the textcolor for the thumbnail text
	*
	* @param int $red
	* @param int $green
	* @param int $blue
	* @uses $text_color
	*/
	public function setTextColor($red = 0, $green = 0, $blue = 0) {
		$this->text_color = array($red, $green, $blue);
	} // end function

	/**
	* sets the output type of the thumbnail
	*
	* @param string $format gif, jpg, png, wbmp
	* @uses $cs_type
	* @uses $formats
	*/
	public function setOutputFormat($format = 'png') {
		if (array_key_exists(trim($format), $this->formats)) {
			$this->cs_type = $this->formats[trim($format)];
		} // end if
	} // end function

	/**
	* adds a thumbnail of a given picture
	*
	* @param string $file path/filename of the pic to be added
	* @return boolean
	* @uses WMThumbnail
	* @uses $cache_time
	* @uses $show_file_info
	* @uses $text_space
	* @uses $thumb_width
	* @uses $thumb_height
	* @uses $thumbnails
	*/
	public function addThumbnail($file = '') {
		if (file_exists($file)) {
			$thumb = new CachedThumbnail($file, $this->cache_time);
			$correction = (int) (($this->show_file_infos === TRUE) ? $this->text_space : 0);
			$thumb->setMaxSize($this->thumb_width, $this->thumb_height - $correction);
			$this->thumbnails[] = $thumb;
			return (boolean) TRUE;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* calculates the image size with the information given about thumbs, rows, cols and thumbsizes
	*
	* @return void
	* @uses $thumbnails
	* @uses $columns
	* @uses $rows
	* @uses $cs_width
	* @uses $cs_height
	* @uses $thumb_width
	* @uses $thumb_height
	* @uses $margin
	*/
	protected function setContactSheetSize() {
		$needed_rows = ceil(count($this->thumbnails) / $this->columns);

		if ($needed_rows > $this->rows) {
			$this->setRows($needed_rows);
		} // end if

		$this->cs_width = ($this->columns * ($this->thumb_width + ($this->thumb_margin << 1))) + (($this->columns + 1) * $this->margin);
		$this->cs_height = ($this->rows * ($this->thumb_height + ($this->thumb_margin << 1))) + (($this->rows + 1) * $this->margin);
	} // end function

	/**
	* creates and returns a thumbnailbox (with text infos) for a given thumbnail
	*
	* @param object $thumbnail thumbnail object
	* @return mixed
	* @uses WMThumbnail
	* @uses $thumb_width
	* @uses $thumb_height
	* @uses $thumb_bg_color
	* @uses $show_file_info
	* @uses $text_color
	*/
	protected function returnBoxedThumb($thumbnail) {
		if (!is_object($thumbnail)) {
			return (boolean) FALSE;
		} // end if

		$temp_image = @imagecreatetruecolor(($this->thumb_width + ($this->thumb_margin << 1)),
											($this->thumb_height + ($this->thumb_margin << 1)))
											or die ('Cannot Initialize new GD image stream');
		$background_color = imagecolorallocate($temp_image,
											   $this->thumb_bg_color[0],
											   $this->thumb_bg_color[1],
											   $this->thumb_bg_color[2]);
		imagefill($temp_image, 0, 0, $background_color);
		$start_point = (($this->thumb_width + ($this->thumb_margin << 1)) - $thumbnail->getThumbWidth()) >> 1;
		imagecopy($temp_image, $thumbnail->returnThumbnail('png'), $start_point,
				  $this->thumb_margin, 0, 0, $thumbnail->getThumbWidth(),
				  $thumbnail->getThumbHeight());

		if ($this->show_file_infos === TRUE) { // add file info
			$text_color = imagecolorallocate($temp_image,
											 $this->text_color[0],
											 $this->text_color[1],
											 $this->text_color[2]);
			imagestring($temp_image, 1,	2,
						($this->thumb_height + ($this->thumb_margin << 1) - 20),
						$thumbnail->getPictureName(),
						$text_color);
			imagestring($temp_image, 1, 2,
						($this->thumb_height + ($this->thumb_margin << 1) - 10),
						'Size: ' . round(filesize($thumbnail->getPictureName()) / 1024, 2) . ' KB',
						$text_color);
		} // end if
		return $temp_image;
	} // end function

	/**
	* creates the cs image
	*
	* @return void
	* @uses setContactSheetSize()
	* @uses $cs_image
	* @uses $cs_width
	* @uses $cs_height
	* @uses $bg_color
	* @uses $margin
	* @uses $rows
	* @uses $columns
	* @uses $thumbnails
	* @uses returnBoxedThumb()
	* @uses $thumb_width
	* @uses $thumb_height
	*/
	protected function setContactSheetImage() {
		$this->setContactSheetSize();
		$this->cs_image = @imagecreatetruecolor($this->cs_width, $this->cs_height)
		 or die ('Cannot Initialize new GD image stream');
		$background_color = imagecolorallocate($this->cs_image,
											   $this->bg_color[0],
											   $this->bg_color[1],
											   $this->bg_color[2]);
		imagefill($this->cs_image, 0, 0, $background_color);
		$x_pos = $y_pos = $this->margin;
		$count = (int) 0;

		for ($i = 0; $i < $this->rows; $i++) {
			for ($y = 0; $y < $this->columns; $y++) {
				if (isset($this->thumbnails[$count])) {
					imagecopy($this->cs_image,
							  $this->returnBoxedThumb($this->thumbnails[$count++]),
							  $x_pos, $y_pos, 0, 0,
							  $this->thumb_width + ($this->thumb_margin << 1),
							  $this->thumb_height + ($this->thumb_margin << 1));
					/* set starting point for next thumb to next column */
					$x_pos = $x_pos + $this->thumb_width + $this->margin + ($this->thumb_margin << 1);
				} // end if
			} // end for
			$x_pos = $this->margin; // set starting point for next thumb to next row
			$y_pos = $y_pos + $this->thumb_height + $this->margin + ($this->thumb_margin << 1);
		} // end for
	} // end function

	/**
	* outputs the cs image to the browser
	*
	* @param string $format gif, jpg, png, wbmp
	* @param int $quality jpg-quality: 0-100
	* @return mixed
	* @uses setOutputFormat()
	* @uses setContactSheetImage()
	* @uses $cs_type
	* @uses $cs_image
	*/
	public function outputContactSheet($format = 'png', $quality = 75) {
	    $this->setOutputFormat($format);
	    $this->setContactSheetImage();
	    switch ($this->cs_type) {
	        case 1:
	        	header('Content-type: image/gif');
	        	imagegif($this->cs_image);
	            break;
	        case 2:
	        	$quality = (int) $quality;
	        	if ($quality < 0 || $quality > 100) {
					$quality = 75;
	        	} // end if

	        	header('Content-type: image/jpeg');
	        	imagejpeg($this->cs_image, '', $quality);
	            break;
	        case 3:
	        	header('Content-type: image/png');
	            imagepng($this->cs_image);
	            break;
	        case 15:
	            header('Content-type: image/vnd.wap.wbmp');
	            imagewbmp($this->cs_image);
	            break;
	    } // end switch
	    imagedestroy($this->cs_image);
	} // end function

	/**
	* returns the cs image for further processing
	*
	* @return mixed
	* @uses setContactSheetImage()
	* @uses $cs_image
	*/
	public function returnContactSheet() {
	    $this->setContactSheetImage();
	    return $this->cs_image;
	} // end function

	/**
	* returns an array with all the paths to the cached thumbnails
	*
	* @return array
	* @uses setContactSheetImage()
	* @uses $cs_image
	*/
	public function getImagepaths() {
	    foreach ($this->thumbnails as $thumbnail) {
	    	$paths[] = (string) $thumbnail->getCacheFilepath('png', 75);
	    } // end if
	    return $paths;
	} // end function

	/**
	* returns the height of the image
	*
	* @return int
	* @uses $cs_height
	*/
	public function getCSImageHeight() {
		$this->setContactSheetSize();
		return (int) $this->cs_height;
	} // end function

	/**
	* returns the width of the image
	*
	* @return int
	* @uses $cs_width
	*/
	public function getCSImageWidth() {
		$this->setContactSheetSize();
		return (int) $this->cs_width;
	} // end function
} // end class ContactSheet
?>