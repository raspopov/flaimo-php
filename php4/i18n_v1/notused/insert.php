<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'translator_testdb';
$conn = mysql_pconnect($host, $user, $password) or die ('Datenbankverbindung nicht möglich! ' . mysql_error());
mysql_select_db($dbname) or die ('Datenbank konnte nicht ausgewählt werden! ' . mysql_error());

/*
$fields = mysql_list_fields($dbname, 'flp_translator', $conn);
$columns = mysql_num_fields($fields);

for ($i = 0; $i < $columns; $i++) {
   echo mysql_field_name($fields, $i) . "\n";
}

*/


			$query = 'SELECT de FROM flp_translator WHERE string = "Yess"';
			$result =  mysql_query($query, $conn) or die ('Request not possible! SQL Statement: ' . $query . ' / ' . mysql_error());
			$field = mysql_result($result, 0, 0);

echo $field;

/*
if ($languagefile = (array) file('lang_main.inc')) {
	foreach ($languagefile as $key => $value) {
		if (strlen(trim($value)) > 0) {
			list($lang_array_key, $lang_array_value) = split(' = ', $value);
			if ((strlen(trim($lang_array_key)) > 0) && (strlen(trim($lang_array_value)) > 0)) {

				$query = 'UPDATE flp_translator SET it="' . trim($lang_array_value) . '" WHERE string = "' . trim($lang_array_key) . '"';
				//$query = 'INSERT IGNORE INTO flp_translator (string) VALUES ("' . trim($lang_array_key) . '")';
				$sql =  mysql_query($query, $conn) or die ('Abfrage war ungültig! SQL Statement: ' . $query . ' / ' . mysql_error());


			}
		} // end if
	} // end foreach
	unset($languagefile);
} // end if
*/




/*
$query = '';

$sql =  mysql_query($query, $conn) or die ('Abfrage war ungültig! SQL Statement: ' . $query . ' / ' . mysql_error());
*/
?>