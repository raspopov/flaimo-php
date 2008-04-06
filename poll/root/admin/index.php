<?php
$pagetitle = 'Übersicht';
require_once 'header_inc.php';
require_once 'errors_inc.php';
$chached_cat_dropdown = generateCategoryDropdown(new PollCategory(0), 1, new PollCategory(0));
if ($base_category->available() == TRUE) {
	$polls =& $list->getPolls(); ?>
	<h4>Umfragen</h4>
	<?php
	if (count($polls) > 0) { ?>
	<div class="help">
		<h5>Hilfe</h5>
		<p>Die Übersicht aller Umfragen in dieser Kategorie zeigt folgende Informationen an:</p>
		<dl>
			<dt>Titel</dt>
			<dd>Der Titel der Umfrage.</dd>
			<dt>Datum</dt>
			<dd>Das Erstellungsdatum der Umfrage.</dd>
			<dt>Online Von-Bis</dt>
			<dd>Von wann bis wann die Umfrage aktiv ist, sprich abgestimmt werden kann bzw. das Ergebnis angezeigt wird. Außerhalb dieses Zeitraums wird ein Hinweis angezeigt, dass die Umfrage nicht mehr online ist.</dd>
			<dt>St(immen)</dt>
			<dd>Die Anzahl an Besuchern die bis jetzt bei dieser Umfrage abgestimmt hat.</dd>
			<dt>Optionen</dt>
			<dd>Hier befinden sich die Möglichkeiten die gegebene Umfrage zu editieren (<img src="b_edit.png" width="10" height="10" alt="Symbol für „Editieren“" />) bzw. zu löschen (<img src="b_drop.png" width="10" height="10" alt="Symbol für „Löschen“" />). Um eine Umfrage zu löschen muss gleichzeitig ein Häckchen bei der jeweiligen linken Kontroll-Checkbox gesetzt werden (um unabsichtliches löschen zu verhindern).<br /> Weiters kann die jeweilige Umfrage in ein anderes Verzeichnis verschoben (<img src="b_lastpage.png" width="10" height="8" alt="Symbol für „Verschieben“" />) oder kopiert (<img src="b_copy.png" width="8" height="8" alt="Symbol für „Kopieren“" />) werden. Beim Kopieren wird der Titel der zu kopierenden Umfrage plus dem Wort „Kopie“ dahinter verwendet. Bei der Kopie werden alle Stimmen auf 0 zurückgesetzt!</dd>
			<dt>Links</dt>
			<dd>In der zweiten Zeile befinden sich die <acronym title="Uniform Ressource Locator" lang="en" xml:lang="en">URL</acronym> bzw. der Popup-Link die in das Redaktionssystem kopiert werden können.</dd>
		</dl>
	</div>

	<table id="polls">
		<tr>
			<th scope="col"></th>
			<th scope="col">Titel</th>
			<th scope="col">Datum</th>
			<th scope="col" title="Von - Bis">Online</th>
			<th scope="col"><abbr title="Stimmen">St.</abbr></th>
			<th scope="col" colspan="4">Optionen</th>
		</tr>
	<?php
		foreach ($polls as &$poll) {
			$row_col = altRowColor();
			$onlinestastus = 'online';
			$onlinestatus_name = 'Online';
			if (time() > $poll->getStopPoll()) {
				$onlinestastus = 'offline-past';
				$onlinestatus_name = 'Offline';
			} elseif (time() < $poll->getStartPoll()) {
				$onlinestastus = 'offline-future';
				$onlinestatus_name = 'Offline';
			} // end if

			echo '<form action="' , PHPSELF , '" method="post"><tr class="' , $row_col , '"><td rowspan="3" title="ID: ' , $poll->getID() , '" class="' , $onlinestastus , '">';
			echo '<input type="checkbox" name="del_check_' , $poll->getID() , '" value="1" />';
			echo '<input type="hidden" name="poll" value="' , $poll->getID() , '" />';
			echo '<input type="hidden" name="category" value="' , $cat_id  , '" /></td>';
			echo '<td rowspan="2" title="' , htmlspecialchars($poll->getDescription()) , '"><a href="' . SCRIPT_PATH_PUBLIC . 'poll_preview.php?poll=' , $poll->getID() , '" target="_blank">' , htmlspecialchars($poll->getTitle()) , '</a></td>';
			echo '<td rowspan="2" title="' , date('H:i:s', $poll->getCreated()) , '">' , date('Y-m-d', $poll->getCreated()) , '</td>';
			echo '<td title="' , date('H:i:s', $poll->getStartPoll()) , '">' , date('Y-m-d', $poll->getStartPoll()) , '</td>';
			echo '<td rowspan="2">' , $poll->getSumVotes() , '</td>';
			echo '<td rowspan="2" title="Editieren"><a href="edit_poll.php?poll=' , $poll->getID() , '&amp;category=' , $base_category->getID() , '" class="edit"><span>Editieren</span></a></td>';
			echo '<td rowspan="2" title="Löschen"><button type="submit" name="delete" class="delete"><span>Löschen</span></button></td>';
			echo '<td rowspan="2">Nach <select name="category_to_use" class="categories">';
			echo $chached_cat_dropdown;
			echo '</select></td>';
			echo '<td rowspan="2"><button title="Verschieben" type="submit" class="move" name="move"><span>Verschieben</span></button><br />';
			echo '<button title="Kopieren" type="submit" class="copy" name="copy"><span>Kopieren</span></button></td></tr>';
			echo '<tr class="' , $row_col , '">';
			echo '<td title="' , date('H:i:s', $poll->getStopPoll()) , '">' , date('Y-m-d', $poll->getStopPoll()) , '</td>';
			echo '</tr>';
			echo '<tr class="' , $row_col , '"><td colspan="4">URL: <input type="text" readonly="readonly" size="40" name="generated_url" value="' . SCRIPT_PATH_PUBLIC . 'poll.php?poll=' , $poll->getID() , '" /></td>';
			echo '<td colspan="4">Popup-Link: <input type="text" readonly="readonly" size="40" name="generated_popup_url" value="&lt;a href=&quot;#&quot; onclick=&quot;void window.open(' . "'" . SCRIPT_PATH_PUBLIC . 'poll.php?poll=' . $poll->getID() . "', 'poll" . $poll->getID() . "', 'width=540,height=490,menubar=0,resizable=0,toolbar=0,scrollbars=0,location=0,copyhistory=0,status=0,directories=0');" .  '&quot;&gt;Umfrage&lt;/a&gt;" /></td>';
			echo '</tr></form>';
		} // end foreach
		echo '</table>';
		echo '</tr></form>';

	} else {
		echo '<p id="nopollsavailable">Es befinden siche keine Umfragen in dieser Kategorie</p>';
	} // end if
	echo '<p id="createnewpoll"><a href="add_poll.php?category=' , $base_category->getID() , '">Neue Umfrage in Kategorie „' , htmlspecialchars($base_category->getName()) , '“ erstellen</a></p>';
?>
<form action="<?php echo PHPSELF; ?>" method="post" accept-charset="UTF-8">
	<fieldset id="newcatdata">
		<legend>Neue Unterkategorie erstellen</legend>
		<div class="help">
			<h5>Hilfe</h5>
			<p>Der eingegebene Name wird als Unterkategorie der aktuell angezeigten Kategorie angelegt.</p>
			<p>Alle Umfragen die in dieser neuen Kategorie angelegt werden und <strong>keine</strong> spezielle Vorlage zugewiesen bekommen haben, greifen auf die Standard-Vorlage der Kategorie zurück.</p>
		</div>
		<label for="new_category">Name<sup>*</sup>: </label>
		<input type="text" name="new_category" id="new_category" maxlength="<?php echo MAX_LENGTH_CATEGORY_NAME; ?>" /><br />
		<label for="new-category-template">Standardvorlage: </label>
		<select name="new_category_template" id="new-category-template">
		<?php echo generateTemplateDropdown($smarty->template_dir, $base_category->getTemplate()); ?>
		</select>
		<input type="hidden" name="category" value="<?php echo $base_category->getID(); ?>" />
		<input type="hidden" name="create_new_category" value="1" />
		<input type="submit" id="newcat" name="create_new_category_submit" value="Erstellen" onclick="disableButton(this, 'Wird erstellt…');" />
	</fieldset>
</form>
<?php
} else {
	echo '<p>Kategorie nicht gefunden</p>';
} // end if
require_once 'footer_inc.php';
?>