<?php
ob_start('ob_gzhandler');
@session_start();
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');

header('Content-Encoding: gzip'); 
header('Cache-Control: must-revalidate');
header('Expires: ' . date("D, d M Y H:i:s", time() + (60 * 60 * 20)) . ' GMT');

require_once('Smarty.class.php');
include_once('class.ShoutBox.inc.php');  
include_once('class.FormatDate.inc.php');  
include_once('class.FormatLongString.inc.php'); 
include_once('class.ReloadPreventer.inc.php'); 
include_once('class.User.inc.php'); 

$tpl = (object) new Smarty();
$tpl->left_delimiter = (string) '{{'; 
$tpl->right_delimiter = (string) '}}'; 
$tpl->template_dir = (string) 'tpl'; 
$tpl->compile_dir = (string) 'tpl_c'; 
$tpl->config_dir = (string) 'conf';
$tpl->compile_check = (boolean) false; // Wenn Template fertig ist kommentar weg...
$tpl->cache_dir = (string) 'cache';
$tpl->cache_lifetime = (1);
$tpl->caching = (boolean) true; // Nur einschalten wenn redundante Seite (also kein "dynamischer" Inhalt)$tpl->cache_dir = (string) 'cache';

$shoutbox = (object) new ShoutBox(5);
$dp = (object) new FormatDate();
$sp = (object) new FormatLongString();
$rp = (object) new ReloadPreventer();
$user = (object) new User();

if (isset($_POST['sb_submit'])) {
    $valid_submit = (boolean) (($rp->isValid() == true) ? true : false);
} // end if

$rp->setTokenSession(); 
$tpl->assign('TOKEN',$rp->getToken()); 
$tpl->assign('PHP_SELF',$_SERVER['PHP_SELF']); 
$tpl->assign('META_EXPIRES',gmdate("D, d M Y H:i:s", time() + (60 * 60 * 20))); 

if (isset($_POST['sb_submit'])) {
    if ($valid_submit == false) {
        $tpl->assign('FEEDBACK_MESSAGE','Formular wurde bereits gesendet');
    } else {
        if ($shoutbox->insertShout($_POST['sb_name'], $_POST['sb_mail'], $_POST['sb_message']) == false) {
            $tpl->assign('FEEDBACK_MESSAGE','Fehler bei der Eingabe');
        } else {
            $tpl->assign('FEEDBACK_MESSAGE','Shout wurde eingetragen');
        } // end if
    } // end if
} else {
    $tpl->assign('FEEDBACK_MESSAGE','');
} // end if

$date_old = (string) '';
$entries_keys = (array) array_keys($shoutbox->getShoutBoxEntries());
foreach ($entries_keys as $entryID) {
    $entry =& $shoutbox->getShoutboxEntry($entryID);
    $timestamp = $dp->ISOdatetimeToUnixtimestamp($entry->getSBDate());
    if (date('Y-m-d',$timestamp) != $date_old) {
        $sb_date[] = $dp->ShortDate($timestamp);
    } else {
        $sb_date[] = '';
    } // end if
    $sb_name[] = $sp->FormatShortText($entry->getName());
    $sb_email[] = ((strlen(trim($entry->getMail())) > 0) ? $sp->CheckMailHomepage($entry->getMail()) : '' );
    $sb_time[] = $dp->TimeString($timestamp);
    $sb_text[] = $sp->FormatShortText($entry->getMessage());
    $date_old = (string) date('Y-m-d',$timestamp);
} // end foreach
 
$tpl->assign('SB_DATE',$sb_date); 
$tpl->assign('SB_NAME',$sb_name);  
$tpl->assign('SB_EMAIL',$sb_email);
$tpl->assign('SB_TIME',$sb_time); 
$tpl->assign('SB_TEXT',$sb_text); 
$tpl->assign('SESS_NAME',$user->getNickname());
$tpl->assign('SESS_EMAIL',$user->getMail()); 

if (!isset($_COOKIE['shoutbox']) && !isset($_POST['sb_submit'])) {
    $tpl->assign('SHOW_SB_FORM',1);
} else {
    $tpl->assign('SHOW_SB_FORM',0);
} // end if
  
unset($sess_name);
unset($sess_email);

$tpl->display('index.tpl.php');
ob_end_flush();
ob_end_clean();
?>
