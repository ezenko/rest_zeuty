<?php
/**
* Users black list management functions (view and delete from blacklist)
* @see viewprofile.php - add to hotlist
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/13 07:58:32 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";

include "./include/functions_xml.php";

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

if ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	$sel = $_POST["sel"]?$_POST["sel"]:$_GET["sel"];
	if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
		AlertPage();
		exit;
	} elseif ($mode == 0) {
		AlertPage(GetRightModulePath(__FILE__));
		exit;
	}
	switch ($sel){
		case "del": 	DelFromHotList(); break;
		default: 		HotlistTable();
	}
}

function HotListTable(){
	global $config, $smarty, $dbconn, $user, $lang, $REFERENCES;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "hotlist.php";

	IndexHomePage('hotlist', 'homepage');

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$smarty->assign("submenu", "hotlistedthemrent");

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = "SELECT id_friend FROM ".HOTLIST_TABLE." WHERE id_user='".$user[0]."' ORDER BY id_friend";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$id_arr[$i] = $row["id_friend"];
		$rs->MoveNext();
		$i++;
	}

	$num_records = count($id_arr);

	$users_numpage = GetSiteSettings("users_num_page");

	$lim_min = ($page-1)*$users_numpage;
	$lim_max = $users_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$sorter = intval($_GET["sorter"]);
	$smarty->assign("sorter", $sorter);
	$sorter_order = intval($_GET["order"]);

	$sort_arr = getRealtySortOrder($sorter, $sorter_order, "user");
	$sorter_str = " GROUP by a.id ORDER BY ".$sort_arr["sorter_str"].$sort_arr["sorter_order"];

	$sorter_tolink = $sort_arr["sorter_tolink"];
	$sorter_topage = $sort_arr["sorter_topage"];
	$smarty->assign("order_icon", $sort_arr["order_icon"]);

	if ($num_records>0){
		$where_str = "WHERE a.id IN (".implode(",", $id_arr).")";

		$strSQL = "	SELECT DISTINCT a.id, a.fname, a.date_birthday, DATE_FORMAT(a.date_last_seen,'".$config["date_format"]."')  as date_last_login, e.id_user as session, DATE_FORMAT(ht.datenow, '".$config["date_format"]."') as date_hotlisted, a.user_type
					FROM ".USERS_TABLE." a
					LEFT JOIN ".ACTIVE_SESSIONS_TABLE." e on a.id=e.id_user
					LEFT JOIN ".HOTLIST_TABLE." ht ON (a.id=ht.id_friend AND ht.id_user='".$user[0]."')
					".$where_str." and a.status='1' AND a.active='1' ".$sorter_str.$limit_str;
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$users_list[$i]["id"] = $row["id"];
			$users_list[$i]["login"] = $row["fname"];
			$users_list[$i]["age"] = AgeFromBDate($row["date_birthday"]);
			$users_list[$i]["date_last_login"] = $row["date_last_login"];
			$users_list[$i]["status"] = $row["session"]? "online": "offline";
			$users_list[$i]["number"] = ($page-1)*$users_numpage+($i+1);
			$users_list[$i]["del_link"] = $file_name."?sel=del&id_user=".$users_list[$i]["id"];
			$users_list[$i]["date_added"] = $row["date_hotlisted"];
			$users_list[$i]["user_type"] = $row["user_type"];

			$suffix = "&id=".$users_list[$i]["id"];

			$users_list[$i]["contact_link"] = "./contact.php?sel=fs".$suffix;

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
			if ($row["user_type"]==2) {
				$strSQL_logo = "SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND admin_approve='1'";
				$rs_logo = $dbconn->Execute($strSQL_logo);
				if ( ($rs_logo->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/".$rs_logo->fields[0])) ){
					$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_logo->fields[0];
				} else {
					$users_list[$i]["pict_path"] = "";
				}
			} else {
				$strSQL_pict = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND status='1' AND admin_approve='1'";
				$rs_pict = $dbconn->Execute($strSQL_pict);
				if ( ($rs_pict->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/thumb_".$rs_pict->fields[0])) ){
					$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/thumb_".$rs_pict->fields[0];
				} else {
					$users_list[$i]["pict_path"] = "";
				}
			}

			if ($users_list[$i]["pict_path"] == ""){
				$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$icon_name;
			}


			$rs->MoveNext();
			$i++;
		}

		$smarty->assign("order_link", "&order=".$sorter_topage."&page=$page");
		$smarty->assign("order_active_link", "&order=".$sorter_tolink."&page=$page");

		$param = $file_name."?sorter=".$sorter."&amp;order=".$sorter_topage."&";

		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $users_numpage));
		$smarty->assign("lists", 1);
		$smarty->assign("users_list", $users_list);
	}else{
		$smarty->assign("empty",'1');
	}

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/hotlist_table.tpl");
	exit();
}

function DelFromHotList() {
	global $config, $smarty, $dbconn, $user;
	$id_friend = intval($_GET["id_user"]);
	$strSQL = "SELECT id FROM ".HOTLIST_TABLE." WHERE id_user='".$user[0]."' AND id_friend='".$id_friend."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$dbconn->Execute("DELETE FROM ".HOTLIST_TABLE." WHERE id='".$rs->fields[0]."' ");
	}
	HotlistTable();
	exit;
}
?>