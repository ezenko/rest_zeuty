<?php
/**
* Index page (load index page, logout user)
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.7 $ $Date: 2009/01/21 11:04:05 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/class.lang.php";
include "./include/class.calendar_event.php";

if (isset($_REQUEST["from_install"]) && $_REQUEST["from_install"] == 1) {
	include "./include/class.news.php";
	NewsUpdater();
	header("Location: ".$config["server"].$config["site_root"]."/index.php");
}

CheckInstallFolder();

$user = auth_index_user();

@$sel = $_POST["sel"]?$_POST["sel"]:$_GET["sel"];

if ($sel!='logoff'){
	if ( isset($_COOKIE["re_login"]) && isset($_COOKIE["re_pass"]) ){
		$strSQL = "SELECT id FROM ".USERS_TABLE." WHERE login='".addslashes($_COOKIE["re_login"])."' AND password='".addslashes($_COOKIE["re_pass"])."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0){
			sess_write(session_id(), $rs->fields[0]);
			$user = auth_index_user();
		}
	}
} else {
	setcookie("re_login", '', time()-7200);
	setcookie("re_pass", '', time()-7200);
}

$multi_lang = new MultiLang($config, $dbconn);

if($user[4]==1 && $sel!='logoff' && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	if(intval($user[0]) && $user[3] != 1 && $sel == "" && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
		echo "<script>location.href='".$config["site_root"]."/homepage.php'</script>";
	}
	if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
		if ($_REQUEST["for_unreg_user"] == 1) {
			$user = auth_guest_read();
		}
	}
}

switch($sel){
	case "logoff": LogoutUser(); break;
	case "quick_form" : FromQuick(); break;
	default: 	IndexPage();
}

function IndexPage(){
	global $smarty, $config, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = strtolower(AfterLastSlash($_SERVER["PHP_SELF"]));
	else
	$file_name = "index.php";

	IndexHomePage('index');
	CreateMenu('index_page_menu');
	CreateMenu('index_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	if (isset($_REQUEST["back"]) && intval($_REQUEST["back"]) == 1 && isset($_SESSION["quick_search_pars"])) {
		/**
		 * Load search settings
		 */		
		$data = $_SESSION["quick_search_pars"];
		$used_references = array("realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$key = $multi_lang->TableKey($arr["spr_table"]);
				if (!empty($data[$arr["key"]])) {
					$data[$key] = GetBackData($data[$arr["key"]]);
				}
			}
		}
		$used_references = array("realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], $data));
			}
		}
		$search_pref = $data;		
		GetLocationContent($data["country"], $data["region"]);						
		$smarty->assign("search_pref", $search_pref);
	} else {
		GetLocationContent();

		$used_references = array("realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], ''));
			}
		}
		$data["qsform_more_opt"] = 1;
	}	
	$day = (isset($search_pref["move_day"]) && $search_pref["move_day"]) ? $search_pref["move_day"] : date("d")+1;
	$month = (isset($search_pref["move_month"]) && $search_pref["move_month"]) ? $search_pref["move_month"]: date("m");

	$smarty->assign("day", GetDaySelect($day));
	$smarty->assign("month", GetMonthSelect($month));
	
	unset($_SESSION["quick_search_pars"]);
	unset($_SESSION["quick_search_arr"]);
		
	// Get parametres of showing ads for unregistered users from TABLE_SHOW_ADS_AREA
	$area_parametres = GetOrderAds("index",0);
	$smarty->assign("last_ads",1);
	if ($area_parametres["show_type"] != "off")	{
		GetLastAds("last_ads_num_at_page", 1, "?", $area_parametres["sorter"], $area_parametres["sorter_order"], "",
				 $area_parametres["show_type"], $area_parametres["ads_number"], $file_name);
	}

	$smarty->assign("data", $data);
	$smarty->assign("area_parametres", $area_parametres);
	$smarty->assign("from_file", "index");
	$smarty->assign("file_name", $file_name);
	if (strpos($config["index_theme_path"], "/default_theme") != 0){
		$smarty->assign("is_default_theme", 1);
	}else{
		$smarty->assign("is_default_theme", 0);
	}
  
	$smarty->display(TrimSlash($config["index_theme_path"])."/index_home_page.tpl");
	exit;
}

function LogoutUser(){
	global $smarty, $config, $dbconn, $user;

	$strSQL = "Delete from ".ACTIVE_SESSIONS_TABLE." where id_user='".$user[0]."' and session='".session_id()."' ";
	$rs = $dbconn->Execute($strSQL);

	echo "<script>location.href='".$config["site_root"]."/index.php'</script>";
	return;
}
?>