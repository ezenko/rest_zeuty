<?php
/**
* Functions for SmsCoin payment system (send payment request to the payment system,
* get and set payment system data in admin mode, get info, returned by payment system on payment request)
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.3 $ $Date: 2008/10/15 11:44:30 $
**/

/**
 * Attention: set return url in sms:bank settings (http://smscoin.net/purses/edit/id_of_your_sms_bank/)
 */
//redirect on this place after user click button "Pay"
if (isset($dopayment_flag) && $dopayment_flag == 1) {
	
	$billing_system = $template_name;
    $rs_sys = $dbconn->Execute("SELECT purse_id, secret_code FROM ".BILLING_SYS_.$billing_system);
    $payGear = new Payment_Engine(PAYMENT_ENGINE_SEND, false, false);
    $PaySystem = &$payGear->factory($billing_system);

    $clear_amount = 0;
    $data = array(
	    "purse_id"      => $rs_sys->fields[0],
	    "order_id"      => $id_trunzaction,
	    "amount"        => $amount,
	    "clear_amount"  => $clear_amount,
	    "sign" 			=> ref_sign($rs_sys->fields[0], $id_trunzaction, $amount, $clear_amount, $product_name, $rs_sys->fields[1]),
	    "product_name"  => $product_name
    );
    $PaySystem->setFrom($data);
    $PaySystem->doPayment();

    $dopayment_flag = 0;
}

//получение данных о платежке. применяется в admin/admin_payment.php
function getBillingData($name, $lang) {
	global $dbconn;

		$site_unit_costunit = GetSiteSettings('site_unit_costunit');

		$strSQL = "SELECT p.name, p.used, bs.purse_id, bs.secret_code FROM ".BILLING_PAYSYSTEMS_TABLE." p ".
				  "INNER JOIN ".BILLING_SYS_.$name." bs ON p.name = bs.name AND p.name='".$name."'";
		$rs = $dbconn->Execute($strSQL);
		$data = $rs->GetRowAssoc( false );
		if ($data["used"]) $checked = "checked"; else $checked="";
		$data["table_options"] =
		         "<tr>".
                        "<td>".$lang['pays'][$name.'_purse_id'].":&nbsp;</td>".
                        "<td><input type='text' size='30' name='purse_id' value='".$data['purse_id']."'></td>".
                        "<td><input type='checkbox' class='checkbox' name=use value=1 ".$checked."></td><td>".$lang['pays']['use']."</td>".
                 "</tr>".
                 "<tr>".
                        "<td>".$lang['pays'][$name.'_secret_code'].":&nbsp;</td>".
                        "<td colspan='2'><input type='text' size='30' name='secret_code' value='".$data['secret_code']."' class='paysys_settings'></td>".
                  "</tr></table>";

       $strSQL = "SELECT id, name FROM ".BILLING_SYS_.$name._OPERATOR." ORDER BY id";
       $rs = $dbconn->Execute($strSQL);
       $operator_cnt = $rs->RowCount();

	   $data["table_options"] .= "<div class='help_text' style='margin-top: 10px;'><span class='help_title'>".$lang["default_select"]["help"]."</span>".$lang["pays"][$name."_tarif_help"]." <b>".$site_unit_costunit."</b>.</div>";
	   $data["table_options"] .= "<table cellpadding='0' cellspacing='0' border='0' class='form_big_table' style='margin-bottom: 10px;'>
			<tr>
				<td>".$lang["pays"][$name."_add_operator"].":</td>
				<td><input type='text' name='operator'></td>
				<td><input type='button' value='".$lang['buttons']['add']."' onClick='document.forms.money_form.sel.value=\"".$name."_add_operator\"; document.money_form.submit();'></td>
			</tr>
		</table>";
       if ($operator_cnt > 0) {
	       $data["table_options"] .= "<div class='table_title'>".$lang["pays"][$name."_tarif"].":</div>".
	        						  "<table border=0 class='table_main' cellspacing=1 cellpadding=5>".
	        						  "<tr><th class='main_table_header'>".$lang["pays"][$name."_operator"]."</th>".
	        						  "<th class='main_table_header'>".$lang["pays"][$name."_tarif_list"]."</th>".
	        						  "<th class='main_table_header'>".$lang['pays']['delete_action']."</th></tr>";


	       $operators = array();
	       while (!$rs->EOF) {
				$operator_id = $rs->fields[0];
				$operators[] = $rs->getRowAssoc( false );
				$data["table_options"] .= "<tr><td valign='top'><input type='text' name='smscoin_operators[".$operator_id."]' value='".$lang["smscoin_operators"][$operator_id]."'>".
										  "</td><td><table>";
		        $strSQL = "SELECT id, FORMAT(amount,2) AS amount FROM ".BILLING_SYS_.$name._TARIF." ".
		        		  "WHERE operator_id='$operator_id' ORDER BY amount ASC";
				$res = $dbconn->Execute($strSQL);
				if ($res->RowCount() > 0) {
					while (!$res->EOF) {
						$row = $res->GetRowAssoc( false );
						$data["table_options"] .=
					 		  "<tr>".
			                        "<td>".$row['amount']." $site_unit_costunit</td>".
			                        "<td><input type='button' value='".$lang['pays']['delete']."' onClick='document.location.href=".'"'."admin_payment.php?sel=".$name."_delete_tarif&settype=$name&tarif_id=".$row['id'].'"'.";'></td>".
			                  "</tr>";
						$res->MoveNext();
					}
				} else {
					$data["table_options"] .=  "<tr><td><font class='error'>".$lang['pays'][$name.'_add_tarif']."</font></td></tr>";
				}
				$data["table_options"] .= "</table></td>".

				"<td valign='top'><input type='button' value='".$lang['pays']['delete']."' onClick='document.location.href=".'"'."admin_payment.php?sel=".$name."_delete_operator&settype=$name&operator_id=".$operator_id.'"'.";'</td>
				</tr>";
				$rs->MoveNext();
			}
			$data["table_options"] .= "</table>";
			$data["table_options"] .= "<table cellpadding='0' cellspacing='0' border='0' class='form_big_table' style='margin-bottom: 10px;'>
				<tr>
					<td>".$lang["pays"][$name."_new_tarif"].":</td>
					<td><input type='text' name='tarif' size='5'>&nbsp;$site_unit_costunit</td>
					<td>".$lang["pays"][$name."_oper_tarif"].":</td>
					<td><select name='tarif_operator'>";

			foreach ($operators as $oper) {
					$data["table_options"] .= "<option value='".$oper["id"]."'>".$lang["smscoin_operators"][$oper["id"]];
			}
			$data["table_options"] .= "</select></td>
					<td><input type='button' value='".$lang['buttons']['add']."' onClick='document.forms.money_form.sel.value=\"".$name."_add_tarif\"; document.money_form.submit();'></td>
				</tr>
			</table>
			";
        }
		return $data;
}

//установка(изменение) данных о платежке. применяется в admin/admin_payment.php
function setBillingData($name, $lang, $__POST) {
	global $dbconn;
			$purse_id = strval($__POST["purse_id"]);
			$secret_code = strval($__POST["secret_code"]);
			$use = (isset($__POST["use"]) && !empty($__POST["use"])) ? intval($__POST["use"]) : 0;

			$err = 0;
			if(!$purse_id){
				$err = $lang["errors"]["invalid_fields"];
				$err .= "<br>".$lang["pays"][$name."_seller_id"];
			} else {
				$strSQL = "UPDATE ".BILLING_PAYSYSTEMS_TABLE." SET used='".$use."' WHERE name='".$name."'";
				$dbconn->Execute($strSQL);

				$strSQL = "UPDATE ".BILLING_SYS_.$name." SET ".
						  "purse_id='".$purse_id."', secret_code='".$secret_code."' ".
						  "WHERE name='".$name."'";
				$dbconn->Execute($strSQL);

			}
		return $err;
}

//необходимые значения проведенноого платежа.применяется в include/payment_request.php
function getPaymentValue($valuename,$__POST) {
	global $dbconn;

	switch ($valuename) {
		case "count":	return $__POST['s_amount'];
				break;
		case "curency":	{
					$rs = $dbconn->Execute("SELECT value FROM ".SETTINGS_TABLE." WHERE name = 'site_unit_costunit'");
					return $rs->fields[0];
				}
				break;
		case "date":	return date("Y-m-d H:i:s");
				break;
		case "status":	{
					// service secret code
				    $rs = $dbconn->Execute("SELECT secret_code FROM ".BILLING_SYS_."smscoin");
					$secret_code = $rs->fields[0];
					// collecting required data
					$purse        = $__POST["s_purse"];        // sms:bank id
					$order_id     = $__POST["s_order_id"];     // operation id
					$amount       = $__POST["s_amount"];       // transaction sum
					$clear_amount = $__POST["s_clear_amount"]; // billing algorithm
					$inv          = $__POST["s_inv"];          // operation number
					$phone        = $__POST["s_phone"];        // phone number
					$sign         = $__POST["s_sign_v2"];      // signature

					// making the reference signature
					$reference = ref_sign($secret_code, $purse, $order_id, $amount, $clear_amount, $inv, $phone);

					// validating the signature
					return ($sign == $reference) ? 1 : 0;
				}
				break;
		case "id_req":
				return $__POST["s_order_id"];
				break;
		case "quantity":return 1;
				break;
	}
}

function ref_sign() {
	$params = func_get_args();
	$prehash = implode("::", $params);
	return md5($prehash);
}
?>