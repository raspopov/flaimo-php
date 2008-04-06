<?php
ob_start();
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');
Header('Cache-Control: must-revalidate');
Header('Expires: ' . gmdate("D, d M Y H:i:s", time() + (60 * 60 * 20)) . ' GMT');
session_start();

require_once('class.PollList.inc.php');
require_once('class.PollUser.inc.php');
require_once('class.Helpers.inc.php');

/* Start der Liste definieren */
if (isset($_POST['startlisting'])) {
	$startlisting = (int) $_POST['startlisting'];
} elseif (isset($_GET['startlisting'])) {
	$startlisting = (int) $_GET['startlisting'];
} // end if

if (!isset($startlisting) || $startlisting < 0) {
	$startlisting = (int) 0;
} // end if


/* Aktuelle Poll ID definieren */
if (isset($_POST['id'])) {
	$current_vote_id = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
	$current_vote_id = (int) $_GET['id'];
} // end if

$helpers = new Helpers();

if (!isset($current_vote_id) || $current_vote_id < 0 || $current_vote_id > ($helpers->getMaxID())) {
	$current_vote_id = (int) $helpers->getMaxID();
} // end if

$listsize = 10;

/* URL Helferlein definieren */
$session_url_attach = (string) session_name() . '=' . session_id();
$startlisting_url_attach = (string) 'startlisting=' . $startlisting;
$current_vote_id_attach = 'id=' . $current_vote_id;

?>

<html>
<head><title>Test</title></head>
<body>

<?php
$current_poll = new Poll($current_vote_id);

/* E I N T R A G E N   V O N   K O M M E N T A R E N */

if (isset($_POST['commentsend'])) { // Überprüfung des Formulares/Cookies und Eintragen eines Kommentars
	if ($current_poll->insertComment($_POST['comment'], $_POST['name'], $_POST['email'], $_POST['showvote']) === TRUE) {
      $feedback_insert_comment = 'eingetragen';
    } else {
      $feedback_insert_comment = 'nicht eingetragen';
    } // end if
} else {
	$feedback_insert_comment = '';
} // end if
echo $feedback_insert_comment;

/* Eintragen von Abstimmungsergebniss */

if (isset($_POST['vote'])) {
	if ($current_poll->insertVote($_POST['choise']) === TRUE) {
		$feedback_insert_pollvote = 'abgestimmt';
	} else {
		$feedback_insert_pollvote = 'nicht abgestimmt';
	} // end if
} else {
	$feedback_insert_pollvote = '';
} // end if

/* Ausgabe Poll */

echo 'ID: ' . $current_vote_id . '<br />';
echo 'Frage: ' . $current_poll->getQuestion() . '<br />';
echo 'Beschreibung: ' . $current_poll->getDescription() . '<br />';
echo 'Datum: ' . $current_poll->getPollDate() . '<br />';

if ($current_poll->getClosed() == TRUE) {
	echo ' (Beendet)<br />';
}
$user = (object) new PollUser();
if ((isset($_POST['vote'])) || ($user->isPollVoted($current_vote_id) == TRUE)) {
	$show_result = (boolean) TRUE;
} else {
	$show_result = (boolean) FALSE;
} // end if

$current_poll_optionlist = $current_poll->getPollOptionKeys();
if (count($current_poll_optionlist) > 0) { // Auswertung und Ausgabe der Antworten
	if ($show_result === TRUE) {
		/* E R G E B N I S A U S G A B E */
		echo '<br />Ergebniss<br />';
		echo 'Stimmen: ' . $current_poll->getSumVotes() . '<br />';
		foreach ($current_poll_optionlist as $current_poll_option_id) {
			$current_poll_option = $current_poll->getPollOption($current_poll_option_id);
			echo $current_poll_option->getAnswer() . ' (' . $current_poll_option->getVotes() . ' Stimmen)<br />';
		} // end foreach
	} else {
		/* Antwortmöglichkeiten */
		echo '<br />Antwortmöglichkeiten<br />';
		echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';

		srand (microtime() * 1000000);
		shuffle($current_poll_optionlist);
		foreach ($current_poll_optionlist as $current_poll_option_id) {
			$current_poll_option = $current_poll->getPollOption($current_poll_option_id);
			if ($current_poll->getIsMultible() === FALSE) { // Unterschiedliche Auswahlmöglichkeiten falls Mehrfachauswahl...
          		echo '<input type="radio" name="choise" value="' . $current_poll_option->getID() . '" id="choise' . $current_poll_option->getID() . '" />';
          	} else {
          		echo '<input type="checkbox" name="choise[' . $current_poll_option->getID() . ']" value="' . $current_poll_option->getID() . '" id="choise' . $current_poll_option->getID() . '" />';
			} // end if
			echo '<label for="choise' . $current_poll_option->getID() . '" >' . $current_poll_option->getAnswer() . '</label><br />';
		} // end foreach
		echo '<input type="hidden" name="id" value="' . $current_poll->getID() . '" />';
		echo '<input type="hidden" name="vote" value="' . $current_poll->getID() . '" />';
		echo '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />';
		echo '<input type="submit" value="Abstimmen" />';
		echo '</form>';
	} // end if
} // end if

/* Kommentare */

echo '<br />Kommentare<br />----------------<br />';
$current_poll_comment_list = $current_poll->getPollCommentKeys();
if (count($current_poll_comment_list) > 0) {
	foreach ($current_poll_comment_list as $commentID) {
		$comment = $current_poll->getComment($commentID);
		$comment_author = (string) (($comment->getAuthorMail() != '') ? '<a href="' . $comment->getAuthorMail() . '" target="_blank">' . $comment->getAuthorName() . '</a>' : $comment->getAuthorName());
		echo $comment_author . '<br />';
		echo $comment->getCommentDate() . '<br />';
		echo $comment->getCommentText() . '<br />';
	    if ($comment->getShowVote() == TRUE && count($comment->getVotesKeys()) > 0) {
	    	echo '<br /><small><strong>Abgestimmt für:</strong> <em><br />';
			$i = '';
			foreach ($comment->getVotesKeys() as $voted_polloption_key) {
				$voted_poll_option = $comment->getVote($voted_polloption_key);
				$i .= '&#8222;' . $voted_poll_option->getAnswer() . '&#8220;, ';
			} // end if
			echo substr($i,0,(strlen($i)-2)); // Letztes Komma entfernen
	        echo '</em></small><br />';
	        unset($i);
		} // end if
		echo '-----------<br />';
	} // end foreach
} // end if

/* Formular */

if ($user->isCommentWritten($current_poll->getID()) === FALSE && !isset($_POST['commentsend'])) { // Formular für Kommentar
	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '"><fieldset><legend>Kommentar schreiben</legend><br />';
    echo '<label for="name">Name:</label><br /><input type="text" id="name" name="name" size="50" value="" /><br />';
    echo '<label for="email">E-Mail:</label><br /><input type="text" id="email" name="email" size="50" value="" /><br />';
    echo '<label for="comment">Kommentar:</label><br /><textarea cols="40" rows="3" id="comment" name="comment"></textarea><br /><br />';

	if ($user->isPollVoted($current_poll->getID()) === TRUE || isset($_POST['vote'])) {
		echo '<strong>Meine Abstimmungsergebnisse anzeigen?</strong><br />';
       	echo '<input type="radio" name="showvote" id="showvote1" value="1" /><label for="showvote1">Ja</label>';
        echo '<input type="radio" name="showvote" id="showvote2" value="0" checked="checked" /><label for="showvote2">Nein</label><br />';
    } else {
    	echo '<input type="hidden" name="showvote" value="0" />';
    } // end if

	echo '<input type="hidden" name="commentsend" value="' . $current_poll->getID() . '" />';
	echo '<input type="hidden" name="id" value="' . $current_poll->getID() . '" />';
	echo '<input type="hidden" name="startlisting" value="' . $startlisting . '" />';
	echo '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />';
	echo '<br /><input type="submit" value="Eintragen" /></fieldset></form>';
  } // end if

/* Old Polls */

$old_poll_list = new PollList($startlisting, $listsize);
$old_polls = (array) $old_poll_list->getPollListKeys();

if (count($old_polls) > 0) {
	foreach ($old_polls as $old_poll_key) {
		$old_poll = $old_poll_list->getPoll($old_poll_key);
		$vote = (int) ((!isset($_POST['vote'])) ?  -1 : $_POST['vote']);
		$boxchecked = (string) (($user->isPollVoted($old_poll->getID()) == true || $old_poll->getID() == $vote)) ? 'checked="checked" ' : '';
		$polllink = (string) '<strong>' . $old_poll->getQuestion() . '</strong> <small>(' . $old_poll->getPollDate() . ')</small>';

    	if ($old_poll->getClosed() == TRUE) {
      		$polllink .= (string) ' (Beendet)';
      	} // end if

    	if ($old_poll->getID() != $current_poll->getID()) { // Aktuell angezeigte Umfrage wird nicht verlinkt
      		$polllink = (string) '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $old_poll->getID() . '&#38;' . $startlisting_url_attach . '&#38;' . $session_url_attach . '">' . $polllink . '</a>';
      	} // end if
		echo '<input type="checkbox" ' . $boxchecked . 'disabled="disabled" />' . $polllink . '<br />';
	} // end foreach
} else {
	echo 'no_polls_found';
} // end if

/* Poll Navigation */

$prevpage = (string) (($old_poll_list->getPrevPage() === TRUE) ? '&laquo;&laquo; <a href="' . $_SERVER['PHP_SELF'] . '?startlisting=' . ($startlisting - $listsize) . '&#38;' . $current_vote_id_attach . '&#38;' . $session_url_attach . '#list">Zurück</a> &laquo;&laquo;' : '&nbsp;' );
$nextpage = (string) (($old_poll_list->getNextPage() === TRUE) ? '&raquo;&raquo; <a href="' . $_SERVER['PHP_SELF'] . '?startlisting=' . ($startlisting + $listsize) . '&#38;' . $current_vote_id_attach . '&#38;' . $session_url_attach . '#list">Weiter</a> &raquo;&raquo;' : '&nbsp;' );

if (count($old_poll_list->getPages()) > 0) {
	$listpages = (string) 'Seite: ';
  	foreach ($old_poll_list->getPages() as $page => $startpoint) {
    	$listpages .= (string) (((($page-1) * $listsize) != $startlisting) ? '<a href="' .$_SERVER['PHP_SELF'] . '?startlisting=' . $startpoint . '&#38;' . $current_vote_id_attach . '&#38;' . $session_url_attach . '#list">' . $page . '</a> ' : $page .' ');
    } // end foreach
} else {
	$listpages = (string) '';
} // end if

echo '<p>' . $prevpage . '</p>';
echo '<p>' . $listpages . '</p>';
echo '<p>' . $nextpage . '</p>';
?>
<br>
_________________
</body>
</html>