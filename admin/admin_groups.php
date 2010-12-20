<?php
/**
* User groups management
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.5 $ $Date: 2008/10/15 10:38:33 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
if (GetSiteSettings("use_pilot_module_newsletter")){
	include "../include/functions_newsletter.php";
}
include "../include/class.images.php";
include "../include/class.object2xml.php";

$auth = auth_user();

if( (!($auth[0]>0))  || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

$lang["modules"] = GetLangContent('modules');
$lang["users_types"] = GetLangContent('users_types');

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$smarty->assign("sel", $sel);

switch($sel){
	case "edit" : 			EditForm(); break;
	case "perm_change" : 	PermissionsChange(); break;
	case "group_users_list": GroupUsersList(); break;
	case "groupuser": 		UserChange(); break;
	case "delete": 	DeleteGroup(); break;
	case "set_default_perms": 	SetDefaultPerms(); break;
	default: ListGroup();
}

function ListGroup() {
	global $smarty, $dbconn, $config, $lang;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_groups.php";

	IndexAdminPage ('admin_groups');
	CreateMenu('admin_lang_menu');

	$lang["groups"] = GetLangContent('groups');

	$strSQL = "SELECT id, type, addable FROM ".GROUPS_TABLE." ORDER BY id ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$group_arr[$i]["number"] = $i+1;
			$group_arr[$i]["id"] = $row["id"];
			$group_arr[$i]["name"] = $lang["groups"][$row["id"]];
			$group_arr[$i]["root"] = ($row["type"] == "r" || $row["type"] == "g") ? 1 : 0;
			//$group_arr[$i]["type"] = $lang["groups"][$row["type"]];
			$group_arr[$i]["type"] = $row["type"];
			$group_arr[$i]["edit_link"] = $file_name."?sel=edit&id_group=".$row["id"];
			$group_arr[$i]["users_link"] = $file_name."?sel=group_users_list&id_group=".$row["id"];
			$rs->MoveNext();
			$i++;
		}
		$smarty->assign("group_arr", $group_arr);
	} else {
		$smarty->assign("empty_row", "1");
	}

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_groups_table.tpl");
	exit;
}


function EditForm($id_group='', $err="", $perm_arr = array()){
	global $smarty, $dbconn, $config, $lang;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_groups.php";

	IndexAdminPage ('admin_groups');
	CreateMenu('admin_lang_menu');

	$lang["groups"] = GetLangContent('groups');

	if ($err) {
		GetErrors($err);
	}
	if (!$id_group) {
		$id_group = (isset($_REQUEST["id_group"]) && !empty($_REQUEST["id_group"])) ? intval($_REQUEST["id_group"]) : 0;
	}
	$data["name"] = ($id_group) ? $lang["groups"][$id_group] : "";

	if (!$id_group || count($perm_arr) > 0) {
		$data["type"] = (isset($_REQUEST["type"]) && !empty($_REQUEST["type"])) ? $_REQUEST["type"] : "f";
		$data["name"] = (isset($_REQUEST["group_name"]) && !empty($_REQUEST["group_name"])) ? trim($_REQUEST["group_name"]) : "";
		$data["allow_trial"] = (isset($_REQUEST["allow_trial"]) && $_REQUEST["allow_trial"] == 1) ? 1 : 0;
		$data["trial_period"] = (isset($_REQUEST["trial_period"]) && !empty($_REQUEST["trial_period"])) ? intval($_REQUEST["trial_period"]) : "";
	} elseif ($id_group) {
		$rs = $dbconn->Execute("SELECT type, allow_trial, trial_period FROM ".GROUPS_TABLE." WHERE id='".$id_group."'");
		$row = $rs->getRowAssoc( false );
		$data["type"] = $row["type"];
		$data["allow_trial"] = $row["allow_trial"];
		$data["trial_period"] = $row["trial_period"];
	}

	//select permissions
	$strSQL = " SELECT DISTINCT mft.id, mft.file, gmt.id_module
				FROM ".MODULE_FILE_TABLE." mft
				LEFT JOIN ".GROUP_MODULE_TABLE." gmt ON (gmt.id_module=mft.id AND gmt.id_group='".$id_group."')
				WHERE file NOT LIKE '%admin_%'
				AND file NOT LIKE '%newsletter%'
				GROUP BY mft.id ORDER BY mft.id ";

	$rs = $dbconn->Execute($strSQL);
	if($rs->RowCount()>0){
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$permission[$i]["id"] =  $row["id"];
			$permission[$i]["file"] =  $row["file"];
			$permission[$i]["name"] =  $lang["modules"][$row["file"]];
			if (utf8_strlen($lang["modules"]['descr_'.$row["file"]]) > 150) {
				$permission[$i]["descr"] = utf8_substr($lang["modules"]['descr_'.$row["file"]], 0, 150)."...";
			} else {
				$permission[$i]["descr"] =  $lang["modules"]['descr_'.$row["file"]];
			}
			if (count($perm_arr) > 0) {
				$permission[$i]["allowed"] = (in_array($row["id"], $perm_arr)) ? 1 : 0;
			} else {
				$permission[$i]["allowed"] = ($row["id_module"]>0) ? 1 : 0;
			}
			$rs->MoveNext();
			$i++;
		}
	}

	$data["id_group"] = $id_group;

	$smarty->assign("data", $data);
	$smarty->assign("permission", $permission);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_groups_form_table.tpl");
	exit;
}

function PermissionsChange() {
	global $smarty, $dbconn, $config, $lang;

	$id_group = intval($_REQUEST["id_group"]);
	$group_name = (isset($_REQUEST["group_name"]) && !empty($_REQUEST["group_name"])) ? trim($_REQUEST["group_name"]) : "";
	if ($group_name == "") {
		EditForm($id_group, "empty_group_name");
		exit();
	}
	$allow_trial = (isset($_REQUEST["allow_trial"]) && $_REQUEST["allow_trial"] == 1) ? 1 : 0;
	$trial_period = (isset($_REQUEST["trial_period"]) && !empty($_REQUEST["trial_period"])) ? intval($_REQUEST["trial_period"]) : 0;
	if ($allow_trial && !$trial_period) {
		EditForm($id_group, "wrong_group_trial_period");
		exit();
	}
	/**
	 * Save group name
	 */
	if (!$id_group) {
		/**
		 * Add new group, type='f' - paid group
		 */
		$strSQL = "INSERT INTO ".GROUPS_TABLE." SET type='f', speed='1', addable='1'";
		$rs = $dbconn->Execute($strSQL);
		$id_group = $dbconn->Insert_ID();
		
		$strSQL_t = "SELECT fname, sname, email FROM ".USERS_TABLE." WHERE id = '1'";
		$rs_t = $dbconn->Execute($strSQL_t);
		$admin_data = $rs_t->GetRowAssoc(false);
		if (GetSiteSettings("use_pilot_module_newsletter")){
		       AddNewsletterMailingList($id_group, $group_name, $admin_data["fname"].$admin_data["sname"], $admin_data["email"]);
                }
		/**
		 * Add new group name to the language files of each language
		 */
		AddLangString($config["site_path"]."/lang/", 'groups.xml', $id_group, $group_name);
	} else {
		/**
		 * Update group name in the language file of the current language
		 */
		$file_path = $config["site_path"].$config["lang_path"]."groups.xml";
		$xml_parser = new SimpleXmlParser( $file_path );
		$xml_root = $xml_parser->getRoot();
		for ( $i = 0; $i < $xml_root->childrenCount; $i++ ) {
			if ( $xml_root->children[$i]->attrs["name"] == $id_group ) {
				$xml_root->children[$i]->value = $group_name;
			}
		}
		$obj_saver = new Object2Xml( true );
		$obj_saver->Save( $xml_root, $file_path );
		if (GetSiteSettings("use_pilot_module_newsletter")){
		         UpdateNewsletterMailingList($id_group, $group_name);
                }
	}
	/**
	 * Save trial period settings
	 */
	$strSQL = "UPDATE ".GROUPS_TABLE." SET allow_trial='$allow_trial', trial_period='$trial_period' ".
			  "WHERE id='$id_group'";
	$rs = $dbconn->Execute($strSQL);
	/**
	 * Update permissions
	 */
	$allowed = $_POST["allowed"];

	$dbconn->Execute(" DELETE FROM ".GROUP_MODULE_TABLE." WHERE id_group='".$id_group."' ");
	foreach ($allowed as $id_module=>$value) {
		$dbconn->Execute (" INSERT INTO ".GROUP_MODULE_TABLE." (id_group, id_module) VALUES ('".$id_group."', '".$id_module."') ");
	}

	EditForm($id_group);
	exit;
}

function DeleteGroup(){
	global $dbconn, $config;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if (!$id) {
		ListGroup();
		return;
	}
	/**
	 * Check if group could be deleted (only paid groups)
	 */
	$strSQL = "SELECT type, addable FROM ".GROUPS_TABLE." WHERE id='".$id."'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc( false );
	if (!($row["type"] == "f" && $row["addable"] == 1)) {
		ListGroup();
		return;
	}
	/**
	 * Delete group
	 */
	$strSQL = "DELETE FROM ".GROUPS_TABLE." WHERE id='".$id."'";
	$dbconn->Execute($strSQL);

	$rs = $dbconn->Execute("SELECT id FROM ".GROUP_PERIOD_TABLE." WHERE id_group='".$id."'");
	while (!$rs->EOF){
		$dbconn->Execute("DELETE FROM ".BILLING_USER_PERIOD_TABLE." WHERE id_group_period='".$rs->fields[0]."'");
		$rs->MoveNext();
	}
	$dbconn->Execute("DELETE FROM ".GROUP_PERIOD_TABLE." WHERE id_group='".$id."'");
	$dbconn->Execute("DELETE FROM ".GROUP_MODULE_TABLE." WHERE id_group='".$id."'");
	$dbconn->Execute("DELETE FROM ".GROUP_TRIAL_USER_PERIOD_TABLE." WHERE id_group='".$id."'");
	/**
	 * Move users from deleted group to the default group
	 */
	$rs = $dbconn->Execute("SELECT id FROM ".GROUPS_TABLE." WHERE type='d'");
	$default_group = $rs->fields[0];

	$rs = $dbconn->Execute("SELECT id_user FROM ".USER_GROUP_TABLE." WHERE id_group='".$id."'");
	while (!$rs->EOF) {
		$dbconn->Execute("DELETE FROM ".BILLING_USER_PERIOD_TABLE." WHERE id_user='".$rs->fields[0]."' ");
		$rs->MoveNext();
	}
	$dbconn->Execute("UPDATE ".USER_GROUP_TABLE." SET id_group='".$default_group."' WHERE id_group='".$id."'");
	/**
	 * Delete group name from language files
	 */
	DeleteLangString($config["site_path"]."/lang/", 'groups.xml', $id);
        if (GetSiteSettings("use_pilot_module_newsletter")){
	        DeleteMailingList($id, $default_group);	
        }
	
	ListGroup();
	return;
}


function UserChange(){
	global $smarty, $dbconn, $config, $lang;

	$id_group = intval($_POST["id_group"]) ? intval($_POST["id_group"]) : intval($_GET["id_group"]);

	if(!$id_group){ ListGroup(); return;}

	$delete_arr = array();
	$add_arr = array();
	$values_arr = array();
	$prevUsers = is_array($_POST["prevusers"])?array_unique($_POST["prevusers"]):"";
	$IncUsers =  substr($_POST["IncUsers"],0,-1);
	$nextUsers = explode(",", $IncUsers);
	$nextUsers =  is_array($nextUsers)?array_unique($nextUsers):"";

	if(!is_array($prevUsers)) $prevUsers = array();
	if(!is_array($nextUsers)) $nextUsers = array();
	if(is_array($nextUsers) && count($nextUsers)==0 && is_array($prevUsers) && count($prevUsers)==0){
		ListGroup();
		return;
	}

	////// root user
	$root_arr = array();
	$rs = $dbconn->Execute("select id from ".USERS_TABLE." WHERE root_user='1' or guest_user='1'");
	while(!$rs->EOF){
		array_push($root_arr,$rs->fields[0]);
		$rs->MoveNext();
	}

	////// 'd' groups
	$d_arr = array();
	$rs=$dbconn->Execute("select id from ".GROUPS_TABLE." WHERE type='d' ");
	while(!$rs->EOF){
		array_push($d_arr,$rs->fields[0]);
		$rs->MoveNext();
	}

	for($i=0; $i<count($prevUsers); $i++){
		if(!in_array($prevUsers[$i], $nextUsers) && !in_array($prevUsers[$i],$root_arr) && $prevUsers[$i]!=0){	/// if element not in array (old user not in new list) delete him from table
			for($j=0;$j<count($d_arr);$j++){
				array_push($values_arr, " ( '".$prevUsers[$i]."', '".$d_arr[$j]."')");	 //// add in 'd' groups
			}
			array_push($delete_arr, $prevUsers[$i]);
		}
	}

	for($i=0; $i<count($nextUsers); $i++){
		if(!in_array($nextUsers[$i], $prevUsers) && !in_array($nextUsers[$i],$root_arr) && $nextUsers[$i]!=0){	/// if element not in array (new user not in old list) add him into table
			array_push($delete_arr, $nextUsers[$i]);
			array_push($values_arr, " ( '".$nextUsers[$i]."', '".$id_group."')");
			$rs = $dbconn->Execute("INSERT INTO ".USER_GROUP_TABLE." (id_user, id_group) values ( '".$nextUsers[$i]."', '".$id_group."')");
                        if (GetSiteSettings("use_pilot_module_newsletter")){
			        UpdateUserRealestateMailingList($nextUsers[$i]);
                        }
		}
	}

	$values_str = implode(", ", $values_arr);
	$delete_str = implode(", ", $delete_arr);
	$delete_arr = explode(", ",$delete_str);


	if(strlen($delete_str)>0)
		$dbconn->Execute("DELETE FROM ".USER_GROUP_TABLE." WHERE id_user in (".$delete_str.")");
	if(strlen($values_str)>0)
		$rs = $dbconn->Execute("insert into  ".USER_GROUP_TABLE." (id_user, id_group) values ".$values_str);
        if (GetSiteSettings("use_pilot_module_newsletter")){
        	foreach ($values_arr as $user_id){
         		UpdateUserRealestateMailingList($user_id);
        	}
        }

	ListGroup();
	return;
}

function GroupUsersList() {
	global $smarty, $dbconn, $config, $lang;

	$sel = "group_users_list";
	$id_group = (isset($_REQUEST["id_group"]) && !empty($_REQUEST["id_group"])) ? intval($_REQUEST["id_group"]) : 0;
	
	if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_groups.php";
	IndexAdminPage('admin_groups');
	CreateMenu('admin_lang_menu');

	$lang["groups"] = GetLangContent('groups');

	$smarty->assign("add_to_lang", "&sel=$sel&id_group=".$id_group);

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	$letter = (!isset($_REQUEST["letter"]) || strval($_REQUEST["letter"]) == "*") ? "*" : intval($_REQUEST["letter"]);	
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : 1;
	$order = (isset($_REQUEST["order"]) && intval($_REQUEST["order"]) > 0) ? intval($_REQUEST["order"]) : 1;
	$s_type = (isset($_REQUEST["s_type"]) && intval($_REQUEST["s_type"]) > 0) ? intval($_REQUEST["s_type"]) : 1;	
	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? trim($_REQUEST["search"]) : "";
		
	// search
	$search_str = "";
	if(strval($search)){
		$search = strip_tags($search);
		switch($s_type){
			case "1": $search_str=" AND u.login LIKE '%".$search."%'"; break;
			case "2": $search_str=" AND u.fname LIKE '%".$search."%'"; break;
			case "3": $search_str=" AND u.sname LIKE '%".$search."%'"; break;
			case "4": $search_str=" AND u.email LIKE '%".$search."%'"; break;
		}
	}
	$smarty->assign("search", $search);
	$smarty->assign("s_type", $s_type);

	// letter
	if(strval($letter) != "*") {
		$letter_str = " lower(substring(u.email,1,1)) ='".strtolower(chr($letter))."'";
	} else {
		$letter_str = "";
	}
	$smarty->assign("letter", $letter);

	/// letter link
	$param_letter = $file_name."?sel=$sel&sorter=".$sorter."&order=".$order."&id_group=".$id_group."&letter=";
	$used_search_form = ($search_str) ? true : false;
	$letter_links = LettersLink_eng($param_letter, $letter, $used_search_form);
	$smarty->assign("letter_links", $letter_links);

	// sorter
	switch ($order){
		case "1":
			$order_str = " ASC";
			$order_new = 2;
			$order_icon = "&darr;";
			break;
		default:
			$order_str = " DESC";
			$order_new = 1;
			$order_icon = "&uarr;";
			break;
	}
	$smarty->assign("order", $order_new);
	$smarty->assign("order_icon", $order_icon);

	$sorter_str = "  ORDER BY ";
	if(intval($sorter)>0){
		switch($sorter) {
			case "1": $sorter_str.=" u.fname"; break;
			case "2": $sorter_str.=" u.email"; break;
			case "3": $sorter_str.=" u.user_type"; break;
		}
		$sorter_str .= $order_str;
	} else {
		$sorter_str .= " u.fname";
	}
	$smarty->assign("sorter", $sorter);

	$group_str = "ug.id_group='$id_group'";
	if($letter_str){
		$where_str = "where ".$letter_str." AND ".$group_str;
	}elseif($search_str){
		$where_str = "where u.id>0 ".$search_str." AND ".$group_str;
	}else{
		$where_str = "where $group_str";
	}
	$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u LEFT JOIN ".USER_GROUP_TABLE." ug ON ug.id_user=u.id $where_str";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	if ($num_records>0) {
		$rows_num_page = GetSiteSettings('admin_rows_per_page');
		// page
		$lim_min = ($page-1)*$rows_num_page;
		$lim_max = $rows_num_page;
		$limit_str = " limit ".$lim_min.", ".$lim_max;
		// query
		$strSQL = "	SELECT 	u.id, u.fname, u.sname, u.email,
					u.root_user, u.guest_user, u.status, u.user_type,
					DATE_FORMAT(b.date_begin, '".$config["date_format"]." %H:%i:%s') as date_begin,
					DATE_FORMAT(b.date_end, '".$config["date_format"]." %H:%i:%s') as date_end
					FROM ".USERS_TABLE."  u
					LEFT JOIN ".BILLING_USER_PERIOD_TABLE." b on b.id_user=u.id
					LEFT JOIN ".USER_GROUP_TABLE." ug ON ug.id_user=u.id
					 ".$where_str." ".$sorter_str.$limit_str;
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		if ($rs->RowCount()>0){
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$user[$i]["number"] = ($page-1)*$rows_num_page+($i+1);
				$user[$i]["id"] = $row["id"];
				$user[$i]["edit_link"] = "admin_users.php?sel=edit_user&from_file=admin_groups&from_file_sel=$sel&from_file_id_group=$id_group&id_user=".$user[$i]["id"];
				$user[$i]["name"] = stripslashes($row["fname"]." ".$row["sname"]);
				$user[$i]["email"] = $row["email"];
				$user[$i]["status"] = intval($row["status"]);
				$user[$i]["user_type"] = $row["user_type"];
				$user[$i]["root"] = $row["root_user"];
				$user[$i]["guest"] = $row["guest_user"];

				//dates in group
				$user[$i]["dates"] = ($row["date_begin"] && $row["date_end"])?$row["date_begin"]." - ".$row["date_end"]:"";
				//payment history link
				$user[$i]["payments_link"] = "";
				$strSQL = "SELECT DISTINCT id FROM ".BILLING_REQUESTS_TABLE." WHERE id_user='".$user[$i]["id"]."' ";
				$res = $dbconn->Execute($strSQL);
				if ( $res->RowCount()>0 ) {
					$user[$i]["payments_link"] = "admin_payment.php?sel=user_history&from_file=admin_groups&from_file_sel=$sel&from_file_id_group=$id_group&id_user=".$user[$i]["id"];
				}

				$rs->MoveNext();
				$i++;
			}

			$smarty->assign("user", $user);
			$smarty->assign("page", $page);
			$param = $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&sorter=".$sorter."&order=".$order."&id_group=".$id_group."&";
			$dop_param["left_arrow_name"] = "&lt;&lt;";
			$dop_param["right_arrow_name"] = "&gt;&gt;";
			$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page, $dop_param) );
		}
	}

	$smarty->assign("sorter_link", $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&order=".$order_new."&id_group=".$id_group);

	$form["action"] = 	$file_name;
	$form["hiddens"] = "<input type=hidden name=sel value=$sel>
						<input type=hidden name=id_group value=$id_group>";
	$smarty->assign("form", $form);

	$smarty->assign("id_group", $id_group);
	$smarty->assign("groupname", $lang["groups"][$id_group]);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_groups_users.tpl");
	exit;
}

/**
 * Get perrmissions on module files of the group to default
 *
 */
function SetDefaultPerms() {
	global $config;

	$free_user_group_default_module = GetSiteSettings("free_user_group_default_module");
	$default_group_modules = explode(",", $free_user_group_default_module);

	EditForm("", "", $default_group_modules);
	exit();
}
?>