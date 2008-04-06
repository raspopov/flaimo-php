<?php
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.29/4.1.1/5.0.0RC1)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo 'at' gmx 'dot' net>                  |
//|          Rafael Machado Dohms <dooms 'at' terra 'dot' com 'dot' br>  |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package valImage
*/
error_reporting(E_ALL & E_NOTICE);
ob_start();
session_start();
require_once 'class.valimage.inc.php';
$vi = new valImage();

if (isset($_POST[$vi->getFormName()])) { // only validate if form was send
	$message = (($vi->checkCode() === TRUE) ? 'Your Input is correct' : 'Your Input is wrong.');
} else {
	$message = '';
} // end if
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>valImage Sample Form</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<h2><?php echo $message; ?></h2>
		<form name="sampleform" id="sampleform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<p>Please type the characters in the picture into the input box and hit Validate (Characters are case-sensitive).</p>
			<img src="image_valimage.php" alt="validateImage" /><br />
			<input name="<?php echo $vi->getFormName(); ?>" type="text" id="<?php echo $vi->getFormName(); ?>" size="7" /><br />
			<input name="send" type="submit" value="Validate" />
		</form>
	</body>
</html>
<?php ob_end_flush(); ?>