function disableButton(submit_button, label) {
	submit_button.disabled = true;
	submit_button.value = label;
	submit_button.form.submit();
	return true;
}