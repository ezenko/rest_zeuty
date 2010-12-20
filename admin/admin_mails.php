<?php
/**
* Mails (notifications) information for admin
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:24 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/class.object2xml.php";
include "../include/class.images.php";
include "../include/class.settings_manager.php";

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

$section = (isset($_REQUEST["section"]) && !empty($_REQUEST["section"])) ? $_REQUEST["section"] : "admin";
$edit = (isset($_REQUEST["edit"]) && !empty($_REQUEST["edit"])) ? intval($_REQUEST["edit"]) : 0;
$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? $_REQUEST["language_id"] : $config["default_lang"];

if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
else
	$file_name = "admin_mails.php";

IndexAdminPage ('admin_mails');
CreateMenu('admin_lang_menu');

$mails = array();
$format = "_text";

$is_for_admin = ($section == "admin") ? 1 : 0;
$strSQL = "SELECT xml, tpl, unsubscribe_possible, id_subscribe ".
		  "FROM ".SYSTEM_LETTERS_TABLE." WHERE is_for_admin='$is_for_admin'";
$rs = $dbconn->Execute($strSQL);
while (!$rs->EOF) {
	$mail = $rs->GetRowAssoc(false);
	$mail["tpl"] = $mail["tpl"].$format;
	$mails[] = $mail;
	$rs->MoveNext();
}

/**
 * Get notifications settings
 */
$settings_manager = new SettingsManager();
$data["site_email"] = $settings_manager->GetSiteSettings("site_email");

$lang_default = GetLangContent("mail_content_default_select");
$data["site_name"] = $lang_default["site_name"];
$data["admin_name"] = GetAdminName();
$smarty->assign("data", $data);

/**
 * Edit mails content
 */
if ($edit) {
	$lang_path = LangPathById($current_lang_id);
	$xml_name = $_REQUEST["xml"];

	if (isset($_REQUEST["save"]) && $_REQUEST["save"] == 1) {
		/**
		 * Save xml file
		 */
		$is_magic_quotes = intval(ini_get("magic_quotes_gpc"));

		$file_path = $config["site_path"].$lang_path.$_REQUEST["edit_file"].".xml";
		$xml_parser = new SimpleXmlParser( $file_path );
		$xml_root = $xml_parser->getRoot();
		for ( $i = 0; $i < $xml_root->childrenCount; $i++ ) {
			$str = $_REQUEST["lang"][$xml_root->children[$i]->attrs["name"]];
			$xml_root->children[$i]->value = ($is_magic_quotes) ? stripslashes($str) : $str ;
		}
		$obj_saver = new Object2Xml( true );
		$obj_saver->Save( $xml_root, $file_path );
	}

	foreach ($mails as $mail) {
		if ($mail["xml"] == $xml_name) {
			$smarty->assign("mail_content", GetMailContentReplace($mail["xml"], $current_lang_id));
			$smarty->assign("tpl_name", $mail["tpl"]);
			$smarty->assign("unsubscribe_possible", $mail["unsubscribe_possible"]);
			break;
		}
	}

	/**
	 * Get static data to mail content from language file
	 */
	$data = array();
	$data["fname"] = $lang["content"]["preview_fname"];
	$data["sname"] = $lang["content"]["preview_sname"];
	$data["to_name"] = $data["fname"]." ".$data["sname"];
	$data["name"] = $data["to_name"];
	$data["login"] = $lang["content"]["preview_login"];
	$data["pass"] = $lang["content"]["preview_password"];
	$data["add_on_account"] = $lang["content"]["preview_add_on_account"];
	$data["account"] = $lang["content"]["preview_account"];
	
	$data["agent_name"] = $lang["content"]["agent_name"];
	$data["company_name_user"] = $lang["content"]["company_name_user"];
	$data["link"] = $lang["content"]["link"];
	$data["approve_link"] = $lang["content"]["link"];
	$data["name_user"] = $data["fname"]." ".$data["sname"];
	$data["realtor_name"] = $data["fname"]." ".$data["sname"];
	$data["user_name"] = $data["fname"]." ".$data["sname"];
	$data["company_name"] = $lang["content"]["company_name"];

	$strSQL = "SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='".GetSiteSettings("site_unit_costunit")."' ";
	$rs = $dbconn->Execute($strSQL);
	$data["cur"] = $rs->fields[0];

	$data["unsubscribe_possible"] = $mail["unsubscribe_possible"];

	if (GetSiteSettings("use_registration_confirmation")) {
		$data["confirm_link"] = $lang["content"]["preview_confirm_link"];
	}
	//$data["type"] = 2;

	$smarty->assign("data", $data);

	/**
	 * Get xml File Content
	 */
	$lang_content = array();
	$lang_content[] = array("name" => "lang_default_select", "file" => "mail_content_default_select");
	$lang_content[] = array("name" => "lang_content", "file" => $xml_name);

	foreach ($lang_content as $cont) {
		$mail_content = array();
		$name = array();
		$descr = array();
		$values = array();

		$xml_parser = new SimpleXmlParser($config["site_path"].$lang_path.$cont["file"].".xml");
		$xml_root = $xml_parser->getRoot();
		$count = $xml_root->childrenCount;

		foreach ( $xml_root->children as $node) {
			switch($node->tag){
				case "lines":
					array_push($name,$node->attrs["name"]);
					array_push($descr,$node->attrs["descr"]);
					array_push($values, $node->value );
				break;
			}
		}
		$mail_content["name"] = $name;
		$mail_content["descr"] = $descr;
		$mail_content["values"] = $values;

		$content[] = array("name" => $cont["name"], "file" => $cont["file"], "values" => $mail_content);
	}
	$smarty->assign("content", $content);
	$smarty->assign("xml_file", $xml_name);
} else {
	$strSQL = "SELECT COUNT(id) AS cnt FROM ".USERS_TABLE." WHERE guest_user='0'";
	$rs = $dbconn->Execute($strSQL);
	$total_users_cnt = $rs->fields[0];			
	foreach ($mails as $mail_id => $mail) {
		if ($mail["id_subscribe"]) {			
			$strSQL = "SELECT COUNT(su.id_user) AS cnt FROM ".SUBSCRIBE_USER_TABLE." su ".
					  "LEFT JOIN ".USERS_TABLE." u ON u.id=su.id_user ".
					  "WHERE su.id_subscribe='{$mail["id_subscribe"]}' AND ".
					  "u.status='1' AND u.active='1' AND u.access='1'";					  
			$rs = $dbconn->Execute($strSQL);
			$mails[$mail_id]["users_cnt"] = $rs->fields[0];				
		} else {
			$mails[$mail_id]["users_cnt"] = $total_users_cnt;
		}
	}	
	
	$smarty->assign("mails", $mails);
}

$smarty->assign("section", $section);
$smarty->assign("edit", $edit);
$smarty->assign("current_lang_id", $current_lang_id);
$smarty->assign("file_name", $file_name);
$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_mails.tpl");

?>