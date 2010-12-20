<?php
/**
* View and edit language file
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.4 $ $Date: 2008/10/29 08:05:17 $
**/

include "../include/config.php";
include "../common.php";
include "../include/functions_admin.php";
include "../include/functions_auth.php";

include "../include/functions_xml.php";
include "../include/class.object2xml.php";

ini_set("display_errors", "0");
error_reporting(E_ALL);

$is_magic_quotes = intval(ini_get("magic_quotes_gpc"));

$auth = auth_user();
session_register("current");
session_register("edit");
session_register("name");
session_register("count");

if( (!($auth[0]>0))  || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

IndexAdminPage('admin_settings');
$smarty->assign("template_root", $config["admin_theme_path"]);

if (isset($_GET["edit"])){
	$edit = $_GET["edit"];
	$_SESSION["edit"] = $_GET["edit"];
} else {
	$edit = $_SESSION["edit"];
}

$admin_lang_menu = CreateMenu('admin_lang_menu');	
foreach ($admin_lang_menu as $lang_menu) {
	if ($lang_menu["name"] == $edit) {
		$lang_name = $lang_menu["value"];
		$smarty->assign("lang_name", $lang_name);
		break;
	}
}		

$file_path = (isset($_REQUEST["file_path"]) && !empty($_REQUEST["file_path"])) ? trim($_REQUEST["file_path"]) : "";
$search_string = (isset($_REQUEST["search_string"]) && !empty($_REQUEST["search_string"])) ? trim($_REQUEST["search_string"]) : "";
$s = (isset($_REQUEST["section"]) && !empty($_REQUEST["section"])) ? $_REQUEST["section"] : "";
$save = (isset($_REQUEST["save_purum"]) && !empty($_REQUEST["save_purum"])) ? $_REQUEST["save_purum"] : "";

if ($file_path) {
	$_SESSION["current"] = $file_path;
	$smarty->assign("file_path", $file_path);
	$is_menu_part = (strstr($file_path, "menu/")) ? 1 : 0;	
} else {
	$smarty->assign("part", $_GET["part"]);
	$part = "/".$_GET["part"]."/";
	$part = str_replace("//","/",$part);
	
	$is_menu_part = (strstr($part,"menu")) ? 1 : 0;
	
	$dir = $config["site_path"].'/lang'.$config["path_lang"]."/{$edit}".$part;
	$mes = scan_dir_file($dir, $part);

	$set_s = false;
	$sections = array();
	foreach ($mes as $id=>$value) {
		$sec = array();
		$sec["name"] = $value;
		$sec["val"] = $id;
		if (!$set_s) {
			if ($s == "") {
				$set_s = true;
				$sec["sel"] = 1;
				$_SESSION["current"] = $part.$sec["name"];
			} else {
				if($sec["val"] == $s ){
					$sec["sel"] = 1;
					$_SESSION["current"] = $part.$sec["name"];
				} else {
					$sec["sel"] = 0;
				}
			}
		} else {
			$sec["sel"] = 0;
		}
		$sections[] = $sec;
	}
	$smarty->assign("sections", $sections);
}

if ($save == 1) {
	$name = explode(",", $_SESSION["name"]);
	$count = $_SESSION["count"];
	$new_xml_strings = array();
	if ($is_menu_part) {
		$attrs = GetItemAttr("{$config["site_path"]}/lang/{$edit}".$_SESSION["current"], 1);
		for ( $i = 0; $i < $count; $i++ ) {
			$str = OneTwoQuoteToCode($_POST[$name[$i]]);
			$new_xml_strings[] = new XmlNode( "item", $attrs[$name[$i]], null, ($is_magic_quotes) ? stripslashes($str) : $str );
		}
		$xml_strings = new XmlNode( "menu", array(), $new_xml_strings );
	} else {
		$attrs = GetItemAttr("{$config["site_path"]}/lang/{$edit}".$_SESSION["current"], 0);
		for ( $i = 0; $i < $count; $i++ ) {
			$str = OneTwoQuoteToCode($_POST[$name[$i]]);
			$new_xml_strings[] = new XmlNode( "lines", $attrs[$name[$i]], null, ($is_magic_quotes) ? stripslashes($str) : $str );

		}
		$xml_strings = new XmlNode( "data", array(), $new_xml_strings );
	}
	$obj_saver = new Object2Xml(true);
	$obj_saver->Save( $xml_strings, "{$config["site_path"]}/lang/{$edit}".$_SESSION["current"]);
	unset( $xml_strings, $new_xml_strings, $obj_saver);
}

$name = array();
$descr = array();
$values = array();
$marked = array();
$first_marked_id = "";

$xml_parser = new SimpleXmlParser("{$config["site_path"]}/lang/{$edit}".$_SESSION["current"]);
$xml_root = $xml_parser->getRoot();
$count = $xml_root->childrenCount;

$innertext = "";
if ($is_menu_part) {
	foreach ( $xml_root->children as $node) {
		switch($node->tag){
			case "item":
				array_push($name, $node->attrs["name"]);
				array_push($descr, $node->attrs["href"]);
				array_push($values, $node->value );
				$is_marked = (stristr($node->value, $search_string) ? 1 : 0);
				array_push($marked, $is_marked);
				if ($is_marked && $first_marked_id == "") {
					$first_marked_id = $node->attrs["name"];
				}
			break;
		}
	}
} else {
	foreach ( $xml_root->children as $node){
		switch($node->tag){
			case "lines":
				array_push($name,$node->attrs["name"]);
				array_push($descr,$node->attrs["descr"]);
				array_push($values, $node->value );
				$is_marked = (stristr($node->value, $search_string) ? 1 : 0);
				array_push($marked, $is_marked);
				if ($is_marked && $first_marked_id == "") {
					$first_marked_id = $node->attrs["name"];
				}
			break;
		}
	}
}
$smarty->assign("name", $name);

$_SESSION["name"] = implode(",",$name);
$_SESSION["count"] = $count;

$smarty->assign("text", $text);

$smarty->assign("descr", $descr);
$smarty->assign("values", $values);
$smarty->assign("marked", $marked);

if ($first_marked_id != "") {
	$smarty->assign("on_load_action", "document.getElementById('".$first_marked_id."').focus();");
}

if (sizeof($name)>0){
	$smarty->assign("noempty", 1);
}

$smarty->assign("file_name", './admin_editfile.php');
$smarty->assign("subheader",  "| ".$lang["menu"]["sections_management"]." | ".$lang["menu"]["menu_and_content"]." | ".$lang["content"]["edit_langfiles"].((isset($lang_name)) ? " (".$lang_name.")" : ""));
$smarty->display("admin/admin_edit_langfile.tpl");


function scan_dir_file($dirname, $part) {

	$mass = array();
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false){
		if($file != "." && $file != ".."){
			if(is_file($dirname."/".$file)){
				$mass[] = $file;
			}
		}
	}
	closedir($dir);

	if ($part == "/") {
		$ignore_arr = array("metatags.xml", "confirm.xml", "groups.xml", "alert.xml", "smscoin_operators.xml");
		foreach ($mass as $id=>$file) {
			if (in_array($file, $ignore_arr) || substr($file, 0, 5) == "mail_") {
				unset($mass[$id]);
			}
		}
	}

	natcasesort($mass);
	return $mass;
}

function GetItemAttr($file_name, $menu) {

	$xml_parser = new SimpleXmlParser($file_name);
	$xml_root = $xml_parser->getRoot();

	$_array = array();
	if ($menu) {
		foreach ( $xml_root->children as $node) {
			switch($node->tag) {
				case "item":
				$_array[$node->attrs["name"]] = $node->attrs;
				break;
			}
		}
	} else {
		foreach ( $xml_root->children as $node) {
			switch($node->tag) {
				case "lines":
				$_array[$node->attrs["name"]] = $node->attrs;
				break;
			}
		}
	}
	return $_array;
}
?>