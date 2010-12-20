<?php
/**
* Show ads on areas management
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

$auth = auth_user();

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

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "list_areas";
$smarty->assign("sel", $sel);

switch($sel){
	case "list_areas": ListAreas(); break;
	case "save": SaveAreasSettings(); break;
	default: ListAreas();
}

function ListAreas($area_err = array(), $err = array()) {
	global $smarty, $dbconn, $config;

	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_show_ads_area.php";

	IndexAdminPage ('admin_show_ads_area');
	CreateMenu('admin_lang_menu');

	$smarty->assign("err", $err);

	$strSQL = "SELECT id, area, for_registered, show_type, ads_number, view_type ".
			  "FROM ".SHOW_ADS_AREA_TABLE." ORDER BY for_registered DESC";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while (!$rs->EOF) {
		$area[$i] = $rs->getRowAssoc( false );
		$area[$i]["ads_number"] = (isset($area_err[$area[$i]["id"]])) ? $area_err[$area[$i]["id"]] : $area[$i]["ads_number"];
		$i++;
		$rs->MoveNext();
	}
	$smarty->assign("show_areas", $area);

	$show_type = array("max_views", "admin_choose", "last_added", "off");
  	$view_type = array("list", "row");
	$smarty->assign("show_type", $show_type);
	$smarty->assign("view_type", $view_type);

	$smarty->assign("max_ads_number", GetSiteSettings("max_ads_number"));
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_show_ads_area.tpl");
}

function SaveAreasSettings() {
	global $dbconn, $config;

	$max_ads_number = GetSiteSettings("max_ads_number");

	$err = array();
	foreach ($_REQUEST["ads_number"] as $id=>$value) {
		$val = trim($value);
		if (!$val || !IsPositiveInt($val) || $val>$max_ads_number) {
			$err["ads_number"][$id] = "positive_int_not_more_than";
			$data[$id] = "";
		} else {
			$data[$id] = intval($val);
		}
	}
	if (count($err) > 0) {
		ListAreas($data, $err);
		exit();
	}

	foreach ($_REQUEST["show_type"] as $area_id=>$show_type) {
		$strSQL = "UPDATE ".SHOW_ADS_AREA_TABLE." SET ads_number='".$data[$area_id]."', ".
				  "view_type='".$_REQUEST["view_type"][$area_id]."', ".
				  "show_type='".$show_type."' ".
				  "WHERE id='$area_id'";
		$rs = $dbconn->Execute($strSQL);
	}

	ListAreas();
	exit();
}

?>