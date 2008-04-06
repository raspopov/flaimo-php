<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                  |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2004 Michael Wimmer                               |
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
* Tested with Apache 1.3.29 and PHP 5.0.0RC1
* Last change: 2004-03-25
*
* @desc Preventing data being parsed twice
* @access public
* @author Michael Wimmer <flaimo 'at' gmx 'dot' net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package ReloadPreventer
* @example sample_rp.php Sample script
* @version 2.002
*/
class ReloadPreventer {

	/**
	* token value
	*
	* @var string
	*/
	protected $token;

	/**
	* if token was set before in this object
	*
	* @var boolean
	*/
	protected $new_token_set = FALSE;

	/**
	* session name under which the token value is saved
	*
	* @const string
	*/
	const SESSIONNAME = 'token_session';

	/**
	* form name under which the token value is added to a form
	*
	* @const string
	*/
	const FORMNAME = 'token';

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Sets token class variable
	*
	* @param string $value
	*/
	protected function setToken($value) {
		$this->token = (string) $value;
	} // end function

	/**
	* Creates a new token
	*
	* Creates a token based on md5 value of the current time in msec,
	* creates a session with that token.
	* @uses setToken()
	* @uses ReloadPreventer::$token
	* @uses ReloadPreventer::$new_token_set
	*/
	public function setTokenSession() {
		if ($this->new_token_set === FALSE) {
			session_register(self::SESSIONNAME);
			$this->setToken(md5(microtime()));
			$_SESSION[self::SESSIONNAME] = (string) $this->token;
		} // end if
		$this->new_token_set = (boolean) TRUE;
	} // end function

	/**
	* Returns token
	*
	* @return string $token
	* @uses setTokenSession()
	* @uses ReloadPreventer::$token
	*/
	public function &getToken() {
		$this->setTokenSession();
		return $this->token;
	} // end function

	/**
	* Returns form name
	*
	* @return string FORM_NAME
	*/
	public function &getFormName() {
		return self::FORMNAME;
	} // end function

	/**
	* Returns a form element
	*
	* @return string form element
	* @uses setTokenSession()
	* @uses ReloadPreventer::$token
	* @uses $form_name
	*/
	public function getInputElement() {
		$this->setTokenSession();
		return (string) '<input name="' . self::FORMNAME . '" type="hidden" value="' . $this->token . '" />';
	} // end function

	/**
	* Checks if token is valid
	*
	* Compares the session with the transmitted token from the form.
	* If they don’t match, a boolean “false” is returned.
	*
	* @return boolean
	*/
	public function isValid() {
		if (!isset($_POST[self::FORMNAME]) || !isset($_SESSION[self::SESSIONNAME])) {
			return (boolean) FALSE;
		} else {
			return (boolean) (($_POST[self::FORMNAME] != $_SESSION[self::SESSIONNAME]) ? FALSE : TRUE);
		} // end if
	} // end function
} // end class ReloadPreventer
?>
