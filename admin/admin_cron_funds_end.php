<?php
/**
* Cron file for sending notification for users which are about to run out of funds on their account.
*
* @package RealEstate
* @subpackage Admin Mode
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

$send_mails = 0;
$threshold = $settings_manager->GetSiteSettings('account_threshold');

$strSQL = "SELECT a.id_user, a.account, u.fname, u.sname, u.lang_id, u.email ".
		  "FROM ".BILLING_USER_ACCOUNT_TABLE." a ".
		  "LEFT JOIN ".USERS_TABLE." u ON u.id=a.id_user ".
		  "WHERE a.is_send='0' AND a.account<='$threshold'";
$rs = $dbconn->Execute($strSQL);

if ($rs->RowCount() > 0) {
	$site_mail = $settings_manager->GetSiteSettings('site_email');
	$mail_content_lang = array();
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);

		$mail_lang_id = GetUserLanguageId($row["lang_id"]);
		if (!isset($mail_content_lang[$mail_lang_id])) {
			$mail_content_lang[$mail_lang_id] = GetMailContentReplace("mail_content_money_end", $mail_lang_id);
		}
		$mail_content = $mail_content_lang[$mail_lang_id];

		$data["name"] = $row["fname"]." ".$row["sname"];
		$err = SendMail($row["email"], $site_mail, $mail_content["subject"], $data, $mail_content, "mail_money_end_table", '', $data["name"] , $mail_content["site_name"], 'text');
		if (!$err) {
			/**
			 * mail was send
			 */
			$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET is_send=1 WHERE id_user='{$row["id_user"]}'");
			$send_mails++;
		}
		$rs->MoveNext();
	}
}

/**
 * write to log
 */
$fp = fopen( "cron_funds_end_log.txt" , "a+" );
fwrite($fp, "\n Date:".date("Y-m-d H:i:s"));
fwrite($fp, "\n send: $send_mails");
fclose($fp);
?>