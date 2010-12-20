{literal}
	var tree;
	var nodes = new Array();
	var nodeIndex = 0;

	var menuElements = new Array();

{/literal}
	{$menuElements}
{literal}

	function menuInit() {
		tree = new YAHOO.widget.TreeView("treeDiv1");
		tree.onExpand = function(node) {
			// alert(node.index + " was expanded");
		}

		tree.onCollapse = function(node) {
			// alert(node.index + " was collapsed");
		}

                nodes[1] = new YAHOO.widget.MenuNode({label: "{/literal}{$home}{literal}", icon: "{/literal}{$site_root}{$template_root}{literal}/images/menu/icons/home.gif", href: "{/literal}{$site_root}{literal}/admin/admin_homepage.php"}, tree.getRoot(), false);

		for (var i = 0; i < menuElements.length; i++) {
			var thisId = nodeIndex++;
			var thisLabel = menuElements[i][0];
			var thisIcon = "";
			// nodes[thisId] = new YAHOO.widget.TextNode({ label: thisLabel }, tree.getRoot(), false);
			// nodes[thisId] = new YAHOO.widget.MenuNode({label:thisLabel, href:"http://asdf"}, tree.getRoot(), false);  icon: }

			nodes[thisId] = new YAHOO.widget.HTMLNode('<div class="toplevel">' + thisLabel + '</div>', tree.getRoot(), false);

			for (var j = 0; j < menuElements[i][2].length; j++) {
				thisId = nodeIndex++;
				thisLabel = menuElements[i][2][j][0][0];
				var thisIcon = "";
				var thisHref = "";
				var thisCookieID = "";

				if (menuElements[i][2][j][0][1]) {
					thisIcon = menuElements[i][2][j][0][1];
				}

				if (menuElements[i][2][j][0][2]) {
					thisHref = menuElements[i][2][j][0][2];
				}

				if (menuElements[i][2][j][0][3]) {
					thisCookieID = menuElements[i][2][j][0][3];
				}

				var thisShowIdentifier = getCookie('show' + thisCookieID);
				var thisShow;

				if (thisShowIdentifier == "Y") {
					thisShow = true;
				} else {
					thisShow = false;
				}

				// nodes[thisId] = new YAHOO.widget.TextNode({ label: thisLabel }, p1, true);
				//nodes[thisId] = new YAHOO.widget.MenuNode({label: thisLabel, icon: thisIcon, href: thisHref, cookieid: thisCookieID}, tree.getRoot(), thisShow);
				if(menuElements[i][2][j][1].length != 0){
					nodes[thisId] = new YAHOO.widget.MenuNode({label: thisLabel, icon: thisIcon, href: '', cookieid: thisCookieID}, tree.getRoot(), thisShow);
				} else {
					nodes[thisId] = new YAHOO.widget.MenuNode({label: thisLabel, icon: thisIcon, href: thisHref, cookieid: thisCookieID}, tree.getRoot(), thisShow);
				}

				var p2 = nodes[thisId];

				for (var k =0; k < menuElements[i][2][j][1].length; k++) {
					thisId = nodeIndex++;
					thisLabel = menuElements[i][2][j][1][k][0];
					var thisIcon = "";
					var thisHref = "";
					var thisOnclick = "";

					if (menuElements[i][2][j][1][k][1]) {
						thisIcon = menuElements[i][2][j][1][k][1];
					}
					if (menuElements[i][2][j][1][k][2]) {
						thisHref = menuElements[i][2][j][1][k][2];
					}
					if (menuElements[i][2][j][1][k][3]) {
						thisOnclick = menuElements[i][2][j][1][k][3];
					}

					var data = {
							id: thisId,
							label: thisLabel,
							icon: thisIcon,
							href: thisHref,
							onclick: thisOnclick}
					nodes[thisId] = new YAHOO.widget.TextNode(data, p2, false);
					// nodes[thisId] = new YAHOO.widget.MenuNode(thisLabel, p2, false);
				}

			}

		}

		// nodes[0] = new YAHOO.widget.TextNode(tree.getRoot(), false, "label-0");
		tree.draw();
	}

	var selectedId = null;

	function getCookie(name) {
	    var dc = document.cookie;
	    var prefix = name + "=";
	    var begin = dc.indexOf("; " + prefix);
	    if (begin == -1) {
	        begin = dc.indexOf(prefix);
	        if (begin != 0) return null;
	    } else {
	        begin += 2;
	    }
	    var end = document.cookie.indexOf(";", begin);
	    if (end == -1) {
	        end = dc.length;
	    }
	    return unescape(dc.substring(begin + prefix.length, end));
	}

	function onLabelClick(id) {

		var node = tree.getNodeByProperty("id", id);
		// alert(node.label);

		var el = node.getLabelEl()

	        el.style.backgroundColor = "#c5dbfc";


		if (selectedId != null) {
			node = tree.getNodeByProperty("id", selectedId);
			node.getLabelEl().style.backgroundColor = "white";
		}

		selectedId = id;
	}
{/literal}
