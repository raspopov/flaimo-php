<?php
require_once 'class_PollBase_inc.php';

class PollUser extends PollBase {
	protected $use_cookies;
	const SESSION_PREFIX = 'polls';
	const COOKIE_TIME = 31536000;

	function __construct($use_cookies = TRUE) {
		$this->setUseCookies($use_cookies);
		parent::__construct();
	} // end function

	public function setUseCookies($boolean = TRUE) {
		$this->use_cookies = $boolean;
	} // end function

	public function setLastVote(Poll &$poll = FALSE) {
		if (!($poll instanceOf Poll)) {
			return FALSE;
		} // end if

		$_SESSION[PollUser::SESSION_PREFIX]['votes'][$poll->getID()] = time();
		if ($this->use_cookies == TRUE) {
			@setcookie('polls_lastvote_' . $poll->getID(), time(), time()+ PollUser::COOKIE_TIME);
		} // end if
	} // end function

	public function getLastVote(Poll &$poll = FALSE) {
		if (!($poll instanceOf Poll)) {
			return FALSE;
		} // end if
		if (isset($_SESSION[PollUser::SESSION_PREFIX]['votes'][$poll->getID()])) {
			return $_SESSION[PollUser::SESSION_PREFIX]['votes'][$poll->getID()];
		} elseif ($this->use_cookies == TRUE && isset($_COOKIE['polls_lastvote_' . $poll->getID()])) {
			return $_COOKIE['polls_lastvote_' . $poll->getID()];
		} // end if
	
		return FALSE;
	} // end function
	
		public function getNextVote(Poll &$poll = FALSE) {
		if (!($poll instanceOf Poll) || $this->getLastVote($poll) == FALSE) {
			return FALSE;
		} // end if
		$time_passed = (int) (time() - $this->getLastVote($poll));
		$sec_to_go = $poll->getRevote() - $time_passed;
		return (int) ($sec_to_go > 0) ? $sec_to_go : 0;
		} // end function
} // end class
?>