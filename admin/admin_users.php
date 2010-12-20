<?php
/**
* Users management
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.17 $ $Date: 2009/01/14 14:17:15 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";
if (GetSiteSettings("use_pilot_module_newsletter")) {
	include "../include/functions_newsletter.php";
}
include "../include/functions_xml.php";
include "../include/functions_mail.php";
include "../include/class.lang.php";
include "../include/class.images.php";
include "../include/class.calendar_event.php";
			
$auth = auth_user();

if ( (!($auth[0]>0))  || (!($auth[4]==1))) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if (($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";

$multi_lang = new MultiLang($config, $dbconn);
$lang["users_types"] = GetLangContent("users_types");
$lang["rentals"] = GetLangContent("rentals");

if (isset($_REQUEST["err"]) && !empty($_REQUEST["err"])) {
	GetErrors($_REQUEST["err"]);
}

switch($sel) {
	case "edit_user": 		EditUserForm("edit", $_GET["id_user"]); break;
	case "add": 			EditUserForm("add"); break;
	case "status": 			StatusChange(); break;
	case "active": 			ActiveChange(); break;
	case "access": 			AccessChange(); break;
	case "add_user":		AddNewUser(); break;
	case "save_changes":	SaveUserChanges(); break;
	case "delete_user":		DeleteUser(); break;
	case "user_rent":		UserAds(intval($_GET["id_user"])); break;
	case "upload_view":		UploadView(); break;
	case "plan_view":		UploadPlanView(); break;
	case "logo_view":		UploadLogoView(); break;
	case "upload_delete":	UploadDelete(); break;
	case "del_user_ad":		DeleteUserAd(); break;
	case "user_mail":		UserMail(intval($_GET["id_user"]));break;
	case "users_import":	UsersImport();break;
	case "get_hour_by_date":GetHourByDate();break;
	case "add_money_form":	AddMoneyForm(intval($_REQUEST["user_id"]));break;
	case "add_money":		AddMoney();break;
	default: 				ListUsers();
}

function ListUsers() {
	global $smarty, $dbconn, $config, $lang;

	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_users.php";

	IndexAdminPage('admin_users');
	CreateMenu('admin_lang_menu');
	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;	

	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? trim($_REQUEST["search"]) : "";
	$s_type = (isset($_REQUEST["s_type"]) && intval($_REQUEST["s_type"]) > 0) ? intval($_REQUEST["s_type"]) : 1;
	$s_stat = (isset($_REQUEST["s_stat"]) && !empty($_REQUEST["s_stat"])) ? trim($_REQUEST["s_stat"]) : "";
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : 2;
	$letter = (!isset($_REQUEST["letter"]) || strval($_REQUEST["letter"]) == "*") ? "*" : intval($_REQUEST["letter"]);	
	$order = (isset($_REQUEST["order"]) && intval($_REQUEST["order"]) > 0) ? intval($_REQUEST["order"]) : 1;
	
	// search
	$search_str = "";
	if (strval($search)) {
		$search = strip_tags($search);
		switch($s_type) {
			case "1": $search_str=" AND u.login LIKE '%".$search."%'"; break;
			case "2": $search_str=" AND u.fname LIKE '%".$search."%'"; break;
			case "3": $search_str=" AND u.sname LIKE '%".$search."%'"; break;
			case "4": $search_str=" AND u.email LIKE '%".$search."%'"; break;
		}
	}
	$smarty->assign("search", $search);
	$smarty->assign("s_type", $s_type);

	// letter
	if (strval($letter) != "*") {
		$letter_str = " lower(substring(u.email,1,1)) ='".strtolower(chr($letter))."'";
	} else {
		$letter_str = "";
	}
	$smarty->assign("letter", $letter);

	/// letter link
	$param_letter = $file_name."?sorter=".$sorter."&order=".$order."&letter=";
	$letter_links = LettersLink_eng($param_letter, $letter);
	$smarty->assign("letter_links", $letter_links);

	///////// sorter & order
	switch ($order) {
		case "1":
			$order_str = " ASC";
			$order_new = 2;
			$order_icon = "&darr;";
			break;
		default:
			$order_str = " DESC";
			$order_new = 1;
			$order_icon = "&uarr;";
			break;
	}
	$smarty->assign("order", $order_new);
	$smarty->assign("order_icon", $order_icon);

	$sorter_str = "  ORDER BY ";
	if ($sorter) {
		switch($sorter) {
			//case "1": $sorter_str.=" u.login"; break;
			case "2": $sorter_str.=" u.fname"; break;
			case "3": $sorter_str.=" u.email"; break;
			case "4": $sorter_str.=" u.date_registration"; break;
			case "5": $sorter_str.=" u.date_last_seen"; break;
			case "7": $sorter_str.=" u.active"; break;
			case "8": $sorter_str.=" u.user_type"; break;
			case "9": $sorter_str.=" ads_user $order_str, u.fname $order_str"; break;
			case "10":$sorter_str.="";break;
			case "balance": $sorter_str.=" ua.account $order_str, u.fname $order_str"; break;
		}
		$sorter_str .= ($sorter != 9 && $sorter != "balance") ? $order_str : "";
	} else {
		$sorter_str .= " u.fname";
	}
	$smarty->assign("sorter", $sorter);

	if ($letter_str) {
		$where_str = "where ".$letter_str." ";
	}elseif ($search_str) {
		$where_str = "where u.id>0 ".$search_str." AND u.id != 2";
	} else { 
		$where_str = "where u.id != 2";
	}
	$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u  ".$where_str;
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];
	if ($sorter == 10) {
		$sorter_str = "";
	}

	if ($num_records>0) {
		$rows_num_page = GetSiteSettings('admin_rows_per_page');
		// page
		$lim_min = ($page-1)*$rows_num_page;
		$lim_max = $rows_num_page;
		$limit_str = " limit ".$lim_min.", ".$lim_max;

		$strSQL = "	SELECT DISTINCT 
		 			u.id, u.fname, u.sname, u.status, u.access, u.login, u.email,
					DATE_FORMAT(u.date_last_seen, '".$config["date_format"]."') as date_last_seen,
					DATE_FORMAT(u.date_registration, '".$config["date_format"]."') as date_registration,
					u.root_user, u.guest_user, u.active, u.user_type, ua.account ";
		$strSQL .= "FROM ".USERS_TABLE."  u ";
		//		   "LEFT JOIN ".MAILBOX_USER_LISTS_TABLE." mu ON mu.user_id=u.id ";
		
		$strSQL.= "LEFT JOIN ".BILLING_USER_ACCOUNT_TABLE." ua ON ua.id_user = u.id ";
 		$strSQL .= $where_str." GROUP BY u.id ".$sorter_str.$limit_str;
	
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		if ($rs->RowCount()>0) {
			while(!$rs->EOF) {
				
				$row = $rs->GetRowAssoc(false);
				$user[$i]["number"] = ($page-1)*$rows_num_page+($i+1);
				$user[$i]["id"] = $row["id"];
				$user[$i]["edit_link"] = $file_name."?sel=edit_user&id_user=".$user[$i]["id"];
				$user[$i]["name"] = stripslashes($row["fname"]." ".$row["sname"]);
				$user[$i]["status"] = intval($row["status"]);
				$user[$i]["active"] = intval($row["active"]);
				$user[$i]["access"] = intval($row["access"]);
				$user[$i]["nick"] = $row["login"];
				$user[$i]["email"] = $row["email"];
				$user[$i]["user_type"] = $row["user_type"];
				$user[$i]["date_registration"] = $row["date_registration"];
				$user[$i]["last_login"] = $row["date_last_seen"];
//				$user[$i]["comunicate"] = "./admin_comunicate.php?id=".$row["id"];
				$user[$i]["root_user"] = $row["root_user"]?$row["root_user"]:$row["guest_user"];
				$user[$i]["root"] = $row["root_user"];
				$user[$i]["guest"] = $row["guest_user"];
				$user[$i]["balance"] = $row["account"];
				$user[$i]["payment_link"] = $config["server"].$config["site_root"]."/admin/admin_payment.php?sel=user_history&id_user={$user[$i]["id"]}";
				$user[$i]["spent_link"] = $config["server"].$config["site_root"]."/admin/admin_payment.php?sel=list_spended&search={$user[$i]["email"]}&s_type=4";
				$strSQL2 = "SELECT DISTINCT user_id AS mailbox_user FROM ".MAILBOX_USER_LISTS_TABLE." WHERE user_id = '".$user[$i]["id"]."'" ;				
				$rs2 = $dbconn->Execute($strSQL2);
				if ($rs2->fields[0] > 0) {
					$user[$i]["mailbox_user"] = 1;
				}

				if ( $user[$i]["root_user"] != 1 ) {
					$user[$i]["del_link"] = "./admin_users.php?sel=delete_user&id_user=".$row["id"];
				}
				//mailbox link
				$user[$i]["mail_link"] = (isset($user[$i]["mailbox_user"])) ? $file_name."?sel=user_mail&amp;id_user=".$user[$i]["id"]." " : "";
				$user[$i]["rent_link"] = "";
				
					//rent link
					$strSQL = "SELECT DISTINCT id FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[$i]["id"]."' ";
					$res = $dbconn->Execute($strSQL);
					if ( $res->RowCount()>0 ) {
						$user[$i]["rent_link"] = $file_name."?sel=user_rent&id_user=".$user[$i]["id"];
						$user[$i]["rent_count"] = $res->RowCount();
					}
				
					//spended
					$strSQL = "SELECT SUM(count_curr) AS spended FROM ".BILLING_SPENDED_TABLE." ".
							  "WHERE id_user='".$user[$i]["id"]."' ";
					$res = $dbconn->Execute($strSQL);
					if ( $res->RowCount()>0 ) {
						$user[$i]["spended"] = $res->fields[0];
					}
				
				$rs->MoveNext();
				$i++;
			}
			if ($sorter == 10) {
				function cmp_desc($a, $b) {
					if ($a["mailbox_user"] == $b["mailbox_user"]) {
				      return 0;
				    }
				    return ($a["mailbox_user"] > $b["mailbox_user"]) ? -1 : 1;
				}
				function cmp_asc($a, $b) {
					if ($a["mailbox_user"] == $b["mailbox_user"]) {
				   	   return 0;
				   	}
				   	return ($a["mailbox_user"] < $b["mailbox_user"]) ? -1 : 1;
				}

				if ($order == "1") {					
					usort($user, "cmp_asc");
				} else {
					usort($user, "cmp_desc");
				}
				$max_number = $lim_max;
				
				$max_number = ($num_records < $max_number) ? $num_records : $max_number;
				
				for ($i=$lim_min; $i<$max_number; $i++ ) {
					$res_user[] = $user[$i];
				}
				$users = $res_user;
			}

			$smarty->assign("user", $user);
			$smarty->assign("page", $page);
			$param = $file_name."?letter=".$letter."&search=".$search."&s_type=".$s_type."&s_stat=".$s_stat."&sorter=".$sorter."&order=".$order."&";
			$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page) );
		}
	}
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_users.tpl");
	exit;
}
function StatusChange() {
	global $smarty, $dbconn, $config, $lang;

	$str_id = " (";
	$i = 0;
	foreach ($_POST["id_user"] as $user_id) {
		$i++;
		if ($i==sizeof($_POST["id_user"])) {
			$str_id .= $user_id." ";
		} else {
			$str_id .= $user_id." , ";
		}
	}
	$str_id .= " )";

	$dbconn->Execute("UPDATE ".USERS_TABLE." SET status='0' WHERE id IN ".$str_id." AND  root_user<>'1' AND guest_user <>'1'");
	$str_status = " (";
	$i = 0;
	foreach ($_POST["status"] as $id_user=>$status) {
		$i++;
		if ($i==sizeof($_POST["status"])) {
			$str_status .= $id_user." ";
		} else {
			$str_status .= $id_user." , ";
		}
	}
	$str_status .= " )";
	$dbconn->Execute("UPDATE ".USERS_TABLE." SET status='1', confirm='1' WHERE id IN ".$str_status);
	ListUsers();
	exit;
}

function ActiveChange() {
	global $smarty, $dbconn, $config, $lang;

	$str_id = " (";
	$i = 0;
	foreach ($_POST["id_user"] as $user_id) {
		$i++;
		if ($i==sizeof($_POST["id_user"])) {
			$str_id .= $user_id." ";
		} else {
			$str_id .= $user_id." , ";
		}
	}
	$str_id .= " )";

	$dbconn->Execute("UPDATE ".USERS_TABLE." SET active='0', confirm='0' WHERE id IN ".$str_id." AND  root_user<>'1' AND guest_user <>'1'");
	$str_status = " (";
	$i = 0;
	foreach ($_POST["active"] as $id_user=>$status) {
		$i++;
		if ($i==sizeof($_POST["active"])) {
			$str_status .= $id_user." ";
		} else {
			$str_status .= $id_user." , ";
		}
	}
	$str_status .= " )";
	$dbconn->Execute("UPDATE ".USERS_TABLE." SET active='1', confirm='1' WHERE id IN ".$str_status);
	/**
	 * @todo - деактивировать все объ€влени€ пользовател€ при деактивации админом его профайла
	 */
	ListUsers();
	exit;
}

function AccessChange() {
	global $smarty, $dbconn, $config, $lang;

	$str_id = " (";
	$i = 0;
	foreach ($_POST["id_user"] as $user_id) {
		$i++;
		if ($i==sizeof($_POST["id_user"])) {
			$str_id .= $user_id." ";
		} else {
			$str_id .= $user_id." , ";
		}
	}
	$str_id .= " )";

	$dbconn->Execute("UPDATE ".USERS_TABLE." SET access='0', confirm='0' WHERE id IN ".$str_id." AND  root_user<>'1' AND guest_user <>'1'");
	$str_status = " (";
	$i = 0;
	foreach ($_POST["access"] as $id_user=>$status) {
		$i++;
		if ($i==sizeof($_POST["access"])) {
			$str_status .= $id_user." ";
		} else {
			$str_status .= $id_user." , ";
		}
	}
	$str_status .= " )";
	$dbconn->Execute("UPDATE ".USERS_TABLE." SET access='1', confirm='1' WHERE id IN ".$str_status);
	ListUsers();
	exit;
}

function EditUserForm($par='', $id_user='') {
	global $smarty, $dbconn, $config, $lang;
	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_users.php";

	if (isset($_GET["id_user"]) && !empty($_GET["id_user"])) {
		$smarty->assign("add_to_lang", "&sel=edit_user&id_user=".intval($_GET["id_user"]));
	}
		
	IndexAdminPage('admin_users');
	CreateMenu('admin_lang_menu');
	$smarty->assign("use_agent_user_type", GetSiteSettings("use_agent_user_type"));
	
	if ($par =='add') {
		$user["user_type"] = 1;
		$user["lang_id"] = GetDefaultLanguageId();
		$user["agency_approve"] = -1;
	} else {
		$id_user = intval($id_user);

		$strSQL = "	SELECT fname, sname, login, DATE_FORMAT(date_birthday, '%d') as birth_day, DATE_FORMAT(date_birthday, '%m') as birth_month, DATE_FORMAT(date_birthday, '%Y') as birth_year, lang_id, email, DATE_FORMAT(date_last_seen, '".$config["date_format"]."' ) as date_last_seen, DATE_FORMAT(date_registration, '".$config["date_format"]."' ) as date_registration, root_user, guest_user, login_count, active, status, phone, user_type
					FROM ".USERS_TABLE." WHERE id='".$id_user."'  ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->RowCount()>0) {
			$row = $rs->GetRowAssoc(false);
			//id_user
			$user["fname"] = htmlspecialchars($row["fname"]);
			$user["sname"] = htmlspecialchars($row["sname"]);
			$user["login"] = htmlspecialchars($row["login"]);
			$user["birth_day"] = $row["birth_day"];
			$user["birth_month"] = $row["birth_month"];
			$user["birth_year"] = $row["birth_year"];
			$user["email"] = htmlspecialchars($row["email"]);
			$user["lang_id"] = GetUserLanguageId($row["lang_id"]);
			$user["user_type"] = $row["user_type"];
			
			if ($user["user_type"] == 2) {
				$strSQL_2 = "SELECT company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, logo_path, admin_approve, id_country,id_region,id_city,address,postal_code
							FROM ".USER_REG_DATA_TABLE."
							WHERE id_user='".$id_user."' ";
				$rs_2 = $dbconn->Execute($strSQL_2);
				$row_2 = $rs_2->GetRowAssoc(false);
				$user["company_name"] = htmlspecialchars($row_2["company_name"]);
				$user["company_url"] = htmlspecialchars($row_2["company_url"]);
				$user["weekday_str"] = $row_2["weekday_str"];
				$user["weekday_1"] = explode(",",$user["weekday_str"]);
				foreach ($user["weekday_1"] as $value) {
					$user["weekday"][$value-1] = $value;
				}
				$user["work_time_begin"] = $row_2["work_time_begin"];
				$user["work_time_end"] = $row_2["work_time_end"];
				$user["lunch_time_begin"] = $row_2["lunch_time_begin"];
				$user["lunch_time_end"] = $row_2["lunch_time_end"];
				$user["logo_path"] = $row_2["logo_path"];
				$user["admin_approve"] = $row_2["admin_approve"];
				$user["id_country"] = $row_2["id_country"];
				$user["id_region"] = $row_2["id_region"];
				$user["id_city"] = $row_2["id_city"];
				$user["address"] = $row_2["address"];
				$user["postal_code"] = htmlspecialchars($row_2["postal_code"]);
				if (strlen($user["logo_path"])>0) {
					if (file_exists($config["site_path"]."/uploades/photo/".$user["logo_path"])) {
						$user["logo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$user["logo_path"];
					} else {
						$user["logo_path"] = '';
					}
				}				
				$user["use_photo_approve"] = GetSiteSettings("use_photo_approve");
				$strSQL_2 = "SELECT id_agent FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$id_user' AND approve = '1'";
				$rs_2 = $dbconn->Execute($strSQL_2);
				if ($rs_2->RowCount() > 0) {
					$user["have_agents"] = 1;
				}
			}
		$user["agency_approve"] = -1;
		
		if ($user["user_type"] == 3) {
			
			$strSQL = "SELECT aoc.id, aoc.id_agent, aoc.id_company, aoc.approve, rd.company_name, rd.company_url, rd.logo_path, rd.admin_approve as logo_approve, rd.address, rd.weekday_str, rd.work_time_begin, rd.work_time_end, rd.lunch_time_begin, rd.lunch_time_end, ct.name as country_name, rt.name as region_name, cit.name as city_name, cit.lon, cit.lat, rd.id_country,  u.phone 
								FROM ".AGENT_OF_COMPANY_TABLE." aoc 
								LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user 
								LEFT JOIN ".USERS_TABLE." u ON aoc.id_company = u.id 
								LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=rd.id_country
								LEFT JOIN ".REGION_TABLE." rt ON rt.id=rd.id_region
								LEFT JOIN ".CITY_TABLE." cit ON cit.id=rd.id_city
								WHERE id_agent = '$id_user' AND (aoc.inviter = 'agent' OR (aoc.inviter = 'company' AND aoc.approve = '1')) ORDER BY aoc.id DESC LIMIT 1";
			
			$rs = $dbconn->Execute($strSQL);				
			
			if ($rs->fields[0] > 0) {
				$row_3 = $rs -> GetRowAssoc(false);
				$user["agency_name"] = $row_3["company_name"];					
				if ($row_3["company_url"] != "" && strpos("http://", $row_3["company_url"]) == 0) {
					$user["agency_url"] = "http://".$row_3["company_url"]."/";
				} else { 
					$user["agency_url"] = $row_3["company_url"];
				}
				$user["id_agency"] = $row_3["id_company"];					
				
				$user["agency_approve"] = $row_3["approve"];
				$user["logo_approve"] = $row_3["logo_approve"];			
				if ($row_3["logo_path"]) {				
					$user["agency_logo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$row_3["logo_path"];
				}
				$user["agency_phone"] = $row_3["phone"];		
				$user["country_name"] = $row_3["country_name"];
				
				if ($user["country_name"] != '') {
					$user["in_base"] = 1;
				} else { 
					$user["in_base"] = 0;
				}
				$user["id_country"] = $row_3["id_country"];
				$user["region_name"] = $row_3["region_name"];		
				$user["city_name"] = $row_3["city_name"];		
				$user["ag_address"] = $row_3["address"];
				
				$user["weekday_str"] = $row_3["weekday_str"];
				if ($user["weekday_str"] != "") {
					$user["weekday_1"] = explode(",",$user["weekday_str"]);
					foreach ($user["weekday_1"] as $value) {
						$user["weekday"][$value-1] = $value;
					}
				}
				$user["work_time_begin"] = intval($row_3["work_time_begin"]);
				$user["work_time_end"] = intval($row_3["work_time_end"]);
				$user["lunch_time_begin"] = intval($row_3["lunch_time_begin"]);
				$user["lunch_time_end"] = intval($row_3["lunch_time_end"]);
				
				$use_maps_in_viewprofile = GetSiteSettings("use_maps_in_viewprofile");
				$smarty->assign("use_maps_in_viewprofile", $use_maps_in_viewprofile);
				$smarty->assign("map",GetMapSettings());		
				
				$profile["country_name"]=$user["country_name"];
				$profile["region_name"]=$user["region_name"];
				$profile["city_name"]=$user["city_name"];
				$profile["addres"] = $user["ag_address"];
				$profile["lon"]=$row_3["lon"];
				$profile["lat"]=$row_3["lat"];
				$smarty->assign("profile",$profile);
				
			}
							
			$rs_2 = $dbconn->Execute("SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='$id_user'");
			if ($rs_2->NumRows() > 0) {				
			$user["photo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_2->fields[0];			
			$user["admin_approve"] = $rs_2->fields[1];			
			$user["use_photo_approve"] = GetSiteSettings("use_photo_approve");
			}			
		}

			$user["phone"] = htmlspecialchars($row["phone"]);
			if ( $row["root_user"] || $row["guest_user"] ) {
				$user["root_user"] = 1;
			} else {
				$user["root_user"] = 0;
			}
			$user["date_last_seen"] = $row["date_last_seen"];
			$user["date_registration"] = $row["date_registration"];
			$user["login_count"] = $row["login_count"];

			$user["active"] = $row["active"];
			$user["status"] = $row["status"];
		}
		$user["id"] = $id_user;

		$strSQL = "SELECT DISTINCT id FROM ".RENT_ADS_TABLE." WHERE id_user='".$id_user."' ";
		$rs = $dbconn->Execute($strSQL);
		if ( $rs->RowCount()>0 ) {
			$user["rent_link"] = $file_name."?sel=user_rent&id_user=".$id_user;
			$user["rent_count"] = $rs->RowCount();
		}

		$strSQL = "SELECT DISTINCT id FROM ".MAILBOX_MESSAGES_TABLE." WHERE from_user_id='".$id_user."' OR to_user_id='".$id_user."' GROUP BY id ";
		$rs = $dbconn->Execute($strSQL);
		if ( $rs->RowCount()>0 ) {
			$user["mail_link"] = $file_name."?sel=user_mail&amp;id_user=".$id_user." ";
		}
	}

	GetLocationContent(isset($user["id_country"]) ? $user["id_country"] : "", isset($user["id_region"]) ? $user["id_region"] : "");

	$smarty->assign("user", $user);
	$smarty->assign("par", $par);

	$smarty->assign("day", GetDaySelect(isset($user["birth_day"]) ? $user["birth_day"] : date("d")));
	$smarty->assign("month", GetMonthSelect(isset($user["birth_month"]) ? $user["birth_month"] : date("d")));
	if (!isset($user["birth_year"])) {
		$user["birth_year"] = intval(date("Y"))-18;
	}
	$smarty->assign("year", GetYearSelect($user["birth_year"], 80, (intval(date("Y"))-18)));

	$week = GetWeek();
	$smarty->assign("week", $week);

	$time_arr = GetHourSelect();
	$smarty->assign("time_arr", $time_arr);


	$back_link = (isset($_REQUEST["from_file"]) && !empty($_REQUEST["from_file"])) ? strval($_REQUEST["from_file"]).".php" : $file_name;
	$back_link .= (isset($_REQUEST["from_file_sel"]) && !empty($_REQUEST["from_file_sel"])) ? "?sel=".strval($_REQUEST["from_file_sel"]) : "";
	$back_link .= (isset($_REQUEST["from_file_id_group"]) && !empty($_REQUEST["from_file_id_group"])) ? "&id_group=".strval($_REQUEST["from_file_id_group"]) : "";
	
	$redirect = (isset($_REQUEST["redirect"]) && !empty($_REQUEST["redirect"])) ? intval($_REQUEST["redirect"]) : "";
	switch ($redirect){
		case "1":
			$back_link = $config["server"].$config["site_root"]."/admin/admin_sms_notifications.php?sel=statistics&type=user&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorterR"])."&order=".intval($_REQUEST["orderR"]);			
			break;	
		case "2":	
			$back_link = $config["server"].$config["site_root"]."/admin/admin_sms_notifications.php?sel=statistics&type=user&id_user=".$id_user;			
			break;	
	}

	
	$smarty->assign("back_link", $back_link);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_edit_user_form.tpl");
	exit;
}

function AddNewUser() {
	global $smarty, $dbconn, $config, $lang;

	$fname = strip_tags(trim($_POST["fname"]));
	$sname = strip_tags(trim($_POST["sname"]));
	$birth_year = intval(trim($_POST["birth_year"]));
	$birth_month = intval(trim($_POST["birth_month"]));
	$birth_day = intval(trim($_POST["birth_day"]));
	$email = trim($_POST["email"]);
	$login = $email;
	$phone = strip_tags(trim($_POST["phone"]));
	$password = trim($_POST["password"]);

	if ($birth_year<1) {
		$birthdate = sprintf("%04d-%02d-%02d", 0, 0, 0);
	} else {
		$birthdate = sprintf("%04d-%02d-%02d", $birth_year, $birth_month, $birth_day);
	}
	$user_type = intval($_POST["user_type"]);

	/**
	 * @todo check fields values, now check only in tpl
	 */
	$active = 1;
	$status = 1;
	$send_mail = 1;
	$lang_id = intval($_REQUEST["lang_id"]);

	$strSQL = "	INSERT INTO ".USERS_TABLE." (fname, sname, status, confirm, login, password, date_birthday, lang_id, email, date_registration, root_user, guest_user, active, user_type, phone)
				VALUES ('".addslashes($fname)."', '".addslashes($sname)."', '".$status."', '1', '".$login."', '".md5($password)."', '".$birthdate."', '".$lang_id."', '".$email."', now(), '0', '0', '".$active."', '".$user_type."', '".addslashes($phone)."')";
	$dbconn->Execute($strSQL);
	$id_user = $dbconn->Insert_ID();

	//add to default group
	$strSQL = "SELECT id FROM ".GROUPS_TABLE." WHERE type='d'";
	$rs = $dbconn->Execute($strSQL);
	if (intval($rs->fields[0])>0) {
		$dbconn->Execute("INSERT INTO ".USER_GROUP_TABLE." (id_user, id_group) VALUES ('".$id_user."', '".intval($rs->fields[0])."') ");
	}
        if (GetSiteSettings("use_pilot_module_newsletter")) {
        	UpdateNewsletterUserData($id_user, $fname, $sname, $email);
        	UpdateUserRealestateMailingList($id_user);
        }

	if ($user_type==2) {
		/**
		 * realtor
		 */
		$company_name = addslashes(strip_tags(trim($_POST["company_name"])));
		if ($company_name == '') {
			EditUserForm("edit", $id_user);
			//@todo send error
		}		
		$id_country = (isset($_POST["country"]) && !empty($_POST["country"])) ? $_POST["country"] : 0;
		$id_region = (isset($_POST["region"]) && !empty($_POST["region"])) ? $_POST["region"] : 0;
		$id_city = (isset($_POST["city"]) && !empty($_POST["city"])) ? $_POST["city"] : 0;
		$postal_code = (isset($_POST["postal_code"]) && !empty($_POST["postal_code"])) ? $_POST["postal_code"] : "";
		$address = (isset($_POST["address"]) && !empty($_POST["address"])) ? addslashes(strip_tags(trim($_POST["address"]))) : "";
		$company_url = addslashes(strip_tags(trim($_POST["company_url"])));
		$company_rent_count = addslashes(strip_tags(trim($_POST["company_rent_count"])));
		$company_how_know = addslashes(strip_tags(trim($_POST["company_how_know"])));
		$company_quests_comments = addslashes(strip_tags(trim($_POST["company_quests_comments"])));
		$weekday_str = implode(",",$_POST["weekday"]);
		$work_time_begin = intval($_POST["work_time_begin"]);
		$work_time_end = intval($_POST["work_time_end"]);
		$lunch_time_begin = intval($_POST["lunch_time_begin"]);
		$lunch_time_end = intval($_POST["lunch_time_end"]);
		$strSQL = "SELECT id_user FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$id_user."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$strSQL = " UPDATE ".USER_REG_DATA_TABLE." SET
						company_name='".$company_name."', company_url='".$company_url."',
						id_country='".$id_country."', id_region='".$id_region."', id_city='".$id_city."', 
						address='".$address."', postal_code='".$postal_code."',
						company_rent_count='".$company_rent_count."',  company_how_know='".$company_how_know."',
						company_quests_comments='".$company_quests_comments."',
						weekday_str='".$weekday_str."',  work_time_begin='".$work_time_begin."',
						work_time_end='".$work_time_end."',	lunch_time_begin='".$lunch_time_begin."',
						lunch_time_end='".$lunch_time_end."'
						WHERE id_user='".$id_user."' ";
		} else {
			$strSQL = " INSERT INTO ".USER_REG_DATA_TABLE."
						(id_user, company_name, company_url, id_country, id_region, id_city, 
						address, postal_code, company_rent_count,
						company_how_know, company_quests_comments, weekday_str,
						work_time_begin, work_time_end, lunch_time_begin, lunch_time_end)
						VALUES ('".$id_user."', '".$company_name."', '".$company_url."', '".
						$id_country."','".$id_region."','".$id_city."','".$address."','".$postal_code."','".
						$company_rent_count."', '".$company_how_know."', '".$company_quests_comments."', '".
						$weekday_str."', '".$work_time_begin."', '".$work_time_end."', '".
						$lunch_time_begin."', '".$lunch_time_end."' )";
		}
		$dbconn->Execute($strSQL);

		$company_logo = $_FILES["company_logo"];

		if ((strlen($company_logo["name"])!=0) && (intval($company_logo["size"])!=0)) {
				$strSQL = " SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$id_user."' ";
				$rs = $dbconn->Execute($strSQL);
				if (strlen($rs->fields[0])>0) {
					if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
						$dbconn->Execute(" UPDATE ".USER_REG_DATA_TABLE." SET logo_path='' WHERE id_user='".$id_user."' ");
						unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
					}
				}
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($company_logo, $id_user, 1);			
		}
	}
	
	if ($user_type==3)
	{
		$id_company = intval($_POST["id_company"]);		
		$id_agent = $id_user;
		
		$strSQL = "SELECT id FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent='$id_agent' AND id_company='$id_company' AND approve = '1'";
		
		$rs = $dbconn->Execute($strSQL);		
		
		if ($id_company && ($rs->RowCount() == 0)) {	
			$strSQL = "INSERT INTO ".AGENT_OF_COMPANY_TABLE." (id_agent, id_company, approve, inviter) 
												VALUES ('".$id_agent."','".$id_company."','0','agent')";
		
		$rs = $dbconn->Execute($strSQL);	
	
		/**
		 * Send mail to the company
		 */
		$strSQL = "SELECT u.email, u.fname, u.sname, u.lang_id, rd.company_name FROM ".USERS_TABLE." u  
							LEFT JOIN ".USER_REG_DATA_TABLE." rd on rd.id_user=u.id 
							WHERE id='$id_company'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$company["email"] = $row["email"];
		$company["lang_id"] = $row["lang_id"];
		$data["realtor_name"] = htmlspecialchars($row["fname"])." ".htmlspecialchars($row["sname"]);
		$data["company_name"] = $row["company_name"];
		
		$strSQL = "SELECT fname, sname FROM ".USERS_TABLE." WHERE id='$id_agent'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data["user_name"] = htmlspecialchars($row["fname"])." ".htmlspecialchars($row["sname"]);
		$data["approve_link"] = $config["server"].$config["site_root"]."/agents.php";
		
		$site_email = GetSiteSettings('site_email');
	
		$mail_content = GetMailContentReplace("mail_content_new_agent", GetUserLanguageId($user["lang_id"]));
	
		SendMail($company["email"], $site_email, $mail_content["subject"], $data, $mail_content, "mail_new_agent_table", '', $data["company_name"]."(".$data["user_name"].")" , $mail_content["site_name"], 'text');
	
		}
	}
	if ($user_type == 3) {
		$agent_photo = $_FILES["agent_photo"];

		if ((strlen($agent_photo["name"])!=0) && (intval($agent_photo["size"])!=0)) {			
			
			$strSQL = " SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$id_user."' ";
			$rs = $dbconn->Execute($strSQL);
			if (strlen($rs->fields[0])>0) {
				if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
					$dbconn->Execute(" DELETE FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$id_user."' ");
					
				}
			}				
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($agent_photo, $id_user, 0, "agent_photo");			
			if (!$err) {
				unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
			} else { 
				$dbconn->Execute("INSERT INTO ".USER_PHOTOS_TABLE." (id_uder, photo_path, approve) VALUES ('$id_user', '$rs->fields[0]', '$rs->fields[1]') WHERE id_user='".$id_user."' ");
			}
		}
	}

	if ($send_mail == 1) {
		// send registration data to user on reg email
		$cont_arr["fname"] = $fname;
		$cont_arr["sname"] = $sname;
		$cont_arr["login"] = $login;
		$cont_arr["pass"] = $password;
		$cont_arr["email"] = $email;
		$cont_arr["site"] = $config["server"];

		$site_mail = GetSiteSettings("site_email");

		$cont_arr["adminname"] = GetAdminName();

		$mail_content = GetMailContentReplace("mail_content_registration_by_admin", $lang_id);//xml
		$subject = $mail_content["subject"];
		$email_to_name = $cont_arr["fname"]." ".$cont_arr["sname"];

		SendMail($email, $site_mail, $subject, $cont_arr, $mail_content, "mail_registration_for_user", "", $email_to_name, $mail_content["site_name"], "text");
	}
	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_users.php?sel=edit_user&id_user=$id_user".((isset($err) && $err != "") ? "&err=$err" : ""));
	exit();
}

function DeleteUser() {
	global $smarty, $dbconn, $config, $lang, $REFERENCES;
	$id_user = intval($_GET["id_user"]);

	if ( ($id_user == 1) || ($id_user == 2) ) {
		ListUsers();
		return;
	}
	/* ads deleting */
	//slideshow
	$photo_folder = GetSiteSettings("photo_folder");
	$strSQL = "SELECT upload_path FROM ".RENT_ADS_TABLE." WHERE id_user='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount()>0) {
		while(!$rs->EOF) {
			unlink($config["site_path"].$photo_folder."/".$rs->fields[0]);
			$rs->MoveNext();
		}
	}
	//ads
	$dbconn->Execute("DELETE FROM ".RENT_ADS_TABLE." WHERE id_user='".$id_user."' ");
	$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$dbconn->Execute("DELETE FROM ".$arr["spr_user_table"]." WHERE id_user='".$id_user."' ");
			if ($arr["spr_match_table"] != "") {
				$dbconn->Execute("DELETE FROM ".$arr["spr_match_table"]." WHERE id_user='".$id_user."' ");
			}
		}
	}

	$dbconn->Execute("DELETE FROM ".FEATURED_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".USERS_RENT_AGES_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".USERS_RENT_PAYS_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".RENT_AD_VISIT_TABLE." WHERE id_visiter='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".INTERESTS_TABLE." WHERE id_interest_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".SPONSORS_ADS_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".CALENDAR_EVENTS_TABLE." WHERE user_id='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent='".$id_user."' OR id_company='".$id_user."'");
	
	
	//photo
	$folder = GetSiteSettings("photo_folder");
	$strSQL = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$id_user."' AND upload_type='f' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			unlink($config["site_path"].$folder."/".$rs->fields[0]);
			unlink($config["site_path"].$folder."/thumb_".$rs->fields[0]);
			unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[0]);
			$rs->MoveNext();
		}
	}
	//photo from USERS_PHOTO
	
	$strSQL = "SELECT photo_path FROM ".USER_PHOTOS_TABLE." WHERE id_user = '$id_user'";	
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			unlink($config["site_path"].$folder."/".$rs->fields[0]);			
			$rs->MoveNext();
		}
	}
	
	$dbconn->Execute("DELETE FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$id_user."' ");
	
	//video
	$folder = GetSiteSettings("video_folder");
	$strSQL = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$id_user."' AND upload_type='v' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			unlink($config["site_path"].$folder."/".$rs->fields[0]);
			
			$flv_name = explode('.', $rs->fields[0]);
			unlink($config["site_path"].$folder."/".$flv_name[0]."1.jpg");
			unlink($config["site_path"].$folder."/".$flv_name[0].".flv");					

			$rs->MoveNext();
		}
	}
	$dbconn->Execute("DELETE FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$id_user."' ");

	//plan
	$strSQL = "SELECT upload_path FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='".$id_user."'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			unlink($config["site_path"].$folder."/".$rs->fields[0]);
			unlink($config["site_path"].$folder."/thumb_".$rs->fields[0]);
			unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[0]);
			$rs->MoveNext();
		}
	}
	$dbconn->Execute("DELETE FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='".$id_user."' ");
	/* /ads deleting */

	// deleting other user information
	$dbconn->Execute("DELETE FROM ".ACCOUNT_DEACTIVATED_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".ACTIVE_SESSIONS_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".BLACKLIST_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".BLACKLIST_TABLE." WHERE id_enemy='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".HOTLIST_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".HOTLIST_TABLE." WHERE id_friend ='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".SUBSCRIBE_USER_TABLE." WHERE id_user='".$id_user."' ");

	$dbconn->Execute("DELETE FROM ".INTERESTS_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".PROFILE_VISIT_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".PROFILE_VISIT_TABLE." WHERE id_visiter='".$id_user."' ");

	$dbconn->Execute("DELETE FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".BILLING_USER_PERIOD_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".BILLING_REQUESTS_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".BILLING_SPENDED_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".BILLING_USER_RECIPIENTS_TABLE." WHERE id_user='".$id_user."' OR id_from_user='".$id_user."'");

	$dbconn->Execute("DELETE FROM ".USER_GROUP_TABLE." WHERE id_user='".$id_user."' ");
	$dbconn->Execute("DELETE FROM ".USERS_TABLE." WHERE id='".$id_user."' ");

	if (GetSiteSettings("use_pilot_module_newsletter")) {
	        SetNewsletterUserUnactive($id_user);
        }
	

	//other with select or files
	$strSQL = "SELECT id FROM ".SAVE_POWERSEARCHR_TABLE." WHERE id_user='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		while(!$rs->EOF) {
			$dbconn->Execute("DELETE FROM ".SAVE_POWERSEARCHR_DESCR_TABLE." WHERE id_save='".$rs->fields[0]."' ");
			$rs->MoveNext();
		}
	}
	$dbconn->Execute("DELETE FROM ".SAVE_POWERSEARCHR_TABLE." WHERE id_user='".$id_user."' ");
	/* mailbox */
	$dbconn->Execute("DELETE FROM ".MAILBOX_MESSAGES_TABLE." WHERE from_user_id='".$id_user."'");
	$dbconn->Execute("DELETE FROM ".MAILBOX_MESSAGES_TABLE." WHERE to_user_id='".$id_user."'");
	$dbconn->Execute("DELETE FROM ".MAILBOX_USER_LISTS_TABLE." WHERE user_id='".$id_user."'");
	$dbconn->Execute("DELETE FROM ".MAILBOX_USER_LISTS_TABLE." WHERE list_user_id='".$id_user."'");
	ListUsers();
	return;
}

function UserAds($id_user='') {
	global $smarty, $dbconn, $config, $lang, $multi_lang;
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	
	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_users.php";

	IndexAdminPage('admin_users');
	CreateMenu('admin_lang_menu');

	$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));

	$strSQL = " SELECT fname, sname FROM ".USERS_TABLE." WHERE id='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	$smarty->assign("user_login", $rs->fields[0]." ".$rs->fields[1]);

	$param = $file_name."?sel=user_rent&id_user=".$id_user."&";
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : "";
	$page = (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) ? intval($_REQUEST["page"]) : "";
	$order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 0;
	$referer = (isset($_REQUEST["referer"]) && !empty($_REQUEST["referer"])) ? $_REQUEST["referer"] : "";
	
	$redirect = (isset($_REQUEST["redirect"]) && !empty($_REQUEST["redirect"])) ? intval($_REQUEST["redirect"]) : "";	
	$back_link = "";
	switch ($redirect) {
		case "2":
			$back_link = $config["server"].$config["site_root"]."/registration.php?sel=choose_company&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorter"])."&search=".$_REQUEST["search"]."&order=".$order."&is_show=".intval($_REQUEST["is_show"])."&from_admin_mode=1";			
			break;	
		case "3":
			$back_link = $config["server"].$config["site_root"]."/agents.php?sel=choose_agent&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorter"])."&search=".$_REQUEST["search"]."&order=".$order."&is_show=".intval($_REQUEST["is_show"])."&from_admin_mode=1";			
			break;	
		case "4":
			$back_link = $config["server"].$config["site_root"]."/agents.php?sel=agents_list&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorter"])."&order=".$order."&from_admin_mode=1";			
			break;		
	}
	$smarty->assign("redirect", $redirect);
	if (isset($back_link)){
		$smarty->assign("back_link", $back_link);
	}

	$ads = GetUserAds($id_user, $file_name, $param, $id_ad);

	/**
	 * Get info for calendar displaying
	 */
	$date["months"] = GetMonth();	
	$date["day_of_week"] = GetDayOfWeek();
	$date["now_date"] = getdate();		
	$start_month = isset($_REQUEST["start_month"]) ? intval($_REQUEST["start_month"]) : $date["now_date"]["mon"];
	$start_year = isset($_REQUEST["start_year"]) ? intval($_REQUEST["start_year"]) : $date["now_date"]["year"];				
	$calendar_event = new CalendarEvent();
	$date["display"] = $calendar_event->GetMonthYearArray($start_month, $start_year, $ads[0]["id"], $ads[0]["id_user"], 12, 12);			
	$smarty->assign("half_tf_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_tf_day.gif");
	$smarty->assign("half_ft_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_ft_day.gif");
	$smarty->assign("half_tft_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_tft_day.gif");
	$smarty->assign("date", $date);
	$smarty->assign("id_ad", $ads[0]["id"]);
	
	if (count($ads) > 0 ) {
		$smarty->assign("ads",$ads);
	} else {
		EditUserForm("edit", $id_user);
		exit;
	}

	$smarty->assign("id_user", $id_user);
	if ($referer != "") {
		$file_name = GetReferer($referer,$id_user);
	}

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_users_rent_list.tpl");
	exit;
}

function UploadView() {
	global $smarty, $dbconn, $config, $lang, $multi_lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_users.php";
	IndexAdminPage('admin_users');
	$id_file = intval($_GET["id_file"]);
	$category = $_GET["category"];

	$rs = $dbconn->Execute("SELECT upload_path, upload_type, user_comment
							from ".USERS_RENT_UPLOADS_TABLE."
							where id='".$id_file."'");

	$upload_file["file_name"] = $rs->fields[0];
	$upload_file["upload_type"] = $rs->fields[1];
	$upload_file["user_comment"] = stripslashes($rs->fields[2]);


	switch($upload_file["upload_type"]) {
		case "f":		$folder = GetSiteSettings("photo_folder");	break;
		case "a":		$folder = GetSiteSettings("audio_folder");	break;
		case "v":		$folder = GetSiteSettings("video_folder");	break;
	}
	
	$is_flv = intval($_GET["is_flv"]);
		
	if (GetSiteSettings("use_ffmpeg") && $is_flv) {				
		$flv_name = explode('.', $upload_file["file_name"]);
		$upload_file["file_icon"] = $flv_name[0]."1.jpg";
		$upload_file["file_name"] = $flv_name[0].".flv";
		$upload_file["icon_path"] = $config["server"].$config["site_root"].GetSiteSettings("video_folder")."/".$upload_file["file_icon"];
		$size = explode('x', GetSiteSettings("flv_output_dimension"));
		$upload_file["width"] = $size[0];			
		$upload_file["height"] = $size[1];
	}		
	
	$upload_file["file_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_name"];

	$smarty->assign("upload_file", $upload_file);
	
	$smarty->assign("is_flv", $is_flv);		
	$smarty->display(TrimSlash($config["index_theme_path"])."/upload_view.tpl");
	exit;
}

function UploadPlanView() {
	global $smarty, $dbconn, $config, $lang, $multi_lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_users.php";
	IndexAdminPage('admin_users');
	$id_file = intval($_GET["id_file"]);
	$folder = GetSiteSettings("photo_folder");

	$rs = $dbconn->Execute("SELECT upload_path, user_comment
							from ".USER_RENT_PLAN_TABLE."
							where id='".$id_file."'");

	$upload_file["file_name"] = $rs->fields[0];
	$upload_file["upload_type"] = "f";
	$upload_file["user_comment"] = stripslashes($rs->fields[2]);
	$upload_file["file_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_name"];

	$smarty->assign("upload_file", $upload_file);

	$smarty->display(TrimSlash($config["index_theme_path"])."/upload_view.tpl");
	exit;
}

function UploadLogoView() {
	global $smarty, $dbconn, $config, $lang, $multi_lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_users.php";
	IndexAdminPage('admin_users');
	$id_file = $_GET["id_file"];	
	$folder = GetSiteSettings("photo_folder");
	
	if ($id_file[1] == '_') {		
		$temp = explode("_", strval($id_file));
		$id_file = intval($temp[1]);
		$rs = $dbconn->Execute("SELECT photo_path
							from ".USER_PHOTOS_TABLE."
							where id_user='".$id_file."'");
	} else { 
	$rs = $dbconn->Execute("SELECT logo_path
							from ".USER_REG_DATA_TABLE."
							where id_user='".$id_file."'");
	}

	$upload_file["file_name"] = $rs->fields[0];
	$upload_file["upload_type"] = "f";
	$upload_file["file_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_name"];

	$smarty->assign("upload_file", $upload_file);

	$smarty->display(TrimSlash($config["index_theme_path"])."/upload_view.tpl");
	exit;
}

function UploadDelete() {
	global $smarty, $config, $dbconn, $user;
	$id_file = intval($_GET["id_file"]);
	$id_user = intval($_GET["id_user"]);
	$sub_sel = (isset($_REQUEST["sub_sel"]) && !empty($_REQUEST["sub_sel"])) ? $_REQUEST["sub_sel"] : "";

	if ($sub_sel == "plan") {
		$rs = $dbconn->Execute("SELECT id, upload_path
								FROM ".USER_RENT_PLAN_TABLE."
								WHERE id='".$id_file."'");

	} else {
		$rs = $dbconn->Execute("SELECT id, upload_path, upload_type
								FROM ".USERS_RENT_UPLOADS_TABLE."
								WHERE id='".$id_file."'");
	}
	if ($rs->fields[0]>0) {
		$upload_type = (isset($rs->fields[2])) ? $rs->fields[2] : "f";
		switch($upload_type) {
			case "f":	$folder = GetSiteSettings("photo_folder");	break;
			case "v":	$folder = GetSiteSettings("video_folder");	break;
		}

		unlink($config["site_path"].$folder."/".$rs->fields[1]);
		unlink($config["site_path"].$folder."/thumb_".$rs->fields[1]);
		unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[1]);
		
		if ($upload_type == "v") {
			$flv_name = explode('.', $rs->fields[1]);
			unlink($config["site_path"].$folder."/".$flv_name[0]."1.jpg");
			unlink($config["site_path"].$folder."/".$flv_name[0].".flv");					
		}	

		if ($sub_sel == "plan") {
			$dbconn->Execute("DELETE FROM ".USER_RENT_PLAN_TABLE." WHERE id='".$id_file."' ");
		} else {
			$dbconn->Execute("DELETE FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id='".$id_file."' ");
		}
	}

	UserAds($id_user);
	exit;
}

function DeleteUserAd() {
	global $smarty, $config, $dbconn, $REFERENCES;

	$id_ad = intval($_REQUEST["id_ad"]);
	$id_user = intval($_REQUEST["id_user"]);	
	if (!$id_ad) {
		UserAds($id_user);
		exit;
	}

	$strSQL = " SELECT id FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' ";
	$rs = $dbconn->Execute($strSQL);
	if (intval($rs->fields[0]) === $id_ad) {
		$dbconn->Execute("DELETE FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' ");

		$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$dbconn->Execute("DELETE FROM ".$arr["spr_user_table"]." WHERE id_ad='".$id_ad."' ");
				if ($arr["spr_match_table"] != "") {
					$dbconn->Execute("DELETE FROM ".$arr["spr_match_table"]." WHERE id_ad='".$id_ad."' ");
				}
			}
		}

		$dbconn->Execute("DELETE FROM ".FEATURED_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".USERS_RENT_AGES_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".RENT_AD_VISIT_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".INTERESTS_TABLE." WHERE id_interest_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".SPONSORS_ADS_TABLE." WHERE id_ad='".$id_ad."' ");
		$dbconn->Execute("DELETE FROM ".CALENDAR_EVENTS_TABLE." WHERE id_ad='".$id_ad."' ");

		//photo
		$folder = GetSiteSettings("photo_folder");
		$strSQL = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND upload_type='f' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {
				unlink($config["site_path"].$folder."/".$rs->fields[0]);
				unlink($config["site_path"].$folder."/thumb_".$rs->fields[0]);
				unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[0]);
				$rs->MoveNext();
			}
		}
		//video
		$folder = GetSiteSettings("video_folder");
		$strSQL = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND upload_type='v' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {
				unlink($config["site_path"].$folder."/".$rs->fields[0]);
				
				$flv_name = explode('.', $rs->fields[0]);
				unlink($config["site_path"].$folder."/".$flv_name[0]."1.jpg");
				unlink($config["site_path"].$folder."/".$flv_name[0].".flv");					

				$rs->MoveNext();
			}
		}
		$dbconn->Execute("DELETE FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' ");

		//plan
		$strSQL = "SELECT upload_path FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$id_ad."'";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {
				unlink($config["site_path"].$folder."/".$rs->fields[0]);
				unlink($config["site_path"].$folder."/thumb_".$rs->fields[0]);
				unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[0]);
				$rs->MoveNext();
			}
		}
		$dbconn->Execute("DELETE FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$id_ad."' ");
	}
	//UserAds($id_user);
	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_users.php?sel=user_rent&id_user=$id_user");
	exit;
}
function SaveUserChanges() {
	global $smarty, $dbconn, $config, $lang, $multi_lang;

	$id_user = intval($_POST["user_id"]);		
	$fname = strip_tags(trim($_POST["fname"]));
	$sname = strip_tags(trim($_POST["sname"]));
	$birth_year = intval(trim($_POST["birth_year"]));
	$birth_month = intval(trim($_POST["birth_month"]));
	$birth_day = intval(trim($_POST["birth_day"]));
	$email = $_POST["email"];
	$login = $email;
	$phone = strip_tags(trim($_POST["phone"]));

	$lang_id = $_REQUEST["lang_id"];

	if ($birth_year<1) {
		$birthdate = sprintf("%04d-%02d-%02d", 0, 0, 0);
	} else {
		$birthdate = sprintf("%04d-%02d-%02d", $birth_year, $birth_month, $birth_day);
	}
	$user_type = intval($_POST["user_type"]);

		
	$strSQL = " SELECT user_type FROM ".USERS_TABLE." WHERE id='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	$old_user_type = $rs->fields[0];
	
	if ($user_type==2) {
		/**
		 * realtor
		 */
		$company_name = addslashes(strip_tags(trim($_POST["company_name"])));
		if ($company_name == '') {
			EditUserForm("edit", $id_user);
			//@todo отправить ошибку!
		}
		$company_url = (isset($_REQUEST["company_url"]) && !empty($_REQUEST["company_url"])) ? addslashes(strip_tags(trim($_REQUEST["company_url"]))) : "";
		$company_rent_count = isset($_POST["company_rent_count"]) ? intval($_POST["company_rent_count"]) : 0;
		$company_how_know = isset($_POST["company_how_know"]) ? addslashes(strip_tags(trim($_POST["company_how_know"]))) : "";
		$company_quests_comments = isset($_POST["company_quests_comments"]) ? addslashes(strip_tags(trim($_POST["company_quests_comments"]))) : "";
		$weekday_str = (isset($_POST["weekday"])) ? implode(",",$_POST["weekday"]) : "";
		$work_time_begin = intval($_POST["work_time_begin"]);
		$work_time_end = intval($_POST["work_time_end"]);
		$lunch_time_begin = intval($_POST["lunch_time_begin"]);
		$lunch_time_end = intval($_POST["lunch_time_end"]);
		if ($lunch_time_begin >= $work_time_begin && $lunch_time_end <= $work_time_end && $lunch_time_begin <= $lunch_time_end && $work_time_begin <= $work_time_end) {
			$err = "";
		} else { 
			$err = "invalid_time";
			$work_time_begin = 0;
			$work_time_end = 0;
			$lunch_time_begin = 0;
			$lunch_time_end = 0;
		}		
		$id_country = intval($_POST["country"]);
		$id_region = intval($_POST["region"]);
		$id_city = intval($_POST["city"]);
		$address = trim(strip_tags(addslashes($_POST["address"])));
		$postal_code = trim(strip_tags(addslashes($_POST["postal_code"])));
		
		$strSQL = "SELECT id_user FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$id_user."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$strSQL = " UPDATE ".USER_REG_DATA_TABLE." SET
						company_name='".$company_name."', company_url='".$company_url."',
						company_rent_count='".$company_rent_count."',  company_how_know='".$company_how_know."',
						company_quests_comments='".$company_quests_comments."',
						weekday_str='".$weekday_str."',  work_time_begin='".$work_time_begin."',
						work_time_end='".$work_time_end."', lunch_time_begin='".$lunch_time_begin."',
						lunch_time_end='".$lunch_time_end."', id_country=".$id_country.", id_region=".$id_region.", id_city=".$id_city.", address='".$address."',postal_code='".$postal_code."' WHERE id_user='".$id_user."' ";
		} else {
			$strSQL = " INSERT INTO ".USER_REG_DATA_TABLE."
						(id_user, company_name, company_url, company_rent_count,
						company_how_know, company_quests_comments, weekday_str,
						work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, id_country,id_region,id_city,address, postal_code)
						VALUES ('".$id_user."', '".$company_name."', '".$company_url."', '".
						$company_rent_count."', '".$company_how_know."', '".$company_quests_comments."', '".
						$weekday_str."', '".$work_time_begin."', '".$work_time_end."', '".
						$lunch_time_begin."', '".$lunch_time_end."',".$id_country.",".$id_region.",".$id_city.",'".$address."','".$postal_code."' )";
		}
		$dbconn->Execute($strSQL);

		$company_logo = $_FILES["company_logo"];

		if ((strlen($company_logo["name"])!=0) && (intval($company_logo["size"])!=0)) {
				$strSQL = " SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$id_user."' ";
				$rs = $dbconn->Execute($strSQL);
				if (strlen($rs->fields[0])>0) {
					if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
						$dbconn->Execute(" UPDATE ".USER_REG_DATA_TABLE." SET logo_path='' WHERE id_user='".$id_user."' ");
						unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
					}
				}
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($company_logo, $id_user, 1);
			GetErrors($err);
		}
	}

	$send_info = 1;
	$change_pass = (isset($_REQUEST["change_pass"])) ? intval($_REQUEST["change_pass"]) : 0;

	$strSQL = "UPDATE ".USERS_TABLE." SET login='".addslashes($login)."', fname='".addslashes($fname)."', sname='".addslashes($sname)."', date_birthday='".$birthdate."', lang_id='".$lang_id."', email='".$email."', phone='".addslashes($phone)."', user_type='".$user_type."'";
	if ($change_pass) {		
		$password = $_POST["password"];
		$strSQL .= ", password=md5('".$password."')";
	}
	$strSQL .= "WHERE id = '$id_user'";
	$dbconn->Execute($strSQL);
	
	$rs = $dbconn->Execute("SELECT id_agent, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$id_user'");
    if (GetSiteSettings("use_pilot_module_newsletter")) {
    	UpdateNewsletterUserData($id_user, $fname, $sname, $email);	
    	UpdateUserRealestateMailingList($id_user);
    }
	$new_company = 0;
	if (!$rs->fields[0] && $user_type == 3) {
		$new_company = 1;
	}
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);
		
		$send_about_del = "no";				
		if ($old_user_type==3 && $user_type!=3) {
			if ($row["approve"] == 1) {			
				$send_about_del = "yes_1";			
			}		
			if ($row["inviter"] == 'company' && $row["approve"] == 0) {
				$send_about_del = "yes_2";
			}
			if ($row["inviter"] == 'agent' && $row["approve"] == 0) {
				$send_about_del = "yes_3";	
				
			}
		}
		
		if ($old_user_type == 3 && $user_type == 3) {
			$id_company = intval($_POST["id_company"]);			
			
			$agency_name = addslashes(strip_tags(trim($_POST["agency_name"])));	
			if ($row["id_company"] != $id_company) {
				$new_company = 1;
				if ($row["id_company"] != 0) {
					if ($row["approve"] == 1) {				
					$send_about_del = "yes_1";
					}
					if ($row["inviter"] == 'company' && $row["approve"] == 0) {
						$send_about_del = "yes_2";				
					}		
					if ($row["inviter"] == 'agent' && $row["approve"] == 0) {				
						$send_about_del = "yes_3";				
					}				
				}						
			}				
		}	
		$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$row["id_agent"]."'");
		$lang_id_agent = GetUserLanguageId($rs2->fields[0]);
		$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$row["id_company"]."'");
		$lang_id_company = GetUserLanguageId($rs2->fields[0]);
		
		if ($send_about_del != "no") {
			$site_mail = GetSiteSettings("site_email");		
			switch ($send_about_del) {
				case "yes_1": 
					$mail_content = GetMailContentReplace("mail_content_delete_by_agent", $lang_id_company);
					$template = "mail_delete_by_agent_table";
				break;
				
				//not approved by agent	
				case "yes_2": 
					$mail_content = GetMailContentReplace("mail_content_delete_by_agent_1", $lang_id_company);
					$template = "mail_delete_by_agent_table_2";
				break;
				//not approved by company 
				case "yes_3": 
					$mail_content = GetMailContentReplace("mail_content_delete_by_agent_2", $lang_id_company);
					$template = "mail_delete_by_agent_table_2";
				break;	
			}
			
			
			$subject = $mail_content["subject"];
					
			$rs2 = $dbconn->Execute("SELECT aoc.id_agent, aoc.id_company, rd.company_name FROM ".AGENT_OF_COMPANY_TABLE." aoc LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user WHERE aoc.id_agent = '$id_user'");
					
			$row2 = $rs2->GetRowAssoc(false);		
			$id_agent = $row2["id_agent"];
			$id_company_prev = $row2["id_company"];
			 
			$data["company_name"] = $row2["company_name"];
							
			$strSQL = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_company_prev'";		
			$rs2=$dbconn->Execute($strSQL);
			$row2 = $rs2->GetRowAssoc(false);
				
			$email = $row2["email"];
			$email_to_name = $row2["fname"]." ".$row2["sname"];
			$data["company_name_user"] = $email_to_name;
			
			$strSQL = "SELECT u.fname, u.sname FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
			$rs2=$dbconn->Execute($strSQL);
			$row2 = $rs2->GetRowAssoc(false);		
			
			$data["agent_name"] = $row2["fname"]." ".$row2["sname"];
			
			$data["link"] = $config["server"].$config["site_root"]."/agents.php";
			
			SendMail($email, $site_mail, $subject, $data, $mail_content, $template, $email_to_name, $mail_content["site_name"] );	
			$dbconn->Execute("DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$id_user' AND id_company = '$id_company_prev'");	
		}
		$rs->MoveNext();
	}
	
	if ($old_user_type == 2 && $user_type != 2) {
		$rs = $dbconn->Execute("SELECT id_agent, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$id_user'");	
		
		$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$row["id_agent"]."'");
		$lang_id_agent = GetUserLanguageId($rs2->fields[0]);	
		
		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			if ($row["inviter"] == 'company') {
					$mail_content = GetMailContentReplace("mail_content_delete_offer_by_realtor", $lang_id_agent);
					$template = "mail_delete_by_realtor_table";
			}		
			if ($row["inviter"] == 'agent') {				
					$mail_content = GetMailContentReplace("mail_content_decline_by_realtor", $lang_id_agent);
					$template = "mail_delete_by_realtor_table";
			}			
			$subject = $mail_content["subject"];
			$id_agent = $row["id_agent"];
			$id_company = $row["id_company"];
					
			$rs2 = $dbconn->Execute("SELECT company_name FROM ".USER_REG_DATA_TABLE." WHERE id_user = '$id_company'");	
			$row2 = $rs2->GetRowAssoc(false);
			$data["company_name"] = $row2["company_name"];
			
			$strSQL = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
			$rs2=$dbconn->Execute($strSQL);
			$row2 = $rs2->GetRowAssoc(false);
				
			$email = $row2["email"];
			$email_to_name = $row2["fname"]." ".$row2["sname"];
			$data["agent_name"] = $email_to_name;
			$data["link"] = $config["server"].$config["site_root"]."/account.php";
			
			SendMail($email, $site_mail, $subject, $data, $mail_content, "mail_delete_by_realtor_table", $email_to_name, $mail_content["site_name"] );	
				
			$strSQL = "DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$id_agent' AND id_company = '".$id_company."'";
			$dbconn->Execute($strSQL);
			$rs->MoveNext();
		}
	}
	
	if (($old_user_type != 3 && $user_type==3) || ($new_company == 1)) {
		$id_company = intval($_POST["id_company"]);				
		$id_agent = $id_user;
		
		$strSQL = "SELECT id FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent='$id_agent' AND id_company='$id_company' AND approve = '1'";		
		$rs = $dbconn->Execute($strSQL);		
		
		if ($id_company && ($rs->RowCount() == 0)) {
			$strSQL = "INSERT INTO ".AGENT_OF_COMPANY_TABLE." (id_agent, id_company, approve, inviter) 
												VALUES ('".$id_agent."','".$id_company."','0','agent')";
		
			$rs = $dbconn->Execute($strSQL);	
		
			/**
			 * Send mail to the company
			 */
			$strSQL = "SELECT u.email, u.fname, u.sname, u.lang_id, rd.company_name FROM ".USERS_TABLE." u  
								LEFT JOIN ".USER_REG_DATA_TABLE." rd on rd.id_user=u.id 
								WHERE id='$id_company'";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$company["email"] = $row["email"];
			$company["lang_id"] = $row["lang_id"];
			$data["realtor_name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
			$data["company_name"] = $row["company_name"];
			
			$strSQL = "SELECT fname, sname FROM ".USERS_TABLE." WHERE id='$id_agent'";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$data["user_name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
			$data["approve_link"] = $config["server"].$config["site_root"]."/agents.php";
			
			$site_email = GetSiteSettings('site_email');
		
			$mail_content = GetMailContentReplace("mail_content_new_agent", GetUserLanguageId($company["lang_id"]));
		
			SendMail($company["email"], $site_email, $mail_content["subject"], $data, $mail_content, "mail_new_agent_table", '', $data["company_name"]."(".$data["user_name"].")" , $mail_content["site_name"], 'text');
	
		}
	}
	if ($user_type == 3) {
		$agent_photo = $_FILES["agent_photo"];

		if ((strlen($agent_photo["name"])!=0) && (intval($agent_photo["size"])!=0)) {			
			
			$strSQL = " SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$id_user."' ";
			$rs = $dbconn->Execute($strSQL);
			if (strlen($rs->fields[0])>0) {
				if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
					$dbconn->Execute(" DELETE FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$id_user."' ");
					
				}
			}				
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($agent_photo, $id_user, 1, "agent_photo");			
			if (!$err) {
				unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
			} else { 
				$dbconn->Execute("INSERT INTO ".USER_PHOTOS_TABLE." (id_uder, photo_path, approve) VALUES ('$id_user', '$rs->fields[0]', '$rs->fields[1]') WHERE id_user='".$id_user."' ");
			}
		}
	}

	$strSQL .= " WHERE id='".$id_user."'";
			
	$dbconn->Execute($strSQL);

	if ($send_info == 1) {
		// send registration data to user on reg email
		$cont_arr["fname"] = stripslashes($fname);
		$cont_arr["sname"] = stripslashes($sname);
		$cont_arr["login"] = stripslashes($login);
		if ($change_pass==1) {
			$cont_arr["pass"] = $password;
		}
		$cont_arr["email"] = $email;
		$cont_arr["site"] = $config["server"];

		$site_mail = GetSiteSettings("site_email");

		$cont_arr["adminname"] = GetAdminName();

		$mail_content = GetMailContentReplace("mail_content_profile_change_by_admin", GetUserLanguageId($lang_id));//xml

		$subject = $mail_content["subject"];
		$email_to_name = $cont_arr["fname"]." ".$cont_arr["sname"];

		SendMail($email, $site_mail, $subject, $cont_arr, $mail_content, "mail_registration_for_user", "", $email_to_name, $mail_content["site_name"], "text");
	}
	if (!isset($err) || !$err) {	
		if ($old_user_type != $user_type) {			
			if ($user_type == 3) {
				$err = "user_reg_data_changed_4";
			} elseif ($user_type == 2) {
				$err = "user_reg_data_changed_2";
			} else {
				$err = "user_reg_data_changed_1";
			}
		} else {
			$err = "user_reg_data_changed_3";
		}
	}
	
	if (isset($err)) {
		GetErrors($err);
	}

	EditUserForm("edit", $id_user);
	return;
}

function UserMail($id_user='') {
	global $smarty, $dbconn, $config, $lang, $multi_lang;

	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_users.php";

	IndexAdminPage('admin_users');
	CreateMenu('admin_lang_menu');

	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 1;
	$order = (isset($_REQUEST["order"]) && intval($_REQUEST["order"]) > 0) ? intval($_REQUEST["order"]) : 1;
	// sorter
	switch ($order) {
		case "1":
			$order_str = " ASC";
			$smarty->assign("order", 2);
			break;
		default:
			$order_str = " DESC";
			$smarty->assign("order", 1);
			break;
	}
	if (intval($sorter)>0) {
		$sorter_str = "  ORDER BY ";
		switch($sorter) {
			case "1": $sorter_str.=" fname_from"; break;
			case "2": $sorter_str.=" fname_to"; break;
			case "3": $sorter_str.=" mbt.timestamp"; break;
		}
		$sorter_str .= $order_str;
	} else {
		$sorter_str = " mbt.timestamp";
	}
	$smarty->assign("sorter", $sorter);

	$strSQL = " SELECT fname, sname FROM ".USERS_TABLE." WHERE id='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	$user["login"] = $rs->fields[0]." ".$rs->fields[1];

	$strSQL = " SELECT DISTINCT mbt.id, mbt.from_user_id, mbt.to_user_id,
				UNIX_TIMESTAMP(mbt.timestamp) as mail_time, mbt.body, mbt.seen,
				ut.fname as fname_from, ut.sname as sname_from, ut2.fname as fname_to, ut2.sname as sname_to
				FROM ".MAILBOX_MESSAGES_TABLE." mbt
				LEFT JOIN ".USERS_TABLE." ut ON mbt.from_user_id=ut.id
				LEFT JOIN ".USERS_TABLE." ut2 ON mbt.to_user_id=ut2.id
				WHERE (mbt.from_user_id='".$id_user."') OR (mbt.to_user_id='".$id_user."')
				GROUP BY mbt.id $sorter_str";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);
		$mail_log[$i]["from_user_id"] = $row["from_user_id"];
		$mail_log[$i]["edit_from_link"] = $file_name."?sel=edit_user&id_user=".$mail_log[$i]["from_user_id"];
		$mail_log[$i]["to_user_id"] = $row["to_user_id"];
		$mail_log[$i]["edit_to_link"] = $file_name."?sel=edit_user&id_user=".$mail_log[$i]["to_user_id"];
		$mail_log[$i]["mail_time"] = date("d-m-Y H:i", $row["mail_time"]);
		$mail_log[$i]["body"] = stripslashes($row["body"]);
		$mail_log[$i]["from_name"] = $row["fname_from"]." ".$row["sname_from"];
		$mail_log[$i]["to_name"] = $row["fname_to"]." ".$row["sname_to"];
		$rs->MoveNext();
		$i++;
	}

	$smarty->assign("mail_log", $mail_log);
	$smarty->assign("user", $user);
	$smarty->assign("id_user", $id_user);
	$smarty->assign("sel", "user_mail");
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_edit_user_mail.tpl");

}

function UsersImport() {
	global $smarty, $dbconn, $config, $lang;

	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_users.php";

	IndexAdminPage('admin_users');
	CreateMenu('admin_lang_menu');

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_import_users.tpl");
}

function GetHourByDate() {
	header('Content-type: text/html; charset=utf-8');
	global $smarty, $dbconn, $user;
	$id_user = isset($_REQUEST["id_user"]) ? intval($_REQUEST["id_user"]) : "";		
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$day = isset($_REQUEST["day"]) ? intval($_REQUEST["day"]) : "";		
	$year = isset($_REQUEST["year"]) ? intval($_REQUEST["year"]) : "";		
	$month = isset($_REQUEST["month"]) ? intval($_REQUEST["month"]) : "";		
	
	$this_day_start  = adodb_mktime(0, 0, 0, $month, $day, $year);
	$this_day_end  = adodb_mktime(23, 59, 59, $month, $day, $year);
			
	$calendar_event = new CalendarEvent();	
	$reserve_days = $calendar_event->GetReserveDays($id_ad, $id_user);			
	$echo_str="";	
		foreach ($reserve_days AS $period) {			
			if ( $period["start_tmstmp"] <= $this_day_start && $period["end_tmstmp"] >= $this_day_start) {
				$echo_str .= "00:00"." - ".date("H:i",$period["end_tmstmp"])."|";
			}elseif ( $period["start_tmstmp"] >= $this_day_start && $period["end_tmstmp"] <= $this_day_end) {
				$echo_str .= date("H:i",$period["start_tmstmp"])." - ".date("H:i",$period["end_tmstmp"])."|";
			}elseif ( $period["start_tmstmp"] <= $this_day_end && $period["end_tmstmp"] >= $this_day_end) {
				$echo_str .= date("H:i",$period["start_tmstmp"])." - "."23:59"."|";
			}			
		}		
	$echo_str=substr($echo_str,0,strlen($echo_str)-1);	
	echo $echo_str;
		
}

function AddMoneyForm($user_id){
	global $dbconn, $config, $smarty;
	IndexAdminPage('admin_users');
	$cur = GetSiteSettings('site_unit_costunit');
	$strSQL = "	SELECT DISTINCT b.account,
					u.fname, u.sname, u.email
					FROM ".USERS_TABLE." u
					LEFT JOIN ".BILLING_USER_ACCOUNT_TABLE." b ON u.id=b.id_user
					WHERE u.id='$user_id'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->getRowAssoc(false);
	if (!$row["account"]){
		$row["account"] = 0;
	}
	$smarty->assign("details", $row);
	
	if (isset($_REQUEST["balance_field_id"])){
		$smarty->assign("balance_field_id", htmlspecialchars($_REQUEST["balance_field_id"]));
	}
	if (isset($_REQUEST["user_id"])){
		$smarty->assign("user_id", intval($_REQUEST["user_id"]));
	}
	$smarty->assign("cur", $cur);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_users_pay_manual.tpl");
	exit();	
}

function AddMoney(){
	global $dbconn, $config;
	$amount = floatval(str_replace(",", ".", $_REQUEST["amount"]));
	$is_ajax = (isset($_REQUEST["ajax"])) ? 1: 0;
	$to_all = (isset($_REQUEST["to_all"])) ? 1: 0;
	$user_id = intval($_REQUEST["user_id"]);
	$cur = GetSiteSettings('site_unit_costunit');
	
	if ($to_all){
		$strSQL = "SELECT id FROM ".USERS_TABLE." WHERE id != '1' AND id != '2'";
		$rs = $dbconn->Execute($strSQL);
		while (!$rs->EOF) {
			$user_id_arr[] = $rs->fields[0];
			$rs->MoveNext();
		}
		$use_alert = intval($_REQUEST['use_alert']);
	}else{
		$user_id_arr[] = $user_id;
		$use_alert = 1;
	}
	foreach ($user_id_arr AS $key=>$user_id){
		$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='$user_id'";
		$rs = $dbconn->Execute($strSQL);	
		if ($rs->EOF){
			$new_balance = $amount;
			$strSQL = "INSERT INTO ".BILLING_USER_ACCOUNT_TABLE." (id_user, account, account_curr, date_refresh, is_send)
						VALUES('$user_id', '$new_balance', '0', now(), '0');";		
		}else{
		$new_balance = $amount + $rs->fields[0];
		$strSQL = "UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account = '$new_balance', date_refresh = NOW() WHERE id_user='$user_id'";
		}
		$dbconn->Execute($strSQL);
		
		$strSQL = "INSERT INTO ".BILLING_ADDING_BY_ADMIN_TABLE." (id_user, count_curr, currency, date_send) 
					VALUES ('$user_id', '$amount', '$cur', NOW());";
		$dbconn->Execute($strSQL);
		
		if ($use_alert){
			/**
			 * Send mail to user
			 */
			$strSQL = "SELECT email, fname, sname, lang_id FROM ".USERS_TABLE." WHERE id='$user_id'";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$user["lang_id"] = $row["lang_id"];
			$user["email"] = $row["email"];
			$user["fname"] = stripslashes($row["fname"]);
			$user["sname"] = stripslashes($row["sname"]);
		
			$data["name"] = $user["fname"]." ".$user["sname"];
			$data["add_on_account"] = $amount;
			$data["account"] = $new_balance;
		
			$settings = GetSiteSettings(array('site_email', 'site_unit_costunit'));
		
			$strSQL = "SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='$cur' ";
			$rs = $dbconn->Execute($strSQL);
			$data["cur"] = $rs->fields[0];
			
			$mail_content = GetMailContentReplace("mail_content_money_add", GetUserLanguageId($user["lang_id"]));
		
			SendMail($user["email"], $settings["site_email"], $mail_content["subject"], $data, $mail_content, "mail_money_add_table", '', $data["name"] , $mail_content["site_name"], 'text');
		}
	}	
	
	if ($is_ajax){
		echo $new_balance;
	}else{
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_users.php");
		exit;
	}
	
}
?>