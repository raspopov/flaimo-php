<?php

/**
* Class for creating MCrypt functions
* @author Michael Wimmer <flaimo@gmx.net>
* @category flaimo-php
* @copyright Copyright © 2002-2008, Michael Wimmer
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package MCrypt
* @version 1.00
*/
class MCrypt {
	const ALGORITHM = MCRYPT_BLOWFISH;
	const MODE = MCRYPT_MODE_NOFB;
	const RANDOM_SOURCE = MCRYPT_RAND;

	protected $key = '';

	function __construct($password = '') {
		$this->key = $password;
	} // end constructor

	protected function getIVSize() {
		return mcrypt_get_iv_size(self::ALGORITHM, self::MODE);
	} // end function

	protected function getIV() {
		if (!isset($this->iv)) {
			$this->iv = mcrypt_create_iv($this->getIVSize(), self::RANDOM_SOURCE);
		} // end if
		return $this->iv;
	} // end function
	
	public function encrypt($cleartext = '') {
		$ciphertext = mcrypt_encrypt(self::ALGORITHM, $this->key, $cleartext, self::MODE, $this->getIV());
		$string = base64_encode($this->getIV() . $ciphertext);
		return $string;
	} // end function
	
	public function decrypt($string = '') {
		$string = base64_decode($string);
		$ciphertext = substr($string, $this->getIVSize());
		$iv = substr($string, 0, $this->getIVSize());
		return mcrypt_decrypt(self::ALGORITHM, $this->key, $ciphertext, self::MODE, $iv);
	} // end function
} // end class
?>
