<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//+----------------------------------------------------------------------+
//| WAMP (XP-SP1/1.3.24/4.0.12/4.3.0)                                    |
//+----------------------------------------------------------------------+
//| Copyright (c) 1992-2003 Michael Wimmer                               |
//+----------------------------------------------------------------------+
//| I don't have the time to read through all the licences to find out   |
//| what the exactly say. But it's simple. It's free for non commercial  |
//| projects, but as soon as you make money with it, i want my share :-) |
//+----------------------------------------------------------------------+
//| Authors: Michael Wimmer <flaimo@gmx.net>                             |
//+----------------------------------------------------------------------+
//
// $Id$

/**
* @package Ticker
* @category FLP
* @filesource
*/
ob_start();
session_start();
include_once 'class.Ticker.inc.php';
$t = new Ticker(20);
$list = $t->getMessageList();

echo '<?xml version="1.0" encoding="iso-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMS/E-Mail Ticker</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="doc.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
<!--
/*
The contents of this file are subject to the Netscape Public
License Version 1.1 (the "License"); you may not use this file
except in compliance with the License. You may obtain a copy of
the License at http://www.mozilla.org/NPL/

Software distributed under the License is distributed on an "AS
IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
implied. See the License for the specific language governing
rights and limitations under the License.

The Initial Developer of the Original Code is Doron Rosenberg

Alternatively, the contents of this file may be used under the
terms of the GNU Public License (the "GPL"), in which case the
provisions of the GPL are applicable instead of those above.
If you wish to allow use of your version of this file only
under the terms of the GPL and not to allow others to use your
version of this file under the NPL, indicate your decision by
deleting the provisions above and replace them with the notice
and other provisions required by the GPL.  If you do not delete
the provisions above, a recipient may use your version of this
file under either the NPL or the GPL.

Contributor(s):  Doron Rosenberg, doronr@gmx.net
                 Bob Clary, Netscape Communications, Copyright 2001
*/

/*
stock-ticker.js Version 0.2

this script animates a ticker consisting of a div containing a sequence of
spans.	the div is shifted to the left by shiftBy pixels every interval ms.
As the second visible span reaches the left of the screen, the first is appended to
the end of the div's children.

See http://devedge.netscape.com/toolbox/examples/2001/stock-ticker/
*/

function Ticker(name, id, shiftBy, interval) {
  this.name     = name;
  this.id       = id;
  this.shiftBy  = shiftBy ? shiftBy : 1;
  this.interval = interval ? interval : 100;
  this.runId	= null;
  this.div = document.getElementById(id);
  var node = this.div.firstChild;
  var next;

  while (node) {
    next = node.nextSibling;
    if (node.nodeType == 3)
      this.div.removeChild(node);
    node = next;
  }

  this.left = 0;
  this.shiftLeftAt = this.div.firstChild.offsetWidth;
  this.div.style.height	= this.div.firstChild.offsetHeight;
  this.div.style.width = 2 * screen.availWidth;
  this.div.style.visibility = 'visible';
}

function startTicker() {
  this.stop();
  this.left -= this.shiftBy;

  if (this.left <= -this.shiftLeftAt) {
    this.left = 0;
    this.div.appendChild(this.div.firstChild);
    this.shiftLeftAt = this.div.firstChild.offsetWidth;
  }

  this.div.style.left = (this.left + 'px');
  this.runId = setTimeout(this.name + '.start()', this.interval);
}

function stopTicker() {
  if (this.runId)
    clearTimeout(this.runId);

  this.runId = null;
}

function changeTickerInterval(newinterval) {

  if (typeof(newinterval) == 'string')
    newinterval =  parseInt('0' + newinterval, 10);

  if (typeof(newinterval) == 'number' && newinterval > 0)
    this.interval = newinterval;

    this.stop();
    this.start();
}

/* Prototypes for Ticker */
Ticker.prototype.start = startTicker;
Ticker.prototype.stop = stopTicker;
Ticker.prototype.changeInterval = changeTickerInterval;

var ticker = null;

function setTickerSpeed() {
	ticker.changeInterval(document.forms.frmTickerSpeed.speed.value);
}

function startticker() {
	ticker = new Ticker('ticker', 'tickerID', 1, document.forms.frmTickerSpeed.speed.value);
	ticker.start();
}
// -->
</script>
<style type="text/css">
<!--
.ticker {
	padding: 		2px 0px;
	background-color: white;
	position:		relative;
	visibility:		hidden;
	left:			0px;
	top:			0px;
	border-width:	1px;
	border-style:	solid;
	font-size:		12px;
	font-weight:	bold;
	width:			100%;
}
-->
</style>
</head>
<body>
<h1><a href="http://sourceforge.net/projects/php-flp/">SMS/E-Mail Ticker</a> <small>(V1.002)</small></h1>
<p>To test the script, send a <acronym title="Short Message Service" lang="en" xml:lang="en">SMS</acronym> (cell phone text message) or mail to ticker@flaimo.com. The message body has to start with "<?php echo $t->getStartString(); ?>" and end with "<?php echo $t->getEndString(); ?>". Only the first 160 characters are displayed. Guidelines for a couple of cell phone companies on how to send a SMS to an e-mail can be found <a href="sms2mail.htm">here</a>.</p>
<p>Some download servers at sourceforge.net seem to be down. Be sure to try all mirror servers that are listed there.</p>
<h2>Last <?php echo $t->getListSize(); ?> Messages</h2>
<?php
// to add a message though a form for example use this method.
if ($t->hasMessageWritten() === FALSE && isset($_POST['Submit'])) {
	$t->addTickerMessage($_POST['t_message'],$_POST['t_name']);
} // end if

$singlestring = '';
foreach ($list as $id) {
	$m = $t->getMessage($id);
	echo '<p style="font-size:0.6em;border-bottom: 1px solid #333;"><b>ID:</b> ' , $m->getID() , '<br />';
	echo '<b>Date:</b> ' , date('Y-m-d H:i:s',$m->getTimestamp()) , '<br />';
	echo '<b>Message:</b> ' , htmlentities(strip_tags($m->getText())) , '</p>';
	$singlestring .= '<span>' , htmlentities(strip_tags($m->getText())) , ' <span style="color:red">+++</span> </span>' , "\n";
} // end foreach
?>
<h2>Sample Ticker</h2>
<p>You can use the messages to create a ticker, guestbook, shoutbox,&#8230;whatever.</p>
<div style="position: relative; height: 18px; width: 100%; overflow: hidden;">
	<div name="tickerspace" id="tickerID" class="ticker">
		<?php echo $singlestring; ?>
	</div>
</div>
<form name="frmTickerSpeed">
	<input type="hidden" id="speed" name="speed" value="20" size="4" />
	<!--Ticker Speed <input type="text" id="speed" name="speed" value="20" size="4" /> ms&nbsp;
	<button type="button" onclick="ticker.stop();" style="color:red; font-weight:bold">Stop</button>
	<button type="button" onclick="setTickerSpeed();ticker.start();" style="color:green; font-weight:bold">Play</button>
	<button type="button" onclick="setTickerSpeed()">Set Ticker Speed</button>-->
</form>
<hr />
<p>
	Besides Mail and SMS you can use a plain HTML form to add a Ticker message
	 (maximum 160 characters). Please be patient. It could take some time until
	  the message shows up.
</p>
<?php if ($t->hasMessageWritten() === FALSE) { ?>
	<form name="ticker" id="ticker" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<p>
		<fieldset>
			<legend>Add Ticker message</legend>
			<label for="t_message">Message:</label><br />
			<textarea name="t_message" id="t_message" cols="40" rows="3"></textarea>
			<br />
			<label for="t_name">Name</label>
			<input type="text" name="t_name" id="t_name" />
	    	<br />
	    	<input type="submit" name="Submit" id="Submit" value="Add Message" />
		</fieldset>&nbsp;
		</p>
	</form>
<?php } ?>
<hr />
<p> <strong>Author:</strong> <a href="mailto:flaimo@gmx.net">Flaimo</a><br />
				<strong>Date:</strong> 2003-05-30<br />
				<strong>URLs:</strong><br />
				<a href="http://sourceforge.net/projects/php-flp/">Project homepage</a><br />
				<a href="http://www.flaimo.com/">Sample-Script</a></p>
	<script language="javascript" type="text/javascript">
	<!--
	startticker();
	// -->
	</script>
</body>
</html>
<?php
ob_end_flush();
?>