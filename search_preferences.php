<?php
/**
* Search preferences
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.4 $ $Date: 2008/10/31 12:56:38 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/class.lang.php";

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );
if($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
		AlertPage();
		exit;
	} elseif ($mode == 0) {
		AlertPage(GetRightModulePath(__FILE__));
		exit;
	}

	$multi_lang = new MultiLang($config, $dbconn);

	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	switch ($sel){
		case "delete_location":	DeleteSearchLocation(); break;
		case "make_preferred":	MakePreferred(); break;
		case "make_primary":	MakePrimary($user[0]); break;
		case "clean_search_history": CleanSearchHistory($user[0]); break;
		case "clean_search_preferences": CleanSearchPreferences($user[0]); break;
		default:	Preferences(); break;
	}
}


/**
 * Quick search form initialization
 *
 * @return void
 */
function Preferences(){
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "search_preferences.php";

	IndexHomePage('search_preferences','homepage');
	CreateMenu('search_menu');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$left_links = GetLeftLinks("search_preferences.php");
	$smarty->assign("left_links", $left_links);

	GetLocationContent();

	$search_pref = GetSearchPreferences($user[0]);

	$used_references = array("realty_type", "description");
	if ($search_pref) {
		/**
		 * load search preferences to references array
		 */
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$key = $multi_lang->TableKey($arr["spr_table"]);
				if ($arr["key"] == "realty_type") {
					$data[$key][] = $search_pref["realty_type"];
				} elseif ($arr["key"] == "description") {
					$data[$key][] = $search_pref["beds_number"];
					$data[$key][] = $search_pref["bath_number"];
					$data[$key][] = $search_pref["garage_number"];
				}				
			}
		}
	}
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], isset($data) ? $data : ""));
		}
	}

	$day = (isset($search_pref["move_day"]) && $search_pref["move_day"]) ? $search_pref["move_day"] : date("d")+1;
	$month = (isset($search_pref["move_month"]) && $search_pref["move_month"]) ? $search_pref["move_month"]: date("m");

	$smarty->assign("day", GetDaySelect($day));
	$smarty->assign("month", GetMonthSelect($month));

	$smarty->assign("search_pref", $search_pref );

	$smarty->assign("search_preferred", GetSearchLocationList($user[0], 1));
	$smarty->assign("search_history", GetSearchLocationList($user[0], 0));

	$smarty->assign("primary_location", GetPrimarySearchLocation($user[0]));

	$smarty->assign("from_file", "search_preferences");
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/search_preferences.tpl");
	exit;
}

/**
 * Delete Search Location
 *
 * @param void
 * @return void
 */
function DeleteSearchLocation($is_primary = false) {
	global $config, $dbconn;

	if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {
		$strSQL = "DELETE FROM ".SEARCH_LOCATION_TABLE." ".
				  "WHERE id='".intval($_REQUEST["id"])."'";
		$rs = $dbconn->Execute($strSQL);
	}
	Preferences();
	return;
}

/**
 * Make Search Location preferred
 *
 * @param void
 * @return void
 */
function MakePreferred() {
	global $config, $dbconn;

	if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {
		$strSQL = "UPDATE ".SEARCH_LOCATION_TABLE." SET ".
				  "is_preferred='1' ".
				  "WHERE id='".intval($_REQUEST["id"])."'";
		$rs = $dbconn->Execute($strSQL);
	}
	Preferences();
	return;
}

/**
 * Make Search Location Primary
 *
 * @param integer $id_user
 * @return void
 */
function MakePrimary($id_user) {
	global $config, $dbconn;

	if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {
		$strSQL = "UPDATE ".SEARCH_LOCATION_TABLE." SET ".
				  "is_primary='0' WHERE id_user='$id_user'";
		$rs = $dbconn->Execute($strSQL);

		$strSQL = "UPDATE ".SEARCH_LOCATION_TABLE." SET ".
				  "is_primary='1' ".
				  "WHERE id='".intval($_REQUEST["id"])."'";
		$rs = $dbconn->Execute($strSQL);
	}
	Preferences();
	return;
}

/**
 * Clean search location history
 *
 * @param integer $id_user
 * @return void
 */
function CleanSearchHistory($id_user) {
	global $config, $dbconn;
	$strSQL = "DELETE FROM ".SEARCH_LOCATION_TABLE." ".
			  "WHERE id_user='$id_user' AND is_preferred='0'";
	$rs = $dbconn->Execute($strSQL);

	Preferences();
	return;
}

/**
 * Clean users' search preferences
 *
 * @param integer $id_user
 * @return void
 */
function CleanSearchPreferences($id_user) {
	global $config, $dbconn;
	$strSQL = "DELETE FROM ".SEARCH_PREFERENCES_TABLE." ".
			  "WHERE id_user='$id_user'";
	$rs = $dbconn->Execute($strSQL);

	Preferences();
	return;
}
?>