<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Weiterleitung</title>
<?php
if (isset($_GET['mail']) && $_GET['mail'] != '' && $_GET['mail'] != ' ')
  {
  include_once('classes.inc.php'); 
  $sp = (object) new FormatString();
  $mail = (string) $sp->DecodeMailAddress($_GET['mail']);

  echo '<meta http-equiv="Refresh" content="0;URL=' . $mail . '" />';
  }
?>
</head>
<body>
<p>Weiterleitung...</p>
</body>
</html>
