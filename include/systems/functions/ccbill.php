<?php
/**
* Functions for ccbill payment system (send payment request to the payment system,
* get and set payment system data in admin mode, get info, returned by payment system on payment request)
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 10:43:40 $
**/

//redirect on this place after user click button "Pay"
if (isset($dopayment_flag) && $dopayment_flag == 1) {

	$billing_system = $template_name;
	switch($currency){
		case "USD": $curr = 1;  break;
        case "EUR": $curr = 85; break;
        case "GBP": $curr = 44; break;
        case "CAD": $curr = 2;  break;
        case "JPY": $curr = 81; break;
    }
    $str = "	SELECT seller_id, seller_sub_id, form_name, lang as language, allowed_types, subscription_type_id
					FROM ".BILLING_SYS_.$billing_system;
    $rs_sys = $dbconn->Execute($str);
    $row = $rs_sys->GetRowAssoc(false);

    $payGear = new Payment_Engine(PAYMENT_ENGINE_SEND, false, false);
	$PaySystem = &$payGear->factory($billing_system);

	$data = array(
		'seller_id'     => $row["seller_id"],
		'seller_sub_id'	=> $row["seller_sub_id"],
		'form_name'	=> $row["form_name"],
		'language'	=> $row["language"],
		'allowed_types' => $row["allowed_types"],
		'user_id' 	=> $id_trunzaction."_".$id_service,
		'currency' 	=> $curr,
		'subscription_type_id' => $row["subscription_type_id"]
	);

	$PaySystem->setFrom($data);
    $PaySystem->doPayment();
    $dopayment_flag = 0;
}

//получение данных о платежке. применяется в admin/admin_payment.php
function getBillingData($name, $lang) {
	global $dbconn;
		$rs = $dbconn->Execute(" 	SELECT 	p.name, p.used,
											bs.seller_id, bs.seller_sub_id, bs.form_name, bs.lang as language, bs.allowed_types, bs.subscription_type_id
									FROM ".BILLING_PAYSYSTEMS_TABLE." p
									INNER JOIN ".BILLING_SYS_.$name." bs ON p.name = bs.name and p.name='".$name."'");
		$row = $rs->GetRowAssoc(false);

		$data["use"] = $row["used"];
		$data["seller_id"] = stripslashes($row["seller_id"]);
		$data["seller_sub_id"] = stripslashes($row["seller_sub_id"]);
		$data["form_name"] = stripslashes($row["form_name"]);
		$data["language"] = stripslashes($row["language"]);
		$data["allowed_types"] = stripslashes($row["allowed_types"]);
		$data["subscription_type_id"] = stripslashes($row["subscription_type_id"]);
		if ($data["use"]) $checked = "checked"; else $checked="";
		$data["table_options"] =
		        "<tr>".
                        "<td>".$lang['pays']['ccbill_seller_id'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='seller_id' value='".$data['seller_id']."' class='paysys_settings'></td>".
                        "<td><input type='checkbox' class='checkbox' name='use' value=1 ".$checked."></td><td>".$lang['pays']['use']."</td>
                  </tr>
                  <tr>".
                        "<td>".$lang['pays']['ccbill_seller_sub_id'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='seller_sub_id' value='".$data['seller_sub_id']."' class='paysys_settings'></td>".
                        "<td class='main_header_text' align='left'>&nbsp;</td>
                  </tr>
                  <tr>".
                        "<td>".$lang['pays']['ccbill_form_name'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='form_name' value='".$data['form_name']."' class='paysys_settings'></td>".
                        "<td class='main_header_text' align='left'>&nbsp;</td>
                  </tr>
                  <tr>".
                        "<td>".$lang['pays']['ccbill_language'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='language' value='".$data['language']."' class='paysys_settings'></td>".
                        "<td class='main_header_text' align='left'>&nbsp;</td>
                  </tr>
                  <tr>".
                        "<td>".$lang['pays']['ccbill_allowed_types'].":&nbsp;</td>".
                        "<td><textarea name='allowed_types' rows='2' cols='30' class='paysys_settings'>".$data['allowed_types']."</textarea></td>".
                        "<td class='main_header_text' align='left'>&nbsp;</td>
                  </tr>
                  <tr>".
                        "<td>".$lang['pays']['ccbill_subscription_type_id'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='subscription_type_id' value='".$data['subscription_type_id']."' class='paysys_settings'></td>".
                        "<td class='main_header_text' align='left'>&nbsp;</td>
                  </tr>
                  ";
		return $data;
}

//установка(изменение) данных о платежке. применяется в admin/admin_payment.php
function setBillingData($name, $lang, $__POST) {
	global $dbconn;

	$seller_id = addslashes($__POST["seller_id"]);
	$use = intval($__POST["use"]);
	$seller_sub_id = addslashes($__POST["seller_sub_id"]);
	$form_name = addslashes($__POST["form_name"]);
	$language = addslashes($__POST["language"]);
	$allowed_types = addslashes($__POST["allowed_types"]);
	$subscription_type_id = addslashes($__POST["subscription_type_id"]);

	$strSQL = "UPDATE ".BILLING_PAYSYSTEMS_TABLE." SET used='".$use."' WHERE name='".$name."' ";
	$dbconn->Execute($strSQL);
	$err = 0;
	if ((!$seller_id) || (!$use) || (!$seller_sub_id) || (!$form_name) || (!$language) || (!$allowed_types) || (!$subscription_type_id)) {
		$err = $lang["errors"]["invalid_fields"];		
	} else {
		$strSQL = "UPDATE ".BILLING_SYS_.$name." SET seller_id='".$seller_id."' WHERE name='".$name."' ";
		$dbconn->Execute($strSQL);
		$strSQL = "UPDATE ".BILLING_SYS_.$name." SET seller_sub_id='".$seller_sub_id."' WHERE name='".$name."' ";
		$dbconn->Execute($strSQL);
		$strSQL = "UPDATE ".BILLING_SYS_.$name." SET form_name='".$form_name."' WHERE name='".$name."' ";
		$dbconn->Execute($strSQL);
		$strSQL = "UPDATE ".BILLING_SYS_.$name." SET lang='".$language."' WHERE name='".$name."' ";
		$dbconn->Execute($strSQL);
		$strSQL = "UPDATE ".BILLING_SYS_.$name." SET allowed_types='".$allowed_types."' WHERE name='".$name."' ";
		$dbconn->Execute($strSQL);
		$strSQL = "UPDATE ".BILLING_SYS_.$name." SET subscription_type_id='".$subscription_type_id."' WHERE name='".$name."' ";
		$dbconn->Execute($strSQL);
	}
	return $err;
}

//необходимые значения проведенноого платежа.применяется в include/payment_request.php
function getPaymentValue($valuename,$__POST) {
	switch ($valuename) {

		case "count":	return $__POST["initialPrice"];
				break;
		case "curency":	return 'USD';
				break;
		case "date":	return date("Y-m-d H:i:s");
				break;
		case "status":
			if ($_REQUEST["productDesc"]) {
				return 1;
			} else {
				return 0;
			}
			break;
		case "id_req":
				$arr = explode("_",$_REQUEST["productDesc"]);
				return $arr[0];
				break;
		case "id_service":
				$arr = explode("_",$_REQUEST["productDesc"]);
				return $arr[1];
				break;
		case "quantity":return 1;
				break;
	}
}
?>