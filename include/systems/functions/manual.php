<?php
/**
* Functions for manual payment 
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.2 $ $Date: 2009/01/14 09:24:37 $
**/

function getBillingData($name, $lang) {
	global $dbconn, $config, $tinymce;
	include_once "../tinymce/tinymce.php";	
		$rs = $dbconn->Execute("Select p.name, p.used, bs.information from ".BILLING_PAYSYSTEMS_TABLE." p INNER JOIN ".BILLING_SYS_.$name." bs ON p.name = bs.name and p.name='".$name."'");
		$data["use"] = $rs->fields[1];
		$data["value"] = $rs->fields[2];
		
		if ($data["use"]) $checked = "checked"; else $checked="";
		$data["table_options"] =
		        "$tinymce<tr>".
                    "<td>".$lang['pays'][$name.'_information'].":&nbsp;</td>".
                    "<td><textarea rows='4' id='content' name='value' style='width:400px; height:200px;'>".$data['value']."</textarea></td>".
                    "<td><input type='checkbox' class='checkbox' name='use' value=1 ".$checked."></td><td>".$lang['pays']['use']."</td></tr>";
		return $data;
}



//установка(изменение) данных о платежке. применяется в admin/admin_payment.php
function setBillingData($name, $lang, $__POST) {
	global $dbconn;
			$value = strval($__POST["value"]);			
			$use = isset($__POST["use"]) ? intval($__POST["use"]) : 0;
			$err = 0;
			if(!$value){
				$err = $lang["errors"]["invalid_fields"];
				$err .= "<br>".$lang["pays"][$name."_seller_id"];
			} else {
				$strSQL = "Update ".BILLING_PAYSYSTEMS_TABLE." set used='".$use."' where name='".$name."'";				
				$dbconn->Execute($strSQL);
				$strSQL = "Update ".BILLING_SYS_.$name." set information='".addslashes($value)."' where name='".$name."'";	
							
				$dbconn->Execute($strSQL);
			}
		return $err;
}
?>