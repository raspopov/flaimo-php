<?php
/**
* @package Polls
*/
/**
* Helper methods for the Poll classes
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-04-21
*
* @desc Helper methods for the Poll classes
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Polls
* @version 1.001
*/
class Helpers {

	/*-------------------*/
	/* V A R I A B L E S */
	/*-------------------*/

	/**
	* MySQL host
	*
	* @desc MySQL host
	* @var string
	* @access private
	*/
	var $db_host = 'localhost';

	/**
	* MySQL user
	*
	* @desc MySQL user
	* @var string
	* @access private
	*/
	var $db_user = 'root';

	/**
	* MySQL password
	*
	* @desc MySQL password
	* @var string
	* @access private
	*/
	var $db_password = '';

	/**
	* MySQL database
	*
	* @desc MySQL database
	* @var string
	* @access private
	*/
	var $database = 'absolventen';

	/**
	* table name for the poll table
	*
	* @desc table name for the poll table
	* @var string
	* @access private
	*/
	var $tbl_polls = 'tbl_polls';

	/**
	* table name for the votes table
	*
	* @desc table name for the votes table
	* @var string
	* @access private
	*/
	var $tbl_votes = 'tbl_votes';

	/**
	* table name for the comment table
	*
	* @desc table name for the comment table
	* @var string
	* @access private
	*/
	var $tbl_comments = 'tbl_comments';

	/**
	* MySQL connection
	*
	* @desc MySQL connection
	* @access private
	*/
	var $conn;

	/**
	* number of polls
	*
	* @desc number of polls
	* @var int
	* @access private
	*/
	var $sum_polls;

	/**
	* number of votes
	*
	* @desc number of votes
	* @var int
	* @access private
	*/
	var $sum_votes;

	/**
	* highest ID number from all polls
	*
	* @desc highest ID number from all polls
	* @var int
	* @access private
	*/
	var $max_id;


	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @return (void)
	* @access private
	* @uses readDefaultPollSettings()
	* @since 1.001 - 2003/04/21
	*/
	function Helpers() {
		$this->readDefaultPollSettings();
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

	/**
	* Reads the default settings from the settings file if necessary
	*
	* @desc Reads the default settings from the settings file if necessary
	* @return (void)
	* @access private
	* @since 1.001 - 2003/04/21
	*/
	function readINIsettings() {
		if (!isset($GLOBALS['poll_settings']) && file_exists('poll_settings.ini')) {
			$GLOBALS['poll_settings'] = (array) parse_ini_file('poll_settings.ini', TRUE);
		} // end if
	} // end function

	/**
	* Reads the default settings for the poll classes
	*
	* @desc Reads the default settings for the poll classes
	* @return (void)
	* @access private
	* @uses readINIsettings()
	* @since 1.001 - 2003/04/21
	*/
	function readDefaultPollSettings() {
        $this->readINIsettings();
        if (isset($GLOBALS['poll_settings'])) {
            $this->db_host 			=& $GLOBALS['poll_settings']['Database']['host'];
            $this->db_user 			=& $GLOBALS['poll_settings']['Database']['user'];
            $this->db_password 		=& $GLOBALS['poll_settings']['Database']['password'];
            $this->database 		=& $GLOBALS['poll_settings']['Database']['database'];
            $this->tbl_polls 		=& $GLOBALS['poll_settings']['Database']['tbl_polls'];
            $this->tbl_votes 		=& $GLOBALS['poll_settings']['Database']['tbl_votes'];
            $this->tbl_comments 	=& $GLOBALS['poll_settings']['Database']['tbl_comments'];
    	} // end if
	} // end function

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
			$this->conn = mysql_pconnect($this->db_host, $this->db_user, $this->db_password) or die ('Connection not possible! => ' . mysql_error());
			mysql_select_db(&$this->database) or die ('Couldn\'t connect to "' . $this->database . '" => ' . mysql_error());
		} // end if
	} // end function

	/**
	* Gets the number of polls from the database
	*
	* @desc Gets the number of polls from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function setSumPolls() {
		if (!isset($this->sum_polls)) {
			$this->setDBconnection();
			$sql  = 'SELECT COUNT(ID)';
			$sql .= ' FROM ' . $this->tbl_polls;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->sum_polls = mysql_result($query, 0, 0);
	    	mysql_free_result($query);
    	} // end if
	} // end function

	/**
	* Gets the number of votes from the database
	*
	* @desc Gets the number of votes from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
  	function setSumVotes() {
		if (!isset($this->sum_votes)) {
			$this->setDBconnection();
			$sql  = "SELECT SUM(Stimmen)";
			$sql .= " FROM " . $this->tbl_votes;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->sum_votes = mysql_result($query, 0, 0);
	    	mysql_free_result($query);
		}
	} // end function

	/**
	* Gets the highest ID number of all polls
	*
	* @desc Gets the highest ID number of all polls
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
  	function setMaxID() {
		if (!isset($this->max_id)) {
			$this->setDBconnection();
			$sql  = "SELECT MAX(ID)";
			$sql .= " FROM " . $this->tbl_polls;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->max_id = mysql_result($query, 0, 0);
	    	mysql_free_result($query);
		}
	} // end function

	/**
	* Returns the number of polls
	*
	* @desc Returns the number of polls
	* @return (int) $sum_polls
	* @access public
	* @uses setSumPolls()
	* @since 1.001 - 2003/04/21
	*/
	function getSumPolls() {
		$this->setSumPolls();
		return (int) $this->sum_polls;
	} // end function

	/**
	* Returns the number of votes
	*
	* @desc Returns the number of votes
	* @return (int) $sum_votes
	* @access public
	* @uses setSumVotes()
	* @since 1.001 - 2003/04/21
	*/
	function getSumVotes() {
		$this->setSumVotes();
		return (int) $this->sum_votes;
	} // end function

	/**
	* Returns the highest ID of all polls
	*
	* @desc Returns the highest ID of all polls
	* @return (int) $sum_votes
	* @access public
	* @uses setMaxID()
	* @since 1.001 - 2003/04/21
	*/
	function getMaxID() {
		$this->setMaxID();
		return (int) $this->max_id;
	} // end function

	/**
	* Returns the name for the polls table
	*
	* @desc Returns the name for the polls table
	* @return (string) $tbl_polls
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getTablenamePolls() {
		return (string) $this->tbl_polls;
	} // end function

	/**
	* Returns the name for the votes table
	*
	* @desc Returns the name for the votes table
	* @return (string) $tbl_votes
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getTablenameVotes() {
		return (string) $this->tbl_votes;
	} // end function

	/**
	* Returns the name for the comment table
	*
	* @desc Returns the name for the comment table
	* @return (string) $tbl_comments
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getTablenameComments() {
		return (string) $this->tbl_comments;
	} // end function

	/**
	* Returns MySQL host
	*
	* @desc Returns MySQL host
	* @return (string) $db_host
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getDBHost() {
		return (string) $this->db_host;
	} // end function

	/**
	* Returns MySQL user
	*
	* @desc Returns MySQL user
	* @return (string) $db_user
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getDBUser() {
		return (string) $this->db_user;
	} // end function

	/**
	* Returns MySQL password
	*
	* @desc Returns MySQL password
	* @return (string) $db_password
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getDBPassword() {
		return (string) $this->db_password;
	} // end function

	/**
	* Returns MySQL database
	*
	* @desc Returns MySQL database
	* @return (string) $database
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getDBname() {
		return (string) $this->database;
	} // end function
} // end Class Helpers
?>