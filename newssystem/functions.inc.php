<?php
function user_online24() {
    $zeitspanne = 300; // in Sekunden
    $REMOTE_ADDR = getenv("REMOTE_ADDR"); 

    mysql_query("DELETE FROM tbl_useronline WHERE expire < ".time().""); 
    mysql_query("UPDATE tbl_useronline SET expire = '".(time()+$zeitspanne)."' WHERE ip='".$REMOTE_ADDR."'"); // Versucht einen Datensatz zu �ndern 

    if(!mysql_affected_rows()) { // Bei Mi�erfolg wird ein neuer Datensatz eingef�gt
        mysql_query("INSERT INTO tbl_useronline (ip,expire) VALUES ('$REMOTE_ADDR','".(time()+$zeitspanne)."')");
    }

    $result = mysql_query("SELECT count(*) FROM tbl_useronline"); // Ermittelt aktive User 
    return mysql_result($result,0); 
}
?>
