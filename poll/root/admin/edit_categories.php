<?php
$pagetitle = 'Kategorien';
require_once 'header_inc.php';

if (isset($_POST['submit']) && isset($_POST['cat_id']) &&
	isset($_POST['name']) && isset($_POST['category']) &&
	isset($_POST['template'])) {
	$cat_to_edit = new PollCategory($_POST['cat_id']);
	$cat_to_edit->fetchData();
	if (strlen(trim($_POST['name'])) > 0) {
		$cat_to_edit->setName($_POST['name']);
	} // end if
	$cat_to_edit->setName($_POST['name']);
	$cat_to_edit->setParent(new PollCategory($_POST['category']));
	$cat_to_edit->setPosition($_POST['position']);
	$cat_to_edit->setTemplate($_POST['template']);
	if ($cat_to_edit->updateData() == TRUE) {
		$notices[] = 'Kategorie erfolgreich geändert';
	} else {
		$errors['update_cat'] = 'Fehler beim Updaten der Kategorie';
	} // end if
} elseif (isset($_POST['c_delete']) && isset($_POST['del_check']) && $_POST['del_check'] == $_POST['cat_id']) {
	$cat_to_delete = new PollCategory($_POST['cat_id']);
	$tmp = new PollCategoryList($cat_to_delete);
	if ($tmp->deleteCategory($cat_to_delete) == TRUE) {
		$notices[] = 'Kategorie erfolgreich gelöscht';
	} else {
		$errors['delete_cat'] = 'Fehler beim Löschen der Kategorie';
	} // end if
	unset($cat_to_delete, $tmp);
} // end if

$display = 'all';
$category_list = new PollCategoryList($display);
$categories =& $category_list->getChildren();

require_once 'errors_inc.php';
?>
<table id="categories">
	<tr>
		<th scope="col"></th>
		<th scope="col">ID</th>
		<th scope="col">Name</th>
		<th scope="col">Elternkategorie</th>
		<th scope="col">Template</th>
		<th scope="col">Position</th>
		<th scope="col" colspan="2">Optionen</th>
	</tr>
	<?php
	foreach ($categories as &$category) {
		echo '<form action="' , PHPSELF , '" method="post" accept-charset="UTF-8">';
		echo '<tr>';
		echo '<td><input type="checkbox" name="del_check" value="' , $category->getID() , '" /></td>';
		echo '<td>' , $category->getID() , '</td>';
		echo '<td><input type="text" name="name" value="' , htmlspecialchars($category->getName()) , '" /></td>';
		echo '<td>';
		if ($category->getID() != 1) {
			echo '<select name="category">';
			echo generateCategoryDropdown(new PollCategory(0), 1, $category->getParent());
			echo '<select>';
		} // end if
		echo '</td>';
		echo '<td>';
		echo '<select name="template">';
		$smarty = new PollSmarty;
		echo generateTemplateDropdown($smarty->template_dir, $category->getTemplate());
		unset($smarty);
		echo '</select>';
		echo '</td>';
		echo '<td>';
		echo '<select name="position">';
		for ($i = 0; $i < 100; $i++) {
			$selected = ($category->getPosition() == $i) ? ' selected="selected"' : '';
			echo '<option value="' , $i , '"' , $selected , '>' , $i , '</option>';
		} // end for
		echo '</select>';
		echo '<input type="hidden" name="cat_id" value="' , $category->getID() , '" />';
		echo '</td>';
		echo '<td><input type="submit" name="submit" value="Ändern" /></td>';
		echo '<td>';
		if ($category->getID() != 1) {
			echo '<input type="submit" name="c_delete" value="Löschen" />';
		} // end if
		echo '</td>';
		echo '</tr>';
		echo '</form>';
	} // end foreach
echo '</table>';
require_once 'footer_inc.php';
?>