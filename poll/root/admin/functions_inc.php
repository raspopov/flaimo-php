<?php
/* create dropdown */
function generateCategoryDropdown(PollCategory &$start_cat = FALSE, $level = 1, PollCategory &$preselected_cat = FALSE) {
	//static $cached_list;
	//static $finished = FALSE;
	$html = '';
/*
	if (isset($chached_list)) {
		foreach ($chached_list as $id => $data) {
			list($name, $level) = $data;
			$selected = ($preselected_cat != FALSE && $preselected_cat->getID() == $id) ? ' selected="selected"': '';
			$html .= '<option value="' . $id . '"' . $selected . '>' . str_repeat('- ', $level) . ' ' . $name . '</option>';
		} // end foreach
		return $html;
	} // end if
*/
	$list = new PollCategoryList($start_cat);
	$children =& $list->getChildren();

	if (count($children) < 1) {
		return $html;
	} // end if

	foreach ($children as &$child) {
		//$cached_list[$child->getID()] = array('level' => $level, 'name' => $child->getName());
		$selected = ($preselected_cat != FALSE && $preselected_cat->getID() == $child->getID()) ? ' selected="selected"': '';
		$html .= '<option class="level-' . $level . '" value="' . $child->getID() . '"' . $selected . '>' . str_repeat('- ', $level) . ' ' . htmlspecialchars($child->getName()) . '</option>';
		$html .= generateCategoryDropdown($child, $level + 1, $preselected_cat);
	} // end foreach
	return $html;
} // end function


function generateDateDropdown($type = 'year', $preselected = 0) {
	$types = array(
				'year' => array('label' => 'Jahr', 'start' => (date('Y') - 1), 'stop' => (date('Y') + 10)),
				'month' => array('label' => 'Monat', 'start' => 1, 'stop' => 12),
				'day' => array('label' => 'Tag', 'start' => 1, 'stop' => 31),
				'hour' => array('label' => 'Stunde', 'start' => 0, 'stop' => 23),
				'minute' => array('label' => 'Minute', 'start' => 0, 'stop' => 59),
				);
	//$str = '<optgroup label="' . $types[$type]['label'] . '">';
	$str = '';
	for($i = $types[$type]['start']; $i <= $types[$type]['stop']; $i++) {
		$selected = ($i == $preselected) ? ' selected="selected"' : '';
		$str .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
	} // end for
	return $str; // . '</optgroup>';
} // end function


function generateTemplateDropdown($template_dir = '', $selected_dir = '') {
	static $chached_list;
	$str = '';

	if (isset($chached_list)) {
		foreach ($chached_list as &$option) {
			$selected = ($option == $selected_dir) ? ' selected="selected"' : '';
			$str .= '<option value="' . $option . '"' . $selected . '>' . htmlspecialchars($option) . '</option>';
		} // end foreach
		return $str;
	} // end if

	$handle = opendir($template_dir . '/');
	while (false != ($dir = readdir($handle))) {
		if (substr_count($dir, '.') > 0) {
			continue;
		} //end if

		$chached_list[] = $dir;
		$selected = ($dir == $selected_dir) ? ' selected="selected"' : '';
		$str .= '<option value="' . $dir . '"' . $selected . '>' . htmlspecialchars($dir) . '</option>';
	} // end while
	closedir($handle);
	return $str;
} // end function

function altRowColor() {
	static $i = 0;
	return ($i++ % 2) ? 'dark' : 'light';
} // end function

function stripSpecialChar($string = '') {
	if ($string == 'News Networld') {
		return 'nn';
	} elseif ($string == 'Verlagsgruppe News') {
		return '';
	} // end if

	$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$replace_char = '#';
	$temp = strtr($string, $charset, str_repeat($replace_char, strlen($charset)));
 	$temp2 = strtr($string, $temp, str_repeat(' ', strlen($temp)));
	return str_replace (' ', '', strtolower($temp2));
} // end function

?>