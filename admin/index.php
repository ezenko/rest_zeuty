<?php
/**
* Index page for Admin Mode (login, logout)
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:25 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";

include "../include/functions_xml.php";

$auth = array();
$auth = auth_user();

@$sel = $_POST["sel"]?$_POST["sel"]:$_GET["sel"];

if (@($auth[4]==1) || @!(is_array($auth))){
	if (@($auth[0]>0) && $sel!="logoff"){
		$_SESSION["lang_cd"] = $auth[13];
		echo "<script>location.href='".$config["site_root"]."/admin/admin_homepage.php'</script>";
	} else {
		switch($sel){
			case "logoff": 	AdminLogout(); break;
			default: 	 	AdminLoginPage();
		}
	}
} elseif (@($auth[10]==2 || $auth[10]==3)){
	$_SESSION["lang_cd"] = $auth[13];
	//$auth[10]==2 - a realtor
	//$auth[10]==3 - agent of realtor
	echo "<script>location.href='".$config["site_root"]."/homepage.php'</script>";
} else {
	echo "<script>location.href='".$config["site_root"]."/index.php?sel=logoff'</script>";
}


function AdminLoginPage(){
	global $smarty, $dbconn, $config, $lang;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "index.php";


	IndexAdminPage("admin_login");

	if (isset($_POST["login_lg"]) || isset($_POST["pass_lg"])){
		GetErrors("auth_failed");
	}
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_login_table.tpl");
	exit;
}

function AdminLogout(){
	global $smarty, $dbconn, $config, $lang;

	setcookie("re_login", '', time()-7200);
	setcookie("re_pass", '', time()-7200);

	$strSQL = "Delete from ".ACTIVE_SESSIONS_TABLE." where id_user='".$user[0]."' and session='".session_id()."' ";
	$rs = $dbconn->Execute($strSQL);
	sess_delete(session_id());

	AdminLoginPage();
	exit;
}
?>