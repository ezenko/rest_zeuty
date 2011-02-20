<?php
/**
* Functions collections for working with xml data
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: dpavlov $
* @version $Revision: 1.7 $ $Date: 2008/11/20 12:06:47 $
**/

include "class.xmlparser.php";

function CreateMenu( $menu_name ) {
	global $user, $config, $smarty, $dbconn;

	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"].$config["menu_path"].$menu_name.".xml" );
	$xml_root = $xml_parser->getRoot();

	$menu_arr = array();
	$menu_arr = XMLMenu( $xml_root );
	unset( $xml_parser, $xml_root );

	if ($menu_name == 'lang_menu' || $menu_name == 'admin_lang_menu') {

		$visible_lang_cnt = 0;
		foreach ($menu_arr as $key=>$lang_arr) {
			$strSQL = " SELECT visible FROM ".LANGUAGE_TABLE." WHERE id='".$lang_arr['id_lang']."' ";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]=='1') {
				$menu_arr[$key]['vis'] = 1;
				$visible_lang_cnt++;
			} else {
				$menu_arr[$key]['vis'] = 0;
			}
		}

		if ($menu_name == 'lang_menu') {
			$menu_arr = ($visible_lang_cnt > 1) ? $menu_arr : array();
		}

		$smarty->assign($menu_name."_visible_cnt", $visible_lang_cnt);
	}
	
	if ($menu_name == 'rental_menu' || $menu_name == 'account_menu') {
		$strSQL = "SELECT COUNT(id_agent) FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$user[0]' AND inviter = 'agent' AND approve = '0' GROUP BY id_company;";					
		$rs = $dbconn->Execute($strSQL);		
		$smarty->assign("new_agents", $rs->fields[0]);
		
		$strSQL = "SELECT COUNT(id_company) FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]' AND inviter = 'company' AND approve = '0' GROUP BY id_agent;";					
		$rs = $dbconn->Execute($strSQL);		
		$smarty->assign("new_company", $rs->fields[0]);
		
		$strSQL = "SELECT user_type FROM ".USERS_TABLE." WHERE id = '$user[0]';";			
		$rs = $dbconn->Execute($strSQL);
				
		$smarty->assign("user_type", $rs->fields[0]);
	}

	$info_menu = array("bottom_menu", "index_top_menu", "homepage_top_menu", "index_page_menu");
	if (in_array($menu_name, $info_menu)) {
		include_once "class.info_manager.php";
		$menu_position = substr($menu_name, 0, strpos($menu_name, "_menu"));
		if (strstr($menu_position, "_top") || strstr($menu_position, "_page")) {
			$menu_position = "top";
		}
		$info_manager = new InfoManager();
		$sections = $info_manager->GetSectionsList($config["default_lang"], $menu_position, true);

		foreach ($sections as $sect) {
			$menu_item["name"] = "info_".$sect["id"];
			$menu_item["value"] = $sect["caption"];
			$menu_item["href"] = $config["server"].$config["site_root"]."/info.php?id=".$sect["id"];
			$menu_arr[] = $menu_item;
		}
	}
	$smarty->assign($menu_name, $menu_arr);
	return $menu_arr;
}

function XMLMenu( $root ) {
	global $config;

	//GetHideModeIds declared in common.xml
	$mode_hide_common_ids = GetHideModeIds($config["site_mode"]);

	$menu = array();
	$cnt = 0;
    if(count($root->children)) {
    	foreach ( $root->children as $node ) {
    		switch($node->tag) {
    			case "item":
    				$menu[$cnt] = array();
    				$menu[$cnt]["name"] = $node->attrs["name"];
    				$menu[$cnt]["value"] = $node->value;
    				if (!empty($node->attrs["href"])) {
    					$menu[$cnt]["href"] = $config["site_root"].$node->attrs["href"];
    				}
    				if (!empty($node->attrs["onclick"])) {
    					$menu[$cnt]["onclick"] = str_replace("[href]",$menu[$cnt]["href"],$node->attrs["onclick"]);
    					//$menu[$cnt]["href"] = "#";
    				}
    				if (!empty($node->attrs["id_lang"])) {
    					$menu[$cnt]["id_lang"] = $node->attrs["id_lang"];
    				}
    				if (!empty($node->attrs["all"]) && isset($config["inbox_all"])) {
    					$menu[$cnt]["all"] = str_replace("[all]",$config["inbox_all"], $node->attrs["all"]);
    				}
    				if (!empty($node->attrs["new"]) && isset($config["inbox_new"])) {
    					$menu[$cnt]["new"] = str_replace("[new]",$config["inbox_new"], $node->attrs["new"]);
    				}
    				if (!empty($node->attrs["id"])) {
    					$menu[$cnt]["id"] = $node->attrs["id"];
    				}
    
    				if (is_array($mode_hide_common_ids) && isset($menu[$cnt]["id"]) && in_array($menu[$cnt]["id"], $mode_hide_common_ids)) {
    					//if this item of menu is hided - depend on site mode
    					unset($menu[$cnt]);
    				} else {
    					$cnt++;
    				}
    				break;
    		}
    	}
    }
	return $menu;
}

function GetErrors($err_name) {
	global $config, $smarty;
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."errors.xml" );
	$xml_root = $xml_parser->getRoot();
	$error = "";
	foreach ( $xml_root->children as $cnt => $node ) {
		if ($node->attrs["name"] == $err_name) {
			$error = $node->value;
			break;
		}
	}
	$smarty->assign("error", $error);
	return $error;
}

function GetErrorsWithLink($err_name) {
	global $config, $smarty;	
	$error = "";
	switch ($err_name) {
		case "auth_access":
			$error = GetErrors("auth_access_1")."<a href=\"#\" onclick=\"parent.location.href='./contact.php';parent.GB_hide();\">".GetErrors("auth_access_2")."</a>".GetErrors("auth_access_3");
			$smarty->assign("error", $error);
			break;
	}
}

function GetLangContent($content_name, $lang_path = "") {
	global $config;
	$lang_path = ($lang_path) ? $lang_path : $config["lang_path"];
	$lang_content = array();
	if (file_exists($config["site_path"].$lang_path.$content_name.".xml")) {		
		$xml_parser = new SimpleXmlParser( $config["site_path"].$lang_path.$content_name.".xml" );
		$xml_root = $xml_parser->getRoot();
		foreach ( $xml_root->children as $cnt => $node ) {
			$lang_content[$node->attrs["name"]] = OneTwoQuoteToCode($node->value);
		}
	}
	return $lang_content;
}
function GetMonth() {
	global $config;
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."month.xml" );
	$xml_root = $xml_parser->getRoot();
	foreach ( $xml_root->children as $cnt => $node ) {
		$month_name[$node->attrs["name"]] = $node->value;
	}
	return $month_name;
}

function GetDayOfWeek() {
	global $config;
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."day_of_week.xml" );
	$xml_root = $xml_parser->getRoot();
	foreach ( $xml_root->children as $cnt => $node ) {
		$day_of_week[$node->attrs["name"]] = $node->value;
	}
	return $day_of_week;
}


function GetTypesName() {
	global $config;
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."users_types.xml" );
	$xml_root = $xml_parser->getRoot();
	foreach ( $xml_root->children as $cnt => $node ) {
		$users_types[$node->attrs["name"]] = $node->value;
	}
	return $users_types;
}

function GetAlertsName() {
	global $config;
	$alerts = array();
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."alerts.xml" );
	$xml_root = $xml_parser->getRoot();
	foreach ( $xml_root->children as $cnt => $node ) {
		if (!empty($node->attrs["id"])) {
			$alerts[$node->attrs["id"]]["name"] = $node->value;
			$alerts[$node->attrs["id"]]["id"] = $node->attrs["id"];
		}
	}
	return $alerts;
}

function GetMailContent($mail_template_name, $lang_path = "") {
	global $config;

	$lang_path = ($lang_path) ? $lang_path : $config["lang_path"];
	$mail_content = array();
	if (file_exists($config["site_path"].$lang_path.$mail_template_name.".xml")) {
		$xml_parser = new SimpleXmlParser( $config["site_path"].$lang_path.$mail_template_name.".xml" );
		$xml_root = $xml_parser->getRoot();
		foreach ( $xml_root->children as $cnt => $node ) {
			$mail_content[$node->attrs["name"]] = $node->value;
		}

	}
	return $mail_content;
}

/**
 * Get xml language strings from $mail_content, replace values from $translit array
 * with values from $mail_content_default, and merge content of this to arrays
 *
 * @param string $mail_content
 * @param string $language
 * @param string $mail_content_default
 * @return array
 */
function GetMailContentReplace($mail_content, $language_id = 0, $mail_content_default = "mail_content_default_select") {

	if ($language_id) {
		$lang_path = LangPathById($language_id);
	}

	$lang_content = GetMailContent($mail_content, $lang_path);
	$default_content = GetMailContent($mail_content_default, $lang_path);

	$translit['[site_name]'] = $default_content["site_name"];

	foreach ($lang_content as $line_name=>$line) {
		$lang_content[$line_name] = strtr($line, $translit);
	}

	return array_merge($lang_content, $default_content);
}

function GetWeek() {
	global $config;
	$xml_parser = new SimpleXmlParser( $config["site_path"].$config["lang_path"]."week.xml" );
	$xml_root = $xml_parser->getRoot();
	$i = 0;
	foreach ( $xml_root->children as $cnt => $node ) {
		$week_name[$i]['id'] = $node->attrs["name"];
		$week_name[$i]['name'] = $node->value;
		$i++;
	}
	return $week_name;
}

/**
 * Get metatags.xml file content, or another file ($content_name) with the same structure
 *
 * @param string $content_name - xml file name without extension
 * @param string $lang_path - language subfolder
 * @return array
 */
function GetMetatagsContent($content_name, $lang_path = "") {
	global $config;

	$lang_path = ($lang_path) ? $lang_path : $config["lang_path"];
	$lang_content = array();
	if (file_exists($config["site_path"].$lang_path.$content_name.".xml")) {
		$xml_parser = new SimpleXmlParser( $config["site_path"].$lang_path.$content_name.".xml" );
		$xml_root = $xml_parser->getRoot();

		foreach ( $xml_root->children as $cnt => $node ) {
			foreach ( $node->children as $child_cnt => $child ) {
				$lang_content[$node->attrs["name"]]["metatags"][$child->tag] = $child->value;
			}
		}
	}
	return $lang_content;
}

/**
 * Get language path by language id
 *
 * @param integer $language_id
 * @return string
 */
function LangPathById($language_id) {
	global $dbconn;

	$lang_path = "";
	$strSQL = "SELECT lang_path FROM ".LANGUAGE_TABLE." WHERE id='$language_id'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount() > 0) {
		$lang_path = $rs->fields[0];
	}
	return $lang_path;
}

/**
 * Get list of pages with its description for user and metatags
 *
 * @param string $content_name - xml file name without extension
 * @param integer $language_id
 * @return array
 */
function GetMetatagsList($content_name, $language_id = 0) {
	global $config, $dbconn;

	if ($language_id) {
		$lang_path = LangPathById($language_id);
	}

	$modules = GetLangContent("modules", $lang_path);
	$files = GetMetatagsContent($content_name, $lang_path);

	foreach ( $files as $fname => $file ) {
		$files[$fname]["file"] = $modules[$fname];
		$files[$fname]["file_descr"] = $modules["descr_".$fname];
	}

	return $files;
}

/**
 * Get Lang content for module.xml structure file
 *
 * @param string $content_name - xml file name without extension
 * @param integer $language_id
 * @return array
 */
function GetModuleLangContent($content_name, $lang_path = "") {
	global $config;
	$lang_path = ($lang_path) ? $lang_path : $config["lang_path"];
	$lang_content = array();
	if (file_exists($config["site_path"].$lang_path.$content_name.".xml")) {		
		$xml_parser = new SimpleXmlParser( $config["site_path"].$lang_path.$content_name.".xml" );
		$xml_root = $xml_parser->getRoot();
		foreach ( $xml_root->children as $cnt => $node ) {
			
			$lang_content[$node->attrs["name"]]["value"] = $node->value;			
			if (isset($node->attrs["file"]) && !empty($node->attrs["file"])) {
				$lang_content[$node->attrs["name"]]["file"] = $node->attrs["file"];	
			}			
		}
	}
	return $lang_content;
}

/**
 * Get map.xml file content
 * @param array $hide_mode_ids
 * @return array
 */
function GetMapContent($hide_mode_ids) {
	global $config;

	$lang = GetLangContent("map");

	$map = array();
	if (file_exists($config["site_path"]."/include/map.xml")) {
		$xml_parser = new SimpleXmlParser( $config["site_path"]."/include/map.xml" );
		$xml_root = $xml_parser->getRoot();

		foreach ( $xml_root->children as $cnt => $xml_section ) {
			if (!isset($xml_section->attrs["id"]) || (isset($xml_section->attrs["id"]) && !in_array($xml_section->attrs["id"], $hide_mode_ids))) {
				$section = array();
				$section = array("name" => (isset($lang[$xml_section->attrs["name"]]) ? $lang[$xml_section->attrs["name"]] : $xml_section->attrs["name"]), "link" => $xml_section->attrs["link"]);
				if ($xml_section->children) {
					foreach ( $xml_section->children as $xml_subsection_cnt => $xml_subsection ) {
						if (!isset($xml_subsection->attrs["id"]) || (isset($xml_subsection->attrs["id"]) && !in_array($xml_subsection->attrs["id"], $hide_mode_ids))) {
							$subsection = array();
							$subsection["name"] = (isset($lang[$xml_subsection->attrs["name"]]) ? $lang[$xml_subsection->attrs["name"]] : $xml_subsection->attrs["name"]);
							$subsection["link"] = $xml_subsection->attrs["link"];
							if ($xml_subsection->children) {
								$item = array();
								foreach ( $xml_subsection->children as $xml_item_cnt => $xml_item ) {
									if (!isset($xml_item->attrs["id"]) || (isset($xml_item->attrs["id"]) && !in_array($xml_item->attrs["id"], $hide_mode_ids))) {
										$item[] = array("name" => (isset($lang[$xml_item->attrs["name"]]) ? $lang[$xml_item->attrs["name"]] : $xml_item->attrs["name"]), "link" => $xml_item->attrs["link"]);
									}
								}
								$subsection["subsection"] = $item;
							}
							$section["subsection"][] = $subsection;
						}
					}
				}
				$map[] = $section;
			}
		}
	}
	return $map;
}

/**
 * Replace ' and " to it's code in string $str
 *
 * @param string $str
 * @return string
 */
function OneTwoQuoteToCode($str) {
	$from = array("'", "\"");
	$to = array("&#145;", "&quot;");	
	return str_replace($from, $to, $str);
}

/**
 * Search string in lang file, if it was fined in some strings, returned them in the array 
 * (with highlighting)
 *
 * @param string $path
 * @param string $search_string
 * @return array
 */
function GetFindInFile($path, $search_string) {
	global $config;
	
	$lang_content = array();
	
	$xml_parser = new SimpleXmlParser($path);
	$xml_root = $xml_parser->getRoot();
	foreach ($xml_root->children as $cnt => $node) {
		if ($res = stristr($node->value, $search_string)) {
			$str_to_mark = utf8_substr($res,0,utf8_strlen($search_string));	
			$lang_content[$node->attrs["name"]] = str_replace($str_to_mark, "<font class='search_string'>".$str_to_mark."</font>", $node->value);
		}
	}	
	return $lang_content;
}

?>