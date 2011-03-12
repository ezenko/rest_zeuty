<?php
/**
* Users homepage
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.5 $ $Date: 2008/10/31 12:57:09 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_auth.php";
include "./include/functions_common.php";
include "./include/class.lang.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";
include "./include/class.calendar_event.php";

$user = auth_index_user();


if ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} elseif ($user[0] && $user[3]!=1) {
	if (isset($_GET["from"]) && !empty($_GET["from"])) {
		switch ($_GET["from"]) {
			case "rental":
				echo "<script>location.href='".$config["site_root"]."/rentals.php?sel=add_rent'</script>";	break;
			case "subscribe":
				echo "<script>location.href='".$config["site_root"]."/account.php'</script>";	break;
			case "mailto":
				echo "<script>location.href='".$config["site_root"]."/mailbox.php'</script>";	break;
			case "sresults":
				$var_2 = intval($_GET["var_2"]);
				$var_3 = intval($_GET["var_3"]);
				echo "<script>location.href='".$config["site_root"]."/rentals.php?sel=add_rent&amp;from=sresults&amp;var_2=".$var_2."&amp;var_3=".$var_3."'</script>";	break;
			default :
				echo "<script>location.href='".$config["site_root"]."/homepage.php'</script>";	break;
		}
		exit;
	}
	$multi_lang = new MultiLang($config, $dbconn);

	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	switch ($sel) {
		case "viewed_my":			ListResults("viewed", "my");		break;
		case "viewed_their":		ListResults("viewed", "their");		break;
		case "hotlisted_me":		ListResults("hotlisted", "me");		break;
		case "hotlisted_them":		ListResults("hotlisted", "them");	break;
		case "inter_me":			ListResults("inter", "me");			break;
		case "inter_them":			ListResults("inter", "them");		break;
		case "blacklisted_me":		ListResults("blacklisted", "me");	break;
		case "blacklisted_them":	ListResults("blacklisted", "them");	break;
		case "match_their":			MatchResults("match", "their");		break;
		case "match_my":			MatchResults("match", "my");		break;
		case "match":				MatchResults("match", $_GET["par"]);break;
		case "visited_ad":			ListResults("visited", "ad");		break;
		case "after_confirm":		HomePage(1);						break;
		default: 					HomePage(0);						break;
	}
} else {
	AlertPage();
}


function HomePage($par){
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if ($par == 1) {
		$smarty->assign("success_confirm_user", GetErrors("success_confirm_user"));
	}

	if ($user[8]=='1') {
		$smarty->assign("active_user", 1);
	} else {
		$smarty->assign("active_user", 0);
	}
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "homepage.php";

	IndexHomePage('homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');

	unset($_SESSION["quick_search_arr"]);

	$left_links = GetLeftLinks($file_name);
	$smarty->assign("left_links", $left_links);
	$smarty->assign("file_name", $file_name);

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);

				
	$day = (isset($search_pref["move_day"]) && $search_pref["move_day"]) ? $search_pref["move_day"] : date("d")+1;
	$month = (isset($search_pref["move_month"]) && $search_pref["move_month"]) ? $search_pref["move_month"] : date("m");

	// Get parametres of showing ads for unregistered users from TABLE_SHOW_ADS_AREA
	$area_parametres = GetOrderAds("homepage",1);
	$smarty->assign("last_ads",1);
	if ($area_parametres["show_type"] != "off")	{
	GetLastAds("last_ads_num_at_page", 1, "?", $area_parametres["sorter"], $area_parametres["sorter_order"], "",
				 $area_parametres["show_type"], $area_parametres["ads_number"], $file_name);
	}
	$smarty->assign("area_parametres", $area_parametres);
	$smarty->assign("day", GetDaySelect($day));
	$smarty->assign("month", GetMonthSelect($month));

	$smarty->assign("search_pref", $search_pref );
	$smarty->assign("data", $data );

	$smarty->assign("from_file", "homepage");
	$smarty->display(TrimSlash($config["index_theme_path"])."/homepage_table.tpl");
	exit;
}

function ListResults($section='', $par='') {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "homepage.php";

	IndexHomePage('homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);

	$left_links = GetLeftLinks($file_name);
	$smarty->assign("left_links", $left_links);

	$smarty->assign("submenu", $section.$par);

	switch ($section) {
		case "viewed":
			$add_str = " , DATE_FORMAT(pvt.last_visit_date, '".$config["date_format"]."') as date_added ";
			switch ($par) {
				case "my":
					$strSQL = " SELECT id_visiter AS u_id FROM ".PROFILE_VISIT_TABLE." WHERE id_user='".intval($user[0])."' AND id_visiter<>'".intval($user[0])."' AND id_visiter<>'1' AND id_visiter<>'2' ";
					$table_str = " LEFT JOIN ".PROFILE_VISIT_TABLE." pvt ON (pvt.id_user='".$user[0]."' AND pvt.id_visiter=a.id) ";
					break;
				case "their":
					$strSQL = " SELECT id_user AS u_id FROM ".PROFILE_VISIT_TABLE." WHERE id_visiter='".intval($user[0])."' AND id_user<>'".intval($user[0])."'  AND id_visiter<>'1' AND id_visiter<>'2' ";
					$table_str = " LEFT JOIN ".PROFILE_VISIT_TABLE." pvt ON (a.id=pvt.id_user AND pvt.id_visiter='".$user[0]."') ";
					break;
			}
			break;
		case "visited":
			$add_str = " , DATE_FORMAT(rvt.last_visit_date, '".$config["date_format"]."') as date_added ";
			$strSQL = " SELECT id_visiter AS u_id FROM ".RENT_AD_VISIT_TABLE." WHERE id_ad='".intval($_GET["id_ad"])."' AND id_visiter<>'".intval($user[0])."' AND id_visiter<>'1' AND id_visiter<>'2' ";
			$table_str = " LEFT JOIN ".RENT_AD_VISIT_TABLE." rvt ON (rvt.id_ad='".intval($_GET["id_ad"])."' AND rvt.id_visiter=a.id) ";
			$add_to_order = "&amp;id_ad=".intval($_GET["id_ad"]);
			break;
		case "hotlisted" :
			$add_str = " , DATE_FORMAT(ht.datenow, '".$config["date_format"]."') as date_added ";
			switch ($par) {
				case "me":
					$strSQL = "	SELECT id_user AS u_id FROM ".HOTLIST_TABLE." WHERE id_friend='".$user[0]."'";
					$table_str = " LEFT JOIN ".HOTLIST_TABLE." ht ON (ht.id_friend='".$user[0]."' AND ht.id_user=a.id) ";
					break;
				case "them":
					echo "<script>document.location.href='hotlist.php';</script>";
					//					$strSQL = "	SELECT id_friend AS u_id FROM ".HOTLIST_TABLE." WHERE id_user='".$user[0]."'";
					//					$table_str = " LEFT JOIN ".HOTLIST_TABLE." ht ON (a.id=ht.id_friend AND ht.id_user='".$user[0]."') ";
					break;
			}
			break;
		case "blacklisted" :
			$add_str = " , DATE_FORMAT(ht.datenow, '".$config["date_format"]."') as date_added ";
			switch ($par) {
				case "me":
					$strSQL = "	SELECT id_user AS u_id FROM ".BLACKLIST_TABLE." WHERE id_enemy='".$user[0]."'";
					$table_str = " LEFT JOIN ".BLACKLIST_TABLE." ht ON (ht.id_enemy='".$user[0]."' AND ht.id_user=a.id) ";
					break;
				case "them":
					echo "<script>document.location.href='blacklist.php';</script>";
					//					$strSQL = "	SELECT id_enemy AS u_id FROM ".BLACKLIST_TABLE." WHERE id_user='".$user[0]."'";
					//					$table_str = " LEFT JOIN ".BLACKLIST_TABLE." ht ON (a.id=ht.id_enemy AND ht.id_user='".$user[0]."') ";
					break;
			}
			break;
		case "inter":			
			switch ($par) {
				case "me":
					$strSQL = "SELECT id_user AS u_id FROM ".INTERESTS_TABLE." WHERE id_interest_user='".$user[0]."'";
					$field_1 = " id_user ";
					$field_2 = " id_interest_user ";
					break;
				case "them":
					$strSQL = "SELECT id_interest_user AS u_id FROM ".INTERESTS_TABLE." WHERE id_user='".$user[0]."'";
					$field_1 = " id_interest_user ";
					$field_2 = " id_user ";
					break;
			}
			break;
	}	
	$smarty->assign("add_to_lang", "&amp;sel=".$section."_".$par.$add_to_order);

	$rs = $dbconn->Execute($strSQL);
	if ( $rs->fields[0]>0 ) {
		$smarty->assign("empty_result", 0);
		$i = 0;
		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			$id_arr[$i] = $row["u_id"];
			$rs->MoveNext();
			$i++;
		}
		$users_numpage = GetSiteSettings("users_num_page");
		$num_users = sizeof($id_arr);
		$lim_min = ($page-1)*$users_numpage;
		$lim_max = $users_numpage;
		$limit_str = " limit ".$lim_min.", ".$lim_max;

		$sorter = intval($_GET["sorter"]);
		$sorter_order = intval($_GET["order"]);
		$smarty->assign("sorter", $sorter);

		$sort_arr = getRealtySortOrder($sorter, $sorter_order, "user");
		$sorter_str = " GROUP by a.id ORDER BY ".$sort_arr["sorter_str"].$sort_arr["sorter_order"];

		$sorter_tolink = $sort_arr["sorter_tolink"];
		$sorter_topage = $sort_arr["sorter_topage"];
		$smarty->assign("order_icon", $sort_arr["order_icon"]);

		$where_str = "WHERE a.id IN (".implode(",", $id_arr).")";

		$strSQL = "	SELECT DISTINCT a.id, a.fname, DATE_FORMAT(a.date_last_seen,'".$config["date_format"]."')  as date_last_login, a.user_type, e.id_user as session ".$add_str."
					FROM ".USERS_TABLE." a
					LEFT JOIN ".ACTIVE_SESSIONS_TABLE." e on a.id=e.id_user
					".$table_str."
					".$where_str." and a.status='1' AND a.active='1' ".$sorter_str.$limit_str;
		//echo $strSQL;
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$users_list[$i]["id"] = $row["id"];
			$users_list[$i]["login"] = $row["fname"];
			$users_list[$i]["date_last_login"] = $row["date_last_login"];
			$users_list[$i]["status"] = $row["session"]? "online": "offline";
			$users_list[$i]["number"] = ($page-1)*$users_numpage+($i+1);
			$users_list[$i]["user_type"] = $row["user_type"];

			$users_list[$i]["date_added"] = $row["date_added"];

			$suffix = "&id=".$users_list[$i]["id"];

			$users_list[$i]["contact_link"] = "./contact.php?sel=fs".$suffix;

			if ($section == "inter") {
				$strSQL_i = " SELECT id_interest_ad, DATE_FORMAT(interest_date ,'".$config["date_format"]."') FROM ".INTERESTS_TABLE." WHERE ".$field_1."='".$users_list[$i]["id"]."' AND ".$field_2."='".$user[0]."' ";
				$rs_i = $dbconn->Execute($strSQL_i);
				$j = 0;
				while(!$rs_i->EOF){
					$users_list[$i]["id_interest_ad"][$j] = $rs_i->fields[0];
					$users_list[$i]["interest_date"][$j] = $rs_i->fields[1];
					$rs_i->MoveNext();
					$j++;
				}
			}
			if ($row["user_type"] == 2){
				$strSQL_company = " SELECT company_name FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$users_list[$i]["id"]."' ";
				$rs_name = $dbconn->Execute($strSQL_company);
				if ($rs_name->fields[0]){
					$users_list[$i]["company_name"] = stripslashes($rs_name->fields[0]);
				}
			}
			$used_references = array("gender");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserGenderIds($arr["spr_user_table"], $users_list[$i]["id"], 0, $arr["val_table"]);
					$users_list[$i][$arr["key"]] = $name;
				}
			}
			$gender_info = getDefaultUserIcon($users_list[$i]["user_type"], $users_list[$i]["gender"]);
			$icon_name =  $gender_info["icon_name"];

			$str_query = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND status='1' ";
			$rs_ad = $dbconn->Execute($str_query);
			if ($rs_ad->fields[0]>0){
				$users_list[$i]["view_ad_link"] = $config["server"].$config["site_root"]."/viewprofile.php?sel=more_ad&amp;id_user=".$users_list[$i]["id"]."&amp;section=rent";
			}
			$users_list[$i]["pict_path"] = "";
			if ($row["user_type"]==2) {
				$strSQL_logo = "SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND admin_approve='1'";
				$rs_logo = $dbconn->Execute($strSQL_logo);
				if ( ($rs_logo->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/".$rs_logo->fields[0])) ){
					$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_logo->fields[0];
				}
			} else {
				$strSQL_pict = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND status='1' AND admin_approve='1'";
				$rs_pict = $dbconn->Execute($strSQL_pict);
				if ( ($rs_pict->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/thumb_".$rs_pict->fields[0])) ){
					$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/thumb_".$rs_pict->fields[0];
				}
			}

			if ($users_list[$i]["pict_path"] == ""){
				$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$icon_name;
			}

			$rs->MoveNext();
			$i++;
		}

		$param = "sorter=".$sorter."&amp;order=".$sorter_topage."&amp;sel=".$section."_".$par."&amp;section=".$_GET["section"]."&amp;".$add_to_order;
		$smarty->assign("order_link", "&amp;order=".$sorter_topage."&amp;sel=".$section."_".$par."&amp;section=".$_GET["section"]."&amp;page=".$page.$add_to_order);
		$smarty->assign("order_active_link", "&amp;order=".$sorter_tolink."&amp;sel=".$section."_".$par."&amp;section=".$_GET["section"]."&amp;page=".$page.$add_to_order);

		$smarty->assign("links", GetLinkArray($num_users, $page, $file_name."?".$param, $lim_max));
		$smarty->assign("users_list", $users_list);
	} else {
		$smarty->assign("empty_result", 1);
	}

	$smarty->assign("section", $section);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/homepage_users_table.tpl");
	exit;
}

function MatchResults($section, $par) {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $values_arr, $REFERENCES;

	$smarty->assign("add_to_lang", "&amp;sel=".$section."_".$par."&amp;section=".$_GET["section"]);
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "homepage.php";

	IndexHomePage('homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');

	$smarty->assign("submenu", $section.$par.$_GET["section"]);

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);

	$left_links = GetLeftLinks($file_name);
	$smarty->assign("left_links", $left_links);

	switch ($section) {
		case "match":
			switch ($_GET["section"]) {
				case "rent":
					$smarty->assign("rentals", 1);
					$param = $file_name."?sel=".$section."_".$par."&amp;section=".$_GET["section"]."&amp;";
					$ads = GetUserAds($file_name, $param);

					if (count($ads) > 0 ){
						$smarty->assign("ads",$ads);
					} else {
						$smarty->assign("no_user_ad", 1);
					}

					break;
				case "rental_match":
					$used_references = array("info", "period", "realty_type", "description");
					$id_ad = intval($_GET["id_ad"]);
					$par = $_GET["par"];

					$strSQL = "	SELECT type, movedate, with_photo, with_video
								FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[0]."' AND id='".$id_ad."' ";
					$rs = $dbconn->Execute($strSQL);
					if ($rs->fields[0] == 0) {
						$smarty->assign("no_user_ad", 1);
					} else {
						$smarty->assign("no_user_ad", 0);
					}
					$row = $rs->GetRowAssoc(false);

					$type = $row["type"];
					$movedate = $row["movedate"];
					$with_photo = $row["with_photo"];
					$with_video = $row["with_video"];

					if ($type == 1) {
						$choise = 2;
					} elseif ($type == 2) {
						$choise = 1;
					} elseif ($type == 3) {
						$choise = 4;
					} elseif ($type == 4) {
						$choise = 3;
					}

					$strSQL = "	SELECT id_country, id_region, id_city FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
					$rs = $dbconn->Execute($strSQL);
					$row = $rs->GetRowAssoc(false);

					$country = $row["id_country"];
					$region = $row["id_region"];
					$city = $row["id_city"];
					$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
					$strSQL_payment = " SELECT min_payment, max_payment, auction, min_deposit, max_deposit,
						min_live_square, max_live_square, min_total_square, max_total_square,
						min_land_square, max_land_square, min_floor,  max_floor, floor_num, subway_min,
						min_year_build, max_year_build
						FROM ".USERS_RENT_PAYS_TABLE."
						WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";

					$rs_payment = $dbconn->Execute($strSQL_payment);
					$row_payment = $rs_payment->GetRowAssoc(false);

					$min_payment = ($row_payment["min_payment"]);
					$max_payment = ($row_payment["max_payment"]);
					$auction = $row_payment["auction"];
					$min_deposit = ($row_payment["min_deposit"]);
					$max_deposit = ($row_payment["max_deposit"]);
					$min_year_build = $row_payment["min_year_build"];
					$max_year_build = $row_payment["max_year_build"];
					$min_live_square = $row_payment["min_live_square"];
					$max_live_square = $row_payment["max_live_square"];
					$min_total_square = $row_payment["min_total_square"];
					$max_total_square = $row_payment["max_total_square"];
					$min_land_square = $row_payment["min_land_square"];
					$max_land_square = $row_payment["max_land_square"];
					$min_floor = $row_payment["min_floor"];
					$max_floor = $row_payment["max_floor"];
					$floor_num = $row_payment["floor_num"];
					$subway_min = $row_payment["subway_min"];

					$location_str = "";
					if ($country){
						$location_table = " ".USERS_RENT_LOCATION_TABLE." rl, ";
						$location_str .= " AND rl.id_ad=ra.id AND rl.id_country='".$country."' ";
					}
					if ($region){
						$location_str .= " AND rl.id_region='".$region."' ";
					}
					if ($city){
						$location_str .= " AND rl.id_city='".$city."' ";
					}

					$payment_str = "";
					$payment_table = " ".USERS_RENT_PAYS_TABLE." rp, ";

					if ($type == 1 || $type == 3) {

						if ($min_payment > 0) {
							$payment_str .= " AND (rp.min_payment >= '".$min_payment."' || rp.min_payment = '0')";
						}
						if ($max_payment > 0) {
							$payment_str .= " AND (rp.min_payment <= '".$max_payment."' || rp.min_payment = '0')";
						}

						//$payment_str .= " AND rp.auction = '".$auction."'";

						if ($min_deposit > 0) {
							$payment_str .= " AND (rp.min_deposit >= '".$min_deposit."' || rp.min_deposit = '0')";
						}
						if ($max_deposit > 0) {
							$payment_str .= " AND (rp.min_deposit <= '".$max_deposit."' || rp.min_deposit = '0')";
						}
						if ($min_year_build > 0) {
							$payment_str .= " AND (rp.min_year_build >= '".$min_year_build."' || rp.min_year_build = '0')";
						}
						if ($max_year_build > 0) {
							$payment_str .= " AND (rp.min_year_build <= '".$max_year_build."' || rp.min_year_build = '0')";
						}
						if ($min_live_square > 0) {
							$payment_str .= " AND (rp.min_live_square >= '".$min_live_square."' || rp.min_live_square = '0')";
						}
						if ($max_live_square > 0) {
							$payment_str .= " AND (rp.min_live_square <= '".$max_live_square."' || rp.min_live_square = '0')";
						}
						if ($min_total_square > 0) {
							$payment_str .= " AND (rp.min_total_square >= '".$min_total_square."' || rp.min_total_square = '0')";
						}
						if ($max_total_square > 0) {
							$payment_str .= " AND (rp.min_total_square <= '".$max_total_square."' || rp.min_total_square = '0')";
						}
						if ($min_land_square > 0) {
							$payment_str .= " AND (rp.min_land_square >= '".$min_land_square."' || rp.min_land_square = '0')";
						}
						if ($max_land_square > 0) {
							$payment_str .= " AND (rp.min_land_square <= '".$max_land_square."' || rp.min_land_square = '0')";
						}
						if ($min_floor > 0) {
							$payment_str .= " AND (rp.min_floor >= '".$min_floor."' || rp.min_floor = '0')";
						}
						if ($max_floor > 0) {
							$payment_str .= " AND (rp.min_floor <= '".$max_floor."' || rp.min_floor = '0')";
						}

					} elseif ( $type == 2 || $type == 4 ) {
						/**
						 * - величины столбцов таблицы с именами min_* - это фиксированные значени€ полей
						 * дл€ объ€влений типа сдам в аренду/продам
						 * - величины столбцов таблицы с именами max_* всегда равны 0
						 */
						if ($min_payment > 0) {
							$payment_str .= " AND ((rp.min_payment <= '".$min_payment."' || rp.min_payment = '0') AND (rp.max_payment >= '".$min_payment."' || rp.max_payment = '0'))";
						}

						//$payment_str .= " AND rp.auction = '".$auction."'";

						if ($min_deposit > 0) {
							$payment_str .= " AND ((rp.min_deposit <= '".$min_deposit."' || rp.min_deposit = '0') AND (rp.max_deposit >= '".$min_deposit."' || rp.max_deposit = '0'))";
						}
						if ($min_year_build > 0) {
							$payment_str .= " AND ((rp.min_year_build <= '".$min_year_build."' || rp.min_year_build = '0') AND (rp.max_year_build >= '".$min_year_build."' || rp.max_year_build = '0'))";
						}
						if ($min_live_square > 0) {
							$payment_str .= " AND ((rp.min_live_square <= '".$min_live_square."' || rp.min_live_square = '0') AND (rp.max_live_square >= '".$min_live_square."' || rp.max_live_square = '0'))";
						}
						if ($min_total_square > 0) {
							$payment_str .= " AND ((rp.min_total_square <= '".$min_total_square."' || rp.min_total_square = '0') AND (rp.max_total_square >= '".$min_total_square."' || rp.max_total_square = '0'))";
						}
						if ($min_land_square > 0) {
							$payment_str .= " AND ((rp.min_land_square <= '".$min_land_square."' || rp.min_land_square = '0') AND (rp.max_land_square >= '".$min_land_square."' || rp.max_land_square = '0'))";
						}
						if ($min_floor > 0) {
							$payment_str .= " AND ((rp.min_floor <= '".$min_floor."' || rp.min_floor = '0') AND (rp.max_floor >= '".$min_floor."' || rp.max_floor = '0'))";
						}
					}

					if ($floor_num > 0) {
						if ($type == 2 || $type == 4) {
							$payment_str .= " AND (rp.floor_num >= '".$floor_num."'";
						} elseif ($type == 1 || $type == 3) {
							$payment_str .= " AND (rp.floor_num <= '".$floor_num."'";
						}
						$payment_str .= " || rp.floor_num = '1')";
					}
					if ($subway_min > 0) {
						if ($type == 2 || $type == 4) {
							$payment_str .= " AND (rp.subway_min >= '".$subway_min."'";
						} elseif ($type == 1 || $type == 3) {
							$payment_str .= " AND (rp.subway_min <= '".$subway_min."'";
						}
						$payment_str .= " || rp.subway_min = '0')";
					}
					$payment_str .= (strlen($payment_str) > 0) ? " AND rp.id_ad=ra.id " : "";

					/*if ($movedate) {
						$movedate_str = " AND (ra.movedate >= '".$movedate."' || ra.movedate = '0000-00-00 00:00:00')";
					}*/
					$movedate_str = "";
					$upload_table = "";
					$upload_str = "";
					if ($type == 1 || $type == 3) {
						if ($with_photo > 0) {
							$pr = "f";
							$upload_str .= " AND (ru$pr.upload_path<>'' AND ru$pr.upload_type='f' AND ru$pr.id_ad=ra.id AND ru$pr.status='1' AND ru$pr.admin_approve='1' ) ";
							$upload_table .= " ".USERS_RENT_UPLOADS_TABLE." ru$pr, ";
						}
						if ($with_video > 0) {
							$pr = "v";
							$upload_str .= " AND (ru$pr.upload_path<>'' AND ru$pr.upload_type='v' AND ru$pr.id_ad=ra.id AND ru$pr.status='1' AND ru$pr.admin_approve='1' ) ";
							$upload_table .= " ".USERS_RENT_UPLOADS_TABLE." ru$pr, ";
						}
					}

					$user_val = array();
					foreach ($REFERENCES as $arr) {
						if (in_array($arr["key"], $used_references)) {
							$user_val[$arr["key"]] = GetUserSprArray($arr["spr_user_table"], $id_ad);
						}
					}
					$spr_str = "";
					$spr_table = "";
					foreach ($REFERENCES as $arr) {
						if (in_array($arr["key"], $used_references)) {
							foreach ( $user_val[$arr["key"]] as $id_key=>$id_subspr) {
								$subspr_name = "spr_".$arr["key"].$id_key;

								$user_value[$arr["key"]] = implode(",", $user_val[$arr["key"]][$id_key]);

								if ($user_value[$arr["key"]] != "") {
									$spr_str .= " AND ($subspr_name.id_spr='$id_key' AND $subspr_name.id_value IN (".$user_value[$arr["key"]].")) ";
									$spr_table .= " LEFT JOIN ".$arr["spr_user_table"]." $subspr_name ON $subspr_name.id_ad=ra.id ";
								}
							}
						}
					}

					$where_str = " AND u.guest_user='0' AND u.id != '".$user[0]."' AND u.status='1' AND u.active='1' AND ra.status='1' ";

					$strSQL = "SELECT DISTINCT ra.id ".
							  "FROM ".USERS_TABLE." u, ".$location_table.
							  $upload_table.$payment_table." ".RENT_ADS_TABLE." ra ".$spr_table.
							  "WHERE ra.type='".$choise."' AND ra.id_user=u.id ".$where_str.
							  $location_str.$payment_str.$movedate_str.$upload_str.$spr_str.
							  "GROUP BY ra.id ORDER BY ra.id";
							  
					$rs = $dbconn->Execute($strSQL);
					$id_arr = array();
					if ($rs->fields[0]>0){
						while(!$rs->EOF){
							$row = $rs->GetRowAssoc(false);
							$id_arr[] = $row["id"];
							$rs->MoveNext();
						}
					}
					/**
					 * @todo использовать $_SESSION["match_search_id_arr"]  при сортировке или $page > 1
					 */
					$_SESSION["match_search_id_arr"] = $id_arr;

					$sorter = intval($_REQUEST["sorter"]);
					$smarty->assign("sorter", $sorter);
					$sorter_order = intval($_GET["order"]);

					$param = "&sel=match&amp;section=rental_match&amp;id_ad=".$id_ad."&amp;par=".$par."&amp;";
					$order_link = "&sel=match&amp;section=rental_match&amp;id_ad=".$id_ad."&amp;par=".$par."&amp;page=".$page;
					getSearchArr($id_arr, $file_name, $page, $param, $order_link, $sorter, $sorter_order);

					break;

					default:
					break;

			}
			break;
	}
	$smarty->assign("section", $section);
	$smarty->assign("par", $par);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/homepage_users_table.tpl");
	exit;
}

/**
 * Get spr values for, which were set for the listing
 *
 * @param string $table
 * @param integer $id_ad
 * @return mixed - array or boolean
 */
function GetUserSprArray ($table, $id_ad ) {
	global $smarty, $config, $dbconn;
	$strSQL = "SELECT DISTINCT id_spr FROM ".$table." WHERE id_ad='".$id_ad."' ORDER BY id_spr ";
	$rs = $dbconn->Execute($strSQL);
	$result = array();
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			$strSQL_v = "SELECT id_value FROM ".$table." WHERE id_spr='".$row["id_spr"]."' AND id_ad='".$id_ad."'";
			$rs_v = $dbconn->Execute($strSQL_v);
			$j = 0;
			while(!$rs_v->EOF) {
				$row_v = $rs_v->GetRowAssoc(false);
				$result[$row["id_spr"]][$j] = $row_v["id_value"];
				$rs_v->MoveNext();
				$j++;
			}

			$rs->MoveNext();
		}
	}
	return (count($result) > 0) ? $result : false;
}

?>