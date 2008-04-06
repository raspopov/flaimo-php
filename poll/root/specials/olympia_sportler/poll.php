<?php
ob_start();
define('PHPSELF', $_SERVER['PHP_SELF']);
session_start();
require_once '../inc/class_PollBase_inc.php';
if (isset($_POST['poll'])) {
	$poll_id =& $_POST['poll'];
} elseif (isset($_GET['poll'])) {
	$poll_id =& $_GET['poll'];
} else {
	$poll_id = 0;
} // end if
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
<title>Umfrage</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
$current_poll = new Poll($poll_id);
if ($poll_id > 0 && $current_poll->available() == TRUE) {
	if ($current_poll->isPollOnline() == TRUE) {

		$showresult = (isset($_POST['showresult']) || isset($_GET['showresult']) || $current_poll->userAllowedToVote() == FALSE) ? TRUE : FALSE;
		$display_type = ($showresult == TRUE) ? 'result' : 'display';
		$option_list = new PollOptionList($current_poll, $display_type);
		$options =& $option_list->getPollOptions();
		require_once '../rp/class_ReloadPreventer_inc.php';
		$rp = new reloadPreventer;

		echo '<h1>' , $current_poll->getTitle() , '</h1>';
		echo '<p>' , $current_poll->getDescription() , '</p>';

		/* abstimmung eintragen */
		if (isset($_POST['vote']) && isset($_POST['selection'])) {
			if ($rp->isValid() == TRUE) {
				$current_poll->addVote($_POST['selection']);
			} // end if
			$showresult = TRUE;
		} // end if

		/* fehler ausgeben */
		if (count($current_poll->getErrors()) > 0) {
			echo '<ul><li>' , implode('</li><li>' , $current_poll->getErrors()) , '</li></ul>';
		} // end if

		if ($showresult == TRUE) {
			$rp->setTokenSession();
			$sum_votes = ($current_poll->getSumVotes() > 0) ? $current_poll->getSumVotes() : 1;
			echo '<p><small>Summe der Stimmen: ' , $current_poll->getSumVotes() , '</small></p>';

			if (isset($_GET) && count($_GET) > 0 && $current_poll->getUser()->getNextVote($current_poll) > 0) {
				echo '<p>Nachste Abstimmung in ' , round($current_poll->getUser()->getNextVote($current_poll) / 60, 0) , ' Minuten</p>';
			} // end if

			foreach ($options as $option) {
				echo '<h2>' . $option->getTitle() . '</h2>';
				echo '<p>' , $option->getDescription() , '</p>';
				$percent = round((100 / $sum_votes) * $option->getVotes() , 1);
				echo '<p><small> Stimmen: ' , $option->getVotes() , ' (' , $percent , '%)</small></p>';
				echo '<div style="height: 10px; width:' , round($percent, 0)*4 , 'px; background-color: red;"></div>';
				echo '<hr>';

			} // end foreach
			if ($current_poll->userAllowedToVote() == TRUE) {
				echo '<p><a href="' , PHPSELF , '?poll=' , $current_poll->getID() , '">Abstimmen</a></p>';
			} // end if

		} else {
			echo '<form action="' , PHPSELF , '" method="post">';
			foreach ($options as $option) {
				echo '<h2>';
				if ($current_poll->getMultipleChoise() == TRUE) {
					echo '<input type="checkbox" name="selection[' , $option->getID() , ']"';
				} else {
					echo '<input type="radio" name="selection"';
				} // end if
				echo ' id="selection_' , $option->getID() , '" value="' , $option->getID() , '" />';
				echo '<label for="selection_' , $option->getID() , '">' . $option->getTitle() . '</label>';
				echo '</h2>';
				echo '<p>' , $option->getDescription() , '</p><hr>';
			} // end foreach

  		echo $rp->getInputElement();
			echo '<input type="hidden" name="poll" value="' , $current_poll->getID() , '" />';
			echo '<input type="submit" name="vote" id="vote" value="Abstimmen" />';
			echo '</form>';
		} // end if
	} else {
		echo '<p>Die Umfrage ist nicht mehr Online</p>';
	} // end if
} else {
	echo '<p>Die Umfrage konnte nicht gefunden werden</p>';
} // end if

echo '<hr>Verwendetes template: ' . $current_poll->getTemplate();

?>
</body>
</html>