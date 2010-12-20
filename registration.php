<?php
/**
* Registration page
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.8 $ $Date: 2009/01/08 11:07:13 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_common.php";
if (in_array("mhi_registration", $config["mode_hide_ids"])) {		
	HidePage();
	exit;
}
include "./include/functions_index.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";
if (GetSiteSettings("use_pilot_module_newsletter")){
	include "./include/functions_newsletter.php";
}
include "./include/class.images.php";
include "./include/class.lang.php";

GetLocationContent();
$user = auth_index_user();

if ($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	if ($_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
}
$multi_lang = new MultiLang($config, $dbconn);

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";

if (!empty($sel)) {
	switch($sel) {
		case "register"	: RegisterUser(); break;
		case "choose_company" : ChooseCompany();break;
		default: GetStarted();
	}
} elseif ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	GetStarted();
}


function GetStarted($data=''){
	global $smarty, $config, $dbconn, $user, $lang;

	if($user[3] != 1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
		echo "<script> location.href='./homepage.php';</script>";
	}
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "registration.php";

	CreateMenu('index_top_menu');
	CreateMenu('index_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	IndexHomePage("registration");

	$alerts = GetAlertsName();
	$strSQL = "SELECT id FROM ".SUBSCRIBE_SYSTEM_TABLE." WHERE status='1'";
	$rs = $dbconn->Execute ($strSQL);
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$alerts_st[$row["id"]] = $row["id"];
		$rs->MoveNext();
	}
	$i = 0;
	foreach ($alerts as $arr){
		if (in_array($arr["id"],$alerts_st)){
			$alerts_vis[$i]["id"]=$arr["id"];
			$alerts_vis[$i]["name"]=$arr["name"];
			++$i;
		}
	}
	if (isset($_GET["from"])){
		$data["from"] = $_GET["from"];
	}

	$smarty->assign("alerts", $alerts_vis);

	$smarty->assign("day", GetDaySelect(isset($data["birth_day"]) ? $data["birth_day"] : date("d")));
	$smarty->assign("month", GetMonthSelect(isset($data["birth_month"]) ? $data["birth_month"] : date("d")));
	if (!isset($data["birth_year"])) {
		$data["birth_year"] = intval(date("Y"))-18;
	}
	$smarty->assign("year", GetYearSelect($data["birth_year"], 80, (intval(date("Y"))-18)));

	$week = GetWeek();
	$smarty->assign("week", $week);

	$time_arr = GetHourSelect();
	$smarty->assign("time_arr", $time_arr);

	$smarty->assign("file_name", $file_name);

	if (isset($_REQUEST["from"]) && $_REQUEST["from"] == 'sresults') {
		$data["dialog_value_1"] = $_GET["c"];			
		$data["user_type_copy"] = 1;
	}
	$smarty->assign("data", $data);
	$smarty->assign("property_types", GetPropertyTypeArr());
	$smarty->assign("use_agent_user_type", GetSiteSettings("use_agent_user_type"));

	$smarty->display(TrimSlash($config["index_theme_path"])."/registration_table.tpl");
	exit;
}

function RegisterUser(){
	global $smarty, $config, $dbconn, $user;

	$login = FormFilter($_POST["email"]);
	$pass = $_POST["pass"];
	$ver_pass = $_POST["ver_pass"];
	$first_name = FormFilter($_POST["first_name"]);
	$last_name = FormFilter($_POST["last_name"]);
	$birth_year = intval($_POST["birth_year"]);
	$birth_month = intval($_POST["birth_month"]);
	$birth_day = intval($_POST["birth_day"]);
	$email = FormFilter($_POST["email"]);
	$ver_email = FormFilter($_POST["ver_email"]);
	$phone = FormFilter($_POST["phone"]);
	$from = $_POST["from"];
	

	$show_info = intval($_POST["show_info"]);
	$user_type = intval($_POST["user_type"]);		
	
	
	if ($user_type == 2) {		
		$company_name = addslashes(strip_tags(trim($_POST["company_name"])));
		$id_country = (isset($_POST["country"]) && !empty($_POST["country"])) ? $_POST["country"] : 0;
		$id_region = (isset($_POST["region"]) && !empty($_POST["region"])) ? $_POST["region"] : 0;
		$id_city = (isset($_POST["city"]) && !empty($_POST["city"])) ? $_POST["city"] : 0;
		$postal_code = (isset($_POST["postal_code"]) && !empty($_POST["postal_code"])) ? $_POST["postal_code"] : "";
		$address = (isset($_POST["address"]) && !empty($_POST["address"])) ? FormFilter($_POST["address"]) : "";	
	}
	
	if ($user_type == 3) {		
		$agency_name = addslashes(strip_tags(trim($_POST["agency_name"])));	
	}

	if ($user_type == 1 || $user_type == 3) {
		$dialog_1 = intval($_POST["dialog_1"]);
		$dialog_2_1 = intval($_POST["dialog_2_1"]);
		$from = "sresults&var_1=1&var_2=".$dialog_1."&var_3=".$dialog_2_1;
		
	} else {
		$dialog_1 = intval($_POST["dialog_1"]);
		$dialog_2_2 = intval($_POST["dialog_2_2"]);
		$from = "sresults&var_1=2&amp;var_2=".$dialog_1."&amp;var_3=".$dialog_2_2;
	}

	//CHECKING
	if(!(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['keystring'])) {
		GetErrors("invalid_pin");
		GetStarted($_POST);
		exit;
	}
	unset($_SESSION['captcha_keystring']);

	if ($birth_year) {
		//check birthdate
		if(checkdate($birth_month, $birth_day, $birth_year)){
			$birthdate = sprintf("%04d-%02d-%02d", $birth_year, $birth_month, $birth_day);
		}else{
			GetErrors("invalid_date");
			GetStarted($_POST);
			exit;
		}
	}
	//email check
	if(EmailFilter($email)) {
		GetStarted($_POST);
		exit;
	}
	if($email != $ver_email){
		GetErrors("email_not_eq");
		GetStarted($_POST);
		exit;
	}
	//// check not valid pass
	if($ver_pass != $pass){
		GetErrors("pass_not_eq");
		GetStarted($_POST);
		exit;
	}
	if($login == $pass) {
		GetErrors("pass_eq_login");
		GetStarted($_POST);
		exit;
	}
	if(PasswFilter($pass)) {
		GetStarted($_POST);
		exit;
	}

	if (( BadWordsCont($first_name)=='badword' ) || ( BadWordsCont($last_name)=='badword' ) || ( BadWordsCont($phone)=='badword' )) {
		GetErrors("badword");
		GetStarted($_POST);
		exit;
	}
	if ( AgeFromBDate($birthdate)<18) {
		GetErrors("age_censored");
		GetStarted($_POST);
		exit;
	}

	$strSQL = "SELECT count(*) FROM ".USERS_TABLE." WHERE email = '".$email."' ";
	$rs = $dbconn->Execute($strSQL);
	if($rs->fields[0]>0) {
		GetErrors("email_exist");
		GetStarted($_POST);
		exit;
	}
	//user confirmation
	$use_registration_confirmation = GetSiteSettings("use_registration_confirmation");
	$status = ($use_registration_confirmation) ? 0 : 1;
	$confirm = $status;

	/**
	 * Save users' current interface language
	 */
	$lang_id = $config["default_lang"];
	$strSQL = "INSERT INTO ".USERS_TABLE." (fname, sname, status, confirm, login, password, lang_id, email, date_birthday, date_last_seen, date_registration, root_user, guest_user, phone, show_info, user_type)
				VALUES ('".$first_name."','".$last_name."','".$status."','".$confirm."','".$login."','".md5($pass)."','$lang_id','".$email."','".$birthdate."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','0','0','".$phone."','".$show_info."','".$user_type."') ";
		
	$rs = $dbconn->Execute($strSQL);
	if ($rs === false) {
		echo "error in SQL query: ".$strSQL;
		exit();
	}
	
	
	if ($user_type == 2) {
		$strSQL = "SELECT MAX(id) FROM ".USERS_TABLE." ";
		$rs = $dbconn->Execute($strSQL);
		$id = intval($rs->fields[0]);
		$strSQL = "INSERT INTO ".USER_REG_DATA_TABLE." ".
				  "(id_user, company_name, id_country, id_region, id_city, address, postal_code) VALUES ".
				  "('".$id."','".$company_name."','".$id_country."','".$id_region."','".$id_city."','".$address."','".$postal_code."')";
		$rs = $dbconn->Execute($strSQL);		
	}
	

	if($user_type==3)
	{
	$id_company = intval($_POST["id_company"]);
	$strSQL = "SELECT MAX(id) FROM ".USERS_TABLE." ";
	$rs = $dbconn->Execute($strSQL);
	$id_agent = intval($rs->fields[0]);

	$strSQL = "SELECT id FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent='$id_agent' AND id_company='$id_company' AND approve = '1'";
		
	$rs = $dbconn->Execute($strSQL);		
		
	if ($id_company && ($rs->RowCount() == 0)){	

			$strSQL = "INSERT INTO ".AGENT_OF_COMPANY_TABLE." (id_agent, id_company, approve, inviter) VALUES ".
					  "('".$id_agent."','".$id_company."','0','agent')";		
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
	
	//get registered user's id
	$strSQL = "SELECT MAX(id) FROM ".USERS_TABLE." ";
	$rs = $dbconn->Execute($strSQL);
	$id = intval($rs->fields[0]);

	if (!empty($_POST["alert"])){
		$alerts = $_POST["alert"];
		foreach ($alerts as $arr){
			$strSQL = "INSERT INTO ".SUBSCRIBE_USER_TABLE." (id_subscribe, id_user) VALUES ('".$arr."','".$id."') ";
			$dbconn->Execute($strSQL);
		}
	}
	//add to default group
	$strSQL = "SELECT id FROM ".GROUPS_TABLE." WHERE type='d'";
	$rs = $dbconn->Execute($strSQL);
	if(intval($rs->fields[0])>0) {
		$dbconn->Execute("INSERT INTO ".USER_GROUP_TABLE." (id_user, id_group) VALUES ('".$id."', '".intval($rs->fields[0])."') ");
	}
        if (GetSiteSettings("use_pilot_module_newsletter")){
	        UpdateNewsletterUserData($id, $first_name, $last_name, $email);
	        UpdateUserRealestateMailingList($id);
        }
	$cont_arr["fname"] = $first_name;
	$cont_arr["sname"] = $last_name;
	$cont_arr["login"] = $login;
	$cont_arr["pass"] = $pass;
	$cont_arr["email"] = $email;
	$cont_arr["site"] = $config["server"];


	if ($use_registration_confirmation) {
		$cont_arr["confirm_link"] = $config["server"].$config["site_root"]."/confirm.php?id=".$id."&lang_code=".$config["default_lang"]."&mail=".md5($cont_arr["email"]);
	}
	$site_mail = GetSiteSettings("site_email");

	$cont_arr["adminname"] = GetAdminName();

	$mail_content = GetMailContentReplace("mail_content_registration", $lang_id);//xml

	$subject = $mail_content["subject"];

	$email_to_name = $cont_arr["fname"]." ".$cont_arr["sname"];
	SendMail($email, $site_mail, $subject, $cont_arr, $mail_content, "mail_registration_for_user", $email_to_name, $mail_content["site_name"] );

	if ($use_registration_confirmation) {
		AlertPage("noconfirm");
	} else {
		sess_write(session_id(), $id);
		//$user = auth_index_user();
		echo "<script>location.href='".$config["site_root"]."/homepage.php?from=".$from."'</script>";
	}
}

function ChooseCompany(){
	global $smarty, $config, $dbconn, $user, $lang;
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";

	IndexHomePage('registration');
		
	$param = $file_name."?sel=choose_company&amp;";
	$where_str = "";
	
	$page = (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 1;
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 1;
	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? $_REQUEST["search"] : "";
	$order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 2;
	$is_show = (isset($_REQUEST["is_show"]) && !empty($_REQUEST["is_show"])) ? intval($_REQUEST["is_show"]) : "";
	$user_index = isset($_REQUEST["user_index"]) ? intval($_REQUEST["user_index"]) : -1;

	$id_company = isset($_REQUEST["id_company"]) ? intval($_REQUEST["id_company"]) : 0;
	$id_user_exc = isset($_REQUEST["id_user_exc"]) ? intval($_REQUEST["id_user_exc"]) : 0;	
	$script = "";
	if ($id_company){
		$strSQL = "SELECT company_name FROM ".USER_REG_DATA_TABLE." WHERE id_user = '$id_company'";			
		$rs = $dbconn->Execute($strSQL);
		$agency_name = $rs->fields[0];
		$script = "<script>CloseParentWindow('$agency_name', 'choose_company');</script>";		
		$smarty->assign("script", $script);
	}

	// search
	$search_str = "";

	if(strval($search)){
		$search = strip_tags($search);
			$search_str .= " AND ( u.fname LIKE '%".$search."%'";
			$search_str .= " OR u.sname LIKE '%".$search."%'";
			$search_str .= " OR rd.company_name LIKE '%".$search."%' ) ";
	}

	$smarty->assign("search", $search);
		
	$smarty->assign("lang", $lang);
	$smarty->assign("is_show", $is_show);
	$smarty->assign("id_user_exc", $id_user_exc);

	$data = getRealtySortOrder($sorter, $order, "user2");
	$smarty->assign("sorter", $sorter);
	
	if (strval(trim($search)) != ""){
		
		if ($id_user_exc){
			$strSQL = "SELECT DISTINCT id_company FROM ".AGENT_OF_COMPANY_TABLE. " WHERE id_agent = '$id_user_exc'";			
		}else{
			$strSQL = "SELECT DISTINCT id_company FROM ".AGENT_OF_COMPANY_TABLE. " WHERE id_agent = '$user[0]'";			
		}
		
		$rs = $dbconn->Execute($strSQL);
		if ( $rs->fields[0]>0 ) {		
			$i = 0;		
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$id_arr[$i] = $row["id_company"];
				$rs->MoveNext();
				$i++;
			}
		}	
		
		if ($user[0] == 1 && $id_user_exc != 1){
			$where_str .= "where u.id != '2' AND u.status = '1' AND rd.company_name != '' AND u.user_type = '2' ";
		}else{
			$where_str .= "where u.id !='".$user[0]."' AND u.id != '2' AND u.status = '1' AND rd.company_name != '' AND u.user_type = '2' ";
		}
	
		if ($id_user_exc != 0){
			$where_str .= "AND u.id != '$id_user_exc' ";
		}
		if($search_str){
			$where_str .= "AND u.id>0 ".$search_str." ";
		}else{
			$where_str .= "";
		}
	
		$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u LEFT JOIN ".USER_REG_DATA_TABLE." rd ON rd.id_user=u.id ".$where_str;
		
		$rs = $dbconn->Execute($strSQL);
		$num_records = $rs->fields[0];
		
		if (($num_records>0) && ($is_show)) {
			$rows_num_page = GetSiteSettings('admin_rows_per_page');
			$lim_min = ($page-1)*$rows_num_page;
			$lim_max = $page*$rows_num_page;
			$limit_str = ($sorter == 5) ? "" : " limit ".$lim_min.", ".$lim_max;
			$strSQL = "	SELECT DISTINCT ra.id_user as ads_user,
						u.id, u.fname, u.sname, u.status, u.access, u.login, u.email,
						u.active, u.user_type, rd.company_name
						FROM ".USERS_TABLE."  u
						LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=u.id
						LEFT JOIN ".USER_REG_DATA_TABLE." rd ON rd.id_user=u.id
	 					".$where_str." ORDER BY ".$data["sorter_str"].$data["sorter_order"].$limit_str;
	
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			if($rs->RowCount()>0){
				while(!$rs->EOF){
					$row = $rs->GetRowAssoc(false);
					$users[$i]["number"] = ($page-1)*$rows_num_page+($i+1);
					$users[$i]["id"] = $row["id"];
					$users[$i]["notify"] = 0;
					if (isset($id_arr) && is_array($id_arr) && in_array($users[$i]["id"], $id_arr)) {
						$users[$i]["notify"] = 1;
					}
					$users[$i]["index"] = $i;
					$users[$i]["name"] = stripslashes($row["fname"]." ".$row["sname"]);
					$users[$i]["email"] = $row["email"];
					$users[$i]["company_name"] = $row["company_name"];
	
					//rent link
					if ($user[0] == 1){
						$users[$i]["rent_link"] = "admin/admin_users.php"."?sel=user_rent&id_user=".$row["ads_user"]."&redirect=2&pageR=$page&sorter=$sorter&search=$search&order=$order&is_show=$is_show";
					}else{
						$users[$i]["rent_link"] = "viewprofile.php"."?sel=more_ad&id_user=".$row["ads_user"]."&redirect=2&pageR=$page&sorter=$sorter&search=$search&order=$order&is_show=$is_show";
					}
					$strSQL = "SELECT DISTINCT id FROM ".RENT_ADS_TABLE." WHERE id_user='".$users[$i]["id"]."' AND status='1'";
					$res = $dbconn->Execute($strSQL);
					if ( $res->RowCount()>0 ) {
						$users[$i]["rent_count"] = $res->RowCount();
					}
	
					$rs->MoveNext();
					$i++;
				}
				/**
				 * сортировка по объявлениям
				 */
				if ($sorter == 5) {
					function cmp_desc($a, $b) {
						if ($a["rent_count"] == $b["rent_count"]) {
					      return 0;
					    }
					    return ($a["rent_count"] > $b["rent_count"]) ? -1 : 1;
					}
					function cmp_asc($a, $b) {
						if ($a["rent_count"] == $b["rent_count"]) {
					   	   return 0;
					   	}
					   	return ($a["rent_count"] < $b["rent_count"]) ? -1 : 1;
					}
	
					if ($data["sorter_order"] == " ASC ") {
						usort($users, "cmp_asc");
					} else {
						usort($users, "cmp_desc");
					}
					$max_number = $lim_max;
					$max_number = ($num_records < $max_number) ? $num_records : $max_number;
					for ($i=$lim_min; $i<$max_number; $i++ ) {
						$res_user[] = $users[$i];
					}
					$users = $res_user;
				}
	
	
				$smarty->assign("page", $page);
				$smarty->assign("rows_num_page", $rows_num_page);
				$param = $param."is_show=1&search=".$search."&sorter=".$sorter."&order=".$order."&";
				$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page) );
			}
			$smarty->assign("users", $users);
		}
		
	
	$smarty->assign("user_index", $user_index);
	}else{
		$smarty->assign("search", "");
	}
	
	$smarty->assign("file_name", $file_name);
	$smarty->assign("par", "choose_company");
	$smarty->assign("data", $data);	
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/users_choosing.tpl");
	exit;
}
?>