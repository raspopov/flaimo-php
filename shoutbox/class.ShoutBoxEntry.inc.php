<?php
include_once('class.DBclass.inc.php');
include_once('class.Helpers.inc.php');

class ShoutBoxEntry {

    /* V A R I A B L E S */

    var $db;
    var $helpers;
    var $isValidShoutBoxEntry;
    var $dataLoaded;

    var $id;
    var $name;
    var $authormail;
    var $message;
    var $sbdate;

    function ShoutBoxEntry($id = 1) { // Konstruktor
        $this->id = (int) $id;
        $this->validateID($id);
    } // end function

    /* F U N C T I O N S */

    function validateID($id) {
        $this->isValidShoutBoxEntry = (boolean) TRUE;
        if (!is_int($id) || $id == 0) {
            $this->isValidShoutBoxEntry = (boolean) FALSE;
        } // end if
    }  // end function

    function emptyShoutBoxEntry() {
        $this->id           = (int) 0;
        $this->name         = (string) $string;
        $this->authormail   = (string) '';
        $this->message      = (string) $string;
        $this->sbdate       = (string) date('Y-m-d H:i:s',time());
    }  // end function

    function setDBconnection() {
        if (!isset($this->db)) {
            $this->db = (object) new DBclass();
        }
    } // end function

    function setHelpers() {
        if (!isset($this->helpers)) { $this->helpers = (object) new Helpers(); }
    }  // end function

    function setShoutBoxEntry() {
        if ($this->dataLoaded == FALSE) {
            if ($this->isValidShoutBoxEntry == TRUE) {
                $this->setDBconnection();
                $this->setHelpers();
                $sql  = (string) "SELECT id, name, mail, message, date";
                $sql .= (string) " FROM " . $this->helpers->getTableName('tbl_shoutbox');
                $sql .= (string) " WHERE id = " . $this->id;
                $rs_sbentry = $this->db->db_query($sql);
                if ($this->db->rs_num_rows($rs_sbentry) > 0) {
                    $this->dataLoaded == TRUE;
                    while ($sbentry = $this->db->rs_fetch_assoc($rs_sbentry)) {
                        $this->id           = (int) $sbentry['id'];
                        $this->name         = (string) $sbentry['name'];
                        $this->authormail   = (string) $sbentry['mail'];
                        $this->message      = (string) $sbentry['message'];
                        $this->sbdate       = (string) $sbentry['date'];
                    } // end while
                } else {
                    $this->isValidShoutBoxEntry = (boolean) false;
                    $this->emptyShoutBoxEntry('no_entry_found');
                } // end if
            } else {
                $this->isValidShoutBoxEntry = (boolean) false;
                $this->emptyShoutBoxEntry('wrong_input_for_id');
            } // end if
            $this->db->db_freeresult($rs_sbentry);
        } // end if
    } // end function

    function getID() {
        $this->setShoutBoxEntry();
        return (int) $this->id;
    } // end function
    function getName() {
        $this->setShoutBoxEntry();
        return (string) $this->name;
    } // end function
    function getMail() {
        $this->setShoutBoxEntry();
        return (string) $this->authormail;
    } // end function
    function getMessage() {
        $this->setShoutBoxEntry();
        return (string) $this->message;
    } // end function
    function getSBDate() {
        $this->setShoutBoxEntry();
        return (string) $this->sbdate;
    } // end function
} // end class ShoutBoxEntry
?>
