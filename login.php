<?php
/**
* Login user page
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.7 $ $Date: 2009/02/02 07:41:48 $
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
if (GetSiteSettings("use_pilot_module_newsletter")) {
	include "./include/functions_newsletter.php";
}
$user = auth_index_user();

if (isset($_GET["go_to_url"]) && !empty($_GET["go_to_url"])) {
	$query = $_SERVER["QUERY_STRING"];	
	if ($query) {
		$query_arr = explode("&", $query);
		$form["hidden"] = "";
		foreach ($query_arr as $v){
			$s = explode("=", $v);
			$form["hidden"] .= "<input type=\"hidden\" name=\"".$s[0]."\" value=\"".$s[1]."\">";
		}		
	}
} elseif (isset($_GET["view"]) && $_GET["view"]=='yes'){
	$query = $_SERVER["QUERY_STRING"];
	if($query){
		$query_arr = array();
		$s = array();
		$query_arr = explode("&",$query);
		$form["hidden"] = "";
		foreach ($query_arr as $v){
			$new = str_replace("qw=./viewprofile.php?sel","from",$v);
			$s = explode("=",$new);
			$form["hidden"] .= "<input type=\"hidden\" name=\"".$s[0]."\" value=\"".$s[1]."\">";
		}
	}
} elseif (isset($_GET["mail"]) && $_GET["mail"]=='yes'){
	$query = $_SERVER["QUERY_STRING"];
	if($query){
		$query_arr = array();
		$s = array();
		$query_arr = explode("&",$query);
		$form["hidden"] = "";
		foreach ($query_arr as $v){
			$new = str_replace("qw=./mailbox.php?sel","from",$v);
			$s = explode("=",$new);
			$form["hidden"] .= "<input type=\"hidden\" name=\"".$s[0]."\" value=\"".$s[1]."\">";
		}
	}
} elseif (isset($_GET["contact"]) && $_GET["contact"]=='yes') {
	$query = $_SERVER["QUERY_STRING"];
	if($query){
		$query_arr = array();
		$s = array();
		$query_arr = explode("&",$query);
		$form["hidden"] = "";
		foreach ($query_arr as $v){
			$new = str_replace("qw=./contact.php?sel","from",$v);
			$s = explode("=",$new);
			$form["hidden"] .= "<input type=\"hidden\" name=\"".$s[0]."\" value=\"".$s[1]."\">";
		}
	}
} else {
	if (isset($_GET["from"]) && isset($_GET["login"])) {
		if ($_GET["from"]=='subscribe' &&  (strlen ($_GET["login"]) > 1) ) {
			$from = 'subscribe';
			$smarty->assign ("login", $_GET["login"]);
			$smarty->assign ("from", "subscribe");
		} elseif ($_GET["from"]=='mailto' && (strlen ($_GET["login"]) > 1) && ( intval($_GET["id_mail"] ) > 0) ) {
			$from = 'mailto';
			$smarty->assign ("login", $_GET["login"]);
			$smarty->assign ("id_mail", intval($_GET["id_mail"]));
			$smarty->assign ("from", "mailto");
		}
	}
}

if (!headers_sent() && isset($_POST["remember_me"])){
	setcookie("re_login", $_POST["login_lg"], time()+7200);
	setcookie("re_pass", md5($_POST["pass_lg"]), time()+7200);
}

/**
 * switch interface language
 */
$_SESSION["lang_cd"] = (isset($user[13])) ? $user[13] : 0;

if (intval($user[0]) && $user[3] != 1) {
	if (isset($_POST["go_to_url"]) && !empty($_POST["go_to_url"])) {
		echo "<script>parent.location.href='".$_POST["go_to_url"]."';parent.GB_hide();</script>";
	} elseif ($_POST["view"]=="yes") {
		if (isset($_POST["from"]) && $_POST["from"] == "more_ad") {			
			echo "<script>parent.location.href='".$config["site_root"]."/viewprofile.php?sel=".$_POST["from"]."&id_user=".$_POST["id_user"]."';parent.GB_hide();</script>";
		} elseif (isset($_POST["from"]) && $_POST["from"] == "print") {			
			echo "<script>parent.location.href='".$config["site_root"]."/viewprofile.php?sel=".$_POST["from"]."&id=".$_POST["id"]."';parent.GB_hide();</script>";
		} else {
			echo "<script>parent.location.href='".$config["site_root"]."/viewprofile.php?section=".$_POST["section"]."&amp;id=".$_POST["id_ad"]."';parent.GB_hide();</script>";
		}
	} elseif ($_POST["mail"]=="yes"){		
		if (!in_array("mhi_my_messages", $config["mode_hide_ids"])) {		
			echo "<script>parent.location.href='".$config["site_root"]."/mailbox.php?sel=".$_POST["from"]."&amp;id=".$_POST["id"]."&amp;section=".$_POST["section"]."&amp;id_ad=".$_POST["id_ad"]."';parent.GB_hide();</script>";
		} else {
			echo "<script>parent.location.href='".$config["site_root"]."/viewprofile.php?sel=".$_POST["from"]."&id=".$_POST["id_ad"]."';parent.GB_hide();</script>";
		}
	} elseif ($_POST["contact"]=="yes") {
		echo "<script>parent.location.href='".$config["site_root"]."/contact.php?sel=".$_POST["from"]."&amp;id=".$_POST["id"]."&amp;section=".$_POST["section"]."&amp;id_ad=".$_POST["id_ad"]."';parent.GB_hide();</script>";
	} else {
		echo "<script>parent.location.href='".$config["site_root"]."/homepage.php?from=".$_GET["from"]."&id_mail=".$_POST["id_mail"]."';parent.GB_hide();</script>";
	}
}

if(isset($_SERVER["PHP_SELF"]))
$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
else
$file_name = "login.php";

IndexHomePage('login');

$smarty->assign("lost_passw_link","./lost_pass.php?lang_code=$lang_code");

if ($user == "err") {
	GetErrors("auth_failed");
}
if ($user == "no_access") {
	GetErrorsWithLink("auth_access");
}
if ($user == "no_confirm") {
	GetErrors("no_confirm");
}
if (isset($form)) {
	$smarty->assign("form", $form);
}
$smarty->assign("file_name", $file_name);
$smarty->display(TrimSlash($config["index_theme_path"])."/login_table.tpl");

?>