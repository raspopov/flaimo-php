<?php
require_once 'class_PollBase_inc.php';

class PollList extends PollBase {
	protected $filter;
	protected $polls;

	function __construct(&$filter = FALSE, $database = '') {
		$this->setFilter($filter);
		parent::__construct($database);
	} // end function

	protected function setFilter(&$filter = FALSE) {
		if ($filter instanceOf PollCategory || $filter instanceOf Survey) {
			$this->filter =& $filter;
		} // end if
	} // end function

	public function &getFilter() {
		return $this->filter;
	} // end function

	public function fetchData() {
		if (!isset($this->filter)) {
			return FALSE;
		} // end if

		$shuffle = FALSE;
		if ($this->filter instanceOf PollCategory) {
			$sql  = 'SELECT id';
			$sql .= ' FROM ' . $this->tables['polls'];
			$sql .= ' WHERE category = ' . parent::prepareSQLdata($this->filter->getID());
			$sql .= ' ORDER BY created DESC, title ASC';
		} elseif($this->filter instanceOf Survey) {
			$sql  = 'SELECT poll_id';
			$sql .= ' FROM ' . $this->tables['survey_polls'];
			$sql .= ' WHERE survey_id = ' . parent::prepareSQLdata($this->filter->getID());
			if ($this->filter->getShowOrderedList() == TRUE) {
				$sql .= ' ORDER BY position DESC, poll_id ASC';
			} else {
				$shuffle = TRUE;
			} // end if
		} // end if

		$result = sqlite_unbuffered_query(parent::getConn(), $sql);
		$this->polls = array();
		while ($id = sqlite_fetch_single($result)) {
			$this->polls[$id] = new Poll($id);
		} // end while

		if ($shuffle == TRUE) {
			srand((float )microtime() * 1000000);
			shuffle($this->polls);
		} // end if
	} // end function

	public function &getPolls() {
		return parent::getVar('polls');
	} // end function

	public static final function isTimestamp($int = 0) {
		if ($int < 0 || $int > 2147483647) {
			return FALSE;
		} // end if
		return TRUE;
	} // end if

	public function &addPoll($title = '', $description = '', $start_poll = 0,
							 $stop_poll = 2147483647, $mc = FALSE,
							 $ordered = FALSE, $popular = FALSE,
							 $useonlyactive = TRUE, $displayonlyactive = FALSE,
							 $registeredusersonly = FALSE, $show_votes_percent = TRUE,
							 $show_votes_absolute = FALSE, $revote = 0,
							 $template = '', $button_label = 'Abstimmen',
							 $cp = 'umfrage', $cp_type= 'u', $channel = 0) {

		if (!($this->filter instanceOf PollCategory) ||
			parent::isFilledString($title) == FALSE ||
			parent::isFilledString($button_label) == FALSE ||
			parent::isFilledString($cp) == FALSE ||
			Poll::correctCPType($cp_type) == FALSE) {
			return FALSE;
		} // end if

		if ($this->isTimestamp($start_poll) == FALSE ) { $start_poll = 0; }
		if ($this->isTimestamp($stop_poll) == FALSE ) { $stop_poll = 2147483647; }

		$flags = 0;
		if ($mc == TRUE) { $flags += Poll::MULTIBLE_CHOISE; }
		if ($ordered == TRUE) { $flags += Poll::ORDERED_LIST; }
		if ($popular == TRUE) { $flags += Poll::POPULAR_RESULT; }
		if ($useonlyactive == TRUE) { $flags += Poll::USE_ONLY_ACTIVE_OPTIONS; }
		if ($displayonlyactive == TRUE) { $flags += Poll::DISPLAY_ONLY_ACTIVE_OPTIONS; }
		if ($registeredusersonly == TRUE) { $flags += Poll::REGISTERED_USERS_ONLY; }
		if ($show_votes_percent == TRUE) { $flags += Poll::SHOW_VOTES_PERCENT; }
		if ($show_votes_absolute == TRUE) { $flags += Poll::SHOW_VOTES_ABSOLUTE; }
		if ($this->isTimestamp($revote) == FALSE ) { $revote = 0; }

		//hier neue l1 und l2 berechnen
		$l1 = $l2 = 1;
		if ($this->filter->getID() == 1) {
			$l1 = $l2 = $this->filter->getID();
		} elseif($this->filter->getParent()->getID() == 1) {
			$l1 = $this->filter->getID();
		} elseif ($this->filter->getParent()->getParent()->getID() == 1) {
			$l1 = $this->filter->getParent()->getID();
			$l2 = $this->filter->getID();
		} else {
			// rekursiv berechnen
			$temp_cat = $this->filter;
			while ($temp_cat->getID() != 1) {
				if ($temp_cat->getParent()->getParent()->getID() == 1) {
					$l1 = $temp_cat->getParent()->getID();
					$l2 = $temp_cat->getID();
					break;
				} // end if
				$temp_cat = $temp_cat->getParent();
			} // end while
		} // end if

		$sql  = 'INSERT INTO ' . $this->tables['polls'];
		$sql .= ' (category, title, description, created, start_poll, stop_poll, flags, revote, template, button_label, cp, cp_type, channel, L1, L2) VALUES';
		$sql .= ' (' . parent::prepareSQLdata($this->filter->getID()) . ", '" . parent::prepareSQLdata($title) . "', '" . parent::prepareSQLdata($description) . "', " . time() . ', ' . parent::prepareSQLdata($start_poll) . ', ' . parent::prepareSQLdata($stop_poll) . ', ' . parent::prepareSQLdata($flags) . ', ' . parent::prepareSQLdata($revote) . ", '" . parent::prepareSQLdata($template) . "', '" . parent::prepareSQLdata($button_label) . "', '" . parent::prepareSQLdata($cp) . "', '" . parent::prepareSQLdata($cp_type) . "', " . parent::prepareSQLdata($channel) . ', ' . $l1 . ', ' . $l2 . ')';
		$result = sqlite_query(parent::getConn(), $sql);

		if (!$result) {
			return FALSE;
		} // end if
		$id = sqlite_last_insert_rowid(parent::getConn());
		$this->fetchData();
		return $this->polls[$id];
	} // end function

	public function addSurveyPoll(Poll &$poll = FALSE, $position = 99) {
		if (!($this->filter instanceOf Survey) ||
			!($poll instanceOf Poll)) {
			return FALSE;
		} // end if

		$position = (int) $position;
		$sql  = ' DELETE FROM ' . $this->tables['survey_polls'];
		$sql .= ' WHERE survey_id = ' . parent::prepareSQLdata($this->filter->getID());
		$sql .= ' AND poll_id = ' . parent::prepareSQLdata($poll->getID());

		if (sqlite_query(parent::getConn(), $sql) == FALSE) {
			return FALSE;
		} // end if

		$sql  = 'INSERT INTO ' . $this->tables['survey_polls'];
		$sql .= ' (survey_id, poll_id, position) VALUES';
		$sql .= ' (' . parent::prepareSQLdata($this->filter->getID()) . ', ' . parent::prepareSQLdata($poll->getID()) . ', ' . parent::prepareSQLdata($position) . ')';
		return sqlite_query(parent::getConn(), $sql);
	} // end function

	public function deletePoll(Poll &$poll = FALSE) {
		if (!($poll instanceOf Poll)) {
			return FALSE;
		} // end if

		if ($this->filter instanceOf PollCategory) {
			sqlite_exec(parent::getConn(), 'BEGIN TRANSACTION delete_poll');
			$sql  = 'DELETE FROM ' . $this->tables['polloptions'];
			$sql .= ' WHERE poll = ' . parent::prepareSQLdata($poll->getID());
			sqlite_exec(parent::getConn(), $sql);
			$sql  = ' DELETE FROM ' . $this->tables['polls'];
			$sql .= ' WHERE id = ' . parent::prepareSQLdata($poll->getID());
			sqlite_exec(parent::getConn(), $sql);
			sqlite_exec(parent::getConn(), 'END TRANSACTION delete_poll');
		} elseif ($this->filter instanceOf Survey) {
			$sql  = 'DELETE FROM ' . $this->tables['survey_polls'];
			$sql .= ' WHERE poll_id = ' . parent::prepareSQLdata($poll->getID());
			$sql .= ' AND survey_id = ' . parent::prepareSQLdata($this->filter->getID());
			sqlite_exec(parent::getConn(), $sql);
		} // end if

		if (isset($this->polls)) {
			unset($this->polls);
			$this->fetchData();
		} // end if
		parent::vacuumDB();
		return TRUE;
	} // end if

	public function copyPoll(Poll &$poll_to_copy = FALSE, PollCategory &$filter_to_copy_to = FALSE) {
		$dummy 						=  new PollList($filter_to_copy_to);
		$copied_poll 				=  $dummy->addPoll($poll_to_copy->getTitle() . ' Kopie', $poll_to_copy->getDescription(), $poll_to_copy->getStartPoll(), $poll_to_copy->getStopPoll(), $poll_to_copy->getMultipleChoise(), $poll_to_copy->getShowOrderedList(), $poll_to_copy->getShowPopularResult(), $poll_to_copy->getRevote(), $poll_to_copy->getTemplate());
		$polloption_list_to_copy 	=  new PollOptionList($poll_to_copy);
		$polloptions_to_copy 		=& $polloption_list_to_copy->getPollOptions();
		$copied_poll_list 			=  new PollOptionList($copied_poll, 'display');

		foreach ($polloptions_to_copy as &$polloption_original) {
			$polloption_copy = $copied_poll_list->addPollOption($polloption_original->getTitle(), $polloption_original->getDescription(), $polloption_original->getPosition());
		} // end foreach
		return TRUE;
	} // end function

} // end class
?>