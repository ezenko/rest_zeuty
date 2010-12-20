<?php
/**
* Payment services user groups membership payments management
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 11:44:00 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";

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

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "groups";
$smarty->assign("sel", $sel);

$section = (isset($_REQUEST["section"]) && !empty($_REQUEST["section"])) ? $_REQUEST["section"] : "sec_group_payment";
$smarty->assign("section", $section);

$lang["users_types"] = GetLangContent('users_types');
$lang["groups"] = GetLangContent('groups');

CreateMenu('admin_lang_menu');

switch($sel){
	//pay services fee and settings
	case "list_services": ListServices(); break;
	case "save": SaveServices(); break;
	case "add_bonus": AddBonus($section); break;
	case "delete_bonus": DeleteBonus($section); break;
	case "add_sell_lease": AddSellLease($section); break;
	case "delete_sell_lease": DeleteSellLease($section); break;
	//membership fee
	case "groups": GroupListBilling($section); break;
	case "speed": GroupsChange($section); break;

	default: ListServices();
}

function ListServices($data = array(), $err="") {
	global $smarty, $dbconn, $config, $lang;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_pay_services.php";

	IndexAdminPage ('admin_pay_services');

	if ($err) {
		/**
		 * Load user settings to the form
		 */
		GetErrors("empty_fields");
		$smarty->assign("err", $err);
		$smarty->assign("data", $data);
	} else {
		/**
		 * Get settings
		 */
		$smarty->assign("data", GetSiteSettings(array("top_search_cost", "slideshow_cost", "slideshow_period", "featured_in_region_cost", "featured_in_region_period", "use_listing_completion_bonus", "listing_completion_bonus_number", "use_sell_lease_payment", "commission_percent")) );
	}
	/**
	 * get bonus table
	 */
	$bonus = array();
	$strSQL = "SELECT id, percent, amount FROM ".BONUS_SETTINGS_TABLE." ORDER BY percent ASC";
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$bonus[] = $rs->getRowAssoc( false );
		$rs->MoveNext();
	}
	$smarty->assign("bonus", $bonus);
	$smarty->assign("bonus_cnt", count($bonus));

	/**
	 * get sell/lease ads publivation table table
	 */
	$sell_lease = array();
	$strSQL = "SELECT id, ads_number, amount FROM ".SELL_LEASE_PAYMENT_SETTINGS_TABLE." ORDER BY ads_number ASC";
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$sell_lease[] = $rs->getRowAssoc( false );
		$rs->MoveNext();
	}
	$smarty->assign("sell_lease", $sell_lease);
	$smarty->assign("sell_lease_cnt", count($sell_lease));

	$smarty->assign("costunit", GetSiteSettings('site_unit_costunit'));
	$smarty->assign("script", "div_actions");
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_pay_services.tpl");
	exit;
}

/**
 * Save services settings
 *
 * @param void
 * @return void
 */
function SaveServices() {
	global $dbconn, $config;

	$err = array();
	$data["top_search_cost"] = trim($_REQUEST["top_search_cost"]);
	$data["slideshow_cost"] = trim($_REQUEST["slideshow_cost"]);
	$data["slideshow_period"] = trim($_REQUEST["slideshow_period"]);
	$data["featured_in_region_cost"] = trim($_REQUEST["featured_in_region_cost"]);
	$data["featured_in_region_period"] = trim($_REQUEST["featured_in_region_period"]);

	$data["use_listing_completion_bonus"] = (isset($_REQUEST["use_listing_completion_bonus"]) && !empty($_REQUEST["use_listing_completion_bonus"])) ? intval($_REQUEST["use_listing_completion_bonus"]) : "0";

	$data["use_sell_lease_payment"] = (isset($_REQUEST["use_sell_lease_payment"]) && !empty($_REQUEST["use_sell_lease_payment"])) ? intval($_REQUEST["use_sell_lease_payment"]) : "0";

	$data["listing_completion_bonus_number"] = trim($_REQUEST["listing_completion_bonus_number"]);
	$data["commission_percent"] = trim($_REQUEST["commission_percent"]);

	if (!$data["top_search_cost"] || !IsPositiveFloat($data["top_search_cost"])) {
		$err["top_search_cost"] = "positive_float";
		$data["top_search_cost"] = "";
	} else {
		$data["top_search_cost"] = floatval($data["top_search_cost"]);
	}
	if (!$data["slideshow_cost"] || !IsPositiveFloat($data["slideshow_cost"])) {
		$err["slideshow_cost"] = "positive_float";
		$data["slideshow_cost"] = "";
	} else {
		$data["slideshow_cost"] = floatval($data["slideshow_cost"]);
	}
	if (!$data["slideshow_period"] || !IsPositiveInt($data["slideshow_period"])) {
		$err["slideshow_period"] = "positive_int";
		$data["slideshow_period"] = "";
	} else {
		$data["slideshow_period"] = intval($data["slideshow_period"]);
	}
	if (!$data["featured_in_region_cost"] || !IsPositiveFloat($data["featured_in_region_cost"])) {
		$err["featured_in_region_cost"] = "positive_float";
		$data["featured_in_region_cost"] = "";
	} else {
		$data["featured_in_region_cost"] = floatval($data["featured_in_region_cost"]);
	}
	if (!$data["featured_in_region_period"] || !IsPositiveInt($data["featured_in_region_period"])) {
		$err["featured_in_region_period"] = "positive_int";
		$data["featured_in_region_period"] = "";
	} else {
		$data["featured_in_region_period"] = intval($data["featured_in_region_period"]);
	}
	if ($data["use_listing_completion_bonus"] && (!$data["listing_completion_bonus_number"] || !IsPositiveInt($data["listing_completion_bonus_number"]))) {
		$err["listing_completion_bonus_number"] = "positive_int";
		$data["listing_completion_bonus_number"] = "";
	} else {
		$data["listing_completion_bonus_number"] = intval($data["listing_completion_bonus_number"]);
	}
	if (!IsPositiveInt($data["commission_percent"]) && ($data["commission_percent"] != 0)) {
		$err["commission_percent"] = "not_minus_int";
		$data["commission_percent"] = "";
	} elseif ($data["commission_percent"] > 99) {
		$err["commission_percent"] = "big_percent";
		$data["commission_percent"] = "";
	} else {
		$data["commission_percent"] = intval($data["commission_percent"]);
	}	
	if (count($err) > 0) {
		ListServices($data, $err);
		exit();
	}
	foreach ($data as $key=>$value) {
		$strSQL = "UPDATE ".SETTINGS_TABLE." SET value='$value' WHERE name='$key'";
		$dbconn->Execute($strSQL);
	}

	ListServices();
	exit();
}

/**
 * Add bonus value for profile completion
 *
 * @param string $section - section name
 * @return void
 */
function AddBonus($section) {
	global $dbconn, $config;

	$err = array();
		
	$data["lc_bonus_percent"] = trim($_REQUEST["lc_bonus_percent"]);
	$data["lc_bonus_amount"] = trim($_REQUEST["lc_bonus_amount"]);

	if (!$data["lc_bonus_percent"] || !IsPositiveInt($data["lc_bonus_percent"]) || intval($data["lc_bonus_percent"]) > 100) {
		$err["lc_bonus_percent"] = "invalid_lc_bonus_percent";
		$data["lc_bonus_percent"] = "";
	}
	if (!$data["lc_bonus_amount"] || !IsPositiveFloat($data["lc_bonus_amount"])) {
		$err["lc_bonus_amount"] = "invalid_lc_bonus_amount";
		$data["lc_bonus_amount"] = "";
	}
	if (count($err) > 0) {
		ListServices($data, $err);
		exit();
	}

	$strSQL = "SELECT COUNT(id) AS cnt FROM ".BONUS_SETTINGS_TABLE." WHERE ".
			  "percent='".intval($data["lc_bonus_percent"])."'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] > 0) {
		$err["lc_bonus_percent_dublicate"] = "lc_bonus_percent_dublicate";
		ListServices($data, $err);
	}

	$strSQL = "INSERT INTO ".BONUS_SETTINGS_TABLE." SET ".
			  "percent='".intval($data["lc_bonus_percent"])."', ".
			  "amount='".floatval($data["lc_bonus_amount"])."'";
	$dbconn->Execute($strSQL);

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_pay_services.php?sel=list_services&section=$section");
	exit();
}

/**
 * Add payment on sell/lease ads publication
 *
 * @param string $section - section name
 * @return void
 */
function AddSellLease($section) {
	global $dbconn, $config;

	$data["sell_lease_ads_number"] = trim($_REQUEST["sell_lease_ads_number"]);
	$data["sell_lease_amount"] = trim($_REQUEST["sell_lease_amount"]);

	$err = array();
	if (!$data["sell_lease_ads_number"] || !IsPositiveInt($data["sell_lease_ads_number"])) {
		$err["sell_lease_ads_number"] = "invalid_sell_lease_ads_number";
		$data["sell_lease_ads_number"] = "";
	}
	if (!$data["sell_lease_amount"] || !IsPositiveFloat($data["sell_lease_amount"])) {
		$err["sell_lease_amount"] = "invalid_sell_lease_amount";
		$data["sell_lease_amount"] = "";
	}
	if (count($err) > 0) {
		ListServices($data, $err);
		exit();
	}

	$strSQL = "SELECT COUNT(id) AS cnt FROM ".SELL_LEASE_PAYMENT_SETTINGS_TABLE." WHERE ".
			  "ads_number='".intval($data["sell_lease_ads_number"])."'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] > 0) {
		$err["sell_lease_ads_number_dublicate"] = "sell_lease_ads_number_dublicate";
		ListServices($data, $err);
	}

	$strSQL = "INSERT INTO ".SELL_LEASE_PAYMENT_SETTINGS_TABLE." SET ".
			  "ads_number='".intval($data["sell_lease_ads_number"])."', ".
			  "amount='".floatval($data["sell_lease_amount"])."'";
	$dbconn->Execute($strSQL);

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_pay_services.php?sel=list_services&section=$section");
	exit();
}

/**
 * Delete bonus by id
 *
 * @param string $section
 * @return void
 */
function DeleteBonus($section) {
	global $dbconn, $config;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if (!$id) {
		ListServices();
		exit();
	}
	$strSQL = "SELECT COUNT(id) AS cnt FROM ".BONUS_SETTINGS_TABLE;
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] == 1) {
		/**
		 * switch off bonus service
		 */
		$dbconn->Execute("UPDATE ".SETTINGS_TABLE." SET value='0' WHERE name='use_listing_completion_bonus'");
	}
	$dbconn->Execute("DELETE FROM ".BONUS_SETTINGS_TABLE." WHERE id='".$id."'");

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_pay_services.php?sel=list_services&section=$section");
	exit();
}

/**
 * Delete sell/lease payment by id
 *
 * @param string $section
 * @return void
 */
function DeleteSellLease($section) {
	global $dbconn, $config;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if (!$id) {
		ListServices();
		exit();
	}
	$strSQL = "SELECT COUNT(id) AS cnt FROM ".SELL_LEASE_PAYMENT_SETTINGS_TABLE;
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0] == 1) {
		/**
		 * switch off bonus service
		 */
		$dbconn->Execute("UPDATE ".SETTINGS_TABLE." SET value='0' WHERE name='use_sell_lease_payment'");
	}
	$dbconn->Execute("DELETE FROM ".SELL_LEASE_PAYMENT_SETTINGS_TABLE." WHERE id='".$id."'");

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_pay_services.php?sel=list_services&section=$section");
	exit();
}

/**
 * Get group membership payment list
 *
 * @param string $section
 * @param string $err
 * @return void
 */
function GroupListBilling($section, $err=""){
	global $smarty, $dbconn, $config, $page, $lang;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_pay_services.php";

	IndexAdminPage('admin_billing');

	if ($err){
		$form["err"] = $err;
		$smarty->assign("error", $lang["content"][$err]);	
	}
	$settings["site_unit_costunit"] = GetSiteSettings('site_unit_costunit');

	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : "cost";
	$order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 1;

	///////// sorter & order
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
	switch($sorter) {
		case "cost": $sorter_str .= " cost"; break;
	}
	$smarty->assign("sorter", $sorter);

	///// groups
	$strSQL = "SELECT id, speed from ".GROUPS_TABLE."  where type='f' order by id";
	$rs = $dbconn->Execute($strSQL);
	$i=0;
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$groups[$i]["id"] = $row["id"];
		$groups[$i]["name"] = $lang["groups"][$row["id"]];
		$groups[$i]["cost"] = $row["speed"];

		$j = 0;
		$strSQL_p = "SELECT id, cost, period, amount FROM ".GROUP_PERIOD_TABLE." ".
					"WHERE id_group='".$row["id"]."' AND status='1' $sorter_str $order_str";
		$rs_p = $dbconn->Execute($strSQL_p);
		while(!$rs_p->EOF){
			$row_p = $rs_p->GetRowAssoc(false);
			$groups[$i]["period"][$j]["id"] = $row_p["id"];
			$groups[$i]["period"][$j]["count"] = $row_p["amount"];
			$groups[$i]["period"][$j]["period"] = $row_p["period"];
			$groups[$i]["period"][$j]["cost"] = $row_p["cost"];
			$groups[$i]["period"][$j]["del_link"] = "./".$file_name."?sel=speed&par=delete&id=".$row_p["id"];
			$rs_p->MoveNext();
			$j++;
		}
		$rs->MoveNext();
		$i++;
	}
	$smarty->assign("groups", $groups);

	$form["hiddens"] = "<input type=hidden name=sel value=speed><input type=hidden name=section value=$section>";
	$form["action"] = $file_name;
	$form["costunits"] = $settings["site_unit_costunit"];
	$form["currency"] = $form["costunits"];

	$smarty->assign("form", $form);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_pay_services.tpl");
	exit;
}

/**
 * Add & delete group membership payment
 *
 * @param string $section
 * @return void
 */
function GroupsChange($section){
	global $smarty, $dbconn, $config, $page, $lang;

	$err = "";
	$settings["site_unit_costunit"] = GetSiteSettings('site_unit_costunit');

	$par = (isset($_GET["par"]) && !empty($_GET["par"])) ? $_GET["par"] : "";
	if($par == "delete") {
		$id = intval($_REQUEST["id"]);
		$dbconn->Execute("update ".GROUP_PERIOD_TABLE." set status='0' where id='".$id."'");
	} else {
		$group = intval($_POST["group"]);
		$count = intval($_POST["count"]);
		$period = strval($_POST["period"]);
		$cost = $_POST["cost"];
		if ($group && $period && $cost && is_numeric($cost) && $count) {
			$cost = floatval($_POST["cost"]);
			$rs = $dbconn->Execute("SELECT count(*) from ".GROUP_PERIOD_TABLE." where id_group='".$group."' and period='".$period."' and amount='".$count."' and status='1'");
			if($rs->fields[0] == 0) {
				$dbconn->Execute("insert into ".GROUP_PERIOD_TABLE." (id_group, cost, period, amount, status) values ('".$group."', '".$cost."', '".$period."', '".$count."', '1')");
			} else {
				$err = "period_exist";
			}
		} else {
			$err = "wrong_fields";
		}
	}
	GroupListBilling($section, $err);
}
?>