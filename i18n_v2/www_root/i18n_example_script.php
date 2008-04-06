<?php
error_reporting(E_ALL);
ob_start(); // you have to use output buffering, otherwise there's a problem with setting cookies/sessions
header("content-type: text/html;charset=UTF-8 \r\n");
session_start();
mb_internal_encoding('UTF-8');
mb_language('uni');

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

require_once '../inc/class.I18Nbase.inc.php';

$englisch_translator = new I18Ntranslator('', new I18Nlocale('en'));
$i18n_user 			 =& $englisch_translator->getI18Nuser();

if (isset($_POST['locale'])) {
	$i18n_user->setPrefLocale($_POST['locale']);
} elseif (isset($_POST['timeformat'])) {
	$i18n_user->setPrefTimeFormat($_POST['timeformat']);
} elseif (isset($_POST['measure'])) {
	$i18n_user->setPrefMeasureSystem($_POST['measure']);
} // end if

$translator		= new I18Ntranslator();
$measure 		= new I18Nmeasure('si');
$format_date 	= new I18NformatDate();
$format_number	= new I18NformatNumber();
$format_string	= new I18NformatString();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>i18n example-script</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style type="text/css">
		<!--
		@import url(css.css);
		-->
		</style>
	</head>
	<body>
		<h1><a href="https://sourceforge.net/projects/php-flp/">FLP – i18n</a> <small>(V2.1b2)</small></h1>
			<ul id="menu">
				<li><a href="#translator">Translator</a></li>
				<li><a href="#date">Date</a></li>
				<li><a href="#number">Number</a></li>
				<li><a href="#measure">Measure</a></li>
				<li><a href="#string">String</a></li>
			</ul>
			<p class="timer"><?php $t = next_timer($t, 'generating objects'); ?></p>
			<h2 id="translator">Translator <small>(<?php echo $translator->getI18NSetting('mode'); ?>)</small></h2>
				<h3>(current locale used: <?php echo $englisch_translator->_($translator->getTranslatorLocale()->getI18Nlocale()); ?>)</h3>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>#translator" name="select_locale" id="select_locale">
						<select name="locale" id="locale">
						<?php
						$possible_locales = array_keys($translator->getLocales());
						$current_locale = $translator->getTranslatorLocale();
						foreach ($possible_locales as $code) {
							$translator->changeLocale(new I18Nlocale($code));
							$selected = ($code == $current_locale->getI18Nlocale()) ? ' selected="selected"' : '';
							echo '<option value="' , $code , '"' , $selected , '>' , $translator->_($code) , '</option>';
							//echo '<option value="' , $code , '"' , $selected , '>' , mb_detect_encoding($translator->_($code)) , '</option>';

						} // end foreach
						$translator->changeLocale($current_locale);
						?>
						</select>
						<input type="submit" name="Submit" value="Change language" />
					</form>
					<p class="sample" id="translator_sample">
						Translating »no_records_found«: <samp><?php echo $translator->_('no_records_found'); ?></samp>
					</p>
					<p class="timer"><?php $t = next_timer($t, 'translating'); ?></p>
			<h2 id="date">Date</h2>
				<h3>(current time format used: <?php echo $format_date->getTimeFormat(); ?>)</h3>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>#date" name="select_timeformat" id="select_timeformat">
						<select name="timeformat" id="timeformat">
						<?php
						foreach ($format_date->getPossibleTimeFormats() as $id => $name) {
							$selected = ($format_date->getTimeFormat() == $name) ? ' selected="selected"' : '';
							echo '<option value="' , $id , '"' , $selected , '>' , $name , '</option>';
						} // end foreach
						?>
						</select>
						<input type="submit" name="Submit" value="Change time format" />
					</form>
					<?php
					$date = '2004-12-31 15:30:00';
					$timestamp = $format_date->ISOdatetimeToUnixtimestamp($date);
					?>
					<p class="sample" id="formatdate_sample">
						Formating »<?php echo $date; ?>« <small>(long)</small>: <samp><?php echo $format_date->longDateTime($timestamp); ?></samp>
					</p>
					<p class="sample" id="formatdate_sample">
						Formating »<?php echo $date; ?>« <small>(middle)</small>: <samp><?php echo $format_date->middleDateTime($timestamp); ?></samp>
					</p>
					<p class="sample" id="formatdate_sample">
						Formating »<?php echo $date; ?>« <small>(short)</small>: <samp><?php echo $format_date->shortDateTime($timestamp); ?></samp>
					</p>
					<p class="timer"><?php $t = next_timer($t, 'formating dates'); ?></p>
			<h2 id="number">Number</h2>
				<?php
				$number = 12345.678;
				$float = 0.567;
				$money = 0.56;
				?>
				<p class="sample" id="formatnumber_sample">
					Formating »<?php echo $number; ?>«: <samp><?php echo $format_number->number($number); ?></samp>
				</p>
				<p class="sample" id="formatnumber_sample">
					Formating »<?php echo $float; ?>« <small>(percent)</small>: <samp><?php echo $format_number->percent($float); ?>%</samp>
				</p>
				<p class="sample" id="formatnumber_sample">
					Formating »<?php echo $money; ?>« <small>(currency big)</small>: <samp><?php echo $format_number->currency($money, 'full', 'gb', TRUE); ?></samp>
				</p>
				<p class="sample" id="formatnumber_sample">
					Formating »<?php echo $money; ?>« <small>(currency small)</small>: <samp><?php echo $format_number->currency($money, 'full', 'gb', FALSE); ?></samp>
				</p>
				<p class="sample" id="formatnumber_sample">
					Formating »<?php echo $money; ?>« <small>(currency symbol left)</small>: <samp><?php echo $format_number->currency($money, 'symbol', 'gb', TRUE, 'before'); ?></samp>
				</p>
				<p class="timer"><?php $t = next_timer($t, 'formating numers'); ?></p>
			<h2 id="measure">Measure</h2>
				<h3>(current measure output format used: <?php echo $measure->getOutput(); ?>)</h3>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>#measure" name="select_measure" id="select_measure">
						<select name="measure" id="measure2">
						<?php
						$possible_formats =& $measure->getFormats();
						foreach ($possible_formats as $format) {
							$translator->changeLocale(new I18Nlocale($code));
							$selected = ($format == $measure->getOutput()) ? ' selected="selected"' : '';
							echo '<option value="' , $format , '"' , $selected , '>' , $format , '</option>';
						} // end foreach
						?>
						</select>
						<input type="submit" name="Submit" value="Change measure output format" />
					</form>
				<?php
				$number = 30000;
				?>
				<p class="sample" id="formatmeasure_sample">
					Formating »<?php echo $number; ?> mm«: <samp><?php echo $measure->linear($number, 0, 0) , ' ' ,  $measure->Unit(2); ?></samp>
				</p>
				<p class="timer"><?php $t = next_timer($t, 'formating measure'); ?></p>
			<h2 id="string">String</h2>
			<?php
			$string_1 = 'I know a lot of Buffy fanpages on the WWW.';
			$string_2 = 'Most of them have Fanfiction stories you can download, but nearly all of them are bullshit.';
			?>
			<p class="sample" id="formatstring_sample">
				Stripping bad words: <samp><?php echo $format_string->wordFilter($string_2, TRUE); ?></samp>
			</p>
			<p class="sample" id="formatstring_sample">
				Highlighting special words: <samp><?php echo $format_string->highlightSpecialWords($string_1); ?></samp>
			</p>
			<p class="timer"><?php $t = next_timer($t, 'formating strings'); ?></p>
			<p id="sum"><?php $t = sumTimer(0,true); ?></p>
	</body>
</html>
<?php ob_end_flush(); ?>