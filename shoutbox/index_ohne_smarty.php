<?php
//ob_start();
@session_start();
ini_set('arg_separator.output','&#38;');
ini_set('session.use_trans_sid','0');
putenv('TZ=CET');

header('Content-Encoding: gzip'); 
Header('Cache-Control: must-revalidate');
Header('Expires: ' . date("D, d M Y H:i:s", time() + (60 * 60 * 20)) . ' GMT');

include_once('class.ShoutBox.inc.php');  
include_once('class.FormatDate.inc.php');  
include_once('class.FormatLongString.inc.php'); 
include_once('class.ReloadPreventer.inc.php'); 
$shoutbox = (object) new ShoutBox(5);
$dp = (object) new FormatDate();
$sp = (object) new FormatLongString();
$rp = (object) new ReloadPreventer();

if (isset($_POST['sb_submit']))
  {
  $valid_submit = (boolean) (($rp->isValid() == true) ? true : false);
  } // end if
$rp->setTokenSession(); 

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>ShoutBox</title>
      <meta http-equiv="MSThemeCompatible" content="Yes" />
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <meta http-equiv="Content-Style-Type" content="text/css" />
      <?php echo '<meta http-equiv="expires" content="' . gmdate("D, d M Y H:i:s", time() + (60 * 60 * 20)) . '" />'; ?>
      <meta http-equiv="pragma" content="no-cache" />
 <style type="text/css">
<!--
.shoutbox {
	margin: 1px;
	padding: 7px;
	height: 450px;
	width: 250px;
	font: small Georgia, "Times New Roman", Times, serif;
	background: #FFFFFF;
	border: medium dashed #0000FF;
	overflow: scroll;
}
-->
</style>
  </head>
  <body>
 <div class="shoutbox">
<h1>ShoutBox</h1>
<?php
if (isset($_POST['sb_submit']))
  {
  if ($valid_submit == false)
    {
    echo 'Formular wurde bereits gesendet.';
    }
  else
    {
    if ($shoutbox->insertShout($_POST['sb_name'], $_POST['sb_mail'], $_POST['sb_message']) == false)
      {
      echo 'Fehler bei der Eingabe<hr />';
      }
    else
      {
      echo 'Shout wurde eingetragen';
      } // end if
    } // end if
  } // end if

  
$date_old = (string) '';
foreach ($shoutbox->getShoutBoxEntries() as $entry)
  {
  $timestamp = $dp->ISOdatetimeToUnixtimestamp($entry->getSBDate());
  
  if (date('Y-m-d',$timestamp) != $date_old)
    {
    echo '<h4>' . $dp->ShortDate($timestamp) . '</h4>';
    } // end if
  
  echo '<p><big>';
  $name = $sp->FormatShortText($entry->getName());
  echo ((strlen(trim($entry->getMail())) > 0) ? '<a href="' . $sp->CheckMailHomepage($entry->getMail()) . '" target="_blank">' . $name . '</a>' : $name );
  echo  '</big><br /><small>';
  echo $dp->TimeString($timestamp) . '</small><br />';
  echo $sp->FormatShortText($entry->getMessage()) . '<br /></p>';
  $date_old = (string) date('Y-m-d',$timestamp);
  } // end foreach
  
if (isset($_COOKIE['cook_name'])) // Überprüfung für das Vorausfüllen des Formulares
  {
  $sess_name = (string) $_COOKIE['cook_name'];
  }
elseif(isset($_SESSION['sess_name']))
  {
  $sess_name = (string) $_SESSION['sess_name'];
  }
else
  {
  $sess_name = (string) '';
  } // end if
  
if (isset($_COOKIE['cook_email'])) // Überprüfung für das Vorausfüllen des Formulares
  {
  $sess_email = (string) $_COOKIE['cook_email'];
  }
elseif(isset($_SESSION['sess_email']))
  {
  $sess_email = (string) $_SESSION['sess_email'];
  }
else
  {
  $sess_email = (string) '';
  } // end if

if (!isset($_COOKIE['shoutbox']) && !isset($_POST['sb_submit']))
  {
  echo '<form action="' . $_SERVER['PHP_SELF'] . '" id="shoutbox_form" name="shoutbox_form" method="post" title="ShoutBox">
          <label for="sb_name" class="sblabel">Name:</label><br /><input type="text" name="sb_name" id="sb_name" class="sbinput" value = "' . $sess_name . '" /><br />
          <label for="sb_mail" class="sblabel">E-Mail:</label><br /><input type="text" name="sb_mail" id="sb_mail" class="sbinput" value="' . $sess_email . '" /><br />
          <label for="sb_message" class="sblabel">Message:</label><br /><textarea name="sb_message" id="sb_message" class="sbtextarea" cols="20" rows="3" ></textarea><br />
          <input type="hidden" name="token" value="' . $rp->getToken() . '" />
          <input type="submit" name="sb_submit" id="sb_submit" value="Shout Out" class="sbsumbit" />
        </form>';
  } // end if
  
unset($sess_name);
unset($sess_email);



//ob_end_flush();
//ob_end_clean();
?>
</div> 
</body></html>