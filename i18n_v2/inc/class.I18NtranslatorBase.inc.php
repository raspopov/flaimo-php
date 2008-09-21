<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* base class for all translatorXXX classes; provides general methods
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3
*/
abstract class I18NtranslatorBase extends I18Nbase {

	/**
	* @var array
	*/
	protected $namespaces;

	/**
	* @var object given locale from the constructor
	*/
	protected $locale;

	/**
	* @var array available translator-locales
	*/
	protected $locales;

	/**
	* @var object translator-locale
	*/
	protected $translator_locale;

	/**
	* @param string $namespaces comma seperated string with namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorBase::setI18NLocale()
	* @uses I18NtranslatorBase::setNamespaces()
	* @uses I18Nbase::__construct()
	* @return void
	*/
	public function __construct($namespaces = '', I18Nlocale &$locale = NULL) {
		$this->setI18NLocale($locale);
		$this->setNamespaces($namespaces);
		parent::__construct();
	} // end constructor

	/**
	* set the $locale var with the given locale from the constructor
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorBase::checkLocale()
	* @uses I18Nlocale::getI18NLocale()
	* @uses I18NtranslatorBase::$locale
	* @return object
	*/
	protected function setI18NLocale(I18Nlocale &$locale = NULL) {
		if ($locale == NULL ||
			$this->checkLocale($locale->getI18NLocale()) == FALSE) {
			$this->locale = new I18Nlocale();
			return (boolean) TRUE;
		} // end if
		$this->locale =& $locale;
		return (boolean) TRUE;
	} // end function

	/**
	* returns the locale given via the constructor
	* @uses I18NtranslatorBase::$locale
	* @return object
	*/
	public function getI18Nlocale() {
		return $this->locale;
	} // end function

	/**
	* returns the encoding for the current translator locale
	* @uses I18Ntranslator::getL10Nsetting()
	* @return string
	*/
	public function getEncoding() {
		return $this->getTranslatorLocale()->getL10Nsetting('encoding');
	} // end function

	/**
	* returns the number of strings currently used by an translator object
	* @uses I18NtranslatorBase::getTranslationTable()
	* @return int
	*/
    public function getCountStrings() {
		return count($this->getTranslationTable());
    } // end function

	/**
	* set the namespace array compining given namespaces from the constructor and the i18n_settings.ini
	* @param string $namespaces comma seperated string with namespaces
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18Nbase::isFilledString()
	* @uses I18Nbase::getI18Nsetting()
	* @return void
	*/
	protected function setNamespaces($namespaces = '') {
		$this->namespaces = array();
		if (parent::isFilledString($namespaces) == TRUE) {
			$this->namespaces = split('[,]', $namespaces);
		} // end if

		$this->namespaces = array_merge(split('[,]', parent::getI18Nsetting('default_namespaces')), $this->namespaces);
		$this->namespaces = array_filter(array_unique(array_map('trim', $this->namespaces)), 'strlen');
	} // end function

	/**
	* returns the namespaces array
	* @uses I18NtranslatorBase::$namespaces
	* @return array
	*/
	public function getNamespaces() {
		return $this->namespaces;
	} // end function

	/**
	* retreive the available translator locales
	* @return boolean
	*/
	abstract protected function setLocales();

	/**
	* returns the available translator locales
	* @uses I18NtranslatorBase::$locales
	* @uses I18NtranslatorBase::setLocales()
	* @return array
	*/
	public function getLocales() {
		if (!isset($this->locales)) {
			$this->setLocales();
		} // end if
		return $this->locales;
	} // end function

	/**
	* returns temporary array with possible locales from the session available
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18NtranslatorBase::$locales
	* @return boolean
	*/
	protected function getSessionLocales() {
		if (parent::getI18Nsetting('locale_checking') == TRUE ||
			!isset($_SESSION['i18n']['translator_locales'])) {
			return (boolean) FALSE;
		} // end if
		foreach ($_SESSION['i18n']['translator_locales'] as $key => $real_locale) {
			$this->locales[$key] =& parent::getI18NfactoryLocale($real_locale);
		} // end foreach
		return (count($this->locales) > 0) ? TRUE : FALSE;
	} // end function

	/**
	* returns the real locale for an alias, else returns the given locale
	* @param string $locale
	* @uses I18NtranslatorBase::setLocales()
	* @uses I18NtranslatorBase::$locales
	* @return mixed object (I18Nlocale) / boolean
	*/
	public function getRealLocale($locale = '') {
		if ($this->setLocales() === TRUE &&
			array_key_exists($locale, $this->locales)) {
			return $this->locales[$locale];
		} else {
			return (boolean) FALSE;
		} // end if
	} // end if

	/**
	* checks if a locale is still available
	* @param string $locale
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18NtranslatorBase::getLocales()
	* @return boolean
	*/
	public function checkLocale($locale = '') {
		if (parent::getI18Nsetting('locale_checking') === FALSE) {
			return (boolean) TRUE;
		} // end if
		return (boolean) ((array_key_exists($locale, $this->getLocales())) ? TRUE : FALSE);
	} // end function

	/**
	* sets the locale used for translation
	* @uses I18Nlocale::getI18NLocales()
	* @uses I18NtranslatorBase::getLocales()
	* @uses I18NtranslatorBase::$translator_locale
	* @uses I18Nlocale::getI18NLanguages()
	* @uses I18Nbase::getI18Nsetting()
	* @return boolean
	*/
	protected function setTranslatorLocale() {
		$user_locales = $this->locale->getI18NLocales();
		$available_locales = array_keys($this->getLocales());
		$matches = array_intersect($user_locales, $available_locales);

		if (count($matches) > 0) {
			$this->translator_locale =& $this->locales[array_shift($matches)];
			return (boolean) TRUE;
		} // end if
		$user_languages = $this->locale->getI18NLanguages();
		$matches = array_intersect($user_languages, $available_locales);
		if (count($matches) < 1) {
			$this->translator_locale = new I18Nlocale(parent::getI18Nsetting('default_locale'));
			return (boolean) TRUE;
		} // end if
	} // end function

	/**
	* returns the current locale used for translation
	* @uses I18NtranslatorBase::setTranslatorLocale()
	* @return object I18Nlocale
	*/
	public function getTranslatorLocale() {
		if (!isset($this->translator_locale)) {
			$this->setTranslatorLocale();
		} // end if
		return $this->translator_locale;
	} // end function

	/**
	* returns the number of availale translator locales
	* @return int
	*/
    public function getCountLocales() {
        if (!isset($this->locales)) {
			$this->setLocales();
		} // end if
         return (int) count($this->locales);
    } // end function

	/**
	* writes the last update timestamp to the session
	* @param string $namespace
	* @param int $timestamp
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @return boolean
	*/
	protected function setLastUpdateSession($namespace = '', $timestamp = 0) {
		$locale = $this->getTranslatorLocale()->getI18Nlocale();
		$_SESSION['i18n']['settings']['last_update'][$locale][$namespace] = (int) $timestamp;
		return (boolean) TRUE;
	} // end function

	/**
	* returns the last update timestamp for a given namespace
	* @param string $namespace
	* @uses I18NtranslatorBase::getTranslatorLocale()
	* @return boolean
	*/
	protected function getLastUpdateSession($namespace = '') {
		$locale = $this->getTranslatorLocale()->getI18Nlocale();
		if (isset($_SESSION['i18n']['settings']['last_update'][$locale][$namespace])) {
			return $_SESSION['i18n']['settings']['last_update'][$locale][$namespace];
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* returns the last update timestamp for all current namespaces
	* @param string $namespaces
	* @uses I18NtranslatorBase::$namespaces
	* @uses I18NtranslatorBase::getLastUpdateDateNamespace()
	* @return boolean
	*/
	public function getLastUpdateDate($namespaces = '') {
		$lastchange = (boolean) FALSE;
		$namespaces = (array) ((strlen(trim($namespaces)) < 1) ? $this->namespaces : split('[,]', $namespaces));
		$namespaces = array_unique(array_map('trim', $namespaces));

        foreach ($namespaces as $namespace) {
            if (($currentdate = $this->getLastUpdateDateNamespace($namespace)) === FALSE) {
				continue;
            } // end if

            if ($currentdate > $lastchange) {
                $lastchange = (int) $currentdate;
            } // end if
        } // end foreach
		return $lastchange;
	} // end function

	/**
	* changes the locale of an translator object and resetzs the translation data
	* @param object $locale I18Nlocale object
	* @uses I18NtranslatorBase::$translation_table
	* @uses I18NtranslatorBase::$count_strings
	* @uses I18NtranslatorBase::getLocales()
	* @uses I18Nlocale::getI18Nlocale()
	* @return boolean
	*/
	public function changeLocale(I18Nlocale &$locale = NULL) {
		if ($locale == NULL ||
			!array_key_exists($locale->getI18Nlocale(), $this->getLocales())) {
			return (boolean) FALSE;
		} // end if

		if (isset($this->translation_table)) {
			unset($this->translation_table);
		} // end if
		if (isset($this->count_strings)) {
			unset($this->count_strings);
		} // end if

		$this->translator_locale =& $this->locales[$locale->getI18Nlocale()];
		return (boolean) TRUE;
	} // end function

	/**
	* checks if a translationstring already exists in the translation table
	* @param string $translationstring
	* @param string $namespace
	* @return viod
	*/
	protected function checkForDuplicates($translationstring = '', $namespace = '') {
		if (parent::getI18Nsetting('show_errormessages') && isset($this->translation_table[$translationstring])) {
			trigger_error('TRANSLATION ERROR: Translationstring "' . $translationstring . '" in namespace "' . $namespace . '" already in use. Possible other Locations: "' . implode(', ', $this->getNamespaces()) . '". Existing translation will be overwritten.', E_USER_WARNING);
		} // end if
	} // end function

	/**
	* checks if one of the given arguments ins empty
	* @param string $string
	* @param string $translation
	* @param string $namespace
	* @return boolean
	*/
	protected function validTranslationInput($string = '', $translation = '', $namespace = 'lang_main') {
        if (mb_strlen(trim($string)) < 1 ||
        	mb_strlen(trim($translation)) < 1 ||
        	mb_strlen(trim($namespace)) < 1) {
        		return (boolean) FALSE;
		} // end if
        return (boolean) TRUE;
	} // end function
} // end TranslatorBase
?>
