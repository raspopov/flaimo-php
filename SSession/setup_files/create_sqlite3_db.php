<?php
/*
function setSQLiteConn($db_name) {
	static $conn;
	if (!isset($conn)) {
		$conn = new PDO('sqlite:' . $db_name);
	} // end if
	return $conn;
} // end function
*/

error_reporting(E_ALL);
// install cript für SQLite
$db_name 	= 'ssession.sqlite3';
$table_name = 'ssession';

if ($db = new PDO('sqlite:' . $db_name)) {

	 $db->query('DROP TABLE ' . $table_name);
	 $db->query('DROP VIEW allfields');
	 $db->query('CREATE TABLE ' . $table_name . ' (id varchar(32) NOT NULL PRIMARY KEY, access int(10), data text)');

	 $db->query('CREATE UNIQUE INDEX id ON ' . $table_name . ' (id ASC)');
	 $db->query('CREATE INDEX access ON ' . $table_name . ' (access ASC)');
	 
	// sqlite_query($db,'CREATE VIEW allfields AS SELECT * FROM ' . $table_name . ' WHERE string=""');
	 $db->query('VACUUM ' . $table_name);

	 echo 'table "' . $db_name . '.' . $table_name . '" created';

    //$result = sqlite_query($db,'select * from ' . $table_name);
   // var_dump(sqlite_fetch_array($result));
	// sqlite_unbuffered_query() verwenden
} else {
    die ($sqliteerror);
}

?>
