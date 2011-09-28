<?php
/**
* Error page
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 13:51:23 $
**/
//This is a test
include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/class.settings_manager.php";

$user = auth_index_user();
if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	if (isset($_REQUEST["for_unreg_user"]) && $_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
}

$errors_code = (isset($_REQUEST["code"]) && !empty($_REQUEST["code"])) ? $_REQUEST["code"] : 0;
$smarty->assign("errors_code", $errors_code);
ErrorsIndexPage($errors_code);


function ErrorsIndexPage($errors_code){
	global $smarty, $config, $dbconn, $user, $lang;
	
	$settings_manager = new SettingsManager();
	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
		CreateMenu('rental_menu');
		$link_count = GetCountForLinks($user[0]);
		$smarty->assign("link_count", $link_count);
		$left_links = GetLeftLinks("homepage.php");
		$smarty->assign("left_links", $left_links);
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	IndexHomePage('errors', 'errors');
	$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]) : "errors.php";	
	
	$current_lang_id = $config["default_lang"];
	$smarty->assign("error", $settings_manager->GetErrorByCode($errors_code, $current_lang_id));
		
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/errors_index_table.tpl");
	exit;
}


?>