<?php
/**
* required interface for all translatorXXXExtended classes
*/
require_once 'interface.I18NtranslatorInterfaceExtended.inc.php';
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* extended translator class with flat utf8-textfiles as a backend
* @author Michael Wimmer <flaimo@gmail.com>
* @category flaimo-php
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license GNU General Public License v3
* @link http://code.google.com/p/flaimo-php/
* @package i18n
* @version 2.3.1
*/
class I18NtranslatorTextExtended extends I18NtranslatorText implements I18NtranslatorInterfaceExtended {

	/**
	* @param string $namespaces
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorText::__construct()
	* @return void
	*/
	public function __construct($namespaces = '', I18Nlocale &$locale = NULL) {
		parent::__construct($namespaces, $locale);
	} // end constructor

	/**
	* returns the timestamp when a given namespace was last updated
	* @param string $namespace
	* @return mixed int/boolean
	* @uses I18Nbase::isFilledString()
	* @uses I18NtranslatorBase::getLastUpdateSession()
	* @uses I18NtranslatorText::getNamespaceFilepath()
	* @uses I18NtranslatorBase::setLastUpdateSession()
	*/
	public function getLastUpdateDateNamespace($namespace = '') { // basically an overloaded method...
		if (parent::isFilledString($namespace) == FALSE) {
			return (boolean) FALSE;
		} elseif (($tmp_namespace = parent::getLastUpdateSession($namespace)) != FALSE) {
			return (int) $tmp_namespace;
		} // end if

		if (($lastchange = @filemtime(parent::getNamespaceFilepath($namespace))) != FALSE) {
        	parent::setLastUpdateSession($namespace, $lastchange);
        	return (int) $lastchange;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**#@+
	* @return boolean
	* @param string $string the stranslation string
	* @param string $namespace
	*/
	/**
	* deletes selected values from  the translation array
	* @uses I18NtranslatorText::$translation_table
	* @uses I18NtranslatorBase::$namespaces
	*/
	protected function stripTranslationTable($string = '', $namespace = 'lang_main') {
 		if (isset($this->translation_table[trim($string)]) && in_array(trim($namespace), $this->namespaces)) {
 			unset($this->translation_table[trim($string)]);
 			return (boolean) TRUE;
 		} // end if
 		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation from the given namespace for a given locale
	* @param object $locale I18Nlocale
	* @uses I18NtranslatorText::$delimiter
	* @uses I18NtranslatorText::$ext
	* @uses I18NtranslatorText::fetchTranslationFile()
	* @uses I18Nbase::getI18Nsetting()
	* @uses I18Nlocale::getI18Nlocale()
	* @uses I18NtranslatorText::stripTranslationTable()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	*/
	protected function deleteLocaleString($string = '', $namespace = 'lang_main', I18Nlocale &$locale) {
		$found = (boolean) FALSE;
		$string_i = trim($string) . $this->delimiter;
		$file = parent::fetchTranslationFile($namespace);

		if ($file == FALSE) {
			return (boolean) FALSE;
		} // end if

		foreach ($file as $line) {
			$startstring = mb_substr($line, 0, mb_strlen($string_i));
			if ($startstring === $string_i) {
				$found = (boolean) TRUE;
				continue;
			} elseif (mb_strlen(trim($line)) === 0) {
				continue;
			} // end if
			$returnfile[] = $line;
		} // end foreach

		if ($found === FALSE) {
			unset($returnfile);
			return (boolean) FALSE;
		} // end if

		$filepath = parent::getI18Nsetting('locales_path') . '/' . $locale->getI18Nlocale() . '/' . $namespace . '.' . $this->ext;
		$handle = @fopen($filepath, 'w');
		if (!$handle) {
			return (boolean) FALSE;
		} // end if
		@fputs($handle, implode("\n",$returnfile));
		@fclose($handle);
		if ($locale->getI18Nlocale() == $this->getTranslatorLocale()->getI18Nlocale()) {
			$this->stripTranslationTable($string, $namespace);
		} // end if
		return (boolean) TRUE;
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for the current locale
	* @uses I18NtranslatorTextExtended::deleteLocaleString()
	* @uses I18NtranslatorBase::getTranslatorLocale()
	*/
	public function deleteTranslation($string = '', $namespace = 'lang_main') {
		if ($this->deleteLocaleString($string, $namespace, $this->getTranslatorLocale()) === TRUE) {
			return (boolean) TRUE;
		} // end if
		return (boolean) FALSE;
	} // end function

	/**
	* deletes a translation string + translation from the given namespace for ALL locales
	* @uses I18NtranslatorText::setLocales()
	* @uses I18NtranslatorTextExtended::deleteLocaleString()
	*/
	public function deleteTranslationString($string = '', $namespace = 'lang_main') {
		if (!isset($this->locales)) {
			$this->setLocales();
		} // end if
		$error = (boolean) FALSE;
		foreach ($this->locales as $locale) {
			if ($this->deleteLocaleString($string, $namespace, $locale) === FALSE) {
				$error = (boolean) TRUE;
			} // end if
		} // end foreach
		return (boolean) (($error === TRUE) ? FALSE : TRUE);
	} // end function

	/**
	* adds a translationstring + translation to the given namespace of the current locale
	* @param string $translation the stranslation itself
	* @uses I18Nbase::validTranslationInput()
	* @uses I18NtranslatorText::fetchTranslationFile()
	* @uses I18NtranslatorText::getNamespaceFilepath()
	* @uses I18NtranslatorText::$translation_table
	*/
	public function addTranslation($string = '', $translation = '', $namespace = 'lang_main') {
		if (parent::validTranslationInput($string, $translation, $namespace) === FALSE) {
			return (boolean) FALSE;
		} // end if

		$string_i = trim($string) . $this->delimiter;
		$file = parent::fetchTranslationFile($namespace);
		foreach ($file as $line) {
			$startstring = mb_substr($line, 0, mb_strlen($string_i));
			if ($startstring === $string_i) {
				return (boolean) FALSE;
			} // end if
		} // end foreach
		$handle = @fopen(parent::getNamespaceFilepath($namespace), 'a');
		if (!$handle) {
			return (boolean) FALSE;
		} // end if
		@fputs($handle, "\n" . $string_i . trim($translation));
		@fclose($handle);
		$this->translation_table[trim($string)] = trim($translation);
		return (boolean) TRUE;
	} // end function

	/**
	* updates a translationstring or translation or namespace
	* @param string $translation the stranslation itself
	* @param int $position 2 = change namespace; 1 = change string; 0 = change translation
	* @uses I18Nbase::validTranslationInput()
	* @uses I18NtranslatorText::$translation_table
	* @uses I18NtranslatorText::fetchTranslations()
	* @uses I18NtranslatorText::getNamespaceFilepath()
	* @uses I18NtranslatorText::$delimiter
	* @uses I18NtranslatorTextExtended::deleteTranslationString()
	* @uses I18NtranslatorTextExtended::addTranslation()
	*/
	public function updateTranslation($string = '', $translation = '', $namespace = 'lang_main', $position = 1) {
		if (parent::validTranslationInput($string, $translation, $namespace) === FALSE) {
			return (boolean) FALSE;
		} // end if

		if ($position < 2) { // change translation or string
			if (isset($this->translation_table)) {
				unset($this->translation_table);
			} // end if
			parent::fetchTranslations($namespace);
			$content = $this->translation_table;

			if (($position == 1 && !isset($content[$string])) ||
				($position == 0 && !in_array($translation, $content))) {
				return (boolean) FALSE;
			} // end if

			if ($position == 1) {
				$content[trim($string)] = trim($translation);
			} elseif ($position == 0) {
				$tmp = array_flip($content);
				$tmp[trim($translation)] = trim($string);
				$content = array_flip($tmp);
			} // end if

			$handle = @fopen(parent::getNamespaceFilepath($namespace), 'w');
			if (!$handle) {
				return (boolean) FALSE;
			} // end if

			foreach ($content as $string => $translation) {
				$returnfile[] = $string . $this->delimiter . $translation;
			} // end if

			@fputs($handle, implode("\n",$returnfile));
			@fclose($handle);
			return (boolean) TRUE;
		} // end if

		foreach ($this->namespaces as $current_namespace) {
			$this->deleteTranslationString($string, $current_namespace);
		} // end foreach
		if ($this->addTranslation($string, $translation, $namespace) == FALSE) {
			return (boolean) FALSE;
		} // end if
		return (boolean) TRUE;
	} // end function
	/**#@-*/
} // end TranslatorText
?>