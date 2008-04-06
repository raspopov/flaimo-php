<?php
$db_name 	= 'polls.sqlite';
if ($db = sqlite_popen($db_name, 0775, $sqliteerror)) {

/*

bei list objekten:  sqlite_fetch_single()  verwenden

   $sql = "SELECT id FROM sometable WHERE id = 42";
   $res = sqlite_unbuffered_query($dbhandle, $sql);

   if (sqlite_num_rows($res) > 0) {
       echo sqlite_fetch_single($res); // 42
   }

   sqlite_close($dbhandle);

   --------------------------------------


		$result = sqlite_single_query(parent::getConn(), $sql, TRUE);
		print_r($result);


		BEI EINZELFELDER NEHMEN

__________________________________


sqlite_last_insert_rowid  Returns


__________________________________________



bei multivotes:  $sqls[] = 'UPDATE polloptions SET votes = votes + 1 WHERE id IN (1, 2)';


bei poll objekt erzeugung num_rows verwenden und flag "no poll" auf true setzen

*/


	@sqlite_query($db,'DROP TABLE categories');
	sqlite_query($db,'CREATE TABLE categories (id integer NOT NULL PRIMARY KEY, parent integer NOT NULL DEFAULT 0, name varchar(50) NOT NULL DEFAULT "Kategorie", position integer NOT NULL DEFAULT 0, template varchar(120) NOT NULL DEFAULT "default")');
	sqlite_query($db,'CREATE UNIQUE INDEX category_id ON categories (id ASC)');
	sqlite_query($db,'CREATE INDEX parent ON categories (parent ASC)');
	sqlite_query($db,'CREATE INDEX category_position ON categories (position DESC)');


	@sqlite_query($db,'DROP TABLE polls');
	sqlite_query($db,'CREATE TABLE polls (id integer NOT NULL PRIMARY KEY, category integer NOT NULL DEFAULT 0, title varchar(150) NOT NULL, description text, created integer NOT NULL DEFAULT 0, start_poll integer NOT NULL DEFAULT 0, stop_poll integer NOT NULL DEFAULT 2147483647, flags integer NOT NULL DEFAULT 0, revote integer NOT NULL DEFAULT 0, template varchar(120) NOT NULL DEFAULT "default", button_label varchar(80) NOT NULL DEFAULT "Abstimmen", cp varchar(100) NOT NULL DEFAULT "umfrage", cp_type char(1) NOT NULL DEFAULT "u", channel integer NOT NULL DEFAULT 0, L1 integer NOT NULL DEFAULT 1, L2 integer NOT NULL DEFAULT 1)');
	sqlite_query($db,'CREATE UNIQUE INDEX poll_id ON polls (id ASC)');
	sqlite_query($db,'CREATE INDEX category ON polls (category ASC)');
	sqlite_query($db,'CREATE INDEX created ON polls (created DESC)');
	sqlite_query($db,'CREATE INDEX start_poll ON polls (start_poll ASC)');
	sqlite_query($db,'CREATE INDEX stop_poll ON polls (stop_poll ASC)');

	@sqlite_query($db,'DROP TABLE polloptions');
	sqlite_query($db,'CREATE TABLE polloptions (id integer NOT NULL PRIMARY KEY, poll integer NOT NULL DEFAULT 0, title varchar(120) NOT NULL DEFAULT "Poll title", description text, votes integer NOT NULL DEFAULT 1, lastvote integer NOT NULL DEFAULT 0, position integer NOT NULL DEFAULT 0, flags integer NOT NULL DEFAULT 1)');
	sqlite_query($db,'CREATE UNIQUE INDEX po_id ON polloptions (id ASC)');
	sqlite_query($db,'CREATE INDEX poll ON polloptions (poll DESC)');
	sqlite_query($db,'CREATE INDEX po_position ON polloptions (position DESC)');
	sqlite_query($db,'CREATE INDEX votes ON polloptions (votes DESC)');
	sqlite_query($db,'CREATE INDEX lastvote ON polloptions (lastvote DESC)');

	@sqlite_query($db,'DROP TABLE surveys');
	sqlite_query($db,'CREATE TABLE surveys (id integer NOT NULL PRIMARY KEY, name varchar(150) NOT NULL, description text, created integer NOT NULL DEFAULT 0, start_survey integer NOT NULL DEFAULT 0, stop_survey integer NOT NULL DEFAULT 2147483647, flags integer NOT NULL DEFAULT 0, revote integer NOT NULL DEFAULT 0, cp varchar(100) NOT NULL DEFAULT "umfrage", channel integer NOT NULL DEFAULT 0)');
	sqlite_query($db,'CREATE UNIQUE INDEX survey_id ON surveys (id ASC)');
	sqlite_query($db,'CREATE INDEX s_created ON surveys (created DESC)');
	sqlite_query($db,'CREATE INDEX start_survey ON surveys (start_survey ASC)');
	sqlite_query($db,'CREATE INDEX stop_survey ON surveys (stop_survey ASC)');

	@sqlite_query($db,'DROP TABLE survey_polls');
	sqlite_query($db,'CREATE TABLE survey_polls (survey_id integer NOT NULL DEFAULT 0, poll_id integer NOT NULL DEFAULT 0, flags integer NOT NULL DEFAULT 0, position integer NOT NULL DEFAULT 0)');
	sqlite_query($db,'CREATE INDEX survey ON survey_polls (survey_id DESC)');
} else {
    die ($sqliteerror);
}


?>