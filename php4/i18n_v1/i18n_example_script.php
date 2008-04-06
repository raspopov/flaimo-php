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
* @package i18n
* @category FLP
* @filesource
*/

error_reporting(E_ALL & E_NOTICE);
ob_start(); // you have to use output buffering, otherwise there's a problem with setting cookies/sessions
session_start();

function start_timer($event) {
	//printf("timer: %s<br>\n", $event);
	list($low, $high) = explode(' ', microtime());
	$t = $high + $low;
	flush();
	return $t;
}

function next_timer($start, $event) {
	list($low, $high) = explode(' ', microtime());
	$t    = $high + $low;
	$used = $t - $start;
	sumTimer($used, false);
    printf('timer (%s): %8.4f', $event, $used);
	//printf("Page generated in %s %8.4f seconds", $event, $used);
	flush();
	return $t;
}

function sumTimer($time = 0, $show = false) {
	static $sum = 0;
	if($show == true) {
		printf('timer (sum): %8.4f', $sum);
	} else {
		$sum +=	$time;
	}
}

$t = start_timer("start Befehl 1");

include_once 'class.ChooseLanguage.inc.php';
include_once 'class.ChooseFormatDate.inc.php';
include_once 'class.ChooseMeasure.inc.php';
include_once 'class.FormatString.inc.php';
include_once 'class.FormatNumber.inc.php';
$lg =& new ChooseLanguage('','');
$englisch_lang =& new Translator('en',''); // calling a class with "en" overrules all other language settings and uses englisch
$measure =& new ChooseMeasure('','si');
$format_date =& new ChooseFormatDate();
$fn =& new FormatNumber('');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>i18n example-script</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<style type="text/css">
		<!--
		body {
			background-color:white;
			font: 11pt Verdana;
		}

		code {
			color: blue;
		}

		code.trans {
			color: white;
			background-color: darkblue;
			padding: 3px;
		}

		form {
			background-color: orange;
			padding: 30px;
		}
		dfn {
			color: red;
		}
		acronym {
			color: lightgreen;
		}
		//-->
		</style>
	</head>
	<body>
	<?php //echo $lg->getLocale() . ' ' .  $lg->getCountry() . ' ' . $lg->getLang(); ?>
        <p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'generating objects from classes'); ?></small></p>
		<h1><a href="https://sourceforge.net/projects/php-flp/">i18n package</a> <small>(V1.061)</small></h1>
		<p>If you want to out&#173;put a drop&#173;down&#173;menu for
		chang&#173;ing the lang&#173;uage use the <code>ChooseLanguage</code>
		class. If you don&rsquo;t need that just use the <code>Translator</code> class.
		<br />The script/browser re&#173;members the choosen language by
		session and cookies. To make this work you have to in&#173;clude
		<code>ob_start()</code> and <code>session_start()</code> at the
		be&#173;ginn&#173;ing of your script.</p>

		<h2>Choose Language</h2>
		<h3>(current language: <? echo $englisch_lang->__($lg->getLocale()); ?>)</h3>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" name="<?php echo session_name(); ?>" value="<?php echo session_id(); ?>" />
			<?php echo $lg->returnDropdownLang(); ?>&nbsp;<input type="submit" name="Submit" value="Change language" />
		</form>

		<p>Trans&#173;lated string for <code>'no_records_found'</code> <small>
		(Modus: <code><? echo $lg->getModus(); ?></code>)</small>:
		<br /><br />
		<code class="trans"><? echo $lg->__('no_records_found'); ?></code>
		<br /><br />
		If the string was not found in the trans&#173;lation file (for this
		'no_records_found'-example there is no entry for span&#173;ish, italian or
		french) it will out&#173;put an error message.</p>
		<p>Though it&rsquo;s more com&#173;plicated to set up gettext, i still
		re&#173;commend it, since it's about 15&#8211;40% faster than
		work&#173;ing with <code>inc</code> files. You can change the modus
		in the <code>i18n_settings.ini</code> file.</p>
        <p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'generating language dropdown menu an translating one string'); ?></small></p>
		<hr />
		<h2>Date formatting</h2>
		<h3>(current format: <? echo $format_date->getPossibleDisplayTimes($format_date->getTimeset()); ?>)</h3>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" name="<?php echo session_name(); ?>" value="<?php echo session_id(); ?>" />
			<?php echo $format_date->returnDropdownSelecttime(); ?>&nbsp;<input type="submit" name="Submit" value="Change format" />
		</form>

		<p>For the current language the date <code>2003-12-24&nbsp;13:45:00</code> (iso
		standard) would be formated like this:
		<br /><br />Long Date and Time:
		<code class="trans">
			<? $date = '2003-12-24 13:45:00';
			   echo $format_date->longDateTime($format_date->ISOdatetimeToUnixtimestamp($date)); ?></code><br />
			Middle Date and Time: <code class="trans"><? echo $format_date->middleDateTime($format_date->ISOdatetimeToUnixtimestamp($date)); ?></code><br />
			Short Date and Time: <code class="trans"><? echo $format_date->shortDateTime($format_date->ISOdatetimeToUnixtimestamp($date)); ?></code>
		</p>
        <p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'formating one date'); ?></small></p>
		<hr />
		<h2>Measure</h2>
		<h3>(Input: <code><?php echo $measure->getInput(); ?></code>/ Output: <code><?php echo $measure->getOutput(); ?></code>)</h3>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" name="<?php echo session_name(); ?>" value="<?php echo session_id(); ?>" />
			<?php echo $measure->returnDropdownMeasure(); ?>&nbsp;<input type="submit" name="Submit" value="Change Output Measure System" />
		</form>
		<p>Input is "30000". Output is:<br /><br />
		<code class="trans"><?php echo $measure->number($measure->linear(30000, 1, 2), 2) , '&nbsp;' , $measure->Unit(2); ?></code>
		</p>
        <p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'converting one measure unit'); ?></small></p>
		<hr />
		<h2>String formatting</h2>
		<p>You can mark words as abbreviation, definition or acronym. Also, you can ex&#173;change bad words in a string with *&rsquo;s. Depending on the language set of course.<br />Switch to english language to see how it works.<br /><br />
		<code class="trans">
		<?php
		$st =& new FormatString();
		$string_1 = 'I know a lot of Buffy fanpages on the WWW. ';
		$string_2 = 'Most of them have Fanfiction stories you can download, but nearly all of them are bullshit.';
		$string_3 = 'An russian word: ???.';
		echo $st->filterSpecialWords($st->wordFilter($string_1, 0));
		echo $st->filterSpecialWords($st->wordFilter($string_2, 1));
		?>
		</code>
		</p>
        <p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'scanning two string'); ?></small></p>
		<hr />
		<h2>Number formatting</h2>
		<p>You can format number, currencies and percent numbers.<br /><br />
		<code>123456.789</code> formated with two decimals (depending on language):
		<code class="trans"><?php echo $fn->Number(123456.789, 2); ?></code><br />
		<code>0.154321</code> formated as percent:
		<code class="trans"><?php echo $fn->Percent(0.154321, 2); ?></code><br />
		<code>99.90</code> formated as a currency (Pounds):
		<code class="trans"><?php echo $fn->Currency(99.90, 'full', 'gb', FALSE); ?></code><br />
		</p>
		<p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'formating numbers'); ?></small></p>
		<hr />
		<h2>Other Stuff</h2>
		<h3>More that one Translation file (see php source)</h3>
		<code class="trans"><?php
		// create a new object with more that one translation file
		$another_lang =& new Translator('de','lang_main,lang_classFormatDate');

		echo 'String "Yes": ' , $another_lang->__('Yes');
		echo '</code><br /><code class="trans">';
		/*
		when using modus "gettext" you have to pass the name of the translation file
		as a second value if the string is not located in the first language file from
		the filelist you have used when creating the object.
		In this case the first file in the list is "lang_main". The string "June" can only
		be found in the 2nd file, so you have to add is as a 2nd value.
		THIS IN ONLY IMPORTANT IF YOU USE GETTEXT!
		*/
		echo 'String "June": ' , $another_lang->__('june','lang_classFormatDate');
		echo '</code><br /><code class="trans">';
		/*
		If you wouldn't include the 2nd argument, you would get an error:
		*/
		echo 'String "June": ' , $another_lang->__('june');
		// echo date("F d Y H:i:s.", $lg->getLastUpdateDate());
		//echo $lg->getCountStrings();
		//echo 'Last modified: ' . date("F d Y H:i:s.", getlastmod());
		?>
		</code>
		<p style="text-align:right;color:gray"><small><?php $t = next_timer($t, 'another translation object and 3 translations'); ?><br />
		<?php $t = sumTimer(0,true); ?></small></p>
	</body>
</html>
<?php
ob_end_flush(); // you have to use output buffering, otherwise there's a problem with setting cookies/sessions
?>