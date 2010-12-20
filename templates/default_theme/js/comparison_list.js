var req = null;

function InitXMLHttpRequest() {
	// Make a new XMLHttp object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
}


function AddToComparisonList(id_ad, destination_id) {
	InitXMLHttpRequest();

	var destination = document.getElementById(destination_id);
	// Load the result from the response page
	if (req) {					
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				destination.innerHTML = req.responseText;

				//document.getElementById("comparison_menu").style.visibility = "visible";
				document.getElementById("comparison_str").style.display = "inline";
				RefreshComparisonStr("comparison_str");
				//RefreshComparisonList("comparison_list");
			} else {
				destination.innerHTML = "Loading data...";
			}
		}
		req.open("GET", "comparison_list.php?id_ad=" + id_ad);
		req.send(null);	
	} else {
		destination.innerHTML = 'Browser unable to create XMLHttp Object';
	}
}

function RefreshComparisonList(destination_id) {
	InitXMLHttpRequest();

	var destination = document.getElementById(destination_id);
	// Load the result from the response page
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseText == "empty") {
					//document.getElementById("comparison_menu").style.visibility = "hidden";
					document.getElementById("comparison_str").style.display = "none";
					destination.innerHTML = "";
				} else {
					destination.innerHTML = req.responseText;
				}
			} else {
				destination.innerHTML = "Loading data...";
			}
		}
		req.open("GET", "comparison_list.php?action=get_list");
		req.send(null);				
	} else {
		destination.innerHTML = 'Browser unable to create XMLHttp Object';
	}
}

function RefreshComparisonStr(destination_id) {
	InitXMLHttpRequest();

	var destination = document.getElementById(destination_id);
	// Load the result from the response page
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseText == "empty") {
					//document.getElementById("comparison_menu").style.visibility = "hidden";
					destination.style.display = "none";
					destination.innerHTML = "";
				} else {
					destination.style.display = "inline";
					destination.innerHTML = req.responseText;					
				}
			} else {
				destination.innerHTML = "Loading data...";
			}
		}				
		req.open("GET", "comparison_list.php?action=get_str");
		req.send(null);		
	} else {
		destination.innerHTML = 'Browser unable to create XMLHttp Object';
	}
}

function ClearComparisonList(destination_id) {
	InitXMLHttpRequest();

	var destination = document.getElementById(destination_id);
	// Load the result from the response page
	if (req) {		
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				eval(req.responseText);
				destination.innerHTML = "";
				//document.getElementById("comparison_menu").style.visibility = "hidden";
				document.getElementById("comparison_str").style.display = "none";
			} else {
				destination.innerHTML = "Loading data...";
			}
		}		
		req.open("GET", "comparison_list.php?action=clear_list");
		req.send(null);
	} else {
		destination.innerHTML = 'Browser unable to create XMLHttp Object';
	}
}

function DeleteFromComparisonList(destination_id, id_ad) {
	InitXMLHttpRequest();

	var destination = document.getElementById(destination_id);
	// Load the result from the response page
	if (req) {		
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				eval(req.responseText);
				destination.innerHTML = "";
				RefreshComparisonList("comparison_list");				
			} else {
				destination.innerHTML = "Loading data...";
			}
		}		
		req.open("GET", "comparison_list.php?action=delete_ad&id_ad=" + id_ad);
		req.send(null);
	} else {
		destination.innerHTML = 'Browser unable to create XMLHttp Object';
	}
}