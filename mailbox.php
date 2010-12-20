<?php
/**
* Mailbox page (mailbox works like IM)
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.12 $ $Date: 2009/01/14 14:17:16 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_common.php";
if (in_array("mhi_my_messages", $config["mode_hide_ids"])) {		
	HidePage();
	exit;
}
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";

$from_admin_mode = (isset($_REQUEST["from_admin_mode"]) && ($_REQUEST["from_admin_mode"]) == 1) ? 1 : 0;  
$smarty->assign("from_admin_mode", $from_admin_mode);
  
if ($from_admin_mode) {
	include "./include/functions_admin.php";
} else {
	include "./include/functions_index.php";
}
if (GetSiteSettings("use_pilot_module_newsletter")){
	include "./include/functions_newsletter.php";
}			
if (GetSiteSettings("use_pilot_module_sms_notifications")){
	include "./include/functions_sms_notifications.php";
}

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

@$sel = $_POST["sel"]?$_POST["sel"]:$_GET["sel"];

$smarty->assign("from_admin_mode", $from_admin_mode);
$smarty->assign("index_theme_path", $config["index_theme_path"]);

if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

switch ($sel){
	case "inbox":			MyUsers(); break;
	case "chat_start":		ChatStart(); break;
	case "chat":			ChatWithUser(); break;
	case "send_message":	ChatSend(); break;
	case "clear_history":	ClearHistory(); break;	
	default: 				MyUsers(); break;
}

function MyUsers() {
	global $config, $smarty, $dbconn, $user, $lang, $from_admin_mode;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "mailbox.php";
	if ($from_admin_mode) {
		IndexAdminPage('mailbox', 1);
		CreateMenu('admin_lang_menu');
		$smarty->assign("add_to_lang", "&from_admin_mode=1&sel=inbox");
	} else {	
		IndexHomePage('mailbox');
		$smarty->assign("add_to_lang", "&sel=inbox");
	}	
			
	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
//	$left_links = GetLeftLinks("homepage.php");
//	$smarty->assign("left_links", $left_links);
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$list_user = array();
	$list_user = getMailboxList();
			
	if (sizeof($list_user)>0) {
		$smarty->assign("list_user", $list_user);
	}

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/mailbox_inbox.tpl");
	exit;
}

function ChatStart($err = ""){
	global $config, $smarty, $dbconn, $user, $lang, $from_admin_mode;
			
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "mailbox.php";

	if ($from_admin_mode) {
		IndexAdminPage('mailbox', 1);		
	} else {	
		IndexHomePage('mailbox');
	}	

	$user_id = intval($_REQUEST["user_id"]);
	
	$messages = getMessages($user_id);

	$user_info = GetUserInfo($user_id);

	$smarty->assign("self_info", GetUserAccountInfo($user[0]));
	
	if (sizeof($user_info)>0){
		$smarty->assign("user_info", $user_info);
	}
	if ($err) {
		GetErrors($err);
	}
	$smarty->assign("messages", $messages);
	$smarty->assign("user_id", $user_id);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/mailbox_dialog.tpl");
}

function ChatWithUser(){
	global $config, $smarty, $dbconn, $user, $lang;
	IndexHomePage('mailbox');

	$user_id = (isset($_REQUEST["user_id"]) && !empty($_REQUEST["user_id"])) ? intval($_REQUEST["user_id"]) : 0;	
	$messages = getMessages($user_id);

	$smarty->assign("messages", $messages);
	$smarty->assign("user_id", $user_id);
	$smarty->assign("current_user_id", $user[0]);	
	$smarty->display(TrimSlash($config["index_theme_path"])."/mailbox_messages.tpl");
	exit;
}

function ChatSend(){
	global $config, $smarty, $dbconn, $user, $lang;
	IndexHomePage('mailbox');
	$user_id = $_POST["user_id"]?$_POST["user_id"]:$_GET["user_id"];
	$user_id = intval($user_id);
	if (!isUserInList( $user_id )){
		addUserToMailbox( $user_id );
	}				
	$update_err = "";
	if ($update_err != "") {
		ChatStart($update_err);
		exit();		
	}
	$body = strip_tags(trim($_POST["body"]));
	if (BadWordsCont($body)) {
		ChatStart("mailbox_badword");
		exit();
	}
	
	if ($body != '') {
		$strSQL = "INSERT INTO ".MAILBOX_MESSAGES_TABLE." (from_user_id, to_user_id, timestamp, body)
				   VALUES('".$user[0]."', '".$user_id."', NOW(), '".addslashes(htmlspecialchars($body))."')";
		$dbconn->Execute($strSQL);

		$rs = $dbconn->Execute("SELECT id, fname, sname, email FROM ".USERS_TABLE." WHERE id='".$user[0]."' ");
		$row = $rs->GetRowAssoc(false);
		$data["id"] = $row["id"];
		$data["login"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
		$use_pilot_module_sms_notifications = GetSiteSettings("use_pilot_module_sms_notifications");
		if ($use_pilot_module_sms_notifications){
			$strSQL = "	SELECT DISTINCT b.id AS id_user, b.email, b.fname, b.sname, b.lang_id, a.id_user AS email_subscribe, sms_u.id_user AS sms_subscribe 
						FROM ".USERS_TABLE." b
						LEFT JOIN ".SUBSCRIBE_USER_TABLE." a ON b.id=a.id_user 
						LEFT JOIN ".SMS_NOTIFICATIONS_USER_EVENT." sms_u ON sms_u.id_user=b.id 
						WHERE ( (a.type='s' AND a.id_subscribe='2') OR sms_u.id_subscribe='2') AND b.id='".$user_id."'
						GROUP BY b.id";
		}else{				
			$strSQL = "	SELECT DISTINCT a.id_user, b.email, b.fname, b.sname, b.lang_id
						FROM ".SUBSCRIBE_USER_TABLE." a, ".USERS_TABLE." b
						WHERE a.type='s' AND a.id_subscribe='2' AND b.id=a.id_user AND b.id='".$user_id."'
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

		$email_to_name = $id_user["fname"]." ".$id_user["sname"];
		$data["to_name"] = $email_to_name;
		$data["unsubscribe_possible"] = 1;
		if ($use_pilot_module_sms_notifications){
			$id_user["email_subscribe"] = $row["email_subscribe"];
			$id_user["sms_subscribe"] = $row["sms_subscribe"];
		}

		$site_mail = GetSiteSettings('site_email');
		$mail_content = GetMailContentReplace("mail_content_newmailbox", GetUserLanguageId($id_user["lang_id"]));//xml

		if ( $id_user["id"]>0 ) {
			if ( (!$use_pilot_module_sms_notifications) || ($use_pilot_module_sms_notifications && $id_user["email_subscribe"])){
				SendMail($id_user["email"], $site_mail, $mail_content["subject"], $data, $mail_content, "mail_mailbox_subscr_table", '', $email_to_name, $mail_content["site_name"], "text");										
			}
			//SMS-notification
			if ($use_pilot_module_sms_notifications && $id_user["sms_subscribe"]){
				$sms_settings = GetSmsSettings();
				if ($sms_settings["use"]){
					$user_sms_data = GetUserSmsData($user_id, 2);
					$sms_text = str_replace(array("{USER_NAME}", "{SITE_NAME}"),array($email_to_name, $mail_content["site_name"]),GetSmsText(2));
					if ($user_sms_data && ($user_sms_data["sms_balance"] != 0)){
						SendSms($user_sms_data["phone"], $sms_text, $user_id, 2);
					}
				}
			}
			
			//end of SMS-notification
		}
		
	}
	ChatStart();
	return;
}

function getMailboxList($is_ignored = false){
	global $config, $smarty, $dbconn, $user, $lang, $REFERENCES, $from_admin_mode;

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = " SELECT distinct mul.list_user_id
				FROM ".MAILBOX_USER_LISTS_TABLE." mul
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=mul.list_user_id
				LEFT JOIN ".BLACKLIST_TABLE." bt ON bt.id_user=mul.list_user_id AND bt.id_enemy='".$user[0]."'
				WHERE mul.user_id='".$user[0]."' AND ut.guest_user='0' ";
	if ($is_ignored){
		$strSQL .= " AND mul.is_ignored='1' ";
	} else {
		$strSQL .= " AND mul.is_ignored='0' ";
	}
	$strSQL .= " GROUP BY mul.list_user_id ";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->RowCount();
	$smarty->assign("num_records", $num_records);
	
	$users_numpage = GetSiteSettings("users_num_page");
	
	$lim_min = ($page-1)*$users_numpage;
	$lim_max = $users_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;
	if ($num_records>0){
		$strSQL = " SELECT DISTINCT mul.list_user_id, ut.fname, ut.sname, ut.user_type, UNIX_TIMESTAMP(ut.date_last_seen) as date_seen, bt.id as black_id, bt_user.id as user_in_blacklist
					FROM ".MAILBOX_USER_LISTS_TABLE." mul
					LEFT JOIN ".USERS_TABLE." ut ON ut.id=mul.list_user_id
					LEFT JOIN ".BLACKLIST_TABLE." bt ON bt.id_user=mul.list_user_id AND bt.id_enemy='".$user[0]."'
					LEFT JOIN ".BLACKLIST_TABLE." bt_user ON bt_user.id_user=mul.user_id AND bt_user.id_enemy=mul.list_user_id
					LEFT JOIN ".MAILBOX_MESSAGES_TABLE." mmt ON (mmt.from_user_id=mul.list_user_id AND mmt.to_user_id='".$user[0]."')
					WHERE mul.user_id='".$user[0]."' AND ut.guest_user='0' ";

		if ($is_ignored){
			$strSQL .= " AND mul.is_ignored='1' ";
		} else {
			$strSQL .= " AND mul.is_ignored='0' ";
		}
		$strSQL .= " GROUP BY mul.list_user_id ORDER BY mmt.seen DESC ".$limit_str;
		$rs = $dbconn->Execute($strSQL);
		$list = array();
		$online_users = getOnlineUsers();
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);						
			$list_user = array();
			$list_user["user_id"] = $row["list_user_id"];
			$list_user["user_type"] = $row["user_type"];
			$list_user["black_id"] = $row["black_id"];
			$list_user["user_in_blacklist"] = $row["user_in_blacklist"];
			$list_user["date_seen"] = date("d.m.Y H:i", $row["date_seen"]);
			$list_user["name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
			$list_user["online_status"] = (in_array($list_user["user_id"], array_values($online_users))) ? 1 : 0 ;
			$list_user["new_messages"] = checkNewMessages($list_user["user_id"]);
			$list_user["num_messages"] = checkNumMessages($list_user["user_id"]);

			$list_user["mess_id"] = GetMessId($list_user["num_messages"]);

			if ($row["user_type"] == 2){
				$strSQL_company = " SELECT company_name FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$list_user["user_id"]."' ";
				$rs_name = $dbconn->Execute($strSQL_company);
				if ($rs_name->fields[0]){
					$list_user["company_name"] = stripslashes($rs_name->fields[0]);
				}
			}
			$str_query = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$list_user["user_id"]."'  AND status='1' ";
			$rs_ad = $dbconn->Execute($str_query);
			if ($rs_ad->fields[0]>0){
				if ($from_admin_mode) {
					$list_user["view_ad_link"] = $config["server"].$config["site_root"]."/admin/admin_users.php?sel=user_rent&id_user=".$list_user["user_id"];
					$list_user["view_user_link"] = $config["server"].$config["site_root"]."/admin/admin_users.php?sel=edit_user&id_user=".$list_user["user_id"];
				} else {	
					$list_user["view_ad_link"] = $config["server"].$config["site_root"]."/viewprofile.php?sel=more_ad&amp;id_user=".$list_user["user_id"]."&amp;section=rent";
				}	
			}

			$list_user["pict_path"] = "";
			if ($row["user_type"]==2) {
				$strSQL_logo = "SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$list_user["user_id"]."' AND admin_approve='1'";
				$rs_logo = $dbconn->Execute($strSQL_logo);
				if ( ($rs_logo->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/".$rs_logo->fields[0])) ){
					$list_user["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_logo->fields[0];
				}
			} else {
				$strSQL_pict = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$list_user["user_id"]."' AND status='1' AND admin_approve='1'";
				$rs_pict = $dbconn->Execute($strSQL_pict);
				if ( ($rs_pict->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/thumb_".$rs_pict->fields[0])) ){
					$list_user["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/thumb_".$rs_pict->fields[0];
				}
			}
			if ($list_user["pict_path"] == ""){
				$used_references = array("gender");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$name = GetUserGenderIds($arr["spr_user_table"], $list_user["user_id"], 0, $arr["val_table"]);
						$tmp_user[$arr["key"]] = $name;
					}
				}
				$gender_info = getDefaultUserIcon( $list_user["user_type"], $tmp_user["gender"]);
				$list_user["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$gender_info["icon_name"];
			}

			$list[$i] = $list_user;
			$i++;
			$rs->MoveNext();
		}
		$param = "mailbox.php?sel=inbox&amp;".($from_admin_mode ? "from_admin_mode=1&" : "");
		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $users_numpage));
	}
				
	return (isset($list)) ? $list : false;
}

function getOnlineUsers() {
	global $dbconn, $user;
	$out_arr = array();
	$strSQL = " SELECT ast.id_user
				FROM ".ACTIVE_SESSIONS_TABLE." ast, ".USERS_TABLE." ut
				WHERE ut.id=ast.id_user AND ut.guest_user='0' AND ut.id!='".$user[0]."'
				GROUP BY ast.id_user ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF){
		$out_arr[$i] = $rs->fields[0];
		$rs->MoveNext();
		$i++;
	}
	return $out_arr;
}

function checkNumMessages($user_id) {
	global $dbconn, $user;
	$strSQL = " SELECT COUNT(*) FROM ".MAILBOX_MESSAGES_TABLE." WHERE from_user_id='".$user_id."' AND to_user_id='".$user[0]."' AND clear_user_id<>'{$user[0]}'";
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->fields[0];
	return $count;
}

function checkNewMessages($user_id) {
	global $dbconn, $user;
	$strSQL = " SELECT COUNT(*) FROM ".MAILBOX_MESSAGES_TABLE." WHERE from_user_id='".$user_id."' AND to_user_id='".$user[0]."' AND seen='0' ";
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->fields[0];
	if ($count>0){
		return $count;
	} else {
		return false;
	}
}

function checkNewForCurrentUser() {
	global $dbconn, $user;
	$strSQL = "SELECT COUNT(*) FROM ".MAILBOX_MESSAGES_TABLE." WHERE to_user_id = '".$user[0]."' AND seen='0' ";
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->fields[0];
	return ($count == 0) ? 0 : $count;
}

function isUserInList( $user_id ){
	global $dbconn, $user;
	$strSQL = "SELECT COUNT(id) FROM ".MAILBOX_USER_LISTS_TABLE." WHERE user_id='".$user[0]."' AND list_user_id='".$user_id."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] > 0) {
		return true;
	} else {
		return false;
	}
}

function addUserToMailbox($add_user_id) {
	//if not in mailbox then add him to our and add user to his
	global $dbconn, $user;
	if (isUserInList( $add_user_id )){
		return -1;
	}
	$strSQL = " INSERT INTO ".MAILBOX_USER_LISTS_TABLE." (user_id, list_user_id) VALUES ('".$user[0]."', '".$add_user_id."') ";
	$dbconn->Execute($strSQL);

	$strSQL = " INSERT INTO ".MAILBOX_USER_LISTS_TABLE." (user_id, list_user_id) VALUES ('".$add_user_id."', '".$user[0]."') ";
	$dbconn->Execute($strSQL);
	return 1;
}

function getMessages($user_id) {
	global $config, $smarty, $dbconn, $user, $lang;

	$messages_number = 15;
	$strSQL = "SELECT UNIX_TIMESTAMP(update_date) FROM ".ACTIVE_SESSIONS_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$last_login = $rs->fields[0];

	$ten_minutes_ago = date("Y-m-d H:i:s", mktime(date("H"), date("i")-10));

	$strSQL = "SELECT COUNT(*) FROM ".MAILBOX_MESSAGES_TABLE."  WHERE ( (from_user_id = '".$user[0]."' AND to_user_id = '".$user_id."') OR (from_user_id = '".$user_id."' AND to_user_id = '".$user[0]."') ) AND clear_user_id<>'{$user[0]}'";
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->fields[0];

	if ($count == 0)
	return false;

	$message_num = (($count - $messages_number) < 0) ? 0 : ($count - $messages_number);

	$strSQL = "SELECT mm.from_user_id, mm.to_user_id, ".
			  "DATE_FORMAT(mm.timestamp,'".$config["date_format"]."') as timestamp, mm.body, ".
			  "ut.fname, ut.sname ".
			  "FROM ".MAILBOX_MESSAGES_TABLE." mm ".
			  "LEFT JOIN ".USERS_TABLE." ut ON mm.from_user_id=ut.id ".
			  "WHERE ( (mm.from_user_id = '".$user[0]."' AND mm.to_user_id = '".$user_id."') OR (mm.from_user_id = '".$user_id."' AND mm.to_user_id = '".$user[0]."') ) AND clear_user_id<>'{$user[0]}' ".
			  "ORDER BY mm.timestamp ASC LIMIT ".$message_num.",".$messages_number;
	$rs = $dbconn->Execute($strSQL);

	while ( !$rs->EOF ) {
		$row = $rs->GetRowAssoc(false);
		$message["from_user_id"] = $row["from_user_id"];
		$message["from_user_name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
		$message["to_user_id"] = $row["to_user_id"];
		$message["timestamp"] = $row["timestamp"];
		$message["body"] = stripslashes($row["body"]);
		$messages[] = $message;
		$rs->MoveNext();
	}

	$strSQL = "UPDATE ".MAILBOX_MESSAGES_TABLE." SET seen='1' WHERE to_user_id='".$user[0]."' AND from_user_id='".$user_id."' AND timestamp < NOW()";
	$dbconn->Execute($strSQL);
	return $messages;
}

function GetUserAccountInfo($user_id) {
	global $dbconn;	
	
	$strSQL = "SELECT fname, sname, email, phone FROM ".USERS_TABLE." WHERE id='$user_id'";
	$rs = $dbconn->Execute($strSQL);
	$user = array();
	if ($rs->RowCount() > 0) {
		$user = $rs->getRowAssoc(false);
		foreach ($user as $key=>$value) {
			$user[$key] = htmlspecialchars($value);
		}
	}
	return (count($user) > 0) ? $user : false;
}	

function UpdateUserAccountInfo($user_id, $is_root) {
	global $dbconn, $user;
	
	$error = "";
	$fname = trim(strip_tags($_POST["fname"]));
	$sname = trim(strip_tags($_POST["sname"]));
	$email = trim(strip_tags($_POST["email"]));
	if (!$is_root) {
		$login = $email;
	}
	$phone = trim(strip_tags($_POST["phone"]));

	if (!($fname && $sname && $email)) {
		$error = "empty_fields";
	}
	
	if ($error == "") {
		$strSQL = "UPDATE ".USERS_TABLE." SET ".
			      "fname='".addslashes($fname)."', sname='".addslashes($sname)."', ".
			      "email='".addslashes($email)."', ";
		if (!$is_root) {
			$strSQL .= "login='".addslashes($login)."', ";
		}
		$strSQL .= "phone='".addslashes($phone)."' WHERE id='$user_id'";
		$dbconn->Execute($strSQL);	
		if (GetSiteSettings("use_pilot_module_newsletter")) {
			UpdateNewsletterUserData($user_id, $fname, $sname, $email);
        }
	}	
	return $error;
}

function GetUserInfo($user_id){
	global $config, $smarty, $dbconn, $user, $lang, $REFERENCES, $from_admin_mode;
	$user_arr = "";
	$strSQL = " SELECT u.fname, u.sname, ast.id as status, u.user_type, UNIX_TIMESTAMP(u.date_last_seen) as date_seen, bt.id as black_id, bt_user.id as user_in_blacklist
				FROM ".USERS_TABLE." u
				LEFT JOIN ".ACTIVE_SESSIONS_TABLE." ast ON ast.id_user='".$user_id."'
				LEFT JOIN ".BLACKLIST_TABLE." bt ON bt.id_user='".$user_id."' AND bt.id_enemy='".$user[0]."'
				LEFT JOIN ".BLACKLIST_TABLE." bt_user ON bt_user.id_user='".$user[0]."' AND bt_user.id_enemy='".$user_id."'

				WHERE u.id='".$user_id."'
				";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]) {
		$row = $rs->GetRowAssoc(false);
		$user_arr["fname"] = htmlspecialchars($row["fname"]);
		$user_arr["black_id"] = $row["black_id"];
		$user_arr["user_in_blacklist"] = $row["user_in_blacklist"];
		$user_arr["sname"] = htmlspecialchars($row["sname"]);
		$user_arr["date_seen"] = date("d.m.Y H:i", $row["date_seen"]);

		$str_query = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$user_id."'  AND status='1' ";
		$rs_ad = $dbconn->Execute($str_query);
		if ($rs_ad->fields[0]>0) {			
			if ($from_admin_mode) {
				$user_arr["view_ad_link"] = $config["server"].$config["site_root"]."/admin/admin_users.php?sel=user_rent&id_user=$user_id";
			} else {	
				$user_arr["view_ad_link"] = $config["server"].$config["site_root"]."/viewprofile.php?sel=more_ad&amp;id_user=".$user_id."&amp;section=rent";
			}	
		}
		
		if ($row["status"]>0) {
			$user_arr["status"] = 1;
		} else {
			$user_arr["status"] = 0;
		}
		$user_arr["pict_path"] = "";
		if ($row["user_type"] == 2) {
			$strSQL_logo = "SELECT logo_path FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user_id."' AND admin_approve='1' ";
			$rs_logo = $dbconn->Execute($strSQL_logo);
			if ( ($rs_logo->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/".$rs_logo->fields[0])) ){
				$user_arr["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$rs_logo->fields[0];
			}
		} else {
			$strSQL_pict = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$user_id."' AND status='1' AND admin_approve='1'";
			$rs_pict = $dbconn->Execute($strSQL_pict);
			if ( ($rs_pict->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/thumb_".$rs_pict->fields[0])) ){
				$user_arr["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/thumb_".$rs_pict->fields[0];
			}
		}
		if ($user_arr["pict_path"] == ""){

			$used_references = array("gender");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserGenderIds($arr["spr_user_table"], $user_id, 0, $arr["val_table"]);
					$tmp_user[$arr["key"]] = $name;
				}
			}
			$gender_info = getDefaultUserIcon($row["user_type"], $tmp_user["gender"]);
			$user_arr["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$gender_info["icon_name"];
		}

		$rs->MoveNext();
	}
					
	return $user_arr;

}

function GetMessId($num){
	$str = "".$num."";
	$str = substr($str, (strlen($str)-1),1);
	$str_int = intval($str);
	if ($num >10 && $num<20){
		$mess_id = "1";
	} else {
		if ($num == 4){
			$mess_id = "3";
		} else {
			if ($str == 1) {
				$mess_id = "2";
			} elseif (($str == 2) || ($str == 3) || ($str == 4)){
				$mess_id = "3";
			} else{
				$mess_id = "1";
			}
		}
	}
	return $mess_id;
}

/**
 * Clear messages history: if one user wants to clear it - mark for deleting, 
 * if another wants to clear history too - remove from the table
 *
 * @param void
 */
function ClearHistory() {
	global $config, $dbconn, $user;
		
	$user_id = (isset($_REQUEST["user_id"]) && !empty($_REQUEST["user_id"])) ? intval($_REQUEST["user_id"]) : 0;
	if ($user_id) {
		$strSQL = "DELETE FROM ".MAILBOX_MESSAGES_TABLE." WHERE ".
				  "from_user_id='{$user[0]}' AND to_user_id='$user_id' AND seen='1' AND clear_user_id<>0";
		$rs = $dbconn->Execute($strSQL);				  
		$strSQL = "DELETE FROM ".MAILBOX_MESSAGES_TABLE." WHERE ".
				  "from_user_id='$user_id' AND to_user_id='{$user[0]}' AND seen='1' AND clear_user_id<>0";
		$rs = $dbconn->Execute($strSQL);
		$strSQL = "UPDATE ".MAILBOX_MESSAGES_TABLE." SET clear_user_id='{$user[0]}' WHERE ".
				  "from_user_id='{$user[0]}' AND to_user_id='$user_id' AND clear_user_id='0'";
		$rs = $dbconn->Execute($strSQL);		  	
		$strSQL = "UPDATE ".MAILBOX_MESSAGES_TABLE." SET clear_user_id='{$user[0]}' WHERE ".
				  "from_user_id='$user_id' AND to_user_id='{$user[0]}' AND seen='1' AND clear_user_id='0'";		
		$rs = $dbconn->Execute($strSQL);
	}	
	MyUsers();
	return;
}

?>