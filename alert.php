<?php
/**
* Alert page. User is redirected here from page, if he has no access permissions on it.
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.4 $ $Date: 2008/10/14 14:07:43 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

$confirm = (isset($_REQUEST["need"]) && !empty($_REQUEST["need"])) ? $_REQUEST["need"] : "";
switch($confirm){
	case "no" :	{
		$user=auth_guest_read();
		$user[7]=0;
		$user[8]=1;
		$user[9]=0;
	}
	break;
	default : $user = auth_index_user();
}


$lang["errors"] = GetLangContent('errors');
if($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}else {
	if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
		if ($_REQUEST["for_unreg_user"] == 1) {
			$user = auth_guest_read();
		}
	}
	$id_module = (isset($_REQUEST["id_module"]) && !empty($_REQUEST["id_module"])) ? intval($_REQUEST["id_module"]) : 0;
	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	switch($sel){
		case "access": 	AlertTable($id_module, $confirm); break;
		default: 		AlertTable("", $confirm);
	}
}

function AlertTable($id_module = "", $confirm = ""){
	global $smarty, $lang, $config, $dbconn, $user;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "alert.php";

	IndexHomePage('alert');
	if($user[3] != 1) {
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
		CreateMenu('account_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}

	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$smarty->assign("noconfirm", 0);

	if ($user[3] == 1 && $confirm != "no") {
		/**
		 * guest user
		 */
		$form["alert_msg"] = $lang["errors"]["need_registration_1"]." <a href=\"./registration.php\">".$lang["errors"]["need_registration_2"]."</a>".$lang["errors"]["need_registration_3"];
	} else {
		if (intval($id_module)>0){
			$form["alert_msg"] = $lang["errors"]["no_access_1"]." <a href=\"./services.php?sel=group\">".$lang["errors"]["no_access_2"]."</a>".$lang["errors"]["no_access_3"];	
		} else {
			if ($user[8] == 0) {
				$form["alert_msg"] = $lang["errors"]["inactive_account_1"]." <a href=\"./account.php?sel=activate\">".$lang["errors"]["inactive_account_2"]."</a>".$lang["errors"]["inactive_account_3"];
			} elseif ($user[7] == 0) {
				$form["alert_msg"] = $lang["errors"]["no_status_1"]." <a href=\"./contact.php\">".$lang["errors"]["no_status_2"]."</a>".$lang["errors"]["no_status_3"];
				if ($user[9] == 0) {
					$form["alert_msg"] = $lang["errors"]["no_confirm"];
					$smarty->assign("noconfirm", 1);
				}
			}
		}
	}
	if (!isset($form["alert_msg"]) || $form["alert_msg"] == "") {
		$form["alert_msg"] = $lang["errors"]["no_access_1"]." <a href=\"./services.php?sel=group\">".$lang["errors"]["no_access_2"]."</a>".$lang["errors"]["no_access_3"];	
	}
	$smarty->assign("form", $form);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/alert_table.tpl");
	exit;
}

?>