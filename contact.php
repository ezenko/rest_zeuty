<?php
/**
* Contact us page
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.4 $ $Date: 2008/10/15 07:18:46 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";
include "./include/class.lang.php";
include "./include/class.calendar_event.php";

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

$multi_lang = new MultiLang($config, $dbconn);

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";

if ($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	if ($_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
}
if (($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

switch($sel){
	case "contact": 		ContactSend(); break;
	case "agency_send": 	AgencySend(); break;
	case "fse": 			ListTableContact("", "from_searche"); break;
	case "fsv": 			ListTableContact("", "from_searchv"); break;
	case "for_agency":		ListTableContact("","for_agency"); break;
	case "contact_user":	ContactUser(); break;
	default: 				ListTableContact(); break;
}

function ListTableContact($err="", $par="", $data=""){
	global $config, $smarty, $dbconn, $user, $lang;

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	IndexHomePage("contact");

	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"contact.php";

	$form["action"] = $file_name;
	if ($err){
		GetErrors($err);
	}
	
	if ($err == "email_was_sent" && $par == "from_searchv") {
		$par = "";
		$smarty->assign("hide_contact_form", 1);
	}
			
	$form["hidden"] = "<input type=hidden name=sel value=contact>";

	$data["name"] = (isset($_POST["name"]) && !empty($_POST["name"])) ? trim(strip_tags($_POST["name"])) : "" ;
	$data["email"] = (isset($_POST["email"]) && !empty($_POST["email"])) ? trim(strip_tags($_POST["email"])) : "" ;
	$data["subject"] = (isset($_POST["subject"]) && !empty($_POST["subject"])) ? trim(strip_tags($_POST["subject"])) : "" ;
	$data["body"] = (isset($_POST["body"]) && !empty($_POST["body"])) ? trim(strip_tags($_POST["body"])) : "" ;
	$data["complaint_reason"] = (isset($_POST["complaint_reason"]) && !empty($_POST["complaint_reason"])) ? intval($_POST["complaint_reason"]) : 0;
	$data["your_comment"] = (isset($_POST["your_comment"]) && !empty($_POST["your_comment"])) ? strip_tags(trim($_POST["your_comment"])) : 0;
	
	if ($user[3] == 1) {
		$data["name"] = (isset($data["name"])) ? $data["name"] : "";
		$data["email"] = (isset($data["email"])) ? $data["email"] : "";		
	} else {
		if ( $par=='from_searchv') {
			$section = (isset($_REQUEST["section"]) && !empty($_REQUEST["section"])) ? intval($_REQUEST["section"]) : "";
			$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;
			$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
			
			$strSQL = "SELECT u.fname FROM ".USERS_TABLE." u  where  u.id='".$id."'";
			$rs = $dbconn->Execute($strSQL);
			$data["subject"] = $rs->fields[0];
			$strSQL = "SELECT u.fname, u.email FROM ".USERS_TABLE." u  where  u.id='".$user[0]."'";
			$rs = $dbconn->Execute($strSQL);
			$data["name"] = (isset($data["name"])) ? $data["name"] : $rs->fields[0];
			$data["email"] = (isset($data["email"])) ? $data["email"] : $rs->fields[1];
			$data["href"] = $config["server"].$config["site_root"]."/viewprofile.php?id=".$id_ad;		
		} elseif ($par=='from_searche') {
			$id_event = intval($_GET["id_event"]);
			$strSQL = "SELECT u.fname FROM ".USERS_TABLE." u  where  u.id='".intval($_GET["id_user"])."'";
			$rs = $dbconn->Execute($strSQL);
			$data["subject"] = $rs->fields[0];
			$strSQL = "SELECT u.fname, u.email FROM ".USERS_TABLE." u  where  u.id='".$user[0]."'";
			$rs = $dbconn->Execute($strSQL);
			$data["name"] = (isset($data["name"])) ? $data["name"] : $rs->fields[0];
			$data["email"] = (isset($data["email"])) ? $data["email"] : $rs->fields[1];
			$data["href"] = $config["server"]."/events.php?sel=view_event&amp;id_event=".$id_event;
		} else {
			$strSQL = "SELECT MAX(id) FROM ".RENT_ADS_TABLE." WHERE status='1'";
			$rs = $dbconn->Execute($strSQL);
			$show_url = "./viewprofile.php?id=".$rs->fields[0];
			$smarty->assign("show_url", $show_url);
	
			$strSQL = "SELECT u.fname, u.email, u.user_type, u.phone FROM ".USERS_TABLE." u WHERE  u.id='".$user[0]."'";
			$rs = $dbconn->Execute($strSQL);
			$data["name"] = (isset($_REQUEST["company_contact_person"])) ? $_REQUEST["company_contact_person"] : $rs->fields[0];
			$data["email"] = (isset($_REQUEST["company_email"])) ? $_REQUEST["company_email"] : $rs->fields[1];
			$data["user_type"] = $rs->fields[2];
			$data["phone"] = (isset($_REQUEST["company_phone"])) ? $_REQUEST["company_phone"] : $rs->fields[3];
			if ($data["user_type"] == 2){
				$strSQL = " SELECT company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user[0]."' ";
				$rs = $dbconn->Execute($strSQL);
				$row = $rs->GetRowAssoc(false);
				$data["company_name"] = (isset($_REQUEST["company_name"])) ? $_REQUEST["company_name"] : stripslashes($row["company_name"]);
				$data["company_url"] = (isset($_REQUEST["company_url"])) ? $_REQUEST["company_url"] :stripslashes($row["company_url"]);
				$data["company_rent_count"] = (isset($_REQUEST["company_rent_count"])) ? $_REQUEST["company_rent_count"] :stripslashes($row["company_rent_count"]);
				$data["company_how_know"] = (isset($_REQUEST["company_how_know"])) ? $_REQUEST["company_how_know"] :stripslashes($row["company_how_know"]);
				$data["company_quests_comments"] = (isset($_REQUEST["company_quests_comments"])) ? $_REQUEST["company_quests_comments"] :stripslashes($row["company_quests_comments"]);
			}
		}
	}		
	$smarty->assign("form", $form);
	$smarty->assign("data", $data);
	$smarty->assign("par", $par);
	$smarty->assign("file_name", $file_name);

	$smarty->display(TrimSlash($config["index_theme_path"])."/contact_table.tpl");
	exit;
}

function ContactSend(){
	global $config, $smarty, $dbconn, $user;
	
	$from = (isset($_POST["from"])) ? $_POST["from"] : "";	
	switch ($from){
		case "complaint":
			$data["name"] = strip_tags(trim($_POST["name"]));
			$data["email"] = strip_tags(trim($_POST["email"]));
			$data["subject"] = strip_tags(trim($_POST["subject"]));
			$data["complaint_reason"] = intval($_POST["complaint_reason"]);
			$data["your_comment"] = strip_tags(trim($_POST["your_comment"]));

			if ( ( strlen($data["name"])<1 ) || ( strlen($data["email"])<1 ) || ( strlen($data["subject"])<1) ) {
				ListTableContact("empty_fields","from_searchv");
				exit;
			}
			//check captcha
			if(!(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring'])) {		
				ListTableContact("invalid_keystring","from_searchv");		
				exit;
			}
			unset($_SESSION['captcha_keystring']);				
			if ( ( BadWordsCont($data["name"])=='badword' ) || ( BadWordsCont($data["email"])=='badword' ) || ( BadWordsCont($data["subject"])=='badword' ) || ( BadWordsCont($data["complaint_reason"])=='badword' ) || ( BadWordsCont($data["your_comment"])=='badword' )	) {
				ListTableContact("badword","from_searchv");
				exit;
			}
			$site_mail = GetSiteSettings("site_email");
			$mail_content = GetMailContentReplace("mail_content_contact_complain", GetAdminLanguageId());
			$data["complaint_reason"] = $mail_content["complaint_".$data["complaint_reason"]];
			$data["href"] = $_POST["href"];

			$err = SendMail($site_mail, $data["email"], $mail_content["subject"], $data, $mail_content, "mail_contact_complain", "", GetAdminName(), $data["name"], "text");
			if(!$err){
				$err = "email_was_sent";
			} else {
				$err = "mail_error";
			}
			ListTableContact($err, "from_searchv");
			exit;
			break;
		default:
			$name = $_POST["name"];
			$email = $_POST["email"];
			$subject = strip_tags($_POST["subject"]);
			$body = strip_tags($_POST["body"]);

			if(!strlen($name) || !strlen($subject) || !strlen($body)){
				$err = "empty_fields";
				ListTableContact($err);
				exit;
			}
			//check captcha
			if(!(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring'])) {		
				ListTableContact("invalid_keystring","from_search");		
				exit;
			}
			unset($_SESSION['captcha_keystring']);
			if ( BadWordsCont($name) || BadWordsCont($email) || BadWordsCont($subject) || BadWordsCont($body) ) {
				ListTableContact("badword");
				exit;
			}

			$data["m_subject"] = $subject;
			$data["body"] = $body;
			$site_mail = GetSiteSettings("site_email");
			$mail_content = GetMailContentReplace("mail_content_contact_form", GetAdminLanguageId());

			$err = SendMail($site_mail, $email, $mail_content["subject"], $data, $mail_content, "mail_contact_form", "", GetAdminName(), $name, "text");
			if(!$err){
				$err = "email_was_sent";
				$_POST["name"] = "";
				$_POST["email"] = "";
				$_POST["subject"] = "";
				$_POST["body"] = "";
			} else {
				$err = "mail_error";
			}
			ListTableContact($err);
			exit;
			break;
	}
}

function AgencySend(){
	global $config, $smarty, $dbconn, $user;
	$data["company_name"] = strip_tags($_POST["company_name"]);
	$data["company_contact_person"] = strip_tags($_POST["company_contact_person"]);
	$data["company_phone"] = strip_tags($_POST["company_phone"]);
	$data["company_email"] = strip_tags($_POST["company_email"]);

	$data["company_url"] = strip_tags($_POST["company_url"]);
	$data["company_rent_count"] = strip_tags($_POST["company_rent_count"]);
	$data["company_how_know"] = strip_tags($_POST["company_how_know"]);
	$data["company_quests_comments"] = strip_tags($_POST["company_quests_comments"]);

	if ( 	( BadWordsCont($data["company_name"])=='badword' ) ||
	( BadWordsCont($data["company_contact_person"])=='badword' ) ||
	( BadWordsCont($data["company_phone"])=='badword' ) ||
	( BadWordsCont($data["company_email"])=='badword' ) ||
	( BadWordsCont($data["company_url"])=='badword' ) ||
	( BadWordsCont($data["company_rent_count"])=='badword' ) ||
	( BadWordsCont($data["company_how_know"])=='badword' ) ||
	( BadWordsCont($data["company_quests_comments"])=='badword' )
	)
	{
		ListTableContact("badword","for_agency",$data);
		exit;
	}
	//check captcha
	if(!(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring'])) {		
		ListTableContact("invalid_keystring","for_agency",$data);		
		exit;
	}

	$site_mail = GetSiteSettings("site_email");
	$mail_content = GetMailContentReplace("mail_content_contact_agency", GetAdminLanguageId());

	$err = SendMail($site_mail, $data["company_email"], $mail_content["subject"], $data, $mail_content, "mail_contact_agency", "", GetAdminName(), $data["company_name"], "text");

	ListTableContact("email_was_sent","for_agency","");
	return;
}

function ContactUser(){
	global $config, $smarty, $dbconn, $multi_lang, $REFERENCES;

	$err = 0;
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"contact.php";

	IndexHomePage("contact");

	$id_user = (isset($_REQUEST["id_user"]) && !empty($_REQUEST["id_user"])) ? intval($_REQUEST["id_user"]) : false;
	$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : false;

	if (!$id_user || !$id_ad) {
		ListTableContact();
	}

	/**
	 * get user and listing info
	 */

	$send = (isset($_REQUEST["send"]) && !empty($_REQUEST["send"])) ? intval($_REQUEST["send"]) : false;
	if ($send) {
		$data["name"] = strip_tags(trim($_REQUEST["name"]));
		$data["email"] = strip_tags(trim($_REQUEST["email"]));
		$data["body"] = strip_tags(trim($_REQUEST["body"]));

		//check captcha
		if(!(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring'])) {		
			GetErrors("invalid_keystring");
			$err++;
		}
		unset($_SESSION['captcha_keystring']);
		
		if ( BadWordsCont($data["name"]) || BadWordsCont($data["email"]) || BadWordsCont($data["body"]) ) {
			GetErrors("badword");
			$err++;
		}
			
		if ($err == 0) {
			/**
			 * send mail
			 */
			$strSQL = "SELECT fname, sname, email, lang_id FROM ".USERS_TABLE." WHERE id='$id_user'";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
	
			$data["to_user_name"] = $row["fname"]." ".$row["sname"];
			$email_to = $row["email"];
	
			$mail_content = GetMailContentReplace("mail_content_contact_user", GetUserLanguageId($row["lang_id"]));
	
			$data["href"] = $config["server"].$config["site_root"]."/viewprofile.php?id=$id_ad";
	
			//interested in listing
			$err = SendMail($email_to, $data["email"], $mail_content["subject"], $data, $mail_content, "mail_contact_user", "", $data["to_user_name"], $data["name"], "text");
			GetErrors("email_was_sent");
		}	
	}

	$smarty->assign("profile", GetContactAd($id_ad, $id_user) );
	$form["hidden"] = " <input type=hidden name=sel value=contact_user>
						<input type=hidden name=send value=1>
						<input type=hidden name=id_user value=$id_user>
						<input type=hidden name=id_ad value=$id_ad>";

	$smarty->assign("form", $form);
	$smarty->assign("data", $data);

	$smarty->display(TrimSlash($config["index_theme_path"])."/contact_user.tpl");
	return;
}
?>