<?php
/**
* @package Polls
*/
/**
* Including the User class
*/
require_once('class.User.inc.php');

/**
* Holds methods for manipulating user related informations
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-04-21
*
* @desc Holds methods for manipulating user related informations
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Polls
* @version 1.001
*/
class PollUser extends User {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* Prefix for all session/cookies when marking a poll voted
	*
	* @desc Prefix for all session/cookies when marking a poll voted
	* @var string
	* @access private
	*/
	var $poll_varname;

	/**
	* Prefix for all session/cookies when marking a poll written (with a comment)
	*
	* @desc Prefix for all session/cookies when marking a poll written (with a comment)
	* @var string
	* @access private
	*/
	var $commentwritten_varname;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @return (void)
	* @access private
	* @uses User::User()
	* @since 1.001 - 2003/04/21
	*/
	function PollUser() {
		parent::User();
		$this->commentwritten_varname = (string) 'commentwritten_';
		$this->poll_varname = (string) 'abgestimmt_';
	} // end function

	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Sets a session/cookie to mark a poll written (with a comment)
	*
	* @desc Sets a session/cookie to mark a poll written (with a comment)
	* @param (int) $id The ID of the poll
	* @return (void)
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function setCommentWritten($id) {
		$_COOKIE[$this->commentwritten_varname . $id] = time() + 360;
		setcookie($this->commentwritten_varname . $id, 1, time() + 360);
		$_SESSION[$this->commentwritten_varname . $id] = $GLOBALS[$this->commentwritten_varname . $id] = (int) time() +360;
		session_register($this->commentwritten_varname . $id);
	} //  end function

	/**
	* Checks if the user has written a comment for that poll or not
	*
	* @desc Checks if the user has written a comment for that poll or not
	* @param (int) $id The ID of the poll
	* @return (boolean) $cookieset
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function isCommentWritten($id) {
		$cookieset = (boolean) FALSE;
		if ((isset($_COOKIE[$this->commentwritten_varname . $id])) || (isset($_SESSION[$this->commentwritten_varname . $id]) && $_SESSION[$this->commentwritten_varname . $id] >= time())) {
			$cookieset = (boolean) TRUE;
		} elseif (isset($_SESSION[$this->commentwritten_varname . $id]) && $_SESSION[$this->commentwritten_varname . $id] < time()) {
			session_unregister($this->commentwritten_varname . $id);
		} // end if
		return (boolean) $cookieset;
	} //  end function

	/**
	* Sets a session/cookie to mark a poll voted
	*
	* @desc Sets a session/cookie to mark a poll voted
	* @param (int) $id The ID of the poll
	* @param (array) $values Array with the coosen value(s)
	* @return (void)
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function setPollVoted($id, $values) {
		$remember_choise = ((isset($values) && is_array($values)) ? implode(',', $values) : $values);
		$_COOKIE[$this->poll_varname . $id] = $remember_choise;
		setcookie($this->poll_varname . $id, $remember_choise, time()+31536000);
		$_SESSION[$this->poll_varname . $id] = $GLOBALS[$this->poll_varname . $id] = $remember_choise;
		session_register($this->poll_varname . $id);
	} //  end function

	/**
	* Checks if the user has voted for that poll or not
	*
	* @desc Checks if the user has voted for that poll or not
	* @param (int) $id The ID of the poll
	* @return (boolean) $cookieset
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function isPollVoted($id) {
		$cookieset = (boolean) FALSE;
		if ((isset($_COOKIE[$this->poll_varname . $id])) || (isset($_SESSION[$this->poll_varname . $id]))) {
			$cookieset = (boolean) TRUE;
		} // end if
		return (boolean) $cookieset;
	} //  end function

	/**
	* Returns the prefix for poll cookies/sessions
	*
	* @desc Returns the prefix for poll cookies/sessions
	* @return (string) $poll_varname
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollVarname() {
		return (string) $this->poll_varname;
	}
} // end class PollUser
?>
