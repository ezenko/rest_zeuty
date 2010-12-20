<?php
/**
* Agents of realtor
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_mail.php";
include "./include/class.lang.php";

$from_admin_mode = (isset($_REQUEST["from_admin_mode"]) && ($_REQUEST["from_admin_mode"]) == 1) ? 1 : 0;  

if ($from_admin_mode && ((!isset($_POST["sel"])) || $_POST["sel"] != "choose_agent")) {
	include "./include/functions_admin.php";
} else {
	include "./include/functions_index.php";
}

$smarty->assign("from_admin_mode", $from_admin_mode);

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );
if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

$multi_lang = new MultiLang($config, $dbconn);

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
switch ($sel){
	case "approve": ApproveAgent(intval($_REQUEST["aoc_id"])); break;
	case "decline": DeclineAgent(intval($_REQUEST["aoc_id"])); break;
	case "decline_offer": DeleteAgent(intval($_REQUEST["aoc_id"]), 3); break;
	case "del": DeleteAgent(intval($_REQUEST["aoc_id"]), 1); break;
	case "choose_agent": ChooseAgent(); break;
	case "add": AddAgent(intval($_REQUEST["id_agent"])); break;		
	case "app_offer_by_agent": ApproveCompany(intval($_REQUEST["id"])); break;
	case "dec_offer_by_agent": DeclineCompany(intval($_REQUEST["id"])); break;
	case "dec_self_offer_by_agent": DeclineOfferToCompany(intval($_REQUEST["id"])); break;
	case "delete_realtor": DeleteCompany(intval($_REQUEST["id"])); break;
	default	: AgentsList(); break;
}


/**
 * Quick search form initialization
 *
 * @return void
 */
function AgentsList(){
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES, $from_admin_mode;	

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	if ($from_admin_mode) {		
		IndexAdminPage('agents', 1);
		CreateMenu('admin_lang_menu');
		$smarty->assign("add_to_lang", "&from_admin_mode=1");
	}else{
		IndexHomePage('agents','homepage');
		$link_count = GetCountForLinks($user[0]);
		$smarty->assign("link_count",$link_count);
		$left_links = GetLeftLinks("agents.php");
		$smarty->assign("left_links", $left_links);
	}
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "agents.php";
	
	CreateMenu('search_menu');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	GetLocationContent();
	
	$id_company = $user[0];
	$strSQL = "SELECT user_type FROM ".USERS_TABLE." WHERE id = '$id_company';"	;	
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] != 2){
		$is_realtor = 0;
		$smarty->assign("submenu","realtor");
	}else{
		$is_realtor = 1;
		$smarty->assign("submenu","agents");
	}	
	$smarty->assign("is_realtor", $is_realtor);			
	
	if( $is_realtor ){
		$strSQL = "SELECT DISTINCT id_agent FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$id_company';";				
		$rs = $dbconn->Execute($strSQL);
		if ( $rs->fields[0]>0 ) {
			$smarty->assign("empty_result", 0);
			$i = 0;
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$id_arr[$i] = $row["id_agent"];
				$rs->MoveNext();
				$i++;
			}
			$users_numpage = GetSiteSettings("users_num_page");
			$num_users = sizeof($id_arr);
			$lim_min = ($page-1)*$users_numpage;
			$lim_max = $users_numpage;
			$limit_str = " limit ".$lim_min.", ".$lim_max;
	
			$sorter = (isset($_GET["sorter"])) ? intval($_GET["sorter"]) : 0;
			$sorter_order = (isset($_GET["order"])) ? intval($_GET["order"]) : 0;
			$smarty->assign("sorter", $sorter);
	
			$sort_arr = getRealtySortOrder($sorter, $sorter_order, "user");
			$sorter_str = " GROUP by a.id ORDER BY aoc.approve, aoc.inviter DESC, ".$sort_arr["sorter_str"].$sort_arr["sorter_order"];
	
			$sorter_tolink = $sort_arr["sorter_tolink"];
			$sorter_topage = $sort_arr["sorter_topage"];
			$smarty->assign("order_icon", $sort_arr["order_icon"]);
	
			$where_str = "WHERE a.id IN (".implode(",", $id_arr).")";			
	
			$strSQL = "	SELECT DISTINCT a.id, concat(a.fname,' ', a.sname) as login, DATE_FORMAT(a.date_last_seen,'".$config["date_format"]."')  as date_last_login, a.user_type, e.id_user as session, aoc.id as aoc_id, aoc.approve, aoc.inviter  
						FROM ".USERS_TABLE." a
						LEFT JOIN ".ACTIVE_SESSIONS_TABLE." e on a.id=e.id_user
						LEFT JOIN ".AGENT_OF_COMPANY_TABLE." aoc on a.id=aoc.id_agent AND aoc.id_company = '$user[0]'						
						".$where_str." and a.status='1' AND a.active='1' ".$sorter_str.$limit_str;
			
			$rs = $dbconn->Execute($strSQL);
			
			$i = 0;
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$users_list[$i]["id"] = $row["id"];
				$users_list[$i]["login"] = $row["login"];				
				$users_list[$i]["date_last_login"] = $row["date_last_login"];
				$users_list[$i]["online_status"] = $row["session"]? 1: 0;
				$users_list[$i]["number"] = ($page-1)*$users_numpage+($i+1);
				$users_list[$i]["user_type"] = $row["user_type"];
	
				//$users_list[$i]["date_added"] = $row["date_added"];
				$users_list[$i]["approve"] = $row["approve"];
				$users_list[$i]["inviter"] = $row["inviter"];
	
				$suffix = "&id=".$users_list[$i]["id"];
	
				$users_list[$i]["contact_link"] = "./contact.php?sel=fs".$suffix;				
				$users_list[$i]["del_from_agents_link"] = "$file_name?sel=del&aoc_id=".$row["aoc_id"];
				$users_list[$i]["approve_link"] = $file_name."?sel=approve&aoc_id=".$row["aoc_id"];				
				$users_list[$i]["decline_link"] = "$file_name?sel=decline&aoc_id=".$row["aoc_id"];				
				$users_list[$i]["del_company_offer_link"] = "$file_name?sel=decline_offer&aoc_id=".$row["aoc_id"];
				if ($from_admin_mode){
					$users_list[$i]["del_from_agents_link"] .= "&from_admin_mode=1";
					$users_list[$i]["approve_link"] .= "&from_admin_mode=1";
					$users_list[$i]["decline_link"] .= "&from_admin_mode=1";
					$users_list[$i]["del_company_offer_link"] .= "&from_admin_mode=1";
				}			
				
								
				$used_references = array("gender");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$name = GetUserGenderIds($arr["spr_user_table"], $users_list[$i]["id"], 0, $arr["val_table"]);
						$users_list[$i][$arr["key"]] = $name;
					}
				}
				$gender_info = getDefaultUserIcon($users_list[$i]["user_type"], $users_list[$i]["gender"]);
				$icon_name =  $gender_info["icon_name"];
	
				$str_query = " SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND status='1' ";
				$rs_ad = $dbconn->Execute($str_query);
				if ($rs_ad->fields[0]>0){
					if ($from_admin_mode){						
						$users_list[$i]["view_ad_link"] = $config["server"].$config["site_root"]."/admin/admin_users.php?sel=user_rent&amp;id_user=".$users_list[$i]["id"]."&redirect=4&pageR=$page&sorter=$sorter";
					}else{
						$users_list[$i]["view_ad_link"] = $config["server"].$config["site_root"]."/viewprofile.php?sel=more_ad&amp;id_user=".$users_list[$i]["id"]."&amp;section=rent";
					}
				}
				
				$users_list[$i]["pict_path"] = "";
				
				$strSQL_pict = "SELECT upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_user='".$users_list[$i]["id"]."' AND status='1' AND admin_approve='1'";
				$rs_pict = $dbconn->Execute($strSQL_pict);
				if ( ($rs_pict->fields[0] != "") && (file_exists($config["site_path"]."/uploades/photo/thumb_".$rs_pict->fields[0])) ){
					$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/thumb_".$rs_pict->fields[0];
				}			
	
				if ($users_list[$i]["pict_path"] == ""){
					$users_list[$i]["pict_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$icon_name;
				}
	
				$rs->MoveNext();
				$i++;
			}
			if (isset($_GET["section"])){
				$section_part = "&amp;section=".$_GET["section"];
			}else{
				$section_part = "";
			}
			$param = "sorter=".$sorter."&amp;order=".$sorter_topage.$section_part."&amp;";
			if ($from_admin_mode) {
				$param .= "from_admin_mode=1&";
			}
			$smarty->assign("order_link", "&amp;order=".$sorter_topage.$section_part."&amp;page=".$page);
			$smarty->assign("order_active_link", "&amp;order=".$sorter_tolink.$section_part."&amp;page=".$page);
	
			$smarty->assign("links", GetLinkArray($num_users, $page, $file_name."?".$param, $lim_max));
			$smarty->assign("users_list", $users_list);
		} else {
			$smarty->assign("empty_result", 1);
		}
	}else{		
		$strSQL = "SELECT DISTINCT id, id_company, approve, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]';";		
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		$company_info = array();
		while ( !$rs->EOF ) {			
			$row = $rs->GetRowAssoc(false);
			$company_info[$i] = GetAccountTableInfo($row["id_company"]);
			
			$rs_2 = $dbconn->Execute("SELECT id FROM ".RENT_ADS_TABLE." WHERE id_user = '".$row["id_company"]."' AND status='1' LIMIT 1");
			
			if ($rs_2->fields[0]){				
				if ($from_admin_mode){
					$company_info[$i]["view_ad_link"] = "admin/admin_users.php"."?sel=user_rent&id_user=".$row["id_company"]."&redirect=4&pageR=$page&sorter=$sorter&search=$search&order=$order";
				}else{
				$company_info[$i]["view_ad_link"] = "viewprofile.php"."?sel=more_ad&id_user=".$row["id_company"]."&redirect=4";
				}
			}			
			$company_info[$i]["id"] = $row["id_company"];
			$company_info[$i]["approve"] = $row["approve"];
			$company_info[$i]["inviter"] = $row["inviter"];
			$company_info[$i]["contact_link"] = "./contact.php?sel=fs&id=".$row["id_company"];				
			$company_info[$i]["approve_link"] = "./agents.php?sel=app_offer_by_agent&id=".$row["id_company"];				
			$company_info[$i]["decline_link"] = "./agents.php?sel=dec_offer_by_agent&id=".$row["id_company"];				
			$company_info[$i]["del_agent_offer_link"] = "./agents.php?sel=dec_self_offer_by_agent&id=".$row["id_company"];			
			$company_info[$i]["delete_realtor_link"] = "./agents.php?sel=delete_realtor&id=".$row["id_company"];
			
			if ($from_admin_mode){
				$company_info[$i]["approve_link"] .= "&from_admin_mode=1";
				$company_info[$i]["decline_link"] .= "&from_admin_mode=1";
				$company_info[$i]["del_agent_offer_link"] .= "&from_admin_mode=1";
				$company_info[$i]["delete_realtor_link"] .= "&from_admin_mode=1";
			}	
			
			$rs->MoveNext();
			$i++;			
		}
		$smarty->assign("company_info", $company_info);			
	}
	$smarty->assign("file_name", $file_name);
	$smarty->assign("section_name", "agents");	
	$smarty->display(TrimSlash($config["index_theme_path"])."/agents_table.tpl");
	
}

function ApproveAgent( $id ){
	global $dbconn, $user, $config;	
	
	$site_mail = GetSiteSettings("site_email");		
	
	
	$rs = $dbconn->Execute("SELECT aoc.id_agent, aoc.id_company, rd.company_name FROM ".AGENT_OF_COMPANY_TABLE." aoc 
								LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user WHERE id = '$id'");
	$row = $rs->GetRowAssoc(false);
	$id_agent = $row["id_agent"];
	$id_company = $row["id_company"];
	$data["company_name"] = $row["company_name"];
	
	$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$id_agent."'");
	$lang_id_agent = GetUserLanguageId($rs2->fields[0]);
	
	$mail_content = GetMailContentReplace("mail_content_approve_by_realtor", $lang_id_agent);//xml
	$subject = $mail_content["subject"];
	
	$strSQL = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
	$rs=$dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
		
	$email = $row["email"];
	$email_to_name = $row["fname"]." ".$row["sname"];
	$data["agent_name"] = $email_to_name;	
	$data["link"] = $config["server"].$config["site_root"]."/account.php";
	
	SendMail($email, $site_mail, $subject, $data, $mail_content, "mail_approve_by_realtor_table", $email_to_name, $mail_content["site_name"] );
	
	$strSQL = "UPDATE ".AGENT_OF_COMPANY_TABLE." SET approve = '1' WHERE id = '$id' AND id_company = '".$user[0]."'";
	$dbconn->Execute($strSQL);
	
	$strSQL = "SELECT id, id_company, inviter FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$id_agent' AND id_company != '$user[0]'";		
	
	$rs = $dbconn->Execute($strSQL);	
	while (!$rs->EOF){
		$row = $rs->GetRowAssoc(false);		
		switch ($row["inviter"]){
			case "agent": 				
				DeclineAgent($row["id"]);								
				break;
			case "company": 							
				UpdateAgentTable($row["id_company"], 0, $id_agent);				
				break;	
		}
		$rs->MoveNext();
	}
	AgentsList();
}


function DeleteAgent( $id, $par){
	global $dbconn, $user, $config;

		
	$rs = $dbconn->Execute("SELECT aoc.id_agent, aoc.id_company, rd.company_name FROM ".AGENT_OF_COMPANY_TABLE." aoc 
								LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user WHERE id = '$id'");	
	
	$row = $rs->GetRowAssoc(false);
	$id_agent = $row["id_agent"];
	$id_company = $row["id_company"];
	$data["company_name"] = $row["company_name"];
	
	$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$id_agent."'");
	$lang_id_agent = GetUserLanguageId($rs2->fields[0]);
	
	$site_mail = GetSiteSettings("site_email");		
	switch ($par){
		case 1: $mail_content = GetMailContentReplace("mail_content_delete_by_realtor", $lang_id_agent);//xml
		break;
		case 2: $mail_content = GetMailContentReplace("mail_content_decline_by_realtor", $lang_id_agent);//xml
		break;
		case 3: $mail_content = GetMailContentReplace("mail_content_delete_offer_by_realtor", $lang_id_agent);//xml
		break;
	}
	
	$subject = $mail_content["subject"];
	
	$strSQL = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
	$rs=$dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
		
	$email = $row["email"];
	$email_to_name = $row["fname"]." ".$row["sname"];
	$data["agent_name"] = $email_to_name;
	$data["link"] = $config["server"].$config["site_root"]."/account.php";
	
	SendMail($email, $site_mail, $subject, $data, $mail_content, "mail_delete_by_realtor_table", $email_to_name, $mail_content["site_name"] );	
		
	$strSQL = "DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id = '$id' AND id_company = '".$id_company."'";
	$dbconn->Execute($strSQL);
	
	AgentsList();
}

function DeclineAgent( $id ){
	DeleteAgent($id, 2);
}

function ChooseAgent(){
	global $smarty, $config, $dbconn, $user, $lang;
	$from_admin_mode = (isset($_REQUEST["from_admin_mode_2"]) && ($_REQUEST["from_admin_mode_2"]) == 1) ? 1 : 0;  
	
	$smarty->assign("from_admin_mode", $from_admin_mode);
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "agents.php";
	
	IndexHomePage('agents');
	
	if (isset($err)) {
		GetErrors($err);
	}
	

	
	$param = $file_name."?sel=choose_agent&amp;";


	$page = (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 1;
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 1;
	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? $_REQUEST["search"] : "";
	$order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 2;
	$is_show = (isset($_REQUEST["is_show"]) && !empty($_REQUEST["is_show"])) ? intval($_REQUEST["is_show"]) : "";
	$user_index = isset($_REQUEST["user_index"]) ? intval($_REQUEST["user_index"]) : -1;

	$id_agent = isset($_REQUEST["id_agent"]) ? intval($_REQUEST["id_agent"]) : 0;
	$script = "";
	if ($id_agent){		
		$script = "<script>CloseParentWindow('$id_agent', 'choose_agent');</script>";		
		$smarty->assign("script", $script);
	}

	// search
	$search_str = "";

	if(strval($search)){
		$search = strip_tags($search);
			$search_str .= " AND ( u.fname LIKE '%".$search."%'";
			$search_str .= " OR u.sname LIKE '%".$search."%'";
			$search_str .= " OR u.email LIKE '%".$search."%' ) ";
	}

	$smarty->assign("search", $search);
	//$smarty->assign("s_type", $s_type);
	
	$smarty->assign("lang", $lang);
	$smarty->assign("is_show", $is_show);

	if (strval(trim($search)) != ""){
		$data = getRealtySortOrder($sorter, $order, "user2");
		$smarty->assign("sorter", $sorter);
		
		$strSQL = "SELECT DISTINCT id_agent FROM ".AGENT_OF_COMPANY_TABLE. " WHERE id_company = '$user[0]' OR approve = '1'";			
		$rs = $dbconn->Execute($strSQL);
		if ( $rs->fields[0]>0 ) {		
			$i = 0;		
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$id_arr[$i] = $row["id_agent"];
				$rs->MoveNext();
				$i++;
			}
		}
	
		$where_str = "where u.id !='".$user[0]."' AND u.id !='1' AND u.id != '2' AND u.user_type = '3' AND u.status = '1' ";
		if ($id_arr){
			$where_str .= "AND u.id NOT IN (".implode(",", $id_arr).")";
		}
		
		if($search_str){
			$where_str .= " AND u.id>0 ".$search_str." ";
		}else{
			$where_str .= "";
		}
	
		$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u ".$where_str;		
		$rs = $dbconn->Execute($strSQL);
		$num_records = $rs->fields[0];
		
		if (($num_records > 0) && ($is_show)) {
			
			$rows_num_page = GetSiteSettings('admin_rows_per_page');
			$lim_min = ($page-1)*$rows_num_page;
			$lim_max = $page*$rows_num_page;
			$limit_str = ($sorter == 5) ? "" : " limit ".$lim_min.", ".$lim_max;
			$strSQL = "	SELECT DISTINCT ra.id_user as ads_user,
						u.id, u.fname, u.sname, u.status, u.access, u.login, u.email,
						u.active, u.user_type 
						FROM ".USERS_TABLE."  u
						LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=u.id					
	 					".$where_str." ORDER BY ".$data["sorter_str"].$data["sorter_order"].$limit_str;
			
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			
			if($rs->RowCount()>0){
				while(!$rs->EOF){
					$row = $rs->GetRowAssoc(false);
					$users[$i]["number"] = ($page-1)*$rows_num_page+($i+1);
					$users[$i]["id"] = $row["id"];
					$users[$i]["index"] = $i;
					$users[$i]["name"] = stripslashes($row["fname"]." ".$row["sname"]);
					$users[$i]["email"] = $row["email"];		
	
					//rent link
					
					if ($from_admin_mode){
						$users[$i]["rent_link"] = "admin/admin_users.php"."?sel=user_rent&id_user=".$row["ads_user"]."&redirect=3&pageR=$page&sorter=$sorter&search=$search&order=$order&is_show=$is_show";
					}else{
						$users[$i]["rent_link"] = "viewprofile.php"."?sel=more_ad&id_user=".$row["ads_user"]."&redirect=3&pageR=$page&sorter=$sorter&search=$search&order=$order&is_show=$is_show";
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

	$smarty->assign("par", "choose_agent");

	if (isset($data)){
		$smarty->assign("data", $data);
	}
	
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/users_choosing.tpl");	
}

function AddAgent( $id_agent ){
	global $dbconn, $user, $config;	
	$strSQL = "SELECT DISTINCT id_agent FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$id_agent' AND id_company = '$user[0]'";		
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] == 0 && $id_agent){
		$strSQL = "INSERT INTO ".AGENT_OF_COMPANY_TABLE." (id_agent, id_company, approve, inviter) 
											VALUES ('".$id_agent."','".$user[0]."','0','company')";
		$dbconn->Execute($strSQL);
				
		$site_mail = GetSiteSettings("site_email");
		
		$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$id_agent."'");
		$lang_id_agent = GetUserLanguageId($rs2->fields[0]);
		
		$mail_content = GetMailContentReplace("mail_content_invite_to_realtor", $lang_id_agent);//xml

		$subject = $mail_content["subject"];
		
		$strSQL = "SELECT u.fname, u.sname, u.email, rd.company_name FROM ".USERS_TABLE." u 
						LEFT JOIN ".USER_REG_DATA_TABLE." rd on rd.id_user = '$user[0]'  WHERE id = '$id_agent'";
		
		$rs=$dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);	
		$email = $row["email"];
		$email_to_name = $row["fname"]." ".$row["sname"];
		$data["company_name"] = $row["company_name"];
		$data["agent_name"] = $email_to_name;
		$data["approve_link"] = $config["server"].$config["site_root"]."/agents.php";;
		SendMail($email, $site_mail, $subject, $data, $mail_content, "mail_invite_to_realtor_table", $email_to_name, $mail_content["site_name"] );		
	}
	
	AgentsList();
}

function UpdateAgentTable($id_company, $approve, $id_agent = 0){
	global $dbconn, $user, $config;
	
	$rs2 = $dbconn->Execute("SELECT lang_id FROM ".USERS_TABLE." WHERE id = '".$id_company."'");
	$lang_id_company = GetUserLanguageId($rs2->fields[0]);
		
	$site_mail = GetSiteSettings("site_email");		
	switch ($approve){
		case "3": 
			$mail_content = GetMailContentReplace("mail_content_delete_by_agent", $lang_id_company);
			$template = "mail_delete_by_agent_table";			
			break;
		case "2": 
			$mail_content = GetMailContentReplace("mail_content_delete_by_agent_2", $lang_id_company);
			$template = "mail_delete_by_agent_table_2";
			break;
		case "1": 
			$mail_content = GetMailContentReplace("mail_content_approve_by_agent", $lang_id_company);
			$template = "mail_update_by_agent_table";
			break;
		case "0": 
			$mail_content = GetMailContentReplace("mail_content_decline_by_agent", $lang_id_company);
			$template = "mail_update_by_agent_table";
			break;
	}
		
	$subject = $mail_content["subject"];
	if (!$id_agent){
		$id_agent=$user[0];
	}
	$rs = $dbconn->Execute("SELECT aoc.id_agent, aoc.id_company, rd.company_name FROM ".AGENT_OF_COMPANY_TABLE." aoc 
								LEFT JOIN ".USER_REG_DATA_TABLE." rd ON aoc.id_company = rd.id_user WHERE aoc.id_agent = '$id_agent' AND aoc.id_company = '$id_company'");	
	
	$row = $rs->GetRowAssoc(false);
	if ($rs->RowCount() > 0){
		$id_agent = $row["id_agent"];
		$id_company = $row["id_company"];
		$data["company_name"] = $row["company_name"];
		
		$strSQL = "SELECT u.fname, u.sname, u.email FROM ".USERS_TABLE." u WHERE id = '$id_company'";		
		$rs=$dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		
		$data["company_name_user"] = $row["fname"]." ".$row["sname"];
		$email = $row["email"];
		$email_to_name = $data["company_name_user"];			
		
		$strSQL = "SELECT u.fname, u.sname FROM ".USERS_TABLE." u WHERE id = '$id_agent'";		
		$rs=$dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		
		$data["agent_name"] = $row["fname"]." ".$row["sname"];	
		$data["link"] = $config["server"].$config["site_root"]."/agents.php";
		
		SendMail($email, $site_mail, $subject, $data, $mail_content, $template, $email_to_name, $mail_content["site_name"] );
		switch ($approve){
			case "3": 
				$strSQL = "DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$id_company' AND id_agent = '$id_agent'";
				break;
			case "2": 
				$strSQL = "DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$id_company' AND id_agent = '$id_agent'";
				break;
			case "1": 
				$strSQL = "UPDATE ".AGENT_OF_COMPANY_TABLE." SET approve = '1' WHERE id_company = '$id_company' AND id_agent = '".$id_agent."'";
				break;
			case "0": 
				$strSQL = "DELETE FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_company = '$id_company' AND id_agent = '$id_agent'";
				break;				
		}
		
		$dbconn->Execute($strSQL);		
	}		
}

function ApproveCompany($id_company){
	global $user, $dbconn;
	UpdateAgentTable($id_company, 1);
	$strSQL = "SELECT id_company FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user[0]' AND id_company != '$id_company'";		
	$rs = $dbconn->Execute($strSQL);	
	while (!$rs->EOF){
		$row = $rs->GetRowAssoc(false);		
		UpdateAgentTable($row["id_company"], 0);		
		$rs->MoveNext();
	}
	AgentsList();
}

function DeclineCompany( $id_company){
	UpdateAgentTable($id_company, 0);
	AgentsList();
}

function DeleteCompany ( $id_company){
	UpdateAgentTable($id_company, 3);
	AgentsList();
}

function DeclineOfferToCompany($id_company){
	UpdateAgentTable($id_company, 2);
	AgentsList();
}
?>