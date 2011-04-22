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

function GetRealtStyle($type) {
    switch($type) {
        case 36:
            $style = "rest#hotel";
            break;
        case 50:
            $style = "rest#san";
            break;
        case 51:
            $style = "rest#pans";
            break;
        case 52:
            $style = "rest#minihotel";
            break;
        case 53:
            $style = "rest#guesthouse";
            break;
        case 54:
            $style = "rest#camping";
            break;
    }
    return $style;
}

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

$strSQL = "SELECT c.* FROM ".CITY_TABLE." c";
        
$rs = $dbconn->Execute($strSQL);
$cities = array();
while (!$rs->EOF) {
	$row = $rs->GetRowAssoc(false);
	$cities[] = array(
        'id' => $row['id'], 
        'lat' => $row['lat'], 
        'lon' => $row['lon'],
        'name' => $row['name'],
        'show_on_map' => $row['show_on_map'],
        'country' => $row['id_country'],
        'region' => $row['id_region']);
	$rs->MoveNext();
}
$smarty->assign("map_cities", $cities);


$strSQL = "SELECT l.id_ad, l.lat, l.lon, c.lat city_lat, c.lon city_lon, r.headline, r.comment, t.id_value FROM ".USERS_RENT_LOCATION_TABLE." l 
        INNER JOIN ".CITY_TABLE." c ON c.id = l.id_city
        INNER JOIN ".RENT_ADS_TABLE." r ON r.id = l.id_ad
        INNER JOIN ".SPR_THEME_REST_USER_TABLE." t ON r.id = t.id_ad
        WHERE r.type = 4 and r.parent_id = 0 and r.status = '1'";
        
$rs = $dbconn->Execute($strSQL);
$active_rest = array();
$myselt_rest = array();
while (!$rs->EOF) {
	$row = $rs->GetRowAssoc(false);
    $active_rest[] = array(
        'id' => $row['id_ad'], 
        'lat' => $row['lat'] ? $row['lat'] : $row['city_lat'], 
        'lon' => $row['lon'] ? $row['lon'] : $row['city_lon'],
        'name' => $row['headline'],
        'desc' => str_replace(array("\r", "\n"), array('', ' '), $row['comment']),
        'style' => 'rest#a'.$row['id_value'].'id');
    $rs->MoveNext();
}

$strSQL = "SELECT l.id_ad, l.lat, l.lon, c.lat city_lat, c.lon city_lon, r.headline, r.comment, r.type FROM ".USERS_RENT_LOCATION_TABLE." l 
        INNER JOIN ".CITY_TABLE." c ON c.id = l.id_city
        INNER JOIN ".RENT_ADS_TABLE." r ON r.id = l.id_ad
        WHERE r.type = 1 and r.parent_id = 0 and r.status = '1'";
        
$rs = $dbconn->Execute($strSQL);
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
$smarty->assign("map_active_rest", $active_rest);
$smarty->assign("map_myself_rest", $myselt_rest);

$strSQL = "SELECT l.id_ad, l.lat, l.lon, c.lat city_lat, c.lon city_lon, r.headline, r.comment, t.id_value FROM ".USERS_RENT_LOCATION_TABLE." l 
        INNER JOIN ".CITY_TABLE." c ON c.id = l.id_city
        INNER JOIN ".RENT_ADS_TABLE." r ON r.id = l.id_ad
        INNER JOIN ".SPR_RENT_TYPE_USER_TABLE." t ON r.id = t.id_ad
        WHERE r.type = 3 and r.parent_id = 0 and r.status = '1'";
        
$rs = $dbconn->Execute($strSQL);
$realtestate = array();

while (!$rs->EOF) {
	$row = $rs->GetRowAssoc(false);
    $realtestate[] = array(
        'id' => $row['id_ad'], 
        'lat' => $row['lat'] ? $row['lat'] : $row['city_lat'], 
        'lon' => $row['lon'] ? $row['lon'] : $row['city_lon'],
        'name' => $row['headline'],
        'desc' => str_replace(array("\r", "\n"), array('', ' '), $row['comment']),
        'style' => GetRealtStyle($row['id_value']));
    $rs->MoveNext();
}
$smarty->assign("map_realestate", $realtestate);

$info_manager = new EntertaimentManager();
$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? $_REQUEST["language_id"] : $config["default_lang"];
		
$smarty->assign("map_entertaiments", $info_manager->GetEntertaimentListWithCoords( $current_lang_id));

IndexHomePage("map");

$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]) : "info.php";

$smarty->assign("file_name", $file_name);
$smarty->display(TrimSlash($config["index_theme_path"])."/allcities.tpl");

?>