<?php

class DBclass // Verbindung zur Datenbank
  {
  var $host;
  var $user;
  var $password;
  var $dbname;
  var $db_link = false;

  function DBclass() //Konstruktor ruf db_connect() auf
    {

    $this->setHost('localhost');
    $this->setUser('root');
    $this->setPassword('');
    $this->setDBname('newssystem');
   /*    
    $this->setHost('localhost');
    $this->setUser('flaimo');
    $this->setPassword('aqeo');
    $this->setDBname('flaimo'); */ 

    $this->db_connect();
    }

  function getHost() { return $this->host; }
  function getUser() { return $this->user; }
  function getPassword() { return $this->password; }
  function getDBname() { return $this->dbname; }
  
  function setHost($value) { $this->host = (string) $value; }
  function setUser($value) { $this->user = (string) $value; }
  function setPassword($value) { $this->password = (string) $value; }
  function setDBname($value) { $this->dbname = (string) $value; }

  function db_connect() //baut die verbindung auf 
    {
    $this->db_link = @mysql_pconnect($this->getHost(), $this->getUser(), $this->getPassword()) or die ('Datenbankverbindung nicht möglich! ' . mysql_error());
    $this->db_choose();
    }
  
  function db_choose() // wählt die datenbank
    {
    @mysql_select_db($this->getDBname()) or die ('Datenbank konnte nicht ausgewählt werden! ' . mysql_error());
    }
  
  function db_query($query) // sendet einen query
    {
    $res = @mysql_query($query, $this->db_link) or die ('Abfrage war ungültig! SQL Statement: ' . $query . ' / ' . mysql_error());
    return $res;
    }
 
  function rs_result($query,$pointer,$field_nr) // sendet einen query
    {
    $field = mysql_result($query,$pointer,$field_nr);
    return $field;
    }
 
  function rs_num_rows($query) // sendet einen query
    {
    $number = mysql_num_rows($query);
    return $number;
    }  
  
  function rs_fetch_row($query) // sendet einen query
    {
    $number = mysql_fetch_row($query);
    return $number;
    }    

  function rs_fetch_object($query) // sendet einen query
    {
    $object = mysql_fetch_object($query);
    return $object;
    } 
    
  function rs_fetch_assoc($query) // sendet einen query
    {
    $row = mysql_fetch_assoc($query);
    return $row;
    } 
  
  function rs_data_seek($sql,$pointer = 0) // sendet einen query
    {
    mysql_data_seek($sql,$pointer);
    } 
  
  function db_freeresult($query) // sendet einen query
    {
    mysql_free_result($query);
    }
  
  function db_close() // sendet einen query
    {
    mysql_close($this->db_link);
    }
  
  function rs_single($query) // sendet einen query
    {
    $rs_query = $this->db_query($query); 
    $result = $this->rs_result($rs_query, 0, 0);
    return $result;
    $db->db_freeresult($rs_query); 
    }
  } // End Class db_class

?>