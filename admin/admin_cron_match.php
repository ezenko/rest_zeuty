<?php
/**
* Cron file for sending notification for subscribed users if there are listings which match them
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.4 $ $Date: 2008/12/23 11:26:53 $
**/

include "../include/config.php";
include "../common.php";
include "../include/class.lang.php";
include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/functions_mail.php";
if (GetSiteSettings("use_pilot_module_sms_notifications")){
	include "../include/functions_sms_notifications.php";
}

$multi_lang = new MultiLang($config, $dbconn);

$smarty->assign("site_root", $config["site_root"]);
$smarty->assign("mail_template_root", $config["index_theme_path"]);
$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));

$site_mail = GetSiteSettings('site_email');
$photo_folder = $config["server"].$config["site_root"].GetSiteSettings("photo_folder");

$strSQL = "SELECT date_last_send FROM ".SUBSCRIBE_SYSTEM_TABLE." WHERE id='1'";
$date_last_send = $dbconn->GetOne($strSQL);
$strSQL = "UPDATE ".SUBSCRIBE_SYSTEM_TABLE." SET date_last_send=NOW() ";
$dbconn->Execute($strSQL);
$use_pilot_module_sms_notifications = GetSiteSettings("use_pilot_module_sms_notifications");
if ($use_pilot_module_sms_notifications){
	$strSQL = "SELECT DISTINCT ra.id as id_ad, ra.type, ra.movedate, ra.with_photo, ra.with_video, ".
		  "ut.id as id_user, ut.fname, ut.sname, ut.lang_id, ut.email, sut.id_user AS email_subscribe, sms_u.id_user AS sms_subscribe  ".
		  "FROM ".USERS_TABLE." ut ".
		  "LEFT JOIN ".SUBSCRIBE_USER_TABLE." sut ON sut.id_user=ut.id ".
		  "LEFT JOIN ".SMS_NOTIFICATIONS_USER_EVENT." sms_u ON sms_u.id_user=ut.id ".
		  "LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=ut.id ".
		  "WHERE ra.id IS NOT NULL AND (sut.id_subscribe='1' OR sms_u.id_subscribe='1') AND ut.status='1' ".
		  "AND ut.active='1' AND ut.id NOT IN ('2') AND ra.status='1'";

}else{
	$strSQL = "SELECT DISTINCT ra.id as id_ad, ra.type, ra.movedate, ra.with_photo, ra.with_video, ".
			  "ut.id as id_user, ut.fname, ut.sname, ut.lang_id, ut.email  ".
			  "FROM ".USERS_TABLE." ut ".
			  "LEFT JOIN ".SUBSCRIBE_USER_TABLE." sut ON sut.id_user=ut.id ".
			  "LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=ut.id ".
			  "WHERE ra.id IS NOT NULL AND sut.id_subscribe='1' AND ut.status='1' ".
			  "AND ut.active='1' AND ut.id NOT IN ('2') AND ra.status='1'";
}
 		  
$rs_main = $dbconn->Execute($strSQL);
$send_mails = 0;
if ($rs_main->RowCount() > 0) {
	$mail_content_lang = array();
	while(!$rs_main->EOF) {
		$row = $rs_main->GetRowAssoc(false);

		$id_ad = $row["id_ad"];
		$type = $row["type"];

		$id_user = $row["id_user"];
		$to_send["id"] = $id_user;		
		$to_send["fname"] = stripslashes($row["fname"]);
		$to_send["email"] = $row["email"];
		$to_send["sname"] = stripslashes($row["sname"]);
		$to_send["lang_id"] = $row["lang_id"];
		$to_send["unsubscribe_possible"] = 1;
		if ($use_pilot_module_sms_notifications){
			$to_send["email_subscribe"] = $row["email_subscribe"];
			$to_send["sms_subscribe"] = $row["sms_subscribe"];
		}


		$mail_lang_id = GetUserLanguageId($to_send["lang_id"]);
		if (!isset($mail_content_lang[$mail_lang_id])) {
			$mail_content_lang[$mail_lang_id] = GetMailContentReplace("mail_content_match", $mail_lang_id);
		}
		$mail_content = $mail_content_lang[$mail_lang_id];

		$movedate = $row["movedate"];
		$with_photo = $row["with_photo"];
		$with_video = $row["with_video"];

		/**
		 * @see homepage.php MatchResults("rental_match")
		 */
		if ($type == 1) {
			$choise = 2;
		} elseif ($type == 2) {
			$choise = 1;
		} elseif ($type == 3) {
			$choise = 4;
		} elseif ($type == 4) {
			$choise = 3;
		}

		$strSQL = "	SELECT id_country, id_region, id_city FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ";
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
			WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ";

		$rs_payment = $dbconn->Execute($strSQL_payment);
		$row_payment = $rs_payment->GetRowAssoc(false);

		$min_payment = $row_payment["min_payment"];
		$max_payment = $row_payment["max_payment"];
		$auction = $row_payment["auction"];
		$min_deposit = $row_payment["min_deposit"];
		$max_deposit = $row_payment["max_deposit"];
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

		$where_str = " AND u.guest_user='0' AND u.id != '".$id_user."' AND u.status='1' AND u.active='1' AND ra.status='1' ";
		$where_str .= " AND UNIX_TIMESTAMP(ra.datenow)>UNIX_TIMESTAMP('".$date_last_send."') ";

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


		if (count($id_arr) > 0) {
			$topsearch_str = " LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=ra.id AND tsat.type='1' ";

			$strSQL = "	SELECT DISTINCT	ra.id, ra.id_user, DATE_FORMAT(ra.movedate,'".$config["date_format"]."') as movedate, ra.type, ra.people_count, ra.room_type,
						u.fname, u.user_type, tsat.type as topsearched,
						tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
						urp.min_payment, urp.max_payment, urp.auction, uru.upload_path,
						ct.name as country_name, rt.name as region_name, cit.name as city_name,
						hlt.id_friend, blt.id_enemy,
						ra.upload_path as slide_path
						FROM ".RENT_ADS_TABLE." ra
						".$topsearch_str."
						LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id
						LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
						LEFT JOIN ".USERS_RENT_PAYS_TABLE." urp ON urp.id_ad=ra.id
						LEFT JOIN ".USERS_RENT_UPLOADS_TABLE." uru ON uru.id_ad=ra.id AND uru.upload_type='f' AND uru.status='1' AND uru.admin_approve='1'
						LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country
						LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region
						LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city
						LEFT JOIN ".HOTLIST_TABLE." hlt on ra.id_user=hlt.id_friend and hlt.id_user='".$id_user."'
						LEFT JOIN ".BLACKLIST_TABLE." blt on ra.id_user=blt.id_enemy and blt.id_user='".$id_user."'					
						WHERE ra.id in ( ".implode(",", $id_arr)." ) ORDER BY ra.id";
			
			$rs = $dbconn->Execute($strSQL);
			
			$search_result = array();
			$i = 0;
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);				
				$search_result[$i]["number"] = ($i+1);
				$search_result[$i]["id_ad"] = $row["id"];
				$search_result[$i]["people_count"] = $row["people_count"];
				$search_result[$i]["id_user"] = $row["id_user"];
				if ($row["movedate"] != '00.00.0000'){
					$search_result[$i]["movedate"] = $row["movedate"];
				}
				$search_result[$i]["id_type"] = $row["type"];
				$search_result[$i]["topsearched"] = $row["topsearched"];
				if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
					$search_result[$i]["show_topsearch_icon"] = true;
					$search_result[$i]["topsearch_date_begin"] = $row["topsearch_date_begin"];
				}
				$search_result[$i]["login"] = $row["fname"];
				$search_result[$i]["user_type"] = $row["user_type"];

				$lang_ad = 2; //т.к. выводим информацию о том, что ищет человек
				$used_references = array("gender", "realty_type");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$name = GetUserAdSprValues($arr["spr_user_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
						if (count($name) == 0 && $arr["spr_match_table"] != ""){
							$name = GetUserAdSprValues($arr["spr_match_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
						}
						$search_result[$i][$arr["key"]] = implode(",", $name);
					}
				}
				$search_result[$i]["min_payment"] = PaymentFormat($row["min_payment"]);
				$search_result[$i]["max_payment"] = PaymentFormat($row["max_payment"]);
				$search_result[$i]["auction"] = $row["auction"];

				if (strlen($row["slide_path"])>1){
					$search_result[$i]["image"] = $photo_folder."/".$row["slide_path"];
				} elseif (strlen($row["upload_path"])>1){
						$search_result[$i]["image"] = $photo_folder."/thumb_".$row["upload_path"];
				} else {
					$used_references = array("gender");
					foreach ($REFERENCES as $arr) {
						if (in_array($arr["key"], $used_references)) {
							$name = GetUserGenderIds($arr["spr_user_table"], $search_result[$i]["id_user"], 0, $arr["val_table"]);
							$search_result[$i][$arr["key"]] = $name;
						}
					}
					$gender_info = getDefaultUserIcon($search_result[$i]["user_type"], $search_result[$i]["gender"]);
					$search_result[$i]["num_gender"] =  $gender_info["num_gender"];
					$search_result[$i]["image"] =  $photo_folder."/".$gender_info["icon_name"];
				}

				$search_result[$i]["viewprofile_link"] =  $config["server"].$config["site_root"]."/viewprofile.php?id=".$search_result[$i]["id_ad"];
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


			if (sizeof($search_result)>0){
				$to_send["search_result"] = $search_result;
				$email_to_name = $to_send["fname"]." ".$to_send["sname"];

				if ( $to_send["id"]>0 ) {
					if ( (!$use_pilot_module_sms_notifications) || ($use_pilot_module_sms_notifications && $to_send["email_subscribe"])){
						SendMail($to_send["email"], $site_mail, $mail_content["subject"], $to_send, $mail_content, "mail_mailbox_match", '', $email_to_name, $mail_content["site_name"], 'text');					
						$send_mails++;
					}
					//SMS-notification
					if ($use_pilot_module_sms_notifications && $to_send["sms_subscribe"]){
						$sms_settings = GetSmsSettings();
						if ($sms_settings["use"]){
							$user_sms_data = GetUserSmsData($id_user, 1);
							$sms_text = str_replace(array("{USER_NAME}", "{SITE_NAME}"),array($email_to_name, $mail_content["site_name"]),GetSmsText(1));
							if ($user_sms_data && ($user_sms_data["sms_balance"] != 0)){
								SendSms($user_sms_data["phone"], $sms_text, $id_user, 1);
							}
						}
					}
					
					//end of SMS-notification
				}
			}
		}

		$rs_main->MoveNext();
	}
}
$fp = fopen( "cron_match_log.txt" , "a+" );
fwrite($fp, "\n Date:".date("Y-m-d H:i:s"));
fwrite($fp, "\n send: $send_mails");
fclose($fp);

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