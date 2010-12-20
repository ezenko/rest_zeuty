<?php
/**
* Admin homepage
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:24 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/Snoopy.class.php";

$auth = auth_user();

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if ( (!($auth[0]>0))  || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

if(isset($_SERVER["PHP_SELF"]))
$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
else
$file_name = "admin_homepage.php";

IndexAdminPage('admin_homepage');

CreateMenu('admin_lang_menu');

$data = array();

$strSQL = " SELECT COUNT(id) FROM ".USERS_TABLE." WHERE id<>'1' AND id<>'2' ";
$rs = $dbconn->Execute($strSQL);
$data["all_users"] = $rs->fields[0];

$strSQL = " SELECT COUNT(id) FROM ".USERS_TABLE." WHERE id<>'1' AND id<>'2' AND active='1' AND status='1' ";
$rs = $dbconn->Execute($strSQL);
$data["active_users"] = $rs->fields[0];

$strSQL = " SELECT COUNT(id) FROM ".USERS_TABLE." WHERE id<>'1' AND id<>'2' AND active='1' AND user_type='1' AND status='1' ";
$rs = $dbconn->Execute($strSQL);
$data["active_users_private"] = $rs->fields[0];

$strSQL = " SELECT COUNT(id) FROM ".USERS_TABLE." WHERE id<>'1' AND id<>'2' AND active='1' AND user_type='2' AND status='1' ";
$rs = $dbconn->Execute($strSQL);
$data["active_users_agencies"] = $rs->fields[0];

$strSQL = " SELECT COUNT(id) FROM ".USERS_TABLE." WHERE id<>'1' AND id<>'2' AND active='1' AND user_type='3' AND status='1' ";
$rs = $dbconn->Execute($strSQL);
$data["active_users_agents"] = $rs->fields[0];

$strSQL = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." ";
$rs = $dbconn->Execute($strSQL);
$data["all_ads"] = $rs->fields[0];

$strSQL = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE status='1' ";
$rs = $dbconn->Execute($strSQL);
$data["active_ads"] = $rs->fields[0];

$types = array(1 => "need", 2 => "have", 3 => "buy", 4 => "sell");
foreach ($types as $type_id => $type_name) {
	$strSQL = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE status='1' AND type='$type_id' AND room_type='0' ";
	$rs = $dbconn->Execute($strSQL);
	$data[$type_name."_ads"] = $rs->fields[0];
}

$strSQL = "	SELECT COUNT(id) FROM ".MAILBOX_MESSAGES_TABLE." ";
$rs = $dbconn->Execute($strSQL);
$data["mailbox_all"] = $rs->fields[0];

$strSQL = "	SELECT COUNT(DISTINCT(from_user_id)) FROM ".MAILBOX_MESSAGES_TABLE;
$rs = $dbconn->Execute($strSQL);
$data["mailbox_from_user_cnt"] = $rs->fields[0];

$smarty->assign("page_type","start");

$smarty->assign("data", $data);
$smarty->assign("file_name", $file_name);
$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_homepage.tpl");
?>