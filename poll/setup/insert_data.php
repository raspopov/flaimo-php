<?php
$db_name 	= 'polls.sqlite';
if ($db = sqlite_popen($db_name, 0666, $sqliteerror)) {
/**/
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (0, "Verlagsgruppe News", 0, "default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (1, "News Networld", 99, "networld_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (1, "News", 98, "news_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (1, "E-Media", 97, "emedia_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (1, "Profil", 96, "profil_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (2, "Politik", 99, "networld_politik_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (2, "Leute", 98, "networld_leute_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (2, "Sport", 97, "networld_sport_default")';
$sqls[] = 'INSERT INTO categories (parent, name, position, template) VALUES (7, "Fernsehen", 99, "networld_leute_default")';

$sqls[] = 'INSERT INTO polls (category, title, description, created, start_poll, stop_poll, flags, revote, template, button_label, cp, cp_type, channel, L1, L2) VALUES (7, "Die bessere Show", "Welche Show ist besser: Jungle-Camp oder die Alm?", ' . time() . ', 0, 2147483647, 1, 0, "", "Abstimmen", "tvshow", "u", 0, 1, 1)';
$sqls[] = 'INSERT INTO polls (category, title, description, created, start_poll, stop_poll, flags, revote, template, button_label, cp, cp_type, channel, L1, L2) VALUES (7, "Heidis Lover", "Falvio oder Seal??", ' . time() . ', 0, 2147483647, 6, 30, "heidi_lover", "Abstimmen", "heidi", "u", 0, 1, 1)';

$sqls[] = 'INSERT INTO polloptions (poll, title, description, votes, lastvote, position, flags) VALUES (1, "Junglecamp", "Die Sendung auf RTL", 1, 0, 99, 1)';
$sqls[] = 'INSERT INTO polloptions (poll, title, description, votes, lastvote, position, flags) VALUES (1, "Die Alm", "Die Sendung auf Pro Sieben", 1, 0, 98, 1)';
$sqls[] = 'INSERT INTO polloptions (poll, title, description, votes, lastvote, position, flags) VALUES (1, "Ich schau kein TV", "", 1, 0, 98, 0)';
$sqls[] = 'INSERT INTO polloptions (poll, title, description, votes, lastvote, position, flags) VALUES (2, "Seal", "Von Beruf Sänger", 1, 0, 98, 1)';
$sqls[] = 'INSERT INTO polloptions (poll, title, description, votes, lastvote, position, flags) VALUES (2, "Flavio", "Von Beruf Rennteam-Leiter", 1, 0, 99, 1)';


$sqls[] = 'INSERT INTO surveys (name, description, created, start_survey, stop_survey, flags, revote, cp, channel) VALUES ("Die große Befragung", "Hier könnte eine Beschreibung der grßen Befragung stehen", ' . time() . ', 0, 2147483647, 1, 0, "bigsurvey", 0)';

$sqls[] = 'INSERT INTO survey_polls (survey_id, poll_id, flags, position) VALUES (1, 2, 1, 99)';
$sqls[] = 'INSERT INTO survey_polls (survey_id, poll_id, flags, position) VALUES (1, 1, 0, 98)';
$sqls[] = 'INSERT INTO survey_polls (survey_id, poll_id, flags, position) VALUES (1, 5, 0, 97)';
$sqls[] = 'INSERT INTO survey_polls (survey_id, poll_id, flags, position) VALUES (1, 6, 1, 96)';

foreach ($sqls as $sql) {
	 sqlite_query($db, $sql);
}

} else {
    die ($sqliteerror);
}


?>