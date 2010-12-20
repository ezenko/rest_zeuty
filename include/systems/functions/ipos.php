<?php
/**
* Functions for ipos payment system (send payment request to the payment system,
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
    $rs_sys = $dbconn->Execute("select seller_id from ".BILLING_SYS_.$billing_system);
    $payGear = new Payment_Engine(PAYMENT_ENGINE_SEND, false, false);
    $PaySystem = &$payGear->factory($billing_system);

    $data = array(
	    'seller_id'   => $rs_sys->fields[0],
	    "amount"      =>  $amount,
        "order_id"    => $id_trunzaction,
	    "return_url"  => $config["server"].$config["site_root"]."/include/payment_request.php?sel=$billing_system"
    );
    $PaySystem->setFrom($data);
    $PaySystem->doPayment();

    $dopayment_flag = 0;
}

//получение данных о платежке. применяется в admin/admin_payment.php
function getBillingData($name, $lang) {
	global $dbconn;
		$rs = $dbconn->Execute("Select p.name, p.used, bs.seller_id from ".BILLING_PAYSYSTEMS_TABLE." p INNER JOIN ".BILLING_SYS_.$name." bs ON p.name = bs.name and p.name='".$name."'");
		$data["use"] = $rs->fields[1];
		$data["value"] = $rs->fields[2];
		if ($data["use"]) $checked = "checked"; else $checked="";
		$data["table_options"] =
		        "<tr>".
                    "<td>".$lang['pays'][$name.'_seller_id'].":&nbsp;</td>".
                    "<td><input type='text' size='30' name='value' value='".$data['value']."'></td>".
                    "<td><input type='checkbox' class='checkbox' name=use value=1 ".$checked."></td><td>".$lang['pays']['use']."</td></tr>";
		return $data;
}



//установка(изменение) данных о платежке. применяется в admin/admin_payment.php
function setBillingData($name, $lang, $__POST) {
	global $dbconn;
			$value = strval($__POST["value"]);
			$use = intval($__POST["use"]);
			$err = 0;
			if(!$value){
				$err = $lang["errors"]["invalid_fields"];
				$err .= "<br>".$lang["pays"][$name."_seller_id"];
			} else {
				$strSQL = "Update ".BILLING_PAYSYSTEMS_TABLE." set used='".$use."' where name='".$name."'";
				$dbconn->Execute($strSQL);
				$strSQL = "Update ".BILLING_SYS_.$name." set seller_id='".$value."' where name='".$name."'";
				$dbconn->Execute($strSQL);
			}
		return $err;
}

//необходимые значения проведенноого платежа.применяется в include/payment_request.php
function getPaymentValue($valuename,$__POST) {
	switch ($valuename) {
		case "count":	return $__POST["Amount"];
				break;
		case "curency":	return "USD";
				break;
		case "date":	return date("Y-m-d H:i:s");
				break;
		case "status":	return 1;
				break;
		case "id_req":
				$arr = explode("_",$__POST["Ref"]);
				return $arr[0];
				break;
		case "id_service":
				$arr = explode("_",$__POST["Ref"]);
				return $arr[1];
				break;
		case "quantity":return 1;
				break;
	}
}
?>