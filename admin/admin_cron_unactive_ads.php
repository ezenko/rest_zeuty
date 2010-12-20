<?php
/**
* Cron file for ads unactivating, if active period comes to end and activity period is used on the site.
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:24 $
**/

include "../include/config.php";
include "../common.php";
include "../include/class.settings_manager.php";
include "../include/functions_index.php";
include "../include/functions_common.php";
include "../include/functions_xml.php";
include "../include/functions_mail.php";

$settings_manager = new SettingsManager();

$use_ads_activity_period = $settings_manager->GetSiteSettings('use_ads_activity_period');

if ($use_ads_activity_period == 1) {
	$strSQL = "SELECT DISTINCT ra.id as id_ad, ut.id as id_user, ut.fname, ut.sname, ut.lang_id, ut.email  ".
		  	  "FROM ".USERS_TABLE." ut ".
		  	  "LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=ut.id ".
		  	  "WHERE ut.active='1' AND ra.status='1' AND ".
		  	  "ra.sold_leased_status='0' AND (UNIX_TIMESTAMP(ra.date_unactive) < UNIX_TIMESTAMP())";
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->RowCount();

	/**
	 * Unactivate ads
	 */
	$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET status='0' WHERE status='1' AND sold_leased_status='0' AND UNIX_TIMESTAMP(date_unactive) < UNIX_TIMESTAMP();");

	/**
	 * Send email to ads owner
	 */
	if ($count > 0) {
		$site_mail = $settings_manager->GetSiteSettings('site_email');
		while (!$rs->EOF) {
			$row = $rs->GetRowAssoc( false );
			$to_send = array();
			$to_send["id_ad"] = $row["id_ad"];
			$to_send["fname"] = stripslashes($row["fname"]);
			$to_send["sname"] = stripslashes($row["sname"]);
			$to_send["email"] = $row["email"];
			$to_send["lang_id"] = $row["lang_id"];

			$mail_content = GetMailContentReplace("mail_content_unactivate_ad", GetUserLanguageId($to_send["lang_id"]));//xml
			SendMail($to_send["email"], $site_mail, $mail_content["subject"], $to_send, $mail_content, "mail_unactivate_ad", '', $to_send["fname"]." ".$to_send["sname"], $mail_content["site_name"], 'text');

			$rs->MoveNext();
		}
	}
}

/**
 * write to log
 */
$fp = fopen( "cron_unactive_ads_log.txt" , "a+" );
fwrite($fp, "\n Date:".date("Y-m-d H:i:s"));
fwrite($fp, "\n unactivated: $count");
fclose($fp);
?>