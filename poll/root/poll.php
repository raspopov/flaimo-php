<?php
ob_start();
session_start();
header("content-type: text/html;charset=UTF-8 \r\n");
header('Content-Language: de');

define('PHPSELF', $_SERVER['PHP_SELF']);
define('CACHING_TIME_NOTFOUND_PAGE', 0);
define('CACHING_TIME_OFFLINE_PAGE', 0);
define('VOTE_PAGE_NAME', 'vote.tpl');
define('RESULT_PAGE_NAME', 'result.tpl');
define('OFFLINE_PAGE_NAME', 'offline.tpl');
define('EXACT_PERCENT_PRECISION', 1);
define('TEMPLATE_PATH', '/php/poll/root/templates/s_templates/');
define('IMAGES_PATH', '/php/poll/root/images/');
define('INACTIVE_SUFIX', 'inactive');

require_once 'd:/html/php/poll/inc/class_PollBase_inc.php';
require_once 'd:/html/php/poll/smarty/class_PollSmarty_inc.php';

function stripSpecialChar($string = '') {
	if ($string == 'News Networld') {
		return 'nn';
	} elseif ($string == 'Verlagsgruppe News') {
		return '';
	} // end if

	$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$replace_char = '#';
	$temp = strtr($string, $charset, str_repeat($replace_char, strlen($charset)));
 	$temp2 = strtr($string, $temp, str_repeat(' ', strlen($temp)));
	return str_replace (' ', '', strtolower($temp2));
} // end function

if (isset($_POST['poll'])) {
	$poll_id =& $_POST['poll'];
} elseif (isset($_GET['poll'])) {
	$poll_id =& $_GET['poll'];
} else {
	$poll_id = 0;
} // end if

$smarty 		= new PollSmarty;
$current_poll 	= new Poll($poll_id);

/* falls umfrage nicht auffindbar ist, fehlerseite zeigen und fertig */
if ($poll_id < 1 || $current_poll->available() == FALSE) {
	$smarty->caching = CACHING_TIME_NOTFOUND_PAGE;
	$smarty->display('not_found.tpl');
	die();
} // end if

require_once 'd:/html/php/poll/rp/class_ReloadPreventer_inc.php';
$rp = new reloadPreventer;

/* falls template angabe der umfrage leer ist, default template der kategorie
verwenden, ansonsten "default" */
$template_path = (strlen(trim($current_poll->getTemplate())) > 0 && is_dir($smarty->template_dir . '/' . $current_poll->getTemplate())) ? $current_poll->getTemplate(): $current_poll->getCategory()->getTemplate();
if (strlen(trim($template_path)) < 1) {
	$template_path = 'default';
} // end if

$smarty->assign_by_ref('poll_title', $current_poll->getTitle());
$smarty->assign('path', TEMPLATE_PATH . $template_path . '/');

/* falls umfrage nicht mehr online ist, offline seite zeigen und fertig */
if ($current_poll->isPollOnline() == FALSE) {
	$smarty->caching = CACHING_TIME_OFFLINE_PAGE;
	$smarty->display($template_path . '/' . OFFLINE_PAGE_NAME);
	die();
} // end if

$showresult = (isset($_POST['showresult']) || isset($_GET['showresult']) || $current_poll->userAllowedToVote() == FALSE) ? TRUE : FALSE;

/* abstimmung eintragen falls token passt */
if (isset($_POST['vote']) && isset($_POST['selection'])) {

	if ($rp->isValid() == TRUE) {
		$current_poll->addVote($_POST['selection']);
	} // end if
	$showresult = TRUE;
} // end if

$display_type 	= ($showresult == TRUE) ? 'result' : 'display';
$option_list 	= new PollOptionList($current_poll, $display_type);
$options 		=& $option_list->getPollOptions();

$smarty->assign('poll_title', htmlspecialchars($current_poll->getTitle()));
$smarty->assign('poll_description', nl2br(htmlspecialchars($current_poll->getDescription())));
$smarty->assign('count_errors', count($current_poll->getErrors()));
$smarty->assign('sum_polloptions', count($options));
$smarty->assign('sum_polloptions_half', ceil((count($options) / 2)));
$smarty->assign_by_ref('errors', $current_poll->getErrors());
$smarty->assign('php_self', PHPSELF);
$smarty->assign_by_ref('poll_id', $current_poll->getID());
$smarty->assign_by_ref('multiple_choise', $current_poll->getMultipleChoise());
$smarty->assign_by_ref('display_active_only', $current_poll->getDisplayOnlyActiveOptions());
$smarty->assign_by_ref('show_percent', $current_poll->getShowVotesPercent());
$smarty->assign_by_ref('show_absolute', $current_poll->getShowVotesAbsolute());
$smarty->assign_by_ref('cp_type', $current_poll->getCPType());
$smarty->assign('cp', stripSpecialChar($current_poll->getCP()));
$l1 = ($current_poll->getL1Cat()->getID() == 1) ? '' : '/' . stripSpecialChar($current_poll->getL1Cat()->getName());
$l2 = ($current_poll->getL2Cat()->getID() == 1) ? '' : '/' . stripSpecialChar($current_poll->getL2Cat()->getName());
$smarty->assign_by_ref('L1_cat', $l1);
$smarty->assign_by_ref('L2_cat', $l2);


if ($current_poll->getUseOnlyActiveOptions() == TRUE) {
	$smarty->assign_by_ref('sum_votes', $current_poll->getSumActiveVotes());
	$sum_votes = ($current_poll->getSumActiveVotes() > 0) ? $current_poll->getSumActiveVotes() : 1;
} else {
	$smarty->assign_by_ref('sum_votes', $current_poll->getSumVotes());
	$sum_votes = ($current_poll->getSumVotes() > 0) ? $current_poll->getSumVotes() : 1;
} // end if

$y = 0;
foreach ($options as $option) {
	$option_ids[$y] 			= $option->getID();
	$option_titles[$y] 			= htmlspecialchars($option->getTitle());
	$option_descriptions[$y] 	= htmlspecialchars($option->getDescription());
	$option_votes[$y] 			= $option->getVotes();
	$option_active[$y] 			= $option->getIsActive();
	$option_percent_exact[$y] 	= number_format((100 / $sum_votes) * $option->getVotes() , EXACT_PERCENT_PRECISION, ',', '');
	$option_percent[$y] 		= round((100 / $sum_votes) * $option->getVotes() , 0);
	$option_image[$y]	 		= IMAGES_PATH . $current_poll->getID() . '/' . $option->getID() . '.jpg';
	$option_inactive_image[$y]	= IMAGES_PATH . $current_poll->getID() . '/' . $option->getID() . '_' . INACTIVE_SUFIX . '.jpg';
	$option_place[$y] 			= $y+1;
	$y++;

} // end foreach

$smarty->assign_by_ref('option_ids', $option_ids);
$smarty->assign_by_ref('option_titles', $option_titles);
$smarty->assign_by_ref('option_descriptions', $option_descriptions);
$smarty->assign_by_ref('option_votes', $option_votes);
$smarty->assign_by_ref('option_active', $option_active);
$smarty->assign_by_ref('option_percent_exact', $option_percent_exact);
$smarty->assign_by_ref('option_percent', $option_percent);
$smarty->assign_by_ref('option_place', $option_place);
$smarty->assign_by_ref('option_image', $option_image);
$smarty->assign_by_ref('option_inactive_image', $option_inactive_image);

$smarty->caching = 0;
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if ($showresult == TRUE) {
	$rp->setTokenSession();
	$smarty->assign('show_next_revote', FALSE);
	if (isset($_GET) && count($_GET) > 0 && $current_poll->getUser()->getNextVote($current_poll) > 0) {
		$smarty->assign('show_next_revote', TRUE);
		$smarty->assign_by_ref('next_revote_sec', $current_poll->getUser()->getNextVote($current_poll));
		$smarty->assign('next_revote_min', ceil($current_poll->getUser()->getNextVote($current_poll) / 60));
		$smarty->assign('next_revote_hour', ceil($current_poll->getUser()->getNextVote($current_poll) / 60 / 60));
	} // end if

	$smarty->assign('show_revote_link', FALSE);
	if ($current_poll->userAllowedToVote() == TRUE) {
		$smarty->assign('show_revote_link', TRUE);
	} // end if
	/* ergebnisseite nie cachen */
	$template_file = RESULT_PAGE_NAME;
} else {
	$smarty->assign_by_ref('button_label', $current_poll->getButtonLabel());
	$smarty->assign('form_token', $rp->getInputElement());
	/* auswahlseite nur cachen falls antworten NICHT per zufall ausgegeben
	werden sollen */
	$template_file = VOTE_PAGE_NAME;
} // end if
$smarty->display(TEMPLATE_PATH . $template_path . '/' . $template_file);
die();
?>