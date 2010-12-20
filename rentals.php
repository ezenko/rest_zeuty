<?php
/**
* Users listings management (add, edit, delete)
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.14 $ $Date: 2009/01/13 14:39:11 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

include "./include/class.images.php";
include "./include/class.lang.php";
include "./include/class.calendar_event.php";


$user = auth_index_user();

$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

session_register("step_1");
session_register("step_3");
session_register("step_4");
session_register("step_5");
session_register("from_edit");

$smarty->assign("user_type", $user[10]);

$multi_lang = new MultiLang($config, $dbconn);

if ($user[3] != 1) {
	$sel = (isset($_POST["sel"])) ? $_POST["sel"] : (isset($_GET["sel"]) ? $_GET["sel"] : '');
} else {
	$sel = '';
}
if ( $user[3]==1 || ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}
$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
switch($sel){

	case "upload_plan":		UploadPlan($_GET["back"]); break;
	case "plan_view":		PlanView(); break;

	case "my_ad":			MyAd(); break;

	case "add_rent":		EditProfile("step_1"); break;
	case "step_1":			UserAd("step_1"); break;
	case "step_3":			UserAd("step_3"); break;
	case "step_4":			UserAd("step_4"); break;
	case "step_5":			UserAd("step_5"); break;
	case "step_6":			UserAd("step_6"); break;
	case "step_7":			UserAd("step_7"); break;
	case "step_8":			UserAd("step_8"); break;
	case "listing_position":UserAd("listing_position"); break;
	case "upload_video":	UserAd("upload_video"); break;
	case "calendar":		CalendarAd("add_event");break;
	case "add_event":		AddEvent();break;
	case "delete_event":	DeleteEvent();break;
	case "edit_event":		EditEvent();break;	
	case "edited_event":	EditedEvent();break;	
	

	case "save_ad":			SaveProfile("save_ad");break;
	case "1":				SaveProfile("1"); break;
	case "4":				SaveProfile("4"); break;
	case "5":				SaveProfile("5"); break;
	case "6":				SaveProfile("6"); break;
	case "7":				SaveProfile("7"); break;
	case "8":				SaveProfile("8"); break;
	case "finish":			SaveProfile("finish"); break;

	case "upload_photo":	UploadPhoto($_GET["back"]); break;
	case "js_upload":		JsUpload();break;
	case "upload_view":		UploadView(); break;
	case "upload_del":		UploadDelete(); break;
	case "upload_plan":		UploadPlan($_GET["back"]); break;
	case "plan_view":		PlanView(); break;
	case "plan_del":		PlanDelete(); break;
	case "upload_video_save": UploadVideo($_GET["back"]); break;
	case "upload_deactivate": UploadActivate(0); break;
	case "upload_activate": UploadActivate(1); break;
	case "edit_comment": 	UploadEditComment(); break;

	case "list_ads":		ListUserAds(); break;
	case "del":				DelUserAd(); break;
	case "deactivate_ad":	MyAd($_GET["id_ad"],'deactivate'); break;
	case "activate_ad":		MyAd($_GET["id_ad"],'activate'); break;
	case "de_sold_leased":	MyAd($_GET["id_ad"],'de_sold_leased'); break;
	case "sold_leased":		MyAd($_GET["id_ad"],'sold_leased'); break;
	case "edit":			UserAd(); break;
	case "get_bonus":		GetBonus(); break;
	case "db_operation":	DbOperation(); break;
	
	case "get_free_days":	GetFreeDays(); break;
	case "get_hour_by_date":GetHourByDate();break;
	
	case "file_up": 		FileSequence("up"); break;
	case "file_down": 		FileSequence("down"); break;
	
	default:				ListUserAds(); break;
}


function DbOperation() {
	global $smarty, $config, $dbconn, $user, $lang, $multi_lang;

	$smarty->assign("submenu", "edit_rentals");
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexHomePage('rentals','homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	$strSQL = "SELECT count(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);

	$num_records = $rs->fields[0];
	$smarty->assign("num_records", $num_records);

	$smarty->assign("add_rent_link", $file_name."?sel=add_rent");
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/rentals_operation.tpl");
	exit;
}

function GetBonus() {
	global $config, $dbconn, $user;

	$id_ad = (isset($_GET["id_ad"])) ? intval($_GET["id_ad"]) : 0;
	$strSQL = "	SELECT type FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' AND id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	$type = $rs->fields[0];

	$percent = intval(GetPercent($user[0], $id_ad, $type));
	$bonus = GetBonusSettings();

	$count_curr = GetBonusForPercent($percent, $bonus);

	if ($count_curr==0){
		ListUserAds("small_percent");
		exit;
	}

	/**
	 * save $id_ad in id_product field in case that user could not get bonus for one listing twice
	 */
	$strSQL = "INSERT INTO ".BILLING_REQUESTS_TABLE." (id_user, count_curr, currency, date_send, status, paysystem, id_product)".
			  "VALUES ('".$user[0]."', '".$count_curr."', '".GetSiteSettings("site_unit_costunit")."', now(), 'approve', 'bonus', '$id_ad')";
	$dbconn->Execute($strSQL);

	$strSQL = "SELECT id FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0){
		$strSQL = "UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account=account+".$count_curr.", date_refresh=now() WHERE id_user='".$user[0]."' ";
		$dbconn->Execute($strSQL);
	} else {
		$strSQL = "INSERT INTO ".BILLING_USER_ACCOUNT_TABLE." (id_user,account, date_refresh) VALUES('".$user[0]."','".$count_curr."', now()) ";
		$dbconn->Execute($strSQL);
	}
	$strSQL = "UPDATE ".RENT_ADS_TABLE." SET status='1' WHERE id='".$id_ad."' ";
	$dbconn->Execute($strSQL);

	ListUserAds("bonus_recieved");
	exit;
}

function MyAd ($id_ad='', $par='') {
	global $smarty, $config, $dbconn, $user, $lang, $multi_lang, $REFERENCES;
	$sq_meters = GetSiteSettings('sq_meters');
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));
	if (strlen($par)) {
		$id_ad = intval($id_ad);		
		switch ($par){
			case "activate":
				ListingActivate($id_ad);
				break;
			case "deactivate":
				$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET status='0' WHERE id='".$id_ad."' ");
				break;
			case "sold_leased":
				$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET sold_leased_status='1' WHERE id='".$id_ad."'");	
				break;
			case "de_sold_leased":
				$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET sold_leased_status='0' WHERE id='".$id_ad."'");	
				break;	
		}
		header("Location: ".$config["server"].$config["site_root"]."/rentals.php?sel=listing_position&id_ad=$id_ad");
		exit();
	}

	$choise = isset($_POST["choise"]) ? $_POST["choise"] : "";
	if (!(intval($id_ad)>0)){
		$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
		if (!$id_ad) {
			ListUserAds();
			exit();
		}
	}
	$smarty->assign("submenu", "edit_rentals");		
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
	$smarty->assign("use_ads_activity_period", GetSiteSettings("use_ads_activity_period"));
	$smarty->assign("ads_activity_period", GetSiteSettings("ads_activity_period"));
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexHomePage('rentals','homepage');
	if ($user[8]=='1'){
		$smarty->assign("active_user", 1);
	} else {
		$smarty->assign("active_user", 0);
	}
	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	$strSQL = "	SELECT 	a.id, a.id_user, a.type, DATE_FORMAT(a.movedate, '".$config["date_format"]."' ) as movedate,
				a.people_count, a.comment, a.headline, 
				a.with_photo, a.with_video, a.date_unactive, 				 
				urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress,
				count.name as country_name, reg.name as region_name, cit.name as city_name, ut.login, ut.user_type, 
				hlt.id_friend, blt.id_enemy, tsat.type as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
				a.status, a.sold_leased_status, sp.status as spstatus, ft.id as featured
				FROM ".RENT_ADS_TABLE." a
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON a.id=urlt.id_ad
				LEFT JOIN ".COUNTRY_TABLE." count ON count.id=urlt.id_country
				LEFT JOIN ".REGION_TABLE." reg ON reg.id=urlt.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=urlt.id_city
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=a.id_user
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=a.id
				LEFT JOIN ".HOTLIST_TABLE." hlt on a.id_user=hlt.id_friend and hlt.id_user='".$user[0]."'
				LEFT JOIN ".BLACKLIST_TABLE." blt on a.id_user=blt.id_enemy and blt.id_user='".$user[0]."'
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=a.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=a.id
				WHERE a.id='".$id_ad."' ";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$profile["id"] = $row["id"];
	$profile["type"] = $row["type"];

	$profile["subway_name"] = '';
	
	$profile["status"] = $row["status"];
	$profile["sold_leased_status"] = $row["sold_leased_status"];
	$profile["issponsor"] = $row["spstatus"];
	$profile["id_user"] = $row["id_user"];
	$profile["user_type"] = $row["user_type"];
	
	if ($profile["type"] == 2){			
		$calendar_event = new CalendarEvent();
		$profile["reserve"] = $calendar_event->GetEmptyPeriod($profile["id"], $profile["id_user"]);	
	}

	$profile["topsearched"] = $row["topsearched"];
	$profile["featured"] = $row["featured"];	
	$profile["date_unactive"] =$row["date_unactive"];	
	if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
		$profile["show_topsearch_icon"] = true;
		$profile["topsearch_date_begin"] = $row["topsearch_date_begin"];
	}

	/**
	 * Get listing completion bonus
	 */
	$get_bonus = 0;
	if (GetSiteSettings("use_listing_completion_bonus")) {
		$max_bonus_cnt = GetSiteSettings("listing_completion_bonus_number");
		/**
		 * Check if user could get bonus for the current listing completion
		 */
		$strSQL = " SELECT COUNT(id) AS cnt FROM ".BILLING_REQUESTS_TABLE." WHERE paysystem='bonus' AND id_user='".$user[0]."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0] == 0) {
			$get_bonus = 1;
		} else {
			if ($rs->fields[0] >= $max_bonus_cnt) {
				$get_bonus = 0;
			} else {
				$strSQL = "SELECT COUNT(id) AS id FROM ".BILLING_REQUESTS_TABLE." WHERE ".
						  "paysystem='bonus' AND id_user='".$user[0]."' AND id_product='$id_ad'";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0] == 0) {
					$get_bonus = 1;
				} else {
					$get_bonus = 0;
				}
			}
		}
		if ($get_bonus) {
			$percent = intval(GetPercent($user[0], $profile["id"], $profile["type"]));
			$smarty->assign("percent", $percent);

			$bonus = GetBonusSettings();
			$smarty->assign("bonus", $bonus);
			$smarty->assign("can_recieve", GetBonusForPercent($percent, $bonus));
		}
	}
	$smarty->assign("get_bonus", $get_bonus);

	$use_private_person_ads_limit = GetSiteSettings("use_private_person_ads_limit");
	if ($use_private_person_ads_limit && $user[10] == 1) {
		//if use limit and user is private person
		$smarty->assign("private_person_ads_limit", GetSiteSettings("private_person_ads_limit"));
		$smarty->assign("user_ads_cnt", GetUserAdsNumber($user[0]));
	}

	$profile["zip_code"] = $row["zip_code"];
	$profile["street_1"] = stripslashes($row["street_1"]);
	$profile["street_2"] = stripslashes($row["street_2"]);
	$profile["adress"] = stripslashes($row["adress"]);
	if ($row["movedate"] != '00.00.0000'){
		$profile["movedate"] = $row["movedate"];
	}

	$profile["people_count"] = $row["people_count"];

	$profile["with_photo"] = $row["with_photo"];
	$profile["with_video"] = $row["with_video"];

	$profile["comment"] = stripslashes($row["comment"]);
	$profile["headline"] = stripslashes($row["headline"]);
	if ($config["lang_ident"]!='ru') {
		$profile["country_name"] = RusToTranslit($row["country_name"]);
		$profile["region_name"] = RusToTranslit($row["region_name"]);
		$profile["city_name"] = RusToTranslit($row["city_name"]);
	} else {
		$profile["country_name"] = $row["country_name"];
		$profile["region_name"] = $row["region_name"];
		$profile["city_name"] = $row["city_name"];
	}

	$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {

			if ($arr["spr_match_table"] != "") {
			/**
			 * ������������ �������������� ��������
			 */
				$lang_add = 2; //�.�. ��������� ���������� �������
				//�������� ����, ���� ����� �����
				$profile[$arr["key"]."_match"] = GetResArrName($arr["spr_match_table"], $profile["id_user"], $profile["id"], $arr["spr_table"], $arr["val_table"], $lang_add);
				//�������� ����
				$profile[$arr["key"]] = GetResArrName($arr["spr_user_table"], $profile["id_user"], 0, $arr["spr_table"], $arr["val_table"]);
			} else {
				$profile[$arr["key"]] = GetResArrName($arr["spr_user_table"], $profile["id_user"], $profile["id"], $arr["spr_table"], $arr["val_table"]);
			}
		}
	}
	/**
	 * account info
	 */
		$profile["account"] = GetAccountTableInfo($user[0]);
		
		if ($profile["user_type"] == 3){
			
			$strSQL = "SELECT aoc.id_company, uf.photo_path, uf.approve FROM ".AGENT_OF_COMPANY_TABLE." aoc 
					LEFT JOIN ".USER_PHOTOS_TABLE." uf ON aoc.id_agent = uf.id_user WHERE aoc.id_agent = '".$profile["id_user"]."' AND aoc.approve = '1'";
			
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0] > 0){				
				$company_data = GetAccountTableInfo($rs->fields[0]);				
				$profile["agent_photo_path"]=$config["server"].$config["site_root"]."/uploades/photo/".addslashes($rs->fields[1]);
				$profile["agent_photo_admin_approve"] = 1;			
				if (GetSiteSettings("use_photo_approve")){				
					$profile["agent_photo_admin_approve"] = $rs->fields[2];				
				}				
				$smarty->assign("company_data", $company_data);			
			}	
		}
	
	/**
	 * photo
	 */
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve, status FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$profile["id"]."' AND upload_type='f' ORDER BY sequence";
	$rs_img = $dbconn->Execute($strSQL_img);
	$j = 0;
	$profile["photo_approve_total"] = 0;
	if ($rs_img->fields[0]>0){
		while(!$rs_img->EOF){
			$row_img = $rs_img->GetRowAssoc(false);
			$photo_folder = GetSiteSettings('photo_folder');
			$profile["photo_id"][$j] = $row_img["photo_id"];
			$profile["photo_path"][$j] = $row_img["upload_path"];
			$profile["photo_user_comment"][$j] = $row_img["user_comment"];
			$profile["photo_admin_approve"][$j] = $row_img["admin_approve"];
			$profile["photo_approve_total"] += $profile["photo_admin_approve"][$j];
			$profile["photo_status"][$j] = $row_img["status"];

			$path = $config["site_path"].$photo_folder."/".$profile["photo_path"][$j];
			$thumb_path = $config["site_path"].$photo_folder."/thumb_".$profile["photo_path"][$j];

			if(file_exists($path) && strlen($profile["photo_path"][$j])>0){
				$profile["photo_file"][$j] = ".".$photo_folder."/".$profile["photo_path"][$j];
				$profile["photo_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$profile["photo_id"][$j]."&type_upload=f";
				$sizes = getimagesize($path);
				$profile["photo_width"][$j]  = $sizes[0];
				$profile["photo_height"][$j]  = $sizes[1];
			}
			if(file_exists($thumb_path) && strlen($profile["photo_path"][$j])>0)
			$profile["thumb_file"][$j] = ".".$photo_folder."/thumb_".$profile["photo_path"][$j];
			if(!file_exists($path) || !strlen($profile["photo_path"][$j])){
				$profile["photo_file"][$j] = ".".$photo_folder."/".$default_photo;
				$profile["thumb_file"][$j] = $profile["photo_file"][$j];
			}
			$rs_img->MoveNext();
			$j++;
		}
	}
	/**
	 * video
	 */
	$strSQL_video = "SELECT id as video_id, upload_path, user_comment, admin_approve, status FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$profile["id"]."' AND upload_type='v' ORDER BY sequence";
	
	$rs_video = $dbconn->Execute($strSQL_video);
	$j = 0;
	if ($rs_video->RowCount() > 0){		
		
		$settings = GetSiteSettings(array('default_video_icon', 'video_folder', 'use_ffmpeg'));		
		$profile["video_approve_total"] = 0;
		while(!$rs_video->EOF){			
			$row_video = $rs_video->GetRowAssoc(false);			
			$profile["video_id"][$j] = $row_video["video_id"];
			if ($settings["use_ffmpeg"] == 1) {				
				$flv_name = explode('.', $row_video["upload_path"]);
				
				if (file_exists($config["site_path"].$settings["video_folder"]."/".$flv_name[0].".flv")) {															
					$profile["is_flv"][$j] = 1;
					$smarty->assign("is_flv", 1);
					$profile["video_icon"][$j] = $flv_name[0]."1.jpg";
					$profile["video_path"][$j] = $flv_name[0].".flv";
					$size = explode('x', GetSiteSettings("flv_output_dimension"));
					$profile["width"][$j] = $size[0];			
					$profile["height"][$j] = $size[1];
				} else {					
					$profile["video_path"][$j] = $row_video["upload_path"];
					$profile["video_icon"][$j] = $settings["default_video_icon"];
					$profile["width"][$j] = 320;			
					$profile["height"][$j] = 240;
				}
			} else {				
				$profile["video_path"][$j] = $row_video["upload_path"];
				$profile["video_icon"][$j] = $settings["default_video_icon"];
				$profile["is_flv"][$j] = 0;
				$profile["width"][$j] = 320;			
				$profile["height"][$j] = 240;
			}			
			$profile["video_user_comment"][$j] = addslashes($row_video["user_comment"]);
			$profile["video_admin_approve"][$j] =$row_video["admin_approve"];
			$profile["video_approve_total"] += $profile["video_admin_approve"][$j];
			
			$profile["video_status"][$j] = $row_video["status"];

			$path = $config["server"].$config["site_root"].$settings["video_folder"]."/";						
			if (file_exists($config["site_path"].$settings["video_folder"]."/".$profile["video_path"][$j])){				
				
				$profile["video_file"][$j] = $path.$profile["video_path"][$j];
				$profile["video_icon"][$j] = $path.$profile["video_icon"][$j];
				$profile["video_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$profile["video_id"][$j]."&is_flv=".$profile["is_flv"][$j]."&type_upload=v";

			}
			$rs_video->MoveNext();
			$j++;
		}		
	}
	/**
	 * plan
	 */
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve, status FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$profile["id"]."' AND id_user='".$user[0]."' ORDER BY sequence";
	$rs_img = $dbconn->Execute($strSQL_img);
	$j = 0;
	$profile["plan_approve_total"] = 0;
	if ($rs_img->fields[0]>0){
		while(!$rs_img->EOF){
			$row_img = $rs_img->GetRowAssoc(false);
			$photo_folder = GetSiteSettings('photo_folder');
			$profile["plan_photo_id"][$j] = $row_img["photo_id"];
			$profile["plan_photo_path"][$j] = $row_img["upload_path"];
			$profile["plan_user_comment"][$j] = $row_img["user_comment"];
			$profile["plan_admin_approve"][$j] = $row_img["admin_approve"];
			$profile["plan_approve_total"] += $profile["plan_admin_approve"][$j];
			$profile["plan_status"][$j] = $row_img["status"];

			$path = $config["site_path"].$photo_folder."/".$profile["plan_photo_path"][$j];
			$thumb_path = $config["site_path"].$photo_folder."/thumb_".$profile["plan_photo_path"][$j];
			if(file_exists($path) && strlen($profile["plan_photo_path"][$j])>0){
				$profile["plan_file"][$j] = ".".$photo_folder."/".$profile["plan_photo_path"][$j];
				$profile["plan_view_link"][$j] = "./".$file_name."?sel=plan_view&id_file=".$profile["plan_photo_id"][$j]."&type_upload=f";
				$sizes = getimagesize($path);
				$profile["plan_width"][$j]  = $sizes[0];
				$profile["plan_height"][$j]  = $sizes[1];
			}
			if(file_exists($thumb_path) && strlen($profile["plan_photo_path"][$j])>0)
			$profile["plan_thumb_file"][$j] = ".".$photo_folder."/thumb_".$profile["plan_photo_path"][$j];
			if(!file_exists($path) || !strlen($profile["plan_photo_path"][$j])){
				$profile["plan_file"][$j] = ".".$photo_folder."/".$default_photo;
				$profile["plan_thumb_file"][$j] = $profile["plan_file"][$j];
			}
			$rs_img->MoveNext();
			$j++;
		}
	}
	if ($profile["type"] == "1" || $profile["type"] == "3") {

		$strSQL_payment = " SELECT min_payment, max_payment, auction, min_deposit, max_deposit,
							min_live_square, max_live_square, min_total_square, max_total_square,
							min_land_square, max_land_square, min_floor,  max_floor, floor_num, subway_min,
							min_year_build, max_year_build
							FROM ".USERS_RENT_PAYS_TABLE."
							WHERE id_ad='".$profile["id"]."' AND id_user='".$profile["id_user"]."' ";
		$rs_payment = $dbconn->Execute($strSQL_payment);
		$row_payment = $rs_payment->GetRowAssoc(false);
		$profile["min_payment"] = PaymentFormat($row_payment["min_payment"]);
		$profile["min_payment_show"] = FormatPrice($profile["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["max_payment"] = PaymentFormat($row_payment["max_payment"]);
		$profile["max_payment_show"] = FormatPrice($profile["max_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["act_status"] = ($profile["max_payment"] <= 0) ? 0 : 1;
		$profile["auction"] = $row_payment["auction"];
		$profile["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
		$profile["min_deposit_show"] = FormatPrice($profile["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["max_deposit"] = PaymentFormat($row_payment["max_deposit"]);
		$profile["max_deposit_show"] = FormatPrice($profile["max_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["min_live_square"] = $row_payment["min_live_square"];
		$profile["max_live_square"] = $row_payment["max_live_square"];
		$profile["min_total_square"] = $row_payment["min_total_square"];
		$profile["max_total_square"] = $row_payment["max_total_square"];
		$profile["min_land_square"] = $row_payment["min_land_square"];
		$profile["max_land_square"] = $row_payment["max_land_square"];
		$profile["min_floor"] = $row_payment["min_floor"];
		$profile["max_floor"] = $row_payment["max_floor"];
		$profile["floor_num"] = $row_payment["floor_num"];
		$profile["subway_min"] = $row_payment["subway_min"];
		$profile["min_year_build"] = $row_payment["min_year_build"];
		$profile["max_year_build"] = $row_payment["max_year_build"];

	} elseif ($profile["type"] == "2" || $profile["type"] == "4") {
		/**
		 * ������ ������������� �������� ��� ���������� ���� ���� � ������, ������
		 * � min_<field_name>
		 */
		$strSQL_payment = "	SELECT min_payment, auction, min_deposit,
							min_live_square, min_total_square,
							min_land_square, min_floor, floor_num, subway_min, min_year_build
							FROM ".USERS_RENT_PAYS_TABLE."
							WHERE id_ad='".$profile["id"]."' AND id_user='".$profile["id_user"]."' ";
		$rs_payment = $dbconn->Execute($strSQL_payment);
		$row_payment = $rs_payment->GetRowAssoc(false);
		$profile["min_payment"] = PaymentFormat($row_payment["min_payment"]);
		$profile["min_payment_show"] = FormatPrice($profile["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		if($profile["min_payment"]<=0){$profile["act_status"]=0;}else{$profile["act_status"]=1;}
		$profile["auction"] = $row_payment["auction"];
		$profile["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
		$profile["min_deposit_show"] = FormatPrice($profile["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["min_live_square"] = $row_payment["min_live_square"];
		$profile["min_total_square"] = $row_payment["min_total_square"];
		$profile["min_land_square"] = $row_payment["min_land_square"];
		$profile["min_floor"] = $row_payment["min_floor"];
		$profile["floor_num"] = $row_payment["floor_num"];
		$profile["subway_min"] = $row_payment["subway_min"];
		$profile["min_year_build"] = $row_payment["min_year_build"];
	}

	$strSQL_age = "SELECT his_age_1, his_age_2 FROM ".USERS_RENT_AGES_TABLE." WHERE id_user='".$profile["id_user"]."' AND id_ad='".$profile["id"]."' ";
	$rs_age = $dbconn->Execute($strSQL_age);
	$row_age = $rs_age->GetRowAssoc(false);

	$profile["his_age_1"] = $row_age["his_age_1"];
	$profile["his_age_2"] = $row_age["his_age_2"];

	$settings = GetSiteSettings(array("use_video_approve", "use_photo_approve"));
	$profile["use_video_approve"] = $settings["use_video_approve"];
	$profile["use_photo_approve"] = $settings["use_photo_approve"];
	
	/*reserved period for this, that type is 2*/
	
	if ($profile["type"] == 2){
		$calendar_event = new CalendarEvent();
		$reserve_days = $calendar_event->GetReserveDays($profile["id"], $profile["id_user"]);
		$i=0;
		foreach ($reserve_days AS $period){								
			$profile["reserve_days"][$i]["start"] = date("Y-m-d  H:i",$period["start_tmstmp"]);						
			$profile["reserve_days"][$i]["end"]   = date("Y-m-d  H:i",$period["end_tmstmp"]);			
			$profile["reserve_days"][$i]["id"] = $period["id"];			
			$i++;
		}
	}

	$smarty->assign("profile", $profile);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/rental_ad_display.tpl");

	exit;
}

function EditProfile($par, $err="", $choise="", $id_ad=""){
	global $smarty, $config, $dbconn, $user, $lang, $multi_lang, $REFERENCES, $VIDEO_EXT_ARRAY;

	$choise = isset($_POST["choise"]) ? $_POST["choise"] : $choise;
		
	$id_ad = isset($_POST["id_ad"]) ? $_POST["id_ad"] : $id_ad;	
	
	
	$ajax = isset($_REQUEST["ajax"]) ? intval($_REQUEST["ajax"]) : 0;
	
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexHomePage('rentals','homepage');
	if (!$ajax){
		
	
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
		CreateMenu('rental_menu');
		$link_count = GetCountForLinks($user[0]);
		$smarty->assign("link_count",$link_count);
		$left_links = GetLeftLinks("homepage.php");
		$smarty->assign("left_links", $left_links);
		CreateMenu('lang_menu');
		CreateMenu('bottom_menu');
		if ($err){
			GetErrors($err);
		}
	}
	switch($par){
		case "step_1":
			$data = (isset($_SESSION["step_1"])) ? $_SESSION["step_1"] : "";
			$data["from_edit"] = (isset($_SESSION["from_edit"])) ? $_SESSION["from_edit"] : "";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$strSQL = "SELECT id, name FROM ".COUNTRY_TABLE." WHERE 1";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0) {
				$i = 0;
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$country[$i]["id"] = $row["id"];
					if ($config["lang_ident"]!='ru'){
						$country[$i]["name"] = RusToTranslit($row["name"]);
					} else {
						$country[$i]["name"] = $row["name"];
					}
					$rs->MoveNext();
					$i++;
				}
			}else{
				$country = "";
			}
			if ($id_ad){
				$form["next_link"] = $file_name."?sel=1";
			} else {
				$form["next_link"] = $file_name."?sel=save_ad";
			}
			
			if (($data["from_edit"]=='1') || (isset($data["region"]))){

				$strSQL = "SELECT id, name FROM ".REGION_TABLE." WHERE id_country='".$data["country"]."' ORDER by name";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0) {
					$i = 0;
					while(!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						$region[$i]["id"] = $row["id"];
						if ($config["lang_ident"]!='ru'){
							$region[$i]["name"] = RusToTranslit($row["name"]);
						} else {
							$region[$i]["name"] = $row["name"];
						}
						$rs->MoveNext();
						$i++;
					}
					$smarty->assign("region", $region);
				}
				

				$strSQL = "SELECT id, name FROM ".CITY_TABLE." WHERE id_region='".$data["region"]."' ORDER by name";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0) {
					$i = 0;
					while(!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						$city[$i]["id"] = $row["id"];
						if ($config["lang_ident"]!='ru'){
							$city[$i]["name"] = RusToTranslit($row["name"]);
						} else {
							$city[$i]["name"] = $row["name"];
						}
						$rs->MoveNext();
						$i++;
					}
					$smarty->assign("city", $city);
				}
				
			}
			if (isset($data["id_spr_type"])){
				$id_spr_type = intval($data["id_spr_type"]);
			}else{
				$id_spr_type = 0;
			}
			if ((isset($_GET["from"])) && ($_GET["from"] == 'sresults')) {
				$choise = intval($_GET["var_2"]);
				$id_spr_type = intval($_GET["var_3"]);				
			}
			
			$lang_add = ($choise == 1 || $choise == 3) ? "2" : "";
			$used_references = array("realty_type");
			foreach ($REFERENCES as $arr) {					
				if (in_array($arr["key"], $used_references)) {						
					$spr_order = ($arr["key"] == "description") ? "id" : "name";						
					$smarty->assign($arr["key"], GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order));
				}
			}

			/**
			 * @todo ������� �������� ��������� property_type �� registration ����������
			 */

			$pay_sell_lease = 0;
			$use_sell_lease_payment = GetSiteSettings("use_sell_lease_payment");
			if (!$id_ad && $use_sell_lease_payment && !in_array("mhi_services", $config["mode_hide_ids"])) {
				$user_sell_lease = GetSellLeaseUserPayment();
				if ($user_sell_lease) {
					if ($user_sell_lease["ads_number"] - $user_sell_lease["used_ads_number"] == 0) {
						$pay_sell_lease = 1;
					}
				} else {
					$pay_sell_lease = 1;
				}
				if ($pay_sell_lease) {
					$choise = (!$choise) ? 3 : $choise;
				}
			}			
			$smarty->assign("pay_sell_lease", $pay_sell_lease);

			$use_listing_completion_bonus = GetSiteSettings("use_listing_completion_bonus");
			$smarty->assign("use_listing_completion_bonus", $use_listing_completion_bonus);
			if ($use_listing_completion_bonus) {
				$smarty->assign("bonus", GetBonusSettings());
			}

			if (!$id_ad) {
				$use_private_person_ads_limit = GetSiteSettings("use_private_person_ads_limit");
				if ($use_private_person_ads_limit && $user[10] == 1) {
					//if use limit and user is private person
					$smarty->assign("private_person_ads_limit", GetSiteSettings("private_person_ads_limit"));
					$smarty->assign("user_ads_cnt", GetUserAdsNumber($user[0]));
				}
			}

			/**
			 * operation types by default in case of what elements were hidden in the current mode
			 */		
			if ($id_ad && $choise) {
				$saved_choise = $choise;	
				$convert_choise = array(4 => 2, 2 => 4, 1 => 3, 3 => 1);
			}						
			
			$choise_arr = array("mhi_ad_sell" => 4, "mhi_ad_rent" => 1, "mhi_ad_buy" => 3, "mhi_ad_lease" => 2);
			$choise_current = $choise_arr;
			foreach ($choise_arr as $elem_key=>$choise_id) {			
				if (in_array($elem_key, $config["mode_hide_ids"])) {		
					unset($choise_current[$elem_key]);	
				}	
			}			
			if (count($choise_current) > 0) {
				if (!$choise || !in_array($choise, array_values($choise_current))) {					
					if (isset($saved_choise)) {
						$choise = $convert_choise[$saved_choise];										
					}
					if (!in_array($choise, array_values($choise_current))) {		
						foreach ($choise_current as $choise_id) {
							$choise = $choise_id;							
							if (!($use_sell_lease_payment && ($choise == 4 || $choise == 2))) { 					
								break;
							}
						}
					}
				}	
			} else {
				HidePage();
				exit;
			}		
							
			$smarty->assign("choise", $choise);
			$smarty->assign("data", $data);
			$smarty->assign("id_ad", $id_ad);			
			$smarty->assign("id_spr_type", $id_spr_type);
			$smarty->assign("country", $country);
			break;
		case "step_3":
			$form["next_link"] = $file_name."?sel=4";
			$form["back_link"] = $file_name."?sel=add_rent";
			$data = $_SESSION["step_3"];
			$data["from_edit"] = $_SESSION["from_edit"];
						
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");

			//���������� ��� DefaultFieldName (������ �� �����������)
			$lang_add = ($choise == 1 || $choise == 3) ? "2" : "";

			$used_references = array("info", "period", "realty_type", "description");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$spr_order = ($arr["key"] == "description") ? "id" : "name";
					$smarty->assign($arr["key"], GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order));
				}
			}

			if ($data["move_day"]>0){
				$day = $data["move_day"];
				$month = $data["move_month"];
				$year = $data["move_year"];
			} else {
				$day = 1;
				$month = date("m");
				$year = date("Y");
			}
			
			$smarty->assign("day", GetDaySelect($day));
			$smarty->assign("month", GetMonthSelect($month));
			$smarty->assign("year", GetYearSelect($year, 3, (intval($year+2))));

			$smarty->assign("current_year", date("Y"));
			
			$smarty->assign("data", $data);

			$smarty->assign("id_ad", $id_ad);
			$smarty->assign("choise", $choise);
			

			break;
		case "step_4":
			$form["next_link"] = $file_name."?sel=5";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$form["back_link"] = $file_name."?sel=step_3";

			if ($choise=="2" || $choise=="4") {
				$settings = GetSiteSettings(array('use_image_resize','photo_max_size','photo_max_width','photo_max_height','photo_max_user_count','default_photo','photo_folder', 'use_photo_approve'));

				if ($settings["use_image_resize"]){
					$data["size"] = round($settings["photo_max_size"]/1024);
					$data["use_resize"] = 0;
				} else {
					$data["size"] = round($settings["photo_max_size"]/1024);
					$data["width"] = $settings["photo_max_width"];
					$data["height"] = $settings["photo_max_height"];
					$data["use_resize"] = 1;
				}
				$data["limit"] = $settings["photo_max_user_count"];
				$data["use_photo_approve"] = $settings["use_photo_approve"];

				$images_obj = new Images($dbconn);
				$data["photo_extensions"] = $images_obj->IMG_EXT_ARRAY;

				$strSQL = "SELECT id, upload_path, upload_type, status, admin_approve, user_comment ".
						  "FROM ".USERS_RENT_UPLOADS_TABLE." ".
						  "WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' AND upload_type='f' ORDER BY sequence";

				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0){
					$i = 0;
					while(!$rs->EOF){
						$row = $rs->GetRowAssoc(false);
						$upload[$i]["id"] = $row["id"];
						$upload[$i]["upload_path"] = $row["upload_path"];
						$upload[$i]["status"] = $row["status"];
						$upload[$i]["admin_approve"] = $row["admin_approve"];
						$upload[$i]["user_comment"] = stripslashes($row["user_comment"]);
						$path = $config["site_path"].$settings["photo_folder"]."/".$upload[$i]["upload_path"];
						$thumb_path = $config["site_path"].$settings["photo_folder"]."/thumb_".$upload[$i]["upload_path"];

						if(file_exists($path) && strlen($upload[$i]["upload_path"])>0){
							$upload[$i]["file"] = ".".$settings["photo_folder"]."/".$upload[$i]["upload_path"];
							$upload[$i]["del_link"] = "./".$file_name."?sel=upload_del&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f&amp;back=step_4&amp;id_ad=".$id_ad;
							$upload[$i]["view_link"] = "./".$file_name."?sel=upload_view&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f";
							if ($row["status"] == 1) {
								$upload[$i]["deactivate_link"] = "./".$file_name."?sel=upload_deactivate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_4";
							} else {
								$upload[$i]["activate_link"] = "./".$file_name."?sel=upload_activate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_4";
							}
							$upload[$i]["edit_comment_link"] = "./".$file_name."?sel=edit_comment&amp;id_file=".$upload[$i]["id"]."&amp;back=step_4";
						}
						if(file_exists($thumb_path) && strlen($upload[$i]["upload_path"])>0)
						$upload[$i]["thumb_file"] = ".".$settings["photo_folder"]."/thumb_".$upload[$i]["upload_path"];
						if(!file_exists($path) || !strlen($upload[$i]["upload_path"])){
							$upload[$i]["file"] = ".".$settings["photo_folder"]."/".$settings["default_photo"];
							$upload[$i]["thumb_file"] = $upload[$i]["file"];
						}
						$rs->MoveNext();
						$i++;
					}
				}else {
					$upload = array();
				}
				$smarty->assign("upload", $upload);
				$smarty->assign("upload_count", sizeof($upload));
				$smarty->assign("upload_type_link", 'photo');
				$smarty->assign("upload_type", 'f');
				$smarty->assign("data", $data);
			}

			$smarty->assign("choise", $choise);
			$smarty->assign("id_ad", $id_ad);
			break;
		case "step_5":
			$form["next_link"] = $file_name."?sel=6";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$form["back_link"] = $file_name."?sel=step_4";
			
			if ($choise=="1" || $choise=="3"){
				$settings = GetSiteSettings(array('use_image_resize','photo_max_size','photo_max_width','photo_max_height','photo_max_user_count','default_photo','photo_folder', 'use_photo_approve'));

				if ($settings["use_image_resize"]){
					$data["size"] = round($settings["photo_max_size"]/1024);
					$data["use_resize"] = 0;
				} else {
					$data["size"] = round($settings["photo_max_size"]/1024);
					$data["width"] = $settings["photo_max_width"];
					$data["height"] = $settings["photo_max_height"];
					$data["use_resize"] = 1;
				}
				$data["limit"] = $settings["photo_max_user_count"];
				$data["use_photo_approve"] = $settings["use_photo_approve"];

				$images_obj = new Images($dbconn);
				$data["photo_extensions"] = $images_obj->IMG_EXT_ARRAY;

				$strSQL = "SELECT id, upload_path, upload_type, status, admin_approve, user_comment ".
						  "FROM ".USERS_RENT_UPLOADS_TABLE." ".
						  "WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' AND upload_type='f' ORDER BY sequence";
				$rs = $dbconn->Execute($strSQL);

				if ($rs->fields[0]>0){
					$i = 0;
					while(!$rs->EOF){
						$row = $rs->GetRowAssoc(false);
						$upload[$i]["id"] = $row["id"];
						$upload[$i]["upload_path"] = $row["upload_path"];
						$upload[$i]["status"] = $row["status"];
						$upload[$i]["admin_approve"] = $row["admin_approve"];
						$upload[$i]["user_comment"] = $row["user_comment"];
						$path = $config["site_path"].$settings["photo_folder"]."/".$upload[$i]["upload_path"];
						$thumb_path = $config["site_path"].$settings["photo_folder"]."/thumb_".$upload[$i]["upload_path"];

						if(file_exists($path) && strlen($upload[$i]["upload_path"])>0){
							$upload[$i]["file"] = ".".$settings["photo_folder"]."/".$upload[$i]["upload_path"];
							$upload[$i]["del_link"] = "./".$file_name."?sel=upload_del&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f&amp;back=step_5&amp;id_ad=".$id_ad;
							$upload[$i]["view_link"] = "./".$file_name."?sel=upload_view&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f";
							if ($row["status"] == 1) {
								$upload[$i]["deactivate_link"] = "./".$file_name."?sel=upload_deactivate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_5";
							} else {
								$upload[$i]["activate_link"] = "./".$file_name."?sel=upload_activate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_5";
							}
							$upload[$i]["edit_comment_link"] = "./".$file_name."?sel=edit_comment&amp;id_file=".$upload[$i]["id"]."&amp;back=step_5";
						}
						if(file_exists($thumb_path) && strlen($upload[$i]["upload_path"])>0)
						$upload[$i]["thumb_file"] = ".".$settings["photo_folder"]."/thumb_".$upload[$i]["upload_path"];
						if(!file_exists($path) || !strlen($upload[$i]["upload_path"])){
							$upload[$i]["file"] = ".".$settings["photo_folder"]."/".$settings["default_photo"];
							$upload[$i]["thumb_file"] = $upload[$i]["file"];
						}
						$rs->MoveNext();
						$i++;
					}
				} else {
					$upload = array();
				}
				$smarty->assign("upload", $upload);
				$smarty->assign("upload_count", sizeof($upload));
				$smarty->assign("data", $data);
			} elseif ($choise=="2" || $choise=="4"){
				$data = $_SESSION["step_5"];
				$max_age = GetSiteSettings('max_age_limit');
				$min_age = GetSiteSettings('min_age_limit');

				for ($i=$min_age; $i<($max_age+1); $i++){
					$age[$i] = $i;
				}
				$age_sel["age_1"] = isset($data["his_age_1"]) ? $data["his_age_1"] : "";
				if (isset($data["age_2"])){
					$age_sel["age_2"] = $data["his_age_2"];
				} else {
					$age_sel["age_2"] = $max_age;
				}
				$smarty->assign("age_sel", $age_sel);
				$smarty->assign("age", $age);

				$used_references = array("people", "gender", "language");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$smarty->assign($arr["key"]."_match", GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"]."_match", $data, 2));
					}
				}
			}

			$smarty->assign("id_ad", $id_ad);
			$smarty->assign("choise", $choise);
			break;
		case "step_6":
			$form["next_link"] = $file_name."?sel=8";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$form["back_link"] = $file_name."?sel=step_5";

			$strSQL = "SELECT comment FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[0]."' AND type='".$choise."' AND id='".$id_ad."'";
			$rs = $dbconn->Execute($strSQL);

			$comments = stripslashes($rs->fields[0]);

			$smarty->assign("comments", $comments);
			$smarty->assign("choise", $choise);
			$smarty->assign("id_ad", $id_ad);
			break;
		case "step_7":
			$form["next_link"] = $file_name."?sel=my_ad";

			$settings = GetSiteSettings(array('use_image_resize','photo_max_size','photo_max_width','photo_max_height','photo_max_user_count','default_photo','photo_folder', 'use_photo_approve','plan_photo_max_user_count'));
			if ($settings["use_image_resize"]){
				$data["size"] = round($settings["photo_max_size"]/1024);
				$data["use_resize"] = 0;
			} else {
				$data["size"] = round($settings["photo_max_size"]/1024);
				$data["width"] = $settings["photo_max_width"];
				$data["height"] = $settings["photo_max_height"];
				$data["use_resize"] = 1;
			}
			$data["use_photo_approve"] = $settings["use_photo_approve"];

			$images_obj = new Images($dbconn);
			$data["photo_extensions"] = $images_obj->IMG_EXT_ARRAY;
			$data["limit"] = $settings["plan_photo_max_user_count"];

			$strSQL = "SELECT id, upload_path, status, admin_approve, user_comment ".
					  "FROM ".USER_RENT_PLAN_TABLE." ".
					  "WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' ".
					  "ORDER BY sequence";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0){
				$i = 0;
				while(!$rs->EOF){
					$row = $rs->GetRowAssoc(false);
					$plan[$i]["id"] = $row["id"];
					$plan[$i]["upload_path"] = $row["upload_path"];
					$plan[$i]["status"] = $row["status"];
					$plan[$i]["admin_approve"] = $row["admin_approve"];
					$plan[$i]["user_comment"] = stripslashes($row["user_comment"]);
					$path = $config["site_path"].$settings["photo_folder"]."/".$plan[$i]["upload_path"];
					$thumb_path = $config["site_path"].$settings["photo_folder"]."/thumb_".$plan[$i]["upload_path"];

					if(file_exists($path) && strlen($plan[$i]["upload_path"])>0){
						$plan[$i]["file"] = ".".$settings["photo_folder"]."/".$plan[$i]["upload_path"];
						$plan[$i]["del_link"] = "./".$file_name."?sel=plan_del&amp;id_file=".$plan[$i]["id"]."&amp;back=step_7&amp;id_ad=".$id_ad;
						$plan[$i]["view_link"] = "./".$file_name."?sel=plan_view&amp;id_file=".$plan[$i]["id"];
						if ($row["status"] == 1) {
							$plan[$i]["deactivate_link"] = "./".$file_name."?sel=upload_deactivate&amp;subsel=plan&amp;id_file=".$plan[$i]["id"]."&amp;back=step_7";
						} else {
							$plan[$i]["activate_link"] = "./".$file_name."?sel=upload_activate&amp;subsel=plan&amp;id_file=".$plan[$i]["id"]."&amp;back=step_7";
						}
						$plan[$i]["edit_comment_link"] = "./".$file_name."?sel=edit_comment&amp;subsel=plan&amp;id_file=".$plan[$i]["id"]."&amp;back=step_7";
					}

					if(file_exists($thumb_path) && strlen($plan[$i]["upload_path"])>0)
					$plan[$i]["thumb_file"] = ".".$settings["photo_folder"]."/thumb_".$plan[$i]["upload_path"];
					if(!file_exists($path) || !strlen($plan[$i]["upload_path"])){
						$plan[$i]["file"] = ".".$settings["photo_folder"]."/".$settings["default_photo"];
						$plan[$i]["thumb_file"] = $plan[$i]["file"];
					}
					$rs->MoveNext();
					$i++;
				}
			} else {
				$plan = "0";
			}

			$smarty->assign("upload", $plan);
			$smarty->assign("upload_count", sizeof($plan));
			$smarty->assign("upload_type_link", 'plan');
			$smarty->assign("upload_type", 'plan');

			$smarty->assign("data", $data);
			$smarty->assign("id_ad", $id_ad);
			$smarty->assign("choise", $choise);
			break;
		case "step_8":
			$form["next_link"] = $file_name."?sel=finish";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$form["back_link"] = $file_name."?sel=step_6";

			$strSQL = "SELECT headline FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[0]."' AND type='".$choise."' AND id='".$id_ad."'";
			$rs = $dbconn->Execute($strSQL);

			$headline = stripslashes($rs->fields[0]);

			$smarty->assign("headline", $headline);
			$smarty->assign("choise", $choise);
			$smarty->assign("id_ad", $id_ad);
			break;	
		case "upload_video":

			$form["next_link"] = $file_name."?sel=my_ad";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$form["back_link"] = $file_name."?sel=step_4";

			$settings = GetSiteSettings(array("use_video_approve", "video_folder", 'video_max_count', 'video_max_size', 'default_video_icon', 'use_ffmpeg'));

			$data["size"] = round($settings["video_max_size"]/1024);			
			$data["limit"] = $settings["video_max_count"];
			$data["use_video_approve"] = $settings["use_video_approve"];
			$data["default_video_icon"] = $config["server"].$config["site_root"]."".$settings["video_folder"]."/".$settings["default_video_icon"];
			$data["video_extensions"] = $VIDEO_EXT_ARRAY;

			$strSQL = "SELECT id, upload_path, upload_type, status, admin_approve, user_comment ".
					  "FROM ".USERS_RENT_UPLOADS_TABLE." ".
					  "WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' AND upload_type='v' ".
					  "ORDER BY sequence";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0){
				$i = 0;
				while(!$rs->EOF){
					$row = $rs->GetRowAssoc(false);
					$upload[$i]["id"] = $row["id"];					
					$upload[$i]["status"] = $row["status"];
					$upload[$i]["admin_approve"] = $row["admin_approve"];
					$upload[$i]["user_comment"] = stripslashes($row["user_comment"]);
					if ($settings["use_ffmpeg"] == 1) {				
						$flv_name = explode('.', $row["upload_path"]);
						
						if (file_exists($config["site_path"].$settings["video_folder"]."/".$flv_name[0].".flv")) {															
							$upload[$i]["is_flv"] = 1;
							$smarty->assign("is_flv", 1);
							$upload[$i]["video_icon"] = $flv_name[0]."1.jpg";
							$upload[$i]["video_path"] = $flv_name[0].".flv";
							$size = explode('x', GetSiteSettings("flv_output_dimension"));
							$upload[$i]["width"] = $size[0];			
							$upload[$i]["height"] = $size[1];
						} else {					
							$upload[$i]["video_path"] = $row["upload_path"];
							$upload[$i]["video_icon"] = $settings["default_video_icon"];
							$upload[$i]["width"] = 320;			
							$upload[$i]["height"] = 240;
							
						}
					} else {				
						$upload[$i]["video_path"] = $row["upload_path"];
						$upload[$i]["video_icon"] = $settings["default_video_icon"];
						$upload[$i]["is_flv"] = 0;
						$upload[$i]["width"] = 320;			
						$upload[$i]["height"] = 240;
					}								
										
					$path = $config["server"].$config["site_root"].$settings["video_folder"]."/";						
					
					if (file_exists($config["site_path"].$settings["video_folder"]."/".$upload[$i]["video_path"])) {
						$upload[$i]["video_file"] = $path.$upload[$i]["video_path"];
						$upload[$i]["video_icon"] = $path.$upload[$i]["video_icon"];						
						$upload[$i]["del_link"] = "./".$file_name."?sel=upload_del&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=v&amp;back=upload_video&amp;id_ad=".$id_ad;
						$upload[$i]["view_link"] = "./".$file_name."?sel=upload_view&amp;id_file=".$upload[$i]["id"]."&amp;is_flv=".$upload[$i]["is_flv"]."&amp;type_upload=v";
						
						if ($row["status"] == 1) {
							$upload[$i]["deactivate_link"] = "./".$file_name."?sel=upload_deactivate&amp;id_file=".$upload[$i]["id"]."&amp;back=upload_video";
						} else {
							$upload[$i]["activate_link"] = "./".$file_name."?sel=upload_activate&amp;id_file=".$upload[$i]["id"]."&amp;back=upload_video";
						}
						$upload[$i]["edit_comment_link"] = "./".$file_name."?sel=edit_comment&amp;id_file=".$upload[$i]["id"]."&amp;back=upload_video";
					}
					$rs->MoveNext();
					$i++;
				}
			} else {
				$upload = array();
			}
			$smarty->assign("choise", $choise);
			$smarty->assign("upload", $upload);
			$smarty->assign("upload_count", sizeof($upload));
			$smarty->assign("upload_type_link", 'video');
			$smarty->assign("upload_type", 'v');
			$smarty->assign("data", $data);
			$smarty->assign("id_ad", $id_ad);

			break;
		case "listing_position":
			
			$ad_payment_data = $_SESSION["step_3"];
						
			$strSQL = "SELECT type, status, date_unactive, sold_leased_status FROM ".RENT_ADS_TABLE." ".
				      "WHERE id='$id_ad'";
			$rs = $dbconn->Execute($strSQL);
			
			$profile = $rs->GetRowAssoc(false);
			$profile["id"] = $id_ad;
			$profile["act_status"] = $ad_payment_data["act_status"];
						
			$smarty->assign("profile", $profile);
			
			$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
			$smarty->assign("use_ads_activity_period", GetSiteSettings("use_ads_activity_period"));
			$smarty->assign("ads_activity_period", GetSiteSettings("ads_activity_period"));
	
			$use_private_person_ads_limit = GetSiteSettings("use_private_person_ads_limit");
			if ($use_private_person_ads_limit && $user[10] == 1) {
				//if use limit and user is private person
				$smarty->assign("private_person_ads_limit", GetSiteSettings("private_person_ads_limit"));
				$smarty->assign("user_ads_cnt", GetUserAdsNumber($user[0]));
			}
	
			break;
	}

	$form["sel"] = $par;
	$form["action"] = $file_name;	

	$smarty->assign("id_ad", $id_ad);
	$smarty->assign("par", $par);
	$smarty->assign("form", $form);
	$smarty->assign("file_name", $file_name);
	if ($ajax && ($par=='step_4' || $par=='step_7' || $par == 'upload_video')){			
		$smarty->display(TrimSlash($config["index_theme_path"])."/user_upload.tpl");		
		exit;
	}
	$smarty->display(TrimSlash($config["index_theme_path"])."/rental_edit.tpl");
	exit;
}

function CalendarAd($par="", $err="", $date_start="", $date_end="", $id_event="") {
	global $smarty, $config, $dbconn, $user, $lang, $multi_lang, $REFERENCES;
	
	$id_ad = $_REQUEST["id_ad"]?intval($_REQUEST["id_ad"]):$id_ad;			
	
	$smarty->assign("submenu", "edit_rentals");

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexHomePage('rentals','homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');	

	
	$date["months"] = GetMonth();	
	$date["day_of_week"] = GetDayOfWeek();
	for ($i=0; $i<24; $i++){
		$i_str = strval($i);
		if (strlen($i_str) == 1){
			$i_str = '0'.$i_str;
		}
		$date["hours"][$i] = $i_str;
	}
	for ($i=0; $i<60; $i++){
		$i_str = strval($i);
		if (strlen($i_str) == 1){
			$i_str = '0'.$i_str;
		}
		$date["minutes"][$i] = $i_str;
	}
	switch ($par){
		case "add_event":
			if ($date_start){
				$date["now_date"] = getdate($date_start);
				$date["end_date"] = getdate($date_end);
			} else{
				$date["now_date"] = getdate();
				$date["end_date"] = getdate();
			}
			
			$start_month = isset($_REQUEST["start_month"]) ? intval($_REQUEST["start_month"]) : $date["now_date"]["mon"];
			$start_year = isset($_REQUEST["start_year"]) ? intval($_REQUEST["start_year"]) : $date["now_date"]["year"];				
		break;
		case "edited_event":
			$date["now_date"] = getdate($date_start);
			$date["end_date"] = getdate($date_end);
			$start = explode("-", date("Y-n-d-H-i-s", $date_start));
			$edit_event["year_from"] = $start[0];
			$edit_event["mon_from"] = $start[1];
			$edit_event["day_from"] = $start[2];
			$edit_event["hour_from"] = $start[3];
			$edit_event["min_from"] = $start[4];
			$edit_event["sec_from"] = $start[5];
			
			$end = explode("-", date("Y-n-d-H-i-s", $date_end));
			$edit_event["year_to"] = $end[0];
			$edit_event["mon_to"] = $end[1];
			$edit_event["day_to"] = $end[2];
			$edit_event["hour_to"] = $end[3];
			$edit_event["min_to"] = $end[4];
			$edit_event["sec_to"] = $end[5];
			
			$smarty->assign("edit_event", $edit_event);
			$smarty->assign("id_event", $id_event);
			
			$start_month = $start[1];			
			$start_year = $start[0];
			break;	
	}	
	

	$calendar_event = new CalendarEvent();
	$date["display"] = $calendar_event->GetMonthYearArray($start_month, $start_year, $id_ad, $user[0], 3, 1);	

	if ($err){
		GetErrors($err);
	}	
	$smarty->assign("half_tf_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_tf_day.gif");
	$smarty->assign("half_ft_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_ft_day.gif");
	$smarty->assign("half_tft_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_tft_day.gif");
	$smarty->assign("file_name", $file_name);
	$smarty->assign("par", $par);
	$smarty->assign("id_ad", $id_ad);
	$smarty->assign("date", $date);
	
	$smarty->display(TrimSlash($config["index_theme_path"])."/calendar_edit.tpl");
}

function SaveProfile($par){
	global $smarty, $config, $dbconn, $user, $multi_lang, $REFERENCES;

	if (!($par=="save_ad")){
		$id_ad = (isset($_POST["id_ad"])) ? intval($_POST["id_ad"]) : 0;
		$choise = (isset($_POST["choise"])) ? intval($_POST["choise"]) : 0;
	}
	switch ($par){
		case "save_ad"://first save		
			$type = (isset($_POST["choise"]) && !empty($_POST["choise"])) ? intval($_POST["choise"]) : 0;		
			if ( !$type || $_POST["country"] < 1 || $_POST["region"] < 1 || ($_POST["city"] < 1 && ($type == 2 || $type == 4) )) {
				$err = "fill_empty_fields";
				$_SESSION["step_1"] = $_POST;				
				EditProfile("step_1", $err, $type, '');
				exit;
			}
			
			$strSQL = " INSERT INTO ".RENT_ADS_TABLE." (id_user, type, datenow, status, movedate) VALUES ('".$user[0]."','".$type."', now(), '0', DATE_FORMAT(now(), '%Y-%m-%d'))";
			
			$dbconn->Execute($strSQL);
			$err = "";

			$strSQL = " SELECT MAX(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[0]."' AND type='".$_POST["choise"]."' ";
			$rs = $dbconn->Execute($strSQL);
			$id_ad = $rs->fields[0];

			$strSQL = "INSERT INTO ".USERS_RENT_PAYS_TABLE." (id_ad, id_user) VALUES ('".$id_ad."','".$user[0]."')";
			$dbconn->Execute($strSQL);
			$strSQL = "INSERT INTO ".USERS_RENT_AGES_TABLE." (id_ad, id_user) VALUES ('".$id_ad."','".$user[0]."')";
			$dbconn->Execute($strSQL);

			$strSQL = " INSERT INTO ".USERS_RENT_LOCATION_TABLE." (id_user, id_ad, id_country, id_region, id_city, zip_code, street_1, street_2, adress)	
						VALUES ('".$user[0]."', '".$id_ad."','".intval($_POST["country"])."','".intval($_POST["region"])."','".intval($_POST["city"])."', '".strip_tags($_POST["zip_code"])."', '".substr(strip_tags(addslashes($_POST["cross_streets_1"])), 0 , 50)."', '".substr(strip_tags(addslashes($_POST["cross_streets_2"])), 0, 50)."', '".substr(strip_tags(addslashes($_POST["adress"])), 0, 100)."') ";
			$rs = $dbconn->Execute($strSQL);
			
			$used_references = array("realty_type");			
			foreach ($REFERENCES as $arr) {			
				if (in_array($arr["key"], $used_references)) {						
					
					$tmp_info = (isset($_REQUEST[$arr["key"]]) && !empty($_REQUEST[$arr["key"]])) ? $_REQUEST[$arr["key"]] : array();
					$tmp_spr = $_REQUEST["spr_".$arr["key"]];					
					if(is_array($tmp_info) && is_array($tmp_spr)){
						SprTableEdit($arr["spr_user_table"], $id_ad, $tmp_spr, $tmp_info);
					}
				}
			}
				
			$_SESSION["step_1"] = $_POST;
			/**
			 * Save statistics on sell/lease listings publication
			 */
			if (($type == 2 || $type == 4) && GetSiteSettings("use_sell_lease_payment") && GetSiteSettings("site_mode") == 1) {
				$strSQL = "UPDATE ".USER_SELL_LEASE_PAYMENT_TABLE." SET used_ads_number=used_ads_number+1 WHERE id_user='{$user[0]}'";
				$dbconn->Execute($strSQL);
			}
		break;
		case "1":
			$type = intval($_POST["choise"]);
			if ( $_POST["country"] < 1 || $_POST["region"] < 1 || ($_POST["city"] < 1 && ($type == 2 || $type == 4) )) {
				$err = "empty_fields";
				$_SESSION["step_1"] = $_POST;
				EditProfile("step_1", $err, $choise ,$id_ad);
				exit;
			} else {
				
				$strSQL = "UPDATE ".USERS_RENT_LOCATION_TABLE." set id_country='".intval($_POST["country"])."', id_region='".intval($_POST["region"])."', id_city='".intval($_POST["city"])."', zip_code='".strip_tags($_POST["zip_code"])."', street_1='".substr(addslashes(strip_tags($_POST["cross_streets_1"])), 0, 50)."', street_2='".substr(addslashes(strip_tags($_POST["cross_streets_2"])), 0, 50)."', adress='".substr(addslashes(strip_tags($_POST["adress"])), 0, 100)."' WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' ";
				$rs = $dbconn->Execute($strSQL);

				$strSQL = "UPDATE ".RENT_ADS_TABLE." SET type='$type' WHERE id_user='".$user[0]."' AND id='".$id_ad."' ";
				$dbconn->Execute($strSQL);					
			
			}
			$_SESSION["step_1"] = $_POST;
		break;
		case "4"://step_4
			$data["from_edit"] = $_SESSION["from_edit"];
			unset($_SESSION["step_3"]);
			$data["move_month"] = intval($_POST["move_month"]);
			$data["move_day"] = intval($_POST["move_day"]);
			$data["move_year"] = intval($_POST["move_year"]);
			//checking date
			//if ( (checkdate($data["move_month"], $data["move_day"], $data["move_year"])) && ( time() < mktime(date('G'), date('H'), date('i'), $data["move_month"], $data["move_day"], $data["move_year"] )) ){
				$movedate = sprintf("%04d-%02d-%02d", $data["move_year"], $data["move_month"], $data["move_day"]);
				$strSQL = "UPDATE ".RENT_ADS_TABLE." SET movedate='".$movedate."' WHERE id='".$id_ad."' ";
				$rs = $dbconn->Execute($strSQL);
			/*}else{
				$_SESSION["step_3"] = $_POST;
				EditProfile("step_3", "invalid_date");
				exit;
			}*/
			//i need/buy realty
			if ($_POST["choise"]=="1" || $_POST["choise"]=="3") {
				$strSQL = "	UPDATE ".USERS_RENT_PAYS_TABLE." SET
							min_payment='".intval($_REQUEST["min_payment"])."',
							max_payment='".intval($_REQUEST["max_payment"])."',
							auction='".intval($_REQUEST["auction"])."',
							min_deposit='".intval($_REQUEST["min_deposit"])."',
							max_deposit='".intval($_REQUEST["max_deposit"])."',
							min_live_square='".intval($_REQUEST["min_live_square"])."',
							max_live_square='".intval($_REQUEST["max_live_square"])."',
							min_total_square='".intval($_REQUEST["min_total_square"])."',
							max_total_square='".intval($_REQUEST["max_total_square"])."',
							min_land_square='".intval($_REQUEST["min_land_square"])."',
							max_land_square='".intval($_REQUEST["max_land_square"])."',
							min_floor='".intval($_REQUEST["min_floor"])."',
							max_floor='".intval($_REQUEST["max_floor"])."',
							floor_num='".intval($_REQUEST["floor_num"])."',
							subway_min='".intval($_REQUEST["subway_min"])."',
							min_year_build='".intval($_REQUEST["min_year_build"])."',
							max_year_build='".intval($_REQUEST["max_year_build"])."'
							WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
				$dbconn->Execute($strSQL);
				$strSQL = "UPDATE ".RENT_ADS_TABLE." SET
						   with_photo='".(($_REQUEST["with_photo"] == "on") ? 1 : 0)."',
						   with_video='".(($_REQUEST["with_video"] == "on") ? 1 : 0)."'
						   WHERE id_user='".$user[0]."' AND id='".$id_ad."' ";
				$dbconn->Execute($strSQL);
			} elseif ($_POST["choise"]=="2" || $_POST["choise"]=="4") {
			//i have/sell realty
				$min_payment = intval($_REQUEST["min_payment"]);
				if (!$min_payment) {
					$_SESSION["step_3"] = $_POST;
					EditProfile("step_3", "empty_fields");
					exit;
				}
				$strSQL = "	UPDATE ".USERS_RENT_PAYS_TABLE." SET
							min_payment='".intval($_REQUEST["min_payment"])."',
							auction='".intval($_REQUEST["auction"])."',
							min_deposit='".intval($_REQUEST["min_deposit"])."',
							min_live_square='".intval($_REQUEST["min_live_square"])."',
							min_total_square='".intval($_REQUEST["min_total_square"])."',
							min_land_square='".intval($_REQUEST["min_land_square"])."',
							min_floor='".intval($_REQUEST["min_floor"])."',
							floor_num='".intval($_REQUEST["floor_num"])."',
							subway_min='".intval($_REQUEST["subway_min"])."',
							min_year_build='".intval($_REQUEST["min_year_build"])."'
							WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
				$dbconn->Execute($strSQL);
			}
			$used_references = array("info", "period", "realty_type", "description");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$tmp_info = (isset($_REQUEST[$arr["key"]]) && !empty($_REQUEST[$arr["key"]])) ? $_REQUEST[$arr["key"]] : array();
					$tmp_spr = isset($_REQUEST["spr_".$arr["key"]]) ? $_REQUEST["spr_".$arr["key"]] : "";
					if(is_array($tmp_info) && is_array($tmp_spr)){
						SprTableEdit($arr["spr_user_table"], $id_ad, $tmp_spr, $tmp_info);
					}
				}
			}
			$_SESSION["step_3"] = $_POST;
			$_SESSION["from_edit"] = $data["from_edit"];
		break;
		case "5":
			unset($_SESSION["step_4"]);
			if ($_POST["choise"]=="1" || $_POST["choise"]=="3"){
				$people_count = intval($_POST["total_people"]);

				/**
				 * @todo ���������, ���� ������������
				 * ��� ������� � ����������������� ���������� ������������ - isset && !empty, ����� array()
				 */
				$values_gender = $_POST["gender"];
				$values_people = $_POST["people"];
				$values_occupation = $_POST["occupation"];
				$values_interests = $_POST["interests"];
				$values_language = $_POST["language"];
				$values_personality = $_POST["personality"];

				if ($people_count<3){
					if ($people_count<2){
						$limit = 1;
					} else {
						$limit = 2;
					}
					$too_much = 0;
					foreach ($values_people as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					foreach ($values_gender as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					foreach ($values_occupation as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					foreach ($values_personality as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					if ($too_much == 1){
						$_SESSION["step_4"] = $_POST;
						switch ($limit){
							case "1":
								$err = "too_much_descr_selected";
								break;
							case "2":
								$err = "too_much_descr_selected_for_two";
								break;
						}
						EditProfile("step_4", $err, $choise, $id_ad);
						exit;
					}
				}

				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".RENT_ADS_TABLE." set people_count='".$people_count."' WHERE id='".$id_ad."' ";
				$dbconn->Execute($strSQL);

				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET my_age_1='".$age_1."', my_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);

				$spr_people = $_POST["spr_people"];
				if(is_array($values_people) && is_array($spr_people) && intval($id_ad)){
					SprTableEdit(SPR_RENT_PEOPLE_USER_TABLE, $id_ad, $spr_people, $values_people);
				}
				$spr_gender = $_POST["spr_gender"];
				if(is_array($values_gender) && is_array($spr_gender) && intval($id_ad)){
					SprTableEdit(SPR_RENT_GENDER_USER_TABLE, $id_ad, $spr_gender, $values_gender);
				}
			}
			$_SESSION["step_4"] = $_POST;
		break;
		case "6":
			unset($_SESSION["step_5"]);

			if ($_POST["choise"]=="2" || $_POST["choise"]=="4"){
				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET his_age_1='".$age_1."', his_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);



				$used_references = array("people", "gender", "language");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$tmp_info = (isset($_REQUEST[$arr["key"]."_match"]) && !empty($_REQUEST[$arr["key"]."_match"])) ? $_REQUEST[$arr["key"]."_match"] : array();
						$tmp_spr = $_REQUEST["spr_".$arr["key"]."_match"];
						if(is_array($tmp_info) && is_array($tmp_spr)){
							SprTableEdit($arr["spr_match_table"], $id_ad, $tmp_spr, $tmp_info);
						}
					}
				}
				$_SESSION["step_5"] = $_POST;
			}
		break;
		case "7":
			if ($_POST["choise"]=="2" || $_POST["choise"]=="4"){
				/**
				 * @todo ���������, ���� ������������
				 * ��� ������� � ����������������� ���������� ������������ - isset && !empty, ����� array()
				 */
				$total_people = intval($_POST["total_people"]);
				$values_gender = $_POST["gender"];
				$values_people = $_POST["people"];
				$values_occupation = $_POST["occupation"];
				$values_interests = $_POST["interests"];
				$values_language = $_POST["language"];
				$values_personality = $_POST["personality"];

				if ($people_count<3){
					if ($people_count<2){
						$limit = 1;
					} else {
						$limit = 2;
					}
					$too_much = 0;
					foreach ($values_people as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					foreach ($values_gender as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					foreach ($values_occupation as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					foreach ($values_personality as $values){
						if (sizeof($values)>$limit){
							$too_much = 1;
							break;
						}
					}
					if ($too_much == 1){
						switch ($limit){
							case "1":
								$err = "too_much_descr_selected";
								break;
							case "2":
								$err = "too_much_descr_selected_for_two";
								break;
						}
						EditProfile("step_6","too_much_descr_selected",$choise, $id_ad);
						exit;
					}
				}
				$strSQL = " UPDATE ".RENT_ADS_TABLE." SET people_count='".$total_people."' WHERE id='".$id_ad."' AND id_user='".$user[0]."' ";
				$dbconn->Execute($strSQL);

				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET my_age_1='".$age_1."', my_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);
				$spr_people = $_POST["spr_people"];
				if(is_array($values_people) && is_array($spr_people) && intval($id_ad)){
					SprTableEdit(SPR_RENT_PEOPLE_USER_TABLE, $id_ad, $spr_people, $values_people);
				}
				$spr_gender = $_POST["spr_gender"];
				if(is_array($values_gender) && is_array($spr_gender) && intval($id_ad)){
					SprTableEdit(SPR_RENT_GENDER_USER_TABLE, $id_ad, $spr_gender, $values_gender);
				}

			} elseif ($_POST["choise"] == '1') {
				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET his_age_1='".$age_1."', his_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);

				$values_people = $_POST["people_match"];
				$spr_people = $_POST["spr_people_match"];
				if(is_array($values_people) && is_array($spr_people) && intval($id_ad)){
					SprTableEdit(SPR_RENT_PEOPLE_MATCH_TABLE, $id_ad, $spr_people, $values_people);
				}
				$values_gender = $_POST["gender_match"];
				$spr_gender = $_POST["spr_gender_match"];
				if(is_array($values_gender) && is_array($spr_gender) && intval($id_ad)){
					SprTableEdit(SPR_RENT_GENDER_MATCH_TABLE, $id_ad, $spr_gender, $values_gender);
				}
			}
		break;
		case "8":			
			$comment = trim(strip_tags($_POST["comments"]));
			if (BadWordsCont($comment)){
				EditProfile("step_6", "badword");
				exit;
			}			
			$strSQL = "UPDATE ".RENT_ADS_TABLE." set comment='".addslashes(nl2br($comment))."' WHERE id='".$id_ad."' ";
			$dbconn->Execute($strSQL);
			$err = "completed";
		break;
		case "finish":			
			$headline = trim(strip_tags($_POST["headline"]));
			if (BadWordsCont($headline)){
				EditProfile("step_8", "badword");
				exit;
			}			
			$strSQL = "UPDATE ".RENT_ADS_TABLE." SET headline='".addslashes($headline)."' WHERE id='".$id_ad."' ";
			$dbconn->Execute($strSQL);
			$err = "completed";
		break;
	}
	if ($par != "save_ad") {
		AdUpdateDate($id_ad);
	}

	header("Location: ".$config["server"].$config["site_root"]."/rentals.php?sel=my_ad&id_ad=$id_ad");
	exit();
}

function UploadPhoto($back){
	global $smarty, $config, $dbconn, $user;
	$id_ad = intval($_POST["id_ad"]);
	$photo = $_FILES["photo"];
	$upload_comment = trim(strip_tags($_POST["upload_comment"]));

	if (BadWordsCont($upload_comment)){
		EditProfile($back, "badword");
		exit;
	}	
			
	$settings = GetSiteSettings(array( 'photo_folder', 'photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height'));

	$rs = $dbconn->Execute("SELECT COUNT(*) FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".intval($_POST["id_ad"])."' AND upload_type='f'");
	$photo_count = $rs->fields[0];
	if(intval($photo_count) >= intval($settings["photo_max_user_count"])){
		$err = "cant_upload";
	}else{
		$images_obj = new Images($dbconn);
		$upload_type = "f";
		$err = $images_obj->UploadImages($photo, $user[0], $upload_type, '', 0, $upload_comment, $id_ad,'rent');
		AdUpdateDate($id_ad);
	}
	EditProfile($back, $err);
	exit;
}

function JsUpload(){
	global $smarty, $config, $dbconn, $user, $lang;
	
	require_once "./include/JsHttpRequest.php";
	$JsHttpRequest =& new JsHttpRequest("utf-8");
	$lang["errors"] = GetLangContent("errors");
	$id_ad = intval($_GET["id_ad"]);
	$upload = $_FILES["q"];	
	$upload_comment = trim(strip_tags($_GET["upload_comment"]));
	$upload_type = trim(strip_tags($_GET["upload_type"]));

	if (BadWordsCont($upload_comment)){
		$err = "badword";
		echo $lang["errors"][$err];	
		exit;
	}	
			
	$settings = GetSiteSettings(array( 'photo_folder', 'photo_max_user_count', 'plan_photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height', 'video_max_count'));
	
	switch ($upload_type){
		case "f":
			$rs = $dbconn->Execute("SELECT COUNT(*) FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' AND upload_type='$upload_type'");
			$limit = $settings["photo_max_user_count"];
			break;
		case "plan":
			$rs = $dbconn->Execute("SELECT COUNT(id) FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."'");
			 
			$limit = $settings["plan_photo_max_user_count"];
			break;	
		case "v":
			$rs = $dbconn->Execute("select count(*) from ".USERS_RENT_UPLOADS_TABLE." where id_user='".$user[0]."' AND id_ad='".$id_ad."' and upload_type='v'");	
			$limit = $settings["video_max_count"];
		break;	
	}

	
	$upload_count = $rs->fields[0];
	
	if(intval($upload_count) >= intval($limit)){
		$err = "cant_upload";
		
	}else{
		switch ($upload_type){
			case "f":
			case "plan":
				$images_obj = new Images($dbconn);		
				$err = $images_obj->UploadImages($upload, $user[0], $upload_type, '', 0, $upload_comment, $id_ad,'rent');	
				AdUpdateDate($id_ad);	
				break;
			case "v":
				$err = SaveUploadForm($upload, 0, $upload_comment, 'v', $id_ad);
				break;	
		}
		
	}
	if ($err == "file_upload_without_approve" || $err == "file_upload"){		
		echo "||success||".$lang["errors"][$err];
	}else{
		echo $lang["errors"][$err];
	}
}

function UploadPlan($back){
	global $smarty, $config, $dbconn, $user;

	$id_ad = intval($_POST["id_ad"]);
	$plan = $_FILES["plan"];
	$upload_comment = trim(strip_tags($_POST["upload_comment"]));

	if (BadWordsCont($upload_comment)){
		EditProfile($back, "badword");
		exit;
	}	
	
	$settings = GetSiteSettings(array( 'photo_folder', 'plan_photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height'));

	$rs = $dbconn->Execute("SELECT COUNT(id) FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".intval($_POST["id_ad"])."'  ");
	if ($rs->fields[0]>intval($settings["plan_photo_max_user_count"])){
		EditProfile($back, "cant_upload");
		exit;
	}

	$images_obj = new Images($dbconn);
	$upload_type = "plan";
	$err = $images_obj->UploadImages($plan, $user[0], $upload_type, '', 0, $upload_comment, $id_ad, 'rent');
	AdUpdateDate($id_ad);
	EditProfile($back, $err);
	exit;
}

function PlanView() {
	global $smarty, $config, $dbconn, $user, $lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexHomePage('rentals','homepage');

	$id_file = intval($_GET["id_file"]);
	$rs = $dbconn->Execute("SELECT upload_path FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='".$user[0]."' AND id='".$id_file."'  ");
	$upload_file["file_name"] = $rs->fields[0];
	$upload_file["upload_type"] = "f";
	$folder = GetSiteSettings("photo_folder");
	$upload_file["file_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_name"];

	$smarty->assign("upload_file", $upload_file);

	$smarty->display(TrimSlash($config["index_theme_path"])."/upload_view.tpl");
	exit;
}

function PlanDelete(){
	global $config, $dbconn;
	
	$id_file = (isset($_REQUEST["id_file"]) && !empty($_REQUEST["id_file"])) ? intval($_REQUEST["id_file"]) : 0;
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
	$back = $_GET["back"];
	
	$photo_folder = GetSiteSettings('photo_folder');

	$rs = $dbconn->Execute("SELECT id, upload_path, sequence FROM ".USER_RENT_PLAN_TABLE." WHERE id='".$id_file."'");

	if ($rs->fields[0]>0){
		$sequence = $rs->fields[2];
		/**
		 * change sequence for all other pages of the same language
		 */
		$res = $dbconn->Execute("SELECT MAX(sequence) AS max_seq FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='$id_ad'");
		$max_sequence = ($res->RowCount() > 0) ? $res->fields[0] : 1;
		
		if ($sequence < $max_sequence) {
			$strSQL = "SELECT id FROM ".USER_RENT_PLAN_TABLE." ".
					  "WHERE sequence > '{$sequence}' AND id_ad='$id_ad'";
			$res_files = $dbconn->Execute($strSQL);
			while (!$res_files->EOF) {
				$strSQL = "UPDATE ".USER_RENT_PLAN_TABLE." SET ".
				 		  "sequence = sequence-1 ".
						  "WHERE id = '{$res_files->fields[0]}'";
				$dbconn->Execute($strSQL);
				$res_files->MoveNext();
			}
		}
		
		unlink($config["site_path"].$photo_folder."/".$rs->fields[1]);
		unlink($config["site_path"].$photo_folder."/thumb_".$rs->fields[1]);
		$dbconn->Execute("DELETE FROM ".USER_RENT_PLAN_TABLE." WHERE id='".$id_file."' ");
	}
	EditProfile($back, '', 2, $id_ad);
	exit;
}


function UploadView(){
	global $smarty, $config, $dbconn, $user, $lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexHomePage('rentals','homepage');

	$id_file = intval($_GET["id_file"]);

	$rs = $dbconn->Execute("SELECT upload_path, upload_type, user_comment
							from ".USERS_RENT_UPLOADS_TABLE."
							where id='".$id_file."'");

	$upload_file["file_name"] = $rs->fields[0];
	$upload_file["upload_type"] = $rs->fields[1];
	$upload_file["user_comment"] = stripslashes($rs->fields[2]);


	switch($upload_file["upload_type"]){
		case "f":		$folder = GetSiteSettings("photo_folder");	break;
		case "a":		$folder = GetSiteSettings("audio_folder");	break;
		case "v":		$folder = GetSiteSettings("video_folder");	break;
	}
		
	$is_flv = intval($_GET["is_flv"]);	
	if (GetSiteSettings("use_ffmpeg") && $is_flv) {				
		$flv_name = explode('.', $upload_file["file_name"]);
		$upload_file["file_icon"] = $flv_name[0]."1.jpg";
		$upload_file["file_name"] = $flv_name[0].".flv";
		$upload_file["icon_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_icon"];
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


function UploadDelete() {
	global $config, $dbconn, $user;
	
	$id_file = (isset($_REQUEST["file_id"]) && !empty($_REQUEST["file_id"])) ? intval($_REQUEST["file_id"]) : 0;
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
	$ajax = (isset($_REQUEST["ajax"]) && !empty($_REQUEST["ajax"])) ? intval($_REQUEST["ajax"]) : 0;
	$upload_type = (isset($_REQUEST["file_type"]) && !empty($_REQUEST["file_type"])) ? htmlspecialchars($_REQUEST["file_type"]) : '';
	$back = $_GET["back"];
	switch ($upload_type){
		case "f":
			$table = USERS_RENT_UPLOADS_TABLE;	
			$par = "step_4";
			$folder = GetSiteSettings("photo_folder");
			break;
		case "v":
			$table = USERS_RENT_UPLOADS_TABLE;	
			$par = "upload_video";
			$folder = GetSiteSettings("video_folder");
			break;
		case "plan":
			$table = USER_RENT_PLAN_TABLE;
			$par = "step_7";
			$folder = GetSiteSettings("photo_folder");
			break;	
	}
	$rs = $dbconn->Execute("SELECT id, upload_path, sequence
							FROM ".$table."
							WHERE id='".$id_file."' AND id_user='".$user[0]."'");
	if ($rs->fields[0]>0) {
		$sequence = $rs->fields[2];
		/**
		 * change sequence for all other pages of the same language
		 */
		if ($upload_type == "plan"){
			$res = $dbconn->Execute("SELECT MAX(sequence) AS max_seq FROM ".$table." WHERE id_ad='$id_ad'");
		}else{
			$res = $dbconn->Execute("SELECT MAX(sequence) AS max_seq FROM ".$table." WHERE id_ad='$id_ad' AND upload_type='$upload_type'");
		}
		$max_sequence = ($res->RowCount() > 0) ? $res->fields[0] : 1;
		
		if ($sequence < $max_sequence) {
			if ($upload_type == "plan"){
				$strSQL = "SELECT id FROM ".$table." ".
						  "WHERE sequence > '{$sequence}' AND id_ad='$id_ad'";
			}else{
				$strSQL = "SELECT id FROM ".$table." ".
						  "WHERE sequence > '{$sequence}' AND id_ad='$id_ad' AND upload_type='$upload_type'";
			}
			$res_files = $dbconn->Execute($strSQL);
			while (!$res_files->EOF) {
				$strSQL = "UPDATE ".$table." SET ".
				 		  "sequence = sequence-1 ".
						  "WHERE id = '{$res_files->fields[0]}'";
				$dbconn->Execute($strSQL);
				$res_files->MoveNext();
			}
		}
		
		unlink($config["site_path"].$folder."/".$rs->fields[1]);
		if (file_exists($config["site_path"].$folder."/thumb_".$rs->fields[1])){
			unlink($config["site_path"].$folder."/thumb_".$rs->fields[1]);
		}
		if (file_exists($config["site_path"].$folder."/thumb_big_".$rs->fields[1])){
			unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[1]);
		}
		
		if ($upload_type == "v") {
			$flv_name = explode('.', $rs->fields[1]);
			if (file_exists($config["site_path"].$folder."/".$flv_name[0]."1.jpg")){
				unlink($config["site_path"].$folder."/".$flv_name[0]."1.jpg");
			}
			if (file_exists($config["site_path"].$folder."/".$flv_name[0].".flv")){
				unlink($config["site_path"].$folder."/".$flv_name[0].".flv");			
			}		
		}
		
		$dbconn->Execute("DELETE FROM ".$table." WHERE id='".$id_file."'");
	}
	if ($ajax){
		EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);
		exit();
	}else{
		EditProfile($back);
	}
	exit;
}

function UploadActivate($status){
	global $config, $dbconn;
	$id_file = (isset($_REQUEST["file_id"]) && !empty($_REQUEST["file_id"])) ? intval($_REQUEST["file_id"]) : 0;
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
	$ajax = (isset($_REQUEST["ajax"]) && !empty($_REQUEST["ajax"])) ? intval($_REQUEST["ajax"]) : 0;
	$upload_type = (isset($_REQUEST["file_type"]) && !empty($_REQUEST["file_type"])) ? htmlspecialchars($_REQUEST["file_type"]) : $_REQUEST["subsel"];	

	$table_name = ($upload_type == "plan") ? USER_RENT_PLAN_TABLE : USERS_RENT_UPLOADS_TABLE;
	$rs = $dbconn->Execute("UPDATE $table_name SET status='$status' WHERE id='".$id_file."'");
	
	switch ($upload_type){
		case "f":
			$par = "step_4";
			break;
		case "v":
			$par = "upload_video";
			break;
		case "plan":
			$par = "step_7";
			break;	
	}
	
	if ($ajax){
		EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);
	}else{
		EditProfile($_GET["back"]);
	}

	
	exit;
}

function UploadEditComment() {
	global $config, $dbconn;
	$id_file = (isset($_REQUEST["file_id"]) && !empty($_REQUEST["file_id"])) ? intval($_REQUEST["file_id"]) : 0;
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
	$ajax = (isset($_REQUEST["ajax"]) && !empty($_REQUEST["ajax"])) ? intval($_REQUEST["ajax"]) : 0;
	$upload_type = (isset($_REQUEST["file_type"]) && !empty($_REQUEST["file_type"])) ? htmlspecialchars($_REQUEST["file_type"]) : $_REQUEST["subsel"];	
	$back = $_GET["back"];

	$table_name = ($upload_type == "plan") ? USER_RENT_PLAN_TABLE : USERS_RENT_UPLOADS_TABLE;
	
	$upload_comment = trim(strip_tags(str_replace("<br>", "\r\n", $_REQUEST["edit_upload_comment"])));
	switch ($upload_type){
		case "f":
			$par = "step_4";
			break;
		case "v":
			$par = "upload_video";
			break;
		case "plan":
			$par = "step_7";
			break;	
	}

	if (BadWordsCont($upload_comment)){
		EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);
		exit;
	}	
	
	$dbconn->Execute("UPDATE $table_name SET user_comment='".addslashes($upload_comment)."' WHERE id='".$id_file."'");

	if ($ajax){
		EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);
	}else{
		EditProfile($_GET["back"]);
	}
	exit;
}

function ListUserAds($err=''){
	global $smarty, $config, $dbconn, $user, $lang, $REFERENCES;
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";

	unset($_SESSION["step_1"]);
	unset($_SESSION["step_3"]);
	unset($_SESSION["step_4"]);
	unset($_SESSION["step_5"]);
	unset($_SESSION["from_edit"]);

	IndexHomePage('rentals','homepage');

	$smarty->assign("submenu", "edit_rentals");

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$page = (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 1;	
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 0;
	$sorter_order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? $_REQUEST["order"] : 1;
	
	$smarty->assign("sorter", $sorter);

	$param = $file_name."?sel=list_ads&amp;";
	$order_link = "&sel=list_ads&page=".$page;
	
	$strSQL = "SELECT id, type, datenow, status FROM ".RENT_ADS_TABLE." WHERE id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	$ads = array();
	$ads_ids = array();
	while (!$rs->EOF) {
		$ads[] = $rs->getRowAssoc(false);
		$ads_ids[] = $rs->fields[0];
		$rs->MoveNext();
	}	
	
	if (count($ads) == 0){
		EditProfile("step_1");
		exit;
	} else {
		getSearchArr($ads_ids, $file_name, $page, $param, $order_link, $sorter, $sorter_order, "user_ads");
			
		$ad_position = array();	
		foreach ($ads as $listing) {
			$ad_position[$listing["id"]] = VisitedMyAd($listing["id"]);		
			$ad_position[$listing["id"]]["place"] = GetAdPlace($listing["id"], $listing["type"], $listing["datenow"], $listing["status"]);		
		}			
		$smarty->assign("ad_position", $ad_position);
	}
				
	if ($err){
		GetErrors($err);
	}

	$use_private_person_ads_limit = GetSiteSettings("use_private_person_ads_limit");
	if ($use_private_person_ads_limit && $user[10] == 1) {
		//if use limit and user is private person
		$smarty->assign("private_person_ads_limit", GetSiteSettings("private_person_ads_limit"));
	}
	$smarty->assign("user_ads_cnt", GetUserAdsNumber($user[0]));

	$smarty->assign("add_rent_link", $file_name."?sel=add_rent");
	$smarty->assign("add_to_lang","&amp;sel=list_ads&amp;page=".$page);
	$smarty->display(TrimSlash($config["index_theme_path"])."/rentals_list.tpl");
	exit;
}

function DelUserAd(){
	global $smarty, $config, $dbconn, $user, $REFERENCES;

	$id_ad = intval($_GET["id_ad"]);
	$strSQL = " SELECT id FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' ";
	$rs = $dbconn->Execute($strSQL);
	if (intval($rs->fields[0]) === $id_ad){
		//slideshow
		$photo_folder = GetSiteSettings("photo_folder");
		$strSQL = "SELECT upload_path FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->RowCount()>0){
			while (!$rs->EOF) {
				if ($rs->fields[0] != "") { 
					unlink($config["site_path"].$photo_folder."/".$rs->fields[0]);
				}	
				$rs->MoveNext();
			}
		}

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
		if ($rs->fields[0]>0){
			while(!$rs->EOF){
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
		if ($rs->fields[0]>0){
			while(!$rs->EOF){
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
		if ($rs->fields[0]>0){
			while(!$rs->EOF){
				unlink($config["site_path"].$folder."/".$rs->fields[0]);
				unlink($config["site_path"].$folder."/thumb_".$rs->fields[0]);
				unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[0]);
				$rs->MoveNext();
			}
		}
		$dbconn->Execute("DELETE FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$id_ad."' ");

		$dbconn->Execute("DELETE FROM ".COMPARISON_LIST_TABLE." WHERE id_ad='".$id_ad."' ");
	}
	ListUserAds();
	exit;
}

function UserAd($par=''){
	global $smarty, $config, $dbconn, $user, $REFERENCES;

	$id_ad = intval($_GET["id_ad"]);
		
	$ajax = isset($_REQUEST["ajax"]) ? intval($_REQUEST["ajax"]) : 0;
	if ($ajax){
		EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);
		exit();
	}

	$strSQL = "	SELECT type, UNIX_TIMESTAMP(movedate) as movedate, people_count,
				with_photo, with_video
				FROM ".RENT_ADS_TABLE."
				WHERE id='".$id_ad."' AND id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$ad["type"] = $row["type"];
	$ad["movedate"] = $row["movedate"];
	$ad["people_count"] = $row["people_count"];
	$data_1["with_photo"] = $row["with_photo"];
	$data_1["with_video"] = $row["with_video"];

	if (empty($ad["movedate"])) {
		$ad["movedate"] = mktime(0,0,0, date("m"), date("d")+1, date("Y"));
	}

	$used_references = array("info", "period", "realty_type", "description");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data_1[$arr["key"]] = SprTableSelect($arr["spr_user_table"], $id_ad, $user[0], $arr["spr_table"]);
		}
	}

	$used_references = array("gender", "people", "language");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data_2[$arr["key"]] = SprTableSelect($arr["spr_user_table"], $id_ad, $user[0], $arr["spr_table"]);
			$data_2[$arr["key"]."_match"] = SprTableSelect($arr["spr_match_table"], $id_ad, $user[0], $arr["spr_table"]);
		}
	}
	
	
	if ($ad["type"] == "1" || $ad["type"] == "3") {
		$strSQL = "SELECT id_country, id_region, id_city, zip_code, street_1, street_2, adress FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_location["country"] = stripslashes($row["id_country"]);
		$data_location["region"] = stripslashes($row["id_region"]);
		$data_location["city"] = stripslashes($row["id_city"]);

		$data_location["zip_code"] = stripslashes($row["zip_code"]);
		$data_location["cross_streets_1"] = htmlspecialchars(stripslashes($row["street_1"]));
		$data_location["cross_streets_2"] = htmlspecialchars(stripslashes($row["street_2"]));
		$data_location["adress"] = htmlspecialchars(stripslashes($row["adress"]));

		$_SESSION["step_1"] = $data_location;

		$strSQL = " SELECT min_payment, max_payment, auction, min_deposit, max_deposit,
					min_live_square, max_live_square, min_total_square, max_total_square,
					min_land_square, max_land_square, min_floor,  max_floor, floor_num, subway_min,
					min_year_build, max_year_build
					FROM ".USERS_RENT_PAYS_TABLE."
					WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."'";

		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_1["min_payment"] = $row["min_payment"];
		$data_1["max_payment"] = $row["max_payment"];
		$data_1["act_status"] = ($row["max_payment"] <= 0) ? 0 : 1;
		$data_1["auction"] = $row["auction"];
		$data_1["min_deposit"] = $row["min_deposit"];
		$data_1["max_deposit"] = $row["max_deposit"];
		$data_1["min_live_square"] = $row["min_live_square"];
		$data_1["max_live_square"] = $row["max_live_square"];
		$data_1["min_total_square"] = $row["min_total_square"];
		$data_1["max_total_square"] = $row["max_total_square"];
		$data_1["min_land_square"] = $row["min_land_square"];
		$data_1["max_land_square"] = $row["max_land_square"];
		$data_1["min_floor"] = $row["min_floor"];
		$data_1["max_floor"] = $row["max_floor"];
		$data_1["floor_num"] = $row["floor_num"];
		$data_1["subway_min"] = $row["subway_min"];
		$data_1["min_year_build"] = $row["min_year_build"];
		$data_1["max_year_build"] = $row["max_year_build"];

		$data_1["move_year"] = date("Y", $ad["movedate"]);
		$data_1["move_month"] = date("m", $ad["movedate"]);
		$data_1["move_day"] = date("d", $ad["movedate"]);
		$_SESSION["from_edit"] = 1;

		$_SESSION["step_3"] = $data_1;

		$data_2["total_people"] = $ad["people_count"];

		$_SESSION["step_4"] = $data_2;
	} elseif ($ad["type"] == "2" || $ad["type"] == "4") {
		$strSQL = "SELECT id_country, id_region, id_city, zip_code, street_1, street_2, adress FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_location["country"] = stripslashes($row["id_country"]);
		$data_location["region"] = stripslashes($row["id_region"]);
		$data_location["city"] = stripslashes($row["id_city"]);

		
		$data_location["zip_code"] = stripslashes($row["zip_code"]);
		$data_location["cross_streets_1"] = htmlspecialchars(stripslashes($row["street_1"]));
		$data_location["cross_streets_2"] = htmlspecialchars(stripslashes($row["street_2"]));
		$data_location["adress"] = htmlspecialchars(stripslashes($row["adress"]));

		$_SESSION["step_1"] = $data_location;

		$data_1["move_year"] = date("Y", $ad["movedate"]);
		$data_1["move_month"] = date("m", $ad["movedate"]);
		$data_1["move_day"] = date("d", $ad["movedate"]);

		$strSQL = "	SELECT min_payment, auction, min_deposit,
					min_live_square, min_total_square,
					min_land_square, min_floor, floor_num, subway_min, min_year_build
					FROM ".USERS_RENT_PAYS_TABLE."
					WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_1["min_payment"] = $row["min_payment"];
		$data_1["act_status"] = ($row["min_payment"] <= 0) ? 0 : 1;		
		$data_1["auction"] = $row["auction"];
		$data_1["min_deposit"] = $row["min_deposit"];
		$data_1["min_live_square"] = $row["min_live_square"];
		$data_1["min_total_square"] = $row["min_total_square"];
		$data_1["min_land_square"] = $row["min_land_square"];
		$data_1["min_floor"] = $row["min_floor"];
		$data_1["floor_num"] = $row["floor_num"];
		$data_1["subway_min"] = $row["subway_min"];
		$data_1["min_year_build"] = $row["min_year_build"];

		$_SESSION["from_edit"] = 1;
		$_SESSION["step_3"] = $data_1;


		$_SESSION["step_5"] = $data_2;
	}

	if ($par) {
		EditProfile($par,'',$ad["type"],$id_ad);
	} else {
		EditProfile("step_1",'',$ad["type"],$id_ad);
	}
	exit;
}


function UploadVideo($back){
	global $smarty, $config, $dbconn, $user;
	$id_ad = (isset($_POST["id_ad"])) ? intval($_POST["id_ad"]) : 0;
	
	$video = (isset($_FILES["video"])) ? $_FILES["video"] : 0;		
	$upload_comment = (isset($_POST["upload_comment"])) ? trim(strip_tags($_POST["upload_comment"])) : "";

	if (BadWordsCont($upload_comment)){
		EditProfile($back, "badword");
		exit;
	}

	$settings = GetSiteSettings("video_max_count");

	$rs = $dbconn->Execute("select count(*) from ".USERS_RENT_UPLOADS_TABLE." where id_user='".$user[0]."' AND id_ad='".$id_ad."' and upload_type='v'");	
	$video_count = $rs->fields[0];
	
	if(intval($video_count) >= intval($settings["video_max_count"])){
		$err = "cant_upload";		
	}else{
		$err = SaveUploadForm($video, 0, $upload_comment, 'v', $id_ad);						
	}
	EditProfile($back, $err);
	exit;
}

function SaveUploadForm($upload, $id_file, $user_comment, $upload_type='v', $id_ad, $admin_mode=0, $id_user_admin_mode=""){
	global $smarty, $dbconn, $config, $user, $VIDEO_TYPE_ARRAY, $VIDEO_EXT_ARRAY;

	$err = "";
	$id = intval($user[0]);
	$settings = GetSiteSettings(array("use_video_approve", "video_max_count", "video_max_size", "video_folder",
				 "use_ffmpeg", "path_to_ffmpeg", "flv_output_dimension", "flv_output_audio_sampling_rate", "flv_output_audio_bit_rate", "flv_output_foto_dimension"));
					 

	switch($upload_type){
		/*case "a":
			$folder = $settings["audio_folder"];
			$type_array = $AUDIO_TYPE_ARRAY;
			$max_size = $settings["audio_max_size"];
			$err_type = "invalid_audio_type";
			$err_size = "invalid_audio_size";
			$use_approve = $settings["use_audio_approve"];
			break;*/
		case "v":
			$folder = $settings["video_folder"];
			$type_array = $VIDEO_TYPE_ARRAY;
			$ext_array = $VIDEO_EXT_ARRAY;
			$max_size = $settings["video_max_size"];
			
			$err_type = "invalid_video_type";
			$err_size = "invalid_video_size";
			$use_approve = $settings["use_video_approve"];
			break;
	}
	$new_temp_path = GetTempUploadFile($upload["name"]);

	/// for save mode restrict
	if(is_uploaded_file($upload["tmp_name"]) && move_uploaded_file($upload["tmp_name"],$new_temp_path)){
		$upload["tmp_name"] = $new_temp_path;
	}
	$filename_arr = explode(".", $upload["name"]);
	$nr = count($filename_arr);
	$ext = strtolower($filename_arr[$nr-1]);	
	
	if (!in_array($upload["type"], $type_array) || !in_array($ext, $ext_array) ){
		$err .= $err_type;
	}
	if($upload["size"] > $max_size){
		if($err)$err .= "<br>";
		$err .= $err_size;
	}
	if($err){
		return $err;
	}else{
		$new_file_name = GetNewFileName($upload["name"], $id);
		$upload_path =$config["site_path"].$folder."/".$new_file_name;
		if(copy($upload["tmp_name"], $upload_path)){
			unlink($upload["tmp_name"]);
			DeleteUploadedFiles($upload_type, $id_file);
			if ($upload_type == "v" && $settings['use_ffmpeg'] == 1) {	
				
				$new_file_name_arr = explode(".", $new_file_name);
				$flv_name = $new_file_name_arr[0].".flv";
				$flv_path = $config["site_path"].$folder."/".$flv_name;				
				
								
				@exec($settings['path_to_ffmpeg']."ffmpeg.exe -y -i ".$upload_path." -s ".$settings['flv_output_dimension']."  -ar ".$settings['flv_output_audio_sampling_rate']." -ab ".$settings['flv_output_audio_bit_rate']." ".$flv_path, $res);	
				
				@exec($settings['path_to_ffmpeg']."ffmpeg.exe -i ".$upload_path." -an -ss 00:00:00 -t 00:00:01 -r 1 -y -s ".$settings['flv_output_foto_dimension']." ".$config["site_path"].$folder."/".$new_file_name_arr[0]."%d.jpg ", $res);
				
			}
			///// insert entry into db
			$admin_approve = (intval($use_approve)) ? 0 : 1;
			
			$strSQL = "SELECT MAX(sequence) AS max_seq FROM ".USERS_RENT_UPLOADS_TABLE." WHERE upload_type='".$upload_type."' AND id_ad='$id_ad'";
			$rs = $dbconn->Execute($strSQL);
			$sequence = ($rs->RowCount() > 0) ? $rs->fields[0]+1 : 1;
				
			$strSQL = "INSERT INTO ".USERS_RENT_UPLOADS_TABLE." (id_user, id_ad, upload_path, upload_type, file_type, admin_approve, user_comment, sequence) VALUES ('".$id."', '".$id_ad."', '".$new_file_name."', '".$upload_type."', '".$upload["type"]."', '".$admin_approve."', '".$user_comment."', '$sequence')";
			$dbconn->Execute($strSQL);
			
			AdUpdateDate($id_ad);
		}else{
			return  "upload_err";
		}
	}
	return ((intval($use_approve)) ? "file_upload" : "file_upload_without_approve");
}


/**
 * Get bonus amount, which client could recieve on the percent of listing completion
 *
 * @param integer $percent
 * @param array $bonus_arr
 * @return integer
 */
function GetBonusForPercent($percent, $bonus_arr) {
	$can_recieve = 0;
	$bonus_arr_cnt = count($bonus_arr);
	if ($bonus_arr_cnt > 0) {
		$find_key = -1;
		foreach ($bonus_arr as $bonus_arr_key=>$bonus_arr_item) {
			if ($percent >= $bonus_arr_item["percent"]) {
				$find_key++;
			} else {
				break;
			}
		}
		if ($find_key < 0) {
			$can_recieve = 0;
		} else {
			$can_recieve = $bonus_arr[$find_key]["amount"];
		}
	}
	return $can_recieve;
}

function GetFreeDays() {
	header('Content-type: text/html; charset=utf-8');
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$year = isset($_REQUEST["year"]) ? intval($_REQUEST["year"]) : "";		
	$month = isset($_REQUEST["month"]) ? intval($_REQUEST["month"]) : "";		
	$amount_days = adodb_date( "t", adodb_mktime(0, 0, 0, $month, 1, $year) );
			
	$calendar_event = new CalendarEvent();
	$reserve_days = $calendar_event->GetReserveDays($id_ad, $user[0]);
	$available_date = $calendar_event->GetMovedate($id_ad);
	$echo_str="";
	for ($i = 1; $i <= $amount_days; $i++){
		$res_or_empty = "e";
		$this_day_start  = adodb_mktime(0, 0, 0, $month, $i, $year);
		$this_day_end  = adodb_mktime(23, 59, 59, $month, $i, $year);
		if ($this_day_start < $available_date){
			$res_or_empty = "na";
		}else{
			foreach ($reserve_days AS $period){
				if ( $period["start_tmstmp"] <= $this_day_start && $period["end_tmstmp"] >= $this_day_end){
					$res_or_empty = "r";									
				}			
			}
		}
		$echo_str .= $i."_".$res_or_empty."|";
	}	
	$echo_str=substr($echo_str,0,strlen($echo_str)-1);
	echo $echo_str;			
}

function GetHourByDate() {
	header('Content-type: text/html; charset=utf-8');
	global $smarty, $dbconn, $user;	
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$day = isset($_REQUEST["day"]) ? intval($_REQUEST["day"]) : "";		
	$year = isset($_REQUEST["year"]) ? intval($_REQUEST["year"]) : "";		
	$month = isset($_REQUEST["month"]) ? intval($_REQUEST["month"]) : "";		
	
	$this_day_start  = adodb_mktime(0, 0, 0, $month, $day, $year);
	$this_day_end  = adodb_mktime(23, 59, 59, $month, $day, $year);
			
	$calendar_event = new CalendarEvent();	
	$reserve_days = $calendar_event->GetReserveDays($id_ad, $user[0]);			
	$echo_str="";	
		foreach ($reserve_days AS $period){			
			if ( $period["start_tmstmp"] <= $this_day_start && $period["end_tmstmp"] >= $this_day_start){
				$echo_str .= "00:00"." - ".date("H:i",$period["end_tmstmp"])."|";
			}elseif ( $period["start_tmstmp"] >= $this_day_start && $period["end_tmstmp"] <= $this_day_end){
				$echo_str .= date("H:i",$period["start_tmstmp"])." - ".date("H:i",$period["end_tmstmp"])."|";
			}elseif ( $period["start_tmstmp"] <= $this_day_end && $period["end_tmstmp"] >= $this_day_end){
				$echo_str .= date("H:i",$period["start_tmstmp"])." - "."23:59"."|";
			}			
		}		
	$echo_str=substr($echo_str,0,strlen($echo_str)-1);	
	echo $echo_str;
		
}

function AddEvent($show = '1', $id_event = "") {
	global $smarty, $dbconn, $user;
	$id_ad = isset($_POST["id_ad"]) ? intval($_POST["id_ad"]) : "";		
	$user_id = $user[0];
	$year_from = isset($_POST["id_year_select_from"]) ? intval($_POST["id_year_select_from"]) : "";		
	$month_from = isset($_POST["id_month_select_from"]) ? intval($_POST["id_month_select_from"]) : "";		
	$day_from = isset($_POST["id_day_select_from"]) ? intval($_POST["id_day_select_from"]) : "";		
	$hour_from = isset($_POST["id_hour_select_from"]) ? intval($_POST["id_hour_select_from"]) : "";		
	$minute_from = isset($_POST["id_minute_select_from"]) ? intval($_POST["id_minute_select_from"]) : "";		
	$year_to = isset($_POST["id_year_select_from"]) ? intval($_POST["id_year_select_to"]) : "";		
	$month_to = isset($_POST["id_month_select_from"]) ? intval($_POST["id_month_select_to"]) : "";		
	$day_to = isset($_POST["id_day_select_from"]) ? intval($_POST["id_day_select_to"]) : "";		
	$hour_to = isset($_POST["id_hour_select_to"]) ? intval($_POST["id_hour_select_to"]) : "";		
	$minute_to = isset($_POST["id_minute_select_to"]) ? intval($_POST["id_minute_select_to"]) : "";		
	
	$date_start = adodb_mktime($hour_from, $minute_from, 0, $month_from, $day_from, $year_from);		
	$date_end = adodb_mktime($hour_to, $minute_to, 0, $month_to, $day_to, $year_to);
	$calendar_event = new CalendarEvent();	
	$err = $calendar_event->AddTimePeriod($id_ad, $user_id, $date_start, $date_end, $id_event);	
	if ($show == 1){
		CalendarAd("add_event", $err, $date_start, $date_end);	
	}
	if ($show == 2){
		return $err;		
	}
}

function DeleteEvent($show = '1') {
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$id_event = isset($_REQUEST["id_event"]) ? intval($_REQUEST["id_event"]) : "";			
	
	$calendar_event = new CalendarEvent();	
	$calendar_event->DeleteEvent($id_event, $id_ad);
	if ($show == 1){
		MyAd($id_ad);
	}
}

function EditEvent() {
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$id_event = isset($_REQUEST["id_event"]) ? intval($_REQUEST["id_event"]) : "";		
	$user_id = $user[0];	
	$calendar_event = new CalendarEvent();	
	$edited_event = $calendar_event->GetReserveDays($id_ad, $user_id, $id_event);			
	CalendarAd("edited_event", "", $edited_event[0]["start_tmstmp"], $edited_event[0]["end_tmstmp"], $id_event);	
}

function EditedEvent() {
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$id_event = isset($_REQUEST["id_event"]) ? intval($_REQUEST["id_event"]) : "";			
	$user_id = $user[0];	

	$year_from = isset($_POST["id_year_select_from"]) ? intval($_POST["id_year_select_from"]) : "";		
	$month_from = isset($_POST["id_month_select_from"]) ? intval($_POST["id_month_select_from"]) : "";		
	$day_from = isset($_POST["id_day_select_from"]) ? intval($_POST["id_day_select_from"]) : "";		
	$hour_from = isset($_POST["id_hour_select_from"]) ? intval($_POST["id_hour_select_from"]) : "";		
	$minute_from = isset($_POST["id_minute_select_from"]) ? intval($_POST["id_minute_select_from"]) : "";		
	$year_to = isset($_POST["id_year_select_from"]) ? intval($_POST["id_year_select_to"]) : "";		
	$month_to = isset($_POST["id_month_select_from"]) ? intval($_POST["id_month_select_to"]) : "";		
	$day_to = isset($_POST["id_day_select_from"]) ? intval($_POST["id_day_select_to"]) : "";		
	$hour_to = isset($_POST["id_hour_select_to"]) ? intval($_POST["id_hour_select_to"]) : "";		
	$minute_to = isset($_POST["id_minute_select_to"]) ? intval($_POST["id_minute_select_to"]) : "";		
	
	$date_start = adodb_mktime($hour_from, $minute_from, 0, $month_from, $day_from, $year_from);	
	$date_end = adodb_mktime($hour_to, $minute_to, 0, $month_to, $day_to, $year_to);
	$calendar_event = new CalendarEvent();		
	
	$edited_event = $calendar_event->GetReserveDays($id_ad, $user_id, $id_event);							
	DeleteEvent(0);	
	$err = AddEvent(2, $id_event);
		
	if ($err != "err_period_added"){			
		$calendar_event->AddTimePeriod($id_ad, $user_id, $edited_event[0]["start_tmstmp"], $edited_event[0]["end_tmstmp"], $id_event);
		CalendarAd("edited_event", $err, $edited_event[0]["start_tmstmp"], $edited_event[0]["end_tmstmp"], $id_event);		
	} else {			
		CalendarAd("edited_event", "successful_edit", $date_start, $date_end, $id_event);	
	}
			
}

/**
 * Prepare to change sequence for the file attached to the listing
 *
 * @param string $action - up/down (change sequence for one step up or down)
 * @return void
 */
function FileSequence($action) {
	global $config;
	
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
	$ajax = (isset($_REQUEST["ajax"]) && !empty($_REQUEST["ajax"])) ? intval($_REQUEST["ajax"]) : 0;
	$file_id = (isset($_REQUEST["file_id"]) && !empty($_REQUEST["file_id"])) ? intval($_REQUEST["file_id"]) : 0;	
	$file_type = (isset($_REQUEST["file_type"]) && !empty($_REQUEST["file_type"])) ? trim($_REQUEST["file_type"]) : "";
	$from_sel = (isset($_REQUEST["from_sel"]) && !empty($_REQUEST["from_sel"])) ? trim($_REQUEST["from_sel"]) : "";

	if ($id_ad && $file_id && $file_type != "" && ($from_sel != "" || $ajax)) {
		switch ($file_type) {
			case "f": {
				$upload_type = "f";
				$table_name = USERS_RENT_UPLOADS_TABLE;
				$par = "step_4";
			}
			break;
			case "v": {
				$upload_type = "v";
				$table_name = USERS_RENT_UPLOADS_TABLE;
				$par = "upload_video";
			}
			break;
			case "plan": {
				$upload_type = "";
				$table_name = USER_RENT_PLAN_TABLE;
				$par = "step_7";
			}
			break;
		}
		
		FileChangeSequence($id_ad, $file_id, $table_name, $upload_type, $action);
		if ($ajax){			
			EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);		
		}else{
			header("Location: ".$config["server"].$config["site_root"]."/rentals.php?sel=$from_sel&id_ad=$id_ad");
		}
		exit();
	} else {
		if ($ajax){
			EditProfile($par,"",intval($_REQUEST["choise"]), $id_ad);
		}else{
			header("Location: ".$config["server"].$config["site_root"]."/rentals.php?sel=list_ads");
		}
		exit();	
	}
	
}

/**
 * Change sequence for the file attached to the listing
 *
 * @param integer $id_ad
 * @param integer $file_id
 * @param string $table_name
 * @param string $upload_type
 * @param string $action
 * @return 
 */
function FileChangeSequence($id_ad, $file_id, $table_name, $upload_type, $action) {
	global $dbconn;
	
	$strSQL = "SELECT sequence FROM $table_name WHERE id='$file_id'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs !== false) {
		$cur_sequence = $rs->fields[0];		
		
		$strSQL = "SELECT id, sequence FROM $table_name ";
		if ($action == "up") {
			$strSQL .= "WHERE sequence < '$cur_sequence' AND sequence > '0' AND ";
		} elseif ($action == "down") {
			$strSQL .= "WHERE sequence > '$cur_sequence' AND ";
		}		
		$strSQL .= "id_ad = '$id_ad' ";
		if ($upload_type != "") {
			$strSQL .= "AND upload_type = '$upload_type' ";
		}			  
		if ($action == "up") {
			$strSQL .= "ORDER BY sequence DESC LIMIT 1";
		} elseif ($action == "down") {
			$strSQL .= "ORDER BY sequence ASC LIMIT 1";
		}	
		$rs = $dbconn->Execute($strSQL);
		
		if ($rs->RowCount() > 0) {
			$neighbour = $rs->GetRowAssoc(false);

			$strSQL = "UPDATE $table_name SET ".
			 		  "sequence = '{$neighbour["sequence"]}'".
					  "WHERE id = '$file_id'";
			$rs = $dbconn->Execute($strSQL);

			$strSQL = "UPDATE $table_name SET ".
			 		  "sequence = '$cur_sequence'".
					  "WHERE id = '{$neighbour["id"]}'";
			$rs = $dbconn->Execute($strSQL);
		}
	}	
}
?>