<?php
require_once 'class_PollBase_inc.php';

class PollOptionList extends PollBase {
	protected $poll;
	protected $polloptions;
	protected $request_type;

	function __construct(Poll &$poll = FALSE, $request_type = 'display', $database = '') {
		$this->setPoll($poll);
		$this->setRequestType($request_type);
		parent::__construct($database);
	} // end function

	protected function setPoll(Poll &$poll = FALSE) {
		if ($poll instanceOf Poll) {
			$this->poll =& $poll;
		} // end if
	} // end function

	public function &getPoll() {
		return $this->poll;
	} // end function

	protected function setRequestType($request_type = '') {
		$options = array('display', 'result', 'edit');
		if (!in_array($request_type, $options)) {
			$this->request_type = 'display';
			return FALSE;
		} // end if
		$this->request_type = $request_type;
		return TRUE;
	} // end if

	public function fetchData() {
		if (!isset($this->poll)) {
			return FALSE;
		} // end if

		$shuffle = FALSE;
		$sql  = 'SELECT id';
		$sql .= ' FROM ' . $this->tables['polloptions'];
		$sql .= ' WHERE poll = ' . parent::prepareSQLdata($this->poll->getID());
		if ($this->request_type == 'result' && $this->poll->getShowPopularResult() == TRUE) {
			$sql .= ' ORDER BY votes DESC, position DESC, title ASC';
		} elseif ($this->request_type == 'edit' || $this->poll->getShowOrderedList() == TRUE) {
			$sql .= ' ORDER BY position DESC, title ASC';
		} else {
			$shuffle = TRUE;
		} // end if

		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		$this->polloptions = array();
		while ($id = sqlite_fetch_single($result)) {
			$this->polloptions[$id] = new PollOption($id);
		} // end while

		if ($shuffle == TRUE) {
			srand((float )microtime() * 1000000);
			shuffle($this->polloptions);
		} // end if
	} // end function

	public function &getPollOptions() {
		return parent::getVar('polloptions');
	} // end function

	public function &addPollOption($title = '', $description = '', $position = 0, $active = TRUE) {
		if (parent::isFilledString($title) == FALSE) {
			return FALSE;
		} // end if

		$position = (int) $position;
		if ($position < 0) {
			$position = 0;
		} elseif ($position > 99) {
			$position = 99;
		} // end if

		$flags = 0;
		if ($active == TRUE) { $flags += PollOption::ACTIVE; }

		$sql  = 'INSERT INTO ' . $this->tables['polloptions'];
		$sql .= ' (poll, title, description, votes, lastvote, position, flags) VALUES';
		$sql .= ' (' . parent::prepareSQLdata($this->poll->getID()) . ", '" . parent::prepareSQLdata($title) . "', '" . parent::prepareSQLdata($description) . "', 0, 0, " . parent::prepareSQLdata($position) . ', ' . parent::prepareSQLdata($flags) . ')';
		$result = sqlite_query(parent::getConn(), $sql);

		if (!$result) {
			return FALSE;
		} // end if

		$id = sqlite_last_insert_rowid(parent::getConn());
		$this->fetchData();

		return $this->polloptions[$id];
	} // end function

	public function deletePollOption(PollOption &$po = FALSE) {
		if (!($po instanceOf PollOption)) {
			return FALSE;
		} // end if

		$po->removeImage();
		$sql  = ' DELETE FROM ' . $this->tables['polloptions'];
		$sql .= ' WHERE id = ' . parent::prepareSQLdata($po->getID());
		$result = sqlite_query(parent::getConn(), $sql);

		if ($result) {
			if (isset($this->polloptions)) {
				unset($this->polloptions);
				$this->fetchData();
			} // end if
			parent::vacuumDB();
			return TRUE;
		} // end if
		return FALSE;
	} // end if
} // end class
?>