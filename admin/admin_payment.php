<?php
/**
* Billings management:
* 1. payment systems settings
* 2. payments history
* 3. lists of payments and write-offs
* 4. approve and decline payment for user
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.5 $ $Date: 2009/01/14 09:44:06 $
**/
include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";

include "../include/functions_xml.php";
include "../include/class.object2xml.php";
include "../include/functions_mail.php";
$auth = auth_user();
$cur = GetSiteSettings('site_unit_costunit');
if ( (!($auth[0]>0))  || (!($auth[4]==1))){
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

CreateMenu('admin_lang_menu');

$lang["users_types"] = GetLangContent('users_types');
$lang["pays"] = GetLangContent('admin/admin_billing');
$lang["groups"] = GetLangContent('groups');
$lang["smscoin_operators"] = GetLangContent('smscoin_operators');
$lang["errors"] = GetLangContent("errors");

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;

if (isset($_REQUEST["err"]) && !empty($_REQUEST["err"])) {
	GetErrors($_REQUEST["err"]);
}

$smarty->assign("sel", $sel);

switch($sel){
	case "settings": SettingsBilling(); break;
	case "saveset": SaveSettingsBilling(); break;
	case "list_payments": ListPayments(); break;
	case "list_spended": ListSpended(); break;
	case "approve_req": Approve(); break;
	case "decline_req": Decline(); break;
	case "users_list_history": ListUsers();
	case "user_history": UserPaymentHistory();	
	case "smscoin_delete_tarif": SmsCoinDeleteTarif();
	case "smscoin_delete_operator": SmsCoinDeleteOperator();
	case "smscoin_add_operator": SmsCoinAddOperator();
	case "smscoin_add_tarif": SmsCoinAddTarif();
	case "remove_currency": RemoveCurrency();
	case "add_currency": AddCurrency();
	case "save_changes": SaveChanges();
	case "manual_payment_details": PaymentDetails();
	default: ListUsers();
}

function SmsCoinDeleteTarif() {
	global $config, $dbconn;

	$tarif_id = (isset($_REQUEST["tarif_id"]) && !empty($_REQUEST["tarif_id"])) ? intval($_REQUEST["tarif_id"]) : 0;
	$name = trim($_REQUEST["settype"]);

	if ($tarif_id && $name) {
		$strSQL = "DELETE FROM ".BILLING_SYS_.$name._TARIF." ".
	        	  "WHERE id='$tarif_id'";
	    $dbconn->Execute($strSQL);
	}

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_payment.php?sel=settings&settype=$name");
	exit();
}

function SmsCoinDeleteOperator() {
	global $config, $dbconn;

	$operator_id = (isset($_REQUEST["operator_id"]) && !empty($_REQUEST["operator_id"])) ? intval($_REQUEST["operator_id"]) : 0;
	$name = trim($_REQUEST["settype"]);

	if ($operator_id && $name) {
		$strSQL = "DELETE FROM ".BILLING_SYS_.$name._OPERATOR." ".
	        	  "WHERE id='$operator_id'";
	    $dbconn->Execute($strSQL);
	    $strSQL = "DELETE FROM ".BILLING_SYS_.$name._TARIF." ".
	        	  "WHERE operator_id='$operator_id'";
	    $dbconn->Execute($strSQL);
	    /**
		 * Delete operator name from language files
		 */
	    DeleteLangString($config["site_path"]."/lang/", "smscoin_operators.xml", $operator_id);
	}

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_payment.php?sel=settings&settype=$name");
	exit();
}

function SmsCoinAddOperator() {
	global $config, $dbconn;

	$operator = (isset($_REQUEST["operator"]) && !empty($_REQUEST["operator"])) ? trim($_REQUEST["operator"]) : "";
	$name = trim($_REQUEST["settype"]);

	if ($operator && $name) {
		$strSQL = "INSERT INTO ".BILLING_SYS_.$name._OPERATOR." SET ".
	        	  "name='".addslashes($operator)."'";
	    $dbconn->Execute($strSQL);

	    $id_operator = $dbconn->Insert_ID();
	    /**
		 * Add new operator name to the language files of each language
		 */
	    AddLangString($config["site_path"]."/lang/", 'smscoin_operators.xml', $id_operator, $operator);
	}

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_payment.php?sel=settings&settype=$name");
	exit();
}

function SmsCoinAddTarif() {
	global $config, $dbconn;

	$tarif = (isset($_REQUEST["tarif"]) && !empty($_REQUEST["tarif"])) ? ($_REQUEST["tarif"]) : 0;
	$tarif_operator = (isset($_REQUEST["tarif_operator"]) && !empty($_REQUEST["tarif_operator"])) ? intval($_REQUEST["tarif_operator"]) : 0;
	$name = trim($_REQUEST["settype"]);

	if (!is_numeric($tarif) || $tarif <= 0) {
    	GetErrors("smscoin_incorrect_tarif_value");
    	SettingsBilling();
    	return;
	} else {
		$tarif = floatval($tarif);
		if ($tarif && $tarif_operator && $name) {
			$strSQL = "SELECT COUNT(id) FROM ".BILLING_SYS_.$name._TARIF." WHERE ".
		        	  "operator_id='$tarif_operator' AND amount='$tarif'";
		    $rs = $dbconn->Execute($strSQL);
		    if ($rs->fields[0] > 0) {
		    	GetErrors("smscoin_tarif_exist");
		    	SettingsBilling();
		    	return;
		    }
			$strSQL = "INSERT INTO ".BILLING_SYS_.$name._TARIF." SET ".
		        	  "operator_id='$tarif_operator', amount='$tarif'";
		    $dbconn->Execute($strSQL);
		}

		header("Location: ".$config["server"].$config["site_root"]."/admin/admin_payment.php?sel=settings&settype=$name");
		exit();
	}
}

function SettingsBilling($err=""){
	global $smarty, $dbconn, $config, $file_name, $lang;

	$settype = (isset($_REQUEST["settype"]) && !empty($_REQUEST["settype"])) ? $_REQUEST["settype"] : "general";
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_payment.php";

	IndexAdminPage('admin_billing');

	if ($err) {
		$smarty->assign("error", $err);
	}

	$rs = $dbconn->Execute("Select * from ".BILLING_PAYSYSTEMS_TABLE);
	$i=1;
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$paysystems[$i]["value"] = $row["name"];
		$paysystems[$i]["id"] = $row["id"];
		$paysystems[$i]["name"] = $lang["pays"]["option_".$row["name"]];
		$i++;
		$rs->MoveNext();
	}

	$smarty->assign("paysystems", $paysystems);

	//$settype - template_name of the payment system
	if ($settype == "general") {
		$data["site_unit_costunit"] = GetSiteSettings('site_unit_costunit');

		$rs = $dbconn->Execute("Select abbr, id, name, symbol from ".UNITS_TABLE." ");
		$i = 0;
		while(!$rs->EOF){
			$currency[$i]["num"] = $i;
			$currency[$i]["abbr"] = $rs->fields[0];
			$currency[$i]["id"] = $rs->fields[1];
			$currency[$i]["name"] = $rs->fields[2];
			$currency[$i]["symbol"] = htmlentities($rs->fields[3]);
			$currency[$i]["symbol_view"] = $rs->fields[3];
			$rs->MoveNext(); $i++;
		}
		$smarty->assign("count", $i);
		$smarty->assign("currency", $currency);
		$smarty->assign("data", $data);
	} else {
		//include payment module and call to getBillingData() function
		$sys = "../include/systems/functions/".$settype.".php";
		include_once $sys;
		$data = getBillingData($settype, $lang);
		$smarty->assign("data", $data);
	}
	$form["action"] = $file_name;

	$smarty->assign("settype", $settype);
	$smarty->assign("form", $form);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_billing_settings_table.tpl");
	exit;
}

function SaveSettingsBilling(){
	global $smarty, $dbconn, $config, $lang;

	$settype = $_POST["settype"];
	
	$lang["buttons"] = GetLangContent("admin/admin_buttons");
	$lang["default_select"] = GetLangContent("admin/admin_default_select");
	
	//$settype - template_name of the payment system
	if ($settype == "general") {
		$site_unit_costunit = strval($_POST["currency"]);

		if(!strlen($site_unit_costunit)){
			$err = $lang["errors"]["invalid_fields"];
			$err .= "<br>".$lang["pays"]["currency"];
			SettingsBilling($err); return;
		}
		$strSQL = "Update ".SETTINGS_TABLE." set value='".$site_unit_costunit."' where name='site_unit_costunit'";
		$dbconn->Execute($strSQL);
	} else {
		//include payment module and call to getBillingData() function
		$sys = "../include/systems/functions/".$settype.".php";
		include_once $sys;
		$err = setBillingData($settype, $lang, $_POST);
		$data = getBillingData($settype, $lang);
		$smarty->assign("data", $data);
	}

	if ($settype == "smscoin" && $_REQUEST["smscoin_operators"]) {
		foreach ($_REQUEST["smscoin_operators"] as $oper_id=>$oper_value) {
			$oper_name = trim($oper_value);
			if ($oper_name != "") {
				/**
				 * Update operator name in the language file of the current language
				 */
				$file_path = $config["site_path"].$config["lang_path"]."smscoin_operators.xml";
				$xml_parser = new SimpleXmlParser( $file_path );
				$xml_root = $xml_parser->getRoot();
				for ( $i = 0; $i < $xml_root->childrenCount; $i++ ) {
					if ( $xml_root->children[$i]->attrs["name"] == $oper_id ) {
						$xml_root->children[$i]->value = $oper_name;
					}
				}
				$obj_saver = new Object2Xml( true );
				$obj_saver->Save( $xml_root, $file_path );
			}
		}
	}

	if (isset($err)) {
		SettingsBilling($err); return;
	}

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_payment.php?sel=settings&settype=$settype&err=success_save");
	exit();
}

function ListPayments() {
	global $smarty, $dbconn, $config, $lang, $cur;

	$sel = "list_payments";
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_payment.php";
	IndexAdminPage('admin_billing');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	$letter = (!isset($_REQUEST["letter"]) || strval($_REQUEST["letter"]) == "*") ? "*" : intval($_REQUEST["letter"]);	
	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? trim($_REQUEST["search"]) : "";
	$s_type = (isset($_REQUEST["s_type"]) && intval($_REQUEST["s_type"]) > 0) ? intval($_REQUEST["s_type"]) : 1;
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : 5;
	$order = (isset($_REQUEST["order"]) && intval($_REQUEST["order"]) > 0) ? intval($_REQUEST["order"]) : 0;
	
	///////// search
	$search_str = "";
	if(strval($search)){
		$search = strip_tags($search);
		switch($s_type){
			case "1": $search_str=" u.login like '%".$search."%'"; break;
			case "2": $search_str=" u.fname like '%".$search."%'"; break;
			case "3": $search_str=" u.sname like '%".$search."%'"; break;
			case "4": $search_str=" u.email like '%".$search."%'"; break;
		}
	}
	$smarty->assign("search", $search);
	$smarty->assign("s_type", $s_type);

	///////// letter
	if(strval($letter) != "*"){
		$letter_str = " lower(substring(u.email,1,1)) ='".strtolower(chr($letter))."'";
	}else{
		$letter_str = "";
	}
	$smarty->assign("letter", $letter);

	if($letter_str){
		$where_str = "where ".$letter_str." ";
	}elseif($search_str){
		$where_str = "where ".$search_str." ";
	}else{
		$where_str = "";
	}

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
	if(intval($sorter)>0){
		switch($sorter) {
			case "1": $sorter_str.=" u.fname"; break;
			case "2": $sorter_str.=" u.email"; break;
			case "3": $sorter_str.=" b.count_curr"; break;
			case "4": $sorter_str.=" b.currency"; break;
			case "5": $sorter_str.=" b.date_send"; break;
			case "6": $sorter_str.=" b.paysystem"; break;
			case "7": $sorter_str.=" b.status"; break;
		}
		$sorter_str .= $order_str;
	} else {
		$sorter_str .= " u.fname";
	}
	$smarty->assign("sorter", $sorter);

	$strSQL = " SELECT COUNT(DISTINCT b.id) FROM ".BILLING_REQUESTS_TABLE." b
				LEFT JOIN ".USERS_TABLE." u ON u.id=b.id_user
				".$where_str." ";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	if ($num_records>0){
		$settings["admin_rows_per_page"] = GetSiteSettings("admin_rows_per_page");

		$lim_min = ($page-1)*$settings["admin_rows_per_page"];
		$lim_max = $settings["admin_rows_per_page"];
		$limit_str = " limit ".$lim_min.", ".$lim_max;
		$smarty->assign("page", $page);

		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, UNIX_TIMESTAMP(b.date_send) as date_send_time,
					DATE_FORMAT(b.date_send, '".$config["date_format"]." %H:%i:%s') as date_send_show, b.status, b.paysystem, b.user_info,
					u.fname, u.sname, u.email
					FROM ".BILLING_REQUESTS_TABLE." b
					LEFT JOIN ".USERS_TABLE." u ON u.id=b.id_user
					$where_str GROUP BY b.id $sorter_str $limit_str";
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);

			$pays[$i]["id"] = $row["id"];
			$pays[$i]["id_user"] = $row["id_user"];
			$pays[$i]["number"] = $row["id"]."_".$row["id_user"];
			$pays[$i]["list_number"] = ($page-1)*$settings["admin_rows_per_page"]+($i+1);
			$pays[$i]["count_curr"] = $row["count_curr"];
			$pays[$i]["currency"] = $row["currency"];
			$pays[$i]["date_send_time"] = $row["date_send_time"];
			$pays[$i]["date_send_show"] = $row["date_send_show"];
			$pays[$i]["status"] = $row["status"];
			$pays[$i]["paysystem"] = $row["paysystem"];
			$pays[$i]["user_info"] = $row["user_info"];
			$pays[$i]["user_fname"] = stripslashes($row["fname"]);
			$pays[$i]["user_sname"] = stripslashes($row["sname"]);
			$pays[$i]["user_email"] = stripslashes($row["email"]);
			if ($pays[$i]["paysystem"] == 'bonus'){
				//bonus
				$pays[$i]["product"] = $lang["pays"]["service_2"];
			} else {
				//пополнение счёта
				$pays[$i]["product"] = $lang["pays"]["service_1"];
			}
			$rs->MoveNext();
			$i++;
		}
		$param = $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&sorter=".$sorter."&order=".$order."&";
		$dop_param["left_arrow_name"] = "&lt;&lt;";
		$dop_param["right_arrow_name"] = "&gt;&gt;";
		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $settings["admin_rows_per_page"], $dop_param));
		$smarty->assign("pays", $pays);
	} else {
		$smarty->assign("empty", 1);
	}

	$strSQL = "	SELECT COUNT(id), SUM(count_curr), MIN(DATE_FORMAT(date_send, '%Y-%m-%d')), MAX(DATE_FORMAT(date_send, '%Y-%m-%d'))
				FROM ".BILLING_REQUESTS_TABLE;
	$rs = $dbconn->Execute($strSQL);
	$trans["all"]["number"] = $rs->fields[0];
	$trans["all"]["sum"] = $rs->fields[1];
	$trans["all"]["from"] = $rs->fields[2];
	$trans["all"]["to"] = $rs->fields[3];

	$strSQL = "	SELECT status, COUNT(id) AS number, SUM(count_curr) AS total
				FROM ".BILLING_REQUESTS_TABLE." GROUP BY status";
	$rs = $dbconn->Execute($strSQL);

		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$trans[$row["status"]]["number"] = $row["number"];
			$trans[$row["status"]]["sum"] = $row["total"];
			$rs->MoveNext();
		}

	$smarty->assign("trans", $trans);
	//Receipts to account by user
	$strSQL = "	SELECT COUNT(id), SUM(count_curr), SUM(commission)
				FROM ".BILLING_USER_RECIPIENTS_TABLE;
	$rs = $dbconn->Execute($strSQL);

	if ($rs->fields[0]>0) {
		$total_transfer_count = $rs->fields[0];
		$total_transfer_sum = $rs->fields[1];
		$total_transfer_commission = $rs->fields[2];
		$smarty->assign("total_transfer_sum", $total_transfer_sum);
		$smarty->assign("total_transfer_commission", $total_transfer_commission);
		$smarty->assign("total_transfer_count", $total_transfer_count);
	}
	$smarty->assign("cur", $cur);

	/// letter link
	$param_letter = $file_name."?sel=$sel&sorter=".$sorter."&letter=";
	$used_search_form = ($search_str) ? true : false;
	$letter_links = LettersLink_eng($param_letter, $letter, $used_search_form);
	$smarty->assign("letter_links", $letter_links);

	$smarty->assign("sorter_link", $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&order=".$order_new);

	$form["action"] = 	$file_name;
	$form["hiddens"] = "<input type=hidden name=sel value=$sel>";
	$smarty->assign("form", $form);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_billing_list_pays.tpl");
}

function ListSpended() {
	global $smarty, $dbconn, $config, $lang;

	$sel = "list_spended";
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_payment.php";
	IndexAdminPage('admin_billing');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	$letter = (!isset($_REQUEST["letter"]) || strval($_REQUEST["letter"]) == "*") ? "*" : intval($_REQUEST["letter"]);	
	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? trim($_REQUEST["search"]) : "";
	$s_type = (isset($_REQUEST["s_type"]) && intval($_REQUEST["s_type"]) > 0) ? intval($_REQUEST["s_type"]) : 1;
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : 1;
	$order = (isset($_REQUEST["order"]) && intval($_REQUEST["order"]) > 0) ? intval($_REQUEST["order"]) : 1;

	///////// search
	$search_str = "";
	if(strval($search)){
		$search = strip_tags($search);
		switch($s_type){
			case "1": $search_str=" u.login like '%".$search."%'"; break;
			case "2": $search_str=" u.fname like '%".$search."%'"; break;
			case "3": $search_str=" u.sname like '%".$search."%'"; break;
			case "4": $search_str=" u.email like '%".$search."%'"; break;
		}
	}
	$smarty->assign("search", $search);
	$smarty->assign("s_type", $s_type);

	///////// letter
	if(strval($letter) != "*"){
		$letter_str = " lower(substring(u.email,1,1)) ='".strtolower(chr($letter))."'";
	}else{
		$letter_str = "";
	}
	$smarty->assign("letter", $letter);

	if($letter_str){
		$where_str = "where ".$letter_str." ";
	}elseif($search_str){
		$where_str = "where ".$search_str." ";
	}else{
		$where_str = "";
	}

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
	if(intval($sorter)>0){
		switch($sorter) {
			case "1": $sorter_str.=" u.fname"; break;
			case "2": $sorter_str.=" u.email"; break;
			case "3": $sorter_str.=" b.count_curr"; break;
			case "4": $sorter_str.=" b.currency"; break;
			case "5": $sorter_str.=" b.id_service"; break;
			case "6": $sorter_str.=" b.date_send"; break;
		}
		$sorter_str .= $order_str;
	} else {
		$sorter_str .= " u.fname";
	}
	$smarty->assign("sorter", $sorter);

	$strSQL = " SELECT COUNT(*) FROM ".BILLING_SPENDED_TABLE." b
				LEFT JOIN ".USERS_TABLE." u ON u.id=b.id_user
				".$where_str." ";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];
	if ($num_records>0){
		$settings["admin_rows_per_page"] = GetSiteSettings("admin_rows_per_page");

		$lim_min = ($page-1)*$settings["admin_rows_per_page"];
		$lim_max = $settings["admin_rows_per_page"];
		$limit_str = " limit ".$lim_min.", ".$lim_max;
		$smarty->assign("page", $page);

		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency,
					UNIX_TIMESTAMP(b.date_send) as date_send_time, b.id_service,
					DATE_FORMAT(b.date_send, '".$config["date_format"]." %H:%i:%s') as date_send_show,
					u.fname, u.sname, u.email
					FROM ".BILLING_SPENDED_TABLE." b
					LEFT JOIN ".USERS_TABLE." u ON u.id=b.id_user
					$where_str GROUP BY b.id $sorter_str $limit_str";
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		/**
		 * id_service:  1 - поднятие в поиске
		 *				2 - слайдшоу
		 *				3 - лидер региона
		 *				4 - абоненская плата
		 * 				5 - перевод средств другому пользователю
		 */
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$pays[$i]["id"] = $row["id"];
			$pays[$i]["id_user"] = $row["id_user"];
			$pays[$i]["number"] = ($page-1)*$settings["admin_rows_per_page"]+($i+1);
			$pays[$i]["count_curr"] = $row["count_curr"];
			$pays[$i]["currency"] = $row["currency"];
			$pays[$i]["date_send_time"] = $row["date_send_time"];
			$pays[$i]["date_send_show"] = $row["date_send_show"];
			$pays[$i]["user_fname"] = stripslashes($row["fname"]);
			$pays[$i]["user_sname"] = stripslashes($row["sname"]);
			$pays[$i]["user_email"] = stripslashes($row["email"]);
			$pays[$i]["id_service"] = $lang["pays"]["spend_serv_".$row["id_service"]];
			$rs->MoveNext();
			$i++;
		}
		$param = $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&sorter=".$sorter."&order=".$order."&";
		$dop_param["left_arrow_name"] = "&lt;&lt;";
		$dop_param["right_arrow_name"] = "&gt;&gt;";
		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $settings["admin_rows_per_page"], $dop_param));
		$smarty->assign("pays", $pays);
	} else {
		$smarty->assign("empty", 1);
	}
	/// letter link
	$param_letter = $file_name."?sel=$sel&sorter=".$sorter."&letter=";
	$used_search_form = ($search_str) ? true : false;
	$letter_links = LettersLink_eng($param_letter, $letter, $used_search_form);
	$smarty->assign("letter_links", $letter_links);

	$smarty->assign("sorter_link", $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&order=".$order_new);

	$smarty->assign("page", $page);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_billing_spend_pays.tpl");
}

function Approve() {
	global $smarty, $dbconn, $config, $lang;
	$arr = explode("_", $_GET["id_order"]);
	$id_order = $arr[0];
	$id_user = $arr[1];
	$is_ajax = (isset($_REQUEST["ajax"])) ? 1: 0;
	$strSQL = "SELECT count_curr, paysystem FROM ".BILLING_REQUESTS_TABLE." ".
			  "WHERE id='$id_order' AND id_user='$id_user'";
	$rs = $dbconn->Execute($strSQL);
	$sum = $rs->fields[0];
	$paysystem = $rs->fields[1];
	if ($rs->RowCount() > 0){
		$dbconn->Execute("UPDATE ".BILLING_REQUESTS_TABLE." SET status='approve' WHERE id='".$id_order."' AND  id_user='".$id_user."' ");
		$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$id_user."' ";
		$rs = $dbconn->Execute($strSQL);

		if ($rs->RowCount() > 0){
			$account = round(($rs->fields[0] + $sum), 2);
			$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='$account', date_refresh=now(), is_send='0' WHERE id_user='$id_user'");
		} else {
			$account = $sum;
			$dbconn->Execute("INSERT INTO ".BILLING_USER_ACCOUNT_TABLE." (id_user, account, date_refresh, is_send) VALUES ('$id_user', '$account', now(), '0')");
		}
		/**
		 * Send mail to user
		 */
		$strSQL = "SELECT email, fname, sname, lang_id FROM ".USERS_TABLE." WHERE id='$id_user'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$user["lang_id"] = $row["lang_id"];
		$user["email"] = $row["email"];
		$user["fname"] = stripslashes($row["fname"]);
		$user["sname"] = stripslashes($row["sname"]);

		$data["name"] = $user["fname"]." ".$user["sname"];
		$data["add_on_account"] = $sum;
		$data["account"] = $account;

		$settings = GetSiteSettings(array('site_email', 'site_unit_costunit'));

		$strSQL = "SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='".$settings["site_unit_costunit"]."' ";
		$rs = $dbconn->Execute($strSQL);
		$data["cur"] = $rs->fields[0];
		if ($paysystem == "manual"){
			$mail_content_xml = "mail_content_money_add_manual";
		}else{
			$mail_content_xml = "mail_content_money_add";
		}

		$mail_content = GetMailContentReplace($mail_content_xml, GetUserLanguageId($user["lang_id"]));

		SendMail($user["email"], $settings["site_email"], $mail_content["subject"], $data, $mail_content, "mail_money_add_table", '', $data["name"] , $mail_content["site_name"], 'text');
	}
	if ($is_ajax){
		echo "approved";
	}else{
		if (isset($_REQUEST["redirect"]) && $_REQUEST["redirect"] == 1) {
			UserPaymentHistory();
		} else {
			ListPayments();
		}
		return;
	}
}

function Decline() {
	global $smarty, $dbconn, $config, $lang;
	$arr = explode("_", $_GET["id_order"]);
	$is_ajax = (isset($_REQUEST["ajax"])) ? 1: 0;
	$id_order = $arr[0];
	$id_user = $arr[1];
	$strSQL = " SELECT DISTINCT id, paysystem
				FROM ".BILLING_REQUESTS_TABLE."
				WHERE id='".$id_order."' AND  id_user='".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);
	
	if ($rs->fields[0]>0){
		$paysystem = $rs->fields[1];
		$dbconn->Execute(" UPDATE ".BILLING_REQUESTS_TABLE." SET status='fail' WHERE id='".$id_order."' AND  id_user='".$id_user."' ");
		/**
		 * Send mail to user
		 */
		$strSQL = "SELECT email, fname, sname, lang_id FROM ".USERS_TABLE." WHERE id='$id_user'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$user["lang_id"] = $row["lang_id"];
		$user["email"] = $row["email"];
		$user["fname"] = stripslashes($row["fname"]);
		$user["sname"] = stripslashes($row["sname"]);

		$data["name"] = $user["fname"]." ".$user["sname"];

		$settings = GetSiteSettings(array('site_email', 'site_unit_costunit'));

		$strSQL = "SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='".$settings["site_unit_costunit"]."' ";
		$rs = $dbconn->Execute($strSQL);
		$data["cur"] = $rs->fields[0];
		if ($paysystem == "manual"){
			$mail_content_xml = "mail_content_money_decline_manual";
		}else{
			$mail_content_xml = "mail_content_money_decline";
		}

		$mail_content = GetMailContentReplace($mail_content_xml, GetUserLanguageId($user["lang_id"]));

		SendMail($user["email"], $settings["site_email"], $mail_content["subject"], $data, $mail_content, "mail_money_decline_table", '', $data["name"] , $mail_content["site_name"], 'text');
	}
	if ($is_ajax){
		echo "declined";
	}else{
		if (isset($_REQUEST["redirect"]) && $_REQUEST["redirect"] == 1) {
			UserPaymentHistory();
		} else {
			ListPayments();
		}
		return;
	}
}

function ListUsers() {
	global $smarty, $dbconn, $config, $lang, $cur;

	$sel = "users_list_history";
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_payment.php";
	IndexAdminPage('admin_billing');
	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	$letter = (!isset($_REQUEST["letter"]) || strval($_REQUEST["letter"]) == "*") ? "*" : intval($_REQUEST["letter"]);	
	$search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? trim($_REQUEST["search"]) : "";
	$s_type = (isset($_REQUEST["s_type"]) && intval($_REQUEST["s_type"]) > 0) ? intval($_REQUEST["s_type"]) : 1;
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? $_REQUEST["sorter"] : 1;
	$order = (isset($_REQUEST["order"]) && intval($_REQUEST["order"]) > 0) ? intval($_REQUEST["order"]) : 1;

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
	$param_letter = $file_name."?sel=$sel&sorter=".$sorter."&order=".$order."&letter=";
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

	if($letter_str){
		$where_str = "where ".$letter_str." ";
	}elseif($search_str){
		$where_str = "where u.id>0 ".$search_str." ";
	}else{
		$where_str = "";
	}
	$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u  ".$where_str;
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
					 ".$where_str." ".$sorter_str.$limit_str;
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		if ($rs->RowCount()>0){
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$user[$i]["number"] = ($page-1)*$rows_num_page+($i+1);
				$user[$i]["id"] = $row["id"];
				$user[$i]["edit_link"] = "admin_users.php?sel=edit_user&from_file=admin_payment&from_file_sel=$sel&id_user=".$user[$i]["id"];
				$user[$i]["name"] = stripslashes($row["fname"]." ".$row["sname"]);
				$user[$i]["email"] = $row["email"];
				$user[$i]["status"] = intval($row["status"]);
				$user[$i]["user_type"] = $row["user_type"];
				$user[$i]["root"] = $row["root_user"];
				$user[$i]["guest"] = $row["guest_user"];

				//// groups
				$strSQL2 = "SELECT DISTINCT(id_group) FROM ".USER_GROUP_TABLE." where id_user='".$row["id"]."' ";
				$rs2 = $dbconn->Execute($strSQL2);
				unset($groups_arr);
				$groups_arr = array();
				while(!$rs2->EOF){
					array_push($groups_arr, $lang["groups"][$rs2->fields[0]]);
					$rs2->MoveNext();
				}
				if(is_array($groups_arr) && count($groups_arr)>0) {
					$user[$i]["groups"] = implode("<br>", $groups_arr);
				}
				//dates in group
				$user[$i]["dates"] = ($row["date_begin"] && $row["date_end"])?$row["date_begin"]." - ".$row["date_end"]:"";
				//payment history link
				$user[$i]["payments_link"] = "";
				$strSQL = "SELECT DISTINCT id FROM ".BILLING_REQUESTS_TABLE." WHERE id_user='".$user[$i]["id"]."' ";
				$res = $dbconn->Execute($strSQL);
				$strSQL2 = "SELECT DISTINCT id FROM ".BILLING_USER_RECIPIENTS_TABLE." WHERE id_user='".$user[$i]["id"]."' ";
				$res2 = $dbconn->Execute($strSQL2);
				$strSQL3 = "SELECT DISTINCT id FROM ".BILLING_ADDING_BY_ADMIN_TABLE." WHERE id_user='".$user[$i]["id"]."' ";
				$res3 = $dbconn->Execute($strSQL3);
				if (( $res->RowCount()>0 ) || ( $res2->RowCount()>0 ) || ( $res3->RowCount()>0 )) {
					$user[$i]["payments_link"] = $file_name."?sel=user_history&id_user=".$user[$i]["id"];
				}

				$rs->MoveNext();
				$i++;
			}

			$smarty->assign("user", $user);
			$smarty->assign("page", $page);
			$param = $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&sorter=".$sorter."&order=".$order."&";
			$dop_param["left_arrow_name"] = "&lt;&lt;";
			$dop_param["right_arrow_name"] = "&gt;&gt;";
			$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page, $dop_param) );
		}
	}

	$smarty->assign("sorter_link", $file_name."?sel=$sel&letter=".$letter."&search=".$search."&s_type=".$s_type."&order=".$order_new);


	$form["action"] = 	$file_name;
	$form["hiddens"] = "<input type=hidden name=sel value=$sel>";
	$smarty->assign("form", $form);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_billing_users.tpl");
	exit;
}

function UserPaymentHistory() {
	global $smarty, $dbconn, $config;

	$id_user = intval($_REQUEST["id_user"]);
	if ($id_user == 0) {
		ListUsers();
		exit();
	}

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_payment.php";
	IndexAdminPage('admin_billing');

	///// user info
	$strSQL = "SELECT fname, sname, email from ".USERS_TABLE." where id='".$id_user."'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$user["name"] = $row["fname"]." ".$row["sname"];
	$user["email"] = $row["email"];

	/// user account
	$strSQL = "SELECT account, DATE_FORMAT(date_refresh, '".$config["date_format"]." %H:%i:%s') AS date_refresh
			   FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$id_user."'";
	//echo $strSQL;
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$user["account"] = round($row["account"],4);
	$user["date_refresh"] = $row["date_refresh"];
    $smarty->assign("user", $user);

	//платежи и попытки платежей
	$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, b.paysystem, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, b.status
				FROM ".BILLING_REQUESTS_TABLE." b
				WHERE b.id_user='".$id_user."'
				GROUP BY b.id ORDER BY b.date_send DESC  ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	if ($rs->fields[0]>0) {
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$data[$i]["id"] = $row["id"];
			$data[$i]["id_user"] = $row["id_user"];
			$data[$i]["number"] = $row["id"]."_".$row["id_user"];
			$data[$i]["count_curr"] = $row["count_curr"];
			$data[$i]["currency"] = $row["currency"];
			$data[$i]["paysystem"] = $row["paysystem"];
			$data[$i]["status"] = $row["status"];
			if ( $data[$i]["status"] !='approve' ) {
				$data[$i]["link"] = $file_name."?sel=view_request&amp;id_req=".$data[$i]["id"];
			}
			$data[$i]["date_send"] = $row["date_send"];
			$data[$i]["timestamp"] = $row["timestamp"];
			$rs->MoveNext();
			$i++;
		}
	}
	$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.id_from_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, u.fname, u.sname
					FROM ".BILLING_USER_RECIPIENTS_TABLE." b
					LEFT JOIN ".USERS_TABLE." u on u.id = b.id_from_user
					WHERE b.id_user='".$id_user."'
					ORDER BY b.date_send DESC  ";

	$rs = $dbconn->Execute($strSQL);

	if ($rs->fields[0]>0) {
		while(!$rs->EOF){

			$row = $rs->GetRowAssoc(false);
			$data[$i]["id"] = $row["id"];
			$data[$i]["id_user"] = $row["id_user"];
			$data[$i]["user_from_name"] = $row["fname"]." ".$row["sname"];
			$data[$i]["count_curr"] = $row["count_curr"];
			$data[$i]["currency"] = $row["currency"];
			$data[$i]["date_send"] = $row["date_send"];
			$data[$i]["timestamp"] = $row["timestamp"];
			$data[$i]["paysystem"] = "";
			$data[$i]["status"] = "none";
			$data[$i]["link"] = "";
			$rs->MoveNext();
			$i++;
		}
	}
	
	$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, u.fname, u.sname
					FROM ".BILLING_ADDING_BY_ADMIN_TABLE." b
					LEFT JOIN ".USERS_TABLE." u on u.id = b.id_user
					WHERE b.id_user='".$id_user."'
					ORDER BY b.date_send DESC ";

	$rs = $dbconn->Execute($strSQL);

	if ($rs->fields[0]>0) {
		while(!$rs->EOF){

			$row = $rs->GetRowAssoc(false);
			$data[$i]["id"] = $row["id"];
			$data[$i]["id_user"] = $row["id_user"];
			$data[$i]["user_from_name"] = $row["fname"]." ".$row["sname"];
			$data[$i]["count_curr"] = $row["count_curr"];
			$data[$i]["currency"] = $row["currency"];
			$data[$i]["date_send"] = $row["date_send"];
			$data[$i]["timestamp"] = $row["timestamp"];
			$data[$i]["paysystem"] = "by admin";
			$data[$i]["status"] = "by_admin";
			$data[$i]["link"] = "";
			$rs->MoveNext();
			$i++;
		}
	}

	$data = MultiSort($data, "timestamp");
	if ($i > 5) {
		$data[0]["all_req_link"] = 1;
	}
	$smarty->assign("data", $data);
	//списания со счета
	$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, b.id_service, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send
				FROM ".BILLING_SPENDED_TABLE." b
				WHERE b.id_user='".$id_user."'
				GROUP BY b.id ORDER BY b.date_send DESC  ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	if ($rs->fields[0]>0) {
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spended[$i]["count_curr"] = $row["count_curr"];
			$spended[$i]["currency"] = $row["currency"];
			$spended[$i]["id_service"] = $row["id_service"];
			$spended[$i]["date_send"] = $row["date_send"];
			$rs->MoveNext();
			$i++;
		}
		if ($rs->RowCount()>5) {
			$spended[0]["all_spend_link"] = 1;
		}
		$smarty->assign("spended", $spended);
	}

	$smarty->assign("cur", GetSiteSettings('site_unit_costunit'));

	$back_link = (isset($_REQUEST["from_file"]) && !empty($_REQUEST["from_file"])) ? strval($_REQUEST["from_file"]).".php" : $file_name;
	$back_link .= (isset($_REQUEST["from_file_sel"]) && !empty($_REQUEST["from_file_sel"])) ? "?sel=".strval($_REQUEST["from_file_sel"]) : "?sel=users_list_history";
	$back_link .= (isset($_REQUEST["from_file_id_group"]) && !empty($_REQUEST["from_file_id_group"])) ? "&id_group=".strval($_REQUEST["from_file_id_group"]) : "";

	$smarty->assign("file_name", $file_name);
	$smarty->assign("back_link", $back_link);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_billing_user_history.tpl");
	exit;
}

function RemoveCurrency() {
	global $smarty, $dbconn;
	$lang["errors"] = GetLangContent('errors');
	$id = $_REQUEST["id"];
	$strSQL = "SELECT COUNT(id) FROM ".UNITS_TABLE;
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->fields[0];
	$strSQL = "SELECT abbr FROM ".UNITS_TABLE." WHERE id=".$id;
	$rs = $dbconn->Execute($strSQL);
	$abbr = $rs->fields[0];
	$strSQL = "SELECT value FROM ".SETTINGS_TABLE." WHERE name='site_unit_costunit'";
	$rs = $dbconn->Execute($strSQL);		
	$current_abbr = $rs->fields[0];
	if ($count <> 1) {
		$strSQL = "DELETE FROM ".UNITS_TABLE." WHERE id=".$id;
		$rs = $dbconn->Execute($strSQL);
		$smarty->assign("error", $lang["errors"]["successful_edit"]);
	} else {
		$smarty->assign("error", $lang["errors"]["currency_not_deleted"]);		
	}
	if ($current_abbr == $abbr) {
		$strSQL = "SELECT abbr FROM ".UNITS_TABLE." LIMIT 0,1";
		$rs = $dbconn->Execute($strSQL);
		$first_abbr=$rs->fields[0];
		$strSQL = "UPDATE ".SETTINGS_TABLE." SET VALUE='".$first_abbr."' WHERE name='site_unit_costunit'";
		$rs = $dbconn->Execute($strSQL);
	}		
	SettingsBilling();	
	return;
}

function AddCurrency() {
	global $smarty, $dbconn;
	$lang["errors"] = GetLangContent('errors');
	$currency_name = $_REQUEST["currency_name"];
	$currency_abbr = trim($_REQUEST["currency_abbr"]);
	$currency_symbol = trim($_REQUEST["currency_symbol"]);
	$strSQL = "SELECT COUNT(id) FROM ".UNITS_TABLE." WHERE abbr='".$currency_abbr."'";
	$rs = $dbconn->Execute($strSQL);
	$count = $rs->fields[0];
	if ($currency_name != '' && $currency_abbr != '' && $currency_symbol != '' && $count == 0) {	
		$strSQL = "INSERT INTO ".UNITS_TABLE." (abbr, name, fractional_unit, symbol) VALUES ('".$currency_abbr."','".$currency_name."', 0 ,'".$currency_symbol."')";	
		$rs = $dbconn->Execute($strSQL);
		$smarty->assign("error", $lang["errors"]["success_save"]);
	} else {		
		if ($count==0) {
			$smarty->assign("error", $lang["errors"]["empty_fields"]);
		} else {
			$smarty->assign("error", $lang["errors"]["currensy_is_present"]);
		}
	}	
	SettingsBilling();	
	return;
}

function SaveChanges() {
	global $smarty, $dbconn, $config;
	$lang["errors"] = GetLangContent('errors');
	if (intval($_REQUEST["err_count"]) == 0) {
		$id_m = $_REQUEST["id_m"];
		$abbr_m = $_REQUEST["abbr_m"];
		$name_m = $_REQUEST["name_m"];
		$symbol_m = $_REQUEST["symbol_m"];
		$count = intval($_REQUEST["count"]);
		$strSQL = "SELECT value FROM ".SETTINGS_TABLE." WHERE name='site_unit_costunit'";
		$rs = $dbconn->Execute($strSQL);
		$current_abbr = $rs->fields[0];
		$strSQL = "SELECT id FROM ".UNITS_TABLE." WHERE abbr='".$current_abbr."'";
		$rs = $dbconn->Execute($strSQL);
		$current_id = $rs->fields[0];
		for ($i = 0; $i < $count; $i++) {
			$strSQL = "UPDATE ".UNITS_TABLE." SET abbr='".$abbr_m[$i]."', name='".$name_m[$i]."', symbol='".$symbol_m[$i]."' WHERE id=".$id_m[$i];	
			$dbconn->Execute($strSQL);
			if ($id_m[$i] == $current_id) {
				$strSQL = "Update ".SETTINGS_TABLE." set value='".$abbr_m[$i]."' where name='site_unit_costunit'";
				$dbconn->Execute($strSQL);
			}
		}
		$err = "success_save";
	} else {		
		$err = "empty_fields";
	}
	
	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_payment.php?sel=settings&err=$err");
	exit();	
}

function PaymentDetails(){
	global $smarty, $dbconn, $config;
	IndexAdminPage('admin_billing');
	$id = intval($_REQUEST["id"]);
	$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, UNIX_TIMESTAMP(b.date_send) as date_send_time,
					DATE_FORMAT(b.date_send, '".$config["date_format"]." %H:%i:%s') as date_send_show, b.status, b.paysystem, b.user_info,
					u.fname, u.sname, u.email
					FROM ".BILLING_REQUESTS_TABLE." b
					LEFT JOIN ".USERS_TABLE." u ON u.id=b.id_user
					WHERE b.id='$id'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->getRowAssoc(false);
	$smarty->assign("details", $row);
	if (isset($_REQUEST["status_id"])){
		$smarty->assign("status_id", htmlspecialchars($_REQUEST["status_id"]));
	}
	if (isset($_REQUEST["approve_field_id"])){
		$smarty->assign("approve_field_id", htmlspecialchars($_REQUEST["approve_field_id"]));
	}
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_billing_details.tpl");
	exit();	
}

?>