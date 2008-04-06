<?php
/*
2002 (c) Michael Wimmer (flaimo@gmx.net | http://flaimo.com)
-------------------------------------------------------------------

-------------------------------------------------------------------
*/

class Helpers {
  
    /* V A R I A B L E S */
  
    var $tbl_shoutbox;
  
    /* C O N S T R U C T O R */
  
    function Helpers() {
        $this->setTableNames();
    } // end function

    /* F U N C T I O N S */
  
    function setTableNames() {
        $this->tbl_shoutbox = (string) 'tbl_shoutbox';
    } // end function  

    function getTableName($table) {
        return (string) $this->$table;
    } 
} // End Class FormatDate
?>
