<?php
/**
* Cron file for users congratulation with birthday.
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
$site_mail = $settings_manager->GetSiteSettings('site_email');

$strSQL = "SELECT fname, sname, lang_id, email FROM ".USERS_TABLE." ".
	  	  "WHERE active='1' AND status='1'AND ".
	  	  "(DATE_FORMAT(date_birthday, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d'))";
$rs = $dbconn->Execute($strSQL);
$count = $rs->RowCount();
if ($count >0) {	
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc( false );
		$data = array();		
		$data["name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
		
		$data["email"] = $row["email"];
		$data["lang_id"] = $row["lang_id"];

		$mail_lang_id = GetUserLanguageId($data["lang_id"]);
		if (!isset($mail_content_lang[$mail_lang_id])) {
			$mail_content_lang[$mail_lang_id] = GetMailContentReplace("mail_content_birthday", $mail_lang_id);
		}
		$mail_content = $mail_content_lang[$mail_lang_id];

		SendMail($data["email"], $site_mail, $mail_content["subject"], $data, $mail_content, "mail_birthday_table", '', $data["name"], $mail_content["site_name"], 'text');
		$rs->MoveNext();
	}
}


/**
 * write to log
 */

$fp = fopen( "cron_birthday_log.txt" , "a+" );
fwrite($fp, "\n Date:".date("Y-m-d H:i:s"));
fwrite($fp, "\n congratulated: $count");
fclose($fp);

?>