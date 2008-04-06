<?php
/**
* @package Polls
*/
/**
* Including the helper class
*/
require_once('class.Helpers.inc.php');
/**
* Including the poll option class
*/
require_once('class.PollOption.inc.php');

/**
* creates a comment
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-04-21
*
* @desc creates a comment
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Polls
* @version 1.001
*/
class Comment {

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
	* comment ID
	*
	* @desc comment ID
	* @var int
	* @access private
	*/
	var $id;

	/**
	* poll ID
	*
	* @desc poll ID
	* @var int
	* @access private
	*/
	var $umfrage_id;

	/**
	* comment text
	*
	* @desc comment text
	* @var string
	* @access private
	*/
	var $text;

	/**
	* author name
	*
	* @desc author name
	* @var string
	* @access private
	*/
	var $author_name;

	/**
	* author e-mail
	*
	* @desc author e-mail
	* @var string
	* @access private
	*/
	var $author_mail;

	/**
	* date the comment was written
	*
	* @desc date the comment was written
	* @var string
	* @access private
	*/
	var $comment_date;

	/**
	* IP address of the author
	*
	* @desc IP address of the author
	* @var string
	* @access private
	*/
	var $ip;

	/**
	* string with the votes of the author
	*
	* @desc string with the votes of the author
	* @var string
	* @access private
	*/
	var $votes;

	/**
	* array with the votes of the author
	*
	* @desc array with the votes of the author
	* @var array
	* @access private
	*/
	var $votesarray;

	/**
	* whether the votes should be shown or not
	*
	* @desc whether the votes should be shown or not
	* @var boolean
	* @access private
	*/
	var $showvote;

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
	function Comment($id = 0) {
		$this->setID($id);
	} // end function


	/*-------------------*/
	/* F U N C T I O N S */
	/*-------------------*/

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
	* loads the data for this comment from the database
	*
	* @desc loads the data for this comment from the database
	* @return (void)
	* @access private
	* @uses setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function loadCommentData() {
		if ($this->id != 0) {
			$this->setDBconnection();
	        $sql  = "SELECT UmfrageID, CommentText, CommentAuthor, CommentEmail, CommentDate, CommentIP, Vote, ShowVote";
	        $sql .= " FROM " . $this->helpers->getTablenameComments();
	        $sql .= " WHERE IDComment = " . $this->id;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			if (mysql_num_rows($query) > 0) {
	            $this->umfrage_id = (int) mysql_result($query, 0, 0);
	            $this->text = (string) mysql_result($query, 0, 1);
	            $this->author_name = (string) mysql_result($query, 0, 2);
	            $this->author_mail = (string) mysql_result($query, 0, 3);
	            $this->comment_date = (string) mysql_result($query, 0, 4);
	            $this->ip = (string) mysql_result($query, 0, 5);
	            $this->votes = (string) mysql_result($query, 0, 6);
	            $this->showvote = (boolean) mysql_result($query, 0, 7);
			} // end if
            mysql_free_result($query);
		} else {
            $this->umfrage_id = (int) 0;
            $this->text = (string) '';
            $this->author_name = (string) '';
            $this->author_mail = (string) '';
            $this->comment_date = (string) date('Y-m-d H:i:s',time());
            $this->ip = (string) '';
            $this->votes = (string) 0;
            $this->showvote = (boolean) FALSE;
		}// end if
	} // end function

	/**
	* converts the votes string to an array with poll option objects
	*
	* @desc converts the votes string to an array with poll option objects
	* @return (void)
	* @access private
	* @since 1.001 - 2003/04/21
	*/
	function setVotes() {
    	$this->votesarray = (array) array();
    	if (strlen(trim($this->votes)) > 0) {
      		$pieces = explode(',', $this->votes);
      		foreach ($pieces as $pollopt_id) {
        		$pollopt = new PollOption($pollopt_id);
        		$this->votesarray[$pollopt_id] = $pollopt;
        	} // foreach
      	} // end if
    } // end function

	/**
	* returns the comment ID
	*
	* @desc returns the comment ID
	* @return (int) $id
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getID() {
		return (int) $this->id;
	} // end function

	/**
	* returns the ID of the poll, the comment is part of
	*
	* @desc returns the ID of the poll, the comment is part of
	* @return (int) $umfrage_id
	* @access public
	* @since 1.001 - 2003/04/21
	*/
  	function getUmfrageID() {
    	if (!isset($this->umfrage_id)) {
    		$this->loadCommentData();
    	} // end if
    	return (int) $this->umfrage_id;
    } // end function

	/**
	* returns the comment text
	*
	* @desc returns the comment text
	* @return (string) $text
	* @uses loadCommentData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getCommentText() {
    	if (!isset($this->text)) {
    		$this->loadCommentData();
    	} // end if
		return (string) $this->text;
	} // end function

	/**
	* returns the author's name
	*
	* @desc returns the author's name
	* @return (string) $author_name
	* @uses loadCommentData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getAuthorName() {
    	if (!isset($this->author_name)) {
    		$this->loadCommentData();
    	} // end if
		return (string) $this->author_name;
	} // end function

	/**
	* returns the author's e-mail
	*
	* @desc returns the author's e-mail
	* @return (string) $author_mail
	* @uses loadCommentData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getAuthorMail() {
    	if (!isset($this->author_mail)) {
    		$this->loadCommentData();
    	} // end if
		return (string) $this->author_mail;
	} // end function

	/**
	* returns the comment date
	*
	* @desc returns the comment date
	* @return (string) $comment_date
	* @uses loadCommentData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getCommentDate() {
    	if (!isset($this->comment_date)) {
    		$this->loadCommentData();
    	} // end if
		return (string) $this->comment_date;
	} // end function

	/**
	* returns the author's IP address
	*
	* @desc returns the author's IP address
	* @return (string) $ip
	* @uses loadCommentData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getAuthorIP() {
    	if (!isset($this->ip)) {
    		$this->loadCommentData();
    	} // end if
		return (string) $this->ip;
	} // end function

	/**
	* returns whether the votes should be shown or not
	*
	* @desc returns whether the votes should be shown or not
	* @return (boolean) $showvote
	* @uses loadCommentData()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getShowVote() {
    	if (!isset($this->showvote)) {
    		$this->loadCommentData();
    	} // end if
		return (boolean) $this->showvote;
	} // end function

	/**
	* returns an array with all the id's of the poll options voted for
	*
	* @desc returns an array with all the id's of the poll options voted for
	* @return (array)
	* @uses setVotes()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
  	function getVotesKeys() {
    	if (!isset($this->votesarray)) {
    		$this->setVotes();
    	} // end if
    	return (array) array_keys($this->votesarray);
    } // end function

	/**
	* returns a poll option object by the ID number
	*
	* @desc returns a poll option object by the ID number
	* @return (array)
	* @param (int) $id poll option ID
	* @uses setVotes()
	* @access public
	* @since 1.001 - 2003/04/21
	*/
  	function getVote($id = FALSE) {
    	if (!isset($this->votesarray)) {
    		$this->setVotes();
    	} // end if
		if ($id != FALSE && array_key_exists($id, $this->votesarray)) {
			return (object) $this->votesarray[$id];
		} // end if
    } // end function
} // end class Comment
?>
