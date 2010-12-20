function ShowHideDiv(elem_id) {
	var elem = document.getElementById(elem_id);
	if (elem.style.display == 'block') {
		elem.style.display = 'none';
	} else {
		elem.style.display = 'block';
	}
}