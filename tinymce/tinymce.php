<?php
$tinymce = "
		<script language=\"javascript\" type=\"text/javascript\" src=\"".$config["server"].$config["site_root"]."/tinymce/jscripts/tiny_mce/tiny_mce_gzip.php\"></script>
		<script language=\"javascript\" type=\"text/javascript\">
			tinyMCE.init({
				mode : \"textareas\",
				theme : \"advanced\",
				theme_advanced_toolbar_location : \"top\",
				theme_advanced_toolbar_align : \"left\",
				theme_advanced_path_location : \"bottom\",
				plugins : \"table\",
				theme_advanced_buttons1 : \"bold, italic, strikethrough, separator, bullist, numlist, outdent, indent, separator, justifyleft, justifycenter, justifyright ,separator, formatselect, fontselect, fontsizeselect\",
				theme_advanced_buttons2 : \"tablecontrols, separator, link, unlink, image, separator, emotions, forecolor, backcolor, separator, undo, redo, code, wphelp\",
				theme_advanced_buttons3 : \"\",
				convert_urls : false,
				relative_urls : false,
				remove_script_host : false,
				cleanup : false,
    			cleanup_on_startup : true,
				apply_source_formatting : true,
				fix_list_elements : true,
				fix_table_elements : true,
				extended_valid_elements: \"object[classid|codebase|width|height],param[name|value],embed[quality|type|pluginspage|width|height|src]\"
			});
		</script>";
?>