<?php
/**
* Info page (read Info)
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/13 07:58:40 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

include "./include/class.entertaiment_manager.php";

$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;

$user = auth_index_user();
if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	if ($_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
	/**
	 * Set id for pages' preview
	 */
	$rs = $dbconn->Execute("SELECT MIN(id) FROM ".INFO_SECTION_TABLE." WHERE status='1' AND language_id='{$config["default_lang"]}'");
	$id = $rs->fields[0];
} else {
	if (!$id) {
		/**
		 * @todo page was removed - for crawler
		 */
		header("Location: ".$config["server"].$config["site_root"]."/index.php");
		exit();
	}
}

if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
	AlertPage();
	exit;
}
if (GetSiteSettings("use_pilot_module_banners")) {
	Banners('info');
}

$subsection_id = (isset($_REQUEST["subsection_id"]) && !empty($_REQUEST["subsection_id"])) ? intval($_REQUEST["subsection_id"]) : 0;

if($user[3] != 1) {
	//homepage menu if user registered
	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
} else {
	//index menu if user not registered
	CreateMenu('index_top_menu');
	CreateMenu('index_user_menu');
}
CreateMenu('lang_menu');
CreateMenu('bottom_menu');
CreateMenu('rental_menu');

IndexHomePage("entertaiment", "info_$id");

$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]) : "info.php";

$info_manager = new EntertaimentManager();

$section = $info_manager->GetEntertaiment($id);
$smarty->assign("section", $section);

if ($section["caption"]) {
	$smarty->assign("page_title", $section["caption"]);
}


$smarty->assign("file_name", $file_name);
$smarty->display(TrimSlash($config["index_theme_path"])."/video.tpl");

?>