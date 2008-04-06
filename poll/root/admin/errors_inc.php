<?php
	if (count($errors) > 0) {
		echo '<ul class="errors"><li>' , implode('</li><li>', $errors) , '</li></ul><hr />';
	} // end if
	if (count($notices) > 0) {
		echo '<ul class="notices"><li>' , implode('</li><li>', $notices) , '</li></ul><hr />';
	} // end if
?>