<?php
$pagetitle = 'Neue Umfrage';
require_once 'header_inc.php';

/* init variables */
$title = $description = $template = '';
$button_label = 'Abstimmen';
$cp = 'Umfrage';
$cp_type = 'c';
$revote = $ordered_list = $popular_result = $mc = $active_polloptions_given = $display_only_active = $show_votes_percent = $show_votes_absolute = 0;
$errors 			= array();
$preselected_cat_id = 0;
$use_only_active 	= 1;
$start_year 		= (int) date('Y');
$stop_year  		= (int) $start_year + 6;
$start_month 		= (int) date('m');
$stop_month 		= 12;
$start_day 			= (int) date('d');
$stop_day 			= 31;
$start_hour 		= (int) date('H');
$stop_hour 			= 23;
$start_minute 		= (int) date('i');
$stop_minute 		= 59;

for ($i = 1; $i <= MAX_ANSWERS; $i++) {
	$a_title 	= 'a_title_' . $i;
	$a_desc 	= 'a_description_' . $i;
	$a_pos 		= 'a_position_' . $i;
	$a_active 	= 'a_active_' . $i;
	$$a_title 	= $$a_desc = '';
	$$a_pos 	= 0;
	$$a_active 	= 1;
} // end for

if (isset($_GET['a_category'])) {
	$preselected_cat_id = (int) $_GET['a_category'];
} elseif (isset($_POST['a_category'])) {
	$preselected_cat_id = (int) $_POST['a_category'];
} // end if

if (isset($_GET['category'])) {
	$dropdown_cat_id = (int) $_GET['category'];
} else {
	$dropdown_cat_id = (int) 1;
} // end if

if (isset($_POST['addpoll'])) {
	/* eingaben auf fehler überprüfen */
	if (!isset($_POST['title']) || strlen(trim($_POST['title'])) < 1) {
		$errors['poll_title'] = 'Sie müssen einen Titel für die Umfrage angeben';
	} else {

		$title = stripslashes($_POST['title']);
	} // end if

	if (!isset($_POST['button_label']) || strlen(trim($_POST['button_label'])) < 1) {
		$errors['button_label'] = 'Sie müssen einen Text für den Button angeben';
	} else {
		$button_label = stripslashes($_POST['button_label']);
	} // end if

	if (!isset($_POST['cp']) || strlen(trim($_POST['cp'])) < 1) {
		$errors['cp'] = 'Sie müssen einen Zählpixel angeben';
	} else {
		$cp = stripslashes($_POST['cp']);
	} // end if

	if (isset($_POST['description']) && strlen(trim($_POST['description'])) > 0) {
		$description = stripslashes($_POST['description']);
	} // end if

	if (isset($_POST['revote'])) {
		$revote = (int) $_POST['revote'];
	} // end if

	if (isset($_POST['mc']) && $_POST['mc'] == 1) {
		$mc =& $_POST['mc'];
	} // end if

	if (isset($_POST['ordered_list']) && $_POST['ordered_list'] == 1) {
		$ordered_list =& $_POST['ordered_list'];
	} // end if

	if (isset($_POST['popular_result']) && $_POST['popular_result'] == 1) {
		$popular_result =& $_POST['popular_result'];
	} // end if

	if (isset($_POST['use_only_active']) && $_POST['use_only_active'] == 1) {
		$use_only_active =& $_POST['use_only_active'];
	} // end if

	if (isset($_POST['display_only_active']) && $_POST['display_only_active'] == 1) {
		$display_only_active =& $_POST['display_only_active'];
	} // end if

	if (isset($_POST['template']) && strlen(trim($_POST['template'])) > 0) {
		$template = stripslashes($_POST['template']);
	} // end if

	if (isset($_POST['show_votes_percent']) && $_POST['show_votes_percent'] == 1) {
		$show_votes_percent =& $_POST['show_votes_percent'];
	} // end if

	if (isset($_POST['show_votes_absolute']) && $_POST['show_votes_absolute'] == 1) {
		$show_votes_absolute =& $_POST['show_votes_absolute'];
	} // end if

	if (isset($_POST['cp_type'])) {
		$cp_type =& $_POST['cp_type'];
	} // end if

	if (isset($_POST['start_year']) && isset($_POST['start_month']) &&
		isset($_POST['start_day']) && isset($_POST['start_hour']) &&
		isset($_POST['start_minute']) &&
		checkdate($_POST['start_month'], $_POST['start_day'], $_POST['start_year']) == TRUE &&
		$_POST['start_hour'] >= 0 && $_POST['start_hour'] < 24 &&
		$_POST['start_minute'] >= 0 && $_POST['start_minute'] < 60) {
		$start_year =& $_POST['start_year'];
		$start_month =& $_POST['start_month'];
		$start_day =& $_POST['start_day'];
		$start_hour =& $_POST['start_hour'];
		$start_minute =& $_POST['start_minute'];
	} else {
		$errors['start_poll'] = 'Sie müssen ein gültige Start-Datum angeben';
	} // end if

	if (isset($_POST['stop_year']) && isset($_POST['stop_month']) &&
		isset($_POST['stop_day']) && isset($_POST['stop_hour']) &&
		isset($_POST['stop_minute']) &&
		checkdate($_POST['stop_month'], $_POST['stop_day'], $_POST['stop_year']) == TRUE &&
		$_POST['stop_hour'] >= 0 && $_POST['stop_hour'] < 24 &&
		$_POST['stop_minute'] >= 0 && $_POST['stop_minute'] < 60) {
		$stop_year =& $_POST['stop_year'];
		$stop_month =& $_POST['stop_month'];
		$stop_day =& $_POST['stop_day'];
		$stop_hour =& $_POST['stop_hour'];
		$stop_minute =& $_POST['stop_minute'];
	} else {
		$errors['end_poll'] = 'Sie müssen ein gültige End-Datum angeben';
	} // end if

	for ($i = 1; $i <= MAX_ANSWERS; $i++) {
		$a_title 	= 'a_title_' . $i;
		$a_desc 	= 'a_description_' . $i;
		$a_pos 		= 'a_position_' . $i;
		$a_active 	= 'a_active_' . $i;

		if (isset($_POST[$a_title]) && strlen(trim($_POST[$a_title])) > 0) {
			$$a_title = stripslashes($_POST[$a_title]);
			if (isset($_POST[$a_active]) && $_POST[$a_active] == 1) {
				$active_polloptions_given++;
			} // end if
		} // end if

		if (isset($_POST[$a_desc]) && strlen(trim($_POST[$a_desc])) > 0) {
			$$a_desc = stripslashes($_POST[$a_desc]);
		} // end if

		if (isset($_POST[$a_pos]) && $_POST[$a_pos] > 0 && $_POST[$a_pos] < 100) {
			$$a_pos =& $_POST[$a_pos];
		} // end if

		if (isset($_POST[$a_active]) && $_POST[$a_active] == 1) {
			$$a_active = 1;
		} else {
			$$a_active = 0;
		} // end if
	} // end for

	if ($active_polloptions_given < 2) {
		$errors['min_polloptions'] = 'Sie müssen mindestens zwei aktive Antwortmöglichkeiten angeben';
	} // end if

	/* daten in db schreiben */
	if (count($errors) < 1) {
		$dummy = new PollCategory($preselected_cat_id);
		$poll_list = new PollList($dummy);
		$start = mktime($start_hour, $start_minute, 0, $start_month, $start_day, $start_year);
		$end = mktime($stop_hour, $stop_minute, 0, $stop_month, $stop_day, $stop_year);

		$new_poll = $poll_list->addPoll($title, $description, $start, $end, (boolean) $mc, (boolean) $ordered_list, (boolean) $popular_result, (boolean) $use_only_active, (boolean) $display_only_active, FALSE, (boolean) $show_votes_percent, (boolean) $show_votes_absolute, $revote, $template, $button_label, $cp, $cp_type, 0);
		if ($new_poll == FALSE) {
			$errors['create_poll'] = 'Fehler beim erstellen der Umfrage';
		} else {
			$polloption_list = new PollOptionList($new_poll, 'edit');

			for ($i = 1; $i <= MAX_ANSWERS; $i++) {
				$a_title = 'a_title_' . $i;
				$a_desc = 'a_description_' . $i;
				$a_pos = 'a_position_' . $i;
				$a_active = 'a_active_' . $i;
				if (($created_po = $polloption_list->addPollOption($$a_title, $$a_desc, $$a_pos, (boolean) $$a_active)) != FALSE) {
					if (isset($_FILES['a_image_' . $i]) &&
						strlen(trim($_FILES['a_image_' . $i]['name'])) > 0) {
						$created_po->addImage($_FILES['a_image_' . $i]);
					} // end if

					if (isset($_FILES['a_inactiveimage_' . $i]) &&
						strlen(trim($_FILES['a_inactiveimage_' . $i]['name'])) > 0) {
						$created_po->addImage($_FILES['a_inactiveimage_' . $i], INACTIVE_SUFIX);
					} // end if
				} // end if
			} // end for

			header('Location: index.php?category=' . $preselected_cat_id);
			die();
		} // end if
	} // end if
} // end if

require_once 'errors_inc.php';
?>
<form action="<?php echo PHPSELF; ?>" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
	<fieldset>
		<legend>Umfrage-Daten</legend>
		<div id="basicdata">
			<label for="title">Titel<sup>*</sup>:</label>
			<input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" maxlength="<?php echo MAX_LENGTH_POLL_TITLE; ?>" />
			<?php if (isset($errors['poll_title'])) { echo '<span class="inline-error">' , $errors['poll_title'] , '</span>'; } ?>
			<br />
			<label for="description">Beschreibung:</label>
			<textarea name="description" id="description" cols="40" rows="4"><?php echo htmlspecialchars($description); ?></textarea><br />
			<div class="help">
				<h5>Hilfe</h5>
				<p>Der angegebene Text dient als Beschriftung des Buttons der zum Abschicken der Stimme auf der Umfrage-Seite steht.</p>
			</div>
			<label for="buttonlabel">Button-Text<sup>*</sup>:</label>
			<input type="text" name="button_label" id="buttonlabel" value="<?php echo htmlspecialchars($button_label); ?>" maxlength="<?php echo MAX_LENGTH_POLL_BUTTON; ?>" />
			<?php if (isset($errors['button_label'])) { echo '<span class="inline-error">' , $errors['button_label'] , '</span>'; } ?>
			<br />
			<label for="cp">Zählpixel<sup>*</sup>:</label>
			<input type="text" name="cp" id="cp" value="<?php echo htmlspecialchars($cp); ?>" maxlength="<?php echo MAX_LENGTH_POLL_CP; ?>" />
			<?php if (isset($errors['cp'])) { echo '<span class="inline-error">' , $errors['cp'] , '</span>'; } ?>
			<br />
			<div class="help">
				<h5>Hilfe</h5>
				<dl>
					<dt>Redaktioneller Content</dt>
					<dd>Alle Seiten, die aus redaktioneller Berichterstattung oder Aufbereitung stammen und Seiten, die dieser Berichterstattung erweiternd angebunden sind, außer diese angebundenen Inhalte sind nach ihrem Schwerpunkt anderen Kontingenten zuzuordnen.<br />
					Darunter sind alle von internen sowie externen Redaktionen erstellten oder aufbereiteten Content-Elemente einzuordnen. Das Format der Content-Elemente spielt dabei keine Rolle. Es kann sich um Texte, Bilder, Bildserien, Video- oder Audiobeiträge handeln. Der Content kann dem User in sequentieller Abfolge angeboten werden oder auch interaktiv in dem Sinne, dass User-Aktionen die Abfolge des darzustellenden Contents bestimmen.
					<br />
					Dem Bereich sind u.a. zuzuordnen:
						<ul>
							<li>diverse Vergleichstabellen (Tarifvergleiche, Wahlergebnisse, Sporttabellen), so sie in die Berichterstattung eingebunden sind</li>
							<li>Bildergalerien, so sie in die Berichterstattung eingebunden sind und nicht auf Grund ihres Inhaltes anderen Seitenkontingenten zuzuordnen sind</li>
							<li>Suchfunktionen und Ergebnislisten, so sie in die Berichterstattung eingebunden sind</li>
							<li>Linksammlungen, sofern ein redaktioneller Beitrag erkennbar ist (Kommentierung, Bewertung)</li>
							<li>Archiv (sofern die grundsätzlichen Bestimmungen zutreffen)</li>
							<li>Horoskop</li>
							<li>Webcams wie z.B. Panorama</li>
						</ul>
					</dd>
					<dt>Infotainment</dt>
					<dd>
					Darunter sind Seiten zu verstehen, die zu einem selbstständigen Format gehören, bei denen der Unterhaltungswert im Vordergrund steht und die mit Informationen verknüpft sind.
					<br />
					Dem Bereich sind u.a. zuzuordnen:
						<ul>
							<li>Quiz, Wissenstests mit eigenständiger redaktioneller Leistung</li>
							<li>Humor</li>
							<li>Wahlbörse, Börsenspiele</li>
						</ul>
					</dd>
					<dt>Community</dt>
					<dd>
					Alle Seiten, die hauptsächlich durch den Austausch von Lesern und Community-Mitgliedern entstehen. Der redaktionellen Bearbeitung dieser Seiten kommt untergeordnete Bedeutung zu oder fehlt ganz.
					<br />
					Dem Bereich sind u.a. zuzuordnen:
						<ul>
							<li>Chat, Foren</li>
							<li>Partnerbörsen</li>
							<li>Umfragen, Votes, Abstimmungen</li>
							<li>Nickpages, Gästebuch</li>
						</ul>
					</dd>
					<dt>Service</dt>
					<dd>
					Seiten, die entweder ein zielgerichtetes Suchen ermöglichen, sofern die Suchfunktion nicht lediglich unterstützend in einen anderen Bereich eingebunden ist, oder die internetspezifische Dienstleistung enthalten (wie z.B. Epostkarten, Messaging).
					<br />
					Dem Bereich sind u.a. zuzuordnen:
						<ul>
							<li>Kleinanzeigen</li>
							<li>Messaging, Epostkarten</li>
							<li>Datenbanken wie Telefonbücher, Lokalführer, Veranstaltungs-kalender, Rezepte, Kinoprogramm, Fernsehprogramm <small>(Filmkritiken, Rezensionen Zuordnung zu Redaktionellem Content)</small></li>
							<li>Suche, sofern nicht lediglich unterstützend in einen anderen Bereich eingebunden</li>
							<li>Linksammlungen, sofern unkommentiert</li>
							<li>Downloadbereich <small>(so keine redaktionellen Beiträge zu den Produkten)</small></li>
							<li>Unternehmenseigene Informationen <small>(Impressum, Anzeigentarife, Werbemöglichkeiten, Kontakt, Produktinformationen)</small></li>
							<li></li>

						</ul>
					</dd>
					<dt>Unterhaltung und Games</dt>
					<dd>
					Alle Seiten mit Glücks- oder Geschicklichkeitsspielen ohne Informationen oder redaktionellen Inhalt.
					„Erotik“ ist ein Unterbereich von „Games und Unterhaltung“. Unter „Erotik“ fallen alle Seiten, die mit vorwiegend visuellem Material erotische Inhalte darstellen.
					<br />
					Dem Bereich sind u.a. zuzuordnen:
						<ul>
							<li>Spiele mit oder ohne Gewinn</li>
							<li>Wetten ohne Geld</li>
						</ul>
					</dd>
					<dt>E-Commerce</dt>
					<dd>
					Seiten, die zum Ziel haben, daß Produkte oder Dienstleistungen bestellt werden, und auf denen solche Bestellungen auch getätigt werden können. Weiters fallen in diesen Bereich: Wetten oder sonstige Glücks- und Geschicklichkeitsspiele mit Geldeinsatz.
					<br />
					Dem Bereich sind u.a. zuzuordnen:
						<ul>
							<li>Shops</li>
							<li>Auktionen</li>
							<li>Tourismusangebote mit Buchungsmöglichkeit</li>
							<li>Seiten, auf denen Produkte oder Dienstleistungen bestellt werden können <small>(Hotline, Bestellformular also auch Abobestellungen, etc.)</small></li>
							<li>Wetten mit Geldeinsatz</li>
						</ul>
					</dd>
					<dt>Diverses</dt>
					<dd>
					Hier können Seiten, die aus Gründen mangelnder Aktualität vom Anbieter nicht mehr überarbeitet werden, zugeordnet werden. Der Anteil dieses Bereiches sollte eine Höchstgrenze, etwa fünf Prozent der Zugriffe, nicht übersteigen. Im Bedarfsfall kann der ÖWA-Beirat den Anbieter auffordern, die Klassifizierung seiner Seiten zu überarbeiten.
					</dd>
				</dl>
			</div>
			<label for="cp-type">Content-Typ:</label>
			<select name="cp_type" id="cp-type">
				<?php
				foreach ($allowed_cp_types as $cp_type_id => $cp_type_name) {
					$selected = ($cp_type_id == $cp_type) ? ' selected="selected" ': '';
					echo '<option value="' , $cp_type_id , '"' , $selected , '>' , $cp_type_name , '</option>';
				} // end foreach
				?>
			</select>
			<br />
			<div class="help">
				<h5>Hilfe</h5>
				<p>Die Kategorie dient neben der Strukturierung der Umfragen auch dazu selbigen eine Standard-Vorlage zuzuweisen, falls diese über keine eigene verfügen.</p>
			</div>
			<label for="a-category">Kategorie:</label>
			<select name="a_category" id="a-category">
				<?php echo generateCategoryDropdown(new PollCategory(0), 1, new PollCategory($dropdown_cat_id )); ?>
			</select><br />

			<div class="help">
				<h5>Hilfe</h5>
				<p>Nach wieviel Minuten kann ein Besucher bei deser Umfrage wieder abstimmen. Es wird vorausgesetzt, dass der Benutzer Cookies aktiviert hat, ansonsten würde es genügen das Umfragefenster zu schließen und erneut zu öffen um wieder abstimmen zu können.</p>
			</div>
			<label for="revote">Neue Abstimmung </label>
			<select name="revote" id="revote" title="Nach welchem Zeitraum darf der Besucher bei dieser Umfrage erneut abstimmen?">
				<?php
				$options = array(0 => 'ständig', 300 => 'alle 5 Minuten', 600 => 'alle 10 Minuten', 1800 => 'alle 30 Minuten', 3600 => 'alle 60 Minuten', 10800 => 'alle 3 Stunden', 86400 => 'alle 24 Stunden');
				foreach ($options as $seconds => $label) {
					$selected = ($seconds == $revote) ? ' selected="selected"': '';
					echo '<option value="' , $seconds , '"' , $selected , '>' , $label , '</option>';
				} // end foreach
				?>
			</select> möglich <br />


			<div class="help">
				<h5>Hilfe</h5>
				<p>Die Vorlage die für die Umfrage verwendet werden soll. Sollte keine spezielle Vorlage ausgewählt werden, so wird die Standard-Vorlage der Kategorie verwendet.</p>
			</div>
			<label for="template">Vorlage:</label>
			<select name="template" id="template">
				<option value="">Kategorie-Vorlage verwenden</option>
				<?php
				$smarty = new PollSmarty;
				echo generateTemplateDropdown($smarty->template_dir, $template);
				unset($smarty);
				?>
			</select><br />

		</div>
		<div class="help">
			<h5>Hilfe</h5>
			<p>Von wann bis wann soll die Umfrage aktiv sein. In dieser Zeit kann abgestimmt bzw. das Ergebnis angezeigt werden. Außerhalb dieses Zeitraums wird ein Hinweis eingeblendet, dass die Umfrage nicht mehr online ist.</p>
		</div>
		<input type="hidden" name="category" value="<?php echo $base_category->getID(); ?>" />
		<fieldset title="Von wann bis wann die Umfrage online sein soll. Ansonsten wird eine Fehlermeldung angezeigt, dass die Umfrage nicht mehr online ist">
			<legend>Start</legend>
			<?php if (isset($errors['start_poll'])) { echo '<p class="inline-error">' , $errors['start_poll'] , '</p>'; } ?>
			<label for="startyear"> Jahr: </label>
			<select name="start_year" id="startyear">
				<?php echo generateDateDropdown('year', $start_year); ?>
			</select>
			<label for="startmonth"> Monat: </label>
			<select name="start_month" id="startmonth">
				<?php echo generateDateDropdown('month', $start_month); ?>
			</select>
			<label for="startday"> Tag: </label>
			<select name="start_day" id="startday">
				<?php echo generateDateDropdown('day', $start_day); ?>
			</select>
			<label for="starthour"> Stunde: </label>
			<select name="start_hour" id="starthour">
				<?php echo generateDateDropdown('hour', $start_hour); ?>
			</select>
			<label for="startminute"> Minute: </label>
			<select name="start_minute" id="startminute">
				<?php echo generateDateDropdown('minute', $start_minute); ?>
			</select>
		</fieldset>
		<br />
		<fieldset title="Von wann bis wann die Umfrage online sein soll. Ansonsten wird eine Fehlermeldung angezeigt, dass die Umfrage nicht mehr online ist">
			<legend>Ende</legend>
			<?php if (isset($errors['end_poll'])) { echo '<p class="inline-error">' , $errors['end_poll'] , '</p>'; } ?>
			<label for="stopyear"> Jahr: </label>
			<select name="stop_year" id="stopyear">
				<?php echo generateDateDropdown('year', $stop_year); ?>
			</select>
			<label for="stopmonth"> Monat: </label>
			<select name="stop_month" id="stopmonth">
				<?php echo generateDateDropdown('month', $stop_month); ?>
			</select>
			<label for="stopday"> Tag: </label>
			<select name="stop_day" id="stopday">
				<?php echo generateDateDropdown('day', $stop_day); ?>
			</select>
			<label for="stophour"> Stunde: </label>
			<select name="stop_hour" id="stophour">
				<?php echo generateDateDropdown('hour', $stop_hour); ?>
			</select>
			<label for="stopminute"> Minute: </label>
			<select name="stop_minute" id="stopminute">
				<?php echo generateDateDropdown('minute', $stop_minute); ?>
			</select>
		</fieldset>


		<div class="help">
			<h5>Hilfe</h5>
			<p>Soll ein Besucher nur eine Antwortmöglichkeit auswählen können, oder mehrere gleichzeitig.</p>
		</div>
		<?php $checked = ($mc == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="mc" id="mc" value="1"<?php echo $checked; ?> /> <label for="mc" title="Soll die Umfrage die Auswahl mehrerer Antwortmöglichkeiten zulassen?"> Multiple Choise Umfrage</label><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Standardmäßig wird die Reihenfolge der Antwortmöglichkeiten per Zufall gemischt. Durch Aktivieren dieser Option werden diese immer den Positionsnummern nach geordnet (Je höher, umso weiter oben/vorne).</p>
		</div>
		<?php $checked = ($ordered_list == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="ordered_list" id="orderedlist" value="1"<?php echo $checked; ?> /> <label for="orderedlist" title="Je höher die Nummer, umso weiter oben platziert ist die Antwortmöglichkeit; Bei doppelten Positionsnummern wird nach den Alphabet nach geordnet"> Antworten der Positionsnummer nach ordnen</label> <small>(ansonsten zufällige Anordnung)</small><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Standardmäßig orientiert sich die Anzeige der Ergebnisse an der vorherigen Option (Mischen oder Positionsnummer). Sollte diese Option aktivert sein, so wird das Ergebnis den Stimmen nach geordnet (mehr Stimmen = weiter oben/vorne).</p>
		</div>
		<?php $checked = ($popular_result == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="popular_result" id="popularresult" value="1"<?php echo $checked; ?> /> <label for="popularresult" title="Antworten werden absteigend der Anzahl der Stimmen nach sortiert"> Ergebnisanzeige der Verteilung nach ordnen</label> <small>(ansonsten Anordnung wie Antwortenauflistung)</small><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Normalerweise werden die Stimmen aller Antwortmöglichkeiten zur Berechnung der Prozente herangezogen. Durch aktivieren dieser Option werden nur die aktiven Antworten verwendet.<br /><strong>Achtung:</strong> Falls diese Option verwendet wird sollte auch die Option „Nur aktive Antworten anzeigen“ aktiviert werden, da sonst Prozentwerte jenseits der 100% zustande kommen können.</p>
		</div>
		<?php $checked = ($use_only_active == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="use_only_active" id="useonlyactive" value="1"<?php echo $checked; ?> /> <label for="useonlyactive"> Nur aktive Antworten zur Ergebnisberechnung verwenden</label> <small>(ansonsten alle)</small><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Sollte immer aktiviert werden wenn auch die vorherige Option „Nur aktive Antworten zur Ergebnisberechnung verwenden“ verwendet wird. Falls alle Antwortmöglichkeiten angezeigt werden, sollten in der Regel die inaktiven Antworten auf der Umfrageseite nicht zur Auswahl zur Verfügung stehen und farblich anders gekennzeichnet sein (abhängig von der Vorlage; kann bei Spezialvorlagen anders sein).</p>
		</div>
		<?php $checked = ($display_only_active == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="display_only_active" id="displayonlyactive" value="1"<?php echo $checked; ?> /> <label for="displayonlyactive"> Nur aktive Antworten anzeigen</label> <small>(ansonsten alle)</small><br />

		<?php $checked = ($show_votes_percent == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="show_votes_percent" id="showvotespercent" value="1"<?php echo $checked; ?> /> <label for="showvotespercent"> Prozentwerte anzeigen</label><br />

		<?php $checked = ($show_votes_absolute == 1) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="show_votes_absolute" id="showvotesabsolute" value="1"<?php echo $checked; ?> /> <label for="showvotesabsolute"> Absolute Stimmen anzeigen</label><br />

	</fieldset>
	<br />
	<fieldset>
		<legend>Antworten</legend>
		<div class="help">
			<h5>Hilfe</h5>
			<p>Es können bei der Eingabe einer neuen Umfrage maximal <?php echo MAX_ANSWERS; ?> Antworten angegeben werden. Sollten mehr Antworten benötigt werden so können diese nachträglich eingegeben werden indem man die Umfrage editiert.<br />Für jede Antwortmöglichkeit können folgende Angaben gemacht werden:</p>
			<dl>
				<dt>Antwort</dt>
				<dd>Der Titel der Antwort (Pflichtfeld)</dd>
				<dt>Beschreibung</dt>
				<dd>Ein Text der die Antwort näher beschreibt (optional; Anzeige is abhängig von der verwendeten Vorlage)</dd>
				<dt>Bild (aktiv/inaktiv)</dt>
				<dd>Hier können Bilder für den jeweiligen Status upgeloaded werden. Solten bereits Bilder vorhanden sein, werden diese überschrieben</dd>
				<dt>Position</dt>
				<dd>Je höher die Nummer umso weiter oben/vorne wird die Antwort angezeigt, falls die Option „Antworten der Positionsnummer nach ordnen“ aktiviert ist.</dd>
				<dt>Aktiv</dt>
				<dd>Ist die Antwort auswählbar oder nicht. Die Anzeige ist von den oben gewählten Optionen abhängig.</dd>
			</dl>
		</div>

		<?php if (isset($errors['min_polloptions'])) { echo '<p class="inline-error">' , $errors['min_polloptions'] , '</p>'; } ?>
		<?php
		for ($i = 1; $i <= MAX_ANSWERS; $i++) {
			$a_title = 'a_title_' . $i;
			$a_desc = 'a_description_' . $i;
			$a_pos = 'a_position_' . $i;
			$a_active = 'a_active_' . $i;

			echo '<fieldset class="answer">';
			echo '<legend>Antwort ' , $i , '</legend>';
			echo '<table><tr><td class="data">';
			$req = ($i < 3) ? '<sup>*</sup>' : '';
			echo '<label for="a-title-' , $i , '">Antwort' , $req , ': </label>';
			echo '<input type="text" id="a-title-' , $i , '" name="a_title_' , $i , '" value="' , htmlspecialchars($$a_title) , '" maxlength="' , MAX_LENGTH_POLLOPTION_TITLE , '" /> ';
			echo '<label for="a-description-' , $i , '">Beschreibung: </label>';
			echo '<textarea id="a-description-' , $i , '" name="a_description_' , $i , '" cols="20" rows="3">' , htmlspecialchars($$a_desc) , '</textarea> ';
			echo '</td><td>';
			echo '<label for="a-position-' , $i , '" title="Je höher die Nummer, umso weiter oben platziert ist die Antwortmöglichkeit; Bei doppelten Positionsnummern wird nach den Alphabet nach geordnet">Position: </label>';
			echo '<select id="a-position-' , $i , '" name="a_position_' , $i , '">';
			$selected_pos = (isset($$a_pos) && $$a_pos > 0) ? $$a_pos : (100 - $i);
			for ($y = 99; $y >= 1; $y--) {
				$selected = ($selected_pos == $y) ? ' selected="selected"' : '';
				echo '<option value="' , $y , '"' , $selected , '>' , $y , '</option>';
			} // end for
			echo '</select><br />';

			$checked = ($$a_active == 1) ? ' checked="checked"' : '';
			echo '<div class="checkbox"><input type="checkbox" name="a_active_' , $i , '" id="a-active-' , $i , '" value="1"' , $checked , ' /> <label for="a-active-' , $i , '"> Aktiv</label></div>';
			echo '</td><td>';
			echo '</td></tr>';
			echo '<tr><td class="data" colspan="2"><label for="a-image-' , $i , '">Bild <small>(Aktiv)</small>:</label> <input class="file" name="a_image_' , $i , '" type="file" maxlength="100000" accept="image/*" id="a-image-' , $i , '" />';
			echo '<label for="a-inactiveimage-' , $i , '">Bild <small>(Inaktiv)</small>:</label> <input class="file" name="a_inactiveimage_' , $i , '" type="file" maxlength="100000" accept="image/*" id="a-inactiveimage-' , $i , '" /></td></tr></table></fieldset>';
		} // end for
		?>
	</fieldset>
	<input type="hidden" name="addpoll" value="1" />
	<input type="submit" id="addpoll" name="addpoll_submit" value="Umfrage speichern" onclick="disableButton(this, 'Wird erstellt…');" />
</form>
<?php
require_once 'footer_inc.php';
?>