var req = null;

function InitXMLHttpRequest() {
	// Make a new XMLHttp object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
}


function SelectCountry(section, destination) {
	InitXMLHttpRequest();
	// Load the result from the response page
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				destination.innerHTML = req.responseText;
			} else {
				destination.innerHTML = "Loading data...";
			}
		}
		req.open("GET", "admin_location.php?sec=" + section + "&sel=country", true);
		req.send(null);
	} else {
		destination.innerHTML = 'Browser unable to create XMLHttp Object';
	}
}

function SelectRegion(section, id_country, destination, destination2, text, text2) {
	InitXMLHttpRequest();
	if (id_country != '') {
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else {
					destination.innerHTML = "<select style=\"width:150px;\"><option>"+text+"</option></select>";
				}
			}
			req.open("GET", "admin_location.php?sec=" + section + "&sel=region&id_country=" + id_country, true);
			req.send(null);
		} else {
			destination.innerHTML = 'Browser unable to create XMLHttp Object';
		}
	} else {
		req.open('GET', 'admin_location.php?sec=' + section + '&sel=region', false);
	    req.send(null);
    	destination.innerHTML = req.responseText;
	}
    destination2.innerHTML = "<select style=\"width:150px;\"><option>"+text2+"</option></select>";
}

function SelectRegion2(section, id_country, destination, destination2, text, text2, destination3) {
	InitXMLHttpRequest();
	if (id_country != '') {
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else {
					destination.innerHTML = "<select style=\"width:150px;\"><option>"+text+"</option></select>";
				}
			}
			req.open("GET", "admin_location.php?sec=" + section + "&sel=region2&id_country=" + id_country, true);
			req.send(null);
		} else {
			destination.innerHTML = 'Browser unable to create XMLHttp Object';
		}
	} else {
		req.open('GET', 'admin_location.php?sec=' + section + '&sel=region', false);
	    req.send(null);
    	destination.innerHTML = req.responseText;
	}
    destination2.innerHTML = "<select style=\"width:150px;\"><option>"+text2+"</option></select>";
    if ((section == 'rnte') || (section == 'rmte')){
    	destination3.innerHTML = "";
    }
}

function SelectRegion3(section, id_country, destination, destination2, text, text2) {
	InitXMLHttpRequest();
	if (id_country != '') {
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else {
					destination.innerHTML = "<option>"+text+"</option>";
				}
			}
			req.open("GET", "admin_location.php?sec=" + section + "&sel=region&no_sel=1&id_country=" + id_country, true);
			req.send(null);
		} else {
			destination.innerHTML = 'Browser unable to create XMLHttp Object';
		}
	} else {
		req.open('GET', 'admin_location.php?sec=' + section + '&sel=region&no_sel=1', false);
	    req.send(null);
    	destination.innerHTML = req.responseText;
	}
    destination2.innerHTML = "<option>"+text2+"</option>";
}

function SelectCity(section, id_region, destination, text){
	InitXMLHttpRequest();
    if (id_region != '') {
		// Load the result from the response page
	    if (req){
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else {
					destination.innerHTML = "<select style=\"width:150px;\"><option>"+text+"</option></select>";
				}
			}
	       req.open('GET', 'admin_location.php?sec=' + section + '&sel=city&id_region=' + id_region, true);
	       req.send(null);
	    }
	    else{
	       destination.innerHTML = 'Browser unable to create XMLHttp Object';
	    }
    }
    else {
    	req.open('GET', 'admin_location.php?sec=' + section + '&sel=city', false);
		req.send(null);
    	destination.innerHTML = req.responseText;
    }
}

function SelectCity2(section, id_region, destination, text, destination2){
	InitXMLHttpRequest();
    if (id_region != '') {
		// Load the result from the response page
	    if (req){
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else {
					destination.innerHTML = "<select style=\"width:150px;\"><option>"+text+"</option></select>";
				}
			}
	       req.open('GET', 'admin_location.php?sec=' + section + '&sel=city2&id_region=' + id_region, true);
	       req.send(null);
	    }
	    else{
	       destination.innerHTML = 'Browser unable to create XMLHttp Object';
	    }
    }
    else {
    	req.open('GET', 'admin_location.php?sec=' + section + '&sel=city2', false);
		req.send(null);
    	destination.innerHTML = req.responseText;
    }
    if ((section == 'rnte') || (section == 'rmte')){
    	destination2.innerHTML = "";
    }
}

function SelectCity3(section, id_region, destination, text){
	InitXMLHttpRequest();
    if (id_region != '') {
		// Load the result from the response page
	    if (req){
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					destination.innerHTML = req.responseText;
				} else {
					destination.innerHTML = "<option>"+text+"</option>";
				}
			}
	       req.open('GET', 'admin_location.php?sec=' + section + '&sel=city&no_sel=1&id_region=' + id_region, true);
	       req.send(null);
	    }
	    else{
	       destination.innerHTML = 'Browser unable to create XMLHttp Object';
	    }
    }
    else {
    	req.open('GET', 'admin_location.php?sec=' + section + '&sel=city&no_sel=1', false);
		req.send(null);
    	destination.innerHTML = req.responseText;
    }
}

function ShowSubways(section, city_id, destination, text, text2){
	if ((section == 'rnte') || (section == 'rmte')){
		if ((city_id=='3159_4312_4400') || (city_id=='3159_4925_4962')){
			InitXMLHttpRequest();
			if (req){
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						destination.innerHTML = req.responseText;
					} else {
						destination.innerHTML = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" height=\"25\"><tr><td height=\"27\" width=\"100\">" + text2 + ":&nbsp;</td><td><select style=\"width:150px;\"><option>" + text +"</option></select></td></tr></table>";
					}
				}
		       req.open('GET', 'admin_location.php?sec=' + section + '&sel=subway&id_city=' + city_id, true);
		       req.send(null);
		    }
		    else{
		       destination.innerHTML = 'Browser unable to create XMLHttp Object';
		    }
		} else {
			destination.innerHTML = '';
		}
	} else {
		destination.innerHTML = '';
	}
}