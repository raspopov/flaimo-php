<?php
/* KLASSE NICHT FUNKTIONSFÄHIG !!!!!!!!!!!! */
require_once 'class_PollBase_inc.php';

class RegisteredPollUser extends PollUser {
	protected $networld_user;
	protected $usercount_database = '/networld_classes/poll/inc/uservotes.sqlite';
	protected $user_conn;

	function __construct(NetworldPublicUser &$networld_user = FALSE) {
		$this->setNetworldUser($networld_user);
		parent::__construct();
	} // end function

	public function setNetworldUser(NetworldPublicUser &$networld_user = FALSE) {
		if ($networld_user instanceOf NetworldPublicUser) {
			$this->networld_user = $networld_user;
		} // end if
	} // end function

	public function setUserDB($string = '') {
		if (parent::isFilledString($string) == TRUE) {
			$this->usercount_database = $string;
		} // end if
	} // end function

	protected function getUserDBconn() {
		if (!isset($this->user_conn)) {
			$this->user_conn =& getSQLiteConn($this->usercount_database);
		} // end if
		return $this->user_conn;
	} // end function

	public function getLastVote(Poll &$poll = FALSE) {
		/* noch nicht implementiert */
		return FALSE;
	} // end function

	public function getNextVote(Poll &$poll = FALSE) {
	/* noch nicht implementiert */
	return FALSE;
	} // end function
} // end class
?>