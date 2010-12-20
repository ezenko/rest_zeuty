<?php
/**
* Advanced search
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.9 $ $Date: 2009/02/03 08:41:08 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/class.lang.php";
include "./include/class.calendar_event.php";

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

if ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
		AlertPage();
		exit;
	} elseif ($mode == 0) {
		AlertPage(GetRightModulePath(__FILE__));
		exit;
	}
	session_register("power_searchr_pars");
	session_register("power_searchr_id_arr");
	session_register("feature");

	$multi_lang = new MultiLang($config, $dbconn);
	$smarty->assign("map", GetMapSettings());
	
	SaveSearchLocation($user[0]);
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	$smarty->assign("sel",$sel);
	switch ($sel) {
		case "save_search":			SaveSearch(); break;
		case "delete_search":		DelSearch(); break;
		case "load_search":			LoadSearch($_GET["id_save"]); break;
		case "search":				Search(); break;
		case "new_members":			Search("new_members"); break;
		case "by_nick":				Search("by_nick"); break;
		default: 					PowerSearchForm('', 1); break;
	}
}

/**
 * Advanced search from
 *
 * @param array $data - search criteria array
 * @param integer $load_search_preferences - flag, if is set to 1, load users' search preferences to the search array criteria
 * @return void
 */
function PowerSearchForm($data='', $load_search_preferences = 0) {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "power_searchr.php";

	IndexHomePage('power_searchr','search');

	if ($user[3] != 1) {
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('search_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count", $link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	$smarty->assign("submenu", "power_search");

	unset($_SESSION["power_searchr_id_arr"]);

	$back = (isset($_REQUEST["back"])) ? intval($_REQUEST["back"]) : 0;
	if ($back) {
		/**
		 * Load previous search criteria array
		 */
		$data = $_SESSION["power_searchr_pars"];		
		$used_references = array("info", "period", "realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$key = $multi_lang->TableKey($arr["spr_table"]);
				if (!empty($data[$arr["key"]])) {
					$data[$key] = GetBackData($data[$arr["key"]]);
				}
			}
		}
	} elseif ($load_search_preferences) {
		/**
		 * Load users' search preferences
		 */
		$search_location = GetPrimarySearchLocation($user[0]);
		GetLocationContent($search_location["id_country"], $search_location["id_region"]);
		$data["region"] = $search_location["id_region"];
		$data["city"] = $search_location["id_city"];

		$search_pref = GetSearchPreferences($user[0]);
		$used_references = array("info", "period", "realty_type", "description");
		if ($search_pref) {
			foreach ($search_pref as $s_key=>$s_value) {
				$data[$s_key] = $s_value;
			}
			/**
			 * Load search preferences to references array
			 */
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$value = "";
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
		} else {
			$data["choise"] = 4;
		}
		$day = (isset($search_pref["move_day"])) ? $search_pref["move_day"] : date("d")+1;
		$month = (isset($search_pref["move_month"])) ? $search_pref["move_month"]: date("m");
		$year = date("Y");
	}
	unset($_SESSION["power_searchr_pars"]);

	if ($back || !$load_search_preferences) {
		GetLocationContent( ($data["country"]) ? $data["country"] : "", ($data["region"]) ? $data["region"] : "");

		if ($data["choise"]<1) {
			$data["choise"] = 4;
		}
		$data["use_movedate"] = (isset($data["use_movedate"])) ? ($data["use_movedate"]) : 0;

		if (isset($data["movedate"]) && $data["movedate"] != "0000-00-00" && $data["movedate"] != "0") {
			$timestamp = mktime(0, 0, 0, $data["move_month"], $data["move_day"], $data["move_year"]);
			$day = date("d", $timestamp);
			$month = date("m", $timestamp);
			$year = date("Y", $timestamp);
		} else {
			$day = date("d")+1;
			$month = date("m");
			$year = date("Y");
		}
	}
	$smarty->assign("day", GetDaySelect($day));
	$smarty->assign("month", GetMonthSelect($month));
	$smarty->assign("year", GetYearSelect($year, 3, intval($year+2)));
	
	$used_references = array("info", "period", "realty_type", "description");
	/**
	 * load references with selected items from $data
	 */
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], $data, 2));
		}
	}
	
	$smarty->assign("data", $data);
	/**
	 * Load users' saved searches
	 */
	$strSQL = "SELECT id, name FROM ".SAVE_POWERSEARCHR_TABLE." WHERE id_user = '".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			$load[$row['id']] = $row['name'];
			$rs->MoveNext();
		}
		$smarty->assign("load", $load);
	}	
	
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/power_searchr_form.tpl");
	exit;
}

/**
 * Save search criteria
 *
 * @return void
 */
function SaveSearch() {
	global $config, $smarty, $dbconn, $user, $multi_lang, $REFERENCES;

	$search_name = strip_tags(trim($_POST["search_name"]));
	
	$strSQL = "SELECT id FROM ".SAVE_POWERSEARCHR_TABLE." WHERE ".
			  "name='".stripslashes($search_name)."' AND id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	$search_id = ($rs->fields[0] > 0) ? $rs->fields[0] : 0;
		
	$search_par["choise"] = intval($_REQUEST["choise"]);
	$search_par["country"] = isset($_REQUEST["country"]) ? intval($_REQUEST["country"]) : 0;
	$search_par["region"] = isset($_REQUEST["region"]) ? intval($_REQUEST["region"]) : 0;
	$search_par["city"] = isset($_REQUEST["city"]) ? intval($_REQUEST["city"]) : 0;
	$search_par["min_payment"] = intval($_REQUEST["min_payment"]);
	$search_par["max_payment"] = intval($_REQUEST["max_payment"]);
	$search_par["auction"] = intval($_REQUEST["auction"]);
	$search_par["min_deposit"] = intval($_REQUEST["min_deposit"]);
	$search_par["max_deposit"] = intval($_REQUEST["max_deposit"]);
	$search_par["move_year"] = isset($_REQUEST["move_year"]) ? intval($_REQUEST["move_year"]) : 0;
	$search_par["move_month"] = isset($_REQUEST["move_month"]) ? intval($_REQUEST["move_month"]) : 0;
	$search_par["move_day"] = isset($_REQUEST["move_day"]) ? intval($_REQUEST["move_day"]) : 0;		
	$search_par["movedate"] = sprintf("%04d-%02d-%02d", $search_par["move_year"], $search_par["move_month"], $search_par["move_day"]);			
	$search_par["use_movedate"] = (isset($_REQUEST["use_movedate"])) ? intval($_REQUEST["use_movedate"]) : 0;
	$search_par["min_year_build"] = intval($_REQUEST["min_year_build"]);
	$search_par["max_year_build"] = intval($_REQUEST["max_year_build"]);
	$search_par["min_live_square"] = intval($_REQUEST["min_live_square"]);
	$search_par["max_live_square"] = intval($_REQUEST["max_live_square"]);
	$search_par["min_total_square"] = intval($_REQUEST["min_total_square"]);
	$search_par["max_total_square"] = intval($_REQUEST["max_total_square"]);
	$search_par["min_land_square"] = intval($_REQUEST["min_land_square"]);
	$search_par["max_land_square"] = intval($_REQUEST["max_land_square"]);
	$search_par["min_floor"] = intval($_REQUEST["min_floor"]);
	$search_par["max_floor"] = intval($_REQUEST["max_floor"]);
	$search_par["floor_num"] = intval($_REQUEST["floor_num"]);
	$search_par["subway_min"] = intval($_REQUEST["subway_min"]);		
	$search_par["with_photo"] = (isset($_REQUEST["with_photo"])) ? intval($_REQUEST["with_photo"]) : 0;
	$search_par["with_video"] = (isset($_REQUEST["with_video"])) ? intval($_REQUEST["with_video"]) : 0;

	$strSQL = "";
	if ($search_id) {
		$dbconn->Execute("DELETE FROM ".SAVE_POWERSEARCHR_DESCR_TABLE." WHERE id_save='".$search_id."' ");
		$strSQL .= "UPDATE ".SAVE_POWERSEARCHR_TABLE." SET ";	
	} else {
		$strSQL .= "INSERT INTO ".SAVE_POWERSEARCHR_TABLE." SET ";
	}	
	$strSQL .= "choise='".$search_par["choise"]."', ".
				"country='".$search_par["country"]."', ".
				"region='".$search_par["region"]."', ".
				"city='".$search_par["city"]."', ".				
				"min_payment='".$search_par["min_payment"]."', ".
				"max_payment='".$search_par["max_payment"]."', ".
				"auction='".$search_par["auction"]."', ".
				"min_deposit='".$search_par["min_deposit"]."', ".
				"max_deposit='".$search_par["max_deposit"]."', ".				
				"movedate='".$search_par["movedate"]."', ".
				"use_movedate='".$search_par["use_movedate"]."', ".
				"min_year_build='".$search_par["min_year_build"]."', ".
				"max_year_build='".$search_par["max_year_build"]."', ".
				"min_live_square='".$search_par["min_live_square"]."', ".
				"max_live_square='".$search_par["max_live_square"]."', ".
				"min_total_square='".$search_par["min_total_square"]."', ".
				"max_total_square='".$search_par["max_total_square"]."', ".
				"min_land_square='".$search_par["min_land_square"]."', ".
				"max_land_square='".$search_par["max_land_square"]."', ".
				"min_floor='".$search_par["min_floor"]."', ".
				"max_floor='".$search_par["max_floor"]."', ".
				"floor_num='".$search_par["floor_num"]."', ".
				"subway_min='".$search_par["subway_min"]."', ".
				"with_photo='".$search_par["with_photo"]."', ".
				"with_video='".$search_par["with_video"]."', ".
				"id_user='".$user[0]."', ".
				"name='".$search_name."'";
	if ($search_id) {
		$strSQL .= " WHERE id='".$search_id."'";
	}	
		
	$dbconn->Execute($strSQL);
	
	if ($search_id) {
		$id_save = $search_id;
	} else {		
		$id_save = $dbconn->Insert_ID();
	}

	$used_references = array("info", "period", "realty_type", "description");
	foreach ($used_references as $id=>$value) {
		if (isset($_REQUEST[$value])) {
			SearchSprEdit(SAVE_POWERSEARCHR_DESCR_TABLE, $id_save, $_REQUEST["id_spr_".$value], $_REQUEST[$value]);
		}	
	}

	LoadSearch($id_save);
	exit;
}

/**
 * Load saved search criteria
 *
 * @param integer $id_save - save search id
 * @return void
 */
function LoadSearch($id_save) {
	global $config, $smarty, $dbconn, $user, $multi_lang;
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	$strSQL = "	SELECT id as id_save, name as search_name, choise, country, region, city,
				min_payment, max_payment, auction, min_deposit, max_deposit,
				UNIX_TIMESTAMP(movedate) as movedate, use_movedate,
				min_year_build, max_year_build, min_live_square, max_live_square,
				min_total_square, max_total_square, min_land_square, max_land_square,
				min_floor, max_floor, floor_num, subway_min, with_photo, with_video
				FROM ".SAVE_POWERSEARCHR_TABLE."
				WHERE id='".$id_save."' AND id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);

	if ($rs->RowCount() >0) {
		$data = $rs->GetRowAssoc(false);
		if ($data["movedate"]) {
			$timestamp = $data["movedate"];
			$data["move_day"] = date("d", $timestamp);
			$data["move_month"] = date("m", $timestamp);
			$data["move_year"] = date("Y", $timestamp);
		}		
		$strSQL = "SELECT DISTINCT id_spr FROM ".SAVE_POWERSEARCHR_DESCR_TABLE." WHERE id_save='".$id_save."' ORDER BY id_save";
		$rs = $dbconn->Execute($strSQL);

		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			$strSQL_opt = "SELECT id_info FROM ".SAVE_POWERSEARCHR_DESCR_TABLE." WHERE id_save='".$id_save."' AND id_spr='".$row["id_spr"]."' ORDER BY id_info";
			$rs_opt = $dbconn->Execute($strSQL_opt);
			$j = 0;
			while (!$rs_opt->EOF) {
				$row_opt = $rs_opt->GetRowAssoc(false);
				$data[$row["id_spr"]][$j] = $row_opt["id_info"];
				$rs_opt->MoveNext();
				$j++;
			}
			$rs->MoveNext();
		}
	}
	PowerSearchForm($data);
}

/**
 * Delete saved search
 *
 * @return void
 */
function DelSearch() {
	global $config, $smarty, $dbconn, $user, $multi_lang;
	$id_save = intval($_GET["id_save"]);
	$strSQL = "DELETE FROM ".SAVE_POWERSEARCHR_TABLE." WHERE id='".$id_save."' AND id_user='".$user[0]."' ";
	$dbconn->Execute($strSQL);
	$strSQL = "DELETE FROM ".SAVE_POWERSEARCHR_DESCR_TABLE." WHERE id_save='".$id_save."'";
	$dbconn->Execute($strSQL);
	PowerSearchForm();
	exit;
}

/**
 * Generate search request and show search results
 *
 * @param string $par - search type
 * @return void
 */
function Search($par="") {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $values_arr, $REFERENCES;

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 0;

	$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]) : "power_searchr.php";
	IndexHomePage('power_searchr','homepage');

	if ($user[3] != 1) {
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');
	CreateMenu('search_menu');

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count", $link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$smarty->assign("submenu", "power_search");

	$id_arr = array();
	$use_session = 0;

	if ($page > 0 && isset($_SESSION["power_searchr_id_arr"])) {
		$id_arr = $_SESSION["power_searchr_id_arr"];
		$use_session = 1;
		if ($par=='') {
			$par = "search";
		}
	}
	if ($page == 0) {
		$page = 1;
	}
	
	$search_par = array();	
	$used_references = array("info", "period", "realty_type", "description");
	if ($use_session == 0 && $par != "by_nick" && $par != "new_members") {		
		$search_par["choise"] = intval($_REQUEST["choise"]);
		$search_par["country"] = isset($_REQUEST["country"]) ? intval($_REQUEST["country"]) : 0;
		$search_par["region"] = isset($_REQUEST["region"]) ? intval($_REQUEST["region"]) : 0;
		$search_par["city"] = isset($_REQUEST["city"]) ? intval($_REQUEST["city"]) : 0;
	
		$search_par["min_payment"] = intval($_REQUEST["min_payment"]);
		$search_par["max_payment"] = intval($_REQUEST["max_payment"]);
		$search_par["auction"] = intval($_REQUEST["auction"]);
		$search_par["min_deposit"] = intval($_REQUEST["min_deposit"]);
		$search_par["max_deposit"] = intval($_REQUEST["max_deposit"]);
		$search_par["move_year"] = isset($_REQUEST["move_year"]) ? intval($_REQUEST["move_year"]) : 0;
		$search_par["move_month"] = isset($_REQUEST["move_month"]) ? intval($_REQUEST["move_month"]) : 0;
		$search_par["move_day"] = isset($_REQUEST["move_day"]) ? intval($_REQUEST["move_day"]) : 0;		
		$search_par["movedate"] = sprintf("%04d-%02d-%02d", $search_par["move_year"], $search_par["move_month"], $search_par["move_day"]);			
		$search_par["use_movedate"] = (isset($_REQUEST["use_movedate"])) ? intval($_REQUEST["use_movedate"]) : 0;
		$search_par["min_year_build"] = intval($_REQUEST["min_year_build"]);
		$search_par["max_year_build"] = intval($_REQUEST["max_year_build"]);
		$search_par["min_live_square"] = intval($_REQUEST["min_live_square"]);
		$search_par["max_live_square"] = intval($_REQUEST["max_live_square"]);
		$search_par["min_total_square"] = intval($_REQUEST["min_total_square"]);
		$search_par["max_total_square"] = intval($_REQUEST["max_total_square"]);
		$search_par["min_land_square"] = intval($_REQUEST["min_land_square"]);
		$search_par["max_land_square"] = intval($_REQUEST["max_land_square"]);
		$search_par["min_floor"] = intval($_REQUEST["min_floor"]);
		$search_par["max_floor"] = intval($_REQUEST["max_floor"]);
		$search_par["floor_num"] = intval($_REQUEST["floor_num"]);
		$search_par["subway_min"] = intval($_REQUEST["subway_min"]);
		
		$search_par["with_photo"] = (isset($_REQUEST["with_photo"])) ? intval($_REQUEST["with_photo"]) : 0;
		$search_par["with_video"] = (isset($_REQUEST["with_video"])) ? intval($_REQUEST["with_video"]) : 0;
		
		foreach ($used_references as $key=>$value) {
			$search_par["spr_".$value] = $_REQUEST["spr_".$value];
			$search_par[$value] = isset($_REQUEST[$value]) ? $_REQUEST[$value] : array();
		}
	}

	if (isset($_GET["par"]) && ($_GET["par"]=="back" || $_GET["par"]=="send")) {
		if (isset($_SESSION["power_searchr_id_arr"])) {
			$id_arr = $_SESSION["power_searchr_id_arr"];
			//use array of listings ids which were saved in $_SESSION
			$use_session = 1;
		} else {
			foreach ($_SESSION["power_searchr_pars"] as $key=>$value) {
				$search_par[$key] = $value;
			}	
			$use_session = 0;
		}
	}
	/**
	 * Save search params in $_SESSION 
	 */
	foreach ($search_par as $key=>$value) {
		$_SESSION["power_searchr_pars"][$key] = $value;
	}
	
	if ($use_session == 0) {
		switch ($par) {
			case "new_members":				
				$strSQL = "SELECT DISTINCT ra.id ".
						  "FROM ".USERS_TABLE." u, ".RENT_ADS_TABLE." ra ".
						  "WHERE ra.id_user=u.id AND ra.datenow > (now() - interval 7 day) ".
						  "AND u.guest_user='0' AND u.id != '".$user[0]."' AND u.status='1' AND u.active='1' AND ra.status='1' ".
						  "ORDER BY ra.id";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0) {
					$i = 0;
					while(!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						$id_arr[$i] = $row["id"];
						$_SESSION["power_searchr_id_arr"][$i] = $row["id"];
						$i++;
						$rs->MoveNext();
					}
				}
				break;
			case "by_nick":
				$nick = strip_tags(trim($_POST["nick"]));
				$strSQL = "SELECT DISTINCT ra.id ".
						  "FROM ".USERS_TABLE." u, ".RENT_ADS_TABLE." ra ".
						  "WHERE ra.id_user=u.id AND u.fname LIKE '".$nick."' ".
						  "AND u.guest_user='0' AND u.id != '".$user[0]."' AND u.status='1' AND u.active='1' AND ra.status='1' ".
						  "ORDER BY ra.id";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0) {
					$i = 0;
					while(!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						$id_arr[$i] = $row["id"];
						$_SESSION["power_searchr_id_arr"][$i] = $row["id"];
						$i++;
						$rs->MoveNext();
					}
				}
				break;
			default:
				$par = "search";
				unset($_SESSION["power_searchr_id_arr"]);

				$location_table = "";
				$location_str = "";
				if ($search_par["country"]) {
					$location_table = " ".USERS_RENT_LOCATION_TABLE." rl, ";
					$location_str = " AND rl.id_ad=ra.id AND rl.id_country='".$search_par["country"]."' ";
				}
				if ($search_par["region"]) {
					$location_str .= " AND rl.id_region='".$search_par["region"]."' ";
				}
				if ($search_par["city"]) {
					$location_str .= " AND rl.id_city='".$search_par["city"]."' ";
				}

				/**
				 * дл€ объ€влений типа $search_par["choise"] == 2 || $search_par["choise"] == 4 (сдаю в аренду||продаю)
				 * строки запроса со значени€ми полей типа от-до, другие (т.к. фиксированна€
				 * значение переменной хранитс€ в столбце с названием min_название_переменной)
				 */
				$payment_str = "";
				$payment_table = " ".USERS_RENT_PAYS_TABLE." rp, ";

				if ($search_par["choise"] == 1 || $search_par["choise"] == 3) {

					if ($search_par["min_payment"] > 0) {
						$payment_str .= " AND rp.min_payment >= '".$search_par["min_payment"]."' ";
					}
					if ($search_par["max_payment"] > 0) {
						$payment_str .= " AND rp.max_payment <= '".$search_par["max_payment"]."' ";
					}

					$payment_str .= " AND rp.auction = '".$search_par["auction"]."'";

					if ($search_par["min_deposit"] > 0) {
						$payment_str .= " AND rp.min_deposit >= '".$search_par["min_deposit"]."' ";
					}
					if ($search_par["max_deposit"] > 0) {
						$payment_str .= " AND rp.max_deposit <= '".$search_par["max_deposit"]."' ";
					}
					if ($search_par["min_year_build"] > 0) {
						$payment_str .= " AND rp.min_year_build >= '".$search_par["min_year_build"]."' ";
					}
					if ($search_par["max_year_build"] > 0) {
						$payment_str .= " AND rp.max_year_build <= '".$search_par["max_year_build"]."' ";
					}
					if ($search_par["min_live_square"] > 0) {
						$payment_str .= " AND rp.min_live_square >= '".$search_par["min_live_square"]."' ";
					}
					if ($search_par["max_live_square"] > 0) {
						$payment_str .= " AND rp.max_live_square <= '".$search_par["max_live_square"]."' ";
					}
					if ($search_par["min_total_square"] > 0) {
						$payment_str .= " AND rp.min_total_square >= '".$search_par["min_total_square"]."' ";
					}
					if ($search_par["max_total_square"] > 0) {
						$payment_str .= " AND rp.max_total_square <= '".$search_par["max_total_square"]."' ";
					}
					if ($search_par["min_land_square"] > 0) {
						$payment_str .= " AND rp.min_land_square >= '".$search_par["min_land_square"]."' ";
					}
					if ($search_par["max_land_square"] > 0) {
						$payment_str .= " AND rp.max_land_square <= '".$search_par["max_land_square"]."' ";
					}
					if ($search_par["min_floor"] > 0) {
						$payment_str .= " AND rp.min_floor >= '".$search_par["min_floor"]."' ";
					}
					if ($search_par["max_floor"] > 0) {
						$payment_str .= " AND rp.max_floor <= '".$search_par["max_floor"]."' ";
					}

				} elseif ( $search_par["choise"] == 2 || $search_par["choise"] == 4 ) {
					/**
					 * величины столбцов таблицы с именами max_* всегда равны 0
					 */
					if ($search_par["min_payment"] > 0) {
						$payment_str .= " AND rp.min_payment >= '".$search_par["min_payment"]."'";
					}
					if ($search_par["max_payment"] > 0) {
						$payment_str .= " AND rp.min_payment <= '".$search_par["max_payment"]."'";
					}

					$payment_str .= " AND rp.auction = '".$search_par["auction"]."'";

					if ($search_par["min_deposit"] > 0) {
						$payment_str .= " AND rp.min_deposit >= '".$search_par["min_deposit"]."'";
					}
					if ($search_par["max_deposit"] > 0) {
						$payment_str .= " AND rp.min_deposit <= '".$search_par["max_deposit"]."'";
					}
					if ($search_par["min_year_build"] > 0) {
						$payment_str .= " AND rp.min_year_build >= '".$search_par["min_year_build"]."'";
					}
					if ($search_par["max_year_build"] > 0) {
						$payment_str .= " AND rp.min_year_build <= '".$search_par["max_year_build"]."'";
					}
					if ($search_par["min_live_square"] > 0) {
						$payment_str .= " AND rp.min_live_square >= '".$search_par["min_live_square"]."'";
					}
					if ($search_par["max_live_square"] > 0) {
						$payment_str .= " AND rp.min_live_square <= '".$search_par["max_live_square"]."'";
					}
					if ($search_par["min_total_square"] > 0) {
						$payment_str .= " AND rp.min_total_square >= '".$search_par["min_total_square"]."'";
					}
					if ($search_par["max_total_square"] > 0) {
						$payment_str .= " AND rp.min_total_square <= '".$search_par["max_total_square"]."'";
					}
					if ($search_par["min_land_square"] > 0) {
						$payment_str .= " AND rp.min_land_square >= '".$search_par["min_land_square"]."'";
					}
					if ($search_par["max_land_square"] > 0) {
						$payment_str .= " AND rp.min_land_square <= '".$search_par["max_land_square"]."'";
					}
					if ($search_par["min_floor"] > 0) {
						$payment_str .= " AND rp.min_floor >= '".$search_par["min_floor"]."'";
					}
					if ($search_par["max_floor"] > 0) {
						$payment_str .= " AND rp.min_floor <= '".$search_par["max_floor"]."'";
					}
				}

				if ($search_par["floor_num"] > 0) {
					//not more than
					$payment_str .= " AND rp.floor_num <= '".$search_par["floor_num"]."'";
				}
				if ($search_par["subway_min"] > 0) {
					//not more than
					$payment_str .= " AND rp.subway_min <= '".$search_par["subway_min"]."'";
				}
				$payment_str .= (strlen($payment_str) > 0) ? " AND rp.id_ad=ra.id " : "";

				$movedate_str = "";
				if ($search_par["use_movedate"] == 1 && $search_par["movedate"]) {
					$movedate_str = " AND ra.movedate <= '".$search_par["movedate"]."'";
				}

				$upload_table = "";
				$upload_str = "";
				
				if ($search_par["with_video"] > 0) {
					$pr = "v";
					$upload_str .= " AND (ru$pr.upload_path<>'' AND ru$pr.upload_type='v' AND ru$pr.id_ad=ra.id AND ru$pr.status='1' AND ru$pr.admin_approve='1' ) ";
					$upload_table .= " ".USERS_RENT_UPLOADS_TABLE." ru$pr, ";
				}

				$spr_str = "";
				$spr_table = "";
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						foreach ( $_REQUEST["spr_".$arr["key"]] as $id_key=>$id_subspr) {
							$subspr_name = "spr_".$arr["key"].$id_key;
							if (isset($_REQUEST[$arr["key"]][$id_key]))	{						
								$user_value[$arr["key"]] = implode(",", $_REQUEST[$arr["key"]][$id_key]);				
								$spr_str .= " AND ($subspr_name.id_spr='$id_subspr' AND $subspr_name.id_value IN (".$user_value[$arr["key"]].")) ";
								$spr_table .= " LEFT JOIN ".$arr["spr_user_table"]." $subspr_name ON $subspr_name.id_ad=ra.id ";
							}
						}
					}
				}

				$where_str = " AND u.guest_user='0' AND u.id != '".$user[0]."' AND u.status='1' AND u.active='1' AND ra.status='1' ";

				$strSQL = "SELECT DISTINCT ra.id ".
						  "FROM ".USERS_TABLE." u, ".$location_table.$upload_table.
						  $payment_table." ".RENT_ADS_TABLE." ra $spr_table ".
						  "WHERE ra.type='".$search_par["choise"]."' AND ra.id_user=u.id ".$where_str.
						  $location_str.$payment_str.$movedate_str.$upload_str.$spr_str.
						  "GROUP BY ra.id ORDER BY ra.id";

				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0) {
					while(!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						$id_arr[] = $row["id"];
						$rs->MoveNext();
					}
				}
				$_SESSION["power_searchr_id_arr"] = $id_arr;

				break;
		}
	}
	$search_par["with_photo"] = (isset($_REQUEST["with_photo"])) ? intval($_REQUEST["with_photo"]) : 0;
	if ($search_par["with_photo"] > 0) {
		$strSQL_photo = "SELECT DISTINCT id_ad FROM ".USERS_RENT_UPLOADS_TABLE." WHERE upload_path<>'' AND upload_type='f' AND status='1' AND admin_approve='1'";
		$rs_photo = $dbconn->Execute($strSQL_photo);
		while (!$rs_photo->EOF){
			$with_photo_arr[] = $rs_photo->fields[0];
			$rs_photo->MoveNext();
		}		
	}

	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : 0;
	$smarty->assign("sorter", $sorter);
	$sorter_order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? $_REQUEST["order"] : 1;

	$param = "&sel=".$par."&with_photo=".$search_par["with_photo"]."&amp;";
	$order_link = "&sel=".$par."&with_photo=".$search_par["with_photo"]."&page=".$page;
	$search_size = sizeof($id_arr);

	getSearchArr($id_arr, $file_name, $page, $param, $order_link, $sorter, $sorter_order, $par, isset($search_par["region"]) ? $search_par["region"] : "", isset($search_par["choise"]) ? $search_par["choise"] : "", isset($with_photo_arr) ? $with_photo_arr : "");

	$smarty->assign("file_name", $file_name);
	$smarty->assign("search_size", $search_size);
	$smarty->display(TrimSlash($config["index_theme_path"])."/power_searchr_table.tpl");
	exit;
}

?>