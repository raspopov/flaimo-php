<?php
/**
* @package Polls
*/
/**
* Including the helper class
*/
require_once('class.Helpers.inc.php');
/**
* Including the poll class
*/
require_once('class.Poll.inc.php');

/**
* created a list of polls
*
* Tested with WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)
* Last change: 2003-04-21
*
* @desc created a list of polls
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/
* @package Polls
* @version 1.001
*/
class PollList {

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
	* starting point of the poll list
	*
	* @desc starting point of the poll list
	* @var int
	* @access private
	*/
	var $rs_start;

	/**
	* number of polls in the list
	*
	* @desc number of polls in the list
	* @var int
	* @access private
	*/
	var $rs_listsize;

	/**
	* array with the poll objects
	*
	* @desc array with the poll objects
	* @var array
	* @access private
	*/
	var $poll_list;

	/**
	* whether a previous page can be created with the same settings or not
	*
	* @desc whether a previous page can be created with the same settings or not
	* @var boolean
	* @access private
	*/
	var $prevpage;

	/**
	* whether a next page can be created with the same settings or not
	*
	* @desc whether a next page can be created with the same settings or not
	* @var boolean
	* @access private
	*/
	var $nextpage;

	/**
	* array with pagenumbers and startingspoints
	*
	* @desc array with pagenumbers and startingspoints
	* @var array
	* @access private
	*/
	var $pages;

	/*-----------------------*/
	/* C O N S T R U C T O R */
	/*-----------------------*/

	/**
	* Constructor
	*
	* @desc Constructor
	* @param (int) $start startingpoint of the poll list
	* @param (int) $listsize number of polls in the list
	* @return (void)
	* @access private
	* @uses setPollList()
	* @since 1.001 - 2003/04/21
	*/
	function PollList($start = 0, $listsize = 10) {
		$this->rs_start = (int) $start;
		$this->rs_listsize = (int) $listsize;
		$this->setPollList();
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
	* loads the poll list data from the database
	*
	* @desc loads the poll list data from the database
	* @return (void)
	* @access private
	* @uses loadHelperClass(), setDBconnection()
	* @since 1.001 - 2003/04/21
	*/
	function setPollList() {
		if (!isset($this->poll_list)) {
			$this->loadHelperClass();
			$this->setDBconnection();
			$sql  = 'SELECT ID';
			$sql .= ' FROM ' . $this->helpers->getTablenamePolls();
			$sql .= ' ORDER BY ID DESC';
			$sql .= ' LIMIT ' . $this->rs_start . "," . $this->rs_listsize;
			$query = mysql_query($sql, $this->conn) or die ('Abfrage war ungültig! SQL Statement: ' . $sql . ' / ' . mysql_error());
			$this->poll_list = (array) array();
			if (mysql_num_rows($query) > 0) {
				while ($row = mysql_fetch_array($query, MYSQL_NUM)) {
		    		$poll = new Poll($row[0]);
					$this->poll_list[$row[0]] = $poll;
				} // end while
			} // end if
			mysql_free_result($query);

			$this->prevpage = (boolean) ( (($this->rs_start - $this->rs_listsize) >= 0) ? TRUE : FALSE);
			$this->nextpage = (boolean) ( (($this->rs_start + $this->rs_listsize) <= $this->helpers->getSumPolls()) ? TRUE : FALSE);

			if ($this->helpers->getSumPolls() > $this->rs_listsize) {
				$this->pages = (array) array();
				for ($i = 0, $sum_pages = (int) ceil($this->helpers->getSumPolls()/$this->rs_listsize); $i < $sum_pages; $i++) {
					$this->pages[($i+1)] = ($i * $this->rs_listsize);
				} // end for
				unset($i);
				unset($sum_pages);
			} // end if
		} // end if
	}  // end function

	/**
	* returns an array with the keys of the polllist array
	*
	* @desc returns an array with the keys of the polllist array
	* @return (array)
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPollListKeys() {
		return (array) array_keys($this->poll_list);
	} // end function

	/**
	* returns a poll object by a given ID number
	*
	* @desc returns a poll object by a given ID number
	* @param (int) $id ID of the poll
	* @return (object) $poll_list
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPoll($id = FALSE) {
		if ($id != FALSE && array_key_exists($id, $this->poll_list)) {
			return (object) $this->poll_list[$id];
		} // end if
	} // end function

	/**
	* returns whether there is a previous page possible or not
	*
	* @desc returns whether there is a previous page possible or not
	* @return (boolean) $prevpage
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPrevPage() {
		return (boolean) $this->prevpage;
	} // end function

	/**
	* returns whether there is a next page possible or not
	*
	* @desc returns whether there is a next page possible or not
	* @return (boolean) $nextpage
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getNextPage() {
		return (boolean) $this->nextpage;
	} // end function

	/**
	* returns the array with the number of pages
	*
	* @desc returns the array with the number of pages
	* @return (boolean) $pages
	* @access public
	* @since 1.001 - 2003/04/21
	*/
	function getPages() {
		return (array) $this->pages;
	} // end function
} // end class PollList
?>
