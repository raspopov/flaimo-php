<?php
/**
* @package Polls
*/
/**
* Including the helper class
*/
require_once('class.Helpers.inc.php');
/**
* Including the User class
*/
require_once('class.PollUser.inc.php');
/**
* Including the PollOption class
*/
require_once('class.PollOption.inc.php');
/**
* Including the Comment class
*/
require_once('class.Comment.inc.php');

/**
* creates a poll
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-04-21
*
* @desc creates a poll
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Polls
* @version 1.001
*/
class Poll {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* helper object
	*
	* @desc helper object
	* @var object
	* @access private
	*/
	var $helpers;

	/**
	* MySQL connection
	*
	* @desc MySQL connection
	* @access private
	*/
	var $conn;

	/**
	* user object
	*
	* @desc user object
	* @var object
	* @access private
	*/
	var $user;

	/**
	* ID number of the poll
	*
	* @desc ID number of the poll
	* @var int
	* @access private
	*/
	var $id;

	/**
	* question string of the poll
	*
	* @desc question string of the poll
	* @var string
	* @access private
	*/
	var $question;

	/**
	* description string of the poll
	*
	* @desc description string of the poll
	* @var string
	* @access private
	*/
	var $description;

	/**
	* date the poll was created
	*
	* @desc date the poll was created
	* @var string
	* @access private
	*/
	var $poll_date;

	/**
	* whether multible answers are allowed or not
	*
	* @desc whether multible answers are allowed or not
	* @var boolean
	* @access private
	*/
	var $multible;

	/**
	* whether the poll is closed or not
	*
	* @desc hether the poll is closed or not
	* @var boolean
	* @access private
	*/
	var $closed;

	/**
	* array with all the poll option objects
	*
	* @desc array with all the poll option objects
	* @var array
	* @access private
	*/
	var $polloptions;

	/**
	* array with all the poll comments objects
	*
	* @desc array with all the poll comments objects
	* @var array
	* @access private
	*/
	var $comments;

	/**
	* number of votes for this poll
	*
	* @desc number of votes for this poll
	* @var int
	* @access private
	*/
	var $sumvotes;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param (int) $id ID of the poll
	* @return (void)
	* @access private
	* @uses setID()
	* @since 1.001 - 2003/04/21
	*/
	function Poll($id = 0) {
		$this->setID($id);
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* loads the helper class
	*
	* @desc loads the helper class
	* @return (void)
	* @access private
	* @since 1.001 - 2003/04/21
	*/
	function loadHelperClass() {
		if (!isset($this->helpers)) {
			$this->helpers = (object) new Helpers();
		} // end if
	} // end function

	/**
	* loads the user class
	*
	* @desc loads the user class
	* @return (void)
	* @access private
	* @since 1.001 - 2003/04/21
	*/
	function loadUserClass() {
		if (!isset($this->user)) {
			$this->user = (object) new PollUser();
		} // end if
	} // end function

	/**
	* sets the ID number of this poll
	*
	* @desc sets the ID number of this poll
	* @param (int) $id ID of the poll
	* @return (void)
	* @access private
	* @since 1.001 - 2003/04/21
	*/
	function setID($id) {
		$this->id = (int) $id;
	}  // end function

	/**
	* Created a MySQL connection
	*
	* @desc Created a MySQL connection
	* @return (void)
	* @access private
	* @since 1.001 - 2003/04/21
	*/
  	function setDBconnection() {
		if (!isset($this->conn)) {
			$this->loadHelperClass();
			$this->conn = mysql_pconnect($this->helpers->getDBHost(), $this->helpers->getDBUser(), $this->helpers->getDBPassword()) or die ('Connection not possible! => ' . mysql_error());
			mysql_select_db($this->helpers->getDBname()) or die ('Couldn\'t connect to "' .$this->helpers->getDBname() . '" => ' . mysql_error());
		} // end if
	} // end function

	/**
	* loads the data for this poll from the database
	*
	* @desc loads the data for this poll from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function loadPollData() {
		if ($this->id != 0) {
			$this->setDBconnection();
			$sql  = (string) 'SELECT Frage, Beschreibung, Datum, Multible, Closed';
			$sql .= (string) ' FROM ' . $this->helpers->getTablenamePolls();
			$sql .= (string) ' WHERE ID = ' . $this->id;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			if (mysql_num_rows($query) > 0) {
				$this->question = (string) mysql_result($query, 0, 0);
				$this->description = (string) mysql_result($query, 0, 1);
				$this->poll_date = (string) mysql_result($query, 0, 2);
				$this->multible = (boolean) mysql_result($query, 0, 3);

				$date = (string) mysql_result($query, 0, 4);
				if ($date != '0000-00-00' && date('Y-m-d') > $date) {
					$this->closed = (boolean) TRUE;
				} else {
					$this->closed = (boolean) FALSE;
				} // end if

			} // end if
			mysql_free_result($query);
		} else {
			$this->question = (string) '';
			$this->description = (string) '';
			$this->poll_date = (string) date('Y-m-d H:i:s',time());
			$this->multible = (boolean) FALSE;
			$this->closed = (boolean) TRUE;
		}// end if
	}  // end function

	/**
	* loads the options data for this poll from the database
	*
	* @desc loads the options data for this poll from the database
	* @return (void)
	* @access private
	* @uses setDBconnection(), loadHelperClass()
	* @since 1.001 - 2003/04/21
	*/
	function loadPollOptions() {
		if (!isset($this->polloptions)) {
			$this->loadHelperClass();
			$this->setDBconnection();
			$sql  = (string) 'SELECT ID';
			$sql .= (string) ' FROM ' . $this->helpers->getTablenameVotes();
			$sql .= (string) ' WHERE Umfrage_ID = ' . $this->id;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->polloptions = array();
			if (mysql_num_rows($query) > 0) {
				while ($row = mysql_fetch_array($query, MYSQL_NUM)) {
		    		$pollopt = new PollOption($row[0]);
					$this->polloptions[$row[0]] = $pollopt;
				} // end while
			} // end if
			mysql_free_result($query);
		} // end if
	} // end function

	/**
	* loads the comments data for this poll from the database
	*
	* @desc loads the comments data for this poll from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function loadComments() {
		if (!isset($this->comments)) {
			$this->setDBconnection();
			$sql  = (string) 'SELECT IDComment';
			$sql .= (string) ' FROM ' . $this->helpers->getTablenameComments();
			$sql .= (string) ' WHERE UmfrageID = ' . $this->id;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->comments = array();
			if (mysql_num_rows($query) > 0) {
				while ($row = mysql_fetch_array($query, MYSQL_NUM)) {
		    		$comment = new Comment($row[0]);
					$this->comments[$row[0]] = $comment;
				} // end while
			} // end if
			mysql_free_result($query);
		} // end if
	} // end function

	/**
	* gets the number of votes for this poll from the database
	*
	* @desc gets the number of votes for this poll from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function setSumVotes() {
		$this->setDBconnection();
		$sql  = (string) 'SELECT SUM(Stimmen) as summe_stimmen';
		$sql .= (string) ' FROM ' . $this->helpers->getTablenameVotes();
		$sql .= (string) ' WHERE Umfrage_ID = ' . $this->id;
		$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
		$this->sumvotes = mysql_result($query, 0, 0);
	    mysql_free_result($query);
	} // end if

	/**
	* updates the database and sets a cookie/session if the user has voted
	*
	* @desc updates the database and sets a cookie/session if the user has voted
	* @param (mixed) $choise id or array of id's with the selected option(s)
	* @return (boolean) update successfull or not
	* @access public
	* @uses setDBconnection(), loadUserClass()
	* @since 1.001 - 2003/04/21
	*/
	function insertVote($choise = 0) {
		$this->loadUserClass();
		if (($choise != 0) && ($this->closed == FALSE) && ($this->user->isPollVoted($this->id) === FALSE)) {
			$this->setDBconnection();
			if ($this->getIsMultible() === FALSE) {
				$sql  = 'UPDATE ' . $this->helpers->getTablenameVotes();
				$sql .= ' SET Stimmen = (Stimmen+1), LetzteStimme = "' . date('Y-m-d H:i:s') . '"';
				$sql .= ' WHERE ID = ' . $choise;
				$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			} else {
				foreach ($choise as $multible_choise => $multible_choise_value) {
					$sql  = "UPDATE " . $this->helpers->getTablenameVotes();
					$sql .= " SET Stimmen = (Stimmen+1), LetzteStimme = '" . date('Y-m-d H:i:s') . "'";
					$sql .= " WHERE ID = " . $multible_choise_value;
					$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
				} // end foreach
			} // end if
			$this->user->setPollVoted($this->id, $choise);
			return (boolean) TRUE;
		} else {
			return (boolean) FALSE;
		} // end if
	} // end function

	/**
	* inserts a comment for this poll into the database
	*
	* @desc inserts a comment for this poll into the database
	* @param (string) $comment comment text
	* @param (string) $name author name
	* @param (string) $mail author email
	* @param (int) $showvote should the vote be shown with comment or not
	* @return (boolean) update successfull or not
	* @access public
	* @uses setDBconnection(), loadUserClass()
	* @since 1.001 - 2003/04/21
	*/
	function insertComment($comment, $name, $mail, $showvote) {
		$this->loadUserClass();
		if ((!isset($name) || strlen(trim($name)) < 1 || !isset($comment) || strlen(trim($comment)) < 1) || ($this->user->isCommentWritten($this->id) === TRUE)) {
			return (boolean) FALSE;
		} else {
			$this->setDBconnection();
			$choosen = (string) ((isset($_COOKIE[$this->user->getPollVarname() . $this->id])) ? $_COOKIE[$this->user->getPollVarname() . $this->id] : '');
			$sql  = (string) 'INSERT INTO ' . $this->helpers->getTablenameComments() . ' (UmfrageID, CommentText, CommentAuthor, CommentEmail, CommentDate, CommentIP, Vote, ShowVote)';
			$sql .= (string) ' VALUES (' . $this->id . ',"' . $comment . '","' . trim($name) . '","' . trim($mail) . '","' . date('Y-m-d H:i:s') . '","' . $_SERVER["REMOTE_ADDR"] . '","' . $choosen . '","' . $showvote . '")';
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->user->setCommentWritten($this->id);
			return (boolean) TRUE;
		} // end if
	} // end function

	/**
	* returns a PollOption object by the ID
	*
	* @desc returns a PollOption object by the ID
	* @param (int) $id poll option id
	* @return (object) $polloptions PollOption object
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollOption($id = FALSE) {
		if ($id != FALSE && array_key_exists($id, $this->polloptions)) {
			return (object) $this->polloptions[$id];
		} // end if
	} // end function

	/**
	* returns a comment object by the ID
	*
	* @desc returns a comment object by the ID
	* @param (int) $id comment id
	* @return (object) $comments Comment object
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getComment($id = FALSE) {
		if ($id != FALSE && array_key_exists($id, $this->comments)) {
			return (object) $this->comments[$id];
		} // end if
	} // end function

	/**
	* returns the poll ID
	*
	* @desc returns the poll ID
	* @return (int) $id poll ID
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getID() {
		return (int) $this->id;
	} // end function

	/**
	* returns the poll question
	*
	* @desc returns the poll question
	* @return (string) $question
	* @uses loadPollData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getQuestion() {
		if (!isset($this->question)) {
			$this->loadPollData();
		} // end if
		return (string) $this->question;
	} // end function

	/**
	* returns the poll description
	*
	* @desc returns the poll description
	* @return (string) $question
	* @uses loadPollData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getDescription() {
		if (!isset($this->description)) {
			$this->loadPollData();
		} // end if
		return (string) $this->description;
	} // end function

	/**
	* returns the poll date
	*
	* @desc returns the poll date
	* @return (string) $poll_date
	* @uses loadPollData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollDate() {
		if (!isset($this->poll_date)) {
			$this->loadPollData();
		} // end if
		return (string) $this->poll_date;
	} // end function

	/**
	* returns whether the poll is MC or not
	*
	* @desc returns whether the poll is MC or not
	* @return (boolean) $multible
	* @uses loadPollData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getIsMultible() {
		if (!isset($this->multible)) {
			$this->loadPollData();
		} // end if
		return (boolean) $this->multible;
	} // end function

	/**
	* returns whether the poll is closed or not
	*
	* @desc returns whether the poll is closed or not
	* @return (boolean) $closed
	* @uses loadPollData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getClosed() {
		if (!isset($this->closed)) {
			$this->loadPollData();
		} // end if
		return (boolean) $this->closed;
	} // end function

	/**
	* returns an array with all the poll option id's of this poll
	*
	* @desc returns an array with all the poll option id's of this poll
	* @return (array) poll option id's
	* @uses loadPollOptions()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollOptionKeys() {
		$this->loadPollOptions();
		return (array) array_keys($this->polloptions);
	} // end function

	/**
	* returns an array with all the comment id's of this poll
	*
	* @desc returns an array with all the comment id's of this poll
	* @return (array) comment id's
	* @uses loadComments()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollCommentKeys() {
		$this->loadComments();
		return (array) array_keys($this->comments);
	} // end function

	/**
	* returns the number of votes for this poll
	*
	* @desc returns the number of votes for this poll
	* @return (int) $sumvotes
	* @uses setSumVotes()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getSumVotes() {
		if (!isset($this->sumvotes)) {
			$this->setSumVotes();
		}
		return (int) $this->sumvotes;
	} // end function
} // end class Poll
?>