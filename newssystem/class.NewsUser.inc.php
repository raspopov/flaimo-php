<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------
*/
@include_once('class.User.inc.php');

class NewsUser extends User {

	/* V A R I A B L E S */

	var $commentwritten_varname; // PHP5: private

	/* C O N S T R U C T O R */

	function NewsUser() {
		if (!class_exists('User')) {
			echo 'Class "' . get_class($this) . '": Class "User" not found!';
			die();
		} // end if
		parent::User();
		$this->commentwritten_varname = (string) 'commentwritten_';
	} // end function


	/* F U N C T I O N S */

	function setCommentWritten($id) {
		setcookie($this->commentwritten_varname . $id, 1, time() + 360);
		$_SESSION[$this->commentwritten_varname . $id] = $GLOBALS[$this->commentwritten_varname . $id] = (int) time() +360;
		session_register($this->commentwritten_varname . $id);
	} //  end function

	function isCommentWritten($id) {
		$cookieset = (boolean) false;
		if ((isset($_COOKIE[$this->commentwritten_varname . $id])) || (isset($_SESSION[$this->commentwritten_varname . $id]) && $_SESSION[$this->commentwritten_varname . $id] >= time())) {
			$cookieset = (boolean) true;
		}
		elseif ($_SESSION[$this->commentwritten_varname . $id] < time()) {
			session_unregister($this->commentwritten_varname . $id);
		} // end if
		return (boolean) $cookieset;
	} //  end function
} // end class NewsUser
?>
