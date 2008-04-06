<?php
/**
* load base class which takes care of all the other includes via it's autoload function
*/
require_once 'class.AccessBase.inc.php';

/**
* holds functions required for logins
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Access
* @version 1.0
*/
class AccessLogin extends AccessBase {
	/**
	* @const string prefix for session/cookie names
	*/
	const ACCESS_SESSION_NAME = 'access_login';

	/**
	* tries a login for a username/pw combination. writes login to the db and sets the session data
	*
	* return codes:
	* -1 = temporary locked (because of too many tries)
	* -2 = too many tries (account will get locked now temporary)
	* -3 = not enough time between login tries
	* -4 = db error
	* -5 = username/pw couldnt be found in the db
	* -6 = status of account isnt active
	* -7 = login-data (session/cookie) couldn't be written
	*  1 = Login OK
	*
	* @param string $username 
	* @param string $password
	* @return int
	*/
	public function login($username = '', $password = '') {

		$username = (string) parent::getConn()->real_escape_string($username);
		$password = (string) parent::generateCipherPassword(parent::getConn()->real_escape_string($password));
		$max_tries = parent::getAccessSetting('max_tries');
		$time_between_tries = parent::getAccessSetting('time_between_tries');
		$locktime = parent::getAccessSetting('locktime');
/*
		echo '<pre>';
		var_dump($_SESSION['access_login_tries']);
		echo '<br>';
		var_dump($_SESSION['access_login_last_try']);
		echo '<br>';
		var_dump($_SESSION['access_temp_lock']);
		echo '<br>';
		var_dump($password);
*/

		if (!isset($_SESSION[self::ACCESS_SESSION_NAME . '_tries']) || $_SESSION[self::ACCESS_SESSION_NAME . '_tries'] < 1) {
			$_SESSION[self::ACCESS_SESSION_NAME . '_tries'] = (int) 1;
		} // end if

		if (!isset($_SESSION[self::ACCESS_SESSION_NAME . '_last_try']) || $_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] < 0) {
			$_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] = (int) 0;
		} // end if

		// temp gesperrt?
		if (isset($_SESSION[self::ACCESS_SESSION_NAME . '_temp_lock']) && $_SESSION[self::ACCESS_SESSION_NAME . '_temp_lock'] > time()) {
				return -1;
		} elseif (isset($_SESSION[self::ACCESS_SESSION_NAME . '_temp_lock']) && $_SESSION[self::ACCESS_SESSION_NAME . '_temp_lock'] <= time()) {
			unset($_SESSION[self::ACCESS_SESSION_NAME . '_temp_lock']);
		} // end if

		// prüfen ob zuviele loginversuche
		if ($_SESSION[self::ACCESS_SESSION_NAME . '_tries'] > $max_tries) {
      $_SESSION[self::ACCESS_SESSION_NAME . '_temp_lock'] = (int) time() + $locktime;
			$_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] = (int) 0;
			$_SESSION[self::ACCESS_SESSION_NAME . '_tries'] = (int) 1;
			return -2;
		} // end if

		// wenn unter limit schaun ob letzter loginversuch zeitpunkt weit genug weg wenn nicht fehlermeldung ausgeben
		if (($_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] + $time_between_tries) > time()) {
        $_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] = (int) time();
				return -3;
		} // end if
		
		$_SESSION[self::ACCESS_SESSION_NAME . '_tries'] += 1;
		$_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] = time();

		$sp = 'CALL doLogin("' . $username . '", "' . $password .'");';

    if (!parent::getConn()->multi_query($sp)) {return -4;}
		$result = parent::getConn()->store_result();
		if (!$result) {return -4;}

		$r_value = -5;
		while($row = $result->fetch_row()) {
			if ($row[1] != ACCESS_STATUS_ACTIVE) {
				$r_value = -6;
				continue;
			} // end if
			if ($this->setLoginData($row[0]) < 0) {
				$r_value = -7;
				continue;
			} // end if
			$_SESSION[self::ACCESS_SESSION_NAME . '_last_try'] = (int) 0;
			$_SESSION[self::ACCESS_SESSION_NAME . '_tries'] = (int) 1;
      $this->recordLogin($row[0]); // keine fehlerabfrage!
			$r_value = 1;
    } // end while
    $result->close();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return $r_value;
	} // end function

	/**
	* writes a log entry into the db for a given user id
	* @param int $user_id
	* @return boolean
	*/
	protected function recordLogin($user_id = 0) {
		$user_id = (int) $user_id;
		if ($user_id < 1) {return FALSE;}
		$sp = 'CALL recordLogin(' . parent::getConn()->real_escape_string($user_id) . ', "' . parent::getCurrentIP() . '");';
		if (!parent::getConn()->multi_query($sp)) {return FALSE;}
		$result = parent::getConn()->store_result();
	  do {/*throw away other results*/} while (parent::getConn()->next_result());
		return TRUE;
	} // end function

	/**
	* public logout function. calls internal logout function
	* @param object $user AccessUser object
	* @return int
	*/
	public function logout(AccessUser $user = NULL) {
		if ($user instanceOf AccessUser) {
			return $this->deleteLoginData($user->getID());
		} // end if
		return $this->deleteLoginData();
	} // end function

	/**
	* checks for session/cookie data if a user is logged in
	*
	* return codes:
	* -1 = no token/identifier fould session or cookie
	* -2 = db error
	* -3 = no user with that identifier / token could be found in the db
	*  1 = Login OK
	*
	* @return int
	*/
	public function isLoggedIn() {
		$identifier = $token = FALSE;

		if (isset($_SESSION[self::ACCESS_SESSION_NAME . '_i']) && parent::isFilledString($_SESSION[self::ACCESS_SESSION_NAME . '_i'])) {
	    $identifier = parent::getConn()->real_escape_string($_SESSION[self::ACCESS_SESSION_NAME . '_i']);

		} // end if

		if (isset($_SESSION[self::ACCESS_SESSION_NAME . '_t']) && parent::isFilledString($_SESSION[self::ACCESS_SESSION_NAME . '_t'])) {
	    $token = parent::getConn()->real_escape_string($_SESSION[self::ACCESS_SESSION_NAME . '_t']);
		} // end if

		if (($identifier == FALSE || $token == FALSE) && isset($_COOKIE[self::ACCESS_SESSION_NAME])) {
			$temp = explode(':', $_COOKIE[self::ACCESS_SESSION_NAME]);
			$identifier = $temp[0];
			$token = $temp[1];
		} // end if

		if ($identifier == FALSE || $token == FALSE) {
			$this->deleteLoginData();
			return -1;
		} // end if

		$sp = 'CALL isLoggedIn(' . ACCESS_STATUS_ACTIVE . ', "' . $identifier .'", "' . $token . '");';
    if (!parent::getConn()->multi_query($sp)) {return -2;}
		$result = parent::getConn()->store_result();
		if (!$result) {return -2;}

		$r_value = -3;
		while($row = $result->fetch_row()) {
			$r_value = new AccessUser((int) $row[0]);
    } // end while
    $result->close();
	  do {} while (parent::getConn()->next_result());
		$this->deleteLoginData();
		return $r_value;
	} // end function

	/**
	* creates session / cookie data for a given user id and writes it to the db
	* @param int $user_id
	*
	* return codes:
	* -1 = old session/cookie data couldn't be deleted
	* -2 = identifier/token data couldn't be updated in the db (for the user)
	*  1 = OK
	*
	* @return int
	*/
	protected function setLoginData($user_id = 0) {
    $user_id = (int) $user_id;

		if ($this->deleteLoginData($user_id) < 0) {
			return -1;
		} // end if

		$use_cookies = parent::getAccessSetting('persistant_login');
		$timeout = time() + parent::getAccessSetting('timeout');
		$user = new AccessUser($user_id);
		$user->setIdentifier();
		$user->setToken();
		$user->setTimeout($timeout);
		
		if ($user->updateData(TRUE) < 0) {
			return -2;
		} // end if)
		
		$_SESSION[self::ACCESS_SESSION_NAME . '_i'] = $user->getIdentifier();
    $_SESSION[self::ACCESS_SESSION_NAME . '_t'] = $user->getToken();

		if ($use_cookies == TRUE) {
      setcookie(self::ACCESS_SESSION_NAME, $user->getIdentifier() . ':' . $user->getToken(), $timeout);
		} // end if
		return 1;
	} // end if

	/**
	* deletes session/cookie data for a given user id and writes it to the db
	* @param int $user_id if given data in the db will be destroyed too
	*
	* return codes:
	* -2 = identifier/token data couldn't be updated in the db (for the user)
	*  1 = OK
	*
	* @return int
	*/
	protected function deleteLoginData($user_id = 0) {
    $user_id = (int) $user_id;
		if (isset($_SESSION[self::ACCESS_SESSION_NAME . '_i'])) {
		  unset($_SESSION[self::ACCESS_SESSION_NAME . '_i']);
		} // end if

		if (isset($_SESSION[self::ACCESS_SESSION_NAME . '_t'])) {
		  unset($_SESSION[self::ACCESS_SESSION_NAME . '_t']);
		} // end if
		
		setcookie(self::ACCESS_SESSION_NAME, '', time() - 3600);

		if ($user_id > 0) {
			$user = new AccessUser($user_id);
			$user->setIdentifier('');
			$user->setToken();
			$user->setTimeout(0);
			if ($user->updateData(TRUE) < 0) {return -2;} // end if
		} // end if

		return 1;
	} // end function


	/**
	* replaces the old password with a generated new on writes it to the db and sends it to the given mail address
	* @param string $email email of a user in the db
	*
	* return codes:
	* -4 = no user with that mail adress was found in the db
	* -3 = sendmail didn't work
	* -2 = db error
	* -1 = string not a vaild mail address
	*  1 = OK
	*
	* @return int
	*/
	public function sendNewPassword($email = '') {

// noch umschreiben damit mail gesendet wird

		if (parent::isEmailString($email) == FALSE) {
			return -1;
		} // end if
		
		$email = (string) parent::getConn()->real_escape_string($email);
		$sp = 'CALL getUserByEmail("' . $email  . '", ' . ACCESS_STATUS_ACTIVE . ');';
		if (!parent::getConn()->multi_query($sp)) {return -2;}
		$result = parent::getConn()->store_result();
		if (!$result) {return -2;}
		$r_value = -4;
		while($row = $result->fetch_row()) {
			for($len = 8, $cleartext = ''; strlen($cleartext) < $len; $cleartext .= chr(!mt_rand(0, 2) ? mt_rand(48, 57) : (!mt_rand(0, 1) ? mt_rand(65, 90) : mt_rand(97, 122))));
			$from = $this->getAccessSetting('pw_mail_sender');
			$to = $email;
			$subject = $this->getAccessSetting('pw_mail_text_subject');
			$text = $this->getAccessSetting('pw_mail_text') . "\n\n";
			$text .= "\n\n" . $cleartext  . "\n\n";
			$text .= $this->getAccessSetting('pw_mail_text_signature');
			// sendmail(mail erzeugen und schicken)
      $sendmail = TRUE;
		 	file_put_contents('pwmailoutput.txt', $text); // debug funktion
			if ($sendmail == TRUE) {
				$user = new AccessUser($row[0]);
				$user->setPassword($cleartext);
				if ($user->updateData() < 0) {
					$r_value = -2;
					break;
				} // end if
				$r_value = 1;
				break;
			} // end if
			$r_value = -3;
			break;
    } // end while
    $result->close();
	  do {} while (parent::getConn()->next_result());
		return $r_value;
	} // end function
} // end class
?>
