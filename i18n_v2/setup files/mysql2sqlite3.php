<?php
error_reporting(E_ALL);
// install cript für SQLite
$db_name 	= 'translations.sqlite3';
$table_name = 'flp_translator';

if ($db = new PDO('sqlite:' . $db_name)) {

    $db->query('DROP TABLE ' . $table_name);
    $db->query('DROP VIEW allfields');
    $db->query('CREATE TABLE ' . $table_name . ' (namespace varchar(150) NOT NULL DEFAULT "lang_main", string varchar(150) NOT NULL PRIMARY KEY, en text, de text, de_at text, fr text, es text, it text, ru text, ar text, lastupdate int(10))');


	$link = mysqli_connect("127.0.0.1", "root", "maxmobil", "translator_test");
	$result = mysqli_query ($link, 'set character set utf8');
	$result = mysqli_query ($link, 'select namespace, string, en, de, `de-at`, fr, es, it, ru, ar, lastupdate from flp_translator');

	while ($row = mysqli_fetch_row ($result)) {
		$sql = "INSERT INTO " . $table_name . " VALUES ('" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "','" . $row[7] . "','" . $row[8] . "','" . $row[9] . "'," . $row[10] . ")";
		echo $sql , '<br>';
		$db->query($sql);
} // end while

	$db->query('CREATE INDEX namespace ON ' . $table_name . ' (namespace ASC)');
	$db->query('CREATE UNIQUE INDEX string ON ' . $table_name . ' (string ASC)');
	$db->query('CREATE VIEW allfields AS SELECT * FROM ' . $table_name . ' WHERE string=""');
	$db->query('VACUUM ' . $table_name);

	echo 'table "' . $db_name . '.' . $table_name . '" created';

    //$result = sqlite_query($db,'select * from ' . $table_name);
   // var_dump(sqlite_fetch_array($result));
	// sqlite_unbuffered_query() verwenden
} else {
    die ($sqliteerror);
}
?>
