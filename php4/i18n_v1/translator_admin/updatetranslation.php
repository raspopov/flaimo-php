<?php
require_once('header.inc.php');
include_once('class.ReloadPreventer.inc.php');
include_once('../class.Language.inc.php');
$rp = (object) new ReloadPreventer();

if (isset($_POST['Submit_return']) || isset($_POST['Submit_next'])) { // only check if form is submitted
	if ($rp->isValid() == true) { // check if tokens match up
		$translation = (string) trim($_POST['updatetranslation']);
		$string = (string) trim($_POST['string']);
		$lang = (string) trim($_POST['col']);

		$query = (string) 'UPDATE IGNORE ' .  $db_table . ' SET ' . $lang . '="' . $translation . '", lastupdate=UNIX_TIMESTAMP() WHERE string="' . $string . '"';
		$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());

		$rp->setTokenSession();
		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );

		if (isset($_POST['Submit_next'])) {
			$query = (string) 'SELECT SQL_SMALL_RESULT string FROM ' . $db_table . ' WHERE ISNULL(' . $lang . ') ORDER BY string ASC LIMIT 1';
			$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());

			if (mysql_num_rows ($result) > 0) {
				$newstring = (string) mysql_result($result, 0, 0);
				header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/edittranslation.php?string=' . urlencode($newstring) . '&col=' . $lang . '&' . session_name() . '=' . session_id());
			} else {
				header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/editlang.php?col=' . $lang . '&' . session_name() . '=' . session_id());
			}
		} else {
			header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/editlang.php?col=' . $lang . '&' . session_name() . '=' . session_id());
		} // end if
	} else {
		die ('Error: Data sent twice!');
	} // end if
} // end if
ob_end_flush();
?>