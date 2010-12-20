<?php
/**
* Functions for authorizenet payment system (send payment request to the payment system,
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
    $rs_sys = $dbconn->Execute("SELECT seller_id, trans_key FROM ".BILLING_SYS_.$billing_system);
	$payGear = new Payment_Engine(PAYMENT_ENGINE_SEND, false, false);
    $PaySystem = &$payGear->factory($billing_system);
    /**
     * in RE add_param is a field in BILLING_PAYSYSTEMS_TABLE
     */
    $add_param = $billing_system."_param";
    $data = array(
	    'seller_id'     => $rs_sys->fields[0],
		'amount'        => $amount,
		'order_id'		=> $id_trunzaction,
		'sequence'		=> $id_trunzaction,
		'timestamp'		=> $time,
		'currency'		=> $currency,
		'hash'			=> ( trim ( hmac( $rs_sys->fields[1], "{$rs_sys->fields[0]}^{$id_trunzaction}^{$time}^{$amount}^{$currency}") ) ),
	    "test_mode"		=> "FALSE",
	    "pay_form"		=> "PAYMENT_FORM"
    );
    $PaySystem->setFrom($data);
    $PaySystem->doPayment();

    $dopayment_flag = 0;
}

//получение данных о платежке. применяется в admin/admin_payment.php
function getBillingData($name, $lang) {
	global $dbconn;
		$rs = $dbconn->Execute("Select p.name, p.used, bs.seller_id, bs.trans_key from ".BILLING_PAYSYSTEMS_TABLE." p INNER JOIN ".BILLING_SYS_.$name." bs ON p.name = bs.name and p.name='".$name."'");
		$data["use"] = $rs->fields[1];
		$data["value"] = $rs->fields[2];
		$data["trans_key"] = $rs->fields[3];
		if ($data["use"]) $checked = "checked"; else $checked="";
		$data["table_options"] =
		        "<tr>".
                        "<td>".$lang['pays'][$name.'_seller_id'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='value' value='".$data['value']."'></td>".
                        "<td><input type='checkbox' class='checkbox' name=use value=1 ".$checked."></td><td>".$lang['pays']['use']."</td>
                  </tr>
                  <tr>
						<td>".$lang['pays'][$name.'_key'].":&nbsp;</td>
						<td><input type='text' size='30' name='trans_key' value='".$data['trans_key']."'></td>
						<td colspan=2><i>".$lang['pays'][$name.'_key_comment']."</i></td>
					</tr>";
		return $data;
}

//установка(изменение) данных о платежке. применяется в admin/admin_payment.php
function setBillingData($name, $lang, $__POST) {
	global $dbconn;
			$value = strval($__POST["value"]);
			$trans_key = strval($__POST["trans_key"]);
			$use = intval($__POST["use"]);
			$err = 0;
			if((!$value)||(!$trans_key)){
				$err = $lang["errors"]["invalid_fields"];
				if(!$value) $err .= "<br>".$lang["pays"][$name."_seller_id"];
				if(!$trans_key) $err .= "<br>".$lang["pays"][$name."_key"];
			} else {
				$strSQL = "Update ".BILLING_PAYSYSTEMS_TABLE." set used='".$use."' where name='".$name."'";
				$dbconn->Execute($strSQL);
				$strSQL = "Update ".BILLING_SYS_.$name." set seller_id='".$value."', trans_key='".$trans_key."' where name='".$name."'";
				$dbconn->Execute($strSQL);
			}
		return $err;
}

//необходимые значения проведенноого платежа.применяется в include/payment_request.php
function getPaymentValue($valuename,$__POST) {
	switch ($valuename) {
		case "count":	return $__POST["x_amount"];
				break;
		case "curency":	return $__POST["x_currency_code"];
				break;
		case "date":	return date("Y-m-d H:i:s");
				break;
		case "status":	return ($__POST["x_response_code"] == 1)?1:0;
				break;
		case "id_req":
				$arr = explode("_",$__POST["x_description"]);
				return $arr[0];
				break;
		case "id_service":
				$arr = explode("_",$__POST["x_description"]);
				return $arr[1];
				break;
		case "quantity":return 1;
				break;
	}
}

function hmac($key, $data) {
   $b = 64; // byte length for md5
   if (strlen($key) > $b) {
       $key = pack("H*",md5($key));
   }
   $key  = str_pad($key, $b, chr(0x00));
   $ipad = str_pad('', $b, chr(0x36));
   $opad = str_pad('', $b, chr(0x5c));
   $k_ipad = $key ^ $ipad ;
   $k_opad = $key ^ $opad;
   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
}
?>