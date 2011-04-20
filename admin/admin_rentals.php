<?php
/**
* Users listings management (add, edit, delete)
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.7 $ $Date: 2009/01/13 14:39:11 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_common.php";

include "../include/functions_xml.php";
include "../include/class.object2xml.php";
include "../include/class.lang.php";
include "../include/class.images.php";
include "../include/class.calendar_event.php";

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

session_register("step_1");
session_register("step_3");
session_register("step_4");
session_register("step_5");
session_register("from_edit");

$smarty->assign("user_type", $auth[10]);
$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));

if (isset($_REQUEST["sub_sel"]) && !empty($_REQUEST["sub_sel"])) {
	$smarty->assign("sub_sel", $_REQUEST["sub_sel"]);
}

if (isset($_REQUEST["err"]) && !empty($_REQUEST["err"])) {
	GetErrors($_REQUEST["err"]);
}
			
$multi_lang = new MultiLang($config, $dbconn);
$lang["testimonials_block"] = GetLangContent('testimonials_block');

if ($auth[3] != 1) {
	@$sel = ($_POST["sel"]) ? $_POST["sel"] : $_GET["sel"];
} else {
	@$sel = '';
}
if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
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
	case "plan_delete":		PlanDelete(); break;

	case "my_ad":			MyAd(); break;

	case "add_rent":		EditProfile("step_1"); break;
  case "add_child":		EditProfile("add_child"); break;
	case "step_1":			UserAd("step_1"); break;
	case "step_3":			UserAd("step_3"); break;
	case "step_4":			UserAd("step_4"); break;
	case "step_5":			UserAd("step_5"); break;
	case "step_6":			UserAd("step_6"); break;
	case "step_7":			UserAd("step_7"); break;
	case "step_8":			UserAd("step_8"); break;
  case "step_type":			UserAd("step_type"); break;
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
  case "finish_2":			SaveProfile("finish_2"); break;

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
	/// Платные услуги
	case "top_search_ad":	TopSearchAd(); break;
	case "slideshow_ad":	SlideShow(); break;
	case "feature_ad":		MakeAdFeatured(); break;
	case "get_free_days":	GetFreeDays(); break;
	case "get_hour_by_date":GetHourByDate();break;

	case "file_up": 		FileSequence("up"); break;
	case "file_down": 		FileSequence("down"); break;
	
	default:				ListUserAds(); break;
}


function DbOperation() {
	global $smarty, $config, $dbconn, $auth, $lang, $multi_lang;

	$smarty->assign("submenu", "edit_rentals");
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($auth[0]);
	$smarty->assign("link_count",$link_count);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	$strSQL = "SELECT count(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$auth[0]."' ";
	$rs = $dbconn->Execute($strSQL);

	$num_records = $rs->fields[0];
  $smarty->assign("num_records", $num_records);
  
  $strSQL = "SELECT count(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$auth[0]."' AND parent_id = 0 ";
	$rs = $dbconn->Execute($strSQL);

	$num_records_parent = $rs->fields[0];
  $smarty->assign("num_records_parent", $num_records_parent);
	

	$smarty->assign("add_rent_link", $file_name."?sel=add_rent");
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/rentals_operation.tpl");
	exit;
}

function MyAd ($id_ad='', $par='') {

	global $smarty, $config, $dbconn, $auth, $lang, $multi_lang, $REFERENCES;
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
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
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&id_ad=$id_ad");
		exit();
	}
	$choise = (isset($_POST["choise"])) ? $_POST["choise"] : '';
	if (!(intval($id_ad)>0)){
		$id_ad = (isset($_POST["id_ad"])) ? $_POST["id_ad"] : ((isset($_GET["id_ad"])) ? $_GET["id_ad"] : 0);
		$id_ad = intval($id_ad);
	}
	$smarty->assign("submenu", "edit_rentals");
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
	$smarty->assign("use_ads_activity_period", GetSiteSettings("use_ads_activity_period"));
	$smarty->assign("ads_activity_period", GetSiteSettings("ads_activity_period"));
	

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_rentals.php";
	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

	$smarty->assign("active_user", 1);

	$strSQL = "	SELECT 	a.id, a.id_user, a.type, DATE_FORMAT(a.movedate, '".$config["date_format"]."' ) as movedate,
				a.people_count, a.comment, a.sold_leased_status, a.headline, a.date_unactive, 
				a.with_photo, a.with_video,
				urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress,
				count.name as country_name, reg.name as region_name, cit.name as city_name, ut.login, ut.user_type, 
				hlt.id_friend, blt.id_enemy, tsat.type as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
				a.status, sp.status as spstatus, ft.id as featured,
        count.id as count_id, cit.id as cit_id, reg.id as reg_id
				FROM ".RENT_ADS_TABLE." a
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON a.id=urlt.id_ad
				LEFT JOIN ".COUNTRY_TABLE." count ON count.id=urlt.id_country
				LEFT JOIN ".REGION_TABLE." reg ON reg.id=urlt.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=urlt.id_city
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=a.id_user
				LEFT JOIN ".HOTLIST_TABLE." hlt on a.id_user=hlt.id_friend and hlt.id_user='1'
				LEFT JOIN ".BLACKLIST_TABLE." blt on a.id_user=blt.id_enemy and blt.id_user='1'
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=a.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=a.id
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=a.id 
				WHERE a.id='".$id_ad."' ";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$profile["id"] = $row["id"];
	$profile["type"] = $row["type"];
	$profile["sold_leased_status"] = $row["sold_leased_status"];
	$profile["issponsor"] = $row["spstatus"];
	$profile["featured"] = $row["featured"];
	$profile["date_unactive"] =$row["date_unactive"];
	
	$strSQL = "SELECT rent.parent_id, rent_parent.headline FROM ".RENT_ADS_TABLE." rent 
             LEFT JOIN ".RENT_ADS_TABLE." rent_parent ON rent.parent_id = rent_parent.id
             WHERE rent.id_user='1' AND rent.id='".$row['id']."'";
	$rs = $dbconn->Execute($strSQL);
	$headline = stripslashes($rs->fields[1]);
  $parent_id = stripslashes($rs->fields[0]);
  $profile['parent_id'] = $parent_id;
  if ($parent_id) {
    $profile['parent'] = $headline;
  }
	if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
		$profile["headline"] = stripslashes($row["headline"]);
	} else {
		$profile["headline"] = stripslashes($row["headline"]);
	}
  
  $strKidsSQL = "SELECT 	a.id, a.headline, a.status
				      FROM ".RENT_ADS_TABLE." a WHERE a.parent_id = " . $row['id'];
  $rsKid = $dbconn->Execute($strKidsSQL);
	while(!$rsKid->EOF){
	 $rowKid = $rsKid->GetRowAssoc(false);
   $profile['kids'][] = $rowKid;
   $rsKid->MoveNext();
  }         
  $strParentsSQL = "SELECT 	a.id, a.headline
				      FROM ".RENT_ADS_TABLE." a, ".USERS_RENT_LOCATION_TABLE." urlt, ".COUNTRY_TABLE." count,
                   ".REGION_TABLE." reg, ".CITY_TABLE." cit, ".USERS_TABLE." ut
              WHERE a.id=urlt.id_ad AND urlt.id_country = count.id AND urlt.id_region = reg.id AND urlt.id_city = cit.id
              AND count.id=" . $row['count_id'] . " AND reg.id=" . $row['reg_id'] . " AND a.id_user = ut.id
              AND cit.id=" . $row['cit_id'] . " AND ut.id=" . $row['id_user'] . " AND a.parent_id = 0
              AND a.id != " . $row['id'];
	$rsPar = $dbconn->Execute($strParentsSQL);
  if ($rsPar) {
  	while(!$rsPar->EOF){
  	 $rowPar = $rsPar->GetRowAssoc(false);
     $profile['parents_available'][] = $rowPar;
     $rsPar->MoveNext();
    }
  }
	/*
	if ($row["subway_id"]>0){
		$strSQL = "SELECT name FROM ".SUBWAY_SPR_TABLE." WHERE id='".$row["subway_id"]."' ";
		$rs_subway = $dbconn->Execute($strSQL);
		if ($config["lang_ident"]!='ru') {
			$profile["subway_name"] = RusToTranslit($rs_subway->fields[0]);
		} else {
			$profile["subway_name"] = $rs_subway->fields[0];
		}
	} else {
		$profile["subway_name"] = '';
	}
	*/
	$profile["status"] = $row["status"];
	$profile["id_user"] = $row["id_user"];
	$profile["user_type"] = $row["user_type"];
	
	if ($profile["type"] == 2){			
		$calendar_event = new CalendarEvent();
		$profile["reserve"] = $calendar_event->GetEmptyPeriod($profile["id"], $profile["id_user"]);				
	}

	$profile["topsearched"] = $row["topsearched"];

	if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
		$profile["show_topsearch_icon"] = true;
		$profile["topsearch_date_begin"] = $row["topsearch_date_begin"];
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
	if ($config["lang_ident"]!='ru') {
		$profile["country_name"] = RusToTranslit($row["country_name"]);
		$profile["region_name"] = RusToTranslit($row["region_name"]);
		$profile["city_name"] = RusToTranslit($row["city_name"]);
	} else {
		$profile["country_name"] = $row["country_name"];
		$profile["region_name"] = $row["region_name"];
		$profile["city_name"] = $row["city_name"];
	}

	$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description", "theme_rest");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {

			if ($arr["spr_match_table"] != "") {
				/**
			 * описательные характеристики человека
			 */
				$lang_add = 2; //т.к. описываем подходящий вариант
				//описание того, кому хотят сдать
				$profile[$arr["key"]."_match"] = GetResArrName($arr["spr_match_table"], $profile["id_user"], $profile["id"], $arr["spr_table"], $arr["val_table"]);
				//описание себя
				$profile[$arr["key"]] = GetResArrName($arr["spr_user_table"], $profile["id_user"], 0, $arr["spr_table"], $arr["val_table"]);
			} else {
				$profile[$arr["key"]] = GetResArrName($arr["spr_user_table"], $profile["id_user"], $profile["id"], $arr["spr_table"], $arr["val_table"]);
			}
		}
	}

	/**
	 * account info
	 */
	$profile["account"] = GetAccountTableInfo(1);
	
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
			}	

			$smarty->assign("company_data", $company_data);			
			
	}
	
	/**
	 * photo
	 */
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve, status FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$profile["id"]."' AND upload_type='f' ORDER BY sequence";
	$rs_img = $dbconn->Execute($strSQL_img);
	$j = 0;
	$photo_folder = GetSiteSettings('photo_folder');
	$default_photo = GetSiteSettings('default_photo');
	if ($rs_img->fields[0]>0){
		while(!$rs_img->EOF){
			$row_img = $rs_img->GetRowAssoc(false);
			
			$profile["photo_id"][$j] = $row_img["photo_id"];
			$profile["photo_path"][$j] = $row_img["upload_path"];
			$profile["photo_user_comment"][$j] = $row_img["user_comment"];
			$profile["photo_admin_approve"][$j] = $row_img["admin_approve"];
			$profile["photo_status"][$j] = $row_img["status"];

			$path = $config["site_path"].$photo_folder."/".$profile["photo_path"][$j];
			$thumb_path = $config["site_path"].$photo_folder."/thumb_".$profile["photo_path"][$j];

			if(file_exists($path) && strlen($profile["photo_path"][$j])>0){
				$profile["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$profile["photo_path"][$j];
				$profile["photo_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$profile["photo_id"][$j]."&type_upload=f";
				$sizes = getimagesize($path);
				$profile["photo_width"][$j]  = $sizes[0];
				$profile["photo_height"][$j]  = $sizes[1];
			}
			if(file_exists($thumb_path) && strlen($profile["photo_path"][$j])>0)
			$profile["thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/thumb_".$profile["photo_path"][$j];
			if(!file_exists($path) || !strlen($profile["photo_path"][$j])){
				$profile["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
				$profile["thumb_file"][$j] = $profile["photo_file"][$j];
			}
			$rs_img->MoveNext();
			$j++;
		}
	} else {
		$profile["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
		$profile["thumb_file"][$j] = $profile["photo_file"][$j];
	}
	/**
	 * video
	 */
	$strSQL_video = "SELECT id as video_id, upload_path, user_comment, admin_approve, status FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$profile["id"]."' AND upload_type='v' ORDER BY sequence";
	
	$rs_video = $dbconn->Execute($strSQL_video);
	$j = 0;
	if ($rs_video->RowCount() > 0){		
		
		$settings = GetSiteSettings(array('default_video_icon', 'video_folder', 'use_ffmpeg'));
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
			$profile["video_status"][$j] = $row_video["status"];

			$path = $config["server"].$config["site_root"].$settings["video_folder"]."/";						
			
			if (file_exists($config["site_path"].$settings["video_folder"]."/".$profile["video_path"][$j])){				
				
				$profile["video_file"][$j] = $path.$profile["video_path"][$j];
				
				$profile["video_view_link"][$j] = "./admin_users.php?sel=upload_view&category=rental&id_file=".$profile["video_id"][$j]."&is_flv=".$profile["is_flv"][$j]."&type_upload=v";

			}
			$profile["video_icon"][$j] = $path.$profile["video_icon"][$j];
			$rs_video->MoveNext();
			$j++;
		}		
	}
	
	/**
	 * plan
	 */
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve, status FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$profile["id"]."' AND id_user='".$auth[0]."' ORDER BY sequence";
	$rs_img = $dbconn->Execute($strSQL_img);
	$j = 0;
	if ($rs_img->fields[0]>0){
		while(!$rs_img->EOF){
			$row_img = $rs_img->GetRowAssoc(false);
			$photo_folder = GetSiteSettings('photo_folder');
			$profile["plan_photo_id"][$j] = $row_img["photo_id"];
			$profile["plan_photo_path"][$j] = $row_img["upload_path"];
			$profile["plan_user_comment"][$j] = $row_img["user_comment"];
			$profile["plan_admin_approve"][$j] = $row_img["admin_approve"];
			$profile["plan_status"][$j] = $row_img["status"];

			$path = $config["site_path"].$photo_folder."/".$profile["plan_photo_path"][$j];
			$thumb_path = $config["site_path"].$photo_folder."/thumb_".$profile["plan_photo_path"][$j];
			if(file_exists($path) && strlen($profile["plan_photo_path"][$j])>0){
				$profile["plan_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$profile["plan_photo_path"][$j];
				$profile["plan_view_link"][$j] = "./".$file_name."?sel=plan_view&id_file=".$profile["plan_photo_id"][$j]."&type_upload=f";
				$sizes = getimagesize($path);
				$profile["plan_width"][$j]  = $sizes[0];
				$profile["plan_height"][$j]  = $sizes[1];
			}
			if(file_exists($thumb_path) && strlen($profile["plan_photo_path"][$j])>0)
			$profile["plan_thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/thumb_".$profile["plan_photo_path"][$j];
			if(!file_exists($path) || !strlen($profile["plan_photo_path"][$j])){
				$profile["plan_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
				$profile["plan_thumb_file"][$j] = $profile["plan_file"][$j];
			}
			$rs_img->MoveNext();
			$j++;
		}
	} else {
		$profile["plan_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
		$profile["plan_thumb_file"][$j] = $profile["plan_file"][$j];
	}
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));
	if ($profile["type"] == "3") {

		$strSQL_payment = " SELECT min_payment, offer_type, floor, floors, min_flats_square,
              max_flats_square, total_square, ceil_height, sea_distance, term, investor,
              parking, is_hot
							FROM ".USERS_RENT_PAYS_TABLE."
							WHERE id_ad='".$profile["id"]."' AND id_user='".$profile["id_user"]."' ";
		$rs_payment = $dbconn->Execute($strSQL_payment);
		$row_payment = $rs_payment->GetRowAssoc(false);
    $profile["min_payment"] = PaymentFormat($row_payment["min_payment"]);
		$profile["min_payment_show"] = FormatPrice($profile["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["offer_type"] = $row_payment["offer_type"];
		$profile["floor"] = $row_payment["floor"];
		$profile["floors"] = $row_payment["floors"];
    $profile["is_hot"] = $row_payment["is_hot"];
		$profile["min_flats_square"] = $row_payment["min_flats_square"];
		$profile["max_flats_square"] = $row_payment["max_flats_square"];
		$profile["total_square"] = $row_payment["total_square"];
    $profile["ceil_height"] = $row_payment["ceil_height"];
    $profile["sea_distance"] = $row_payment["sea_distance"];
    $profile["term"] = $row_payment["term"];
    $profile["investor"] = $row_payment["investor"];
    $profile["parking"] = $row_payment["parking"];

	} elseif ($profile["type"] == "1" || $profile["type"] == "2" || $profile["type"] == "4") {
		/**
		 * храним фиксированные значения для объявлений типа сдам в аренду, продам
		 * в min_<field_name>
		 */
    
    if ($profile['type'] == '1') {
      $strSQL_payment = "SELECT * FROM " . USERS_RENT_PAYS_TABLE_BY_MONTH . " WHERE id_ad = " . $profile['id'];
      $rs_payment = $dbconn->Execute($strSQL_payment);
  		$prices = $rs_payment->GetRowAssoc(false);
      foreach ($prices as $val) {
        if ($val) {
          $flag = true;
          break;          
        }
      }
      if ($flag) {
        $profile['price'] = $prices;
      }
    }
     
		$strSQL_payment = "SELECT min_payment, auction, min_deposit,
							min_live_square, min_total_square,
							min_land_square, min_floor, floor_num, subway_min, min_year_build,
              furniture, payment_not_season, days, hotel, route, facilities, meals, is_hot
							FROM ".USERS_RENT_PAYS_TABLE."
							WHERE id_ad='".$profile["id"]."' AND id_user='".$profile["id_user"]."' ";
		$rs_payment = $dbconn->Execute($strSQL_payment);
		$row_payment = $rs_payment->GetRowAssoc(false);
		$profile["payment_not_season"] = PaymentFormat($row_payment["payment_not_season"]);
		$profile["payment_not_season_show"] = FormatPrice($profile["payment_not_season"], $settings_price["cur_position"], $settings_price["cur_format"]);
    $profile["min_payment"] = PaymentFormat($row_payment["min_payment"]);
		
		$profile["min_payment_show"] = FormatPrice($profile["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		if($profile["min_payment"]<=0){$profile["act_status"]=0;}else{$profile["act_status"]=1;}
		$profile["auction"] = $row_payment["auction"];
		$profile["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
		$profile["min_deposit_show"] = FormatPrice($profile["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["min_live_square"] = $row_payment["min_live_square"];
		$profile["min_total_square"] = $row_payment["min_total_square"];
		$profile["min_land_square"] = $row_payment["min_land_square"];
    $profile["days"] = $row_payment["days"];
    $profile["hotel"] = $row_payment["hotel"];
    $profile["route"] = $row_payment["route"];
    $profile["facilities"] = $row_payment["facilities"];
    $profile["meals"] = $row_payment["meals"];
    $profile["is_hot"] = $row_payment["is_hot"];
    $profile["min_floor"] = $row_payment["min_floor"];
		$profile["floor_num"] = $row_payment["floor_num"];
		$profile["subway_min"] = $row_payment["subway_min"];
		$profile["min_year_build"] = $row_payment["min_year_build"];
    $profile["furniture"] = $row_payment["furniture"];
	}

	$strSQL_age = "SELECT his_age_1, his_age_2 FROM ".USERS_RENT_AGES_TABLE." WHERE id_user='".$profile["id_user"]."' AND id_ad='".$profile["id"]."' ";
	$rs_age = $dbconn->Execute($strSQL_age);
	$row_age = $rs_age->GetRowAssoc(false);

	$profile["his_age_1"] = $row_age["his_age_1"];
	$profile["his_age_2"] = $row_age["his_age_2"];

	$settings = GetSiteSettings(array("use_video_approve", "use_photo_approve"));
	$profile["use_video_approve"] = $settings["use_video_approve"];
	$profile["use_photo_approve"] = $settings["use_photo_approve"];
	
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
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_rental_ad_display.tpl");

	exit;
}

function EditProfile($par, $err="", $choise="", $id_ad=""){
	global $smarty, $config, $dbconn, $auth, $lang, $multi_lang, $REFERENCES, $VIDEO_EXT_ARRAY;

	@$choise = $_POST["choise"]?$_POST["choise"]:$choise;
	@$id_ad = $_POST["id_ad"]?$_POST["id_ad"]:$id_ad;
  if ($par == 'add_child') {
    $parent_id = $_REQUEST["id_ad"]?$_REQUEST["id_ad"]:0;
  }
        $ajax = isset($_REQUEST["ajax"]) ? intval($_REQUEST["ajax"]) : 0;
	$smarty->assign("submenu", "edit_rentals");

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_rentals.php";
	IndexAdminPage('admin_rentals');
	if (!$ajax){
        	CreateMenu('admin_lang_menu');        

		if ($err){
			GetErrors($err);
		}
	}
	switch($par){
		case "step_1":
    case "add_child":
			$data = @$_SESSION["step_1"];
			$data["from_edit"] = @$_SESSION["from_edit"];
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
			}
      
      if ($par == 'add_child') {
        $strSQL = "SELECT headline FROM ".RENT_ADS_TABLE." WHERE id = " . $parent_id;
        $rs = $dbconn->Execute($strSQL);
        $parent_name = $rs->fields[0];
        $data['parent'] = $parent_name;
        $data['parent_id'] = $parent_id;
      }
      
			if ($id_ad){
				$form["next_link"] = $file_name."?sel=1";
			} else {
				$form["next_link"] = $file_name."?sel=save_ad";
			}
			if (($data["from_edit"]=='1') || (@$data["region"])){

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
				}
				$smarty->assign("region", $region);

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
				}
				$smarty->assign("city", $city);
			}

			if (@$_GET["from"]=='sresults') {
				$choise = intval($_GET["var_2"]);
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
							break;						
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
			$smarty->assign("choise", $choise);
			$smarty->assign("country", $country);
			break;
		case "step_3":
			$form["next_link"] = $file_name."?sel=4";
			$form["back_link"] = $file_name."?sel=add_rent";
			$data = $_SESSION["step_3"];
			$data["from_edit"] = $_SESSION["from_edit"];

			$smarty->assign("add_to_lang", "&amp;sel=add_rent");

			//переменная для DefaultFieldName (работа со склонениями)
			$lang_add = ($choise == 3) ? "2" : "";

			$used_references = array("info", "period", "realty_type", "description", "theme_rest");
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

			if ($choise=="1" || $choise=="2" || $choise=="4") {
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

				$strSQL = "	SELECT id, upload_path, upload_type, status, admin_approve, user_comment
							FROM ".USERS_RENT_UPLOADS_TABLE."
							WHERE id_user='1' AND id_ad='".$id_ad."' AND upload_type='f' ORDER BY sequence";

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
							$upload[$i]["file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/".$upload[$i]["upload_path"];
							$upload[$i]["del_link"] = "./".$file_name."?sel=upload_del&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f&amp;back=step_4";
							$upload[$i]["view_link"] = "./".$file_name."?sel=upload_view&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f";
							if ($row["status"] == 1) {
								$upload[$i]["deactivate_link"] = "./".$file_name."?sel=upload_deactivate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_4";
							} else {
								$upload[$i]["activate_link"] = "./".$file_name."?sel=upload_activate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_4";
							}
							$upload[$i]["edit_comment_link"] = "./".$file_name."?sel=edit_comment&amp;id_file=".$upload[$i]["id"]."&amp;back=step_4";
						}
						if(file_exists($thumb_path) && strlen($upload[$i]["upload_path"])>0)
						$upload[$i]["thumb_file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/thumb_".$upload[$i]["upload_path"];
						if(!file_exists($path) || !strlen($upload[$i]["upload_path"])){
							$upload[$i]["file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/".$settings["default_photo"];
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
			if ($choise=="3"){
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

				$strSQL = "	SELECT id, upload_path, upload_type, status, admin_approve, user_comment
							FROM ".USERS_RENT_UPLOADS_TABLE."
							WHERE id_user='1' AND id_ad='".$id_ad."' AND upload_type='f' ORDER BY sequence";
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
							$upload[$i]["file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/".$upload[$i]["upload_path"];
							$upload[$i]["del_link"] = "./".$file_name."?sel=upload_del&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f&amp;back=step_5";
							$upload[$i]["view_link"] = "./".$file_name."?sel=upload_view&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=f";
							if ($row["status"] == 1) {
								$upload[$i]["deactivate_link"] = "./".$file_name."?sel=upload_deactivate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_5";
							} else {
								$upload[$i]["activate_link"] = "./".$file_name."?sel=upload_activate&amp;id_file=".$upload[$i]["id"]."&amp;back=step_5";
							}
							$upload[$i]["edit_comment_link"] = "./".$file_name."?sel=edit_comment&amp;id_file=".$upload[$i]["id"]."&amp;back=step_5";
						}
						if(file_exists($thumb_path) && strlen($upload[$i]["upload_path"])>0)
						$upload[$i]["thumb_file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/thumb_".$upload[$i]["upload_path"];
						if(!file_exists($path) || !strlen($upload[$i]["upload_path"])){
							$upload[$i]["file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/".$settings["default_photo"];
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
			} elseif ($choise=="1" || $choise=="2" || $choise=="4"){
				$data = $_SESSION["step_5"];
				$max_age = GetSiteSettings('max_age_limit');
				$min_age = GetSiteSettings('min_age_limit');

				for ($i=$min_age; $i<($max_age+1); $i++){
					$age[$i] = $i;
				}
				
				if (isset($data["his_age_1"])){
					$age_sel["age_1"] = $data["his_age_1"];
				}
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

			$strSQL = "SELECT comment FROM ".RENT_ADS_TABLE." WHERE id_user='1' AND type='".$choise."' AND id='".$id_ad."'";
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

			$strSQL = "	SELECT id, upload_path, status, admin_approve, user_comment
					FROM ".USER_RENT_PLAN_TABLE."
					WHERE id_user='1' AND id_ad='".$id_ad."' ORDER BY sequence";
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
						$plan[$i]["file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/".$plan[$i]["upload_path"];
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
					$plan[$i]["thumb_file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/thumb_".$plan[$i]["upload_path"];
					if(!file_exists($path) || !strlen($plan[$i]["upload_path"])){
						$plan[$i]["file"] = $config["server"].$config["site_root"].$settings["photo_folder"]."/".$settings["default_photo"];
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

			$strSQL = "SELECT headline FROM ".RENT_ADS_TABLE." WHERE id_user='1' AND type='".$choise."' AND id='".$id_ad."'";
			$rs = $dbconn->Execute($strSQL);

			$headline = stripslashes($rs->fields[0]);

			$smarty->assign("headline", $headline);
			$smarty->assign("choise", $choise);
			$smarty->assign("id_ad", $id_ad);
			break;	
   case "step_type":
			$form["next_link"] = $file_name."?sel=finish_2";
			$smarty->assign("add_to_lang", "&amp;sel=add_rent");
			$form["back_link"] = $file_name."?sel=finish";

			$strSQL = "SELECT rent.parent_id, rent_parent.headline FROM ".RENT_ADS_TABLE." rent 
                 LEFT JOIN ".RENT_ADS_TABLE." rent_parent ON rent.parent_id = rent_parent.id
                 WHERE rent.id_user='1' AND rent.id='".$id_ad."'";
			$rs = $dbconn->Execute($strSQL);

			$headline = stripslashes($rs->fields[1]);
      $parent_id = stripslashes($rs->fields[0]);
      
      if ($choise == 2) {
        $strParentsSQL = "SELECT 	a.id, a.headline
  				      FROM ".RENT_ADS_TABLE." a 
                WHERE a.parent_id = 0 AND a.id != " . $row['id'] . " AND type='2'";
      	$rsPar = $dbconn->Execute($strParentsSQL);
        $parents_available = array();
      	while(!$rsPar->EOF){
      	 $rowPar = $rsPar->GetRowAssoc(false);
         $parents_available[] = $rowPar;
         $rsPar->MoveNext();
        }
      } else {
        $strSQL = "	SELECT 	a.id, a.id_user, a.type, DATE_FORMAT(a.movedate, '".$config["date_format"]."' ) as movedate,
				a.people_count, a.comment, a.sold_leased_status, a.headline, a.date_unactive, 
				a.with_photo, a.with_video,
				urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress,
				count.name as country_name, reg.name as region_name, cit.name as city_name, ut.login, ut.user_type, 
				hlt.id_friend, blt.id_enemy, tsat.type as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
				a.status, sp.status as spstatus, ft.id as featured,
        count.id as count_id, cit.id as cit_id, reg.id as reg_id
				FROM ".RENT_ADS_TABLE." a
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON a.id=urlt.id_ad
				LEFT JOIN ".COUNTRY_TABLE." count ON count.id=urlt.id_country
				LEFT JOIN ".REGION_TABLE." reg ON reg.id=urlt.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=urlt.id_city
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=a.id_user
				LEFT JOIN ".HOTLIST_TABLE." hlt on a.id_user=hlt.id_friend and hlt.id_user='1'
				LEFT JOIN ".BLACKLIST_TABLE." blt on a.id_user=blt.id_enemy and blt.id_user='1'
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=a.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=a.id
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=a.id 
				WHERE a.id='".$id_ad."' ";
      	$rs = $dbconn->Execute($strSQL);
      	$row = $rs->GetRowAssoc(false);
        
        $strParentsSQL = "SELECT 	a.id, a.headline
  				      FROM ".RENT_ADS_TABLE." a, ".USERS_RENT_LOCATION_TABLE." urlt, ".COUNTRY_TABLE." count,
                     ".REGION_TABLE." reg, ".CITY_TABLE." cit, ".USERS_TABLE." ut
                WHERE a.id=urlt.id_ad AND urlt.id_country = count.id AND urlt.id_region = reg.id AND urlt.id_city = cit.id
                AND count.id=" . $row['count_id'] . " AND reg.id=" . $row['reg_id'] . " AND a.id_user = ut.id
                AND cit.id=" . $row['cit_id'] . " AND ut.id=" . $row['id_user'] . " AND a.parent_id = 0
                AND a.id != " . $row['id'] . " AND a.type = '" . $choise . "'";
      	$rsPar = $dbconn->Execute($strParentsSQL);
        $parents_available = array();
      	while(!$rsPar->EOF){
      	 $rowPar = $rsPar->GetRowAssoc(false);
         $parents_available[] = $rowPar;
         $rsPar->MoveNext();
        }
      }
      
      
      
			$smarty->assign("headline", $headline);
      $smarty->assign("parent_id", $parent_id);
      $smarty->assign("parents_available", $parents_available);
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
			$data["default_video_icon"] = $config["server"].$config["site_root"]."/".$settings["video_folder"]."/".$settings["default_video_icon"];
			$data["video_extensions"] = $VIDEO_EXT_ARRAY;

			$strSQL = "	SELECT id, upload_path, upload_type, status, admin_approve, user_comment
						FROM ".USERS_RENT_UPLOADS_TABLE."
						WHERE id_user='1' AND id_ad='".$id_ad."' AND upload_type='v' ORDER BY sequence";

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
						$upload[$i]["del_link"] = "./".$file_name."?sel=upload_del&amp;id_file=".$upload[$i]["id"]."&amp;type_upload=v&amp;back=upload_video";
						$upload[$i]["view_link"] = "./admin_users.php?sel=upload_view&amp;id_file=".$upload[$i]["id"]."&amp;is_flv=".$upload[$i]["is_flv"]."&amp;type_upload=v";
						
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
			}else {
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
									
			$strSQL = "SELECT type, status, date_unactive, sold_leased_status FROM ".RENT_ADS_TABLE." ".
				      "WHERE id='$id_ad'";
			$rs = $dbconn->Execute($strSQL);
			
			$profile = $rs->GetRowAssoc(false);
			$profile["id"] = $id_ad;
			$profile["act_status"] = $_SESSION["step_3"]["act_status"];
			$profile["id_region"] = $_SESSION["step_1"]["region"];
						
			//get leaders in choosen ad's region
			$smarty->assign("featured_rent", GetFeaturedAd($profile["id_region"]));
					
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
			$smarty->assign("choise", $choise);
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
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_rental_edit.tpl");
	exit;
}

function CalendarAd($par="", $err="", $date_start="", $date_end="", $id_event="") {
	global $smarty, $config, $dbconn, $user, $lang, $multi_lang, $REFERENCES;
	
	$id_ad = $_REQUEST["id_ad"]?intval($_REQUEST["id_ad"]):$id_ad;	
	
	$smarty->assign("submenu", "edit_rentals");

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_rentals.php";
	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

	if ($err){
		GetErrors($err);
	}
	
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
	$date["display"] = $calendar_event->GetMonthYearArray($start_month, $start_year, $id_ad, 1, 4, 1);	

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
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_calendar_edit.tpl");
}

function SaveProfile($par){
	global $smarty, $config, $dbconn, $auth, $multi_lang, $REFERENCES;

	if (!($par=="save_ad")){
		$id_ad = (isset($_POST["id_ad"])) ? intval($_POST["id_ad"]) : 0;
		$choise = (isset($_POST["choise"])) ? intval($_POST["choise"]) : 0;
	} else {
	 if (isset($_POST['parent_id'])) {
  	 $parent_id = (isset($_POST["parent_id"])) ? intval($_POST["parent_id"]) : 0;
     $strSQL = " SELECT `type` FROM ".RENT_ADS_TABLE." WHERE id='".$parent_id."' ";
     $rs = $dbconn->Execute($strSQL);
  	 $type = $rs->fields[0];
     $_POST['choise'] = $type;
   }
	}
  
	switch ($par){
		case "save_ad"://first save
			$type = intval($_POST["choise"]);
			if ( $_POST["country"] < 1 || $_POST["region"] < 1 || ($_POST["city"] < 1 && ($type == 2 || $type == 4) )) {
				$err = "fill_empty_fields";
				$_SESSION["step_1"] = $_POST;
				EditProfile("step_1", $err, $_POST["choise"],'');
				exit;
			}			
			
			$strSQL = " INSERT INTO ".RENT_ADS_TABLE." (id_user, parent_id, type, datenow, status, movedate) VALUES ('1','" . $parent_id . "','".$type."', now(), '0', DATE_FORMAT(now(), '%Y-%m-%d'))";
      $dbconn->Execute($strSQL);
			$err = "";

			$strSQL = " SELECT MAX(id) FROM ".RENT_ADS_TABLE." WHERE id_user='1' AND type='".intval($_POST["choise"])."' ";
			$rs = $dbconn->Execute($strSQL);
			$id_ad = $rs->fields[0];

			$strSQL = "INSERT INTO ".USERS_RENT_PAYS_TABLE." (id_ad, id_user) VALUES ('".$id_ad."','1')";
			$dbconn->Execute($strSQL);
			$strSQL = "INSERT INTO ".USERS_RENT_AGES_TABLE." (id_ad, id_user) VALUES ('".$id_ad."','1')";
			$dbconn->Execute($strSQL);

			$strSQL = " INSERT INTO ".USERS_RENT_LOCATION_TABLE." (id_user, id_ad, id_country, id_region, id_city, zip_code, street_1, street_2, adress)	
						VALUES ('1', '".$id_ad."','".intval($_POST["country"])."','".intval($_POST["region"])."','".intval($_POST["city"])."', '".strip_tags($_POST["zip_code"])."', '".substr(strip_tags(addslashes($_POST["cross_streets_1"])), 0 , 50)."', '".substr(strip_tags(addslashes($_POST["cross_streets_2"])), 0, 50)."', '".substr(strip_tags(addslashes($_POST["adress"])), 0, 100)."') ";
						
			$rs = $dbconn->Execute($strSQL);
			$_SESSION["step_1"] = $_POST;
			/**
			 * Save statistics on sell/lease listings publication
			 */
			if (($type == 2 || $type == 4) && GetSiteSettings("use_sell_lease_payment") && GetSiteSettings("site_mode") == 1) {
				$strSQL = "UPDATE ".USER_SELL_LEASE_PAYMENT_TABLE." SET used_ads_number=used_ads_number+1 WHERE id_user='{$auth[0]}'";
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
				
				$strSQL = "UPDATE ".USERS_RENT_LOCATION_TABLE." set id_country='".intval($_POST["country"])."', id_region='".intval($_POST["region"])."', id_city='".intval($_POST["city"])."', zip_code='".strip_tags($_POST["zip_code"])."', street_1='".substr(addslashes(strip_tags($_POST["cross_streets_1"])), 0, 50)."', street_2='".substr(addslashes(strip_tags($_POST["cross_streets_2"])), 0, 50)."', adress='".substr(addslashes(strip_tags($_POST["adress"])), 0, 100)."' WHERE id_user='1' AND id_ad='".$id_ad."' ";
				$rs = $dbconn->Execute($strSQL);

				$strSQL = "UPDATE ".RENT_ADS_TABLE." SET type='$type' WHERE id_user='1' AND id='".$id_ad."' ";
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
			if ($_POST["choise"]=="3") {
				$strSQL = "	UPDATE ".USERS_RENT_PAYS_TABLE." SET
							min_payment='".intval($_REQUEST["min_payment"])."',
              is_hot='".(isset($_REQUEST["hot"]) ? '1' : '0')."',
							min_flats_square='".floatval($_REQUEST["min_flats_square"])."',
							max_flats_square='".floatval($_REQUEST["max_flats_square"])."',
							total_square='".floatval($_REQUEST["total_square"])."',
							ceil_height='".floatval($_REQUEST["ceil_height"])."',
							sea_distance='".mysql_real_escape_string($_REQUEST["sea_distance"])."',
							term='".mysql_real_escape_string($_REQUEST["term"])."',
              route='".mysql_real_escape_string($_REQUEST["route"])."',
              meals='".mysql_real_escape_string($_REQUEST["meals"])."',
              facilities='".mysql_real_escape_string($_REQUEST["facilities"])."',
							floor='".intval($_REQUEST["floor"])."',
							floors='".intval($_REQUEST["floors"])."',
							investor='".mysql_real_escape_string($_REQUEST["investor"])."',
							parking='".mysql_real_escape_string($_REQUEST["parking"])."',
              offer_type='".mysql_real_escape_string($_REQUEST["offer_type"])."'
							WHERE id_ad='".$id_ad."' AND id_user='1' ";
				$dbconn->Execute($strSQL);
			} elseif ($_POST["choise"]=="1" || $_POST["choise"]=="2" || $_POST["choise"]=="4") {
				//i have/sell realty
				$min_payment = intval($_REQUEST["min_payment"]);
				
        if ($_POST['choise'] == 1) {
          $sql = 'DELETE FROM ' . USERS_RENT_PAYS_TABLE_BY_MONTH . ' WHERE id_ad = ' . intval($id_ad);
          $dbconn->Execute($strSQL);
          $sql = 'INSERT INTO ' . USERS_RENT_PAYS_TABLE_BY_MONTH . ' 
                  VALUES (' . intval($id_ad) . ', ' . intval($_POST['payment']['january']) . ', ' . intval($_POST['payment']['february']) . ',
                          ' . intval($_POST['payment']['march']) . ', ' . intval($_POST['payment']['april']) . ', ' . intval($_POST['payment']['may']) . ',
                          ' . intval($_POST['payment']['june']) . ', ' . intval($_POST['payment']['july']) . ', ' . intval($_POST['payment']['august']) . ',
                          ' . intval($_POST['payment']['september']) . ', ' . intval($_POST['payment']['october']) . ', ' . intval($_POST['payment']['november']) . ',
                          ' . intval($_POST['payment']['december']) . ')';
          $dbconn->Execute($sql);
        }
        
				$strSQL = "	UPDATE ".USERS_RENT_PAYS_TABLE." SET
							payment_not_season='".intval($_REQUEST["payment_not_season"])."',
              min_payment='".intval($_REQUEST["min_payment"])."',
							is_hot='".(isset($_REQUEST["hot"]) ? '1' : '0')."',
              auction='".intval($_REQUEST["auction"])."',
							min_deposit='".intval($_REQUEST["min_deposit"])."',
							min_live_square='".intval($_REQUEST["min_live_square"])."',
							min_total_square='".intval($_REQUEST["min_total_square"])."',
							min_land_square='".intval($_REQUEST["min_land_square"])."',
              days='".mysql_real_escape_string($_REQUEST["days"])."',
              hotel='".mysql_real_escape_string($_REQUEST["hotel"])."',
              route='".mysql_real_escape_string($_REQUEST["route"])."',
              facilities='".mysql_real_escape_string($_REQUEST["facilities"])."',
              meals='".mysql_real_escape_string($_REQUEST["meals"])."',
              min_floor='".intval($_REQUEST["min_floor"])."',
							floor_num='".intval($_REQUEST["floor_num"])."',
							subway_min='".intval($_REQUEST["subway_min"])."',
							min_year_build='".intval($_REQUEST["min_year_build"])."',
              furniture='".mysql_real_escape_string($_REQUEST["furniture"])."'
							WHERE id_ad='".$id_ad."' AND id_user='1' ";
				$dbconn->Execute($strSQL);
			}
			
      $used_references = array("info", "period", "realty_type", "description", "theme_rest");
			
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$tmp_info = (isset($_REQUEST[$arr["key"]]) && !empty($_REQUEST[$arr["key"]])) ? $_REQUEST[$arr["key"]] : array();
					$tmp_spr = (isset($_REQUEST["spr_".$arr["key"]])) ? $_REQUEST["spr_".$arr["key"]] : "";	
          
					if(isset($tmp_info) && is_array($tmp_spr)){
						SprTableEditAdmin($arr["spr_user_table"], $id_ad, $tmp_spr, $tmp_info);
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
				 * @todo проверить, если используется
				 * все массивы с пользовательскими значениями справосников - isset && !empty, иначе array()
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

				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET my_age_1='".$age_1."', my_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$auth[0]."' ";
				$rs = $dbconn->Execute($strSQL);

				$spr_people = $_POST["spr_people"];
				if(is_array($values_people) && is_array($spr_people) && intval($id_ad)){
					SprTableEditAdmin(SPR_RENT_PEOPLE_USER_TABLE, $id_ad, $spr_people, $values_people);
				}
				$spr_gender = $_POST["spr_gender"];
				if(is_array($values_gender) && is_array($spr_gender) && intval($id_ad)){
					SprTableEditAdmin(SPR_RENT_GENDER_USER_TABLE, $id_ad, $spr_gender, $values_gender);
				}
			}
			$_SESSION["step_4"] = $_POST;
		break;
		case "6":
			unset($_SESSION["step_5"]);

			if ($_POST["choise"]=="2" || $_POST["choise"]=="4"){
				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET his_age_1='".$age_1."', his_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='1' ";
				$rs = $dbconn->Execute($strSQL);

				$used_references = array("people", "gender", "language");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$tmp_info = (isset($_REQUEST[$arr["key"]."_match"]) && !empty($_REQUEST[$arr["key"]."_match"])) ? $_REQUEST[$arr["key"]."_match"] : array();
						$tmp_spr = $_REQUEST["spr_".$arr["key"]."_match"];
						if(is_array($tmp_info) && is_array($tmp_spr)){
							SprTableEditAdmin($arr["spr_match_table"], $id_ad, $tmp_spr, $tmp_info);
						}
					}
				}
				$_SESSION["step_5"] = $_POST;
			}			
		break;
		case "7":
			if ($_POST["choise"]=="2" || $_POST["choise"]=="4"){
				/**
				 * @todo проверить, если используется
				 * все массивы с пользовательскими значениями справосников - isset && !empty, иначе array()
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
				$strSQL = " UPDATE ".RENT_ADS_TABLE." SET people_count='".$total_people."' WHERE id='".$id_ad."' AND id_user='".$auth[0]."' ";
				$dbconn->Execute($strSQL);

				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET my_age_1='".$age_1."', my_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$auth[0]."' ";
				$rs = $dbconn->Execute($strSQL);
				$spr_people = $_POST["spr_people"];
				if(is_array($values_people) && is_array($spr_people) && intval($id_ad)){
					SprTableEditAdmin(SPR_RENT_PEOPLE_USER_TABLE, $id_ad, $spr_people, $values_people);
				}
				$spr_gender = $_POST["spr_gender"];
				if(is_array($values_gender) && is_array($spr_gender) && intval($id_ad)){
					SprTableEditAdmin(SPR_RENT_GENDER_USER_TABLE, $id_ad, $spr_gender, $values_gender);
				}

			} elseif ($_POST["choise"] == '1') {
				$age_1 = intval($_POST["age_1"]);
				$age_2 = intval($_POST["age_2"]);
				$strSQL = "UPDATE ".USERS_RENT_AGES_TABLE." SET his_age_1='".$age_1."', his_age_2='".$age_2."' WHERE id_ad='".$id_ad."' AND id_user='".$auth[0]."' ";
				$rs = $dbconn->Execute($strSQL);

				$values_people = $_POST["people_match"];
				$spr_people = $_POST["spr_people_match"];
				if(is_array($values_people) && is_array($spr_people) && intval($id_ad)){
					SprTableEditAdmin(SPR_RENT_PEOPLE_MATCH_TABLE, $id_ad, $spr_people, $values_people);
				}
				$values_gender = $_POST["gender_match"];
				$spr_gender = $_POST["spr_gender_match"];
				if(is_array($values_gender) && is_array($spr_gender) && intval($id_ad)){
					SprTableEditAdmin(SPR_RENT_GENDER_MATCH_TABLE, $id_ad, $spr_gender, $values_gender);
				}
			}
		break;
		case "8":
			$comment = strip_tags($_POST["comments"]);
			$strSQL = "UPDATE ".RENT_ADS_TABLE." set comment='".addslashes($comment)."' WHERE id='".$id_ad."' ";
			$dbconn->Execute($strSQL);
			$err = "completed";
		break;
		case "finish":
			$headline = strip_tags($_POST["headline"]);
			$strSQL = "UPDATE ".RENT_ADS_TABLE." set headline='".addslashes($headline)."' WHERE id='".$id_ad."' ";
			$dbconn->Execute($strSQL);
			$err = "completed";
		break;
    case "finish_2":
			if ($_POST['offer_type'] == 'parent') {
			 $parent_id = 0;
			} else {
			 $parent_id = $_POST['parent_id'];
			}
			$strSQL = "UPDATE ".RENT_ADS_TABLE." set parent_id='".intval($parent_id)."' WHERE id='".$id_ad."' ";
			$dbconn->Execute($strSQL);
			$err = "completed";
		break;
	}
	if ($par != "save_ad") {
		AdUpdateDate($id_ad);
	}

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=my_ad&id_ad=$id_ad");
	exit();
}

function UploadPhoto($back){
	global $smarty, $config, $dbconn, $auth;
	$id_ad = intval($_POST["id_ad"]);
	$photo = $_FILES["photo"];
	$upload_comment = strip_tags($_POST["upload_comment"]);

	$settings = GetSiteSettings(array( 'photo_folder', 'photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height'));

	$rs = $dbconn->Execute("select count(*) from ".USERS_RENT_UPLOADS_TABLE." where id_user='1' AND id_ad='".intval($_POST["id_ad"])."'  and upload_type='f'");
	$photo_count = $rs->fields[0];
	if(intval($photo_count) >= intval($settings["photo_max_user_count"])){
		$err = "cant_upload";
	}else{
		$images_obj = new Images($dbconn);
		$upload_type = "f";
		$err = $images_obj->UploadImages($photo, 1, $upload_type, '', 1, $upload_comment, $id_ad,'rent');
		AdUpdateDate($id_ad);
	}
	EditProfile($back, $err);
	exit;
}

function JsUpload(){
	global $smarty, $config, $dbconn, $user, $lang;
	
	require_once "../include/JsHttpRequest.php";
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
			$rs = $dbconn->Execute("SELECT COUNT(*) FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='1' AND id_ad='".$id_ad."' AND upload_type='$upload_type'");
			$limit = $settings["photo_max_user_count"];
			break;
		case "plan":
			$rs = $dbconn->Execute("SELECT COUNT(id) FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='1' AND id_ad='".$id_ad."'");
			 
			$limit = $settings["plan_photo_max_user_count"];
			break;	
		case "v":
			$rs = $dbconn->Execute("select count(*) from ".USERS_RENT_UPLOADS_TABLE." where id_user='1' AND id_ad='".$id_ad."' and upload_type='v'");	
			$limit = $settings["video_max_count"];
		break;	
	}

	
	$upload_count = $rs->fields[0];
	
	if(intval($upload_count) >= intval($limit)){
		$err = "cant_upload";
		
	} else {
		switch ($upload_type){
			case "f":
			case "plan":
				$images_obj = new Images($dbconn);		
				$err = $images_obj->UploadImages($upload, 1, $upload_type, '', 1, $upload_comment, $id_ad,'rent');	
				AdUpdateDate($id_ad);	
				break;
			case "v":
				$err = SaveUploadForm($upload, 0, $upload_comment, 'v', $id_ad, 1);
				break;	
		}		
	}
	
	if ($err == "file_upload_without_approve" || $err == "file_upload"){		
		echo "||success||".$lang["errors"]["file_upload_without_approve"];
	}else{
		echo $lang["errors"][$err];
	}
}
function UploadPlan($back){
	global $smarty, $config, $dbconn, $auth;

	$id_ad = intval($_POST["id_ad"]);
	$plan = $_FILES["plan"];
	$upload_comment = strip_tags($_REQUEST["upload_comment"]);

	$settings = GetSiteSettings(array( 'photo_folder', 'photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height'));

	$rs = $dbconn->Execute("SELECT COUNT(id) FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='1' AND id_ad='".intval($_POST["id_ad"])."'  ");
	if ($rs->fields[0]>4){
		EditProfile($back, "cant_upload");
		exit;
	}

	$images_obj = new Images($dbconn);
	$upload_type = "plan";
	$err = $images_obj->UploadImages($plan, 1, $upload_type, '', 1, $upload_comment, $id_ad, 'rent');
	AdUpdateDate($id_ad);
	EditProfile($back, $err);
	exit;
}

function PlanView() {
	global $smarty, $config, $dbconn, $auth, $lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

	$id_file = intval($_GET["id_file"]);
	$rs = $dbconn->Execute("SELECT upload_path FROM ".USER_RENT_PLAN_TABLE." WHERE id_user='".$auth[0]."' AND id='".$id_file."'  ");
	$upload_file["file_name"] = $rs->fields[0];
	$upload_file["upload_type"] = "f";
	$folder = GetSiteSettings("photo_folder");
	$upload_file["file_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_name"];

	$smarty->assign("upload_file", $upload_file);

	$smarty->display(TrimSlash($config["admin_theme_path"])."/upload_view.tpl");
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
	global $smarty, $config, $dbconn, $auth, $lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"rentals.php";
	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

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
	$upload_file["file_path"] = $config["server"].$config["site_root"].$folder."/".$upload_file["file_name"];

	$smarty->assign("upload_file", $upload_file);

	$smarty->display(TrimSlash($config["admin_theme_path"])."/upload_view.tpl");
	exit;
}


function UploadDelete(){
	global $config, $dbconn;
	
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
							WHERE id='".$id_file."' AND id_user='1'");
	if ($rs->fields[0]>0){
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
		unlink($config["site_path"].$folder."/thumb_".$rs->fields[1]);
		unlink($config["site_path"].$folder."/thumb_big_".$rs->fields[1]);
		
		if ($upload_type == "v") {			
			$flv_name = explode('.', $rs->fields[1]);
			unlink($config["site_path"].$folder."/".$flv_name[0]."1.jpg");
			unlink($config["site_path"].$folder."/".$flv_name[0].".flv");					
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
	global $smarty, $config, $dbconn, $auth, $lang, $REFERENCES;
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"admin_rentals.php";

	unset($_SESSION["step_1"]);
	unset($_SESSION["step_3"]);
	unset($_SESSION["step_4"]);
	unset($_SESSION["step_5"]);
	unset($_SESSION["from_edit"]);

	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

	$param = $file_name."?sel=list_ads&amp;";
	$ads = GetUserAdsAdmin($file_name, $param);

	if (count($ads) > 0 ){
		$smarty->assign("ads",$ads);
	} else {
		EditProfile("step_1");
		exit;
	}

	if ($err){
		GetErrors($err);
	}
	$smarty->assign("add_rent_link", $file_name."?sel=add_rent");
	$smarty->assign("add_to_lang","&amp;sel=list_ads");
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_rentals_list.tpl");
	exit;
}

function DelUserAd(){
	global $smarty, $config, $dbconn, $auth, $REFERENCES;

	$id_ad = intval($_GET["id_ad"]);
	$strSQL = " SELECT id FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' ";
	$rs = $dbconn->Execute($strSQL);
	if (intval($rs->fields[0]) === $id_ad){
		//slideshow
		$photo_folder = GetSiteSettings("photo_folder");
		$strSQL = "SELECT upload_path FROM ".RENT_ADS_TABLE." WHERE id='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->RowCount()>0){
			while(!$rs->EOF){
				unlink($config["site_path"].$photo_folder."/".$rs->fields[0]);
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
	}
	ListUserAds();
	exit;
}

function UserAd($par=''){
	global $smarty, $config, $dbconn, $auth, $REFERENCES;

	$id_ad = intval($_GET["id_ad"]);

	$strSQL = "	SELECT type, UNIX_TIMESTAMP(movedate) as movedate, people_count,
				with_photo, with_video
				FROM ".RENT_ADS_TABLE."
				WHERE id='".$id_ad."' AND id_user='1' ";
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

	$used_references = array("info", "period", "realty_type", "description", "theme_rest");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data_1[$arr["key"]] = SprTableSelectAdmin($arr["spr_user_table"], $id_ad, 1, $arr["spr_table"]);
		}
	}

	if ($ad["type"] == "3") {
		$strSQL = "SELECT id_country, id_region, id_city, zip_code, street_1, street_2, adress FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_user='1' AND id_ad='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_location["country"] = stripslashes($row["id_country"]);
		$data_location["region"] = stripslashes($row["id_region"]);
		$data_location["city"] = stripslashes($row["id_city"]);

		$data_location["zip_code"] = stripslashes($row["zip_code"]);
		$data_location["cross_streets_1"] = stripslashes($row["street_1"]);
		$data_location["cross_streets_2"] = stripslashes($row["street_2"]);
		$data_location["adress"] = stripslashes($row["adress"]);

		$_SESSION["step_1"] = $data_location;   
    
		$strSQL = " SELECT min_payment, offer_type, floor, floors, min_flats_square, max_flats_square,
          total_square, ceil_height, sea_distance, term, investor, parking, is_hot
					FROM ".USERS_RENT_PAYS_TABLE."
					WHERE id_ad='".$id_ad."' AND id_user='1'";

		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_1["min_payment"] = $row["min_payment"];
		$data_1["offer_type"] = $row["offer_type"];
		$data_1["is_hot"] = $row["is_hot"];
    $data_1["floor"] = $row["floor"];
		$data_1["floors"] = $row["floors"];
		$data_1["min_flats_sqare"] = $row["min_flats_sqare"];
		$data_1["max_flats_square"] = $row["max_flats_square"];
		$data_1["total_square"] = $row["total_square"];
		$data_1["ceil_height"] = $row["ceil_height"];
		$data_1["sea_distance"] = $row["sea_distance"];
		$data_1["term"] = $row["term"];
		$data_1["investor"] = $row["investor"];
		$data_1["parking"] = $row["parking"];

		$_SESSION["from_edit"] = 1;

		$_SESSION["step_3"] = $data_1;

		$_SESSION["step_4"] = $data_2;
	} elseif ($ad["type"] == "1" || $ad["type"] == "2" || $ad["type"] == "4") {
	 
    if ($ad['type'] == 1) {
          
      $strSQL_payment = "SELECT * FROM " . USERS_RENT_PAYS_TABLE_BY_MONTH . " WHERE id_ad = " . $id_ad;
      $rs_payment = $dbconn->Execute($strSQL_payment);
  		$prices = $rs_payment->GetRowAssoc(false);
      foreach ($prices as $val) {
        if ($val) {
          $flag = true;
          break;          
        }
      }
      if ($flag) {
        $data_1['price'] = $prices;
      }
    
    }
    
		$strSQL = "SELECT id_country, id_region, id_city, zip_code, street_1, street_2, adress FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_user='1' AND id_ad='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_location["country"] = stripslashes($row["id_country"]);
		$data_location["region"] = stripslashes($row["id_region"]);
		$data_location["city"] = stripslashes($row["id_city"]);

		$data_location["zip_code"] = stripslashes($row["zip_code"]);
		$data_location["cross_streets_1"] = stripslashes($row["street_1"]);
		$data_location["cross_streets_2"] = stripslashes($row["street_2"]);
		$data_location["adress"] = stripslashes($row["adress"]);

		$_SESSION["step_1"] = $data_location;
		$data_1["move_year"] = date("Y", $ad["movedate"]);
		$data_1["move_month"] = date("m", $ad["movedate"]);
		$data_1["move_day"] = date("d", $ad["movedate"]);

		$strSQL = "	SELECT min_payment, auction, min_deposit,
					min_live_square, min_total_square, is_hot,
					min_land_square, min_floor, floor_num, subway_min, min_year_build, furniture, payment_not_season,
          meals, route, facilities, days
					FROM ".USERS_RENT_PAYS_TABLE."
					WHERE id_ad='".$id_ad."' AND id_user='1'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data_1["min_payment"] = $row["min_payment"];
    $data_1["is_hot"] = $row["is_hot"];
    $data_1["payment_not_season"] = $row["payment_not_season"];
		$data_1["act_status"] = ($row["min_payment"] <= 0) ? 0 : 1;		
		$data_1["auction"] = $row["auction"];
		$data_1["min_deposit"] = $row["min_deposit"];
		$data_1["min_live_square"] = $row["min_live_square"];
		$data_1["min_total_square"] = $row["min_total_square"];
		$data_1["min_land_square"] = $row["min_land_square"];
		$data_1["min_floor"] = $row["min_floor"];
		$data_1["floor_num"] = $row["floor_num"];
		$data_1["subway_min"] = $row["subway_min"];
    $data_1["route"] = $row["route"];
    $data_1["meals"] = $row["meals"];
    $data_1["days"] = $row["days"];
    $data_1["facilities"] = $row["facilities"];
		$data_1["min_year_build"] = $row["min_year_build"];
    $data_1["furniture"] = $row["furniture"];

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
	global $smarty, $config, $dbconn, $auth;
	$id_ad = intval($_POST["id_ad"]);
	$video = $_FILES["video"];
	$upload_comment = strip_tags($_POST["upload_comment"]);

	$settings = GetSiteSettings("video_max_count");

	$rs = $dbconn->Execute("select count(*) from ".USERS_RENT_UPLOADS_TABLE." where id_user='1' AND id_ad='".intval($_POST["id_ad"])."' and upload_type='v'");
	$video_count = $rs->fields[0];
	if(intval($video_count) >= intval($settings["video_max_count"])){
		$err = "cant_upload";
	}else{
		$err = SaveUploadForm($video, $id_file, $upload_comment, 'v', $id_ad, 1);		
	}
	EditProfile($back, $err);
	exit;
}

function SaveUploadForm($upload, $id_file, $user_comment, $upload_type='v', $id_ad, $admin_mode=0, $id_user_admin_mode=""){
	global $smarty, $dbconn, $config, $auth, $VIDEO_TYPE_ARRAY, $VIDEO_EXT_ARRAY;

	$err = "";
	$id = intval($auth[0]);

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
	if ($admin_mode == 1 )$use_approve = 0;
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
					
$strSQL = "insert into ".USERS_RENT_UPLOADS_TABLE." (id_user, id_ad, upload_path, upload_type, file_type, admin_approve, user_comment, sequence) values ('".$id."', '".$id_ad."', '".$new_file_name."', '".$upload_type."', '".$upload["type"]."', '".$admin_approve."', '".$user_comment."', '$sequence')";
			$dbconn->Execute($strSQL);
			AdUpdateDate($id_ad);
		}else{
			return  "upload_err";
		}
	}
	return ((intval($admin_approve)) ? "file_upload" : "file_upload_without_approve");
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

/**
 * поднять объвление в поиске  id_service = 1
 *
 */
function TopSearchAd() {
	global $config, $smarty, $dbconn, $lang, $cur, $REFERENCES;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_rentals.php";

	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');

	$smarty->assign("file_name", $file_name);

	$id_ad = intval($_GET["id_ad"]);
	$type = $_GET["type"];

	if ( ($id_ad<1) || (strlen($type)<0) ) {
		ListUserAds();
		exit;
	}

	switch ($type) {
		case "rent":
			$type = 1;
			break;
		default:
			ListUserAds();
			break;
	}
	//////////////////////
	$res = $dbconn->Execute("SELECT type FROM ".RENT_ADS_TABLE." WHERE id_user='1' AND id='".$id_ad."' ");
	$ads_type = $res->fields[0];

	$strSQL = " SELECT min_payment, max_payment FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='1'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	if($ads_type == "1" || $ads_type == "3"){
		$data_1["payment"] = PaymentFormat($row["max_payment"]);
	}elseif($ads_type == "2" || $ads_type == "4"){
		$data_1["payment"] = PaymentFormat($row["min_payment"]);
	}

	$used_references = array("realty_type");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data[$arr["key"]] = SprTableSelectAdmin($arr["spr_user_table"], $id_ad, 1, $arr["spr_table"]);
			$spr_order = ($arr["key"] == "description") ? "id" : "name";
			$arrq=GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order);
		}
	}
	$flag = 0;
	foreach ($arrq[0]["opt"] as $key=>$value) {
		if(isset($value["sel"])){
			$flag = 1;
		}
	}

	if($flag ==1 && $data_1["payment"]>0){
	/////////////////////
		$dbconn->Execute("DELETE FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_user='1' AND id_ad='".$id_ad."' AND type='".$type."' ");
		$timestamp = time();
		$dbconn->Execute("INSERT INTO ".TOP_SEARCH_ADS_TABLE." (id_user, id_ad, type, date_begin, date_end) VALUES ('1', '".$id_ad."','".$type."', '".date('Y-m-d H:i:s', $timestamp)."', '".date('Y-m-d H:i:s', $timestamp+1*24*60*60)."' ) ");
		
		ListingActivate($id_ad);
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&id_ad=$id_ad&err=your_ad_was_topsearched");				
		exit;
	}else{
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&id_ad=$id_ad&err=activate_alert");		
		exit;
	}
}

/**
 * выделить объявление в поиске id_service = 2 (сделать слайдшоу)
 */
function SlideShow() {
	global $config, $smarty, $dbconn, $lang, $cur,$REFERENCES;
	include "../include/class.gifmerge.php";

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_rentals.php";
	$smarty->assign("file_name", $file_name);

	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');


	$id_ad = intval($_GET["id_ad"]);
	$type = $_GET["type"];

	if ( ($id_ad<1) || (strlen($type)<0) ) {
		ListUserAds();
		exit;
	}
	$acc = $rs->fields[0];
	switch ($type) {
		case "rent":
			$table = USERS_RENT_UPLOADS_TABLE;
			$slide_table = RENT_ADS_TABLE;
			break;
		default:
			ListUserAds();
			exit;
			break;
	}

	//////////////////////
	$res = $dbconn->Execute("SELECT type FROM ".RENT_ADS_TABLE." WHERE id_user='1' AND id='".$id_ad."' ");
	$ads_type = $res->fields[0];

	$strSQL = " SELECT min_payment, max_payment FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='1'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	if($ads_type == "1" || $ads_type == "3"){
		$data_1["payment"] = PaymentFormat($row["max_payment"]);
	}elseif($ads_type == "2" || $ads_type == "4"){
		$data_1["payment"] = PaymentFormat($row["min_payment"]);
	}

	$used_references = array("realty_type");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data[$arr["key"]] = SprTableSelectAdmin($arr["spr_user_table"], $id_ad, 1, $arr["spr_table"]);
			$spr_order = ($arr["key"] == "description") ? "id" : "name";
			$arrq=GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order);
		}
	}

	$flag = 0;
	foreach ($arrq[0]["opt"] as $key=>$value) {
		if(isset($value["sel"])){
			$flag = 1;
		}
	}

	if($flag ==1 && $data_1["payment"]>0){
	/////////////////////
		$strSQL = " SELECT COUNT(id) FROM ".$table." WHERE id_ad='".$id_ad."' AND id_user='1' AND status='1' AND admin_approve='1' AND upload_type='f'";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]<2) {
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&id_ad=$id_ad&err=more_photo");						
			exit;
		} else {
			$strSQL = "	SELECT upload_path FROM ".$table." WHERE id_ad='".$id_ad."' AND id_user='1' AND status='1' AND admin_approve='1' AND upload_type='f'";
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			$images_arr = array();
			$delay = array();
			$xy = array();
			while(!$rs->EOF){
				$images[$i]["upload_path"] = $rs->fields[0];
				array_push($images_arr, GetGifFromImage($images[$i]["upload_path"], $i+1));
				array_push($delay, 100);//1 second I suppose:)
				array_push($xy, 0);
				$rs->MoveNext();
				$i++;
			}
			$anim = new GifMerge($images_arr, 255, 255, 255, 0, $delay, $xy, $xy, 'C_FILE');
			$image = $anim->getAnimation();
			$slide_path = $config["site_path"]."/uploades/photo/1_".$id_ad."_slide.gif";
			$file = fopen($slide_path, 'w+');
			fputs($file, $image);
			fclose($file);
			foreach ($images_arr as $image_to_delete){
				unlink($image_to_delete);
			}

			$date_slided = date("Y-m-d H:i:s", time()+60*60*24*(GetSiteSettings("slideshow_period")));
			$date_spended = date("Y-m-d H:i:s", time());
			$dbconn->Execute("UPDATE ".$slide_table." SET upload_path='1_".$id_ad."_slide.gif"."', date_slided='".$date_slided."' WHERE id='".$id_ad."' AND id_user='1' ");
			ListingActivate($id_ad);
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&id_ad=$id_ad&err=your_ad_was_slideshowed");
		}
	}else{
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&id_ad=$id_ad&err=activate_alert");
		exit;
	}
}

function GetGifFromImage ($name, $i) {
	global $config;
	$path = $config["site_path"]."/uploades/photo/thumb_".$name;
	$new_path = $config["site_path"]."/uploades/slideshow/1_".$i."_temp.gif";
	$image_info = GetImageSize($path);
	$image_type = $image_info[2];

	switch($image_type){
		case "1" :
			$srcImage = @ImageCreateFromGif($path);
			break;	/// GIF
		case "2" :
			$srcImage = @imagecreatefromjpeg($path);
			break;	/// JPG
		case "3" :
			$srcImage = @imagecreatefrompng($path);
			break;	/// PNG
		case "6" :
			$srcImage = @imagecreatefromwbmp($path);
			break;	/// BMP
	}
	if($srcImage){
		if (function_exists("imagegif")) ImageGif( $srcImage, $new_path );
		else return false;
	}
	ImageDestroy( $srcImage  );
	return $new_path;
}

/**
 * сделать объявление лидером региона id_service = 3
 */
function MakeAdFeatured() {
	global $config, $smarty, $dbconn, $lang, $cur,$REFERENCES;

	IndexAdminPage('admin_rentals');
	CreateMenu('admin_lang_menu');
			
	$id_ad = intval($_POST["id_ad"]);
	$id_region = $_POST["id_region"];
	$headline = strip_tags(trim($_POST["feature_headline"]));
	$value = floatval($_POST["curr_value"]);
	$type = $_REQUEST["type"];

	if ( $id_ad<1 ) {
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=unknown_error");
		exit;
	}
	if (strlen($headline)<1){
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=no_headline");
		exit;
	}
	if (BadWordsCont($headline)){
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=badword");
		exit;
	}

	//////////////////////
	$strSQL = " SELECT curr_count, upload_path FROM ".FEATURED_TABLE." WHERE id_region='".$id_region."' AND type='1'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0){
		$current_count = $rs->fields[0];
		$old_photo_name = $rs->fields[1];
	} else {
		$current_count = 0;
		$old_photo_name = "";
	}
	if ($value <= $current_count) {		
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=small_curr");		
		exit;
	}

	$strSQL = "	SELECT id, upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='1' AND upload_type='f' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]<1) {		
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=no_photo_for_feature");		
		exit;
	} else {

		$res = $dbconn->Execute("SELECT type FROM ".RENT_ADS_TABLE." WHERE id_user='1' AND id='".$id_ad."' ");
		$ads_type = $res->fields[0];

		$strSQL = " SELECT min_payment, max_payment FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='1'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		if($ads_type == "1" || $ads_type == "3"){
			$data_1["payment"] = PaymentFormat($row["max_payment"]);
		}elseif($ads_type == "2" || $ads_type == "4"){
			$data_1["payment"] = PaymentFormat($row["min_payment"]);
		}

		$used_references = array("realty_type");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$data[$arr["key"]] = SprTableSelectAdmin($arr["spr_user_table"], $id_ad, 1, $arr["spr_table"]);
				$spr_order = ($arr["key"] == "description") ? "id" : "name";
				$arrq=GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order);
			}
		}
		$flag = 0;
		foreach ($arrq[0]["opt"] as $key=>$spr_val) {
			if(isset($spr_val["sel"])){
				$flag = 1;
				break;
			}
		}
		if($flag ==1 && $data_1["payment"]>0){
			/////////////////////

			$strSQL = " SELECT upload_path FROM ".FEATURED_TABLE." WHERE id_region='".$id_region."' AND type='1'";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0){
				$old_photo_name = $rs->fields[0];
			} else {
				$old_photo_name = "";
			}

			$strSQL = "	SELECT id, upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='1' AND upload_type='f'";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]<1) {				
				header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=no_photo_for_feature");
				exit;
			} else {
				$photo_name = $rs->fields[1];
				//copy user's thumb to special folder 'featured'
				copy($config["site_path"]."/uploades/photo/thumb_".$photo_name, $config["site_path"]."/uploades/featured/thumb_".$photo_name);
				//unlink old featured photo
				unlink($config["site_path"]."/uploades/featured/thumb_".$old_photo_name);

				$strSQL = " SELECT id FROM ".FEATURED_TABLE." WHERE id_region='".$id_region."' AND type='1' ";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0){
					$strSQL = " UPDATE ".FEATURED_TABLE."
								SET id_user='1', id_ad='".$id_ad."',  headline='".addslashes($headline)."',
									date_featured=now(), curr_count='".$value."', datenow=now(), upload_path='".$photo_name."'
								WHERE id_region='".$id_region."' AND type='1' ";
					$dbconn->Execute($strSQL);
				} else {
					$strSQL = " INSERT INTO ".FEATURED_TABLE." (id_user, id_ad, headline, date_featured, curr_count, datenow, upload_path, id_region, type)
														VALUES ('1', '".$id_ad."', '".addslashes($headline)."', now(), '".$value."', now(), '".$photo_name."', '".$id_region."', '1')";
					$dbconn->Execute($strSQL);
				}
				ListingActivate($id_ad);
				header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=you_was_featured");				
			
				exit;
			}
		}else{			
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?sel=listing_position&sub_sel=make_featured&id_ad=$id_ad&err=activate_alert");				
			exit;
		}
	}
}

function GetFreeDays() {
	header('Content-type: text/html; charset=utf-8');
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$year = isset($_REQUEST["year"]) ? intval($_REQUEST["year"]) : "";		
	$month = isset($_REQUEST["month"]) ? intval($_REQUEST["month"]) : "";		
	$amount_days = adodb_date( "t", adodb_mktime(0, 0, 0, $month, 1, $year) );
			
	$calendar_event = new CalendarEvent();
	$reserve_days = $calendar_event->GetReserveDays($id_ad, 1);
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
	$reserve_days = $calendar_event->GetReserveDays($id_ad, 1);			
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
	$user_id = 1;
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
	$user_id = 1;	
	$calendar_event = new CalendarEvent();	
	$edited_event = $calendar_event->GetReserveDays($id_ad, $user_id, $id_event);			
	
	CalendarAd("edited_event", "", $edited_event[0]["start_tmstmp"], $edited_event[0]["end_tmstmp"], $id_event);	
}

function EditedEvent() {
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$id_event = isset($_REQUEST["id_event"]) ? intval($_REQUEST["id_event"]) : "";			
	$user_id = 1;	
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