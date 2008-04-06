<?php
/**
* @package i18n
*/
/**
* For grouping all the user-preferences used in the other classes
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.4/4.3.0)
* Last change: 2003-02-26
*
* @desc For grouping all the user-preferences used in the other classes
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package i18n
* @version 1.051
*/
class User {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $nickname;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $nickname_cookiename = 'cook_name';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $nickname_sessionname = 'sess_name';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $email;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $email_cookiename = 'cook_email';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $email_sessionname = 'sess_email';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $form_name = 'name';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $form_mail = 'email';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $last_visit;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $last_visit_cookiename = 'lastvisit';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $last_visit_sessionname = 'lastvisit';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $specialwordsstatusname = 'specialwordsstatus';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $specialWordsStatus;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var int
	* @access private
	*/
	var $timeset;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var int
	* @access private
	*/
	var $default_timeset = 0;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $TimeSelectName = 'timeset';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $TimeSelectSessionName = 'sess_timeset';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $TimeSelectCookieName = 'cookie_timeset';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LangSelectName = 'lang';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LangSessionName = 'sess_lang';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LangCookieName = 'cookie_lang';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LangContentSelectName = 'lang_content';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LangContentSessionName = 'sess_lang_content';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LangContentCookieName = 'cookie_lang_content';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LocaleSelectName = 'locale';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LocaleSessionName = 'sess_locale';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $LocaleCookieName = 'cookie_locale';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $CountrySelectName = 'country';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $CountrySessionName = 'sess_country';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $CountryCookieName = 'cookie_country';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $PreferedLanguage;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $PreferedLocale;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $PreferedContentLanguage;

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $PreferedCountry;


	/**
	* Preferred Measure System
	*
	* @desc Preferred Measure System
	* @var string
	* @access private
	*/
	var $measure_system;

	/**
	* Name of the <select> element
	*
	* @desc Name of the <select> element
	* @var string
	* @access private
	*/
	var $measureSelectName = 'measure_system';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $measureCookieName = 'cookie_measure';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $measureSessionName = 'session_measure';

	/**
	* No Information available
	*
	* @desc No Information available
	* @var string
	* @access private
	*/
	var $SelectCSSClass = 'ns';

	/**
	* Holds the settings for this class
	*
	* @desc Holds the settings for this class
	* @var array
	* @access private
	*/
	var $user_settings;

	/**
	* Default value whether special words should be shown or not
	*
	* @desc Default value whether special words should be shown or not
	* @var boolean
	* @access private
	*/
	var $default_specialwordsstatus = TRUE;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* Only job is to set all the variablesnames
	*
	* @desc Constructor
	* @return (void)
	* @access private
	* @uses readDefaultUserSettings()
	* @since 1.000 - 2002/10/10
	*/
	function User() {
		$this->readDefaultUserSettings();
	} // end constructor

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return (void)
	* @access private
	* @since 1.045 - 2003/02/21
	*/
	function readDefaultUserSettings() {
        if (!isset($this->user_settings) && file_exists('i18n_settings.ini')) {
			$this->user_settings = (array) parse_ini_file('i18n_settings.ini', TRUE);

			$this->nickname_cookiename 			=& $this->user_settings['User']['nickname_cookiename'];
			$this->nickname_sessionname 		=& $this->user_settings['User']['nickname_sessionname'];

			$this->email_cookiename 			=& $this->user_settings['User']['email_cookiename'];
			$this->email_sessionname 			=& $this->user_settings['User']['email_sessionname'];

			$this->form_mail 					=& $this->user_settings['User']['form_mail'];
			$this->form_name 					=& $this->user_settings['User']['form_name'];

			$this->last_visit_cookiename 		=& $this->user_settings['User']['last_visit_cookiename'];
			$this->last_visit_sessionname 		=& $this->user_settings['User']['last_visit_sessionname'];
			$this->specialwordsstatusname 		=& $this->user_settings['User']['specialwordsstatusname'];
			$this->default_specialwordsstatus 	=& $this->user_settings['User']['default_specialwordsstatus'];

			$this->default_timeset 				=& $this->user_settings['User']['default_timeset'];

			$this->TimeSelectName 				=& $this->user_settings['User']['TimeSelectName'];
			$this->TimeSelectSessionName 		=& $this->user_settings['User']['TimeSelectSessionName'];
			$this->TimeSelectCookieName 		=& $this->user_settings['User']['TimeSelectCookieName'];

			$this->LangSelectName 				=& $this->user_settings['User']['LangSelectName'];
			$this->LangSessionName 				=& $this->user_settings['User']['LangSessionName'];
			$this->LangCookieName 				=& $this->user_settings['User']['LangCookieName'];

			$this->LocaleSelectName 			=& $this->user_settings['User']['LocaleSelectName'];
			$this->LocaleSessionName 			=& $this->user_settings['User']['LocaleSessionName'];
			$this->LocaleCookieName 			=& $this->user_settings['User']['LocaleCookieName'];

			$this->LangContentSelectName 		=& $this->user_settings['User']['LangContentSelectName'];
			$this->LangContentSessionName 		=& $this->user_settings['User']['LangContentSessionName'];
			$this->LangContentCookieName 		=& $this->user_settings['User']['LangContentCookieName'];

			$this->CountrySelectName 			=& $this->user_settings['User']['CountrySelectName'];
			$this->CountrySessionName 			=& $this->user_settings['User']['CountrySessionName'];
			$this->CountryCookieName 			=& $this->user_settings['User']['CountryCookieName'];

			$this->timezoneSelectName 			=& $this->user_settings['User']['timezoneSelectName'];
			$this->timezoneCookieName 			=& $this->user_settings['User']['timezoneCookieName'];
			$this->timezoneSessionName 			=& $this->user_settings['User']['timezoneSessionName'];

			$this->measureSelectName 			=& $this->user_settings['User']['measureSelectName'];
			$this->measureCookieName 			=& $this->user_settings['User']['measureCookieName'];
			$this->measureSessionName 			=& $this->user_settings['User']['measureSessionName'];

			$this->SelectCSSClass				=& $this->user_settings['User']['SelectCSSClass'];
    	} // end if
	} // end function

	/**
	* Sets the users nickname
	*
	* Sets the users nickname by looking if a cookie or session was
	* set with the name. If no string was found, returns an empty string
	*
	* @desc Sets the users nickname
	* @return (void)
	* @access private
	* @see loadMail()
	* @since 1.000 - 2002/10/10
	*/
	function loadNickname() {
		if (!isset($this->nickname)) {
			if (isset($_COOKIE[$this->nickname_cookiename])) {
				// Überprüfung für das Vorausfüllen des Formulares
				$this->nickname =& $_COOKIE[$this->nickname_cookiename];
			} elseif(isset($_SESSION[$this->nickname_sessionname])) {
				$this->nickname =& $_SESSION[$this->nickname_sessionname];
			} else {
				$this->nickname = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Sets the users mail address
	*
	* Sets the users mail address by looking if a cookie or session was
	* set with the mail. If no string was found, returns an empty string
	*
	* @desc Sets the users mail address
	* @return (void)
	* @access private
	* @see loadNickname()
	* @since 1.000 - 2002/10/10
	*/
	function loadMail() {
		if (!isset($this->email)) {
			if (isset($_COOKIE[$this->email_cookiename])) {
				// Überprüfung für das Vorausfüllen des Formulares
				$this->email =& $_COOKIE[$this->email_cookiename];
			} elseif(isset($_SESSION[$this->email_sessionname])) {
				$this->email =& $_SESSION[$this->email_sessionname];
			} else {
				$this->email = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Saves the users nickname
	*
	* Saves the users nickname by setting a cookie and a session
	*
	* @desc Saves the users nickname
	* @param (string) $string  Input is a string with the nickname
	* @return (void)
	* @access private
	* @see setMail()
	* @since 1.000 - 2002/10/10
	*/
	function setNickname($string) {
		$this->nickname = (string) $string;
		$_SESSION[$this->nickname_sessionname] = (string) $string;
		session_register($this->nickname_sessionname);
		setcookie($this->nickname_cookiename, $string, time()+31536000);
	} // end function

	/**
	* Saves the users mail address
	*
	* Saves the users mail address by setting a cookie and a session
	*
	* @desc Saves the users mail address
	* @param (string) $string  Input is a string with the mail address
	* @return (void)
	* @access private
	* @see setNickname()
	* @since 1.000 - 2002/10/10
	*/
	function setMail($string) {
		$this->email = (string) $string;
		$_SESSION[$this->email_sessionname] = (string) $string;
		session_register($this->email_sessionname);
		setcookie($this->email_cookiename, $string, time()+31536000);
	} // end function

	/**
	* Sets and saves the users last visit to a webpage (ISO date - time)
	*
	* @desc Sets and saves the users last visit to a webpage (ISO date - time)
	* @return (void)
	* @access private
	* @since 1.000 - 2002/10/10
	*/
	function setLastVisit() {
		$this->last_visit = (string) date('Y-m-d H:i:s');
		setcookie($this->last_visit_cookiename, '', time()-3600);
		setcookie($this->last_visit_cookiename, $this->last_visit, time()+31536000);
		$_SESSION[$this->last_visit_sessionname] = $this->last_visit;
		session_register($this->last_visit_sessionname);
	} // end function

	/**
	* Returns the users nickname
	*
	* @desc Returns the users nickname
	* @return (string) $nickname  String with the nickname in it
	* @access public
	* @see getMail()
	* @since 1.000 - 2002/10/10
	*/
	function &getNickname() {
		$this->loadNickname();
		return (string) $this->nickname;
	} // end function

	/**
	* Returns the users mail address
	*
	* @desc Returns the users mail address
	* @return (string) $email  String with the mail address in it
	* @access public
	* @see getNickname()
	* @since 1.000 - 2002/10/10
	*/
	function &getMail() {
		$this->loadMail();
		return (string) $this->email;
	} // end function

	/**
	* Returns the users last visit to a webpage (ISO date - time)
	*
	* @desc Returns the users last visit to a webpage (ISO date - time)
	* @return (string)  String with the ISO date - time in it
	* @access public
	* @see setLastVisit()
	* @since 1.000 - 2002/10/10
	*/
	function &getLastVisit() { // DO TO
		if (isset($_COOKIE[$this->last_visit_cookiename])) {
			return (string) $_COOKIE[$this->last_visit_cookiename];
		} elseif (isset($_SESSION[$this->last_visit_sessionname])) {
			return (string) $_SESSION[$this->last_visit_sessionname];
		} else {
			return (string) '';
		} // end if
	} // end function

	//------------------------------------------------

	/**
	* Set SpecialWords ON/OFF
	*
	* Defines the status if a string should be parsed though the
	* SpecialWords function - activates or deactivates underlining of special
	* words
	*
	* @desc Set SpecialWords ON/OFF
	* @return (void)
	* @access public
	* @see getSpecialWordsStatus()
	* @since 1.000 - 2002/10/10
	*/
	function setSpecialWordsStatus() {
		if (!isset($this->specialWordsStatus)) {
			if (isset($_POST[$this->specialwordsstatusname])) {
				$this->specialWordsStatus = (boolean) $_POST[$this->specialwordsstatusname];
				setcookie($this->specialwordsstatusname, $this->specialWordsStatus, time()+31536000);
				$_SESSION[$this->specialwordsstatusname] = (boolean) $GLOBALS[$this->specialwordsstatusname] = (boolean) $this->specialWordsStatus;
				session_register($this->specialwordsstatusname);
			} elseif (isset($_COOKIE[$this->specialwordsstatusname])) {
				$this->specialWordsStatus =& $_COOKIE[$this->specialwordsstatusname];
			} elseif (isset($_SESSION[$this->specialwordsstatusname])) {
				$this->specialWordsStatus =& $_SESSION[$this->specialwordsstatusname];
			} else {
				$this->specialWordsStatus =& $this->default_specialwordsstatus;
			} // end if
		} // end if
	} // end function

	/**
	* Show SpecialWords ON/OFF
	*
	* Returns the status if a string should be parsed though the
	* SpecialWords function
	*
	* @desc Show SpecialWords ON/OFF
	* @return (boolean) $specialWordsStatus  Show SpecialWords YES/NO
	* @access public
	* @see setSpecialWordsStatus()
	* @since 1.000 - 2002/10/10
	*/
	function &getSpecialWordsStatus() {
		$this->setSpecialWordsStatus();
		return (boolean) $this->specialWordsStatus;
	} // end function

	//------------------------------------------------

	/**
	* Sets display-format for time values
	*
	* Sets the timeformat (swatch/iso/standard) depending
	* on POST/GET/COOKIE/SESSION input or else uses standard time
	*
	* @desc Sets display-format for time values
	* @return (void)
	* @access private
	* @see getPreferedTime()
	* @since 1.000 - 2002/10/10
	*/
	function setPreferedTime() {
		if (!isset($this->timeset)) {
			if (isset($_POST[$this->TimeSelectName])) {
				$this->timeset = (int) $_POST[$this->TimeSelectName];
				$_SESSION[$this->TimeSelectSessionName] = $GLOBALS[$this->TimeSelectSessionName] = (int) $this->timeset;
				setcookie($this->TimeSelectCookieName, $this->timeset, time()+31536000);
				session_register($this->TimeSelectCookieName);
			} elseif (isset($_COOKIE[$this->TimeSelectCookieName])) {
				$this->timeset =& $_COOKIE[$this->TimeSelectCookieName];
			} elseif (isset($_SESSION[$this->TimeSelectSessionName])) {
				$this->timeset =& $_SESSION[$this->TimeSelectSessionName];
			} else {
				$this->timeset =& $this->default_timeset;
			} // end if
		} // end if
	} // end function

	/**
	* Returns display-format for time values
	*
	* Returns the integer code for the prefered timeformat
	* (swatch, iso, standard)
	*
	* @desc Returns display-format for time values
	* @return (int) $timeset  0|1|2
	* @access public
	* @see setPreferedTime(), FormatDate::getTimeset()
	* @since 1.000 - 2002/10/10
	*/
	function &getPreferedTime() {
		$this->setPreferedTime();
		return (int) $this->timeset;
	} // end function

	/**
	* Returns the name for the <select> html-element for choosing the
	* display-format for time values
	*
	* @desc Returns the name for the <select> html-element
	* @return (string) $TimeSelectName
	* @access public
	* @see FormatDate::getTimeSelectName()
	* @since 1.000 - 2002/10/10
	*/
	function &getTimeSelectName() {
		return (string) $this->TimeSelectName;
	} // end function

	/**
	* Returns the name for session variable for saving the display-format for
	* time values
	*
	* @desc Returns the name for session variable
	* @return (string) $TimeSelectSessionName
	* @access public
	* @see FormatDate::getTimeSelectSessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getTimeSelectSessionName() {
		return (string) $this->TimeSelectSessionName;
	} // end function

	/**
	* Returns the name for cookie variable for saving the display-format for
	* time values
	*
	* @desc Returns the name for cookie variable
	* @return (string) $TimeSelectCookieName
	* @access public
	* @see FormatDate::getTimeSelectCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getTimeSelectCookieName() {
		return (string) $this->TimeSelectCookieName;
	} // end function

	//------------------------------------------------

	/**
	* Looks up post/session/cookies for a transmitted iso-code
    *
    * To make session and cookies work with this class you have
    * to include ob_start(); and session_start(); in your scripts
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @return (void)
	* @access private
	* @see getPreferedLanguage()
	* @since 1.000 - 2002/10/10
	*/
	function setPreferedLanguage() {
		if(!isset($this->PreferedLanguage)) {
			if (isset($_POST[$this->LangSelectName])) {
				$this->PreferedLanguage = (string) $_POST[$this->LangSelectName];
                setcookie($this->LangCookieName, $_POST[$this->LangSelectName], time()+31536000);
                $_COOKIE[$this->LangCookieName] = (string) $_POST[$this->LangSelectName];
				$_SESSION[$this->LangSessionName] = (string) $GLOBALS[$this->LangSessionName] = (string) $_POST[$this->LangSelectName];
				session_register($this->LangSessionName);
			} elseif (isset($_COOKIE[$this->LangCookieName])) {
                $this->PreferedLanguage =& $_COOKIE[$this->LangCookieName];
			} elseif (isset($_SESSION[$this->LangSessionName])) {
                $this->PreferedLanguage =& $_SESSION[$this->LangSessionName];
			} else {
				$this->PreferedLanguage = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return (string) $PreferedLanguage  iso-code
	* @access public
	* @uses setPreferedLanguage()
	* @see Language::ChooseLang()
	* @since 1.000 - 2002/10/10
	*/
	function &getPreferedLanguage() {
		$this->setPreferedLanguage();
		return (string) $this->PreferedLanguage;
	} // end function


	/**
	* Looks up post/session/cookies for a transmitted iso-code
    *
    * To make session and cookies work with this class you have
    * to include ob_start(); and session_start(); in your scripts
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @return (void)
	* @access private
	* @see getPreferedLanguage()
	* @since 1.044 - 2003/02/18
	*/
	function setPreferedLocale() {
		if(!isset($this->PreferedLocale)) {
			if (isset($_POST[$this->LocaleSelectName])) {
				$this->PreferedLocale = (string) $_POST[$this->LocaleSelectName];
                setcookie($this->LocaleCookieName, $_POST[$this->LocaleSelectName], time()+31536000);
                $_COOKIE[$this->LocaleCookieName] = (string) $_POST[$this->LocaleSelectName];
				$_SESSION[$this->LocaleSessionName] = (string) $GLOBALS[$this->LocaleSessionName] = (string) $_POST[$this->LocaleSelectName];
				session_register($this->LocaleSessionName);
			} elseif (isset($_COOKIE[$this->LocaleCookieName])) {
                $this->PreferedLocale =& $_COOKIE[$this->LocaleCookieName];
			} elseif (isset($_SESSION[$this->LocaleSessionName])) {
                $this->PreferedLocale =& $_SESSION[$this->LocaleSessionName];
			} else {
				$this->PreferedLocale = (string) '';
			} // end if
		} // end if
	} // end function


	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return (string) $PreferedLocale  iso-code
	* @access public
	* @uses setPreferedLocale()
	* @see Language::ChooseLocale()
	* @since 1.044 - 2003/02/18
	*/
	function &getPreferedLocale() {
		$this->setPreferedLocale();
		return (string) $this->PreferedLocale;
	} // end function


	/**
	* Looks up post/session/cookies for a transmitted iso-code
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @return (void)
	* @access private
	* @see getPreferedContentLanguage()
	* @since 1.000 - 2002/10/10
	*/
	function setPreferedContentLanguage() {
		if (!isset($this->PreferedContentLanguage)) {
			if (isset($_POST[$this->LangContentSelectName])) {
				$this->PreferedContentLanguage = (string) $_POST[$this->LangContentSelectName];
				setcookie($this->LangContentCookieName, $this->PreferedContentLanguage, time()+31536000);
				$_SESSION[$this->LangContentSessionName] = (string) $GLOBALS[$this->LangContentSessionName] = (string) $this->PreferedContentLanguage;
				session_register($this->LangContentSessionName);
			} elseif (isset($_COOKIE[$this->LangContentCookieName])) {
				$this->PreferedContentLanguage =& $_COOKIE[$this->LangContentCookieName];
			} elseif (isset($_SESSION[$this->LangContentSessionName])) {
				$this->PreferedContentLanguage =& $_SESSION[$this->LangContentSessionName];
			} else {
				$this->PreferedContentLanguage = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return (string) $PreferedContentLanguage  iso-code
	* @access public
	* @uses setPreferedContentLanguage()
	* @see Language::ChooseLangContent()
	* @since 1.000 - 2002/10/10
	*/
	function &getPreferedContentLanguage() {
		$this->setPreferedContentLanguage();
		return (string) $this->PreferedContentLanguage;
	} // end function

	/**
	* Looks up post/session/cookies for a transmitted iso-code
	*
	* @desc Looks up post/session/cookies for a transmitted iso-code
	* @return (void)
	* @access private
	* @see getPreferedCountry()
	* @since 1.000 - 2002/10/10
	*/
	function setPreferedCountry() {
		if (!isset($this->PreferedCountry)) {
			if (isset($_POST[$this->CountrySelectName])) {
				$this->PreferedCountry = (string) $_POST[$this->CountrySelectName];
				setcookie($this->CountryCookieName, $this->PreferedCountry, time()+31536000);
				$_SESSION[$this->CountrySessionName] = (string) $GLOBALS[$this->CountrySessionName] = (string) $this->PreferedCountry;
				session_register($this->CountrySessionName);
			} elseif (isset($_COOKIE[$this->CountryCookieName])) {
				$this->PreferedCountry =& $_COOKIE[$this->CountryCookieName];
			} elseif (isset($_SESSION[$this->CountrySessionName])) {
				$this->PreferedCountry =& $_SESSION[$this->CountrySessionName];
			} else {
				$this->PreferedCountry = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Returns the iso-code
	*
	* @desc Returns the iso-code
	* @return (string) $PreferedCountry  iso-code
	* @access public
	* @uses setPreferedCountry()
	* @see Language::ChooseCountry()
	* @since 1.000 - 2002/10/10
	*/
	function &getPreferedCountry() {
		$this->setPreferedCountry();
		return (string) $this->PreferedCountry;
	} // end function


	/**
	* Looks up post/session/cookies for a transmitted timezone
	*
	* @desc Looks up post/session/cookies for a transmitted timezone
	* @return (void)
	* @access private
	* @see getPreferedTimezone()
	* @since 1.001 - 2002/11/09
	*/
	function setPreferedTimezone() {
		if(!isset($this->timezone)) {
			if (isset($_POST[$this->timezoneSelectName])) {
				$this->timezone = (string) $_POST[$this->timezoneSelectName];
				setcookie($this->timezoneCookieName, $this->timezone, time()+31536000);
				$_SESSION[$this->timezoneSessionName] = (string) $GLOBALS[$this->timezoneSessionName] = (string) $this->timezone;
				session_register($this->timezoneSessionName);
			} elseif (isset($_COOKIE[$this->timezoneCookieName])) {
				$this->timezone =& $_COOKIE[$this->timezoneCookieName];
			} elseif (isset($_SESSION[$this->timezoneSessionName])) {
				$this->timezone =& $_SESSION[$this->timezoneSessionName];
			} else {
				$this->timezone = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Returns the user timezone
	*
	* @desc Returns the user timezone
	* @return (string) $timezone
	* @access public
	* @uses setPreferedTimezone()
	* @since 1.001 - 2002/11/09
	*/
	function &getPreferedTimezone() {
		$this->setPreferedTimezone();
		return (string) $this->timezone;
	} // end function

	/**
	* Sets display-format for measure values
	*
	* Sets the format (metric, us) depending
	* on POST/GET/COOKIE/SESSION input
	*
	* @desc Sets display-format for measure values
	* @return (void)
	* @access private
	* @see Measure, getPreferedMeasureSystem()
	* @since 1.001 - 2002/11/13
	*/
	function setPreferedMeasureSystem() {
		if (!isset($this->measure_system)) {
			if (isset($_POST[$this->measureSelectName])) {
				$this->measure_system = (string) $_POST[$this->measureSelectName];
				$_SESSION[$this->measureSessionName] = $GLOBALS[$this->measureSessionName] = (string) $this->measure_system;
				setcookie($this->measureCookieName, $this->measure_system, time()+31536000);
				session_register($this->measureCookieName);
			} elseif (isset($_COOKIE[$this->measureCookieName])) {
				$this->measure_system =& $_COOKIE[$this->measureCookieName];
			} elseif (isset($_SESSION[$this->measureSessionName])) {
				$this->measure_system =& $_SESSION[$this->measureSessionName];
			} else {
				$this->measure_system = (string) '';
			} // end if
		} // end if
	} // end function

	/**
	* Returns display-format for measure values
	*
	* Returns the string code for the prefered measure system (metric, us)
	*
	* @desc Returns display-format for measure values
	* @return (string) $measure_system  si|uscs
	* @access public
	* @see Measure, Measure::setOutput()
	* @uses setPreferedMeasureSystem()
	* @since 1.001 - 2002/11/13
	*/
	function &getPreferedMeasureSystem() {
		$this->setPreferedMeasureSystem();
		return (string) $this->measure_system;
	} // end function

	/**
	* Returns the name for the language <select> tag
	*
	* @desc Returns the name for the language <select> tag
	* @return (string) $LangSelectName
	* @access public
	* @see getLangSessionName(), getLangCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangSelectName() {
		return (string) $this->LangSelectName;
	} // end function

	/**
	* Returns the name for the language session variable
	*
	* @desc Returns the name for the language session variable
	* @return (string) $LangSessionName
	* @access public
	* @see getLangSelectName(), getLangCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangSessionName() {
		return (string) $this->LangSessionName;
	} // end function

	/**
	* Returns the name for the language cookie variable
	*
	* @desc Returns the name for the language cookie variable
	* @return (string) $LangCookieName
	* @access public
	* @see getLangSelectName(), getLangSessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangCookieName() {
		return (string) $this->LangCookieName;
	} // end function

	/**
	* Returns the name for the language <select> tag
	*
	* @desc Returns the name for the language <select> tag
	* @return (string) $LocaleSelectName
	* @access public
	* @see getLocaleSessionName(), getLocaleCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLocaleSelectName() {
		return (string) $this->LocaleSelectName;
	} // end function

	/**
	* Returns the name for the language session variable
	*
	* @desc Returns the name for the language session variable
	* @return (string) $LocaleSessionName
	* @access public
	* @see getLocaleSelectName(), getLocaleCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLocaleSessionName() {
		return (string) $this->LocaleSessionName;
	} // end function

	/**
	* Returns the name for the language cookie variable
	*
	* @desc Returns the name for the language cookie variable
	* @return (string) $LocaleCookieName
	* @access public
	* @see getLocaleSelectName(), getLocaleSessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLocaleCookieName() {
		return (string) $this->LocaleCookieName;
	} // end function

	/**
	* Returns the name for the content-language <select> tag
	*
	* @desc Returns the name for the content-language <select> tag
	* @return (string) $LangContentSelectName
	* @access public
	* @see getLangContentSessionName(), getLangContentCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContentSelectName() {
		return (string) $this->LangContentSelectName;
	} // end function

	/**
	* Returns the name for the content-language session variable
	*
	* @desc Returns the name for the content-language session variable
	* @return (string) $LangContentSessionName
	* @access public
	* @see getLangContentSelectName(), getLangContentCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContentSessionName() {
		return (string) $this->LangContentSessionName;
	} // end function

	/**
	* Returns the name for the content-language cookie variable
	*
	* @desc Returns the name for the content-language cookie variable
	* @return (string) $LangContentCookieName
	* @access public
	* @see getLangContentSelectName(), getLangContentSessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getLangContentCookieName() {
		return (string) $this->LangContentCookieName;
	} // end function

	/**
	* Returns the name for the country <select> tag
	*
	* @desc Returns the name for the country <select> tag
	* @return (string) $CountrySelectName
	* @access public
	* @see getCountrySessionName(), getCountryCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountrySelectName() {
		return (string) $this->CountrySelectName;
	} // end function

	/**
	* Returns the name for the country session variable
	*
	* @desc Returns the name for the country session variable
	* @return (string) $CountrySessionName
	* @access public
	* @see getCountrySelectName(), getCountryCookieName()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountrySessionName() {
		return (string) $this->CountrySessionName;
	} // end function

	/**
	* Returns the name for the country cookie variable
	*
	* @desc Returns the name for the country cookie variable
	* @return (string) $CountryCookieName
	* @access public
	* @see getCountrySelectName(), getCountrySessionName()
	* @since 1.000 - 2002/10/10
	*/
	function &getCountryCookieName() {
		return (string) $this->CountryCookieName;
	} // end function

	/**
	* Returns the name for the measure <select> tag
	*
	* @desc Returns the name for the measure <select> tag
	* @return (string) $MeasureSelectName
	* @access public
	* @see getMeasureSessionName(), getMeasureCookieName()
	* @since 1.043 - 2003/02/13
	*/
	function &getMeasureSelectName() {
		return (string) $this->measureSelectName;
	} // end function

	/**
	* Returns the name for the measure session variable
	*
	* @desc Returns the name for the measure session variable
	* @return (string) $MeasureSessionName
	* @access public
	* @see getMeasureSelectName(), getMeasureCookieName()
	* @since 1.043 - 2003/02/13
	*/
	function &getMeasureSessionName() {
		return (string) $this->measureSessionName;
	} // end function

	/**
	* Returns the name for the measure cookie variable
	*
	* @desc Returns the name for the measure cookie variable
	* @return (string) $MeasureCookieName
	* @access public
	* @see getMeasureSelectName(), getMeasureSessionName()
	* @since 1.043 - 2003/02/13
	*/
	function &getMeasureCookieName() {
		return (string) $this->measureCookieName;
	} // end function

	/**
	* Returns the name for the css-class attribute for form elements
	*
	* @desc Returns the name for the css-class attribute for form elements
	* @return (string) $SelectCSSClass
	* @access public
	* @see setSelectCSSClass()
	* @since 1.043 - 2003/02/13
	*/
	function &getSelectCSSClass() {
		return (string) $this->SelectCSSClass;
	} // end function
} // end class User
?>
