<?php
require_once('header.inc.php');
include_once('class.ReloadPreventer.inc.php');
include_once('../class.Language.inc.php');
$lg = (object) new Language();
$rp = (object) new ReloadPreventer();

if (isset($_POST['SubmitLang']) && isset($_POST['newlang'])) { // only check if form is submitted
	if ($rp->isValid() == TRUE) { // check if tokens match up
		$newlang = (string) str_replace('-','_',strtolower(trim($_POST['newlang'])));

		$fields = mysql_list_fields($database, $db_table, $conn);
		$columns = mysql_num_fields($fields);
		for ($i = 0; $i < $columns; $i++) {
			$name = (string) trim( mysql_field_name($fields, $i));
			if ($name == $newlang) {
				die ('Language already exists');
			}
		} // end for

		if ($lg->IsValidLanguageCode($newlang) == TRUE) {
			$query = 'ALTER TABLE ' . $db_table . ' ADD ' . $newlang . ' mediumtext';
			$result = mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
		} else {
			die ('Error: Not an iso-code!');
		} // end if

		$rp->setTokenSession();
		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$port = (string) (($_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '' );
		header('Location:http://' . $_SERVER['SERVER_NAME'] . $port . $path_parts["dirname"] . '/index.php?' . session_name() . '=' . session_id());
	} else {
		die ('Error: Data sent twice!');
	} // end if
} // end if
ob_end_flush();
?>