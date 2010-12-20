<?php
/**
* Lost password page (load for and send password
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.2 $ $Date: 2008/10/14 14:07:43 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";

$user = auth_index_user();

$sel=(isset($_POST["sel"])) ? $_POST["sel"] : ((isset($_GET["sel"])) ? $_GET["sel"] : "");

switch($sel){
	case "send": SendPassw(); break;
	default: 	LostPassForm();
}

function SendPassw(){
	global $smarty, $config, $dbconn, $user;

	$smarty->assign("mail_template_root", $config["index_theme_path"]);

	$email = $_POST["email"];
	$strSQL = "SELECT  id, login, fname, sname, lang_id FROM ".USERS_TABLE." WHERE email='".$email."'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$count = $rs->RowCount();
	$id = $row["id"];
	$login = $row["login"];
	$fname = $row["fname"];
	$sname = $row["sname"];
	$lang_id = $row["lang_id"];

	if(!strval($email) || $count != 1){
		LostPassForm('error_not_email');
		return;
	}

	$new_pass = substr(md5(time()),0,6);
	$dbconn->Execute("UPDATE ".USERS_TABLE." SET password='".md5($new_pass)."' WHERE id='".$id."'");

	//// send registration data to user on reg email
	$cont_arr["fname"] = $fname;
	$cont_arr["sname"] = $sname;
	$cont_arr["login"] = $login;
	$cont_arr["pass"] = $new_pass;
	$cont_arr["email"] = $email;
	$cont_arr["site"] = $config["server"];

	$site_mail = GetSiteSettings("site_email");

	$email_to_name = $cont_arr["fname"]." ".$cont_arr["sname"];

	$mail_content = GetMailContentReplace("mail_content_lost_pass", GetUserLanguageId($lang_id));//xml
	$subject = $mail_content["subject"];

	SendMail($email, $site_mail, $subject, $cont_arr, $mail_content, "mail_lost_pass", '', $email_to_name, $mail_content["site_name"] );

	LostPassForm("success_lost_pass_email");
	return;
}


function LostPassForm($err=""){
	global $smarty, $config, $dbconn, $lang;
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"lost_pass.php";

	IndexHomePage("lost_pass");
	if (isset($err)) {
		GetErrors($err);
	}

	$form["login_link"] = "./login.php";
	$form["contact_link"] = "./contact.php";
	$form["action"] = $file_name;
	$form["hidden"] = "<input type=hidden name=sel value=send>";

	if ($err == 'success_lost_pass_email'){
		$smarty->assign("sended", 1);
	}
	$smarty->assign("form", $form);
	$smarty->display(TrimSlash($config["index_theme_path"])."/lost_pass_table.tpl");
	exit;
}

?>