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
* We need the language so we know how long date/time strings have to be displayed
*/
@require_once 'class.Translator.inc.php';

/**
* Including the user class is necessary for getting the user-preferences
*/
@require_once 'class.I18NUser.inc.php';
/**
* Including the abstract base class
*/
@require_once 'class.I18N.inc.php';
/**
* Formats numbers and currencies based on Language
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-05-04
*
* @desc Formats numbers and currencies based on Language
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @category FLP
* @version 1.061
* @since 1.051 - 2003-02-26
*/
class FormatNumber extends I18N {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	*/
	/**
	* For Translation of date names (needs other class)
	*
	* @desc For Translation of date names (needs other class)
	* @var object
	* @see Translator
	*/
	var $lg;

	/**
	* For getting user settings
	*
	* @desc For getting user settings
	* @var object
	* @see User
	*/
	var $user;

	/**
	* no description
	*
	* @desc no description
	* @var string
	* @see User
	*/
	var $lang = '';

	/**
	* Default value for the minor_unit
	*
	* @desc Default value for the minor_unit
	* @var int
	*/
	var $default_minor_unit = 2;

	/**
	* Whether to encode the final output strings or not
	*
	* @desc Holds the settings for this class
	* @var boolean
	*/
	var $encode_strings = TRUE;

	/**
	* Symbol for the decimal point
	*
	* @desc Symbol for the decimal point
	* @var string
	*/
	var $decimal_point;

	/**
	* Symbol for the thousands seperator
	*
	* @desc Symbol for the thousands seperator
	* @var string
	*/
	var $thousands_sep;

	/**
	* Default symbol for the decimal point
	*
	* @desc Default symbol for the decimal point
	* @var string
	*/
	var $default_decimal_point;

	/**
	* Default symbol for the thousands seperator
	*
	* @desc Default symbol for the thousands seperator
	* @var string
	*/
	var $default_thousands_sep;

	/**
	* Default minor unit
	*
	* @desc DDefault minor unit
	* @var int
	*/
	var $minor_unit;

	/**
	* Default currency code
	*
	* @desc Default currency code
	* @var int
	*/
	var $code;
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @return void
	* @access private
	*/
	/**
	* Constructor
	*
	* No information available
	*
	* @desc Constructor
	* @param string $language  iso based locale
	* @uses I18N::I18N()
	* @since 1.051 - 2003-02-26
	*/
	function FormatNumber($language = '') {
		parent::I18N();
		$this->lang = (string) $language;
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @uses I18N::readINIsettings()
	* @since 1.051 - 2003-02-26
	*/
	function readDefaultSettings() {
        parent::readINIsettings();
        if (isset($GLOBALS[$this->i18n_globalname])) {
            $this->default_minor_unit 		=& $GLOBALS[$this->i18n_globalname]['FormatNumber']['default_minor_unit'];
            $this->default_decimal_point 	=& $GLOBALS[$this->i18n_globalname]['FormatNumber']['default_decimal_point'];
            $this->default_thousands_sep 	=& $GLOBALS[$this->i18n_globalname]['FormatNumber']['default_thousands_sep'];
            $this->encode_strings 			= (boolean) $GLOBALS[$this->i18n_globalname]['FormatNumber']['encode_strings'];
    	} // end if
	} // end function

	/**
	* Reads the default settings for numbers and dates from the settings file if necessary
	*
	* @desc Reads the default settings for numbers and dates from the settings file if necessary
	* @uses I18N::readL10NINIsettings()
	* @uses I18N::readL10NINIsettings()
	* @uses Language::getLocale()
	* @since 1.055 - 2003-04-22
	*/
	function readDefaultL10NSettings() {
        $this->loadLanguageClass();
        parent::readL10NINIsettings($this->lg->getLocale());
        if (isset($GLOBALS[$this->l10n_globalname])) {
            $this->minor_unit 		=& $GLOBALS[$this->l10n_globalname]['Currency']['minor_unit'];
            $this->code 			=& $GLOBALS[$this->l10n_globalname]['Currency']['code'];
            $this->decimal_point 	=& $GLOBALS[$this->l10n_globalname]['Number']['decimal_point'];
            $this->thousands_sep 	=& $GLOBALS[$this->l10n_globalname]['Number']['thousands_sep'];
    	} // end if
	} // end function

	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @see loadUserClass()
	* @uses checkClass()
	* @uses Translator
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->lg = (object) new Translator($this->lang,'lang_classFormatNumber');
		} // end if
	} // end function

	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @see loadLanguageClass()
	* @uses User
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user = (object) new i18nUser();
		} // end if
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	*/
	/**
	* Formats a currency value
	*
	* @desc Formats a currency value
	* @param float $real  The value
	* @param string $notation  'full' | 'short' | 'symbol' (example: Euro | EUR | €)
	* @param string $country  the currency's country
	* @param boolean $force_major  Example: $real = 0.56 => for TRUE output is "0,56 Euro", for FALSE "56 Cent"
	* @return string $output  formated currency
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	* @uses readCurrencyInfos()
	* @since 1.051 - 2003-02-26
	*/
	function currency($real = 0, $notation = 'full', $country = '', $force_major = TRUE) {
		$this->readDefaultL10NSettings();
		if (isset($this->minor_unit)) {
			$minor_unit =& $this->minor_unit;
		} else {
			$this->readDefaultSettings();
			$minor_unit =& $this->default_minor_unit;
		} // end if
		$this->loadLanguageClass();
		if ($force_major === FALSE && $real < 1) {
			$y = (int) $minor_unit;
			for ($i = 0, $y = $minor_unit; $i < $y; $i++) {
				$real *= 10;
				$minor_unit -= 1;
			} // end for
			$symbol = (string) '_currency_minor_';
		} else {
			$symbol = (string) '_currency_major_';
		} // end if
		switch(trim($notation)) {
			case 'full':
				$symbol .= (string) 'long';
				break;
			case 'short':
				$symbol .= (string) 'short';
				break;
			case 'symbol':
			default:
				$symbol .= (string) 'symbol';
				break;
		} // end switch
		$output = (string) $this->Number($real, $minor_unit) . ' ' . $this->lg->_($country . $symbol, 'lang_classFormatNumber');
		if ($this->encode_strings === TRUE) {
			return (string) (($this->lg->isUTFencoded($this->lg->getLocale()) === TRUE) ? $this->lg->utf2html($output) : htmlentities($output));
		} else {
			return (string) $output;
		} // end if
	} // end function

	/**
	* Formats float
	*
	* @desc Formats float
	* @param float $real  The number
	* @param int $digits  number of digits to be displayed
	* @return string $output  formated percent number
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	* @since 1.010 - 2003-01-06
	*/
	function number($real = 0, $digits = 2) {
		$real = (real) $real;
		$digits = (int) $digits;
		$this->readDefaultL10NSettings();
		$this->loadLanguageClass();
		if (isset($this->decimal_point) && isset($this->thousands_sep)) {
			return (string) number_format($real, $digits, $this->decimal_point, $this->thousands_sep);
		} else {
			return (string) number_format($real, $digits, $this->default_decimal_point, $this->default_thousands_sep);
		} // end if
	} // end function

	/**
	* Formats float to percent
	*
	* formats a float variable to percent depending
	* on the language set in the language class
	*
	* @desc Formats float to percent
	* @param float $float  The number
	* @param int $digits  number of digits to be displayed
	* @return string $output  formated percent number
	* @uses loadUserClass()
	* @uses returnError()
	* @uses I18NUser::getPreferedTime()
	* @uses loadLanguageClass()
	* @uses Language::getLocale()
	*/
	function &percent($float, $digits = 1) {
		if (settype($float, 'double') === FALSE) {
			return (string) $this->returnError($float, __LINE__);
		} // end if
		$float *= 100;
		$this->readDefaultL10NSettings();
		$this->loadLanguageClass();
		if (isset($this->decimal_point) && isset($this->thousands_sep)) {
			return (string) number_format($float, $digits, $this->decimal_point, $this->thousands_sep) . '%';
		} else {
			return (string) number_format($float, $digits, $this->default_decimal_point, $this->default_thousands_sep) . '%';
		} // end if
	} // end function


	/**
	* Formats phone numbers according to the country given. Only supports
	* de, at, ch and us at the moment. feel free to mail me formating rules for
	* other countries
	*
	* @desc Formats phone numbers according to the country given
	* @param string $number The telephone number
	* @param string $area The area code (optional)
	* @param string $area The extention (otional)
	* @param boolean $int_area Should the international code be displayed or not
	* @param string $country For which country should the number be formated. Normally depends on where the phone number is located and not the display language of the homepage
	* @return string The formated phone number
	* @since 1.010 - 2002-12-30
	*/
	function formatTelNumber($number = FALSE, $area = FALSE, $extention = FALSE, $int_area = FALSE, $country = 'us') {
		if ($number != FALSE) {
			$pos 				= (int) 0;
			$int_area_codes 	= (array) array(
												'de' => '49',
												'at' => '43',
												'ch' => '41',
												'us' =>  '1',
												'au' => '61',
												'fr' => '33',
												'uk' => '44',
												'it' => '39',
												'ca' =>  '1',
												'pl' => '48',
												'es' => '34'
											   );
			$output_int_area 	= $output_area = $output_number = $output_extention = (string) '';
			$number 			= (string) trim($number);
			$number_length 		= (int) strlen($number);

			for ($i = 0; $i < $number_length; $i++) {
				$numbers[] = (int) substr($number, $i, 1);
			} // end for
			if ($area != FALSE) {
				$area 		 = (string) trim($area);
				$area_length = (int) strlen($area);
				for ($i = 0; $i < $area_length; $i++) {
					$areacode[] = (int) substr($area, $i, 1);
				} // end for
			} // end if
			if ($extention != FALSE) {
				$extention 	= (string) trim($extention);
				$ext_length = (int) strlen($extention);
				for ($i = 0; $i < $ext_length; $i++) {
					$ext[] = (int) substr($extention, $i, 1);
				} // end for
			} // end if
			if ($int_area == TRUE && array_key_exists($country, $int_area_codes)) {
				$output_int_area = (string) '+' . $int_area_codes[$country] . ' ';
			} // end if

			switch ($country) {
				case 'de':
					if ($area != FALSE) {
						if ($int_area == TRUE && $areacode[0] == 0) {
							array_shift($areacode);
							$area_length = (int) count($areacode);
						} // end if
						for ($i = $area_length-1; $i >= 0; $i--) {
							if ($pos == 2) {
								$output_area = (string) ' ' . $output_area;
								$pos = (int) 0;
							} // end if
							$output_area = (string) $areacode[$i] . $output_area;
							$pos++;
						} // end for
						$output_area = (string) '(' . $output_area . ') ';
					} else {
						$output_area = (string) '';
					} // end if

					$pos = (int) 0;
					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($pos == 2) {
							$output_number = (string) ' ' . $output_number;
							$pos = (int) 0;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for

					$pos = (int) 0;
					if ($extention != FALSE) {
						for ($i = $ext_length-1; $i >= 0; $i--) {
							if ($ext_length % 2 == 0 && $ext_length > 3 && $pos == 2) {
								$output_extention = (string) ' ' . $output_extention;
								$pos = (int) 0;
							} // end if
							$output_extention = (string) $ext[$i] . $output_extention;
							$pos++;
						} // end for
						$output_extention = (string) '-' . $output_extention;
					} // end if
					break;
				case 'at':
				case 'ch':
					if ($area != FALSE) {
						if ($int_area == TRUE && $areacode[0] == 0) {
							array_shift($areacode);
							$area_length = (int) count($areacode);
						} // end if
						for ($i = $area_length-1; $i >= 0; $i--) {
							$output_area = (string) $areacode[$i] . $output_area;
						} // end for
						$output_area = (string) '(' . $output_area . ') ';
					} else {
						$output_area = (string) '';
					} // end if

					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($pos == 2) {
							if (($number_length % 2 == 0) ||
								(($number_length % 2 != 0) && $number_length > 4 && $i > 1)) {
								$output_number = (string) ' ' . $output_number;
							} // end if
							$pos = (int) 0;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for

					$pos = (int) 0;
					if ($extention != FALSE) {
						for ($i = $ext_length-1; $i >= 0; $i--) {
							if ($ext_length % 2 == 0 && $ext_length > 3 && $pos == 2) {
								$output_extention = (string) ' ' . $output_extention;
								$pos = (int) 0;
							} // end if
							$output_extention = (string) $ext[$i] . $output_extention;
							$pos++;
						} // end for
						$output_extention = (string) '-' . $output_extention;
					} // end if
					break;
				case 'us':
				default:
					$output_area = (string) (($area != FALSE) ? $area . '-' : '');

					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($i == 2) {
							$output_number = (string) '-' . $output_number;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for

					$output_extention = (string) (($extention != FALSE) ? ' x ' . $extention : '');
					break;
			} // end switch
			return (string) $output_int_area . $output_area . $output_number . $output_extention;
		} else {
			return (string) '';
		} // end if
	} // end function

	/**
	* Changes the language of the translator object
	*
	* @desc Changes the language of the translator object
	* @param string $locale  iso-code
	* @return void
	* @uses loadLanguageClass()
	* @uses Translator::changeLocale()
	* @since 1.055 - 2003-04-20
	*/
	function changeLocale($locale) {
		$this->loadLanguageClass();
		$this->lg->changeLocale($locale);
		$this->readDefaultL10NSettings();
	} // end function
	/**#@-*/

	/**
	* Catches errors and displays the line if the input was somehow wrong
	*
	* @desc Catches errors and displays the line if the input was somehow wrong
	* @param mixed $input  String that causes the error
	* @param int $line  Line where Error accures
	* @return string  Errormessage
	* @access private
	*/
	function returnError($input, $line = '') {
		$return = (string) 'Wrong input: ' . $input . ' (' . basename(__FILE__, '.php');
		return (string) $return . ((strlen(trim($line)) < 1) ? ')' : ' | L: ' . $line . ')' );
	} // end function
} // end class FormatNumber
?>
