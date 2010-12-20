<?php
/**
* File generate an xml structure of site map in Google Sitemap format
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:23 $
**/

$include = 1;
include("map.php");
ini_set("display_errors", "0");

$to_google_map = array();
GetMapStructure($map, $to_google_map);

$output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
$output .= "\t<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\"\r\n
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\r\n
	xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.84\r\n
	http://www.google.com/schemas/sitemap/0.84/sitemap.xsd\">\r\n";

foreach ($to_google_map["link"] as $key=>$val) {
	$output .= "\t\t<url>\r\n";
	$output .= "\t\t\t<loc>".$val."</loc>\r\n";
	if (isset($to_google_map["priority"][$key])) {
		$output .= "\t\t\t<priority>".$to_google_map["priority"][$key]."</priority>\r\n";
	}
	$output .= "\t\t</url>\r\n";
}
$output .= "\t</urlset>";

header ("Content-Type: text/xml; charset=utf-8");
echo $output;

/**
 * Get map structure for Google Sitemap to
 *
 * @param array $map
 * @param array $google_map
 */
function GetMapStructure($map, &$google_map) {
	global $config, $dbconn;

	foreach ($map as $val_arr) {	
		if ($val_arr["link"] != "") {		
			if (substr($val_arr["link"], 0, 1) == ".") {
				$val_arr["link"] = substr($val_arr["link"], 1);
			}
			if (substr($val_arr["link"], 0, 1) != "/") {
				$val_arr["link"] = "/".$val_arr["link"];
			}
			
			$module_name = GetRightModulePath($config["site_path"].$val_arr["link"]);
			$sym_pos = strpos($module_name, "?");
			if ($sym_pos !== false) {
				$module_name = substr($module_name, 0, $sym_pos);
			}
			$sym_pos = strpos($module_name, "/");
			if ($sym_pos !== false) {
				$module_name = "/".substr($module_name, 0, $sym_pos);
			}
			
			/**
			 * Check if guest users have access to the file - not works for all links, 
			 * because there are some redirects from files for guest users (so, in fact 
			 * guest user could have access to the file, but he is redirected from it)
			 */
			$show_link = true;
			$strSQL = "SELECT id FROM ".MODULE_FILE_TABLE." WHERE file='$module_name'";
			$rs = $dbconn->Execute($strSQL);
			
			if ($rs->RowCount() > 0) {
				$id_module = $rs->fields[0];
							
				$strSQL = "SELECT gm.id FROM ".GROUP_MODULE_TABLE." AS gm ".
						  "LEFT JOIN ".GROUPS_TABLE." g ON gm.id_group=g.id ".
						  "WHERE g.type='g' AND gm.id_module='$id_module'";
				$rs = $dbconn->Execute($strSQL);
				
				if ($rs->RowCount() == 0) {
					$show_link = false;
				}
			}
			
			if ($show_link) {
				if ($val_arr["link"] == "/index.php") {
					$cnt = (isset($google_map["link"]) && is_array($google_map["link"])) ? count($google_map["link"]) : 0;
					$google_map["priority"][$cnt] = 1;						
				}
				$google_map["link"][] = htmlspecialchars($config["server"].$config["site_root"].$val_arr["link"]);
			}
		}	
		if (isset($val_arr["subsection"]) && is_array($val_arr["subsection"])) {
			GetMapStructure($val_arr["subsection"], $google_map);
		}							
	}
}

?>