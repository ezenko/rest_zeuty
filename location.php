<?php
/**
* AJAX called file for loading county, region, cite, check if email or login is already exists
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.4 $ $Date: 2009/01/21 11:00:20 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

header ("Content-Type: text/html; charset=utf-8");

if ( (!isset($_GET["sel"])) || (!isset($_GET["sec"])) ) exit;

$sec = $_GET["sec"];
$sel = $_GET["sel"];
$elem_postfix = ($sec == "sl") ? "_add" : "";
$lang["default_select"] = GetLangContent("default_select");
switch($sec){
	case "hp":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "ip":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "sl":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "ps":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "psr":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "rnte":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "rmte":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	case "qs":
		$select_country_name = "country";
		$select_region_name = "region";
		$select_city_name = "city";
		$select_style = "class=\"location\"";
		break;
	default: exit;
}

switch($sel){
	case "country":
		$rs = $dbconn->Execute("select * from ".COUNTRY_TABLE." ORDER BY name");
		echo "<select name=\"".$select_country_name."\" ".$select_style." onchange=\"javascript: SelectRegion('".$sec."', this.value, document.getElementById('region_div".$elem_postfix."'), document.getElementById('city_div".$elem_postfix."'));\">";
		echo "<option value=\"0\">".$lang["default_select"][$sec."_country"]."</option>";
		while (!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			if ($config["lang_ident"]!='ru'){
				$row["name"] = RusToTranslit($row["name"]);
			}
			echo "<option value=\"".$row["id"]."\">".stripslashes(htmlspecialchars($row["name"]))."</option>";
			$rs->MoveNext();
		}
		echo "</select>";
		break;
	case "region":
		echo "<select name=\"".$select_region_name."\" ".$select_style." onchange=\"javascript: SelectCity('".$sec."', this.value, document.getElementById('city_div".$elem_postfix."'),'".$lang["default_select"][$sec."_load_city"]."' );\">";
		echo "<option value=\"\">".$lang["default_select"][$sec."_region"]."</option>";
		if (isset($_GET["id_country"])){
			$rs = $dbconn->Execute("SELECT * FROM ".REGION_TABLE." WHERE id_country='".intval($_GET["id_country"])."' ORDER BY name");
			while (!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				if ($config["lang_ident"]!='ru'){
					$row["name"] = RusToTranslit($row["name"]);
				}
				echo "<option value=\"".$row["id"]."\">".stripslashes(htmlspecialchars($row["name"]))."</option>";
				$rs->MoveNext();
			}
		}
		echo "</select>";
		break;
	case "city":
		echo "<select name=\"".$select_city_name."\" ".$select_style.">";
		echo "<option value=\"0\">".$lang["default_select"][$sec."_city"]."</option>";
		if (isset($_GET["id_region"])){
			$rs = $dbconn->Execute("SELECT * FROM ".CITY_TABLE." WHERE id_region='".intval($_GET["id_region"])."' ORDER BY name");
			while (!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				if ($config["lang_ident"]!='ru'){
					$row["name"] = RusToTranslit($row["name"]);
				}
				echo "<option value=\"".$row["id"]."\">".stripslashes(htmlspecialchars($row["name"]))."</option>";
				$rs->MoveNext();
			}
		}
		echo "</select>";
		break;
	case "login":
		$login = addslashes($_GET["login"]);
		$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." WHERE login LIKE '".$login."' ";		
		$rs = $dbconn->Execute($strSQL);				
		if ($rs->fields[0])
			echo "exists";
		break;
	case "email":
		$rs = $dbconn->Execute("select count(*) from ".USERS_TABLE." where email='".addslashes($_GET["email"])."'");				
		if ($rs->fields[0])
			echo "exists";
		break;
	case "upload_base":
		sleep(10);
		echo "<font class=\"error_div\">".$lang["default_select"]["base_1"]."</font>".$lang["default_select"]["base_2"]."<a href=\"./contact.php?sel=for_agency\">".$lang["default_select"]["base_3"]."</a>";
		break;
	case "agency_name":
		$agency_name = trim($_GET["agency_name"]);
		$agency_name = addslashes(htmlspecialchars($agency_name, 1, "UTF-8"));
		$user_id = intval($_GET["user_id"]);
	
		if (strlen($agency_name) > 0) { 
			$secret_string = "|-|";
			$yet = "yet";
			
			$strSQL = "SELECT r.id_user FROM ".USER_REG_DATA_TABLE." r 
							LEFT JOIN ".USERS_TABLE." u ON u.id = r.id_user 
							WHERE r.company_name = '".$agency_name."' AND u.status = '1' AND u.user_type = '2' AND r.id_user != '$user_id'";		
			
			$rs = $dbconn->Execute($strSQL);						
			$company_id = $rs->fields[0];		
			if ($company_id){
				$strSQL = "SELECT id_company FROM ".AGENT_OF_COMPANY_TABLE." WHERE id_agent = '$user_id'";
				$rs = $dbconn->Execute($strSQL);		
				if ( $rs->fields[0]>0 ) {		
					$i = 0;		
					while(!$rs->EOF) {
						$row = $rs->GetRowAssoc(false);
						$id_arr[$i] = $row["id_company"];
						$rs->MoveNext();
						$i++;
					}
				}
		
				if ((isset($id_arr)) && (in_array($company_id, $id_arr))){
					echo $secret_string.$yet.$agency_name;
				}else{
					echo $company_id;
				}			
			}else{
				echo $secret_string.$agency_name;
			}
		} else {
			echo "";			
		}	
		break;	
	default: exit;
}



?>