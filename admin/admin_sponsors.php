<?php
/**
* Sponsor's announcements: listing and adding
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.5 $ $Date: 2008/10/15 11:58:47 $
**/

include "../include/config.php";
include_once "../common.php";
include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/class.images.php";
include "../include/class.phpmailer.php";
include "../include/functions_mail.php";
include "../include/class.lang.php";
include "../include/class.calendar_event.php";



$auth = auth_user();

if( (!($auth[0]>0)) || (!($auth[4]==1))){
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


$multi_lang = new MultiLang($config, $dbconn);
$lang["users_types"] = GetLangContent("users_types");
$lang["rentals"] = GetLangContent("rentals");

$sel = (isset($_REQUEST["sel"])) ? $_REQUEST["sel"] : "show";
$type = (isset($_REQUEST["type"])) ? $_REQUEST["type"] : "add";

switch($sel){
	case "show"				: ListSponsors($type); break;	
	case "user_rent"		: UserAds(intval($_GET["id_user"])); break;
	case "del_sponsors_ad"	: DeleteAd();break;
	case "show_sp_edit"		: Show_sp_update();break;
	case "list_sp_edit"		: User_sp_update();break;
	case "change_order"		: ChangeOrder();break;
			default			: ListSponsors($type); break;
}

// delete from sponsors table
function DeleteAd() {
	global $smarty, $config, $dbconn;
	
	$id = intval($_REQUEST["id"]);
	$dbconn->Execute("DELETE FROM ".SPONSORS_ADS_TABLE." WHERE id='".$id."' ");
	ListSponsors("list");
	
}

// save checkbox changing: user ads -> insert or delete from sponsor's ads list 
function User_sp_update() {
	global $smarty, $dbconn, $config, $lang;


	$id_user=$_POST["id_user"];
	$str_id = " (";
	$i = 0;
	foreach ($_POST["id_ad"] as $ad_id){
		
		$i++;
		if ($i==sizeof($_POST["id_ad"])){
			$str_id .= $ad_id." ";
		} else {
			$str_id .= $ad_id." , ";
		}
	}
	$str_id .= " )";
	
	
	$rs = $dbconn->Execute("SELECT id_ad FROM ".SPONSORS_ADS_TABLE." WHERE id_ad IN ".$str_id);
	$arr_id = array();
	$i = 0;
	
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);		
		$i++;
		$arr_id[$i] = $row["id_ad"];				
		$rs->MoveNext();
	}	
	
	$rs = $dbconn->Execute("SELECT MAX(order_id) as max_order FROM ".SPONSORS_ADS_TABLE);
	$row = $rs->GetRowAssoc(false);
	$next_order=$row["max_order"]+1;		
	$i = 0;
	foreach ($_POST["id_ad"] as $ad_id){
		
		$i++;
		if ($_POST["issponsor"][$ad_id]) {
			if (!in_array($ad_id,$arr_id)) {
				$dbconn->Execute("INSERT INTO ".SPONSORS_ADS_TABLE." (id_ad, id_user, order_id, status) 
		VALUES ('".$ad_id."', '".$id_user."', '".$next_order."', '1')");				
				$next_order++;
			}
			
		 } 
		else {
			if (in_array($ad_id,$arr_id)) {
				$dbconn->Execute("DELETE FROM ".SPONSORS_ADS_TABLE." WHERE id_ad = ".$ad_id);
			}
			
		}
		
	}
	
	
	echo "<script>location.href='".$config["site_root"]."/admin/admin_sponsors.php?sel=user_rent&type=add&id_user=".$id_user."'</script>";
	
}

// save checkbox changing: sponsor's ads -> show or not show 
function Show_sp_update() {
	global $smarty, $dbconn, $config, $lang;
	
	$str_id = " (";
	$i = 0;
	foreach ($_POST["id_ad"] as $ad_id){
		
		$i++;
		if ($i==sizeof($_POST["id_ad"])){
			$str_id .= $ad_id." ";
		} else {
			$str_id .= $ad_id." , ";
		}
	}
	$str_id .= " )";
	
	
	$rs = $dbconn->Execute("SELECT id FROM ".SPONSORS_ADS_TABLE." WHERE id IN ".$str_id." AND status='1'");
	$arr_id = array();
	$i = 0;
	
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);		
		$i++;		
		$arr_id[$i] = $row["id"];			
		$rs->MoveNext();
	}	

	$rs = $dbconn->Execute("SELECT MAX(order_id) as max_order FROM ".SPONSORS_ADS_TABLE);
	$row = $rs->GetRowAssoc(false);
	$next_order=$row["max_order"]+1;		
	$i = 0;
		
	foreach ($_POST["id_ad"] as $ad_id){		
		$i++;
		if ($_POST["show_sp"][$ad_id]) {
			if (!in_array($ad_id,$arr_id)) {
				$dbconn->Execute("UPDATE ".SPONSORS_ADS_TABLE." SET status='1', order_id='$next_order' WHERE id='$ad_id'");					$next_order++;
			}
		 } 
		else {
			if (in_array($ad_id,$arr_id)) {
				$dbconn->Execute("UPDATE ".SPONSORS_ADS_TABLE." SET status='0'  WHERE id='$ad_id'");
			}
		}
	}	
	
	ListSponsors("list");
	exit;
}

// show all users 
function UsersList($file_name="") {
	global $smarty, $dbconn, $config, $lang;
	
	$lang["users_types"] = GetLangContent("users_types");
	$lang["rentals"] = GetLangContent("rentals");
	$param = $file_name."?type=add&amp;";
	
	$letter = (isset($_REQUEST["letter"])) ? (($_REQUEST["letter"] != "") ? strval($_REQUEST["letter"]) : "*") : "*";
	$sorter = (isset($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : "2";
	$search = (isset($_REQUEST["search"])) ? strval($_REQUEST["search"]) : "";
	$s_type = (isset($_REQUEST["s_type"])) ? intval($_REQUEST["s_type"]) : "1";
	$s_stat = (isset($_REQUEST["s_stat"])) ? intval($_REQUEST["s_stat"]) : "";
	$order = (isset($_REQUEST["order"])) ? intval($_REQUEST["order"]) : "1";	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	
	// search
	$search_str = "";
	if(strval($search)){
		$search = strip_tags($search);
		switch($s_type){
			case "1": $search_str=" AND u.login LIKE '%".$search."%'"; break;
			case "2": $search_str=" AND u.fname LIKE '%".$search."%'"; break;
			case "3": $search_str=" AND u.sname LIKE '%".$search."%'"; break;
			case "4": $search_str=" AND u.email LIKE '%".$search."%'"; break;
		}
	}
	$smarty->assign("search", $search);
	$smarty->assign("s_type", $s_type);
	$smarty->assign("lang", $lang);

	// letter
	if(strval($letter) != "*") {
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
	switch ($order){
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
	if(intval($sorter)>0){
		switch($sorter) {
			//case "1": $sorter_str.=" u.login"; break;
			case "2": $sorter_str.=" u.fname"; break;
			case "3": $sorter_str.=" u.email"; break;
			case "4": $sorter_str.=" u.date_registration"; break;
			case "5": $sorter_str.=" u.date_last_seen"; break;
			//case "6": $sorter_str.=" u.status"; break;
			case "7": $sorter_str.=" u.active"; break;
			case "8": $sorter_str.=" u.user_type"; break;
			case "9": $sorter_str.=" ads_user $order_str, u.fname $order_str"; break;
			case "10": $sorter_str.=" mailbox_user $order_str, u.fname $order_str"; break;
		}
		$sorter_str .= ($sorter != 9 && $sorter != 10) ? $order_str : "";
	} else {
		$sorter_str .= " u.fname";
	}
	$smarty->assign("sorter", $sorter);

	if($letter_str){
		$where_str = "where ".$letter_str." ";
	}elseif($search_str){
		$where_str = "where u.id>0 ".$search_str." ";
	}else{
		$where_str = "";
	}
	$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u  ".$where_str;
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	if ($num_records>0) {
		$rows_num_page = GetSiteSettings('admin_rows_per_page');
		// page
		$lim_min = ($page-1)*$rows_num_page;
		$lim_max = $rows_num_page;
		$limit_str = ($sorter == 9) ? "" : " limit ".$lim_min.", ".$lim_max;
		$strSQL = "	SELECT DISTINCT mu.user_id as mailbox_user, ra.id_user as ads_user,
					u.id, u.fname, u.sname, u.status, u.access, u.login, u.email,
					DATE_FORMAT(u.date_last_seen, '".$config["date_format"]."') as date_last_seen,
					DATE_FORMAT(u.date_registration, '".$config["date_format"]."') as date_registration,
					u.root_user, u.guest_user, u.active, u.user_type
					FROM ".USERS_TABLE."  u
					LEFT JOIN ".MAILBOX_USER_LISTS_TABLE." mu ON mu.user_id=u.id
					LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=u.id
 					".$where_str." ".$sorter_str.$limit_str;
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		if($rs->RowCount()>0){
			while(!$rs->EOF){
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
				if ( $user[$i]["root_user"] != 1 ) {
					$user[$i]["del_link"] = "./admin_users.php?sel=delete_user&id_user=".$row["id"];
				}
				//rent link
				$user[$i]["rent_link"] = "";
				$strSQL = "SELECT DISTINCT id FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[$i]["id"]."' ";
				$res = $dbconn->Execute($strSQL);
				if ( $res->RowCount()>0 ) {
					$user[$i]["rent_link"] = $file_name."?sel=user_rent&type=add&id_user=".$user[$i]["id"]."&pageR=".$page;
					$user[$i]["rent_count"] = $res->RowCount();
				}else{
					$user[$i]["rent_count"] = 0;
				}
				//mailbox link
				$user[$i]["mail_link"] = ($row["mailbox_user"]) ? $file_name."?sel=user_mail&amp;id_user=".$user[$i]["id"]." " : "";
				$rs->MoveNext();
				$i++;
			}
			/**
			 * сортировка по объявлениям
			 */
			if ($sorter == 9) {
				function cmp_desc($a, $b) {
					if ($a["rent_count"] == $b["rent_count"]) {
				      return 0;
				    }
				    return ($a["rent_count"] > $b["rent_count"]) ? -1 : 1;
				}
				function cmp_asc($a, $b) {
					if ($a["rent_count"] == $b["rent_count"]) {
				   	   return 0;
				   	}
				   	return ($a["rent_count"] < $b["rent_count"]) ? -1 : 1;
				}

				if ($order_str == " ASC") {
					usort($user, "cmp_asc");
				} else {
					usort($user, "cmp_desc");
				}
				$max_number = $lim_min+$lim_max;
				$max_number = ($num_records < $max_number) ? $num_records : $max_number;
				for ($i=$lim_min; $i<$max_number; $i++ ) {
					$res_user[] = $user[$i];
				}
				$user = $res_user;
			}

			$smarty->assign("user", $user);
			$smarty->assign("page", $page);
			$smarty->assign("rows_num_page", $rows_num_page);
			$param = $param."letter=".$letter."&search=".$search."&s_type=".$s_type."&s_stat=".$s_stat."&sorter=".$sorter."&order=".$order."&";
			$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page) );
		}
	}
	//$smarty->assign("home", $lang_content["home"]);
	$smarty->assign("file_name", $file_name);
	
}

//return all sponsor's ads
function ADSList($file_name="") {
	global $smarty, $dbconn, $config, $lang;
	
	$lang["users_types"] = GetLangContent("users_types");
	$lang["rentals"] = GetLangContent("rentals");
	
	$smarty->assign("lang", $lang);
	$param = $file_name."?type=list&amp;";	
	$ads = GetSponsorAds($param);
		
	$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));
	$smarty->assign("ads",$ads);
	
	
	
}


function ListSponsors($type="",$page_from=1) {
	global $smarty, $dbconn, $config;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_sponsors.php";
		
	
		

	IndexAdminPage('admin_sponsors');	
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"])) ? $_REQUEST["page"] : "";
	$page = (strval($page) == "" || strval($page) == "0") ? $page_from : intval($page);

	
	//$smarty->assign("files", $files);
	switch ($type) {
		case "list":ADSList($file_name);
		break;
		case "add" :UsersList($file_name);break;
		default: UsersList($file_name);break;
	}
		

	//$param = $file_name."?sel=list_upload&type_upload=".$type_upload."&";
	//$smarty->assign("links", GetLinkArray($num_records, $page, $param, $settings["admin_upload_numpage"]) );
	$smarty->assign("type", $type);

	
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_sponsors.tpl");
}

// return ads user with id_user
function UserAds($id_user='') {
	global $smarty, $dbconn, $config, $lang, $multi_lang;
	
	$lang["users_types"] = GetLangContent("users_types");
	$lang["rentals"] = GetLangContent("rentals");

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_sponsors.php";
	
	IndexAdminPage('admin_sponsors');
	CreateMenu('admin_lang_menu');

	$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));

	$strSQL = " SELECT fname, sname FROM ".USERS_TABLE." WHERE id='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	$smarty->assign("user_login", $rs->fields[0]." ".$rs->fields[1]);
	

	$pageR = (isset($_REQUEST["pageR"])) ? intval($_REQUEST["pageR"]): 0;
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : "";
	$smarty->assign("pageR" , $pageR);
	
	$param = $file_name."?sel=user_rent&amp;type=add&amp;id_user=".$id_user."&amp;pageR=".$pageR."&amp;";
	$ads = GetUserAds($id_user, $file_name, $param, -1);

	if (count($ads) > 0 ){
		$smarty->assign("ads",$ads);
	} else {
		UsersList("edit", $id_user);
		exit;
	}
	
	
	$smarty->assign("id_user", $id_user);
	
	$smarty->assign("type", $_REQUEST["type"]);
	
	
	
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_users_rent_sponsor.tpl");
	exit;
}

// change order of showing
function ChangeOrder() {
	global $smarty, $dbconn;
	
	$ads_move_up= (isset($_REQUEST["ads_move_up"]) && !empty($_REQUEST["ads_move_up"])) ? intval($_REQUEST["ads_move_up"]) : "";
	$ads_move_down= (isset($_REQUEST["ads_move_down"]) && !empty($_REQUEST["ads_move_down"])) ? intval($_REQUEST["ads_move_down"]) : "";
	if ($ads_move_down)	{
		$ads_move = $ads_move_down;
	}
	if ($ads_move_up)	{
		$ads_move = $ads_move_up;
	}
	
	$sql = "SELECT order_id,status FROM ".SPONSORS_ADS_TABLE.
					" WHERE id='".$ads_move."'";
	$rs = $dbconn->Execute($sql);
	$order_id = $rs->fields[0];
	$status = $rs->fields[1];
	
	if ($ads_move_up)	{
		$sql = "SELECT id, order_id FROM ".SPONSORS_ADS_TABLE." ".
					 "WHERE order_id<'".$order_id."' AND order_id>'0' AND status='".$status."' 
					 ORDER BY order_id DESC";
	}
	if ($ads_move_down)	{
		$sql = "SELECT id, order_id FROM ".SPONSORS_ADS_TABLE." ".
					 "WHERE order_id>'".$order_id."' AND order_id>'0' AND status='".$status."' 
					 ORDER BY order_id ASC";
	}
	$rs = $dbconn->Execute($sql);
	if ($rs->RowCount() > 0) {
		$neighbour_row = $rs->GetRowAssoc( false );

		$sql = "UPDATE ".SPONSORS_ADS_TABLE." SET ".
		 			 "order_id='".$neighbour_row["order_id"]."'
				 	  WHERE id='".$ads_move."'";
		 
		$dbconn->Execute($sql);

		$sql = "UPDATE ".SPONSORS_ADS_TABLE." SET ".
		 			 "order_id='".$order_id."'
				 	  WHERE id='".$neighbour_row["id"]."'";
		 
		$dbconn->Execute($sql);
	}
	
	
	ListSponsors("list",intval($_REQUEST["page"]));
}

?>