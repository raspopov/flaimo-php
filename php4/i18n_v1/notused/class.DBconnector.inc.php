<?php

@require_once('class.i18n.inc.php');

class DBconnector extends i18n {

	var $conn;
	var $host = 'localhost';
	var $user = 'root';
	var $password = '';
	var $database = 'translator_testdb';
	var $db_table = 'flp_translator';


	function DBconnector() {
		parent::i18n();
		$this->setConnection();
	} // end constructor


	function setConnection() {
		if (!isset($this->conn)) {
			parent::readINIsettings();
			if (isset($GLOBALS['_I18N_ini_settings'])) {
				$this->host 	=& $GLOBALS['_I18N_ini_settings']['DBconnector']['host'];
				$this->user 	=& $GLOBALS['_I18N_ini_settings']['DBconnector']['user'];
				$this->password =& $GLOBALS['_I18N_ini_settings']['DBconnector']['password'];
				$this->database =& $GLOBALS['_I18N_ini_settings']['DBconnector']['database'];
				$this->db_table	=& $GLOBALS['_I18N_ini_settings']['DBconnector']['translation_table'];
			} // end if
			$this->conn = mysql_pconnect($this->host, $this->user, $this->password) or die ('Connection not possible! => ' . mysql_error());
			mysql_select_db($this->database) or die ('Couldn\'t connect to "' . $this->database . '" => ' . mysql_error());
		} // end if
	} // end function

	function DBQuery($query) {
    	return mysql_query($query, $this->conn) or die (mysql_error());
  	}

	function RSResult($query,$pointer,$field_nr) {
    	return mysql_result($query,$pointer,$field_nr);
    }

	function RSNumRows($result) {
    	return mysql_num_rows($result);
    }

	function RSFetchRow($query) {
    	return mysql_fetch_row($query);
    }

	function DBFreeResult($query) {
    	mysql_free_result($query);
    }

	function DBClose() {
    	mysql_close($this->conn);
    }

	function RSSingleValue($query) {
	    $rs_query = $this->DBQuery($query);
	    $result = $this->RSResult($rs_query, 0, 0);

	    if ($this->RSNumRows($result) > 0) {
		    $this->DBFreeResult($rs_query);
		    return $result;
	    } else {
		    $this->DBFreeResult($rs_query);
		    return (boolean) FALSE;
	    }
    }


} // end class DBconnector
?>