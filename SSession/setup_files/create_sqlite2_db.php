<?php
error_reporting(E_ALL);
// install cript für SQLite
$db_name 	= 'ssession.sqlite2';
$table_name = 'ssession';

if ($db = sqlite_popen($db_name, 0777, $sqliteerror)) {

	 @sqlite_query($db,'DROP TABLE ' . $table_name);
	 @sqlite_query($db,'DROP VIEW allfields');


	 sqlite_query($db,'CREATE TABLE ' . $table_name . ' (id varchar(32) NOT NULL PRIMARY KEY, access int(10), data text)', $sqliteerror);
	 echo $sqliteerror;
	 sqlite_query($db,'CREATE UNIQUE INDEX id ON ' . $table_name . ' (id ASC)');
	 sqlite_query($db,'CREATE INDEX access ON ' . $table_name . ' (access ASC)');
	 sqlite_query($db,'VACUUM ' . $table_name);

/*
	 sqlite_query($db,'CREATE UNIQUE INDEX id ON ' . $table_name . ' (id ASC)');
	// sqlite_query($db,'CREATE VIEW allfields AS SELECT * FROM ' . $table_name . ' WHERE string=""');


	 echo 'table "' . $db_name . '.' . $table_name . '" created';

    //$result = sqlite_query($db,'select * from ' . $table_name);
   // var_dump(sqlite_fetch_array($result));
	// sqlite_unbuffered_query() verwenden
*/
} else {
    die ($sqliteerror);
}

?>
