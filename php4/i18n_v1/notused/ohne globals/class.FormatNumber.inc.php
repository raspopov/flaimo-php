<?php
/**
* @package i18n
*/
/**
* We need the language so we know how long date/time strings have to be displayed
*/
@include_once('class.Translator.inc.php');

/**
* Including the user class is necessary for getting the user-preferences
*/
@include_once('class.User.inc.php');
/**
* Formats numbers and currencies based on Language
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc Formats numbers and currencies based on Language
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @version 1.053
* @since 1.051 - 2003/02/26
*/
class FormatNumber {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* For Translation of date names (needs other class)
	*
	* @desc For Translation of date names (needs other class)
	* @var object
	* @access private
	* @see Translator
	*/
	var $lg;

	/**
	* For getting user settings
	*
	* @desc For getting user settings
	* @var object
	* @access private
	* @see User
	*/
	var $user;

	/**
	* no description
	*
	* @desc no description
	* @var string
	* @access private
	* @see User
	*/
	var $lang = '';

	/**
	* Holds the settings for the currencies
	*
	* @desc Holds the settings for the currencies
	* @var array
	* @access private
	*/
	var $currency_infos;

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $number_settings;

	/**
	* Default value for the minor_unit
	*
	* @desc Default value for the minor_unit
	* @var int
	* @access private
	*/
	var $default_minor_unit = 2;

	/**
	* Whether to encode the final output strings or not
	*
	* @desc Holds the settings for this class
	* @var boolean
	* @access private
	*/
	var $encode_strings = TRUE;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* No information available
	*
	* @desc Constructor
	* @param (string) $language  iso based locale
	* @return (void)
	* @access private
	* @uses setSelectCSSClass()
	* @since 1.051 - 2003/02/26
	*/
	function FormatNumber($language = '') {
		$this->lang = (string) $language;
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the currency file if necessary
	*
	* @desc Reads the default settings from the currency file if necessary
	* @return (void)
	* @access private
	* @since 1.051 - 2003/02/26
	*/
	function readCurrencyInfos() {
		$this->loadLanguageClass();
        if (!isset($this->currency_infos) && file_exists('currency.ini')) {
			$this->currency_infos = (array) parse_ini_file('currency.ini',TRUE);
    	} // end if
	} // end function

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return (void)
	* @access private
	* @since 1.051 - 2003/02/26
	*/
	function readDefaultNumberSettings() {
        if (!isset($this->number_settings) && file_exists('i18n_settings.ini')) {
            $this->number_settings = (array) parse_ini_file('i18n_settings.ini', TRUE);
            $this->default_minor_unit 	=& $this->number_settings['FormatNumber']['default_minor_unit'];
            $this->encode_strings 		= (boolean) $this->number_settings['FormatNumber']['encode_strings'];
    	} // end if
	} // end function

	/**
	* Formats a currency value
	*
	* @desc Formats a currency value
	* @param (float) $real  The value
	* @param (string) $notation  'full' | 'short' | 'symbol' (example: Euro | EUR | €)
	* @param (string) $country  the currency's country
	* @param (boolean) $force_major  Example: $real = 0.56 => for TRUE output is "0,56 Euro", for FALSE "56 Cent"
	* @return (string) $output  formated currency
	* @access public
	* @uses loadLanguageClass(), Language::getLocale(), readCurrencyInfos()
	* @since 1.051 - 2003/02/26
	*/
	function Currency($real = 0, $notation = 'full', $country = '', $force_major = TRUE) {
		$this->readCurrencyInfos();
		$this->readDefaultNumberSettings();
		if (array_key_exists(&$country, $this->currency_infos)) {
			$minor_unit =& $this->currency_infos[$country]['minor_unit'];
		} else {
			$minor_unit =& $this->default_minor_unit;
		} // end if
		$this->loadLanguageClass();
		if ($force_major === FALSE && $real < 1) {
			$y = (int) $minor_unit;
			for ($i = 0, $y = $minor_unit; $i < $y; $i++) {
				$real *= 10;
				$minor_unit -= 1;
			} // end for
			switch(trim($notation)) {
				case 'full':
					$symbol = (string) '_currency_minor_long';
					break;
				case 'short':
					$symbol = (string) '_currency_minor_short';
					break;
				case 'symbol':
				default:
					$symbol = (string) '_currency_minor_symbol';
					break;
			} // end switch
		} else {
			switch(trim($notation)) {
				case 'full':
					$symbol = (string) '_currency_major_long';
					break;
				case 'short':
					$symbol = (string) '_currency_major_short';
					break;
				case 'symbol':
				default:
					$symbol = (string) '_currency_major_symbol';
					break;
			} // end switch
		} // end if
		$output = (string) $this->Number($real, $minor_unit) . ' ' . $this->lg->_($country . $symbol);
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
	* @param (float) $real  The number
	* @param (int) $digits  number of digits to be displayed
	* @return (string) $output  formated percent number
	* @access public
	* @uses loadLanguageClass(), Language::getLocale()
	* @since 1.010 - 2003/01/06
	*/
	function Number($real = 0, $digits = 2) {
		$real = (real) $real;
		$digits = (int) $digits;
		$this->loadLanguageClass();
		switch($this->lg->getLocale()) {
			case 'de':
				return (string) ((floor($real) > 9999) ? number_format($real, $digits, ',', ' ') : number_format($real, $digits, ',', ''));
				break;
			case 'es':
			case 'fr':
			case 'it':
			case 'ru':
				return (string) number_format($real, $digits, ',', ' ');
				break;
			case 'en':
			default:
				return (string) number_format($real, $digits, '.', '');
				break;
		} // end switch
	} // end function

	/**
	* Formats float to percent
	*
	* formats a float variable to percent depending
	* on the language set in the language class
	*
	* @desc Formats float to percent
	* @param (float) $float  The number
	* @param (int) $digits  number of digits to be displayed
	* @return (string) $output  formated percent number
	* @access public
	* @uses loadUserClass(), returnError(), User::getPreferedTime(), loadLanguageClass(), Language::getLocale()
	* @since 1.000 - 2002/10/10
	*/
	function &Percent($float, $digits = 1) {
		if (settype($float, 'double') === FALSE) {
			return (string) $this->returnError($float, __LINE__);
		} // end if
		$float *= 100;
		$this->loadLanguageClass();
		switch($this->lg->getLocale()) {
			case 'de':
			case 'de_at':
			case 'es':
			case 'fr':
			case 'it':
			case 'ru':
            case 'ar':
				$output = (string) number_format($float, $digits,',','.');
				break;
			case 'en':
			default:
				$output = (string) number_format($float, $digits,'.',',');
				break;
		} // end switch
		return (string) $output . '%';
	} // end function


	/**
	* Formats phone numbers according to the country given. Only supports
	* de, at, ch and us at the moment. feel free to mail me formating rules for
	* other countries
	*
	* @desc Formats phone numbers according to the country given
	* @param (string) $number The telephone number
	* @param (string) $area The area code (optional)
	* @param (string) $area The extention (otional)
	* @param (boolean) $int_area Should the international code be displayed or not
	* @param (string) $country For which country should the number be formated. Normally depends on where the phone number is located and not the display language of the homepage
	* @return (string) The formated phone number
	* @access public
	* @since 1.010 - 2002/12/30
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
					if ($area != FALSE) {
						$output_area = (string) $area . '-';
					} else {
						$output_area = (string) '';
					} // end if

					for ($i = $number_length-1; $i >= 0; $i--) {
						if ($i == 2) {
							$output_number = (string) '-' . $output_number;
						} // end if
						$output_number = (string) $numbers[$i] . $output_number;
						$pos++;
					} // end for


					if ($extention != FALSE) {
						$output_extention = (string) ' x ' . $extention;
					} else {
						$output_extention = (string) '';
					} // end if
					break;
			} // end switch
			return (string) $output_int_area . $output_area . $output_number . $output_extention;
		} else {
			return (string) '';
		} // end if
	} // end function

	/**
	* Catches errors and displays the line if the input was somehow wrong
	*
	* @desc Catches errors and displays the line if the input was somehow wrong
	* @param (mixed) $input  String that causes the error
	* @param (int) $line  Line where Error accures
	* @return (string)  Errormessage
	* @access private
	* @since 1.000 - 2002/10/10
	*/
	function returnError($input, $line = '') {
		$return = (string) 'Wrong input: ' . $input . ' (' . basename(__FILE__, '.php');
		return (string) $return . ((strlen(trim($line)) < 1) ? ')' : ' | L: ' . $line . ')' );
	} // end function

	/**
	* Set’s the language variable if it hasn’t been set before
	*
	* @desc Set’s the language variable if it hasn’t been set before
	* @return (object) $lg  Translator
	* @access private
	* @see loadUserClass()
	* @uses checkClass(), Translator
	* @since 1.000 - 2002/10/10
	*/
	function loadLanguageClass() {
		if (!isset($this->lg)) {
			$this->checkClass('Translator', __LINE__);
			$this->lg = (object) new Translator($this->lang,'lang_classFormatNumber');
		} // end if
	} // end function

	/**
	* Set’s the user variable if it hasn’t been set before
	*
	* @desc Set’s the user variable if it hasn’t been set before
	* @return (object) $user  User
	* @access private
	* @see loadLanguageClass()
	* @uses User, checkClass()
	* @since 1.000 - 2002/10/10
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->checkClass('User', __LINE__);
			$this->user = (object) new User();
		} // end if
	} // end function

	/**
	* Checks if a class is available
	*
	* @desc Checks if a class is available
	* @return (void)
	* @access private
	* @since 1.001 - 2002/11/30
	*/
	function checkClass($classname = '', $line = '') {
		if (strlen(trim($classname)) > 0) {
			if (!class_exists($classname)) {
				if (strlen(trim($line)) > 0) {
					$lineinfo = (string) 'at Line ' .$line;
				} // end if
				die('Class "' . get_class($this) . '": Class "' . $classname . '" not found' .$lineinfo . '!');
			} // end if
		} // end if
	} // end function

} // end class FormatNumber
?>
