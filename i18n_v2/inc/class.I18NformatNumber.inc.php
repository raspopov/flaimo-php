<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* formats numbers based on the current locale
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NformatNumber extends I18Nbase {

	/**
	* @var string namespaces needed for translating strings in this class
	*/
	protected $namespace = 'lang_classFormatNumber';

	/**
	* @var object holds a I18Ntranslator object
	*/
	protected $translator;

	/**
	* @var array prefix array
	*/
	protected $notations = array('full' => 'long', 'short' => 'short', 'symbol' => 'symbol');

	/**
	* @param object $locale I18Nlocale
	* @return void
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18Ntranslator
	*/
	public function __construct(&$locale = NULL) {
		if (!($locale instanceOf I18Nlocale)) {
			$locale =& parent::getI18NfactoryLocale();
		} // end if
		$this->translator = new I18Ntranslator($this->namespace, $locale);
	} // end constructor

	/**
	* returns the currency code
	* @uses I18Nbase::$translator
	* @return int
	*/
	public function getCurrencyCode() {
		return $this->translator->getTranslatorLocale()->getL10Nsetting('code');
	} // end function

	/**
	* formats float
	* @param float $real  The number
	* @param int $digits  number of digits to be displayed
	* @return string formated percent number
	* @uses I18NformatNumber::$translator
	* @uses I18Nbase::isFilledString()
	* @uses I18Nbase::getI18Nsetting()
	*/
	public function number($real = 0, $digits = 2) {
		$real = (real) $real;
		$digits = (int) $digits;
		$decimal_point =& $this->translator->getTranslatorLocale()->getL10Nsetting('decimal_point');
		$thousands_sep =& $this->translator->getTranslatorLocale()->getL10Nsetting('thousands_sep');

		if (parent::isFilledString($decimal_point) == FALSE ||
			parent::isFilledString($thousands_sep) == FALSE) {
			$decimal_point =& parent::getI18Nsetting('default_decimal_point');
			$thousands_sep =& parent::getI18Nsetting('default_thousands_sep');
		} // end if
		return number_format($real, $digits, $decimal_point, $thousands_sep);
	} // end function

	/**
	* formats float to percent
	* @param float $float the number
	* @param int $digits  number of digits to be displayed
	* @return string formated percent number
	* @uses I18NformatNumber::number()
	*/
	public function percent($float, $digits = 1) {
		$float = (float) $float;
		if ($float < 0 || $float > 1) {
			return (boolean) FALSE;
		} // end if

		$float *= 100;
		return $this->number($float, $digits);
	} // end function

	/**
	* formats a currency value
	* @param float $real the value
	* @param string $notation  'full' | 'short' | 'symbol' (example: Euro | EUR | €)
	* @param string $country the currency's country
	* @param boolean $force_major Example: $real = 0.56 => for TRUE output is "0,56 Euro", for FALSE "56 Cent"
	* @param string $position  'before' | 'after' (overrides the i10n setting for the locale)
	* @return string formated currency
	* @uses I18NformatNumber::$translator
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18NformatNumber::$notations
	* @uses I18NformatNumber::number()
	*/
	public function currency($real = 0, $notation = 'full', $country = '', $force_major = TRUE, $position = FALSE) {
		$minor_unit =& $this->translator->getTranslatorLocale()->getL10Nsetting('minor_unit');
		$real = (real) $real;
		if ($minor_unit == FALSE) {
			$minor_unit =& parent::getI18Nsetting('default_minor_unit');
		} // end if

		$symbol = '_currency_major_';
		if ($force_major === FALSE && $real < 1) {
			$symbol = '_currency_minor_';

			for ($i = 0; $i <= $minor_unit; $i++) {
				$real *= 10;
				$minor_unit -= 1;
			} // end for
		} // end if
		$symbol .= (array_key_exists($notation, $this->notations)) ? $this->notations[$notation] : 'symbol';

		if ($notation == 'symbol') {
			if ($position == FALSE) {
				$position =& $this->translator->getTranslatorLocale()->getL10Nsetting('symbol_position');
			} // end if
			$sym =  $this->translator->_($country . $symbol, 'lang_classFormatNumber');
			if ($position == 'before') {
				return $sym . ' ' . $this->number($real, $minor_unit);
			} else {
				return $this->number($real, $minor_unit) . ' ' . $sym;
			}// end if
		} // end if

		return $this->number($real, $minor_unit) . ' ' . $this->translator->_($country . $symbol, 'lang_classFormatNumber');
	} // end function
} // end class I18NformatNumber
?>