function disableButton(submit_button, label) {
	submit_button.disabled = true;
	submit_button.value = label;
	submit_button.form.submit();
	return true;
} // end function

function toggleHelp() {
	var button 		= document.getElementById("togglehelp");
	var divs 		= document.getElementsByTagName("div");
	var divlength 	= divs.length;

	for (var i = 0; i < divlength; i++) {
		if (divs[i].className != "help") {
			continue;
		} // end if

		if (divs[i].style.display != "block") {
			divs[i].style.display = "block";
			button.innerHTML = "Hilfe <b>aus</b>schalten";
		} else {
			divs[i].style.display = "none";
			button.innerHTML = "Hilfe <b>ein</b>schalten";
		} // end if
	} // end for
	return true;
} // end function