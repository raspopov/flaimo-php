<?php
include_once('class.DBclass.inc.php');
include_once('class.Helpers.inc.php');
include_once('class.ShoutBoxEntry.inc.php');

class ShoutBox {

    /* V A R I A B L E N */

    var $limit;
    var $helpers;
    var $db;
    var $dataLoaded;
    var $shoutboxEntries;

    function ShoutBox($entries = 5) { // Konstruktor
        $this->setLimit(((!isset($entries) || strlen(trim($entries)) > 0) ? 5 : $entries));
    } // end function

    /* F U N K T I O N E N */

    function getLimit() {
        return $this->limit;
    } // end function

    function setLimit($int) {
        $this->limit = (int) $int;
    } // end function

    function setDBconnection() {
        if (!isset($this->db)) {
            $this->db = (object) new DBclass();
        } // end if
    }  // end function

    function setHelpers() {
        if (!isset($this->helpers)) {
            $this->helpers = (object) new Helpers();
        } // end if
    }  // end function

    function setShoutBoxEntries() {
        if ($this->dataLoaded == FALSE) {
            $this->setDBconnection();
            $this->setHelpers();

            $sql  = (string) "SELECT id";
            $sql .= (string) " FROM " . $this->helpers->getTableName('tbl_shoutbox');
            $sql .= (string) " ORDER BY id DESC";
            $sql .= (string) " LIMIT " . $this->limit;

            $rs_shouts = $this->db->db_query($sql);
            $this->shoutboxEntries = (array) array();
            if ($this->db->rs_num_rows($rs_shouts) > 0) {
                while ($rs = $this->db->rs_fetch_assoc($rs_shouts)) {
                    $shout_id = (int) $rs['id'];
                    $shout = (object) new ShoutBoxEntry($shout_id);
                    $this->shoutboxEntries[$shout_id] = $shout;
                } // end while
            } // end if
            $this->db->db_freeresult($rs_shouts);
        } // end if
    } // end function

    function getShoutboxEntries() {
        $this->setShoutBoxEntries();
        return (array) $this->shoutboxEntries;
    } // end function

    function getShoutboxEntry($id = FALSE) {
        if ($id != false && is_int($id) && array_key_exists($id, $this->shoutboxEntries)) {
            return (object) $this->shoutboxEntries[$id];
        } else {
            return (string) 'no_shout';
        } // end if
    } // end function

    function insertShout($name, $mail = '', $message) {
        if (strlen(trim($name)) == 0 || strlen(trim($message)) == 0 || isset($_COOKIE['shoutbox'])) {
            return (boolean) FALSE;
        } else {
            $this->setDBconnection();
            $this->setHelpers();
            $sql  = (string) "INSERT INTO " . $this->helpers->getTableName('tbl_shoutbox') . " (name, mail, message, date, ip)";
            $sql .= (string) " VALUES ('" . trim($name) . "', '" . trim($mail) . "', '" . trim($message) . "', '" . date('Y-m-d H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "')";
            $insert_rs = $this->db->db_query($sql);
            @setcookie('shoutbox','y',time()+360);
            @setcookie('cook_name', trim($name), time()+31536000);
            @setcookie('cook_email', trim($mail), time()+31536000);
            return (boolean) true;
        } // end if
    } // end function
} // end class ShoutBox
?>
