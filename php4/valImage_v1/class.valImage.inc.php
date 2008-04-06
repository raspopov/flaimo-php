<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.27/4.0.12/4.3.3)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo 'at' gmx 'dot' net>                  |
//|          Rafael Machado Dohms <dooms 'at' terra 'dot' com 'dot' br>  |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package valImage
*/
/**
* Creates an image with random characters and validates the users input if the
* chars in the image match with the users input.
* Based on the class by Rafael Machado Dohms ( dooms 'at' terra 'dot' com 'dot' br)
*
* Tested with Apache 1.3.27 and PHP 4.3.3
* Last change: 2003-09-30
*
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package valImage
* @example sample_form.php Sample script
* @version 1.000
*/
class valImage {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access protected
	* @var string
	*/
	var $form_name = 'valimage_code';
	var $possible_chars = 'AGHacefhjkrStVxY124579';
	var $code;
	/**#@-*/
	/**#@+
	* @access protected
	* @var boolean
	*/
	var $vertical_chars = TRUE;
	var $use_color_chars = TRUE;
	/**#@-*/
	/**#@+
	* @access protected
	* @var array
	*/
	var $color_text = array(0, 0, 0);
	var $color_background = array(255, 255, 255);
	var $color_border = array(128, 128, 128);
	/**#@-*/
	/**#@+
	* @access protected
	* @var int
	*/
	var $str_length = 6;
	var $circles = 10;
	var $char_width = 20;
	var $height = 20;
	var $width;
	/**#@-*/
	/**
	* @access protected
	* @var mixed
	*/
	var $image;

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**#@+
	* @return void
	* @access public
	*/
	/**
	* sets the number of characters to be displayed
	*
	* @param int $length >0
	* @uses $str_length
	*/
	function setStringLength($length = 6) {
		$this->str_length = (int) $length;
	} // end function

	/**
	* whether to use vetical characters or not
	*
	* @param boolean $boolean
	* @uses $vertical_chars
	*/
	function useVerticalChars($boolean = FALSE) {
		$this->vertical_chars = (boolean) $boolean;
	} // end function

	/**
	* whether to use color characters or not
	*
	* @param boolean $boolean
	* @uses $use_color_chars
	*/
	function useColorText($boolean = FALSE) {
		$this->use_color_chars = (boolean) $boolean;
	} // end function

	/**
	* sets the text color (only used if $use_color_chars == FALSE)
	*
	* @param array $rgb 0-255,0-255,0-255
	* @uses $color_text
	*/
	function setTextColor($rgb) {
		$this->color_text = (array) $rgb;
	} // end function

	/**
	* sets the background color
	*
	* @param array $rgb 0-255,0-255,0-255
	* @uses $color_background
	*/
	function setBackgroundColor($rgb) {
		$this->color_background = (array) $rgb;
	} // end function

	/**
	* sets the border color
	*
	* @param array $rgb 0-255,0-255,0-255
	* @uses $color_border
	*/
	function setBorderColor($rgb) {
		$this->color_border = (array) $rgb;
	} // end function

	/**
	* sets the number of circles to be rendered
	*
	* @param int $circles >0
	* @uses $circles
	*/
	function setCircles($circles = 10) {
		if ($height >= 0) {
			$this->circles = (int) $circles;
		} // end if
	} // end function

	/**
	* sets the number of circles to be rendered
	*
	* @param int $height
	* @uses $height
	*/
	function setImageHeight($height = 20) {
		if ($height > 4) {
			$this->height = (int) $height;
		} // end if
	} // end function
	/**#@-*/

	/**
	* calculates the image width
	*
	* @return void
	* @access protected
	* @uses $width
	* @uses $str_length
	* @uses $char_width
	*/
	function setImageWidth() {
		$this->width = ($this->str_length * $this->char_width) + 40;
	} // end function

	/**
	* returns the image height
	*
	* @return int $height
	* @access public
	* @uses $height
	*/
	function getImageHeight() {
		return (int) $this->height;
	} // end function

	/**
	* returns the image width
	*
	* @return int $width
	* @access public
	* @uses setImageWidth()
	* @uses $width
	*/
	function getImageWidth() {
		$this->setImageWidth();
		return (int) $this->width;
	} // end function

	/**
	* returns the name of the session/form element
	*
	* @return string $form_name
	* @access public
	* @uses $form_name
	*/
	function getFormName() {
		return (string) $this->form_name;
	} // end function

	/**
	* generates the code string for the image
	*
	* @param int $length
	* @return void
	* @access protected
	* @uses $code
	* @uses $possible_chars
	*/
	function generateCode($length = 6) {
		if (!isset($this->code) && $length > 0) {
			rand(0,time());
			$this->code = '';
			$length = (int) $length;
			while(strlen($this->code) < $length) {
				$this->code .= substr($this->possible_chars, (rand() % (strlen($this->possible_chars))), 1);
			} // end while
		} // end if
	} // end function

	/**
	* writes the code to the session
	*
	* @return void
	* @access protected
	* @uses $code
	* @uses $form_name
	* @uses $str_length
	* @uses generateCode()
	*/
	function writeCode() {
		$this->generateCode($this->str_length);
		$_SESSION[$this->form_name] = (string) $this->code;
	} // end if

	/**
	* checks if user input matches with the session code
	*
	* @return boolean
	* @access public
	* @uses $form_name
	*/
	function checkCode() {
		if (!isset($_SESSION[$this->form_name]) || (trim($_POST[$this->form_name]) != $_SESSION[$this->form_name])) {
			return (boolean) FALSE;
		}  // end if
		return (boolean) TRUE;
	} // end function

	/**
	* returns a color variable for a given rgb array
	*
	* @param array $rgb
	* @param boolean $random
	* @return mixed
	* @access protected
	* @uses $image
	* @uses $color_background
	*/
	function generateColor($rgb, $random = FALSE) {
		if ($random == FALSE) {
			return imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]);
		} else {
			$r = (($this->color_background[0] > 128) ? rand(0, 128) : rand(128, 255));
			$g = (($this->color_background[1] > 128) ? rand(0, 128) : rand(128, 255));
			$b = (($this->color_background[2] > 128) ? rand(0, 128) : rand(128, 255));
			return imagecolorallocate($this->image, $r, $g, $b);
		} // end if
	} // end function

	/**
	* returns a color variable for a given rgb array
	*
	* @return void
	* @access protected
	* @uses $image
	* @uses setImageWidth()
	* @uses $width
	* @uses $height
	* @uses generateColor()
	* @uses $color_border
	* @uses $color_background
	* @uses $circles
	* @uses writeCode()
	* @uses $str_length
	* @uses $code
	* @uses $vertical_chars
	* @uses $color_text
	* @uses $use_color_chars
	*/
	function generateImage() {
		if (!isset($this->image)) {
			$this->setImageWidth();
			$this->image = imagecreatetruecolor($this->width, $this->height);

			imagefill($this->image, 0, 0, $this->generateColor($this->color_border, FALSE));
			imagefilledrectangle ($this->image, 1, 1, ($this->width - 2),
								  ($this->height - 2),
								  $this->generateColor($this->color_background, FALSE));

			for ($i = 1; $i <= $this->circles; $i++) { // generate the circles
				$c_randomcolor = array(rand(100, 255), rand(100, 255), rand(100, 255));
				imageellipse($this->image, rand(0, $this->width - 10),
							 rand(0, $this->height-3), rand(20, 60), rand(20, 60),
							 $this->generateColor($c_randomcolor, FALSE));
			} // end for

			$start_x = 20;
			$this->writeCode();
			for ($i = 0; $i < $this->str_length; $i++) { // generate the characters
				$char = substr($this->code, $i, 1);
				$font = rand(4, 5);
				$y = round(($this->height - 15) / 2);
				if ($this->vertical_chars == FALSE || ($i % 2) == 0) {
					imagechar($this->image, $font, $start_x, $y, $char,
							  $this->generateColor($this->color_text, $this->use_color_chars));
				} else {
					imagecharup($this->image, $font, $start_x, $y + 10, $char,
								$this->generateColor($this->color_text, $this->use_color_chars));
				} // end if
				$start_x = $start_x + $this->char_width;
			} // end for
		} // end if
	} // end function

	/**
	* outputs the image to the browser
	*
	* @param string $format
	* @param int $quality
	* @return void
	* @access public
	* @uses generateImage()
	* @uses $image
	*/
	function outputImage($format = 'png', $quality = 75) {
	    $this->generateImage();
	    $image_type = 'png';
		$formats = array('gif' => 1, 'jpg' => 2, 'png' => 3, 'wbmp' => 15,
						 'string' => 999);
		if (array_key_exists(trim($format), $formats)) {
			$image_type = $formats[trim($format)];
		} // end if

	    switch ($image_type) {
	        case 1:
	        	header('Content-type: image/gif');
	        	imagegif($this->image);
	            break;
	        case 2:
	        	$quality = (int) $quality;
	        	if ($quality < 0 || $quality > 100) {
					$quality = 75;
	        	} // end if

	        	header('Content-type: image/jpeg');
	        	imagejpeg($this->image, '', $quality);
	            break;
	        case 3:
	        	header('Content-type: image/png');
	            imagepng($this->image);
	            break;
	        case 15:
	            header('Content-type: image/vnd.wap.wbmp');
	            imagewbmp($this->image);
	            break;
	    } // end switch
	    imagedestroy($this->image);
	} // end function

	/**
	* returns the image variable
	*
	* @return mixed
	* @access public
	* @uses generateImage()
	* @uses $image
	*/
	function returnImage() {
		$this->generateImage();
		return $this->image;
	} // end function
} // end class valImage
?>