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

$user = auth_index_user();
if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	if ($_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
} else {
	
}

if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
	AlertPage();
	exit;
}
if (GetSiteSettings("use_pilot_module_banners")) {
	Banners('info');
}

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

$strSQL = "SELECT l.id_ad, l.lat, l.lon, c.lat city_lat, c.lon city_lon, r.headline, r.comment FROM ".USERS_RENT_LOCATION_TABLE." l 
        INNER JOIN ".CITY_TABLE." c ON c.id = l.id_city
        INNER JOIN ".RENT_ADS_TABLE." r ON r.id = l.id_ad
        WHERE r.type = 1 and r.parent_id = 0 and r.status = '1'";
        
$rs = $dbconn->Execute($strSQL);
$active_rest = array();
while (!$rs->EOF) {
	$row = $rs->GetRowAssoc(false);
	$active_rest[] = array(
        'id' => $row['id_ad'], 
        'name' => 'test', 
        'lat' => $row['lat'] ? $row['lat'] : $row['city_lat'], 
        'lon' => $row['lon'] ? $row['lon'] : $row['city_lon'],
        'name' => $row['headline'],
        'desc' => str_replace(array("\r", "\n"), array('', ' '), $row['comment']));
	$rs->MoveNext();
}
$smarty->assign("map_active_rest", $active_rest);

$strSQL = "SELECT l.id_ad, l.lat, l.lon, c.lat city_lat, c.lon city_lon, r.headline, r.comment FROM ".USERS_RENT_LOCATION_TABLE." l 
        INNER JOIN ".CITY_TABLE." c ON c.id = l.id_city
        INNER JOIN ".RENT_ADS_TABLE." r ON r.id = l.id_ad
        WHERE r.type = 4 and r.parent_id = 0 and r.status = '1'";
        
$rs = $dbconn->Execute($strSQL);
$myselt_rest = array();
while (!$rs->EOF) {
	$row = $rs->GetRowAssoc(false);
	$myselt_rest[] = array(
        'id' => $row['id_ad'], 
        'name' => 'test', 
        'lat' => $row['lat'] ? $row['lat'] : $row['city_lat'], 
        'lon' => $row['lon'] ? $row['lon'] : $row['city_lon'],
        'name' => $row['headline'],
        'desc' => str_replace(array("\r", "\n"), array('', ' '), $row['comment']));
	$rs->MoveNext();
}
$smarty->assign("map_myself_rest", $myselt_rest);
IndexHomePage("map");

$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]) : "info.php";

$smarty->assign("file_name", $file_name);
$smarty->display(TrimSlash($config["index_theme_path"])."/allcities.tpl");

?>