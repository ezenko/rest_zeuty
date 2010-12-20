<?php
/**
* Countries, regions, cities managing
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.5 $ $Date: 2009/01/21 11:22:18 $
**/

include "../include/config.php";
include_once "../common.php";
include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/class.settings_manager.php";

include "../include/class.lang.php";

$auth = auth_user();
$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if( (!($auth[0]>0))  || (!($auth[4]==1))) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$id_country = (isset($_REQUEST["id_country"]) && !empty($_REQUEST["id_country"])) ? intval($_REQUEST["id_country"]) : 0;
$id_region = (isset($_REQUEST["id_region"]) && !empty($_REQUEST["id_region"])) ? intval($_REQUEST["id_region"]) : 0;

$settings_manager = new SettingsManager();
$items_on_page = (isset($_REQUEST["items_on_page"]) && intval($_REQUEST["items_on_page"]) > 0) ? intval($_REQUEST["items_on_page"]) :$settings_manager->GetSiteSettings("admin_rows_per_page");

switch($sel){
	case "add": AddCountry(); break;
	case "del": DelCountry(); break;
	case "edit": ListRegion($id_country); break;
	case "addr": AddRegion($id_country); break;
	case "delr": DelRegion($id_country); break;
	case "listc": ListCity($id_country, $id_region); break;
	case "addc": AddCity($id_country, $id_region); break;
	case "delc": DelCity($id_country, $id_region); break;
	case "editc": EditFormCity($id_country, $id_region); break;
	case "changec": ChangeCity($id_country, $id_region); break;
	case "editr": EditFormRegion($id_country); break;
	case "changer": ChangeRegion($id_country); break;
	case "editcountry": EditFormCountry(); break;
	case "changecountry": ChangeCountry(); break;
	default: ListCountry();
}

/**
 * Get countries list
 *
 * @param string $err
 * @param string $name
 * @return void
 */
function ListCountry($err="", $name=""){
	global $smarty, $dbconn, $config, $items_on_page;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_countries.php";

	IndexAdminPage('admin_countries');
	CreateMenu('admin_lang_menu');
	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = "select count(*) from ".COUNTRY_TABLE." ";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	$lim_min = ($page-1)*$items_on_page;
	$lim_max = $items_on_page;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$strSQL = "select distinct id, name from ".COUNTRY_TABLE." order by name ".$limit_str;
	$rs = $dbconn->Execute($strSQL);

	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spr_arr[$i]["number"] = ($page-1)*$items_on_page+($i+1);
			$spr_arr[$i]["id"] = $row["id"];
			$spr_arr[$i]["name"] = stripslashes($row["name"]);
			$spr_arr[$i]["deletelink"] = $file_name."?sel=del&page=".$page."&id=".$rs->fields[0];
			$spr_arr[$i]["regionslink"] = $file_name."?sel=edit&id_country=".$rs->fields[0];
			$spr_arr[$i]["editlink"] = $file_name."?sel=editcountry&id=".$rs->fields[0];
			$rs->MoveNext();
			$i++;
		}
		$param = $file_name."?";
		$smarty->assign("links", GetLinkArray($num_records,$page,$param,$items_on_page));
		$smarty->assign("countries", $spr_arr);
	}
	///	form
	if(!$err){
		$name = "";
	} else {
		GetErrors($err);
	}

	$form["hiddens"] = "<input type=hidden name=sel value=add>";
	$form["hiddens"] .= "<input type=hidden name=page value=".$page.">";

	$form["action"] = $file_name;

	$smarty->assign("name", $name);
	$smarty->assign("form", $form);

	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_country_table.tpl");
	return;
}

/**
 * Add country
 *
 * @param void
 * @return void
 */
function AddCountry(){
	global $smarty, $dbconn, $config;
	
	$name = trim(strip_tags($_POST["name"]));	
	
	$strSQL = "select count(*) from ".COUNTRY_TABLE." where name = '".Rep_Slashes($name)."'";
	$rs= $dbconn->Execute($strSQL);
	if($rs->fields[0]>0){
		ListCountry("exists_country", Rep_Slashes($name)); return;
	}

	if(strlen($name)>0){
		$strSQL = "insert into ".COUNTRY_TABLE." (name) values ('".Rep_Slashes($name)."')";
		$dbconn->Execute($strSQL);
	}

	ListCountry(); return;
}

/**
 * Delete country
 *
 * @param void
 * @return void
 */
function DelCountry(){
	global $smarty, $dbconn, $config;
	
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if (!$id) {
		ListCountry();
		return;
	}

	$strSQL = "delete from ".COUNTRY_TABLE." where id='".$id."'";
	$dbconn->Execute($strSQL);

	$strSQL = "delete from ".REGION_TABLE." where id_country='".$id."'";
	$dbconn->Execute($strSQL);

	$strSQL = "delete from ".CITY_TABLE." where id_country='".$id."'";
	$dbconn->Execute($strSQL);

	ListCountry(); return;
}


/**
 * Get regions list
 *
 * @param integer $id_country
 * @param string $err
 * @param string $name
 * @return void
 */
function ListRegion($id_country, $err="", $name=""){
	global $smarty, $dbconn, $config, $items_on_page;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_countries.php";

	IndexAdminPage('admin_countries');
	CreateMenu('admin_lang_menu');
	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$rs = $dbconn->Execute("Select name from ".COUNTRY_TABLE." where id = '".$id_country."'");
	$country_name = $rs->fields[0];

	$strSQL = "select count(*) from ".REGION_TABLE." where id_country='".$id_country."'";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	$lim_min = ($page-1)*$items_on_page;
	$lim_max = $items_on_page;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$strSQL = "select distinct id, name from ".REGION_TABLE." where id_country='".$id_country."' order by name ".$limit_str;
	$rs = $dbconn->Execute($strSQL);

	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spr_arr[$i]["number"] = ($page-1)*$items_on_page+($i+1);
			$spr_arr[$i]["id"] = $row["id"];
			$spr_arr[$i]["name"] = stripslashes($row["name"]);
			$spr_arr[$i]["deletelink"] = $file_name."?sel=delr&page=".$page."&id_country=".$id_country."&id=".$rs->fields[0];
			$spr_arr[$i]["citieslink"] = $file_name."?sel=listc&id_country=".$id_country."&id_region=".$rs->fields[0];
			$spr_arr[$i]["editlink"] = $file_name."?sel=editr&id_country=".$id_country."&id=".$rs->fields[0];
			$rs->MoveNext();
			$i++;
		}
		$param = $file_name."?sel=edit&id_country=".$id_country."&";
		$smarty->assign("links", GetLinkArray($num_records,$page,$param,$items_on_page));
		$smarty->assign("regions", $spr_arr);
	}
	///	form
	if(!$err){
		$name = "";
	} else {
		GetErrors($err);
	}

	$smarty->assign("country_name", $country_name);
	$smarty->assign("back_link", $file_name);

	$form["hiddens"] = "<input type=hidden name=sel value=addr>";
	$form["hiddens"] .= "<input type=hidden name=page value=".$page.">";
	$form["hiddens"] .= "<input type=hidden name=id_country value=".$id_country.">";

	$form["action"] = $file_name;

	$smarty->assign("name", $name);

	$smarty->assign("form", $form);

	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_country_region_table.tpl");
	return;
}

/**
 * Add region
 *
 * @param integer $id_country
 * @return void
 */
function AddRegion($id_country){
	global $smarty, $dbconn, $config;
	
	$name = $_POST["name"];	
	
	$strSQL = "select count(*) from ".REGION_TABLE." where id_country='".$id_country."' and name = '".Rep_Slashes($name)."'";
	$rs= $dbconn->Execute($strSQL);
	if($rs->fields[0]>0){
		ListRegion($id_country, "exists_region", Rep_Slashes($name)); return;
	}

	if(strlen($name)>0){
		$strSQL = "insert into ".REGION_TABLE." (id_country, name) values ('".$id_country."', '".Rep_Slashes($name)."')";
		$dbconn->Execute($strSQL);
	}

	ListRegion($id_country); return;
}

/**
 * Delete region
 *
 * @param integer $id_country
 * @return void
 */
function DelRegion($id_country){
	global $smarty, $dbconn, $config;
	
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if(!$id){ ListRegion($id_country); return;}

	$strSQL = "delete from ".REGION_TABLE."  where id='".$id."'";
	$dbconn->Execute($strSQL);

	$strSQL = "delete from ".CITY_TABLE."  where id_region='".$id."'";
	$dbconn->Execute($strSQL);

	ListRegion($id_country); return;
}

/**
 * Get cities list
 *
 * @param integer $id_country
 * @param integer $id_region
 * @param string $err
 * @param string $name
 * @return void
 */
function ListCity($id_country, $id_region, $err="", $name=""){
	global $smarty, $dbconn, $config, $items_on_page;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_countries.php";

	IndexAdminPage('admin_countries');
	CreateMenu('admin_lang_menu');
	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	///// table
	if(!$id_country){ ListCountry(); return;}
	if(!$id_region){ ListRegion($id_country); return;}

	$rs = $dbconn->Execute("Select name from ".COUNTRY_TABLE." where id = '".$id_country."'");
	$country_name = $rs->fields[0];
	$rs = $dbconn->Execute("Select name from ".REGION_TABLE." where id = '".$id_region."'");
	$region_name = $rs->fields[0];

	$strSQL = "select count(*) from ".CITY_TABLE." where id_country='".$id_country."' and id_region='".$id_region."'";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	$lim_min = ($page-1)*$items_on_page;
	$lim_max = $items_on_page;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$strSQL = "select distinct id, name from ".CITY_TABLE." where id_country='".$id_country."' and id_region='".$id_region."' order by name ".$limit_str;
	$rs = $dbconn->Execute($strSQL);

	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spr_arr[$i]["number"] = ($page-1)*$items_on_page+($i+1);
			$spr_arr[$i]["id"] = $row["id"];
			$spr_arr[$i]["name"] = stripslashes($row["name"]);
			$spr_arr[$i]["edit_link"] = $file_name."?sel=editc&page=".$page."&id=".$rs->fields[0]."&id_country=".$id_country."&id_region=".$id_region;
			$spr_arr[$i]["deletelink"] = $file_name."?sel=delc&page=".$page."&id=".$rs->fields[0]."&id_country=".$id_country."&id_region=".$id_region;
			$rs->MoveNext();
			$i++;
		}
		$smarty->assign("cities", $spr_arr);
	}
	$param = $file_name."?sel=listc&id_country=".$id_country."&id_region=".$id_region."&";
	$smarty->assign("links", GetLinkArray($num_records,$page,$param,$items_on_page));
	$smarty->assign("country_name", $country_name);
	$smarty->assign("region_name", $region_name);
	$smarty->assign("back_link", $file_name."?sel=edit&id_country=".$id_country);

	///	form
	if(!$err){
		$name = "";
	} else {
		GetErrors($err);
	}

	$form["hiddens"] = "<input type=hidden name=sel value=addc>";
	$form["hiddens"] .= "<input type=hidden name=e value=1>";
	$form["hiddens"] .= "<input type=hidden name=page value=".$page.">";
	$form["hiddens"] .= "<input type=hidden name=id_country value=".$id_country.">";
	$form["hiddens"] .= "<input type=hidden name=id_region value=".$id_region.">";

	$form["action"] = $file_name;

	$smarty->assign("name", $name);
	$smarty->assign("form", $form);

	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_country_region_form.tpl");
	return;
}

/**
 * Edit city form
 *
 * @param integer $id_country
 * @param integer $id_region
 * @param string $err
 * @return void
 */
function EditFormCity($id_country, $id_region, $err=""){
	global $smarty, $dbconn, $config;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_countries.php";

	IndexAdminPage('admin_countries');
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if(!$id) { ListCity($id_country, $id_region); return;}

	$strSQL = "select name from ".CITY_TABLE." where id='".$id."'";
	$rs = $dbconn->Execute($strSQL);
	$city_name = $rs->fields[0];

	if ($err) {
		GetErrors($err);
		$data["name"] = $_POST["name"];
	} else {
		$data["name"] = $city_name;
	}

	$form["hiddens"] = "<input type=hidden name=sel value=changec>";
	$form["hiddens"] .= "<input type=hidden name=e value=1>";
	$form["hiddens"] .= "<input type=hidden name=page value=\"".$page."\">";
	$form["hiddens"] .= "<input type=hidden name=id_country value=\"".$id_country."\">";
	$form["hiddens"] .= "<input type=hidden name=id_region value=\"".$id_region."\">";
	$form["hiddens"] .= "<input type=hidden name=id value=\"".$id."\">";

	$form["back"] = $file_name."?sel=listc&id_country=".$id_country."&id_region=".$id_region."&page=".$page;
	$form["action"] = $file_name;

	$smarty->assign("data", $data);
	$smarty->assign("form", $form);

	$rs = $dbconn->Execute("Select name from ".COUNTRY_TABLE." where id = '".$id_country."'");
	$smarty->assign("country_name", $rs->fields[0]);
	$rs = $dbconn->Execute("Select name from ".REGION_TABLE." where id = '".$id_region."'");
	$smarty->assign("region_name", $rs->fields[0]);

	$smarty->assign("city_name", $city_name);

	$smarty->assign("sel_edit", "city");
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_country_edit_form.tpl");
	return;
}

/**
 * Add city
 *
 * @param integer $id_country
 * @param integer $id_region
 * @return void
 */
function AddCity($id_country, $id_region){
	global $smarty, $dbconn, $config;
	$name = $_POST["name"];
	
	if(!$id_country){ListCountry(); return;}
	if(!$id_region){ListRegion($id_country); return;}

	$strSQL = "select id from ".CITY_TABLE." where id_country='".intval($id_country)."'";
	$rs = $dbconn->Execute($strSQL);
	$cities = array();
	while(!$rs->EOF){
		$cities[] = $rs->fields[0];
		$rs->MoveNext();
	}
	if(is_array($cities)) $cities_str = implode(", ", $cities);

	$strSQL = "select count(*) from ".CITY_TABLE." where name = '".Rep_Slashes($name)."' and id_country='".$id_country."' and id_region='".$id_region."'";
	$rs= $dbconn->Execute($strSQL);
	if($rs->fields[0]>0){
		ListCity($id_country, $id_region, "exists_city"); return;
	}

	if(strlen($name)>0){
		$strSQL = "insert into ".CITY_TABLE." (id_country, name, id_region) values ('".$id_country."', '".Rep_Slashes($name)."', '".$id_region."')";
		$dbconn->Execute($strSQL);
	}

	ListCity($id_country, $id_region); return;
}

/**
 * Edit city
 *
 * @param integer $id_country
 * @param integer $id_region
 * @return void
 */
function ChangeCity($id_country, $id_region){
	global $smarty, $dbconn, $config;
	$name = $_POST["name"];
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	
	if(!$id_country){ListCountry(); return;}
	if(!$id_region){ListRegion($id_country); return;}

	$strSQL = "select count(*) from ".CITY_TABLE." where name = '".Rep_Slashes($name)."' and id_country='".$id_country."' and id_region='".$id_region."' and id<>'".$id."'";
	$rs= $dbconn->Execute($strSQL);
	if($rs->fields[0]>0){
		EditFormCity($id_country, $id_region, "exists_city"); return;
	}

	if(strlen($name)>0){
		$strSQL = "Update ".CITY_TABLE." set id_country='".$id_country."', id_region='".$id_region."', name='".Rep_Slashes($name)."' where id='".$id."'";
		$dbconn->Execute($strSQL);
	}

	ListCity($id_country, $id_region); return;
}

/**
 * Delete city
 *
 * @param integer $id_country
 * @param integer $id_region
 * @return void
 */
function DelCity($id_country, $id_region){
	global $smarty, $dbconn, $config;
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;

	if(!$id){ ListCity($id_country, $id_region); return;}

	$strSQL = "delete from ".CITY_TABLE."  where id='".$id."'";
	$dbconn->Execute($strSQL);

	ListCity($id_country, $id_region); return;

}

/**
 * Edit region form
 *
 * @param integer $id_country
 * @param string $err
 * @return void
 */
function EditFormRegion($id_country, $err=""){
	global $smarty, $dbconn, $config;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_countries.php";

	IndexAdminPage('admin_countries');
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if(!$id) { ListRegion($id_country); return;}

	$strSQL = "select name from ".REGION_TABLE." where id='".$id."'";
	$rs = $dbconn->Execute($strSQL);
	$region_name = $rs->fields[0];

	if ($err) {
		GetErrors($err);
		$data["name"] = $_POST["name"];
	} else {
		$data["name"] = $region_name;
	}

	$form["hiddens"] = "<input type=hidden name=sel value=changer>";
	$form["hiddens"] .= "<input type=hidden name=e value=1>";
	$form["hiddens"] .= "<input type=hidden name=page value=\"".$page."\">";
	$form["hiddens"] .= "<input type=hidden name=id_country value=\"".$id_country."\">";
	$form["hiddens"] .= "<input type=hidden name=id value=\"".$id."\">";

	$form["back"] = $file_name."?sel=edit&id_country=".$id_country."&page=".$page;
	$form["action"] = $file_name;

	$smarty->assign("data", $data);
	$smarty->assign("form", $form);

	$rs = $dbconn->Execute("Select name from ".COUNTRY_TABLE." where id = '".$id_country."'");
	$smarty->assign("country_name", $rs->fields[0]);

	$smarty->assign("region_name", $region_name);

	$smarty->assign("sel_edit", "region");
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_country_edit_form.tpl");
	return;
}

/**
 * Edit region
 *
 * @param integer $id_country
 * @return void
 */
function ChangeRegion($id_country){
	global $smarty, $dbconn, $config;
	$name = $_POST["name"];
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	
	if(!$id_country){ListCountry(); return;}

	$strSQL = "select count(*) from ".REGION_TABLE." where name = '".Rep_Slashes($name)."' and id_country='".$id_country."' and id<>'".$id."'";
	$rs= $dbconn->Execute($strSQL);
	if($rs->fields[0]>0){
		EditFormRegion($id_country, "exists_region"); return;
	}

	if(strlen($name)>0){
		$strSQL = "Update ".REGION_TABLE." set id_country='".$id_country."', name='".Rep_Slashes($name)."' where id='".$id."'";
		$dbconn->Execute($strSQL);
	}

	ListRegion($id_country); return;
}

/**
 * Edit country form
 *
 * @param string $err
 * @return void
 */
function EditFormCountry($err=""){
	global $smarty, $dbconn, $config;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_countries.php";

	IndexAdminPage('admin_countries');
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if(!$id) { ListCountry(); return;}

	$strSQL = "select name from ".COUNTRY_TABLE." where id='".$id."'";
	$rs = $dbconn->Execute($strSQL);
	$country_name = $rs->fields[0];

	if ($err) {
		GetErrors($err);
		$data["name"] = $_POST["name"];
	} else {
		$data["name"] = $country_name;
	}

	$form["hiddens"] = "<input type=hidden name=sel value=changecountry>";
	$form["hiddens"] .= "<input type=hidden name=e value=1>";
	$form["hiddens"] .= "<input type=hidden name=page value=\"".$page."\">";
	$form["hiddens"] .= "<input type=hidden name=id value=\"".$id."\">";

	$form["back"] = $file_name."?page=".$page;
	$form["action"] = $file_name;

	$smarty->assign("data", $data);
	$smarty->assign("form", $form);

	$smarty->assign("country_name", $country_name);

	$smarty->assign("sel_edit", "country");
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_country_edit_form.tpl");
	return;
}

/**
 * Edit country
 * 
 * @return void
 */
function ChangeCountry() {
	global $smarty, $dbconn, $config;
	$name = $_POST["name"];
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	
	$strSQL = "select count(*) from ".COUNTRY_TABLE." where name = '".Rep_Slashes($name)."' and id<>'".$id."'";
	$rs= $dbconn->Execute($strSQL);
	if($rs->fields[0]>0){
		EditFormCountry("exists_country"); return;
	}

	if(strlen($name)>0){
		$strSQL = "Update ".COUNTRY_TABLE." set name='".Rep_Slashes($name)."' where id='".$id."'";
		$dbconn->Execute($strSQL);
	}

	ListCountry($id); return;
}

?>