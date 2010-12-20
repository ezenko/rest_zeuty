<?php
/**
* View property profile (full information on listing),
* add to blacklist, hotlist, show interest
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.13 $ $Date: 2009/01/14 09:24:38 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";
include "./include/class.lang.php";
include "./include/class.images.php";
include "./include/class.calendar_event.php";
if (GetSiteSettings("use_pilot_module_sms_notifications")){
	include "./include/functions_sms_notifications.php";
}


$user = auth_index_user();
$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";

if ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	if ($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
		if ($_REQUEST["for_unreg_user"] == 1) {
			$user = auth_guest_read();
		}		
		/**
		 * Set id for pages' preview
		 */
		$rs = $dbconn->Execute("SELECT MIN(id) FROM ".RENT_ADS_TABLE." WHERE status='1'");		
		$_GET["id"] = $rs->fields[0];
	}
	$multi_lang = new MultiLang($config, $dbconn);

	/*if ($_GET["id"]==$user[0]) {
		echo "<script>location.href='".$config["site_root"]."/homepage.php'</script>";
		exit;
	}*/
	switch ($sel) {
		case "addtohot":			AddToHotList();			break;
		case "addtoblack":			AddToBlackList();		break;
		case "interest":			Interests();			break;
		case "more_ad":				ListMore();				break;
		case "upload_view":			UploadView(); 			break;
		case "print":				ListProfile("print", intval($_GET["id"]));	break;
		case "get_hour_by_date":	GetHourByDate();break;
		default: 					ListProfile("rent", intval($_GET["id"]));	break;
	}

}

/**
 * view full information on the listing with $id_ad
 *
 * @param string $sect
 * @param integer $id_ad
 */
function ListProfile($sect, $id_ad) {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "viewprofile.php";

	IndexHomePage('viewprofile', 'homepage');

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

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$smarty->assign("add_to_lang", '&amp;id='.$id_ad);

	$profile = Ad($id_ad, $user[0], $file_name, $sect);

	if ($profile["id_user"] != 0) {
		/**
		 * update profile visit counter
		 */
		$rs = $dbconn->Execute(" SELECT COUNT(id_visiter) FROM ".PROFILE_VISIT_TABLE." WHERE id_visiter='".$user[0]."' AND id_user='".$profile["id_user"]."' ");
		if ( $rs->fields[0]>0 ) {
			$dbconn->Execute(" UPDATE ".PROFILE_VISIT_TABLE." SET visits_count=visits_count+1, last_visit_date=now() WHERE id_visiter='".$user[0]."' AND id_user='".$profile["id_user"]."' ");
		} else {
			$dbconn->Execute(" INSERT INTO ".PROFILE_VISIT_TABLE." (id_user, id_visiter, last_visit_date, visits_count) VALUES ('".$profile["id_user"]."', '".$user[0]."', now(), '1') ");
		}

		$rs = $dbconn->Execute( "SELECT COUNT(id_visiter) FROM ".RENT_AD_VISIT_TABLE." WHERE id_visiter='".$user[0]."' AND id_ad='".$profile["id"]."' " );
		if ( $rs->fields[0]>0 ) {
			$first_time = 0;
			$dbconn->Execute(" UPDATE ".RENT_AD_VISIT_TABLE." SET visits_count=visits_count+1, last_visit_date=now() WHERE id_visiter='".$user[0]."' AND id_ad='".$profile["id"]."' ");
		} else {
			$first_time = 1;
			$dbconn->Execute(" INSERT INTO ".RENT_AD_VISIT_TABLE." (id_ad, id_visiter, last_visit_date, visits_count) VALUES ('".$profile["id"]."', '".$user[0]."', now(), '1') ");
		}

		if (($user[0]>2) && ($first_time == 1)) {
			/**
			 * send mail if user subscribed on notification "someone viewed my profile"
			 */
			$rs = $dbconn->Execute("SELECT  id, fname, sname, email from ".USERS_TABLE." where id='".$user[0]."' ");
			$row = $rs->GetRowAssoc(false);
			$data["id"] = $row["id"];
			$data["login"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
			$use_pilot_module_sms_notifications = GetSiteSettings("use_pilot_module_sms_notifications");
			if ($use_pilot_module_sms_notifications){
				$strSQL = "	SELECT DISTINCT b.id AS id_user, b.email, b.fname, b.sname, b.lang_id, a.id_user AS email_subscribe, sms_u.id_user AS sms_subscribe 
					FROM ".USERS_TABLE." b
					LEFT JOIN ".SUBSCRIBE_USER_TABLE." a ON b.id=a.id_user 
					LEFT JOIN ".SMS_NOTIFICATIONS_USER_EVENT." sms_u ON sms_u.id_user=b.id 
					WHERE ( (a.type='s' AND a.id_subscribe='3') OR sms_u.id_subscribe='3') AND b.id='".$profile["id_user"]."'
					GROUP BY b.id";

			}else{
				$strSQL = "	SELECT DISTINCT a.id_user, b.email, b.fname, b.sname, b.lang_id
						FROM ".SUBSCRIBE_USER_TABLE." a, ".USERS_TABLE." b
						WHERE a.type='s' AND a.id_subscribe='3' AND b.id=a.id_user AND b.id='".$profile["id_user"]."'
						GROUP BY a.id_user";
			}
			$rs = $dbconn->Execute($strSQL);
			if ($rs->RowCount() > 0) {
				$row = $rs->GetRowAssoc(false);

				$id_user["id"] = $row["id_user"];
				$id_user["email"] = $row["email"];
				$id_user["fname"] = stripslashes($row["fname"]);
				$id_user["sname"] = stripslashes($row["sname"]);
				$id_user["login"] = $row["email"];
				$id_user["lang_id"] = $row["lang_id"];

				$data["email"] = $row["email"];
				$email_to_name = $id_user["fname"]." ".$id_user["sname"];
				$data["to_name"] = $email_to_name;
				$data["unsubscribe_possible"] = 1;
				if (GetSiteSettings("use_pilot_module_sms_notifications")){
					$id_user["email_subscribe"] = $row["email_subscribe"];
					$id_user["sms_subscribe"] = $row["sms_subscribe"];
				}


				$mail_content = GetMailContentReplace("mail_content_viewme", GetUserLanguageId($id_user["lang_id"]));//xml
				$site_mail = GetSiteSettings('site_email');

			
				if (!$use_pilot_module_sms_notifications || ($use_pilot_module_sms_notifications && $id_user["email_subscribe"])){
					SendMail($id_user["email"], $site_mail, $mail_content["subject"], $data, $mail_content, "mail_viewme_subscr_table", '', $email_to_name, $mail_content["site_name"],"text" );
				}
				
				//SMS-notification
				if ($use_pilot_module_sms_notifications && $id_user["sms_subscribe"]){
					$sms_settings = GetSmsSettings();
					if ($sms_settings["use"]){
						$user_sms_data = GetUserSmsData($profile["id_user"], 3);
						$sms_text = str_replace(array("{USER_NAME}", "{SITE_NAME}"),array($email_to_name, $mail_content["site_name"]),GetSmsText(3));
						if ($user_sms_data && ($user_sms_data["sms_balance"] != 0)){
							SendSms($user_sms_data["phone"], $sms_text, $profile["id_user"], 3);						
						}
					}
				}
				
				//end of SMS-notification

			}
		}
	}else{
		echo "<script>document.location.href='./error.php?code=410'</script>";
	}
	$smarty->assign("profile",$profile);

	$use_maps_in_viewprofile = GetSiteSettings("use_maps_in_viewprofile");

	$menu_sections[] = "general";
	if ((isset($profile["photo_id"]) || isset($profile["plan_id"]))  && ($profile["photo_id"] || $profile["plan_id"]) && ($profile["type"] == 2 || $profile["type"] == 4)) {
		$menu_sections[]	= "photo";
	}

	if (isset($profile["video_id"]) && ($profile["type"] == 2 || $profile["type"] == 4)) {
		$menu_sections[]	= "video";
	}
	if ($use_maps_in_viewprofile && ($profile["type"] == 2 || $profile["type"] == 4)) {
		$menu_sections[]	= "map";
	}
	if ($profile["type"] == 2 ) {
		$menu_sections[]	= "calendar";
	}
	$smarty->assign("use_maps_in_viewprofile", $use_maps_in_viewprofile);
	$view = (isset($_REQUEST["view"]) && !empty($_REQUEST["view"])) ? strval($_REQUEST["view"]) : 'general';
	if (!in_array($view, $menu_sections)) {
		$view = 'general';
	}
	$smarty->assign("view", $view);	
	$smarty->assign("map",GetMapSettings());

	
	if ($view == "calendar") {
		/**
		 * Get info for calendar displaying
		 */
		$date["months"] = GetMonth();	
		$date["day_of_week"] = GetDayOfWeek();
		$date["now_date"] = getdate();		
		$start_month = isset($_REQUEST["start_month"]) ? intval($_REQUEST["start_month"]) : $date["now_date"]["mon"];
		$start_year = isset($_REQUEST["start_year"]) ? intval($_REQUEST["start_year"]) : $date["now_date"]["year"];				
		$calendar_event = new CalendarEvent();
		$date["display"] = $calendar_event->GetMonthYearArray($start_month, $start_year, $profile["id"], $profile["id_user"], 12, 12);			
		$smarty->assign("half_tf_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_tf_day.gif");
		$smarty->assign("half_ft_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_ft_day.gif");
		$smarty->assign("half_tft_image", $config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/half_tft_day.gif");
		$smarty->assign("date", $date);
		$smarty->assign("id_ad", $profile["id"]);
	}	
	$profile["country_name_t"]=$profile["country_name"];
	$profile["region_name_t"]=$profile["region_name"];
	$profile["city_name_t"]=$profile["city_name"];	
	$profile["adress_t"]=$profile["adress"];
	$profile["lat_t"]=$profile["lat"];
	$profile["lon_t"]=$profile["lon"];
	
	if ($profile["country_name_t"] == '') {
		$data["in_base"]=0;
	}
	else {$data["in_base"]=1;
	}	
	
		
	
	if ($view=='general' && $sect != "print") {		
		if ($profile["user_type"] == 3 ) {						
			if (isset($profile["company_data"])) {										
					$profile["country_name"]=$profile["company_data"]["country_name"];				
					$profile["region_name"]=$profile["company_data"]["region_name"];
					$profile["city_name"]=$profile["company_data"]["city_name"];
					$profile["adress"]=$profile["company_data"]["address"];
					$profile["lat"]=$profile["company_data"]["lat"];
					$profile["lon"]=$profile["company_data"]["lon"];												
			}	
		} else {
			$temp_data = GetAccountTableInfo($profile["id_user"]);	
			if (isset($temp_data["country_name"])){		
				$profile["country_name"]=$temp_data["country_name"];
				$profile["region_name"]=$temp_data["region_name"];
				$profile["city_name"]=$temp_data["city_name"];
			}
			if (isset($temp_data["address"])){		
				$profile["adress"]=$temp_data["address"];
			}
			if (isset($temp_data["lat"])){		
				$profile["lat"]=$temp_data["lat"];
			}
			
			if (isset($temp_data["lon"])){		
				$profile["lon"]=$temp_data["lon"];
			}				
				
			if ($profile["country_name"] == '') {				
				$profile["in_base"]=0;
			}
			else {$profile["in_base"]=1;
			}	
		}							
	}	
	
	$smarty->assign("profile",$profile);
	$smarty->assign("data", $data);	
	$smarty->assign("menu_sections", $menu_sections);
	$smarty->assign("sect", $sect);
	$smarty->assign("file_name", $file_name);
	$smarty->assign("page_title", $profile["account"]["fname"]." ".$lang["default_select"][$profile["type_name"]]." ".$profile["realty_type_in_line"]." ".$profile["country_name"].($profile["region_name"] ? ", ".$profile["region_name"] : "").($profile["city_name"] ? ", ".$profile["city_name"] : ""));
	if ($sect == "print") {
		$smarty->display(TrimSlash($config["index_theme_path"])."/viewprofile_print.tpl");
	} else {
			
		$smarty->display(TrimSlash($config["index_theme_path"])."/viewprofile_table.tpl");
	}
	exit;
}

/**
 * Add listing to the current users' hotlist
 * @return void
 */
function AddToHotList() {
	global $config, $smarty, $dbconn, $user;
	$sect = $_GET["section"];
	$add_id = (isset($_GET["id"])) ? intval($_GET["id"]) : "";
	if (intval($add_id) && intval($user[0]) && $user[3]!=1 && $user[0]!=$add_id) {
		$strSQL = "INSERT INTO ".HOTLIST_TABLE." (id_user, id_friend) VALUES ( '".$user[0]."', '".$add_id."') ";
		$dbconn->Execute($strSQL);
	}
	if ($sect != 'mailbox') {
		ListProfile($sect, intval($_GET["id_ad"]));
	} else {
		echo "<script>document.location.href='./mailbox.php'</script>";
	}
	exit;
}

/**
 * Add listing to the current users' blacklist
 * @return void
 */
function AddToBlackList() {
	global $config, $smarty, $dbconn, $user;
	$sect = $_GET["section"];
	$add_id = (isset($_GET["id"])) ? intval($_GET["id"]) : "";
	if (intval($add_id) && intval($user[0]) && $user[3]!=1 && $user[0]!=$add_id) {
		$strSQL = "INSERT INTO ".BLACKLIST_TABLE." (id_user, id_enemy) VALUES ( '".$user[0]."', '".$add_id."') ";
		$dbconn->Execute($strSQL);
	}

	if ($sect != 'mailbox') {
		ListProfile($sect, intval($_GET["id_ad"]));
	} else {
		echo "<script>document.location.href='./mailbox.php'</script>";
	}
	exit;
}

/**
 * Current user shows interest to the listing
 * @return void
 */
function Interests() {
	global $config, $smarty, $dbconn, $user;
	$sect = $_GET["section"];
	$add_id = (isset($_GET["id"])) ? intval($_GET["id"]) : "";
	if (intval($add_id) && intval($user[0]) && $user[3]!=1 && $user[0]!=$add_id) {		
		$strSQL = "INSERT INTO ".INTERESTS_TABLE." (id_user, id_interest_user, id_interest_ad, interest_date ) VALUES ( '".$user[0]."', '".$add_id."', '".intval($_GET["id_ad"])."', now() ) ";
		$dbconn->Execute($strSQL);
	}

	if ($user[0]>2) {

		$rs = $dbconn->Execute("SELECT  id, fname, sname, email from ".USERS_TABLE." where id='".$user[0]."' ");
		$row = $rs->GetRowAssoc(false);
		$data["id"] = $row["id"];
		$data["login"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
		$use_pilot_module_sms_notifications = GetSiteSettings("use_pilot_module_sms_notifications");
		if ($use_pilot_module_sms_notifications){
			$strSQL = "	SELECT DISTINCT b.id AS id_user, b.email, b.fname, b.sname, b.lang_id, a.id_user AS email_subscribe, sms_u.id_user AS sms_subscribe 
						FROM ".USERS_TABLE." b
						LEFT JOIN ".SUBSCRIBE_USER_TABLE." a ON b.id=a.id_user 
						LEFT JOIN ".SMS_NOTIFICATIONS_USER_EVENT." sms_u ON sms_u.id_user=b.id 
						WHERE ( (a.type='s' AND a.id_subscribe='4') OR sms_u.id_subscribe='4') AND b.id='".$add_id."'
						GROUP BY b.id";
		}else{
			$strSQL = "	SELECT DISTINCT a.id_user, b.email, b.fname, b.sname, b.lang_id
						FROM ".SUBSCRIBE_USER_TABLE." a, ".USERS_TABLE." b
						WHERE a.type='s' AND a.id_subscribe='4' AND b.id=a.id_user AND b.id='".$add_id."'
						GROUP BY a.id_user";
		}
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);

		$id_user["id"] = $row["id_user"];
		$id_user["email"] = $row["email"];
		$id_user["fname"] = stripslashes($row["fname"]);
		$id_user["sname"] = stripslashes($row["sname"]);
		$id_user["login"] = $row["email"];
		$id_user["lang_id"] = $row["lang_id"];
		$data["email"] = $row["email"];

		$data["link_read"] = $config["server"].$config["site_root"]."/index.php?lang_code=".$config["default_lang"]."login=".$id_user["login"];
		$email_to_name = $id_user["fname"]." ".$id_user["sname"];
		$data["to_name"] = $email_to_name;
		$data["unsubscribe_possible"] = 1;
		if ($use_pilot_module_sms_notifications){
			$id_user["email_subscribe"] = $row["email_subscribe"];
			$id_user["sms_subscribe"] = $row["sms_subscribe"];
		}

		$mail_content = GetMailContentReplace("mail_content_interme", GetUserLanguageId($id_user["lang_id"]));//xml
		$site_mail = GetSiteSettings('site_email');
		$smarty->assign("mail_template_root", $config["index_theme_path"]);

		if ( $id_user["id"]>0 ) {
			if (!$use_pilot_module_sms_notifications || ($use_pilot_module_sms_notifications && $id_user["email_subscribe"])){
				SendMail($id_user["email"], $site_mail, $mail_content["subject"], $data, $mail_content, "mail_interme_subscr_table", '', $email_to_name, $mail_content["site_name"]);
			}
			//SMS-notification
			if ($use_pilot_module_sms_notifications && $id_user["sms_subscribe"]){
				$sms_settings = GetSmsSettings();
				if ($sms_settings["use"]){
					$user_sms_data = GetUserSmsData($id_user["id"], 4);
					$sms_text = str_replace(array("{USER_NAME}", "{SITE_NAME}"),array($email_to_name, $mail_content["site_name"]),GetSmsText(4));
					if ($user_sms_data && ($user_sms_data["sms_balance"] != 0)){
						SendSms($user_sms_data["phone"], $sms_text, $id_user["id"], 4);						
					}
				}
			}
			
			//end of SMS-notification

		}
	}

	ListProfile($sect, intval($_GET["id_ad"]));
	exit;
}

/**
 * Show more listings which owner is owner of the viewed listing
 * @return void
 */
function ListMore() {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	$id_user = (isset($_GET["id_user"])) ? intval($_GET["id_user"]) : "";

	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "viewprofile.php";

	IndexHomePage('viewprofile', 'homepage');

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
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$search_numpage = GetSiteSettings("ads_num_page");
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));

	$strSQL = "SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$id_user."' AND status='1' ";
	$rs = $dbconn->Execute($strSQL);
	$num_records = intval($rs->fields[0]);

	$lim_min = ($page-1)*$search_numpage;
	$lim_max = $search_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$settings = GetSiteSettings();
	$strSQL = "	SELECT DISTINCT ra.id, ra.id_user, DATE_FORMAT(ra.movedate,'".$config["date_format"]."') as movedate,
				ra.type, ra.room_type, ra.sold_leased_status, ra.headline, u.fname, u.user_type,
				urp.min_payment, urp.max_payment, urp.auction, uru.upload_path, uru.user_comment,
				ct.name as country_name, rt.name as region_name, cit.name as city_name, 
				sp.status as spstatus, tsat.type as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end, ft.id as featured,
				rd.company_name  
				FROM ".RENT_ADS_TABLE." ra
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id
				LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
				LEFT JOIN ".USERS_RENT_PAYS_TABLE." urp ON urp.id_ad=ra.id
				LEFT JOIN ".USERS_RENT_UPLOADS_TABLE." uru ON uru.id_ad=ra.id AND uru.upload_type = 'f' AND uru.status='1' AND uru.admin_approve='1'
				LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country
				LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=ra.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=ra.id
				LEFT JOIN ".USER_REG_DATA_TABLE." rd ON rd.id_user=ra.id_user
				WHERE ra.id_user='".$id_user."' AND ra.status='1'
				GROUP BY ra.id ORDER BY ra.id ".$limit_str;
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF) {		
		$row = $rs->GetRowAssoc(false);
		$search_result[$i]["number"] = ($page-1)*$search_numpage+($i+1);
		$search_result[$i]["id_ad"] = $row["id"];
		$search_result[$i]["room_type"] = $row["room_type"];
		$search_result[$i]["id_user"] = $row["id_user"];
		if ($row["movedate"] != '00.00.0000') {
			$search_result[$i]["movedate"] = $row["movedate"];
		}
		$search_result[$i]["id_type"] = $row["type"];
		$search_result[$i]["login"] = $row["fname"];
		
		if ($search_result[$i]["id_type"] == 2) {			
			$calendar_event = new CalendarEvent();
			$search_result[$i]["reserve"] = $calendar_event->GetEmptyPeriod($search_result[$i]["id_ad"], $search_result[$i]["id_user"]);	
		}		
				
		$profile["login"] = $row["fname"];
		$profile["user_type"] = $row["user_type"];

		$lang_ad = 2;
		$used_references = array("realty_type");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$name = GetUserAdSprValues($arr["spr_user_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
				$search_result[$i][$arr["key"]] = implode(",", $name);
			}
		}
		$search_result[$i]["issponsor"] = $row["spstatus"];
		$search_result[$i]["sold_leased_status"] = $row["sold_leased_status"];
		if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
			$search_result[$i]["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
		} else {
			$search_result[$i]["headline"] = stripslashes($row["headline"]);
		}
		$search_result[$i]["min_payment"] = PaymentFormat($row["min_payment"]);
		$search_result[$i]["max_payment"] = PaymentFormat($row["max_payment"]);
		$search_result[$i]["auction"] = $row["auction"];
		
		$search_result[$i]["topsearched"] = $row["topsearched"];
		$search_result[$i]["featured"] = $row["featured"];
		$search_result[$i]["company_name"] = $row["company_name"];

		if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
			$search_result[$i]["show_topsearch_icon"] = true;
			$search_result[$i]["topsearch_date_begin"] = $row["topsearch_date_begin"];
		}

		$used_references = array("gender");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$name = GetUserGenderIds($arr["spr_user_table"], $search_result[$i]["id_user"], 0, $arr["val_table"]);
				$search_result[$i][$arr["key"]] = $name;
			}
		}
		$gender_info = getDefaultUserIcon($profile["user_type"], $search_result[$i]["gender"]);
		$search_result[$i]["image"] =  $settings["photo_folder"]."/".$gender_info["icon_name"];
		$search_result[$i]["alt"] = $gender_info["icon_alt"];

		if (strlen($row["upload_path"])>1) {
			$search_result[$i]["image"] = $settings["photo_folder"]."/thumb_".$row["upload_path"];
			$search_result[$i]["alt"] = $row["user_comment"];
		}
		$search_result[$i]["viewprofile_link"] = "./viewprofile.php?id=".$search_result[$i]["id_ad"];

		if ($config["lang_ident"]!='ru') {
			$search_result[$i]["country_name"] = RusToTranslit($row["country_name"]);
			$search_result[$i]["region_name"] = RusToTranslit($row["region_name"]);
			$search_result[$i]["city_name"] = RusToTranslit($row["city_name"]);
		} else {
			$search_result[$i]["country_name"] = $row["country_name"];
			$search_result[$i]["region_name"] = $row["region_name"];
			$search_result[$i]["city_name"] = $row["city_name"];
		}

		$rs->MoveNext();
		$i++;
	}
	$smarty->assign("add_to_lang", "&amp;sel=more_ad&amp;id_user=".$id_user."&amp;section=rent");
	$param = $file_name."?sel=more_ad&amp;id_user=".$id_user."&amp;section=rent&amp;";
	
	$redirect = (isset($_REQUEST["redirect"]) && !empty($_REQUEST["redirect"])) ? intval($_REQUEST["redirect"]) : 0;
	$back_link = "";
	$back_this_link = "";
	switch ($redirect) {
		case "1":
			$temp_link = "services.php?sel=payment_between_user&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorter"])."&search=".$_REQUEST["search"]."&order=".intval($_REQUEST["order"])."&is_show=".$_REQUEST["is_show"];
			$back_link = $temp_link."&user_index=".intval($_REQUEST["user_index"]);
			$back_this_link = $temp_link."&user_index=".intval($_REQUEST["user_myself"]);
			break;
		case "2":
			$temp_link = "registration.php?sel=choose_company&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorter"])."&search=".$_REQUEST["search"]."&order=".intval($_REQUEST["order"])."&is_show=".intval($_REQUEST["is_show"]);
			if (isset($_REQUEST["user_index"])){
				$back_link = $temp_link."&user_index=".intval($_REQUEST["user_index"]);
			}
			$back_link = $temp_link;
			$back_this_link = $temp_link."&id_company=".$id_user;
			break;
		case "3":
			$temp_link = "agents.php?sel=choose_agent&page=".intval($_REQUEST["pageR"])."&sorter=".intval($_REQUEST["sorter"])."&search=".$_REQUEST["search"]."&order=".intval($_REQUEST["order"])."&is_show=".intval($_REQUEST["is_show"]);
			$back_link = $temp_link."&user_index=".$_REQUEST["user_index"];
			$back_this_link = $temp_link."&id_agent=".$id_user;
			break;
	}
	$smarty->assign("redirect", $redirect);
	$smarty->assign("back_link", $back_link);
	$smarty->assign("back_this_link", $back_this_link);
	$smarty->assign("links", GetLinkArray($num_records, $page, $param, $lim_max));
	$smarty->assign("search_result", $search_result);
	$smarty->assign("empty_result", 0);
	$smarty->assign("sect", "more_list");
	$smarty->assign("file_name", $file_name);
	$smarty->assign("profile", $profile);
	$smarty->display(TrimSlash($config["index_theme_path"])."/viewprofile_table.tpl");
}

/**
 * Get uploaded file info and assign info array to a smarty variable $upload_file,
 * show/play uploaded file in the popup window
 */
function UploadView() {
	global $smarty, $dbconn, $config, $lang, $multi_lang;

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"viewprofile.php";
	IndexHomePage('viewprofile', 'homepage');
	$id_file = intval($_GET["id_file"]);
	$category = $_GET["category"];

	$table = USERS_RENT_UPLOADS_TABLE;

	$rs = $dbconn->Execute("SELECT upload_path, upload_type, user_comment
							from ".$table."
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

function GetHourByDate() {
	header('Content-type: text/html; charset=utf-8');
	global $smarty, $dbconn, $user;
	$id_ad = isset($_REQUEST["id_ad"]) ? intval($_REQUEST["id_ad"]) : "";		
	$id_user = isset($_REQUEST["id_user"]) ? intval($_REQUEST["id_user"]) : "";
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
?>