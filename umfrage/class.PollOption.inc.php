<?php
/**
* @package Polls
*/
/**
* Including the Helper class
*/
require_once('class.Helpers.inc.php');

/**
* Holds the information for a single poll option
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-04-21
*
* @desc Holds the information for a single poll option
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Polls
* @version 1.001
*/
class PollOption {

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
	* poll option ID
	*
	* @desc poll option ID
	* @var int
	* @access private
	*/
	var $id;

	/**
	* poll ID which the option is part of
	*
	* @desc poll ID which the option is part of
	* @var int
	* @access private
	*/
	var $poll_id;

	/**
	* Answer string of the option
	*
	* @desc Answer string of the option
	* @var string
	* @access private
	*/
	var $answer;

	/**
	* Number of votes for that option
	*
	* @desc Number of votes for that option
	* @var int
	* @access private
	*/
	var $votes;

	/**
	* date of the last vote for that option
	*
	* @desc date of the last vote for that option
	* @var string
	* @access private
	*/
	var $last_vote;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param (int) $id ID of the option
	* @return (void)
	* @access private
	* @uses setID()
	* @since 1.001 - 2003/04/21
	*/
	function PollOption($id = 0) {
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
	* sets the ID number of this option
	*
	* @desc sets the ID number of this option
	* @param (int) $id ID of the option
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
	* loads the data for this option from the database
	*
	* @desc loads the data for this option from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function loadPollOptionData() {
		if ($this->id != 0) {
			$this->setDBconnection();
			$sql  = (string) 'SELECT Umfrage_ID, Antwort, Stimmen, LetzteStimme';
			$sql .= (string) ' FROM ' . $this->helpers->getTablenameVotes();
			$sql .= (string) ' WHERE ID = ' . $this->id;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			if (mysql_num_rows($query) > 0) {
				$this->poll_id = (int) mysql_result($query, 0, 0);
				$this->answer = (string) mysql_result($query, 0, 1);
				$this->votes = (int) mysql_result($query, 0, 2);
				$this->last_vote = (string) mysql_result($query, 0, 3);
			} // end if
            mysql_free_result($query);
		} else {
			$this->poll_id = (int) 0;
			$this->answer = (string) '';
			$this->votes = (int) 0;
			$this->last_vote = (string) date('Y-m-d H:i:s',time());
		}// end if
	} // end function

	/**
	* Returns the option ID
	*
	* @desc Returns the option ID
	* @return (int) $id
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getID() {
		return (int) $this->id;
	} // end function

	/**
	* Returns the poll ID which th option is part of
	*
	* @desc Returns the option ID
	* @return (int) $poll_id
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollID() {
		if (!isset($this->poll_id)) {
			$this->loadPollOptionData();
		} // end if
		return (int) $this->poll_id;
	} // end function

	/**
	* Returns the answer string of this option
	*
	* @desc Returns the answer string of this option
	* @return (string) $answer
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getAnswer() {
		if (!isset($this->answer)) {
			$this->loadPollOptionData();
		} // end if
		return (string) $this->answer;
	} // end function

	/**
	* Returns the number of votes for this option
	*
	* @desc Returns the number of votes for this option
	* @return (int) $votes
	* @access public
	* @uses loadPollOptionData()
	* @since 1.001 - 2003/04/21
	*/
	function getVotes() {
		if (!isset($this->votes)) {
			$this->loadPollOptionData();
		} // end if
		return (int) $this->votes;
	} // end function

	/**
	* Returns the date of the last time this option was voted for
	*
	* @desc Returns the date of the last time this option was voted for
	* @return (string) $last_vote
	* @access public
	* @uses loadPollOptionData()
	* @since 1.001 - 2003/04/21
	*/
	function getLastVote() {
		if (!isset($this->last_vote)) {
			$this->loadPollOptionData();
		} // end if
		return (string) $this->last_vote;
	} // end function
} // end class PollOption
?>