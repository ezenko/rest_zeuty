<?php
/**
* This file is in return url parameter, which sends to payment system. Billing user account updates here.
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:30 $
**/

include "./config.php";
include "../common.php";
include "./functions_index.php";
include "./functions_common.php";
include "./class.lang.php";
include "./functions_xml.php";
include "./functions_mail.php";

if(!$_REQUEST["sel"]){
	echo "<script>location.href='".$config["site_root"]."/account.php'</script>";
	exit;
} else {
	//в зависимости от платежки подключается соответ. модуль ПС и вызывается из нее функция getPaymentValue
	$sys = "systems/functions/".$_GET["sel"].".php";
	include_once $sys;
	$count    = getPaymentValue("count", $_POST);
	$currency = getPaymentValue("curency", $_POST);
	$date     = getPaymentValue("date", $_POST);
	$status   = getPaymentValue("status", $_POST);
	$id_req   = getPaymentValue("id_req", $_POST);
	$quantity = getPaymentValue("quantity", $_POST);

	UpdateAccount($count, $currency, $quantity, $date, $status, $id_req);
}

echo "<script>location.href='".$config["site_root"]."/account.php'</script>";

function UpdateAccount($count, $currency, $quantity, $date, $status, $id_req){
	global $dbconn;

	if(intval($id_req) == 0){
		return "";
	}
	///// settings
	$rs = $dbconn->Execute("Select value from ".SETTINGS_TABLE." where name='site_unit_costunit'");
	$settings["site_unit_costunit"] = $rs->fields[0];

	$strSQL = "SELECT id_user, count_units, count_curr,currency, date_send,
			   status, paysystem, id_product, id_group
			   FROM ".BILLING_REQUESTS_TABLE." where id='".$id_req."'";
	$rs = $dbconn->Execute($strSQL);

	$row = $rs->GetRowAssoc(false);
	$data["id_user"] = $row["id_user"];
	$data["count_curr"] = $row["count_curr"];
	$data["currency"] = $row["currency"];
	$data["date_send"] = $row["date_send"];
	$data["status"] = $row["status"];
	$data["paysystem"] = $row["paysystem"];
	$data["count_unit"] = $row["count_units"];
	$data["id_product"] = $row["id_product"];
	$data["id_group"] = $row["id_group"];

	if(!$data["id_user"] || $data["status"]!="send" || !$data["paysystem"]){
		return "";
	}
	if($status != "1"){	// if not Pending
		$strSQL = "update ".BILLING_REQUESTS_TABLE." set status='fail' where id='".$id_req."'";
		$rs = $dbconn->Execute($strSQL);
		return "";
	}else{
		$strSQL = "update ".BILLING_REQUESTS_TABLE." set status='approve' where id='".$id_req."'";
		$rs = $dbconn->Execute($strSQL);
	}
	if($count !=$data["count_curr"] ){
		return "";
	}

	///// update user account
	$rs = $dbconn->Execute("SELECT account from ".BILLING_USER_ACCOUNT_TABLE." where id_user='".$data["id_user"]."'");
	if  ( $rs->RowCount() >0 ) {
		$user_account = round(($rs->fields[0] + $data["count_curr"]), 2);
		$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account= '".$user_account."', date_refresh=now(), is_send='0' WHERE id_user='".$data["id_user"]."'");
	} else {
		$user_account = $data["count_curr"];
		$dbconn->Execute("INSERT INTO ".BILLING_USER_ACCOUNT_TABLE." (id_user, account, date_refresh, is_send) VALUES ('".$data["id_user"]."', '".$user_account."', now(), '0')");
	}
	/**
	 * Send mail to user
	 */
	$strSQL = "SELECT email, fname, sname, lang_id FROM ".USERS_TABLE." WHERE id='".$data["id_user"]."'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$user["email"] = $row["email"];
	$user["lang_id"] = $row["lang_id"];

	$data["name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
	$data["add_on_account"] = $data["count_curr"];
	$data["account"] = $user_account;

	$settings = GetSiteSettings(array('site_email', 'site_unit_costunit'));

	$strSQL = " SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='".$settings["site_unit_costunit"]."' ";
	$rs = $dbconn->Execute($strSQL);
	$data["cur"] = $rs->fields[0];

	$mail_content = GetMailContentReplace("mail_content_money_add", GetUserLanguageId($user["lang_id"]));

	SendMail($user["email"], $settings["site_email"], $mail_content["subject"], $data, $mail_content, "mail_money_add_table", '', $data["name"], $mail_content["site_name"], 'text');


	$_SESSION["thanks"] = 1;
	return "1";
}

?>