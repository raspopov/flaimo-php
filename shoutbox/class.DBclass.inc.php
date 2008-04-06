<?php

class DBclass {
    var $host;
    var $user;
    var $password;
    var $dbname;
    var $db_link = false;

    function DBclass() {
        $this->setHost('localhost');
        $this->setUser('root');
        $this->setPassword('');
        $this->setDBname('shoutbox');
        /*
        $this->setHost('localhost');
        $this->setUser('flaimo');
        $this->setPassword('Telda4za');
        $this->setDBname('flaimo');
        */
        $this->db_connect();
    } // end function

    function getHost() {
        return $this->host;
    }
        
    function getUser() {
        return $this->user;
    }

    function getPassword() {
        return $this->password;
    }
    
    function getDBname() {
        return $this->dbname;
    }
  
    function setHost($value) {
        $this->host = (string) $value;
    }
    
    function setUser($value) {
        $this->user = (string) $value;
    }
    
    function setPassword($value) {
        $this->password = (string) $value;
    }
    
    function setDBname($value) {
        $this->dbname = (string) $value;
    }

    function db_connect() {
        $this->db_link = @mysql_pconnect($this->getHost(), $this->getUser(), $this->getPassword()) or die ('Datenbankverbindung nicht möglich! ' . mysql_error());
        $this->db_choose();
    }
  
    function db_choose() {
        @mysql_select_db($this->getDBname()) or die ('Datenbank konnte nicht ausgewählt werden! ' . mysql_error());
    }
  
    function db_query($query) {
        $res = @mysql_query($query, $this->db_link) or die ('Abfrage war ungültig! SQL Statement: ' . $query . ' / ' . mysql_error());
        return $res;
    }
 
    function rs_result($query,$pointer,$field_nr) {
        $field = mysql_result($query,$pointer,$field_nr);
        return $field;
    }
 
    function rs_num_rows($query) {
        $number = mysql_num_rows($query);
        return $number;
    }  
  
    function rs_fetch_row($query) {
        $number = mysql_fetch_row($query);
        return $number;
    }    

    function rs_fetch_object($query) {
        $object = mysql_fetch_object($query);
        return $object;
    } 
    
    function rs_fetch_assoc($query) {
        $row = mysql_fetch_assoc($query);
        return $row;
    } 
  
    function rs_data_seek($sql,$pointer = 0) {
        mysql_data_seek($sql,$pointer);
    } 
  
    function db_freeresult($query) {
        mysql_free_result($query);
    }
  
    function db_close() {
        mysql_close($this->db_link);
    }
  
    function rs_single($query) {
        $rs_query = $this->db_query($query);
        $result = $this->rs_result($rs_query, 0, 0);
        return $result;
        $db->db_freeresult($rs_query);
    }
} // End Class db_class
?>
