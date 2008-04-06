<?php
session_start();
require_once '../inc/class.FormBase.inc.php';

// später ev validator für zahlenbild --> kombinieren
// später ev. reloadpreventer einbauen

/*
$xml = new DomDocument('1.0', 'UTF-8');
$desc = $xml->createElement('span');

$desc->appendChild($xml->createTextNode(htmlspecialchars('erster teil')));
$link = $xml->createElement('a');
$link->setAttribute('href', htmlspecialchars('http://www.flaimo.com'));
$link->appendChild($xml->createTextNode(htmlspecialchars('linktext')));
$desc->appendChild($link);
$desc->appendChild($xml->createTextNode(htmlspecialchars('erster teil')));
*/

$form = new Form('testformdata', 'http://localhost/forms/www_root/thanks.php');


$desc = 'text <a href="#">x</a>';
$form->startGroup('Demografische Daten');
$form->startGroup('Basisdaten');
$form->addEntry('Vorname', $desc, F_TEXT, F_REQ, FV_STR, '', array(3, 35), array(3,35));

$form->addEntry('Nachname', 'Geben Sie hier ihren Nachnamen ein.', F_TEXT, F_REQ, FV_STR, '', array(3, 35), array(3,35));
$form->addEntry('Passwort', 'Geben Sie hier ihr PW ein.', F_PW, F_OPT, FV_STR, '', array(6, 35), array(6,35));
$form->addEntry('E-Mail', 'Geben Sie hier eine gülte E-Mail Adresse ein.', F_TEXT, F_REQ, FV_EMAIL, '', array(3, 70), array(3,70));
$form->endGroup('Basisdaten');
$form->startGroup('Zusatzdaten');
$form->addEntry('Geburtstag', 'Geben Sie hier bitte das Datum an an welchen Sie geboren wurden.', F_DATE, F_OPT, FV_DATE, '1960-01-01', array('1901-01-01', date('Y-m-d')), array('1921-02-03', '2005-06-07'));
$form->addEntry('Haarfarbe', 'Wählen Sie hier die Haarfarbe aus.', F_SELECT, F_OPT, FV_VL, '', array('' => '', 1 => 'Blond', 2 => 'Brünett', 3 => 'Rot', 4 => 'Andere'), array(1 => 'Blond', 2 => 'Brünett', 3 => 'Rot', 4 => 'Andere'));
$form->addEntry('Geboren in', 'Wählen Sie hier das Land aus in dem Sie geboren wurden.', F_SELECT, F_OPT, FV_VL, '', array('' => '', 'Europa' => array(1 => 'Österreich', 2 => 'Deutschland', 3 => 'Schweiz'), 'x' => 'Keines', 'Ozeanien' => array(4 => 'Austrialen', 5 => 'Neuseeland')), array(1 => 'Österreich', 2 => 'Deutschland', 3 => 'Schweiz'));
$form->endGroup('Zusatzdaten');
$form->endGroup('Demografische Daten');
$form->startGroup('Weitere Daten');
$form->addEntry('Selbstbeschreibung', 'Geben Sie hier einen kurzen Text über sich selbst ein.', F_TEXTAREA, F_OPT, FV_STR, 'Geben Sie hier den Text ein', '', array(0,255));
$form->addCustomEntry('special-edu-div-id', 'Schulausbildung', 'Geben Sie hier die Schultypen an welche Sie besucht haben. Durch Halten der Strg-Taste wärend des Klickens können Sie mehrere Einträge auswählen.', F_MSELECT, F_OPT, FV_VL, array(1,2), array('Pflichtschule' => array(1 => 'Kindergarten', 2 => 'Volksschule', 3 => 'Hauptschule', 4 => 'Gymnasium'), 'Weiterbildung' => array(5 => 'Universität')), array(1 => 'Kindergarten', 2 => 'Volksschule', 3 => 'Hauptschule', 4 => 'Gymnasium', 5 => 'Universität'));
$form->addEntry('Esszeiten', 'Wählen Sie hier die Tageszeit aus zu der Sie am meisten Nahrung zu sich nehmen.', F_RADIO, F_OPT, FV_VL, '',  array(1 => 'Morgens', 2 => 'Mittags', 3 => 'Abends'), array(1 => 'Morgens', 2 => 'Mittags', 3 => 'Abends'));
$form->addEntry('Farben', 'Welche Farben haben ihre Mahlzeiten.', F_CHECKBOX, F_OPT, FV_VL, array(0), array(1 => 'Grün', 2 => 'Rot', 3 => 'Blau'), array(1 => 'Grün', 2 => 'Rot', 3 => 'Blau'));
$form->addEntry('Avatar', 'Hier können Sie eine Datei auf ihrer Festplatte auswählen welches später im Forum als Avatar neben ihren Beiträgen angezeigt wird. Bitte verwenden Sie nur PNG-Dateien die maximal 90KB groß sind.', F_FILE, F_OPT, FV_FILE, '', array(120000, 'image/png'), array(120000, array('image/png' => 'png')));
$form->endGroup('Weitere Daten');
$form->addEntry('Teilnahmebedingungen', 'Bitte markieren Sie die Auswahlbox um zu bestätigen, dass Sie die Teilnahmebedingungen verstanden haben.', F_CHECKBOX, F_REQ, FV_VC, array(0), array('1' => 'Ich akzeptiere die Teilnahmebedingungen'), '1');
$form->addEntry('Quelle', '', F_HIDDEN, F_REQ, FV_VL, 'flaimo.com', array(10, 10), array('flaimo.com' => 'flaimo.com'));

$form->addEntry('Abschicken', '', F_BUTTON, F_REQ, FV_VL, 'Abschicken', '', array('Abschicken' => 'Abschicken'));
$form->validateForm();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<style>
	* {
		 margin: 0;
		 padding: 0;
	}

	body {
		font-family: Verdana;
		font-size: 0.9em;
		padding: 0.5em;
	}
	
	h1 {
		font-size: 2em;
		margin: 0.5em 0;
	}

	form {
		border: 3px solid #000;
		background: #f7f7f7;
		padding: 0.3em;
	}

	form .notice {
		border: 1px solid #999;
		margin: 0.5em;
		padding: 0.5em;
	}
	
	optgroup option {
		padding-left: 1em;
	}

	fieldset {
		border: 2px solid #000;
		margin: 1em 0.5em;
		padding: 0.2em;

	}

	fieldset > fieldset {
		border: 1px solid #000;
	}

	legend {
		letter-spacing: 0.05em;
		font-size: 1.1em;
		font-weight: bold;
		color: #600;
		margin: 0 0.2em 0em 0;
	}

	fieldset > fieldset legend {
		font-size: 1em;
	}

	.formentry,
	.formentry_invalid,
	.formentry_required,
	.formentry_invalid_required {
		background: #d0d0d0;
		margin: 0.3em;
		padding: 0.2em;
		overflow: auto;
	}
	
	.formentry_required {
		background: #ffff66;
	}

	.formentry_invalid,
	.formentry_invalid_required {
		background: #cc0000;
		color: #fff;
	}
	
	.formentry span,
	.formentry_invalid span,
	.formentry_required span,
	.formentry_invalid_required span {
		background: #f0f0f0;
		display: block;
		padding: 0.2em;
		font-size: 0.9em;
		color: #707070;
		float: right;
		width: 40%;
	}

	.formentry div label,
	.formentry_invalid div label,
	.formentry_required div label,
	.formentry_invalid_required div label {
		font-weight: normal;
		font-size: 0.9em;
		width: 94%;
	}

	.formentry_required span {
		background: #ffffcc;
	}

	.formentry_invalid span,
	.formentry_invalid_required span {
		background: thistle;
	}
	
	.formentry-button {
	  padding: 0.3em;
		text-align: right;
	}

	label {
		font-weight: bold;
		margin: 0 0.5em 0 0.2em;
		display: block;
		float: left;
		width: 18%;
		text-align: right;
	}
	
	#errors {
		background: red;
		border: 2px solid #cc0000;
	}
	
	#errors h3 {
		background: #cc0000;
		padding: 0.3em;
		font-size: 1.5em;
	}
	
	#errors * {
		color: #fff;
		text-decoration: none;
	}
	
	#errors ul {
		padding: 0.5em 0.5em 0.5em 1.5em;
	}

	#errors ul li {
		margin: 0.2em 0;
	}

	.valuelist-date label,
	.valuelist-radio label {
		display: inline;
		float: none;
		margin: 0;
		padding: 0;
	}

	.valuelist-date select {
		margin: 0 1em 0 0.1em;
		padding: 0;
		display: inline;
	}

	.valuelist-date option {
		margin: 0.2em;
	}
	

	.valuelist-radio,
	.valuelist-checkbox {
		overflow: auto;
	}

	.valuelist-radio label,
	.valuelist-checkbox label,
	.valuelist-radio input,
	.valuelist-checkbox input {
		float: left;
		display: block;
	}
	
	.valuelist-radio label,
	.valuelist-checkbox label {
		text-align: left;
		margin: 0.1em 0 0.2em 0;
	}
	
	.valuelist-radio input,
	.valuelist-checkbox input {
		clear: left;
		margin: 0.2em 0.5em 0.2em 0;
	}
	
	
	
	</style>
  <title>Testformular</title>
  </head>
  <body>
<?php
	if ($form->countErrors() > 0) {
		echo '<div id="errors"><h3>Fehler</h3>';
		echo $form->getErrors();
		echo '</div>';
	}
	echo '<h1>Formular</h1>';
	echo $form->getCode();
	
	/*
// debug info
echo '<hr />';
echo '<pre>';
if (isset($_POST)) {
	echo '<h1>POST</h1>';
	var_dump($_POST);
} // end if
echo '<hr />';
if (isset($_FILES)) {
	echo '<h1>FILES</h1>';
	var_dump($_FILES);
} // end if

echo '<h1>Einträge</h1>';
var_dump($form->getEntries());
echo '<h1>Session</h1>';
var_dump($_SESSION);
	*/
?>
  </body>
</html>
