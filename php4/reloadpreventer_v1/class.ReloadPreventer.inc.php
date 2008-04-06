<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
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
* @package ReloadPreventer
*/
/**
* Preventing data being parsed twice
*
* You have to be careful to call the setToken method AFTER you
* have valitated all of your important code fragments, otherwise
* the session token and the form token will never match up.
*
* Tested with Apache 1.3.24 and PHP 4.2.3
* Last change: 2002-12-25
*
* @desc Preventing data being parsed twice
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package ReloadPreventer
* @version 1.000
*/
class ReloadPreventer {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**#@+
	* @access private
	* @var string
	*/
	/**
	* No Information available
	*
	* @desc No Information available
	*/
	var $token;

	/**
	* No Information available
	*
	* @desc No Information available
	*/
	var $sessionName = 'token_session';

	/**
	* No Information available
	*
	* @desc No Information available
	*/
	var $formName = 'token';
	/**#@-*/

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**#@+
	* @access private
	* @var string
	* @since 1.000 - 2002-10-10
	*/
	/**
	* Constructor
	*
	* No information available
	*
	* @desc Constructor
	*/
	function ReloadPreventer() {

	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Sets token class variable
	*
	* @desc Sets token class variable
	* @param (string) $value
	*/
	function setToken($value) {
		$this->token = (string) $value;
	} // end function

	/**
	* Sets the name for the session variable
	*
	* @desc Sets the name for the session variable
	* @param (string) $string
	*/
	function setSessionName($string) {
		$this->sessionName = (string) $string;
	} // end function

	/**
	* Sets the name for the form name (POST)
	*
	* @desc Sets the name for the form name (POST)
	* @param (string) $string
	*/
	function setFormName($string) {
		$this->formName = (string) $string;
	} // end function

	/**
	* Creates a new token
	*
	* Creates a token based on md5 value of the current time in msec,
	* creates a session with that token.
	*
	* @desc Creates a new token
	* @access public
	* @see setTokenSession()
	*/
	function setTokenSession() {
		session_register($this->sessionName);
		$this->setToken(md5(microtime()));
		$_SESSION[$this->sessionName] = (string) $this->token;
	} // end function
	/**#@-*/

	/**#@+
	* @access public
	* @since 1.000 - 2002-10-10
	*/
	/**
	* Returns token
	*
	* @desc Returns token
	* @return (string) $token
	*/
	function getToken() {
		return (string) $this->token;
	} // end function

	/**
	* Returns session name
	*
	* @desc Returns session name
	* @return (string) $sessionName
	*/
	function getSessionName() {
		return (string) $this->sessionName;
	} // end function

	/**
	* Returns form name
	*
	* @desc Returns form name
	* @return (string) $formName
	*/
	function getFormName() {
		return (string) $this->formName;
	} // end function

	/**
	* Checks if token is valid
	*
	* Compares the session with the transmitted token from the form.
	* If they don’t match, a boolean “false” is returned.
	*
	* @desc Checks if token is valid
	* @return (boolean)
	* @see setTokenSession()
	*/
	function isValid() {
		if (!isset($_POST[$this->formName]) || !isset($_SESSION[$this->sessionName])) {
			return (boolean) FALSE;
		} else {
			return (boolean) (($_POST[$this->formName] != $_SESSION[$this->sessionName]) ? FALSE : TRUE);
		} // end if
	} // end function
	/**#@-*/
} // end class ReloadPreventer
?>
