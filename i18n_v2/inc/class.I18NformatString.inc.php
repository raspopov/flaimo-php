<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.I18Nbase.inc.php';

/**
* formats strings based on the current locale
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @example  ../www_root/i18n_example_script.php  i18n example script
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package I18N
* @version 2.2
*/
class I18NformatString extends I18Nbase {

	/**
	* @var object holds a I18Ntranslator object
	*/
	protected $translator;

	/**
	* @var string namespaces needed for translating strings in this class
	*/
	protected $namespace = '';

	/**
	* @var array holds an array with bad words
	*/
	protected $bad_words;

	/**
	* @var array holds an array with special words to highlight
	*/
	protected $special_words;

	/**
	* @param object $locale I18Nlocale
	* @return void
	* @uses I18Nbase::getI18NfactoryLocale()
	* @uses I18Ntranslator
	* @uses I18NformatString::$translator
	*/
	public function __construct(&$locale = NULL) {
		if (!($locale instanceOf I18Nlocale)) {
			$locale =& parent::getI18NfactoryLocale();
		} // end if

		$this->translator = new I18Ntranslator($this->namespace, $locale);
	} // end constructor

	/**
	* fetches the bad word string from the l10n file
	* @return boolean
	* @uses I18NformatString::$translator
	* @uses I18Nbase::getI18Nsetting()
	*/
	protected function fetchBadWords() {
		$tmp = explode(',',$this->translator->getTranslatorLocale()->getL10Nsetting('badwords'));
		$tmp = array_map('trim', $tmp);
		foreach ($tmp as $word) {
			$this->bad_words[$word] = str_repeat(parent::getI18Nsetting('replace_char'), mb_strlen($word));
		} // end foreach
		unset($tmp);
		return (boolean) TRUE;
	}  // end function

	/**
	* replaces all bad words in a string with replace-chars
	* @param string $text
	* @param boolean $exact whether only hole words should be replaced or parts of a string
	* @return string
	* @uses I18NformatString::$bad_words
	* @uses I18NformatString::fetchBadWords()
	*/
	public function wordFilter($text = '', $exact = TRUE) {
		if (!isset($this->bad_words)) {
			$this->fetchBadWords();
		} // end if

		if ($exact === TRUE) {
			return strtr($text, $this->bad_words);
		} // end if

        reset($this->bad_words);
        while(list($word, $replace) = each($this->bad_words)) {
            $text = mb_ereg_replace('/(^|\b)' . $word . '(\b|!|\?|\.|,|$)/i', $replace, $text);
        } // end while
		return $text;
	} // end function

	/**
	* fetches the special words from the ini file
	* @return boolean
	* @uses I18NformatString::$special_words
	* @uses I18NformatString::$translator
	* @uses I18Nbase::getI18Nsetting()
	*/
	protected function fetchSpecialWords() {
		$path = parent::getI18Nsetting('locales_path') . '/' . $this->translator->getTranslatorLocale()->getI18Nlocale() . '/words.ini' ;
		$this->special_words = parse_ini_file($path, TRUE);
		return (boolean) TRUE;
	} // end function

	/**
	* "highlights" special words in a string with abbr/dfn/acronym tags
	* @param string $text
	* @param string $what abbr/dfn/acronym (if empty all 3 will be highlighted)
	* @return string
	* @uses I18NformatString::$special_words
	* @uses I18NformatString::fetchSpecialWords()
	* @uses I18Nbase::isFilledString()
	* @uses I18Nbase::getI18Nuser()
	*/
	public function highlightSpecialWords($string = '', $what = '') {
		if (parent::isFilledString($string) == FALSE ||
			parent::getI18Nuser()->getHighlightSpecialWords() === 0) {
			return $string;
		} // end if

		if (!isset($this->special_words)) {
			$this->fetchSpecialWords();
		} // end if

		$loop = (array_key_exists($what, $this->special_words)) ? array($what) : array_keys($this->special_words);
		foreach ($loop as $what) {
			foreach ($this->special_words[$what] as $list_name => $list_value) {
				$valuearray = explode(' | ', $list_value);
				$list_name = quotemeta($list_name);
				//$valuearray[0] = htmlentities($valuearray[0]);
				$string = mb_ereg_replace($list_name, '<'.$what.' title="' . $valuearray[0] . '" xml:lang="' . $valuearray[1] . '">' . $list_name . '</'.$what.'>', $string);
				//$string = preg_replace('*' . $list_name . '*', '<'.$what.' title="' . $valuearray[0] . '" xml:lang="' . $valuearray[1] . '">' . $list_name . '</'.$what.'>', $string , 1);
			} // end foreach
		} // end foreach
		return $string;
	} // end function
} // end class I18NformatString
?>