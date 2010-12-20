<?php
/**
* General site settings' management
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.23 $ $Date: 2009/01/21 10:17:51 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";

include "../include/functions_xml.php";
include "../include/class.object2xml.php";
include "../include/class.lang.php";
include "../include/class.images.php";
include "../include/class.settings_manager.php";
include "../include/functions_mail.php";

if ( !(isset($_REQUEST['sel'])) || ($_REQUEST['sel'] != 'add_form')){
$auth = auth_user();

if( (!($auth[0]>0))  || (!($auth[4]==1))) {
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
}
$multi_lang = new MultiLang($config, $dbconn);
$section = (isset($_REQUEST["section"]) && !empty($_REQUEST["section"])) ? trim($_REQUEST["section"]) : "";
$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? trim($_REQUEST["sel"]) : "";
$err = (isset($_REQUEST["err"]) && !empty($_REQUEST["err"])) ? trim($_REQUEST["err"]) : "";
$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));

switch($sel) {
	case "save": SaveSettings($section); break;
	case "save_settings": SaveSettingsMisc();break;
	case "default": SetDefault($section); break;
	case "upload_icon": UploadIcon(); break;
	case "del_icon": DelIcon($_GET["id_icon"]); break;
	case "search_string": SearchString(); break;
	case "add_form": AdditionForm();break;
	default: ListSettings($section, $err);
}

function ListSettings($section="", $err="", $err_id="") {
	global $smarty, $dbconn, $config, $REFERENCES, $fonts, $fonts_size;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_settings.php";

	if (!(strlen($section)>0) || $section=='pass_change') {
		$section = "admin";
	}
	$file_virt_name = $config["server"].$config["site_root"]."/admin/".$file_name."?section=$section";

	IndexAdminPage('admin_settings');
	CreateMenu('admin_lang_menu');

	switch($section) {
		case "admin":
			//administrator info settings
			$smarty->assign("map",GetMapSettings());
			$smarty->assign("use_maps_in_account", GetSiteSettings("use_maps_in_account"));
			$smarty->assign("use_agent_user_type", GetSiteSettings("use_agent_user_type"));
			$redirect = (isset($_REQUEST["redirect"]) && !empty($_REQUEST["redirect"])) ? intval($_REQUEST["redirect"]) : "";
			$smarty->assign("redirect", $redirect);
			$strSQL = "SELECT login, fname, sname, DATE_FORMAT(date_birthday, '%d') as birth_day, DATE_FORMAT(date_birthday, '%m') as birth_month, DATE_FORMAT(date_birthday, '%Y') as birth_year, lang_id, email, phone, user_type FROM ".USERS_TABLE." WHERE root_user='1' ";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$data = $row;
			$data["login"] = stripslashes($row["login"]);
			$data["fname"] = stripcslashes($row["fname"]);
			$data["sname"] = stripslashes($row["sname"]);
			$data["phone"] = stripslashes($row["phone"]);

			$settings_manager = new SettingsManager();
			$data["site_email"] = $settings_manager->GetSiteSettings("site_email");
			/**
			 * check for users' language visibility
			 */
			$data["lang_id"] = GetUserLanguageId($data["lang_id"]);

			$week = GetWeek();
			$smarty->assign("week", $week);

			$time_arr = GetHourSelect();
			$smarty->assign("time_arr", $time_arr);
			
			if ($data["user_type"] == 2) {
				$strSQL = " SELECT company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, logo_path, admin_approve, id_country,id_region,id_city,address,postal_code FROM ".USER_REG_DATA_TABLE." WHERE id_user='1' ";
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$data["company_name"] = stripslashes($row["company_name"]);
				$data["company_url"] = stripslashes($row["company_url"]);
				$data["company_rent_count"] = stripslashes($row["company_rent_count"]);
				$data["company_how_know"] = stripslashes($row["company_how_know"]);
				$data["company_quests_comments"] = stripslashes($row["company_quests_comments"]);
				$data["weekday_str"] = stripslashes($row["weekday_str"]);
				$data["weekday_1"] = explode(",",$data["weekday_str"]);
				foreach ($data["weekday_1"] as $value) {
					$data["weekday"][$value-1] = $value;
				}
				$data["work_time_begin"] = intval($row["work_time_begin"]);
				$data["work_time_end"] = intval($row["work_time_end"]);
				$data["logo_path"] = $row["logo_path"];
				$data["use_photo_approve"] = GetSiteSettings("use_photo_approve");
				$data["admin_approve"] = $row["admin_approve"];
				if ((strlen($data["logo_path"])>0) && (file_exists($config["site_path"]."/uploades/photo/".$data["logo_path"]))) {
					$data["logo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$data["logo_path"];
				} else {
					$data["logo_path"] = "";
				}
				$data["lunch_time_begin"] = intval($row["lunch_time_begin"]);
				$data["lunch_time_end"] = intval($row["lunch_time_end"]);
				$data["id_country"] = intval($row["id_country"]);
				$data["id_region"] = intval($row["id_region"]);
				$data["id_city"] = intval($row["id_city"]);
				$data["postal_code"] = $row["postal_code"];
				$data["address"] = $row["address"];									
				$strSQL = " SELECT name FROM ".COUNTRY_TABLE." where id=".$data["id_country"];
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$profile["country_name"] = $row["name"];				
				$strSQL = " SELECT name FROM ".REGION_TABLE." where id=".$data["id_region"];
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$profile["region_name"] = $row["name"];				
				$strSQL = " SELECT name,lat,lon FROM ".CITY_TABLE." where id=".$data["id_city"];
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$profile["city_name"] = $row["name"];
				$profile["lon"] = $row["lon"];
				$profile["lat"] = $row["lat"];				
				$profile["adress"] = $data["address"];
				if (($data["id_country"] && $profile["country_name"] == '')||($data["id_region"] && $profile["region_name"] == '')||($data["id_city"] && $profile["city_name"] == '')) {
					$data["in_base"]=0;
				}
				else {
					$data["in_base"]=1;				
				}
				if ($data["id_region"]==0) {
					$profile["region_name"]=''; 
					$profile["lon"]=0;
					$profile["lat"]=0;
				}
				if ($data["id_city"]==0) {
					$profile["city_name"]='';
					$profile["lon"]=0;
					$profile["lat"]=0;
				}					
				
				$strSQL_2 = "SELECT id_agent FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '1' AND approve = '1'";
				$rs_2 = $dbconn->Execute($strSQL_2);
				if ($rs_2->RowCount() > 0) {
					$data["have_agents"] = 1;
				}
				
				$smarty->assign("profile", $profile);	
				$smarty->assign("data", $data);		
			}
			
			$data["agency_approve"] = -1;
			if ($data["user_type"] == 3) {
				
				$strSQL = "SELECT aoc.id, aoc.id_agent, aoc.id_company, aoc.approve, rd.company_name, rd.company_url, rd.logo_path, rd.admin_approve, rd.address, rd.weekday_str, rd.work_time_begin, rd.work_time_end, rd.lunch_time_begin, rd.lunch_time_end, ct.name as country_name, rt.name as region_name, cit.name as city_name, cit.lon, cit.lat, rd.id_country,  u.phone 
									FROM ".AGENT_OF_COMPANY_TABLE." aoc 
									LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user 
									LEFT JOIN ".USERS_TABLE." u ON aoc.id_company = u.id 
									LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=rd.id_country
									LEFT JOIN ".REGION_TABLE." rt ON rt.id=rd.id_region
									LEFT JOIN ".CITY_TABLE." cit ON cit.id=rd.id_city
									WHERE id_agent = '1' AND (aoc.inviter = 'agent' OR (aoc.inviter = 'company' AND aoc.approve = '1')) ORDER BY aoc.id DESC LIMIT 1";
				
				$rs = $dbconn->Execute($strSQL);				
				
				if ($rs->fields[0] > 0) {
					$row = $rs -> GetRowAssoc(false);
					$data["agency_name"] = $row["company_name"];
										
					if ($row["company_url"] != "" && (strpos("http://", $row["company_url"]) == 0)) {
						$data["agency_url"] = "http://".$row["company_url"]."/";
					}else{
						$data["agency_url"] = $row["company_url"];
					}
					
					$data["id_agency"] = $row["id_company"];					
					$data["agency_approve"] = $row["approve"];	
					$data["logo_approve"] = $row["logo_approve"];		
					if ($row["logo_path"]) {					
						$data["agency_logo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$row["logo_path"];
					}
					$data["agency_phone"] = $row["phone"];		
					$data["country_name"] = $row["country_name"];
					if ($data["country_name"] != '') {
						$data["in_base"] = 1;
					}else{
						$data["in_base"] = 0;
					}
					$data["id_country"] = $row["id_country"];
					$data["region_name"] = $row["region_name"];		
					$data["city_name"] = $row["city_name"];		
					$data["ag_address"] = $row["address"];
					
					$data["weekday_str"] = stripslashes($row["weekday_str"]);
					if ($data["weekday_str"] != "") {
						$data["weekday_1"] = explode(",",$data["weekday_str"]);
						foreach ($data["weekday_1"] as $value) {
							$data["weekday"][$value-1] = $value;
						}
					}
					$data["work_time_begin"] = intval($row["work_time_begin"]);
					$data["work_time_end"] = intval($row["work_time_end"]);
					$data["lunch_time_begin"] = intval($row["lunch_time_begin"]);
					$data["lunch_time_end"] = intval($row["lunch_time_end"]);
					
					$use_maps_in_viewprofile = GetSiteSettings("use_maps_in_viewprofile");
					$smarty->assign("use_maps_in_viewprofile", $use_maps_in_viewprofile);
					$smarty->assign("map",GetMapSettings());		
					
					$profile["country_name"] = $data["country_name"];
					$profile["region_name"] = $data["region_name"];
					$profile["city_name"] = $data["city_name"];
					$profile["addres"] = $data["ag_address"];
					$profile["lon"] = $row["lon"];
					$profile["lat"] = $row["lat"];
					$smarty->assign("profile",$profile);
					
				}				
				$rs_2 = $dbconn->Execute("SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='1'");
				if ($rs_2->NumRows() > 0) {
				$data["photo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_2->fields[0];
				$data["admin_approve"] = $rs_2->fields[1];
				$data["use_photo_approve"] = GetSiteSettings("use_photo_approve");
				}
				
			}
			
			GetLocationContent($data["id_country"], $data["id_region"]);
			$smarty->assign("day", GetDaySelect($data["birth_day"]));
			$smarty->assign("month", GetMonthSelect($data["birth_month"]));
			$smarty->assign("year", GetYearSelect($data["birth_year"], 80, (intval(date("Y"))-18)));			

			$strSQL = "SELECT id_subscribe FROM ".SUBSCRIBE_USER_TABLE." WHERE id_user='1'";
			$rs = $dbconn->Execute ($strSQL);
			$i = 0;
			$alerts_sel = array();
			while (!$rs->EOF) {
				$alerts_sel[$i] = $rs->fields[0];
				$rs->MoveNext();
				$i++;
			}

			$alerts = GetAlertsName();
			$strSQL = "SELECT id FROM ".SUBSCRIBE_SYSTEM_TABLE." WHERE status='1'";
			$rs = $dbconn->Execute ($strSQL);
			while (!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$alerts_st[$row["id"]] = $row["id"];
				$rs->MoveNext();
			}
			$i = 0;
			foreach ($alerts as $arr) {
				if (in_array($arr["id"],$alerts_st)) {
					$alerts_vis[$i]["id"] = $arr["id"];
					$alerts_vis[$i]["name"] = $arr["name"];
					if (in_array($alerts_vis[$i]["id"], $alerts_sel)) {
						$alerts_vis[$i]["sel"] = 1;
					}
					$i++;
				}
			}
			$smarty->assign("alerts", $alerts_vis);

			/**
	 		* справочники с характеристикой человека
	 		* для информации профайла в таблице $arr["spr_user_table"] - $id_ad=0
	 		*/
			$used_references = array("gender", "people", "language");

			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$data[$arr["key"]] = SprTableSelectAdmin($arr["spr_user_table"], 0, 1, $arr["spr_table"]);
					$smarty->assign($arr["key"], GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, ''));
				}
			}

			break;
			case "misc":
			$settings = GetSiteSettings(
				array(	'index_theme_path','admin_theme_path',
						'index_theme_css_path', 'index_theme_images_path', 'menu_path',
						'admin_rows_per_page', 'date_format', 'users_num_page', 'reference_numpage', 'news_per_page',
						'max_age_limit', 'min_age_limit' , 'ads_num_page', 'max_ads_admin', 'admin_user_ads_numpage',
						'thumb_max_width','thumb_max_height','use_registration_confirmation',
						'use_photo_approve', 'use_video_approve', 'photo_folder', 'photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height', 'default_photo','plan_photo_max_user_count',
						'default_photo_male', 'default_photo_female', 'default_photo_fwithc', 'default_photo_fwithoutc', 'default_photo_agency', 'default_photo_man',
						'video_max_size', 'video_max_count',
						'use_private_person_ads_limit', 'private_person_ads_limit',
						'slideshow_folder', 'slideshow_max_size','sq_meters',
						'use_image_resize', 'contact_for_free', 'contact_for_unreg',
						'thousands_separator', 'use_sold_leased_status', 'headline_preview_size',
						'use_ads_activity_period', 'ads_activity_period', 'use_ffmpeg', 'path_to_ffmpeg', 'flv_output_dimension','flv_output_audio_sampling_rate' ,'flv_output_audio_bit_rate','flv_output_foto_dimension',
						'cur_position', 'cur_format', 'use_agent_user_type'					
						));
			$data = $settings;
			$data["index_theme_path"] = stripslashes($settings["index_theme_path"]);
			$data["admin_theme_path"] = stripslashes($settings["admin_theme_path"]);
			$data["index_theme_css_path"] = stripslashes($settings["index_theme_css_path"]);
			$data["index_theme_images_path"] = stripslashes($settings["index_theme_images_path"]);
			$data["menu_path"] = stripslashes($settings["menu_path"]);
			$data["date_format"] = stripslashes($settings["date_format"]);
			//upload settings
			$data["photo_max_size"] = round($settings["photo_max_size"]/1024);
			$data["video_max_size"] = round($settings["video_max_size"]/1024);
			$data["video_max_count"] = intval($settings["video_max_count"]);
			$data["photo_folder"] = stripslashes($settings["photo_folder"]);
			$data["default_photo"] = stripslashes($settings["default_photo"]);
			//$data["default_photo_group"] = stripslashes($settings["default_photo_group"]);//never can be used
			$data["default_photo_male"] = stripslashes($settings["default_photo_male"]);			
			$data["default_photo_female"] = stripslashes($settings["default_photo_female"]);
			$data["default_photo_fwithc"] = stripslashes($settings["default_photo_fwithc"]);
			$data["default_photo_fwithoutc"] = stripslashes($settings["default_photo_fwithoutc"]);
			$data["default_photo_agency"] = stripslashes($settings["default_photo_agency"]);
			$data["default_photo_man"] = stripslashes($settings["default_photo_man"]);
			$data["slideshow_max_size"] = round($settings["slideshow_max_size"]/1024);
			$data["slideshow_folder"] = stripslashes($settings["slideshow_folder"]);
			$data["use_registration_confirmation"] = $settings["use_registration_confirmation"];
			$data["thousands_separator"] = $settings["thousands_separator"];
			$data["use_sold_leased_status"] = $settings["use_sold_leased_status"];
			$data["headline_preview_size"] = $settings["headline_preview_size"];
			$data["use_ads_activity_period"] = $settings["use_ads_activity_period"];
			$data["ads_activity_period"] = $settings["ads_activity_period"];
			$data["path_to_ffmpeg"] = htmlspecialchars($settings["path_to_ffmpeg"], ENT_QUOTES, 'UTF-8');
			$data["flv_output_dimension"] = htmlspecialchars($settings["flv_output_dimension"], ENT_QUOTES, 'UTF-8');
			$data["flv_output_audio_sampling_rate"] = htmlspecialchars($settings["flv_output_audio_sampling_rate"], ENT_QUOTES, 'UTF-8');
			$data["flv_output_audio_bit_rate"] = htmlspecialchars($settings["flv_output_audio_bit_rate"], ENT_QUOTES, 'UTF-8');
			$data["flv_output_foto_dimension"] = htmlspecialchars($settings["flv_output_foto_dimension"], ENT_QUOTES, 'UTF-8');
			$cur = GetSiteSettings('site_unit_costunit');
			
			$strSQL_sub = " SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='$cur' ";
			$rs_sub = $dbconn->Execute($strSQL_sub);
			$cur_symbol = $rs_sub->fields[0];
			$smarty->assign("cur_symbol", $cur_symbol);
			
			switch ($data["thousands_separator"]) {
				case "nbsp": $example = "2 000 000";break;
				case ",": $example = "2,000,000";break;
				case "empty": $example = "2000000";break;
			}
			switch ($data["cur_format"]) {
				case "abbr":
					$cur_show = $cur;
					$space = "&nbsp;";							
				break;
				case "symbol":
					$cur_show = $cur_symbol;
					$space = "";
				break;				
			}
			switch ($data["cur_position"]) {
				case "begin":
					$example = $cur_show.$space.$example;
					break;		
				case "end":
					$example = $example."&nbsp;".$cur_show;
					break;			
			}
			$theme_array = ScanTemplateFolder($config["site_path"]."/templates");
			
			$smarty->assign("theme_array", $theme_array);
			$smarty->assign("example", $example);

			break;
		case "icons":
			//icons settings
			
			$settings = GetSiteSettings(array('thumb_max_width','thumb_max_height', 'icons_folder'));
			$data = $settings;
			$strSQL = "SELECT id, file_path, status FROM ".ICONS_TABLE." ORDER by id";
			$rs = $dbconn->Execute($strSQL);

			if($rs->fields[0]>0) {
				$smarty->assign("no_icons", 0);
				$i = 0;
				while (!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$icons[$i]["id"] = $row["id"];
					$icons[$i]["file_path"] = $row["file_path"];
					$icons[$i]["status"] = $row["status"];
					$icons[$i]["del_link"] = "./admin_settings.php?sel=del_icon&id_icon=".$icons[$i]["id"];
					$i++;
					$rs->MoveNext();
				}
			$smarty->assign("icons", $icons);
			} else {
				$smarty->assign("no_icons", 1);
			}

			break;
		case "langedit": //// languages
			$default_lang = GetSiteSettings('default_lang');
			$rs = $dbconn->Execute("SELECT name, visible, id AS value FROM ".LANGUAGE_TABLE);
			$i = 0;
			while (!$rs->EOF) {
				$language[$i] = $rs->GetRowAssoc(false);
				if($language[$i]["value"] == $default_lang) {
					$language[$i]["sel"] = "1";
				} else {
					$language[$i]["sel"] = "";
				}
				$rs->MoveNext();
				$i++;
			}
			$smarty->assign("language", $language);

			$data["langfile_link"] = $file_name."?sel=langedit";
			break;
		case "langeditfile": 
			/**
			 * Language files editing
			 */
			$data["langfile_link"] = $file_name."?sel=langedit";
			//array(folder=>lang_string)
			$lang_parts = array("menu" => "edit_part_menu", "" => "edit_part_user", "admin" => "edit_part_admin");

			if (GetSiteSettings("use_pilot_module_mortgage")) {
				$mortgage_lang_parts = array("mortgage" => "edit_part_mortgage", "admin/mortgage" => "edit_part_admin_mortgage");
				$lang_parts = array_merge($lang_parts, $mortgage_lang_parts);
			}	
			
			$smarty->assign("parts", $lang_parts);
			break;
		case "countries":
				$strSQL = "SELECT value FROM ".SETTINGS_TABLE." WHERE name='one_country'";
				$rs = $dbconn->Execute($strSQL);
				$data["one_country"] = $rs->fields[0];
				if ($data["one_country"]=='1') {
					$strSQL = "SELECT value FROM ".SETTINGS_TABLE." WHERE name='id_country'";
					$rs = $dbconn->Execute($strSQL);
					$data["id_country"] = $rs->fields[0];
				}
				$strSQL = "SELECT DISTINCT id, name FROM ".COUNTRY_TABLE." GROUP BY id";
				$rs = $dbconn->Execute($strSQL);
				if ($rs->fields[0]>0) {
					$i = 0;
					while (!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						if (isset($data["id_country"]) && $data["id_country"] == $row["id"]) {
							$countries[$i]["sel"] = 1;
						}
						$countries[$i]["id"] = $row["id"];
						$countries[$i]["name"] = $row["name"];
						$rs->MoveNext();
						$i++;
					}
					$smarty->assign("countries", $countries);
				}
			break;
		case "site_mode":			
				$lang["hide_ids"] = GetLangContent("sitemode_hide_elems");
			
				$strSQL = "SELECT value FROM ".SETTINGS_TABLE." WHERE name='site_mode'";
				$rs = $dbconn->Execute($strSQL);
				$site_mode = $rs->fields[0];
				
				$mode_ids = array();				
				$strSQL = "SELECT id, id_elem FROM ".MODE_IDS_TABLE;
				$rs = $dbconn->Execute($strSQL);
				while (!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$mode_ids[$row["id"]] = $lang["hide_ids"][$row["id_elem"]];
					$rs->MoveNext();					
				}
				$smarty->assign("mode_ids", $mode_ids);
							
				$strSQL = "SELECT id, descr FROM ".MODES_TABLE;
				$rs = $dbconn->Execute($strSQL);
				$modes = array();
				while (!$rs->EOF) {
					$modes[] = $rs->getRowAssoc(false);
					$rs->MoveNext();
				}								
				foreach ($modes as $mode_key=>$mode) {
					$hide_ids = array();
					$strSQL = "SELECT mi.id FROM ".MODE_IDS_TABLE." mi ".
							  "LEFT JOIN ".MODE_HIDE_IDS_TABLE." m ON mi.id=m.id_elem ".
							  "WHERE m.id_mode='".$mode["id"]."'";
					$rs = $dbconn->Execute($strSQL);
					while (!$rs->EOF) {
						$hide_ids[] = $rs->fields[0];
						$rs->MoveNext();
					}
					$sm_hide_ids = array();
					foreach ($mode_ids as $elem_id=>$elem_descr) {
						$sm_hide_ids[$elem_id] = (in_array($elem_id, $hide_ids)) ? 1 : 0; 
					}					
					$modes[$mode_key]["elem_ids"] = $sm_hide_ids;
				}							
				$smarty->assign("modes", $modes);				
				$smarty->assign("site_mode", $site_mode);				
			break;
		case "maps":
				$data = GetSiteSettings(array('use_maps_in_viewprofile','use_maps_in_search_results','use_maps_in_account'));

				/**
				 * @todo mapquest now have only beta version, so we not use it in release
				 * For MapQuest usage execute:
				 * INSERT INTO [db_prefix]maps VALUES (4, 'mapquest', 'mjtd%7Clu6y296b2q%2C8g%3Do5-0uawg', '0');
				 **/
				$strSQL = "SELECT id, name, app_id, used FROM ".MAPS_TABLE;
				$rs = $dbconn->Execute($strSQL);

				while (!$rs->EOF) {
					$maps[] = $rs->getRowAssoc( false );
					$rs->MoveNext();
				}
				$smarty->assign("maps", $maps);
			break;
		case "server_errors":
				$settings_manager = new SettingsManager();
				$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? intval($_REQUEST["language_id"]) : $config["default_lang"];
				$langs = GetActiveLanguages();
				$smarty->assign("langs", $langs );
				$smarty->assign("langs_cnt", count($langs) );
				$smarty->assign("current_lang_id", $current_lang_id );
				$smarty->assign("errors", $settings_manager->GetErrorsList( $current_lang_id ));
			break;
		case "metatags":
				$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? intval($_REQUEST["language_id"]) : $config["default_lang"];

				$smarty->assign("current_lang_id", $current_lang_id );
				$smarty->assign("pages", GetMetatagsList("metatags", $current_lang_id));
			break;
		case "watermark":
				$settings_manager = new SettingsManager();
				$settings = (array("use_watermark", "watermark_width", "watermark_height", "watermark_type", "watermark_text", "watermark_image", "watermark_blank", "photo_folder"));

				$settings_arr = $settings_manager->GetSiteSettings($settings);
				$smarty->assign("settings", $settings_arr);

				$smarty->assign("fonts", $fonts);
				$smarty->assign("fonts_size", $fonts_size);
				//$smarty->assign("cur_font", $_POST["font-face"]);
				//$smarty->assign("cur_font_size", $_POST["font-size"]);
				$smarty->assign("own_fonts", $settings_manager->GetFilesName($config["site_path"]."/include/fonts/" , "ttf"));
			break;
		case "logotype":
				$settings_manager = new SettingsManager();
				$settings = (array("index_theme_path", "index_theme_images_path"));
				$settings = $settings_manager->GetSiteSettings($settings);

				$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? intval($_REQUEST["language_id"]) : $config["default_lang"];
				$langs = GetActiveLanguages();
				$smarty->assign("langs", $langs );
				$smarty->assign("langs_cnt", count($langs) );
				$smarty->assign("current_lang_id", $current_lang_id );
				$settings["photo_folder"] = $settings["index_theme_path"].$settings["index_theme_images_path"]."/".LangNameById($current_lang_id);
				$smarty->assign("logo_settings", $settings_manager->GetLogoSettings( $current_lang_id ));
				$smarty->assign("settings", $settings);

			break;

		default:
			break;
	}

	$data["section"] = $section;

	if ($err ) {
		GetErrors($err);
		if (!(isset($_REQUEST["success"]) && $_REQUEST["success"] == 1) && ($err != "success_save")) {
			$data = $_POST;
			if (isset($settings)){				
				$data["default_photo_male"] = stripslashes($settings["default_photo_male"]);			
				$data["default_photo_female"] = stripslashes($settings["default_photo_female"]);
				$data["default_photo_fwithc"] = stripslashes($settings["default_photo_fwithc"]);
				$data["default_photo_fwithoutc"] = stripslashes($settings["default_photo_fwithoutc"]);
				$data["default_photo_agency"] = stripslashes($settings["default_photo_agency"]);
				$data["default_photo_man"] = stripslashes($settings["default_photo_man"]);
			}
		}
	}
	$smarty->assign("data", $data);
	$smarty->assign("err_id", $err_id);
	$smarty->assign("script", "div_actions");
	$smarty->assign("file_name", $file_name);
	$smarty->assign("file_virt_name", $file_virt_name);
	$smarty->assign("add_to_lang", "&section=$section");
			
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_settings.tpl");
	exit;
}
function SaveSettingsMisc() {
	global $smarty, $dbconn, $config;
	$not_settings = array('sel', 'ajax', 'sq_meters');
	$flag_ads_activity = 0;
	$error = "";
	foreach ($_REQUEST AS $key=>$item){
		switch ($key){
			case "photo_max_size":
			case "video_max_size":
			case "slideshow_max_size":	
				$item = $item*1024;
			break;	
			
			case "use_photo_approve":
				if (!$item && (GetSiteSettings('use_photo_approve') == 1)){					
					$strSQL = "UPDATE ".USERS_RENT_UPLOADS_TABLE." SET admin_approve='1' WHERE admin_approve='0'";
					$dbconn->Execute($strSQL);
					$strSQL = "UPDATE ".USER_RENT_PLAN_TABLE." SET admin_approve='1' WHERE admin_approve='0'";
					$dbconn->Execute($strSQL);
					$strSQL = "UPDATE ".USER_REG_DATA_TABLE." SET admin_approve='1' WHERE admin_approve='0'";
					$dbconn->Execute($strSQL);
				}
			break;
			case "use_video_approve":
				if (!$item && (GetSiteSettings('use_video_approve') == 1)){					
					$strSQL = "UPDATE ".USERS_RENT_UPLOADS_TABLE." SET admin_approve='1' WHERE admin_approve='0'";
					$dbconn->Execute($strSQL);
				}
			break;	
			case "use_ads_activity_period":
				if ($item && (GetSiteSettings('use_ads_activity_period') == 0)){
					$flag_ads_activity = 1;
				}
			break;	
			case "use_sold_leased_status":
				if (!$item && (GetSiteSettings('use_sold_leased_status') == 1)){
					UnactivateSoldLeased();
				}
			break;
			case "use_agent_user_type":
				if (!$item && (GetSiteSettings('use_agent_user_type') == 1)){
					ConvertUsersToPrivateUser();
				}
			break;			
		}
		if (!in_array($key, $not_settings)){
			$dbconn->Execute("UPDATE ".SETTINGS_TABLE." SET value='".addslashes(strip_tags($item))."' WHERE name='$key'");
		}
		if ($key == "sq_meters"){
			$dbconn->Execute("UPDATE ".SETTINGS_TABLE." SET value='".addslashes($item)."' WHERE name='$key'");
		}
	}
	if ($flag_ads_activity){
		$date_unactive = date("Y-m-d H:i:s", time()+60*60*24*GetSiteSettings("ads_activity_period"));
		$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET date_unactive='$date_unactive' WHERE status='1'");
	}
	
	$ajax = isset($_REQUEST["ajax"]) ? intval($_REQUEST["ajax"]) : 0;
	if ($ajax){		
		echo "saved";		
	}else{
		ListSettings("misc");
		return;
	}
}

function SaveSettings($section) {
	global $smarty, $dbconn, $config;

	$err_id = array();	
	
	switch($section) {
		case "countries":
			$one_country = (isset($_POST["one_country"]) && !empty($_POST["one_country"])) ? intval($_POST["one_country"]) : 0;
			$id_country = (isset($_POST["country"]) && !empty($_POST["country"])) ? intval($_POST["country"]) : 0;
			if ( (strlen($id_country)<1) && ($one_country==1) ) {
				$err = "empty_fields";
			} else {
				$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$one_country."' WHERE name='one_country'";
				$dbconn->Execute($strSQL);
				$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$id_country."' WHERE name='id_country'";
				$dbconn->Execute($strSQL);
			}
		break;
		case "misc":
		    $default_photo_male = $_FILES["default_photo_male"];
			if (strlen($default_photo_male["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($default_photo_male, 'f', "default_photo_male");
			}
			$default_photo_female = $_FILES["default_photo_female"];
			if (strlen($default_photo_female["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($default_photo_female, 'f', "default_photo_female");
			}
			$default_photo_fwithc = $_FILES["default_photo_fwithc"];
			if (strlen($default_photo_fwithc["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($default_photo_fwithc, 'f', "default_photo_fwithc");
			}
			$default_photo_fwithoutc = $_FILES["default_photo_fwithoutc"];
			if (strlen($default_photo_fwithoutc["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($default_photo_fwithoutc, 'f', "default_photo_fwithoutc");
			}
			$default_photo_agency = $_FILES["default_photo_agency"];
			if (strlen($default_photo_agency["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($default_photo_agency, 'f', "default_photo_agency");
			}
			$default_photo_man = $_FILES["default_photo_man"];
			if (strlen($default_photo_man["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($default_photo_man, 'f', "default_photo_man");
			}

			break;
		case "pass_change":
			$password = $_POST["password"];
			$repassword = $_POST["repassword"];
			$oldpassword = $_POST["oldpassword"];
			$strSQL = "SELECT password FROM ".USERS_TABLE." WHERE root_user='1'";
			$rs = $dbconn->Execute($strSQL);
			$old_pass = $rs->fields[0];
	
			if ( (strlen($password)<1) || (strlen($repassword)<1) || (strlen($oldpassword)<1) ) {
				$err = "empty_fields";
			} elseif ($password != $repassword) {
				$err = "error_repass";
			} elseif (md5($oldpassword) != $old_pass) {
				$err = "error_oldpass";
			} else {
				$strSQL = "UPDATE ".USERS_TABLE." SET password='".md5($password)."' WHERE root_user='1'";
				$dbconn->Execute($strSQL);
				$err = "admin_pass_changed";
			}
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_settings.php?section=$section&err=$err&success=1");
			exit();
		break;
		case "admin":
			//administrator info settings
			$site_email = $_POST["site_email"];
			if (strlen($site_email)<1) {
				$err = "empty_fields";
			} else {
				$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$site_email."' WHERE name='site_email'";
				$dbconn->Execute($strSQL);
			}
			SaveProfile();
		break;

		case "icons":
			$icons_folder = $_POST["icons_folder"];
			$icons = $_POST["icons_status"];
			if (strlen($icons_folder)<1) {
				$err = "empty_fields";
			} else {
				$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".addslashes($icons_folder)."' WHERE name='icons_folder'";
				$dbconn->Execute($strSQL);
				$strSQL = "UPDATE ".ICONS_TABLE." SET status='0'";
				$dbconn->Execute($strSQL);
				if (is_array($icons)) {
					foreach($icons as $key => $val) {
						$strSQL = "UPDATE ".ICONS_TABLE." SET status='1' where id='".$key."'";
						$dbconn->Execute($strSQL);
					}
				}
			}
		break;
		case "langedit":
			$par = (isset($_REQUEST["par"]) && !empty($_REQUEST["par"])) ? $_REQUEST["par"] : "";
			if ($par == "lang_save") {
				/**
				 * check if at least one language is active
				 */
				$visible = $_POST["visible"];
				if (!(is_array($visible) && count($visible)>0)) {
					ListSettings('langedit', "one_lang_active");
				}

				//default language
				$def_lang_name = $_POST["def_l"];
				$rs = $dbconn->Execute("select id from ".LANGUAGE_TABLE." where name='".$_POST["def_l"]."'");
				$def_lang_code = $rs->fields[0];

				$dbconn->Execute("Update ".LANGUAGE_TABLE." set visible='0' ");

				$i=0;
				foreach($visible as $k=>$v) {
					$dbconn->Execute("Update ".LANGUAGE_TABLE." set visible='1' where id='".intval($v)."'");
					if ($i == 0) {
						$fkey = $k;
					}
					$i++;
				}
				if (!in_array($def_lang_code, array_values($visible))) {
					$def_lang_code = $visible[$fkey];
					$rs = $dbconn->Execute("select name from ".LANGUAGE_TABLE." where id='".$def_lang_code."'");
					$def_lang_name = $rs->fields[0];
				}

				$dbconn->Execute("Update ".SETTINGS_TABLE." set value='".$def_lang_code."' where name='default_lang'");

				ListSettings('langedit', "lang_saved");
				exit;
			}
			if ($par == "lang_add") {
				$new_lang["name"] = trim($_REQUEST["name"]);
				/**
				 * Check new language name
				 */
				if (!$new_lang["name"]) {
					ListSettings('langedit', "lang_empty_parameters");
					return;
				}
				if (!(preg_match("/^([a-zA-Z])*$/", $new_lang["name"]))) {
					ListSettings('langedit', "incorrect_language_name");
					return;
				}
				$rs = $dbconn->Execute("SELECT id FROM ".LANGUAGE_TABLE." WHERE name='".$new_lang["name"]."'");
				if ($rs->RowCount() > 0) {
					ListSettings('langedit', "lang_exists");
					return;
				}

				/**
				 * Check base language
				 */
				$base_lang["id"] = intval($_REQUEST["base_lang"]);
				$rs = $dbconn->Execute("SELECT name FROM ".LANGUAGE_TABLE." WHERE id='{$base_lang["id"]}'");
				if ($rs->RowCount() == 0) {
					ListSettings('langedit', "not_set_base_lang");
					return;
				}
				$base_lang["name"] = $rs->fields[0];

				/**
				 * Save Language
				 */
				$q = $dbconn->Execute("INSERT INTO ".LANGUAGE_TABLE." (charset, lang_path, name, visible) VALUES ('UTF-8', '/lang/".$new_lang["name"]."/', '".$new_lang["name"]."', '0')");
				$new_lang["id"] = $dbconn->Insert_ID();
				if ($q) {
					$dir_dest = $config["site_path"]."/lang/".$new_lang["name"];
					$dir_src = $config["site_path"]."/lang/".$base_lang["name"];
					dircpy($dir_src,$dir_dest);
					scan_dir_file($dir_dest);

					$dir_dest = $config["site_path"].$config["index_theme_path"]."/images/".$new_lang["name"];
					$dir_src = $config["site_path"].$config["index_theme_path"]."/images/".$base_lang["name"];
					dircpy($dir_src,$dir_dest);
					scan_dir_file($dir_dest);

					AddLangString($config["site_path"]."/lang/", 'menu/lang_menu.xml', $new_lang["name"], $new_lang["name"], "item", array("id_lang"=>$new_lang["id"]));
					AddLangString($config["site_path"]."/lang/", 'menu/admin_lang_menu.xml', $new_lang["name"], $new_lang["name"], "item", array("id_lang"=>$new_lang["id"]));

					$dbconn->Execute("ALTER TABLE ".REFERENCE_LANG_TABLE." ADD COLUMN lang_".$new_lang["id"]."_1 varchar(255) NOT NULL default ''");
					$dbconn->Execute("ALTER TABLE ".REFERENCE_LANG_TABLE." ADD COLUMN lang_".$new_lang["id"]."_2 varchar(255) NOT NULL default ''");
					$dbconn->Execute("UPDATE ".REFERENCE_LANG_TABLE." SET lang_".$new_lang["id"]."_1=lang_".$base_lang["id"]."_1  ");
					$dbconn->Execute("UPDATE ".REFERENCE_LANG_TABLE." SET lang_".$new_lang["id"]."_2=lang_".$base_lang["id"]."_2  ");
					$dbconn->Execute("ALTER TABLE ".SERVER_ERRORS_TABLE." ADD COLUMN message_".$new_lang["id"]." blob ");
					$dbconn->Execute("UPDATE ".SERVER_ERRORS_TABLE." SET message_".$new_lang["id"]."=message_".$base_lang["id"]);

					$dbconn->Execute("ALTER TABLE ".LOGO_SETTINGS_TABLE." ADD COLUMN pic_".$new_lang["id"]." varchar ( 255 ) ");
					$dbconn->Execute("ALTER TABLE ".LOGO_SETTINGS_TABLE." ADD COLUMN alt_".$new_lang["id"]." tinyblob ");
					$dbconn->Execute("UPDATE ".LOGO_SETTINGS_TABLE." SET pic_".$new_lang["id"]."=pic_".$base_lang["id"]);
					$dbconn->Execute("UPDATE ".LOGO_SETTINGS_TABLE." SET alt_".$new_lang["id"]."=alt_".$base_lang["id"]);
					
					if (GetSiteSettings("use_pilot_module_mortgage")) {
						/**
						 * copy all mortgage content pages from base language to new language
						 */
						$strSQL = "SELECT id_page, page_name, content ".
								  "FROM ".MORTGAGE_PAGE_CONTENT_TABLE." ".
								  "WHERE id_lang='".$base_lang["id"]."'";
						$rs = $dbconn->Execute($strSQL);								  
						while (!$rs->EOF) {
							$row = $rs->GetRowAssoc(false);							
							$dbconn->Execute("INSERT INTO ".MORTGAGE_PAGE_CONTENT_TABLE." SET ".
							"id_page='".$row["id_page"]."', page_name='".addslashes($row["page_name"])."', ".
							"content='".addslashes($row["content"])."', id_lang='".$new_lang["id"]."'");
							$rs->MoveNext();
						}						
					}					
					header("Location: ".$config["server"].$config["site_root"]."/admin/admin_settings.php?section=langedit&err=lang_added&success=1");
					exit();
				}
			}
			if ($par == "lang_delete") {
				$lang_id = (isset($_REQUEST["lang_id"]) && !empty($_REQUEST["lang_id"])) ? intval($_REQUEST["lang_id"]) : 0;
				if ($lang_id) {
					$rs = $dbconn->Execute("SELECT COUNT(id) AS cnt FROM ".LANGUAGE_TABLE);
					if ($rs->fields[0] < 2) {
						ListSettings('langedit', "could_not_del_last_lang");
						return;
					}
					/**
					 * Delete Language
					 */
					$rs = $dbconn->Execute("SELECT name FROM ".LANGUAGE_TABLE." WHERE id='$lang_id'");
					$lang_name = $rs->fields[0];
					if ($lang_name) {
						DeleteDir($config["site_path"]."/lang/".$lang_name."/");
						DeleteDir($config["site_path"].$config["index_theme_path"]."/images/".$lang_name."/");

						DeleteLangString($config["site_path"]."/lang/", 'menu/lang_menu.xml', $lang_name);
						DeleteLangString($config["site_path"]."/lang/", 'menu/admin_lang_menu.xml', $lang_name);

						$dbconn->Execute("DELETE FROM ".LANGUAGE_TABLE." WHERE id='$lang_id'");

						$dbconn->Execute("ALTER TABLE ".REFERENCE_LANG_TABLE." DROP COLUMN lang_".$lang_id."_1");
						$dbconn->Execute("ALTER TABLE ".REFERENCE_LANG_TABLE." DROP COLUMN lang_".$lang_id."_2");
						$dbconn->Execute("ALTER TABLE ".SERVER_ERRORS_TABLE." DROP COLUMN message_".$lang_id);
						$dbconn->Execute("ALTER TABLE ".LOGO_SETTINGS_TABLE." DROP COLUMN pic_".$lang_id);
						$dbconn->Execute("ALTER TABLE ".LOGO_SETTINGS_TABLE." DROP COLUMN alt_".$lang_id);

						if (GetSiteSettings("use_pilot_module_mortgage")) {
							$dbconn->Execute("DELETE FROM ".MORTGAGE_PAGE_CONTENT_TABLE." WHERE id_lang='$lang_id'");		
						}		
					
						/**
						 * Set default language if deleted language was default
						 */
						$def_lang_id = GetSiteSettings("default_lang");
						$rs = $dbconn->Execute("SELECT name FROM ".LANGUAGE_TABLE." WHERE id='$def_lang_id'");
						if ($rs->RowCount() == 0) {
							$rs = $dbconn->Execute("SELECT MIN(id) AS id FROM ".LANGUAGE_TABLE." WHERE visible='1'");
							if ($rs->fields[0] > 0) {
								$def_lang_id = $rs->fields[0];
							} else {
								$rs = $dbconn->Execute("SELECT MIN(id) AS id FROM ".LANGUAGE_TABLE);
								$def_lang_id = $rs->fields[0];

								$rs = $dbconn->Execute("UPDATE ".LANGUAGE_TABLE." SET visible='1' WHERE id='$def_lang_id'");
							}
							$dbconn->Execute("UPDATE ".SETTINGS_TABLE." SET value='".$def_lang_id."' WHERE name='default_lang'");
						}
						header("Location: ".$config["server"].$config["site_root"]."/admin/admin_settings.php?section=langedit&err=lang_deleted&success=1");
						exit();
					}
				}
			}
		break;
		case "site_mode":			
			$site_mode = intval($_REQUEST["site_mode"]);
			if ($site_mode > 0) {
				$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$site_mode."' WHERE name='site_mode'";
				$dbconn->Execute($strSQL);				
				if ($site_mode == 3) {					
					if (isset($_REQUEST["smode_ids"]) && isset($_REQUEST["smode_ids"][$site_mode])) {
						$operation_types = array("mhi_ad_sell", "mhi_ad_rent", "mhi_ad_buy", "mhi_ad_lease");				
						$strSQL = "SELECT id FROM ".MODE_IDS_TABLE." WHERE id_elem IN ('".implode("', '", $operation_types)."')";
						$rs = $dbconn->Execute($strSQL);
						
						$operation_ids = array();
						while (!$rs->EOF) {
							$operation_ids[] = $rs->fields[0];
							$rs->MoveNext();
						}
						
						$hide_ids = array();						
						foreach ($_REQUEST["smode_ids"][$site_mode] as $hide_id) {
							$hide_ids[] = intval($hide_id);
						}						
						
						$at_least_one = 0;
						foreach ($operation_ids as $hid) {
							if (!in_array($hid, $hide_ids)) {
								$at_least_one = 1;
								break;
							}
						}
						
						if ($at_least_one) {
							$dbconn->Execute("DELETE FROM ".MODE_HIDE_IDS_TABLE." WHERE id_mode='$site_mode'");				
							$strSQL = "INSERT INTO ".MODE_HIDE_IDS_TABLE." (id_mode, id_elem) VALUES ('$site_mode', ".implode("), ('$site_mode', ", $hide_ids).")";						
							$dbconn->Execute($strSQL);
						} else {
							ListSettings("site_mode", "at_least_one_operation_type");
							return;
						}
					} else {
						//show all elements innn the user defined site mode
						$dbconn->Execute("DELETE FROM ".MODE_HIDE_IDS_TABLE." WHERE id_mode='$site_mode'");				
					}
				}				
			}
		break;
		case "maps":
			$use_maps_in_viewprofile = (isset($_REQUEST["use_maps_in_viewprofile"]) && !empty($_REQUEST["use_maps_in_viewprofile"])) ? intval($_REQUEST["use_maps_in_viewprofile"]) : 0;
			$use_maps_in_search_results = (isset($_REQUEST["use_maps_in_search_results"]) && !empty($_REQUEST["use_maps_in_search_results"])) ? intval($_REQUEST["use_maps_in_search_results"]) : 0;
			$use_maps_in_account = (isset($_REQUEST["use_maps_in_account"]) && !empty($_REQUEST["use_maps_in_account"])) ? intval($_REQUEST["use_maps_in_account"]) : 0;
			
			
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$use_maps_in_viewprofile."' WHERE name='use_maps_in_viewprofile'";
			$dbconn->Execute($strSQL);

			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$use_maps_in_search_results."' WHERE name='use_maps_in_search_results'";
			$dbconn->Execute($strSQL);
			
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='".$use_maps_in_account."' WHERE name='use_maps_in_account'";
			$dbconn->Execute($strSQL);

			$map_used = intval($_REQUEST["map_used"]);

			if ( $map_used > 0 ) {
				$strSQL = "UPDATE ".MAPS_TABLE." SET used='0'";
				$dbconn->Execute($strSQL);
				$strSQL = "UPDATE ".MAPS_TABLE." SET used='1' WHERE id='$map_used'";
				$dbconn->Execute($strSQL);
			}
			foreach ($_REQUEST["app_id"] as $map_id=>$app_id) {
				$app_id = addslashes(trim($app_id));
				//if ($app_id != "") {
					$strSQL = "UPDATE ".MAPS_TABLE." SET app_id='".$app_id."' WHERE id='$map_id'";
					$dbconn->Execute($strSQL);
				//}
			}
		break;
		case "server_errors":
			$settings_manager = new SettingsManager();
			$settings_manager->SaveErrorsList(intval($_REQUEST["language_id"]));
		break;
		case "metatags":
			$is_magic_quotes = intval(ini_get("magic_quotes_gpc"));
			$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? intval($_REQUEST["language_id"]) : $config["default_lang"];

			$metatags = GetMetatagsContent("metatags", LangPathById($current_lang_id));
			$new_xml_strings = array();
			foreach ($metatags as $file=>$metatag) {
				$strings = array();
				$strings[] = new XmlNode( "title", array(), null, ($is_magic_quotes) ? stripslashes($_REQUEST["title"][$file]) : $_REQUEST["title"][$file] );
				$strings[] = new XmlNode( "description", array(), null, ($is_magic_quotes) ? stripslashes($_REQUEST["description"][$file]) : $_REQUEST["description"][$file] );
				$strings[] = new XmlNode( "keywords", array(), null, ($is_magic_quotes) ? stripslashes($_REQUEST["keywords"][$file]) : $_REQUEST["keywords"][$file] );

				$new_xml_strings[] = new XmlNode( "page", array("name"=>$file), $strings );
			}
			$xml_strings = new XmlNode( "data", array(), $new_xml_strings );

			$path = $config["site_path"]."/lang/".LangNameById($current_lang_id)."/metatags.xml";

			$obj_saver = new Object2Xml(true);
			$obj_saver->Save( $xml_strings, $path);
			unset( $xml_strings, $new_xml_strings, $obj_saver);
		break;
		case "watermark":
			if ($_POST["pos"] == "preview") {
				$images_obj = new Images($dbconn);
				$watermark_path = $config["site_path"].$images_obj->settings["photo_folder"]."/";

				$blank = getimagesize($watermark_path.$images_obj->settings["watermark_blank"]);

				$images_obj->mergePix($watermark_path.$images_obj->settings["watermark_blank"],$watermark_path.$images_obj->settings["watermark_image"],$watermark_path."pre_".$images_obj->settings["watermark_blank"],3,40);

				echo "<script>function preview() { window.open('".$config["site_root"].$images_obj->settings["photo_folder"]."/pre_".$images_obj->settings["watermark_blank"]."','watermark_preview','top=10, left=10,menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0,height=".$blank[1].",width=".$blank[0]."');return false;} preview();</script>";
			} elseif ($_POST["pos"] == "save") {
				$settings_manager = new SettingsManager();
				$settings["use_watermark"] = (1 + intval($_POST["use_watermark"]))%2;
				$settings_manager->SaveSiteSettings($settings);
			} else {
				$settings_manager = new SettingsManager();
				$settings["watermark_type"] = addslashes(trim($_POST["type_watermark"]));
				switch ($settings["watermark_type"]) {
					case "text":
						$settings["watermark_text"] = addslashes(trim($_POST["text_watermark"]));
						$settings_manager->SaveSiteSettings($settings);
						if ( extension_loaded("gd") && in_array("imagettfbbox", get_extension_funcs("gd")) ) {
							$font_face = $_POST["font-face"].".ttf";
							$font_size = $_POST["font-size"];

							$images_obj = new Images($dbconn);
							$err = $images_obj->CreateWatermarkImage($settings["watermark_text"],$font_size,$font_face);
						}
					break;

					case "image":
						$settings["watermark_width"] = intval($_POST["width_watermark"]);
						$settings["watermark_height"] = intval($_POST["height_watermark"]);
				 		$settings_manager->SaveSiteSettings($settings);

						if (strlen($_FILES["image_watermark"]["name"])>0) {
							$images_obj = new Images($dbconn);
				 			$err = $images_obj->UploadDefaultImages($_FILES["image_watermark"], 'watermark', "watermark_image");
						} else {
				 			$err = "not_image";
				 		}
					break;
				}
			}

		break;
		case "logotype":
			$settings_manager = new SettingsManager();
			$settings_manager->SaveLogoSettings(intval($_REQUEST["language_id"]));
			$error_arr = array();
			foreach ($_REQUEST["type"] as $id=>$type) {
				if (strlen($_FILES[$type]["name"])>0) {
					$images_obj = new Images($dbconn);
				 	$err = $images_obj->UploadDefaultImages($_FILES[$type], 'anylogo', "", $_REQUEST["width"][$id],$_REQUEST["height"][$id], LangNameById($_REQUEST["language_id"]));
				 	if (strpos($err,"logo") != FALSE) {
				 		$new_logo_name = $err;
				 		$err = "";
				 		$settings_manager->UpdateLogo(intval($_REQUEST["language_id"]), $id, $new_logo_name,  LangNameById($_REQUEST["language_id"]));
				 	}
				 	if ($err != "") {
				 		$error_arr[$type] = GetErrors($err);				 						 					 					 			$err = "";
				 	}
				}
			}
			$smarty->assign("error_arr", $error_arr);
			$smarty->assign("error", $err);
			$error = "";

		break;
		default:
		break;
	}
	if (!isset($err) || $err == "") $err = "success_save";
	
	ListSettings($section, $err, $err_id);						
	return;
}

function UploadIcon() {
	global $smarty, $dbconn, $config;

	$icon_image = $_FILES["icon_image"];
	if (strlen($icon_image["name"])>0) {
				$images_obj = new Images($dbconn);
	 			$err = $images_obj->UploadDefaultImages($icon_image, 'icon');
	} else {
		$err = "upload_err";
	}
	ListSettings('icons', $err);
	return ;
}
function DelIcon($id_icon) {
	global $smarty, $dbconn, $config;

	$folder = GetSiteSettings("icons_folder");

	$strSQL = "SELECT file_path FROM ".ICONS_TABLE." WHERE id='".$id_icon."'";
	$rs = $dbconn->Execute($strSQL);
	$file_path = $rs->fields[0];

	unlink($config["site_path"].$folder."/".$file_path);

	$strSQL = "DELETE FROM ".ICONS_TABLE." WHERE id='".intval($id_icon)."'";
	echo $strSQL;
	$dbconn->Execute($strSQL);
	$err = "del_file";
	ListSettings('icons', $err);
	return ;
}

function dircpy($source, $dest, $overwrite = false) {
	if(!is_dir($dest))  mkdir($dest);
	if($handle = opendir($source)) {
		while (false !== ($file = readdir($handle))) {
			if($file != '.' && $file != '..') {
				$path = $source . '/' . $file;
				if(is_file($path)) {
					if(!is_file($dest . '/' . $file) || $overwrite)
					if(!@copy($path, $dest . '/' . $file)) echo '<font color="red">File ('.$path.') could not be copied, likely a permissions problem.</font>';
				} elseif(is_dir($path)) {
					dircpy($path, $dest . '/' . $file, $overwrite);
				}
			}
		}
		closedir($handle);
	}
}

function scan_dir_file($dirname) {
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false) {
		if($file != "." && $file != "..") {
			if(is_file($dirname."/".$file)) {
				chmod($dirname."/".$file, 0777);
			}
		}
	}
	closedir($dir);
	return ;
}


function UpdateMenuFiles($fname) {
	global $config, $dbconn;

	$xml_parser = new SimpleXmlParser("{$config["site_path"]}/lang/english/menu/".$fname);
	$xml_root = $xml_parser->getRoot();
	$count = $xml_root->childrenCount;

	$values = array();
	foreach ( $xml_root->children as $node) {
		switch($node->tag) {
			case "item":
				$id_lang = $node->attrs["id_lang"];
				$values[$id_lang] = htmlspecialchars( $node->value );
			break;
		}
	}

	$strSQL = " SELECT name, id, lang_path FROM ".LANGUAGE_TABLE." WHERE 1 ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while (!$rs->EOF) {
		$attrs["name"] = $rs->fields[0];
		$attrs["id_lang"] = $rs->fields[1];
		$lang_path_arr[$i] = $config["site_path"]."/lang/".$attrs["name"]."/menu/".$fname;
		if ($values[$attrs["id_lang"]]!='') {
			$str = $values[$attrs["id_lang"]];
		} else {
			$str = $attrs["name"];
		}
		$new_xml_strings[] = new XmlNode( "item", $attrs, null, $str );
		$rs->MoveNext();
		$i++;
	}
	$xml_strings = new XmlNode( "menu", array(), $new_xml_strings );
	$obj_saver = new Object2Xml(true);

	foreach ($lang_path_arr as $path) {
		$obj_saver->Save( $xml_strings, $path);
	}
	unset( $xml_strings, $new_xml_strings, $obj_saver);
}

function SaveProfile() {
	global $config, $smarty, $dbconn, $REFERENCES;

	$fname = addslashes(strip_tags(trim($_POST["fname"])));
	$sname = addslashes(strip_tags(trim($_POST["sname"])));
	$birth_year = intval(trim($_POST["birth_year"]));
	$birth_month = intval(trim($_POST["birth_month"]));
	$birth_day = intval(trim($_POST["birth_day"]));
	$lang_id = intval($_POST["lang_id"]);
	$email = addslashes(strip_tags($_POST["email"]));
	$login = addslashes(strip_tags(trim($_POST["login"])));
	$phone = addslashes(strip_tags(trim($_POST["phone"])));

	if ($birth_year<1) {
		$birthdate = sprintf("%04d-%02d-%02d", 0, 0, 0);
	} else {
		$birthdate = sprintf("%04d-%02d-%02d", $birth_year, $birth_month, $birth_day);
	}
	$user_type = intval($_POST["user_type"]);
	

	if (!($fname && $sname && $email)) {
		ListSettings("admin", "empty_fields");exit;
	}	

	$strSQL = " SELECT user_type FROM ".USERS_TABLE." WHERE root_user='1' ";
	$rs = $dbconn->Execute($strSQL);
	$old_user_type = $rs->fields[0];

	if ($user_type==2) {
		/**
		 * realtor
		 */
		$company_name = addslashes(strip_tags(trim($_POST["company_name"])));
		if ($company_name == '') {
			ListSettings("admin", "empty_fields");exit;
		}
		$company_url = addslashes(strip_tags(trim($_POST["company_url"])));
		$company_rent_count = addslashes(strip_tags(trim($_POST["company_rent_count"])));
		$company_how_know = addslashes(strip_tags(trim($_POST["company_how_know"])));
		$company_quests_comments = addslashes(strip_tags(trim($_POST["company_quests_comments"])));
		$weekday_str = implode(",",$_POST["weekday"]);
		$work_time_begin = intval($_POST["work_time_begin"]);
		$work_time_end = intval($_POST["work_time_end"]);
		$lunch_time_begin = intval($_POST["lunch_time_begin"]);
		$lunch_time_end = intval($_POST["lunch_time_end"]);
		if ($lunch_time_begin >= $work_time_begin && $lunch_time_end <= $work_time_end && $lunch_time_begin <= $lunch_time_end && $work_time_begin <= $work_time_end) {
			$err = "";
		}else{
			$err = "invalid_time";
			$work_time_begin = 0;
			$work_time_end = 0;
			$lunch_time_begin = 0;
			$lunch_time_end = 0;
		}
		$id_country = intval($_POST["country"]);
		$id_region = intval($_POST["region"]);
		$id_city = intval($_POST["city"]);
		$address = addslashes(strip_tags($_POST["address"]));
		$postal_code = addslashes(strip_tags($_POST["postal_code"]));		
		$strSQL = "SELECT id_user FROM ".USER_REG_DATA_TABLE." WHERE id_user='1' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$strSQL = " UPDATE ".USER_REG_DATA_TABLE." SET company_name='".$company_name."', company_url='".$company_url."',
						company_rent_count='".$company_rent_count."',  company_how_know='".$company_how_know."',  company_quests_comments='".$company_quests_comments."',
						weekday_str='".$weekday_str."',  work_time_begin='".$work_time_begin."',  work_time_end='".$work_time_end."',
						lunch_time_begin='".$lunch_time_begin."',  lunch_time_end='".$lunch_time_end."', id_country=".$id_country.", id_region=".$id_region.", id_city=".$id_city.", address='".$address."',postal_code='".$postal_code."'  WHERE id_user='1' ";
		} else {
			$strSQL = " INSERT INTO ".USER_REG_DATA_TABLE." (id_user, company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, id_country,id_region,id_city,address, postal_code)
						VALUES ('1', '".$company_name."', '".$company_url."', '".$company_rent_count."', '".$company_how_know."', '".$company_quests_comments."', '".$weekday_str."', '".$work_time_begin."', '".$work_time_end."', '".$lunch_time_begin."', '".$lunch_time_end."',".$id_country.",".$id_region.",".$id_city.",'".$address."','".$postal_code."' )";
		}
		$dbconn->Execute($strSQL);

		$company_logo = $_FILES["company_logo"];

		if((strlen($company_logo["name"])!=0) && (intval($company_logo["size"])!=0)) {
				$strSQL = " SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='1' ";
				$rs = $dbconn->Execute($strSQL);
				if (strlen($rs->fields[0])>0) {
					if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
						$dbconn->Execute(" UPDATE ".USER_REG_DATA_TABLE." SET logo_path='' WHERE id_user='1' ");
						unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
					}
				}
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($company_logo, 1,1);
		}
	}

	$strSQL = "UPDATE ".USERS_TABLE." SET login='".addslashes($login)."', fname='".addslashes($fname)."', sname='".addslashes($sname)."', date_birthday='".$birthdate."', lang_id='".$lang_id."', email='".$email."', phone='".addslashes($phone)."', user_type='".$user_type."' WHERE id='1'";
	$dbconn->Execute($strSQL);
	
	$rs = $dbconn->Execute("SELECT id_agent, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '1'");
	if (!$rs->fields[0] && $user_type == 3) {
		$new_company = 1;
	}
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);
		$send_about_del = "no";			
		if($old_user_type==3 && $user_type!=3) {
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
		
		if($old_user_type == 3 && $user_type == 3) {		
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
					
			$rs2 = $dbconn->Execute("SELECT aoc.id_agent, aoc.id_company, rd.company_name FROM ".AGENT_OF_COMPANY_TABLE." aoc LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user WHERE aoc.id_agent = '1'");
					
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
			$dbconn->Execute("DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '1' AND id_company = '$id_company_prev'");	
		}
		$rs->MoveNext();
	}
	
	if ($old_user_type == 2 && $user_type !=2) {
		$rs = $dbconn->Execute("SELECT id_agent, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '1'");		
		
		$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$row["id_agent"]."'");
		$lang_id_agent = GetUserLanguageId($rs2->fields[0]);
		
		while (!$rs->EOF) {
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
	
	if(($old_user_type != 3 && $user_type==3) || ($new_company == 1))
	{
		
		$id_company = intval($_POST["id_company"]);		
		$id_agent = 1;
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
	
		$mail_content = GetMailContentReplace("mail_content_new_agent", GetUserLanguageId($user["lang_id"]));
	
		SendMail($company["email"], $site_email, $mail_content["subject"], $data, $mail_content, "mail_new_agent_table", '', $data["company_name"]."(".$data["user_name"].")" , $mail_content["site_name"], 'text');
	
		}
	}
	if ($user_type == 3) {
		$agent_photo = $_FILES["agent_photo"];

		if((strlen($agent_photo["name"])!=0) && (intval($agent_photo["size"])!=0)) {			
			
			$strSQL = " SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='1' ";
			$rs = $dbconn->Execute($strSQL);
			if (strlen($rs->fields[0])>0) {
				if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
					$dbconn->Execute(" DELETE FROM ".USER_PHOTOS_TABLE." WHERE id_user='1' ");
					
				}
			}				
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($agent_photo, 1, 1, "agent_photo");			
			if (!$err) {
				unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
			}else{
				$dbconn->Execute("INSERT INTO ".USER_PHOTOS_TABLE." (id_uder, photo_path, approve) VALUES ('1', '$rs->fields[0]', '$rs->fields[1]') WHERE id_user='1' ");
			}
		}
	}

	$strSQL = "DELETE FROM ".SUBSCRIBE_USER_TABLE." where id_user='1' ";
	$dbconn->Execute($strSQL);
	if (!empty($_POST["alert"])) {
		$alerts = $_POST["alert"];
		foreach ($alerts as $arr) {
			$strSQL = "INSERT INTO ".SUBSCRIBE_USER_TABLE." (id_subscribe, id_user) VALUES ('".$arr."','1') ";
			$dbconn->Execute($strSQL);
		}
	}
	if ($user_type == 2) {
		$strSQL = " UPDATE ".RENT_ADS_TABLE." SET room_type='0' WHERE id_user='1' ";
		$dbconn->Execute($strSQL);
	}
	/**
	 * save references values
	 */
	$used_references = array("gender", "people", "language");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$tmp_info = (isset($_REQUEST[$arr["key"]]) && !empty($_REQUEST[$arr["key"]])) ? $_REQUEST[$arr["key"]] : array();
			$tmp_spr = $_REQUEST["spr_".$arr["key"]];
			if(is_array($tmp_info) && is_array($tmp_spr)) {
				SprTableEditAdmin($arr["spr_user_table"], 0, $tmp_spr, $tmp_info);
			}
		}
	}
	if (!$err) {		
	
		if ($old_user_type != $user_type) {
			
			if ($user_type == 3) {
				$err = "you_reg_data_changed_4";
			}
			elseif ($user_type == 2) {
				$err = "you_reg_data_changed_2";
			} else {
				$err = "you_reg_data_changed_1";
			}
		} else {
			$err = "you_reg_data_changed_3";
		}
	}
	if (intval($_REQUEST["redirect"]) > 0) {
		if (strpos( $err, "reg_data") != 0) {
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_rentals.php?".(($user[13] != $lang_id) ? "lang_code=$lang_id&" : "")."sel=my_ad&id_ad=".intval($_REQUEST["redirect"]));
		}else{
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_settings.php?".(($user[13] != $lang_id) ? "lang_code=$lang_id&" : "")."section=admin&err=$err&success=1&redirect=".intval($_REQUEST["redirect"]));
		}
	}else{
		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_settings.php?".(($user[13] != $lang_id) ? "lang_code=$lang_id&" : "")."section=admin&err=$err&success=1");
	}

	//ListSettings("admin", $err);exit;
	exit;
}

/**
 * Set default message on errors
 *
 * @param int $section
 */

function SetDefault($section)
{
	global $dbconn, $config, $lang;

	$file_virt_name = $config["server"].$config["site_root"]."/admin/admin_settings.php?section=$section";
	switch($section) {
		case "server_errors":
			$settings_manager = new SettingsManager();
			$settings_manager->SetDefault($_REQUEST["language_id"]);
		break;
		case "watermark":
			$settings_manager = new SettingsManager();
			$settings = (array("default_use_watermark", "default_watermark_width", "default_watermark_height", "default_watermark_type", "default_watermark_text", "default_watermark_image", "default_watermark_blank", "photo_folder", "watermark_image"));
			$settings = $settings_manager->GetSiteSettings($settings);
			$settings_arr["use_watermark"] = $settings["default_use_watermark"];
			$settings_arr["watermark_width"] = $settings["default_watermark_width"];
			$settings_arr["watermark_height"] = $settings["default_watermark_height"];
			$settings_arr["watermark_type"] = $settings["default_watermark_type"];
			$settings_arr["watermark_text"] = $settings["default_watermark_text"];

			$images_obj = new Images($dbconn);
			$new_file_name = $images_obj->GetNewFileName($settings["default_watermark_image"], "watermark");
			$photo_folder = $config["site_path"].$settings["photo_folder"];

			copy($photo_folder."/".$settings["default_watermark_image"], $photo_folder."/".$new_file_name);
			unlink($photo_folder."/".$settings["watermark_image"]);
			$settings_arr["watermark_image"] = $new_file_name;

			$settings_manager->SaveSiteSettings($settings_arr);
		break;
		case "logotype":

			$settings_manager = new SettingsManager();
			$settings_logo = $settings_manager->GetLogoSettings($_REQUEST["language_id"]);
			$lang["default_select"] = GetLangContent("default_select", LangPathById($_REQUEST["language_id"]));
			$settings = (array("default_logotype", "default_homelogo", "default_slogan", "index_theme_path", "index_theme_images_path"));
			$settings = $settings_manager->GetSiteSettings($settings);
			$photo_folder = $config["site_path"].$settings["index_theme_path"].$settings["index_theme_images_path"]."/".LangNameById($_REQUEST["language_id"]);
			
			$photo_folder_arr = ScanTemplateFolder($config["site_path"]."/templates/");
			$images_obj = new Images($dbconn);
			$def_settings = "default_".$_REQUEST["type"][$_REQUEST["pos"]];
			$new_logo_name = $images_obj->GetNewFileName($photo_folder."/".$settings[$def_settings], "anylogo");
			foreach ($photo_folder_arr AS $key=>$template){
				copy($photo_folder."/".$settings[$def_settings], $config["site_path"]."/templates/".$template."/images"."/".LangNameById($_REQUEST["language_id"])."/".$new_logo_name);
			}
			
			
			
			$settings_manager->UpdateLogo($_REQUEST["language_id"], $_REQUEST["pos"], $new_logo_name,  LangNameById($_REQUEST["language_id"]));
			$settings_manager->SaveLogoSettings($_REQUEST["language_id"], $_REQUEST["pos"], $lang["default_select"]["logo_alt"]);

		break;

	}
	ListSettings($section);exit;
}

/**
 * Unactivate ads who have sold leased status and send emails to their owners
 *
 * @param void
 * @return void
 */
function UnactivateSoldLeased() {
	global $dbconn;

	$strSQL = "SELECT DISTINCT ra.id as id_ad, ut.id as id_user, ut.fname, ut.sname, ut.lang_id, ut.email  ".
		  	  "FROM ".USERS_TABLE." ut ".
		  	  "LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=ut.id ".
		  	  "WHERE ut.active='1' AND ra.status='1' AND ra.sold_leased_status='1'";
	$rs = $dbconn->Execute($strSQL);

	if ($rs->RowCount() > 0) {
		/**
		 * Unactivate ads
		 */
		$strSQL = "UPDATE ".RENT_ADS_TABLE." SET status='0', sold_leased_status='0' WHERE status='1' AND sold_leased_status='1'";
		$dbconn->Execute($strSQL);
		/**
		 * Send email to ads owner
		 */
		$settings_manager = new SettingsManager();
		$site_mail = $settings_manager->GetSiteSettings('site_email');
		$mail_content_lang = array();
		while (!$rs->EOF) {
			$row = $rs->GetRowAssoc( false );
			$to_send = array();
			$to_send["id_ad"] = $row["id_ad"];
			$to_send["fname"] = stripslashes($row["fname"]);
			$to_send["sname"] = stripslashes($row["sname"]);
			$to_send["email"] = $row["email"];
			$to_send["lang_id"] = $row["lang_id"];

			$mail_lang_id = GetUserLanguageId($to_send["lang_id"]);
			if (!isset($mail_content_lang[$mail_lang_id])) {
				$mail_content_lang[$mail_lang_id] = GetMailContentReplace("mail_content_unactivate_sold_leased", $mail_lang_id);
			}
			$mail_content = $mail_content_lang[$mail_lang_id];

			SendMail($to_send["email"], $site_mail, $mail_content["subject"], $to_send, $mail_content, "mail_unactivate_ad", '', $to_send["fname"]." ".$to_send["sname"], $mail_content["site_name"], 'text');

			$rs->MoveNext();
		}
	}
}

function SearchString() {
	global $config, $smarty, $lang;
	
	$find_lang = (isset($_REQUEST["find_lang"]) && !empty($_REQUEST["find_lang"])) ? trim($_REQUEST["find_lang"]) : "";
	$search_string = (isset($_REQUEST["search_string"]) && !empty($_REQUEST["search_string"])) ? trim($_REQUEST["search_string"]) : "";
	
	IndexAdminPage('admin_settings');
	$admin_lang_menu = CreateMenu('admin_lang_menu');	
	
	if ($find_lang && $search_string) {			
		$result = array();		
		$dir = $config["site_path"]."/lang/$find_lang";		
		
		$files_arr = array();
		GetDirFiles($dir, $files_arr);		
		foreach ($files_arr as $file_path) {
			$res = array();
			$search_strings = GetFindInFile($file_path, $search_string);
			if (count($search_strings)) {				
				$res["short_file_path"] = str_replace($dir, "", $file_path);
				
				$path_parts = pathinfo($res["short_file_path"]);				
				$res["part"] = $path_parts["dirname"];
				$res["filename"] = $path_parts["basename"];
				
				$res["strings"] = $search_strings;
				$result[] = $res;		
			}			
		}		
		$smarty->assign("search_result", $result);	
		$smarty->assign("search_string", $search_string);	
		$smarty->assign("lang_folder", $find_lang);	
		foreach ($admin_lang_menu as $lang_menu) {
			if ($lang_menu["name"] == $find_lang) {
				$find_lang_name = $lang_menu["value"];
				break;
			}
		}		
		$smarty->assign("header", $lang["content"]["search_for_title"]." '$search_string' ".$lang["content"]["in_lang"]." ".((isset($find_lang_name) && !empty($find_lang_name)) ? $find_lang_name : $find_lang)." ".$lang["content"]["site_version"]);	
	} else {
		$smarty->assign("nothing_to_search", 1);
	}
	
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_lang_string_search.tpl");
	exit;
}

/**
 * Forms array of all files in the directory
 *
 * @param string $dirname
 * @param array $mass
 */
function GetDirFiles($dirname, &$mass) {	
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false) {
		if($file != "." && $file != ".." && $file != "CVS"){
			if (is_file($dirname."/".$file)){
				$mass[] = $dirname."/".$file;
			} else {				
				GetDirFiles($dirname."/".$file, $mass);
			}
		}
	}
	closedir($dir);		
}
function ScanTemplateFolder($dirname){
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false) {
		if($file != "." && $file != ".." && $file != "CVS" && $file != "admin"){
			if (!is_file($dirname."/".$file)){
				$mass[] = $file;
			} else {								
			}
		}
	}
	closedir($dir);	
	return $mass;
}

/**
 * Convert all user types to 'private user type'
 *
 */
function ConvertUsersToPrivateUser(){
	global $dbconn;
	$strSQL = "UPDATE ".USERS_TABLE." SET user_type='1' WHERE user_type='3'";	
	$dbconn->Execute($strSQL);
	$strSQL = "TRUNCATE ".AGENT_OF_COMPANY_TABLE;	
	$dbconn->Execute($strSQL);
	return;
}

function AdditionForm(){
}
?>
