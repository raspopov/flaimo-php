<?php
$pagetitle = 'Umfrage editieren';
require_once 'header_inc.php';

if (isset($_POST['poll'])) {
	$poll_id =& $_POST['poll'];
} elseif (isset($_GET['poll'])) {
	$poll_id =& $_GET['poll'];
} else {
	header('Location: index.php');
	die();
} // end if

$poll_to_edit = new Poll($poll_id);
$poll_to_edit->fetchData();
$polloption_list = new PollOptionList($poll_to_edit, 'edit');
$polloptions =& $polloption_list->getPollOptions();
define('MAX_ADD_ANSWERS', MAX_ANSWERS - count($polloptions));

if (isset($_POST['editpoll'])) {
	if (strlen(trim($_POST['title'])) > 0) {
		$poll_to_edit->setTitle($_POST['title']);
	} else {
		$errors['poll_title'] = 'Der Titel darf nicht leer sein';
	} // end if

	if (strlen(trim($_POST['button_label'])) > 0) {
		$poll_to_edit->setButtonLabel($_POST['button_label']);
	} else {
		$errors['button_label'] = 'Der Button-Text darf nicht leer sein';
	} // end if

	if (strlen(trim($_POST['cp'])) > 0) {
		$poll_to_edit->setCP($_POST['cp']);
	} else {
		$errors['cp'] = 'Der Zählpixel darf nicht leer sein';
	} // end if

	$poll_to_edit->setDescription($_POST['description']);
	$poll_to_edit->setCategory(new PollCategory($_POST['e_category']));

	if (isset($_POST['start_year']) && isset($_POST['start_month']) &&
		isset($_POST['start_day']) && isset($_POST['start_hour']) &&
		isset($_POST['start_minute']) &&
		checkdate($_POST['start_month'], $_POST['start_day'], $_POST['start_year']) == TRUE &&
		$_POST['start_hour'] >= 0 && $_POST['start_hour'] < 24 &&
		$_POST['start_minute'] >= 0 && $_POST['start_minute'] < 60) {
		$start = mktime($_POST['start_hour'], $_POST['start_minute'], 0, $_POST['start_month'], $_POST['start_day'], $_POST['start_year']);
		$poll_to_edit->setStartPoll($start);
	} else {
		$errors['start_poll'] = 'Sie müssen ein gültige Start-Datum angeben';
	} // end if

	if (isset($_POST['stop_year']) && isset($_POST['stop_month']) &&
		isset($_POST['stop_day']) && isset($_POST['stop_hour']) &&
		isset($_POST['stop_minute']) &&
		checkdate($_POST['stop_month'], $_POST['stop_day'], $_POST['stop_year']) == TRUE &&
		$_POST['stop_hour'] >= 0 && $_POST['stop_hour'] < 24 &&
		$_POST['stop_minute'] >= 0 && $_POST['stop_minute'] < 60) {
		$start = mktime($_POST['stop_hour'], $_POST['stop_minute'], 0, $_POST['stop_month'], $_POST['stop_day'], $_POST['stop_year']);
		$poll_to_edit->setStopPoll($start);
	} else {
		$errors['end_poll'] = 'Sie müssen ein gültige End-Datum angeben';
	} // end if

	$poll_to_edit->setRevote($_POST['revote']);

	$mc = (isset($_POST['mc']) && $_POST['mc'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setMultipleChoise($mc);

	$ol = (isset($_POST['ordered_list']) && $_POST['ordered_list'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setShowOrderedList($ol);

	$pr = (isset($_POST['popular_result']) && $_POST['popular_result'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setShowPopularResult($pr);

	$oa = (isset($_POST['use_only_active']) && $_POST['use_only_active'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setUseOnlyActiveOptions($oa);

	$da = (isset($_POST['display_only_active']) && $_POST['display_only_active'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setDisplayOnlyActiveOptions($da);

	$sp = (isset($_POST['show_votes_percent']) && $_POST['show_votes_percent'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setShowVotesPercent($sp);

	$sa = (isset($_POST['show_votes_absolute']) && $_POST['show_votes_absolute'] == 1) ? TRUE : FALSE;
	$poll_to_edit->setShowVotesAbsolute($sa);

	$poll_to_edit->setTemplate($_POST['template']);
	$poll_to_edit->setCPType($_POST['cp_type']);

	foreach ($polloptions as &$polloption) {
		$polloption->fetchData();
		$polloption->setDescription($_POST['a_description_' . $polloption->getID()]);
		$polloption->setPosition($_POST['a_position_' . $polloption->getID()]);
		$polloption->setVotes((int) $_POST['a_votes_' . $polloption->getID()]);
		$active = (isset($_POST['a_active_' . $polloption->getID()]) && $_POST['a_active_' . $polloption->getID()] == 1) ? TRUE : FALSE;
		$polloption->setIsActive($active);
		$polloption->setDescription($_POST['a_description_' . $polloption->getID()]);

		if (strlen(trim($_POST['a_title_' . $polloption->getID()])) > 0) {
			$polloption->setTitle($_POST['a_title_' . $polloption->getID()]);
		} else {
			$errors['polloption_titles'] = 'Antwort-Felder dürfen nicht leer sein';
			continue;
		} // end if

		if (isset($_POST['a_deleteimages_' . $polloption->getID()]) &&
			$_POST['a_deleteimages_' . $polloption->getID()] == 1) {
			if ($polloption->removeImage() == FALSE) {
				$errors['polloption_delimage_' . $polloption->getID()] = 'Fehler beim löschen des (aktiven) Bildes für Antwort „' . htmlspecialchars($polloption->getTitle()) . '“';
			} // end if
			if ($polloption->removeImage(INACTIVE_SUFIX) == FALSE) {
				$errors['polloption_delinactiveimage_' . $polloption->getID()] = 'Fehler beim löschen des (inaktiven) Bildes für Antwort „' . htmlspecialchars($polloption->getTitle()) . '“';
			} // end if
		} // end if

		if (isset($_FILES['a_image_' . $polloption->getID()]) &&
			strlen(trim($_FILES['a_image_' . $polloption->getID()]['name'])) > 0) {
			if ($polloption->addImage($_FILES['a_image_' . $polloption->getID()]) == FALSE) {
				$errors['polloption_addimage_' . $polloption->getID()] = 'Fehler beim uploaden des Bildes für Antwort „' . htmlspecialchars($polloption->getTitle()) . '“';
			} // end if
		} // end if

		if (isset($_FILES['a_inactiveimage_' . $polloption->getID()]) &&
			strlen(trim($_FILES['a_inactiveimage_' . $polloption->getID()]['name'])) > 0) {
			if ($polloption->addImage($_FILES['a_inactiveimage_' . $polloption->getID()], INACTIVE_SUFIX) == FALSE) {
				$errors['polloption_addinactiveimage_' . $polloption->getID()] = 'Fehler beim uploaden des Bildes für Antwort „' . htmlspecialchars($polloption->getTitle()) . '“';
			} // end if
		} // end if

		$polloption->updateData();
	} // end foreach

	if (count($errors) < 1) {
		if ($poll_to_edit->updateData() == TRUE) {
			if (isset($_POST['reset_votes']) && $_POST['reset_votes'] == 1) {
				$poll_to_edit->resetVotes();
			} // end if
			$notices[] = 'Änderungen gespeichert';
			$poll_to_edit->fetchData();
		} else {
			$errors['edit_poll'] = 'Fehler beim Schreiben in die Datenbank';
		} // end if
	} else {
		$poll_to_edit->fetchData();
	} // end if
} elseif(isset($_POST['addpolloption'])) {
	if (strlen(trim($_POST['new_a_title'])) < 1) {
		$errors['newpolloption_title'] = 'Die Antwort darf nicht leer sein';
	} else {
		$active = (isset($_POST['new_a_active']) && $_POST['new_a_active'] == 1) ? TRUE : FALSE;
		if (($added_polloption = $polloption_list->addPollOption($_POST['new_a_title'], $_POST['new_a_description'], $_POST['new_a_position'], $active)) != FALSE) {
			/*
			header('Location: ' . PHPSELF . '?poll=' . $poll_to_edit->getID());
			die();
			*/

			if (isset($_FILES['newimage']) &&
				strlen(trim($_FILES['newimage']['name'])) > 0) {
				if ($added_polloption->addImage($_FILES['newimage']) == FALSE) {
					$errors['newimage'] = 'Fehler beim uploaden des Bildes für die Antwort „' . htmlspecialchars($added_polloption->getTitle()) . '“';
				} // end if
			} // end if

			if (isset($_FILES['newinactiveimage']) &&
				strlen(trim($_FILES['newinactiveimage']['name'])) > 0) {
				if ($added_polloption->addImage($_FILES['newinactiveimage'], INACTIVE_SUFIX) == FALSE) {
					$errors['newinactiveimage'] = 'Fehler beim uploaden des Bildes für die Antwort „' . htmlspecialchars($added_polloption->getTitle()) . '“';
				} // end if
			} // end if

			$polloption_list->fetchData();
			$polloptions =& $polloption_list->getPollOptions();
			$notices[] = 'Neue Antwort „' . $_POST['new_a_title'] . '“ eingetragen';
		} else {
			$errors['write_newpolloption'] = 'Fehler beim Schreiben in die Datenbank';
		} // end if
	}  // end if
} elseif (isset($_POST['deletepolloption'])) {
	$pos_to_delete = 0;
	foreach ($polloptions as &$polloption) {
		if (isset($_POST['a_delete_' . $polloption->getID()]) &&
			$_POST['a_delete_' . $polloption->getID()] == 1) {
			$pos_to_delete++;
		} // end if
	} // end foreach

	if ((count($polloptions) - $pos_to_delete) < 2) {
		$errors['min_polloptions'] = 'Löschen nicht möglich; Es müssen mindestens 2 Antworten vorhanden sein';
	} else {
		foreach ($polloptions as &$polloption) {
			if (isset($_POST['a_delete_' . $polloption->getID()]) &&
				$_POST['a_delete_' . $polloption->getID()] == 1) {
				$optionname = $polloption->getTitle();
				if ($polloption_list->deletePollOption($polloption) == TRUE) {
					$notices[] = 'Antwort „' . $optionname . '“ gelöscht';
				} else {
					$errors['del_polloption'] = 'Fehler beim löschen der Antwort';
				} // end if
			} // end if
		} // end foreach
		$polloptions =& $polloption_list->getPollOptions();
	} // end if
} // end if

require_once 'errors_inc.php';
?>
<p id="preview"><a href="<?php echo SCRIPT_PATH_PUBLIC . 'poll_preview.php?poll=' . $poll_to_edit->getID(); ?>" target="_blank">Vorschau</a></p>
<form action="<?php echo PHPSELF; ?>" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
	<fieldset>
		<legend>Umfrage-Daten</legend>
		<div id="basicdata">
			<label for="title">Titel<sup>*</sup>:</label>
			<input type="text" name="title" id="title" value="<?php echo htmlspecialchars($poll_to_edit->getTitle()); ?>" maxlength="<?php echo MAX_LENGTH_POLL_TITLE; ?>" />
			<?php if (isset($errors['poll_title'])) { echo '<span class="inline-error">' , $errors['poll_title'] , '</span>'; } ?>
			<br />
			<label for="description">Beschreibung:</label>
			<textarea name="description" id="description" rows="4" cols="40"><?php echo htmlspecialchars($poll_to_edit->getDescription()); ?></textarea><br />

			<div class="help">
				<h5>Hilfe</h5>
				<p>Der angegebene Text dient als Beschriftung des Buttons der zum Abschicken der Stimme auf der Umfrage-Seite steht.</p>
			</div>
			<label for="buttonlabel">Button-Text<sup>*</sup>:</label>
			<input type="text" name="button_label" id="buttonlabel" value="<?php echo htmlspecialchars($poll_to_edit->getButtonLabel()); ?>" maxlength="<?php echo MAX_LENGTH_POLL_BUTTON; ?>" />
			<?php if (isset($errors['button_label'])) { echo '<span class="inline-error">' , $errors['button_label'] , '</span>'; } ?>
			<br />
			<label for="cp">Zählpixel<sup>*</sup>:</label>
			<input type="text" name="cp" id="cp" value="<?php echo htmlspecialchars($poll_to_edit->getCP()); ?>" maxlength="<?php echo MAX_LENGTH_POLL_CP; ?>" />
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
					$selected = ($cp_type_id == $poll_to_edit->getCPType()) ? ' selected="selected" ': '';
					echo '<option value="' , $cp_type_id , '"' , $selected , '>' , $cp_type_name , '</option>';
				} // end foreach
				?>
			</select>
			<br />
			<div class="help">
				<h5>Hilfe</h5>
				<p>Die Kategorie dient neben der Strukturierung der Umfragen auch dazu selbigen eine Standard-Vorlage zuzuweisen, falls diese über keine eigene verfügen.</p>
			</div>
			<label for="e-category">Kategorie:</label>
			<select name="e_category" id="e-category">
				<?php echo generateCategoryDropdown(new PollCategory(0), 1, $poll_to_edit->getCategory()); ?>
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
					$selected = ($seconds == $poll_to_edit->getRevote()) ? ' selected="selected"': '';
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
				<option value="" style="font-style:italic;">Kategorie-Vorlage verwenden</option>
				<?php
				$smarty = new PollSmarty;
				echo generateTemplateDropdown($smarty->template_dir, $poll_to_edit->getTemplate());
				unset($smarty);
				?>
			</select>
		</div>
		<div class="help">
			<h5>Hilfe</h5>
			<p>Von wann bis wann soll die Umfrage aktiv sein. In dieser Zeit kann abgestimmt bzw. das Ergebnis angezeigt werden. Außerhalb dieses Zeitraums wird ein Hinweis eingeblendet, dass die Umfrage nicht mehr online ist.</p>
		</div>
		<fieldset title="Von wann bis wann die Umfrage online sein soll. Ansonsten wird eine Fehlermeldung angezeigt, dass die Umfrage nicht mehr online ist">
			<legend>Start</legend>
			<?php if (isset($errors['start_poll'])) { echo '<p class="inline-error">' , $errors['start_poll'] , '</p>'; } ?>
			<label for="startyear"> Jahr: </label>
			<select name="start_year" id="startyear">
			<?php echo generateDateDropdown('year', date('Y', $poll_to_edit->getStartPoll())); ?>
			</select>
			<label for="startmonth"> Monat: </label>
			<select name="start_month" id="startmonth">
			<?php echo generateDateDropdown('month', date('m', $poll_to_edit->getStartPoll())); ?>
			</select>
			<label for="startday"> Tag: </label>
			<select name="start_day" id="startday">
			<?php echo generateDateDropdown('day', date('d', $poll_to_edit->getStartPoll())); ?>
			</select>
			<label for="starthour"> Stunde: </label>
			<select name="start_hour" id="starthour">
			<?php echo generateDateDropdown('hour', date('H', $poll_to_edit->getStartPoll())); ?>
			</select>
			<label for="startminute"> Minute: </label>
			<select name="start_minute" id="startminute">
			<?php echo generateDateDropdown('minute', date('i', $poll_to_edit->getStartPoll())); ?>
			</select>
		</fieldset>
		<br />
		<fieldset title="Von wann bis wann die Umfrage online sein soll. Ansonsten wird eine Fehlermeldung angezeigt, dass die Umfrage nicht mehr online ist">
			<legend>Ende</legend>
			<?php if (isset($errors['end_poll'])) { echo '<p class="inline-error">' , $errors['end_poll'] , '</p>'; } ?>
			<label for="stopyear"> Jahr: </label>
			<select name="stop_year" id="stopyear">
			<?php echo generateDateDropdown('year', date('Y', $poll_to_edit->getStopPoll())); ?>
			</select>
			<label for="stopmonth"> Monat: </label>
			<select name="stop_month" id="stopmonth">
			<?php echo generateDateDropdown('month', date('m', $poll_to_edit->getStopPoll())); ?>
			</select>
			<label for="stopday"> Tag: </label>
			<select name="stop_day" id="stopday">
			<?php echo generateDateDropdown('day', date('d', $poll_to_edit->getStopPoll())); ?>
			</select>
			<label for="stophour"> Stunde: </label>
			<select name="stop_hour" id="stophour">
			<?php echo generateDateDropdown('hour', date('H', $poll_to_edit->getStopPoll())); ?>
			</select>
			<label for="stopminute"> Minute: </label>
			<select name="stop_minute" id="stopminute">
			<?php echo generateDateDropdown('minute', date('i', $poll_to_edit->getStopPoll())); ?>
			</select>
		</fieldset>



		<div class="help">
			<h5>Hilfe</h5>
			<p>Soll ein Besucher nur eine Antwortmöglichkeit auswählen können, oder mehrere gleichzeitig.</p>
		</div>
		<?php $checked = ($poll_to_edit->getMultipleChoise() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="mc" id="mc" value="1"<?php echo $checked; ?> /> <label for="mc" title="Soll die Umfrage die Auswahl mehrerer Antwortmöglichkeiten zulassen?"> Multiple Choise Umfrage</label><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Standardmäßig wird die Reihenfolge der Antwortmöglichkeiten per Zufall gemischt. Durch Aktivieren dieser Option werden diese immer den Positionsnummern nach geordnet (Je höher, umso weiter oben/vorne).</p>
		</div>
		<?php $checked = ($poll_to_edit->getShowOrderedList() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="ordered_list" id="orderedlist" value="1"<?php echo $checked; ?> /> <label for="orderedlist" title="Je höher die Nummer, umso weiter oben platziert ist die Antwortmöglichkeit; Bei doppelten Positionsnummern wird nach den Alphabet nach geordnet"> Antworten der Positionsnummer nach ordnen</label> <small>(ansonsten zufällige Anordnung)</small><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Standardmäßig orientiert sich die Anzeige der Ergebnisse an der vorherigen Option (Mischen oder Positionsnummer). Sollte diese Option aktivert sein, so wird das Ergebnis den Stimmen nach geordnet (mehr Stimmen = weiter oben/vorne).</p>
		</div>
		<?php $checked = ($poll_to_edit->getShowPopularResult() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="popular_result" id="popularresult" value="1"<?php echo $checked; ?> /> <label for="popularresult" title="Antworten werden absteigend der Anzahl der Stimmen nach sortiert"> Ergebnisanzeige der Verteilung nach ordnen</label> <small>(ansonsten Anordnung wie Antwortenauflistung)</small><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Normalerweise werden die Stimmen aller Antwortmöglichkeiten zur Berechnung der Prozente herangezogen. Durch aktivieren dieser Option werden nur die aktiven Antworten verwendet.<br /><strong>Achtung:</strong> Falls diese Option verwendet wird sollte auch die Option „Nur aktive Antworten anzeigen“ aktiviert werden, da sonst Prozentwerte jenseits der 100% zustande kommen können.</p>
		</div>
		<?php $checked = ($poll_to_edit->getUseOnlyActiveOptions() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="use_only_active" id="useonlyactive" value="1"<?php echo $checked; ?> /> <label for="useonlyactive"> Nur aktive Antworten zur Ergebnisberechnung verwenden</label> <small>(ansonsten alle)</small><br />

		<div class="help">
			<h5>Hilfe</h5>
			<p>Sollte immer aktiviert werden wenn auch die vorherige Option „Nur aktive Antworten zur Ergebnisberechnung verwenden“ verwendet wird. Falls alle Antwortmöglichkeiten angezeigt werden, sollten in der Regel die inaktiven Antworten auf der Umfrageseite nicht zur Auswahl zur Verfügung stehen und farblich anders gekennzeichnet sein (abhängig von der Vorlage; kann bei Spezialvorlagen anders sein).</p>
		</div>
		<?php $checked = ($poll_to_edit->getDisplayOnlyActiveOptions() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="display_only_active" id="displayonlyactive" value="1"<?php echo $checked; ?> /> <label for="displayonlyactive"> Nur aktive Antworten anzeigen</label> <small>(ansonsten alle)</small><br />

		<?php $checked = ($poll_to_edit->getShowVotesPercent() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="show_votes_percent" id="showvotespercent" value="1"<?php echo $checked; ?> /> <label for="showvotespercent"> Prozentwerte anzeigen</label><br />

		<?php $checked = ($poll_to_edit->getShowVotesAbsolute() == TRUE) ? ' checked="checked"' : ''; ?>
		<input type="checkbox" name="show_votes_absolute" id="showvotesabsolute" value="1"<?php echo $checked; ?> /> <label for="showvotesabsolute"> Absolute Stimmen anzeigen</label><br />


		<div class="help">
			<h5>Hilfe</h5>
			<p>Setzt die Anzahl der Stimmen für alle Antworten auf 0 zurück.</p>
		</div>
		<input type="checkbox" name="reset_votes" id="resetvotes" value="1" /> <label for="resetvotes"> Stimmen zurücksetzen</label><br />
	</fieldset>
	<input type="submit" class="editpoll" name="editpoll" value="Änderungen speichern" />
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
				<dt>Stimmen</dt>
				<dd>Anzahl der absoluten Stimmen für diese Antwort</dd>
				<dt>Position</dt>
				<dd>Je höher die Nummer umso weiter oben/vorne wird die Antwort angezeigt, falls die Option „Antworten der Positionsnummer nach ordnen“ aktiviert ist.</dd>
				<dt>Aktiv</dt>
				<dd>Ist die Antwort auswählbar oder nicht. Die Anzeige ist von den oben gewählten Optionen abhängig.</dd>
				<dt>Bilder löschen</dt>
				<dd>Durch markieren der Checkbox werden alle Bilder die dieser Antwort zugeteilt sind gelöscht. Sollten im gleichen Schritt neue Bilder upgeloaded werden so werden diese verwendet.</dd>
				<dt>Checkbox auf roten Hintergrund</dt>
				<dd>Falls das Häckchen gesetzt und der Button „Löschen“ (am Ende der Seite) gedrückt wird, so wird die betreffende Antwort gelöscht. Es müssen aber mindestens zwei Antwortmöglichkeiten übrig bleiben!</dd>
			</dl>
		</div>
		<?php if (isset($errors['min_polloptions'])) { echo '<p class="inline-error">' , $errors['min_polloptions'] , '</p>'; } ?>
		<?php
		$i = 1;
		foreach ($polloptions as &$polloption) {
			echo '<fieldset class="answer">';
			echo '<legend>Antwort ' , $i , '</legend>';
			echo '<table><tr><td class="thumb" rowspan="2">';
			if (file_exists($polloption->getImagePath())) {
				echo '<img src="' , IMAGES_PATH , $poll_to_edit->getID() , '/' , $polloption->getID() , '.jpg" alt="Thumbnail für Antwort ' , $i , ' (Aktiv)" width="50" /><br />';
			} // end if
			if (file_exists($polloption->getImagePath(INACTIVE_SUFIX))) {
				echo '<img src="' , IMAGES_PATH , $poll_to_edit->getID() , '/' , $polloption->getID() , '_' , INACTIVE_SUFIX , '.jpg" alt="Thumbnail für Antwort ' , $i , ' (Inaktiv)" width="50" />';
			} // end if
			echo '</td><td class="data">';
			echo '<label for="a-title-' , $i , '">Antwort: </label>';
			echo '<input type="text" class="a-title" id="a-title-' , $i , '" name="a_title_' , $polloption->getID() , '" value="' , htmlspecialchars(stripslashes($polloption->getTitle())) , '" maxlength="' , MAX_LENGTH_POLLOPTION_TITLE , '" /><br />';
			echo '<label for="a-description-' , $i , '">Beschreibung: </label>';
			echo '<textarea id="a-description-' , $i , '" name="a_description_' , $polloption->getID() , '" rows="3" cols="20">' , htmlspecialchars(stripslashes($polloption->getDescription())) , '</textarea> ';
			echo '</td><td>';
			echo '<label for="a-votes-' , $i , '">Stimmen: </label>';
			echo '<input type="text" id="a-votes-' , $i , '" name="a_votes_' , $polloption->getID() , '" value="' , $polloption->getVotes() , '" size="6" /><br />';
			echo '<label for="a-position-' , $i , '" title="Je höher die Nummer, umso weiter oben platziert ist die Antwortmöglichkeit; Bei doppelten Positionsnummern wird nach den Alphabet nach geordnet">Position: </label>';
			echo '<select id="a-position-' , $i , '" name="a_position_' , $polloption->getID() , '">';
			for ($y = 99; $y >= 1; $y--) {
				$selected = ($polloption->getPosition() == $y) ? ' selected="selected"' : '';
				echo '<option value="' , $y , '"' , $selected , '>' , $y , '</option>';
			} // end for
			echo '</select><br />';

			$checked = ($polloption->getIsActive() == TRUE) ? ' checked="checked"' : '';
			echo '<div class="checkbox"><input type="checkbox" name="a_active_' , $polloption->getID() , '" id="a-active-' , $polloption->getID() , '" value="1"' , $checked , ' /> <label for="a-active-' , $polloption->getID() , '"> Aktiv</label><br /><br />';
			echo '<input type="checkbox" name="a_deleteimages_' , $polloption->getID() , '" id="a-deleteimages-' , $i , '" value="1" /> <label for="a-deleteimages-' , $i , '"> Bilder löschen</label></div>';
			echo '</td><td class="delete" rowspan="2">';
			echo '<input type="checkbox" name="a_delete_' , $polloption->getID() , '" id="a-delete-' , $polloption->getID() , '" value="1" />'; // <label for="a-delete-' , $polloption->getID()  , '"> Löschen?</label>';
			echo '</td></tr>';
			echo '<tr><td colspan="2" class="data"><label for="a-image-' , $i , '">Bild <small>(Aktiv)</small>:</label> <input class="file" name="a_image_' , $polloption->getID() , '" type="file" maxlength="100000" accept="image/*" id="a-image-' , $i , '" />';
			echo '<label for="a-inactiveimage-' , $i , '">Bild <small>(Inaktiv)</small>:</label> <input class="file" name="a_inactiveimage_' , $polloption->getID() , '" type="file" maxlength="100000" accept="image/*" id="a-inactiveimage-' , $i , '" /></td></tr>';
			echo '</table></fieldset>';
			$i++;
		} // end foreach
		?>
		<div id="deletepolloption"><input type="submit" name="deletepolloption" value="Löschen" title="Markierte Antworten löschen" /></div>
	</fieldset>
	<input type="hidden" name="poll" value="<?php echo $poll_id; ?>" />
	<input type="hidden" name="category" value="<?php echo $base_category->getID(); ?>" />
	<input type="submit" class="editpoll" name="editpoll" value="Änderungen speichern" />
</form>

<form action="<?php echo PHPSELF; ?>" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
	<fieldset class="answer">
	<legend>Neue Antwort hinzufügen</legend>
		<div class="help">
			<h5>Hilfe</h5>
			<p>Es gelten die gleichen Hinweise wie bei der vorherigen Hilfebox.</p>
		</div>

		<?php if (isset($errors['newpolloption_title'])) { echo '<p class="inline-error">' , $errors['newpolloption_title'] , '</p>'; } ?>

		<table>
			<tr>
				<td class="data">
					<label for="newatitle">Antwort: </label>
					<input type="text" name="new_a_title" id="newatitle" />
					<label for="newadesc">Beschreibung: </label>
					<textarea name="new_a_description" id="newadesc" rows="3" cols="20"></textarea>
				</td>
				<td>
					<label for="newaposition">Position: </label>
					<select id="newaposition" name="new_a_position">
						<?php
						$pos = 99 - count($polloptions);
						for ($y = 99; $y >= 1; $y--) {
							$selected = ($pos == $y) ? ' selected="selected"' : '';
							echo '<option value="' , $y , '"' , $selected , '>' , $y , '</option>';
						} // end for
						?>
					</select><br />
					<input type="checkbox" class="active" name="new_a_active" id="newaactive" value="1" checked="checked" /> <label for="newaactive"> Aktiv</label>
					<input type="hidden" name="poll" value="<?php echo $poll_id; ?>" />
					<input type="hidden" name="addpolloption" value="1" />
					<input type="hidden" name="category" value="<?php echo $base_category->getID(); ?>" />
				</td>
				<td rowspan="2">
					<input type="submit" id="addpolloption" name="addpolloption_submit" value="Erstellen" onclick="disableButton(this, 'Wird erstellt…');"  />
				</td>
			</tr>
			<tr>
				<td colspan="2" class="data">
					<label for="newimage">Bild <small>(Aktiv)</small>:</label> <input class="file" name="newimage" type="file" maxlength="100000" accept="image/*" id="newimage" />
					<label for="newinactiveimage">Bild <small>(Inaktiv)</small>:</label> <input class="file" name="newinactiveimage" type="file" maxlength="100000" accept="image/*" id="newinactiveimage" />
				</td>
			</tr>
		</table>
	</fieldset>
</form>
<?php
require_once 'footer_inc.php'; ?>