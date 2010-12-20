<?php
/**
* Menu
*
* @package RealEstate
* @subpackage Admin mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.5 $ $Date: 2009/01/16 15:17:15 $
**/

include "../include/config.php";
include_once "../common.php";
include "../include/functions_admin.php";
include "../include/functions_auth.php";
include_once "../include/functions_xml.php";

$lang["admin_menu"] = GetLangContent("admin/admin_menu");

$user = auth_user();
			
$strSQL = "SELECT COUNT(*) FROM ".MAILBOX_MESSAGES_TABLE." WHERE to_user_id = '".$user[0]."' AND seen='0' ";
$rs = $dbconn->Execute($strSQL);
$count = intval($rs->fields[0]);
$config["inbox_new"] = $count;
	
MenuAdds();
GetHome();
function AdminXMLMenu( $root ){
	global $config, $item_uid, $lang, $dbconn;
	
	$strSQL = "SELECT user_type FROM ".USERS_TABLE." WHERE id = '1'";
	$rs = $dbconn->Execute($strSQL);
	$agents_menu = $rs->fields[0];
	$menu = array();
	$i = 0;
	foreach ( $root->children as $cnt => $node ) {
		$item_uid ++;
		switch($node->tag){
			case "section":
				$menu[$i] = array();
				$menu[$i][0] = $lang["admin_menu"][$node->attrs["name"]];
				$menu[$i][1] = '';
				$menu[$i][2] = array();
				if ($node->childrenCount > 0) {
					$menu[$i][2] = AdminXMLMenu( $node );
				}
				break;
			case "item":
				$menu[$i] = array();
				$menu[$i][0] = array();
				$menu[$i][0][0] = $lang["admin_menu"][$node->attrs["name"]];
				$menu[$i][0][1] = $config["site_root"].$config["admin_theme_path"].$node->attrs["img"];
				$menu[$i][0][2] = $config["site_root"].$node->attrs["href"];
				$menu[$i][0][3] = "item".$item_uid;
				$menu[$i][1] = array();
				if ($node->childrenCount > 0) {
					$menu[$i][1] = AdminXMLMenu( $node );
				}
				break;
			case "subitem":
				
				$menu[$i] = array();	
							
				if ($node->attrs["name"] == 'agents'){
					switch ($agents_menu){
						case 1:
							$menu_str = 'none';
						break;
						case 2:
							$menu_str = 'my_agents';
						break;
						case 3:
							$menu_str = 'my_realtor';
						break;
					}
					if (!GetSiteSettings('use_agent_user_type')){
						$menu_str = 'none';
						
					}
					$menu[$i][0] = $lang["admin_menu"][$menu_str];				
				}else{					
					$menu[$i][0] = $lang["admin_menu"][$node->attrs["name"]];				
				}
				$menu[$i][1] = $config["site_root"].$config["admin_theme_path"].$node->attrs["img"];
				$menu[$i][2] = $config["site_root"].$node->attrs["href"];
				if ( isset($node->attrs["onclick"]) ){
					$node->attrs["onclick"] = str_replace("[href]",$menu[$i][2],$node->attrs["onclick"]);
					$menu[$i][3] = $node->attrs["onclick"];
				}
				if (!empty($node->attrs["new"]) && isset($config["inbox_new"]) && $config["inbox_new"] == 1) {										$menu[$i][1] = $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/mail_alert.gif";
				}
				$unset_elem = 0;
				if (isset($node->attrs["id"]) && !empty($node->attrs["id"]) && in_array($node->attrs["id"], $config["mode_hide_ids"])) { 				
					$unset_elem = 1;
				}
				
				if (isset($menu_str) && $menu_str == 'none') {
					$unset_elem = 1;
				}
				if ($unset_elem == 1) {	
					unset($menu[$i]);
					$i--;
				}
								
				break;
		}
		$i++;
	}
	return $menu;
}

function AdminJSMenu( $menu_arr, $keys = "" ){
	$menu_str = "";
	foreach ( $menu_arr as $key => $value ) {
		if (is_array($menu_arr[$key])) {
			$menu_str .= "menuElements".$keys."[".$key."] = new Array();\n";
			$menu_str .= AdminJSMenu( $menu_arr[$key], $keys."[".$key."]" );
		} else {
			$menu_str .= "menuElements".$keys."[".$key."] = \"".$value."\";\n";
		}
	}
	return $menu_str;
}

function GetHome(){
	global $config, $smarty;
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."admin/admin_homepage.xml" );
	$xml_root = $xml_parser->getRoot();
	foreach ( $xml_root->children as $cnt => $node ) {
		$lang_content[$node->attrs["name"]] = $node->value;
	}
	$smarty->assign("home", $lang_content["home"]);
	return;
}

if (isset($_GET["js"])) {
	$xml_parser = new SimpleXmlParser( $config["site_path"]."/include/admin_menu.xml" );

	$xml_root = $xml_parser->getRoot();
	$menu_arr = array();
	$menu_arr = AdminXMLMenu( $xml_root );
	
	$menu_str = AdminJSMenu( $menu_arr );

	unset( $xml_parser, $xml_root );
	$smarty->assign("menuElements", $menu_str);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/js/menu.js");
}

if (isset($_GET["css"])) {
	$smarty->display(TrimSlash($config["admin_theme_path"]).$config["index_theme_css_path"]."/menu.css");
}

?>
