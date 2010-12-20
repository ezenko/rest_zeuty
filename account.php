<?php
/**
* Manage users account page (change profile info or password, activate/deactivate profile)
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.10 $ $Date: 2009/01/08 11:07:13 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";
if (GetSiteSettings("use_pilot_module_newsletter")) {
	include "./include/functions_newsletter.php";
}
include "./include/class.lang.php";
include "./include/class.images.php";

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

GetLocationContent();

if ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {	
	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	$msg = (isset($_REQUEST["msg"]) && !empty($_REQUEST["msg"])) ? $_REQUEST["msg"] : "";
	if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0 && $sel != "activate")) {
		AlertPage();
		exit;
	} elseif ($mode == 0) {
		AlertPage(GetRightModulePath(__FILE__));
		exit;
	}

	$multi_lang = new MultiLang($config, $dbconn);
	switch($sel) {
		case "deactivate":			AccountPage("deactivate", $msg);break;
		case "from_deactivation":	AccountPage("from_deactivation", $msg);break;
		case "save_profile":		SaveProfile(); break;
		case "save_password":		SavePassword(); break;
		case "edit_password":		AccountPage("password", $msg); break;
		case "activate":			AccountPage("activate", $msg); break;
		case "account":				AccountPage("account", $msg); break;
		default:					AccountPage("account", $msg); break;
	}
}

function AccountPage($par, $err='') {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "account.php";

	IndexHomePage('account','homepage');

	if ($user[3] != 1) {
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('account_menu');	
	if ($err) {
		GetErrors($err);		
	}
	$smarty->assign("file_name", $file_name);
	$smarty->assign("use_agent_user_type", GetSiteSettings("use_agent_user_type"));
	switch ($par) {
		case "account":
			$section = 1;
			$smarty->assign("submenu", "edit_profile");
			$redirect = (isset($_REQUEST["redirect"]) && !empty($_REQUEST["redirect"])) ? intval($_REQUEST["redirect"]) : "";
			$strSQL = "SELECT login, fname, sname, DATE_FORMAT(date_birthday, '%d') as birth_day, DATE_FORMAT(date_birthday, '%m') as birth_month, DATE_FORMAT(date_birthday, '%Y') as birth_year, lang_id, email, phone, user_type FROM ".USERS_TABLE." WHERE id='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$data = $row;
			$data["user_id"] = $user[0];
			$data["login"] = htmlspecialchars($row["login"]);
			$data["fname"] = htmlspecialchars($row["fname"]);
			$data["sname"] = htmlspecialchars($row["sname"]);
			$data["phone"] = htmlspecialchars($row["phone"]);
			$data["email"] = htmlspecialchars($data["email"]);
			/**
			 * check for users' language visibility
			 */
			$data["lang_id"] = GetUserLanguageId($data["lang_id"]);

			$week = GetWeek();
			$smarty->assign("week", $week);

			$time_arr = GetHourSelect();
			$smarty->assign("time_arr", $time_arr);
			$smarty->assign("map",GetMapSettings());
			$smarty->assign("use_maps_in_account", GetSiteSettings("use_maps_in_account"));
			$smarty->assign("redirect", $redirect);			
			
			if ($data["user_type"] == 2) {
				$strSQL = "SELECT company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, logo_path, admin_approve,id_country,id_region,id_city,address,postal_code FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$data["company_name"] = htmlspecialchars($row["company_name"]);
				$data["company_url"] = htmlspecialchars($row["company_url"]);
				$data["company_rent_count"] = htmlspecialchars($row["company_rent_count"]);
				$data["company_how_know"] = htmlspecialchars($row["company_how_know"]);
				$data["company_quests_comments"] = htmlspecialchars($row["company_quests_comments"]);
				$data["weekday_str"] = $row["weekday_str"];
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
				$data["address"] = htmlspecialchars($row["address"]);
				$data["postal_code"] = htmlspecialchars($row["postal_code"]);
								
				GetLocationContent($data["id_country"],$data["id_region"]);							
				$strSQL = "SELECT name FROM ".COUNTRY_TABLE." where id=".$data["id_country"];
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$profile["country_name"]=$row["name"];				
				$strSQL = "SELECT name FROM ".REGION_TABLE." where id=".$data["id_region"];
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$profile["region_name"]=$row["name"];				
				$strSQL = "SELECT name,lat,lon FROM ".CITY_TABLE." where id=".$data["id_city"];
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$profile["city_name"]=$row["name"];
				$profile["lon"]=$row["lon"];
				$profile["lat"]=$row["lat"];				
				$profile["adress"]=$data["address"];				
				if (($data["id_country"] && $profile["country_name"] == '')||($data["id_region"] && $profile["region_name"] == '')||($data["id_city"] && $profile["city_name"] == '')) {
					$data["in_base"]=0;
				}
				else {$data["in_base"]=1;
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
				$strSQL_2 = "SELECT id_agent FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$user[0]' AND approve = '1'";
				$rs_2 = $dbconn->Execute($strSQL_2);
				if ($rs_2->RowCount() > 0) {
					$data["have_agents"] = 1;
				}
				$smarty->assign("profile", $profile);		
			}
			$data["agency_approve"] = -1;
			if ($data["user_type"] == 3) {
				
				$strSQL = "SELECT aoc.id, aoc.id_agent, aoc.id_company, aoc.approve, rd.company_name, rd.company_url, rd.logo_path, rd.admin_approve as logo_approve, rd.address, rd.weekday_str, rd.work_time_begin, rd.work_time_end, rd.lunch_time_begin, rd.lunch_time_end, ct.name as country_name, rt.name as region_name, cit.name as city_name, cit.lon, cit.lat, rd.id_country,  u.phone 
									FROM ".AGENT_OF_COMPANY_TABLE." aoc 
									LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user 
									LEFT JOIN ".USERS_TABLE." u ON aoc.id_company = u.id 
									LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=rd.id_country
									LEFT JOIN ".REGION_TABLE." rt ON rt.id=rd.id_region
									LEFT JOIN ".CITY_TABLE." cit ON cit.id=rd.id_city
									WHERE id_agent = '$user[0]' AND (aoc.inviter = 'agent' OR (aoc.inviter = 'company' AND aoc.approve = '1')) ORDER BY aoc.id DESC LIMIT 1";
				
				$rs = $dbconn->Execute($strSQL);				
				
				if ($rs->fields[0] > 0) {
					$row = $rs -> GetRowAssoc(false);
					$data["agency_name"] = $row["company_name"];					
					if ($row["company_url"] != "" && strpos("http://", $row["company_url"]) == 0) {
						$data["agency_url"] = "http://".$row["company_url"]."/";
					} else {
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
					} else {
						$data["in_base"] = 0;
					}
					$data["id_country"] = $row["id_country"];
					$data["region_name"] = $row["region_name"];		
					$data["city_name"] = $row["city_name"];		
					$data["ag_address"] = $row["address"];
					
					$data["weekday_str"] = $row["weekday_str"];
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
					
					$profile["country_name"]=$data["country_name"];
					$profile["region_name"]=$data["region_name"];
					$profile["city_name"]=$data["city_name"];
					$profile["addres"] = $data["ag_address"];
					$profile["lon"]=$row["lon"];
					$profile["lat"]=$row["lat"];
					if ($profile["country_name"] == '') {				
						$profile["in_base"]=0;
					}
					else {$profile["in_base"]=1;
					}	
					$smarty->assign("profile",$profile);
					
				}				
				$rs_2 = $dbconn->Execute("SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='$user[0]'");
				if ($rs_2->NumRows() > 0) {
				$data["photo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_2->fields[0];
				$data["admin_approve"] = $rs_2->fields[1];
				$data["use_photo_approve"] = GetSiteSettings("use_photo_approve");
				}
				
			}
			
			$smarty->assign("day", GetDaySelect($data["birth_day"]));
			$smarty->assign("month", GetMonthSelect($data["birth_month"]));
			$smarty->assign("year", GetYearSelect($data["birth_year"], 80, (intval(date("Y"))-18)));
			$strSQL = "SELECT id_subscribe FROM ".SUBSCRIBE_USER_TABLE." WHERE id_user='".$user[0]."'";
			$rs = $dbconn->Execute ($strSQL);
			$i = 0;
			while(!$rs->EOF) {
				$alerts_sel[$i] = $rs->fields[0];
				$rs->MoveNext();
				$i++;
			}

			$alerts = GetAlertsName();
			$strSQL = "SELECT id FROM ".SUBSCRIBE_SYSTEM_TABLE." WHERE status='1'";
			$rs = $dbconn->Execute ($strSQL);
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$alerts_st[$row["id"]] = $row["id"];
				$rs->MoveNext();
			}
			$i = 0;
			foreach ($alerts as $arr) {
				if (in_array($arr["id"],$alerts_st)) {
					$alerts_vis[$i]["id"] = $arr["id"];
					$alerts_vis[$i]["name"] = $arr["name"];
					if (isset($alerts_sel) && is_array($alerts_sel) && in_array($alerts_vis[$i]["id"], $alerts_sel)) {
						$alerts_vis[$i]["sel"] = 1;
					}
					$i++;
				}
			}
			$smarty->assign("alerts", $alerts_vis);
			
			$use_pilot_module_sms_notifications = GetSiteSettings("use_pilot_module_sms_notifications");
			if ($use_pilot_module_sms_notifications){
				$lang["sms_notifications"] = GetLangContent("admin/admin_sms_notifications");
				$smarty->assign("lang", $lang);
				$strSQL= "SELECT value, name FROM ".SMS_NOTIFICATIONS_SETTINGS;		
				$sms_settings = array();
				$rs = $dbconn->Execute($strSQL);
				while(!$rs->EOF){
					$row = $rs->GetRowAssoc(false);
					$sms_settings[$row["name"]] = $row["value"];
					$rs->MoveNext();
				}
				$smarty->assign("use_pilot_module_sms_notifications", $sms_settings["use"]);	
				if ($sms_settings["use"]){
					$strSQL = "SELECT id_subscribe FROM ".SMS_NOTIFICATIONS_USER_EVENT." WHERE id_user='{$user[0]}'";
					$rs = $dbconn->Execute($strSQL);
					while(!$rs->EOF){
						$sms_user_event[$rs->fields[0]] = 1;
						$rs->MoveNext();
					}	
					
					$strSQL = "SELECT id, description FROM ".SMS_NOTIFICATIONS_SUBSCRIBE." WHERE status='1'";
					$rs = $dbconn->Execute($strSQL);
					$sms_cases = array();
					while(!$rs->EOF){
						$row = $rs->GetRowAssoc(false);
						$sms_cases[$row["id"]] = $row["description"];
						if (!isset($sms_user_event[$row["id"]])){
							$sms_user_event[$row["id"]] = 0;
						}
						$rs->MoveNext();
					}	
														
					$strSQL = "SELECT sms_balance FROM ".SMS_NOTIFICATIONS_USER_BALANCE." WHERE id_user='".$user[0]."'";
					$rs = $dbconn->Execute ($strSQL);
					
					if(!$rs->EOF) {
						$sms_balance = $rs->fields[0];				
					}else{
						$sms_balance = 0;
					}
										
					$smarty->assign("sms_balance", $sms_balance);
					$smarty->assign('sms_user_event', $sms_user_event);	
					$smarty->assign('sms_cases', $sms_cases);	
					$smarty->assign("link_to_sms", $config["server"].$config["site_root"]."/services.php?sel=sms_notifications");
				}
			}else{
				$smarty->assign("use_pilot_module_sms_notifications", $use_pilot_module_sms_notifications);
			}

			//$smarty->assign("data", $data);
			break;
		case "deactivate":
			$smarty->assign("submenu", "deactivate");
			$deactivate = GetReferenceArray(SPR_DEACTIVATE_TABLE, VALUES_DEACTIVATE_TABLE, "deactivate", "");
			$section = 2;
			
			$strSQL = "SELECT u.user_type FROM ".USERS_TABLE." u WHERE id='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			$user_type = $rs->fields[0];
			
			switch ($user_type) {
				case 2:
					$rs = $dbconn->Execute("SELECT id_company FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$user[0]' AND approve = '1'");							
					break;
				case 3:
					$rs = $dbconn->Execute("SELECT id_agent FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]' AND approve = '1'");										
					break;	
			}
			if ($rs->RowCount()	> 0) {
				$need_delete = 1;
			} else {
				$need_delete = 0;
			}
			$smarty->assign("user_type", $user_type);
			$smarty->assign("need_delete", $need_delete);
			$smarty->assign("deactivate", $deactivate);
			$smarty->assign("add_to_lang", "&sel=deactivate");
			break;
		case "from_deactivation":
			$section = 1;
			$deactivate["deactivate"] = $_POST["deactivate"];
			$user_type = intval($_POST["user_type"]);
			$id_deactive = $_POST["deactivate"][0];
			$user_comments = strip_tags($_POST["comment"]);
			$strSQL = "	INSERT INTO ".ACCOUNT_DEACTIVATED_TABLE." (id_user, id_deactive, comments, deactivated_date)
						VALUES ('".$user[0]."', '".$id_deactive[0]."', '".addslashes($user_comments)."',  now() ) ";
			$dbconn->Execute($strSQL);
			$strSQL = "UPDATE ".USERS_TABLE." SET active='0' WHERE id='".$user[0]."' ";
			$dbconn->Execute($strSQL);
			GetErrors("account_deactivated");
			$user[8] = 0;

			$strSQL = "	UPDATE ".RENT_ADS_TABLE." SET status='0' WHERE id_user='".$user[0]."'";
			$dbconn->Execute($strSQL);
			
			switch ($user_type) {
				case 2:
					$rs = $dbconn->Execute("SELECT aoc.id, aoc.id_company, aoc.id_agent, aoc.approve,aoc.inviter, rd.company_name, u.lang_id FROM ".AGENT_OF_COMPANY_TABLE." aoc 
					LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user 
					LEFT JOIN ".USERS_TABLE." u ON aoc.id_agent = u.id 
					WHERE id_company = '$user[0]'");														
					if ($rs->RowCount() > 0) {
						while(!$rs->EOF) {
							$row = $rs->GetRowAssoc(false);												
							if ($row["inviter"] == 'company' && $row["approve"] == 0) {								
								$mail_content = GetMailContentReplace("mail_content_delete_offer_by_realtor", GetUserLanguageId($row["lang_id"]));
								$template = "mail_delete_by_realtor_table";
							}		
							if ($row["inviter"] == 'agent' && $row["approve"] == 0) {				
								$mail_content = GetMailContentReplace("mail_content_decline_by_realtor", GetUserLanguageId($row["lang_id"]));
								$template = "mail_delete_by_realtor_table";
							}
							$subject = $mail_content["subject"];												
							
							$id_agent = $row["id_agent"];
							$id_company = $row["id_company"];
							$data["company_name"] = $row["company_name"];
							
							$strSQL_2 = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
							$rs_2=$dbconn->Execute($strSQL_2);
							$row_2 = $rs_2->GetRowAssoc(false);
								
							$email = $row_2["email"];
							$email_to_name = $row_2["fname"]." ".$row_2["sname"];
							$data["agent_name"] = $email_to_name;
							$data["link"] = $config["server"].$config["site_root"]."/account.php";
							
							SendMail($email, $site_mail, $subject, $data, $mail_content, $template, $email_to_name, $mail_content["site_name"] );	
								
							$strSQL_2 = "DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id = '$id_agent' AND id_company = '".$id_company."'";
							$dbconn->Execute($strSQL_2);						
							$rs->MoveNext();
						}						
					}
					break;
				case 3:
					$rs = $dbconn->Execute("SELECT aoc.id, aoc.id_company, aoc.id_agent, aoc.approve, aoc.inviter, rd.company_name, u.lang_id FROM ".AGENT_OF_COMPANY_TABLE." aoc 
					LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user 
					LEFT JOIN ".USERS_TABLE." u ON aoc.id_company = u.id 
					WHERE id_agent = '$user[0]'");
							
					if ($rs->RowCount() > 0) {
						while(!$rs->EOF) {
							$row = $rs->GetRowAssoc(false);												
							if ($row["inviter"] == 'company' && $row["approve"] == 0) {
								$mail_content = GetMailContentReplace( "mail_content_delete_by_agent_1", GetUserLanguageId($row["lang_id"]));
								$template = "mail_delete_by_agent_table_2";								
								
							}		
							if ($row["inviter"] == 'agent' && $row["approve"] == 0) {				
								$mail_content = GetMailContentReplace( "mail_content_delete_by_agent_2", GetUserLanguageId($row["lang_id"]));
								$template = "mail_delete_by_agent_table_2";
							}
							
							$subject = $mail_content["subject"];							
							
							$id_agent = $row["id_agent"];
							$id_company_prev = $row["id_company"];							 
							$data["company_name"] = $row["company_name"];											
							$strSQL_2 = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_company_prev'";		
							$rs_2=$dbconn->Execute($strSQL_2);
							$row_2 = $rs_2->GetRowAssoc(false);								
							$email = $row_2["email"];
							$email_to_name = $row_2["fname"]." ".$row_2["sname"];
							$data["company_name_user"] = $email_to_name;
							
							$strSQL_2 = "SELECT u.fname, u.sname FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
							$rs_2=$dbconn->Execute($strSQL_2);
							$row_2 = $rs_2->GetRowAssoc(false);								
							$data["agent_name"] = $row_2["fname"]." ".$row_2["sname"];							
							$data["link"] = $config["server"].$config["site_root"]."/agents.php";
							
							SendMail($email, $site_mail, $subject, $data, $mail_content, $template, $email_to_name, $mail_content["site_name"] );	
							$dbconn->Execute("DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]' AND id_company = '$id_company_prev'");		
							$rs->MoveNext();				
						}						
					}																
					break;						
			}			

			$site_mail = GetSiteSettings("site_email");
			$reason = GetReferenceArray(SPR_DEACTIVATE_TABLE, VALUES_DEACTIVATE_TABLE, "deactivate", $deactivate);
			foreach ($reason[0]["opt"] as $arr) {
				if ($arr["sel"]==1) {
					$sel = $arr["name"];
				}
			}
			$strSQL = "	SELECT fname, email FROM ".USERS_TABLE." WHERE id='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			$data["login"] = $rs->fields[0];
			$data["email"] = $rs->fields[1];
			$data["comments"] = $user_comments;
			$data["reason"] = $sel;

			$mail_content = GetMailContentReplace("mail_content_deactivate", GetAdminLanguageId());
			$subject = $mail_content["subject"];

			SendMail($site_mail, $data["email"], $subject, $data, $mail_content, "mail_contact_deactivate", "", "moderator of ".$config["server"], $data["login"], "text");
			AccountPage("account");
			exit;
			break;
		case "activate":
			$section = 1;
			$strSQL = "UPDATE ".USERS_TABLE." SET active='1' WHERE id='".$user[0]."' ";
			$user[8] = 1;
			$dbconn->Execute($strSQL);
			GetErrors("account_activated");
			AccountPage("account");
			exit;
			break;
		case "password":
			$smarty->assign("submenu", "edit_password");
			$section = 3;
			$strSQL = "SELECT password FROM ".USERS_TABLE." WHERE id='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$data["password"] = $row["password"];
			//$smarty->assign("data", $data);
			$smarty->assign("add_to_lang", "&sel=edit_password");
			break;
	}
	/**
	 * справочники с характеристикой человека
	 * для информации профайла в таблице $arr["spr_user_table"] - $id_ad=0
	 */
	$used_references = array("gender", "people", "language");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data[$arr["key"]] = SprTableSelect($arr["spr_user_table"], 0, $user[0], $arr["spr_table"]);
			$smarty->assign($arr["key"], GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data));
		}
	}
	$smarty->assign("data", $data);

	$smarty->assign("section", $section);
	$smarty->display(TrimSlash($config["index_theme_path"])."/account_table.tpl");
	exit;
}

function SaveProfile() {
	global $config, $smarty, $dbconn, $user, $REFERENCES;

	$fname = strip_tags(trim($_POST["fname"]));
	$sname = strip_tags(trim($_POST["sname"]));
	$birth_year = intval(trim($_POST["birth_year"]));
	$birth_month = intval(trim($_POST["birth_month"]));
	$birth_day = intval(trim($_POST["birth_day"]));
	$lang_id = intval($_POST["lang_id"]);
	$email = trim(strip_tags($_POST["email"]));
	$login = $email;
	$phone = strip_tags(trim($_POST["phone"]));

	if ($birth_year<1) {
		$birthdate = sprintf("%04d-%02d-%02d", 0, 0, 0);
	} else {
		$birthdate = sprintf("%04d-%02d-%02d", $birth_year, $birth_month, $birth_day);
	}
	$user_type = intval($_POST["user_type"]);

	if (!($fname && $sname && $email)) {
		AccountPage("account", "empty_fields");
	}

	$strSQL = "SELECT user_type FROM ".USERS_TABLE." WHERE id='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$old_user_type = $rs->fields[0];
	
	if ($user_type==2) {
		/**
		 * realtor
		 */
		$company_name = addslashes(strip_tags(trim($_POST["company_name"])));
		if ($company_name == '') {
			AccountPage("account", "empty_fields");
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
		$use_photo_approve = GetSiteSettings("use_photo_approve");
		
		$admin_approve = (GetSiteSettings("use_photo_approve")) ? 0 : 1;
		
		$strSQL = "SELECT id_user FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user[0]."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$strSQL = "UPDATE ".USER_REG_DATA_TABLE." SET company_name='".$company_name."', company_url='".$company_url."',
						company_rent_count='".$company_rent_count."', company_how_know='".$company_how_know."',  company_quests_comments='".$company_quests_comments."',
						weekday_str='".$weekday_str."',  work_time_begin='".$work_time_begin."',  work_time_end='".$work_time_end."',
						lunch_time_begin='".$lunch_time_begin."', lunch_time_end='".$lunch_time_end."', id_country='".$id_country."', id_region='".$id_region."', id_city='".$id_city."', address='".$address."', postal_code='".$postal_code."' WHERE id_user='".$user[0]."' ";
		} else {
			$strSQL = "INSERT INTO ".USER_REG_DATA_TABLE." (id_user, company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, id_country,id_region,id_city,address, postal_code)
						VALUES ('".$user[0]."', '".$company_name."', '".$company_url."', '".$company_rent_count."', '".$company_how_know."', '".$company_quests_comments."', '".$weekday_str."', '".$work_time_begin."', '".$work_time_end."', '".$lunch_time_begin."', '".$lunch_time_end."', '".$id_country."', '".$id_region."', '".$id_city."', '".$address."', '".$postal_code."' )";
		}		
		$dbconn->Execute($strSQL);
		
		$company_logo = $_FILES["company_logo"];
		if ((strlen($company_logo["name"])!=0) && (intval($company_logo["size"])!=0)) {
				$strSQL = "SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);
				if (strlen($rs->fields[0])>0) {
					if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
						$dbconn->Execute(" UPDATE ".USER_REG_DATA_TABLE." SET logo_path='', admin_approve = '$admin_approve' WHERE id_user='".$user[0]."' ");
						unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
					}
				}
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($company_logo, $user[0]);
		}
	}
	
	$strSQL = "UPDATE ".USERS_TABLE." SET login='".addslashes($login)."', fname='".addslashes($fname)."', sname='".addslashes($sname)."', date_birthday='".$birthdate."', lang_id='".$lang_id."', email='".addslashes($email)."', phone='".addslashes($phone)."', user_type='".$user_type."' WHERE id='".$user[0]."'";
	$dbconn->Execute($strSQL);
	
	if (GetSiteSettings("use_pilot_module_newsletter")) {
		UpdateNewsletterUserData($user[0], $fname, $sname, $email);
		UpdateUserRealestateMailingList($user[0]);
    }
	
	$rs = $dbconn->Execute("SELECT id_agent, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]'");
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
					
			$rs2 = $dbconn->Execute("SELECT aoc.id_agent, aoc.id_company, rd.company_name FROM ".AGENT_OF_COMPANY_TABLE." aoc LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user WHERE aoc.id_agent = '$user[0]'");
					
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
			$dbconn->Execute("DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]' AND id_company = '$id_company_prev'");	
		}
		$rs->MoveNext();
	}
	if ($old_user_type == 2 && $user_type !=2) {
		$rs = $dbconn->Execute("SELECT id_agent, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$user[0]'");	
		
		$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id='".$rs->fields[0]."'");
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
	
	if (($old_user_type != 3 && $user_type==3) || (isset($new_company) && $new_company == 1))
	{		
		$id_company = intval($_POST["id_company"]);		
		$id_agent = $user[0];			
		
		
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
		$data["company_name"] = htmlspecialchars($row["company_name"]);
		
		$strSQL = "SELECT fname, sname, lang_id FROM ".USERS_TABLE." WHERE id='$id_agent'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data["user_name"] = htmlspecialchars($row["fname"])." ".htmlspecialchars($row["sname"]);
		$data["approve_link"] = $config["server"].$config["site_root"]."/agents.php";
		
		$site_email = GetSiteSettings('site_email');
	
		$mail_content = GetMailContentReplace("mail_content_new_agent", GetUserLanguageId($row["lang_id"]));
	
		SendMail($company["email"], $site_email, $mail_content["subject"], $data, $mail_content, "mail_new_agent_table", '', $data["company_name"]."(".$data["user_name"].")" , $mail_content["site_name"], 'text');
	
		}
	}
	if ($user_type == 3) {
		$agent_photo = $_FILES["agent_photo"];

		if ((strlen($agent_photo["name"])!=0) && (intval($agent_photo["size"])!=0)) {			
			
			$strSQL = "SELECT photo_path, approve FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			if (strlen($rs->fields[0])>0) {
				if (file_exists($config["site_path"]."/uploades/photo/".$rs->fields[0])) {
					$dbconn->Execute(" DELETE FROM ".USER_PHOTOS_TABLE." WHERE id_user='".$user[0]."' ");
					
				}
			}				
			$images_obj = new Images($dbconn);
			$err = $images_obj->UploadCompanyLogo($agent_photo, $user[0], 0, "agent_photo");			
			if (!$err) {
				unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
			} else {
				$dbconn->Execute("INSERT INTO ".USER_PHOTOS_TABLE." (id_uder, photo_path, approve) VALUES ('$user[0]', '$rs->fields[0]', '$rs->fields[1]') WHERE id_user='".$user[0]."' ");
			}
		}
	}

	$strSQL = "DELETE FROM ".SUBSCRIBE_USER_TABLE." where id_user='".$user[0]."' ";
	$dbconn->Execute($strSQL);
	if (!empty($_POST["alert"])) {
		$alerts = $_POST["alert"];
		foreach ($alerts as $arr) {
			$strSQL = "INSERT INTO ".SUBSCRIBE_USER_TABLE." (id_subscribe, id_user) VALUES ('".$arr."','".$user[0]."') ";
			$dbconn->Execute($strSQL);
		}
	}
	if (GetSiteSettings("use_pilot_module_sms_notifications")){
		$strSQL = "DELETE FROM ".SMS_NOTIFICATIONS_USER_EVENT." WHERE id_user='".$user[0]."'";	
		$dbconn->Execute($strSQL);	
	
		if (isset($_REQUEST["sms_price"])){		
			
			$strSQL = "INSERT INTO ".SMS_NOTIFICATIONS_USER_EVENT." (id_subscribe, id_user) VALUES ";
			$i =0 ;
			foreach ($_REQUEST["sms_price"] AS $key=>$item){
				if ($i !=0){
					$strSQL .= ", ";
				}
				$strSQL.= "('$key','{$user[0]}')";
				$i++;
			}	
			$dbconn->Execute($strSQL);
		}
	}
	

	if ($user_type == 2) {
		$strSQL = "UPDATE ".RENT_ADS_TABLE." SET room_type='0' WHERE id_user='".$user[0]."' ";
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
			if (is_array($tmp_info) && is_array($tmp_spr)) {
				SprTableEdit($arr["spr_user_table"], 0, $tmp_spr, $tmp_info);
			}
		}
	}

	if (!isset($err)) {			
		if ($old_user_type != $user_type) {			
			if ($user_type == 3) {
				$err = "you_reg_data_changed_4";
			} elseif ($user_type == 2) {
				$err = "you_reg_data_changed_2";
			} else {
				$err = "you_reg_data_changed_1";
			}
		} else {
			$err = "you_reg_data_changed_3";
		}
	}
	
	if (isset($_REQUEST["redirect"]) && intval($_REQUEST["redirect"]) > 0) {
		if (strpos( $err, "reg_data") != 0) {	
			header("Location: ".$config["server"].$config["site_root"]."/rentals.php?sel=my_ad&".(($user[13] != $lang_id) ? "lang_code=$lang_id&" : "")."id_ad=".intval($_REQUEST["redirect"]));
		} else {
			header("Location: ".$config["server"].$config["site_root"]."/account.php?".(($user[13] != $lang_id) ? "lang_code=$lang_id&" : "")."sel=account&msg=$err&redirect=".intval($_REQUEST["redirect"]));
		}
	} else {
		header("Location: ".$config["server"].$config["site_root"]."/account.php?".(($user[13] != $lang_id) ? "lang_code=$lang_id&" : "")."sel=account&msg=$err");
	}
	exit;
}

function SavePassword() {
	global $config, $smarty, $dbconn, $user;
	$password = $_POST["new_password"];
	$strSQL = "UPDATE ".USERS_TABLE." SET password='".md5($password)."' WHERE id='".$user[0]."'";
	$dbconn->Execute($strSQL);
	AccountPage("account", "pass_changed");
	exit;
}

?>