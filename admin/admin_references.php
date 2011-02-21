<?php
/**
* References management
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.5 $ $Date: 2008/10/30 14:21:20 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/class.lang.php";

$auth = auth_user();

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

$multi_lang = new MultiLang($config, $dbconn);

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$id_spr = (isset($_REQUEST["id_spr"]) && !empty($_REQUEST["id_spr"])) ? intval($_REQUEST["id_spr"]) : 0;

$section = $_GET["section"]?$_GET["section"]:$_POST["section"];
$smarty->assign("section", $section);

switch($section){
	case "apartment":
	/**
	 * $editable boolean - possibility to add subreference to the reference and edit names for the existing subreferences
	 * if $editable = false; you can only edit values in already existing subreferences
	 */
		$editable = true;
		$spr_table = SPR_APARTMENT_TABLE;
		$values_table = VALUES_APARTMENT_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "type":
		$editable = false;
		$spr_table = SPR_TYPE_TABLE;
		$values_table = VALUES_TYPE_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "description":
		$editable = false;
		$spr_table = SPR_DESCRIPTION_TABLE;
		$values_table = VALUES_DESCRIPTION_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "period":
		$editable = false;
		$spr_table = SPR_PERIOD_TABLE;
		$values_table = VALUES_PERIOD_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "deactivate":
		$editable = false;
		$spr_table = SPR_DEACTIVATE_TABLE;
		$values_table = VALUES_DEACTIVATE_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "gender":
		$editable = false;
		$spr_table = SPR_GENDER_TABLE;
		$values_table = VALUES_GENDER_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "people":
		$editable = true;
		$spr_table = SPR_PEOPLE_TABLE;
		$values_table = VALUES_PEOPLE_TABLE;
		$lang_file_name = "admin_references";
		break;
	case "language":
		$editable = false;
		$spr_table = SPR_LANGUAGE_TABLE;
		$values_table = VALUES_LANGUAGE_TABLE;
		$html = true;
		$lang_file_name = "admin_references";
		break;
    case "theme_rest":
		$editable = false;
		$spr_table = SPR_THEME_REST_TABLE;
		$values_table = VALUES_THEME_REST_TABLE;
		$html = true;
		$lang_file_name = "admin_references";
		break;
	default:
		$editable = true;
		$spr_table = SPR_APARTMENT_TABLE;
		$values_table = VALUES_APARTMENT_TABLE;
		$lang_file_name = "admin_references";
		break;
}

switch($sel){
	case "add": AddSpr(); break;
	case "edit": EditForm("edit"); break;
	case "change": ChangeSpr(); break;
	case "del": DelSpr(); break;
	case "listopt": ListOption($id_spr); break;
	case "addopt": AddOption($id_spr); break;
	case "delopt": DelOption($id_spr); break;
	case "update": UpdateOption($id_spr); break;
	default: ListSpr();
}


function ListSpr($err = ""){
	global $smarty, $dbconn, $config, $multi_lang, $section, $spr_table, $values_table, $lang_file_name, $editable, $html;
	$smarty->assign("editable", $editable);
	$smarty->assign("add_to_lang", "&section=".$section);
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"])."?section=".$section;
	else
		$file_name = "admin_references.php?section=".$section;

	IndexAdminPage($lang_file_name);

	CreateMenu('admin_lang_menu');
	
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = "select count(*) from ".$spr_table;
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	$reference_numpage = GetSiteSettings("reference_numpage");

	$lim_min = ($page-1)*$reference_numpage;
	$lim_max = $reference_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$table_key = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName();

	$strSQL = "	SELECT DISTINCT a.id, b.".$field_name." as name, a.sorter, a.type, a.des_type, a.visible_in
				FROM ".$spr_table." a left join
				".REFERENCE_LANG_TABLE." b on b.table_key='".$table_key."' and b.id_reference=a.id
				order by a.sorter ".$limit_str;
                
    $rs = $dbconn->Execute($strSQL);
	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spr_arr[$i]["number"] = $i+1;
			$spr_arr[$i]["id"] = $row["id"];
			$spr_arr[$i]["name"] = $row["name"];
			$spr_arr[$i]["type"] = $row["type"];
			$spr_arr[$i]["des_type"] = $row["des_type"];
			$spr_arr[$i]["visible_in"] = $row["visible_in"];
			$spr_arr[$i]["editlink"] = $file_name."&sel=edit&page=".$page."&id=".$row["id"];
			$spr_arr[$i]["editoptionlink"] = $file_name."&sel=listopt&page=".$page."&id_spr=".$row["id"];
			$rs->MoveNext();
			$i++;
		}
		$param = $file_name."&";
		$smarty->assign("links", GetLinkArray($num_records,$page,$param,$reference_numpage));
		$smarty->assign("references", $spr_arr);
	}

	$smarty->assign("add_link", $file_name."&sel=add&page=".$page);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_spr_table.tpl");
	exit;
}

function EditForm($par, $err="",$name="", $sorter=""){
	global $smarty, $dbconn, $config, $multi_lang, $section, $spr_table, $values_table,$lang_file_name, $editable, $html;

	$smarty->assign("editable", $editable);
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"])."?section=".$section;
	else
		$file_name = "admin_references.php?section=".$section;

	IndexAdminPage($lang_file_name);

	$smarty->assign("add_to_lang", "&section=".$section."&sel=".$par."&id=".$_GET["id"]);
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$rs = $dbconn->Execute("SELECT COUNT(*) FROM ".$spr_table." ");
	$ref_count = $rs->fields[0];
	for($i=0;$i<$ref_count;$i++){
		$sorter_arr[$i]["sel"] = 0;
	}

	if($par != "add"){
		$id = $_GET["id"];

		if(!$id){ ListSpr(); return;}

		if(!$err){
			$table_key = $multi_lang->TableKey($spr_table);
			$name_temp = $multi_lang->SelectDefaultLangNames($table_key, $id);
			$name_1 = htmlspecialchars($name_temp["name_1"]);
			$name_2 = htmlspecialchars($name_temp["name_2"]);
			$strSQL = "select name, sorter, type, des_type, visible_in from ".$spr_table." where id='".$id."'";
			$rs = $dbconn->Execute($strSQL);
			$sorter_arr[$rs->fields[1]-1]["sel"] = "1";
			$type = $rs->fields[2];
			$des_type = $rs->fields[3];
			$visible_in = $rs->fields[4];
		}else{
			$sorter_arr[$sorter-1]["sel"] = "1";
		}
		$form["hiddens"] = "<input type=hidden name=sel value=change>";
		$form["hiddens"] .= "<input type=hidden name=page value=".$page.">";
		$form["hiddens"] .= "<input type=hidden name=id value=".$id.">";
	}else{
		if(!$err){
			$name_1 = "";
			$name_2 = "";
			$sorter_arr[$ref_count]["sel"] = "1";
		}
		$form["hiddens"] = "<input type=hidden name=sel value=add>";
		$form["hiddens"] .= "<input type=hidden name=page value=".$page.">";

	}

	$form["hiddens"] .= "<input type=hidden name=e value=1>";
	if ($err) {GetErrors($err);}
	$form["delete"] = $file_name."&sel=del&id=".$id."&page=".$page;
	$form["back"] = $file_name."&page=".$page;
	$form["action"] = $file_name;
	$form["par"] = $par;

	$table_key = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName();
	$rs = $dbconn->Execute("Select ".$field_name." FROM ".REFERENCE_LANG_TABLE." where id_reference = '".$id."' AND table_key='".$table_key."'");
	$reference_name = $rs->fields[0];

	$smarty->assign("reference_name", $reference_name);

	$smarty->assign("name_1", $name_1);
	$smarty->assign("name_2", $name_2);
	$smarty->assign("sorter", $sorter_arr);
	$smarty->assign("type", $type);
	$smarty->assign("des_type", $des_type);
	$smarty->assign("visible_in", $visible_in);
	$smarty->assign("form", $form);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_spr_form.tpl");
	exit;
}

function ChangeSpr(){
	global $smarty, $dbconn, $config, $multi_lang, $spr_table, $values_table, $lang_file_name, $editable, $html;

	$smarty->assign("editable", $editable);
	$id = $_POST["id"];
	//$name = $_POST["name"];
	$name_1 = $_POST["name_1"];
	$name_2 = $_POST["name_2"];
	if ($editable){
	/**
	 * if !$editable, user can change only names
	 */
		$type = intval($_POST["type"]);
		$des_type = intval($_POST["des_type"]);
		$visible_in = (isset($_POST["visible_in"]) && !empty($_POST["visible_in"])) ? intval($_POST["visible_in"]) : 1;
		$sorter = intval($_POST["sorter"]);
	}
	$page = intval($_POST["page"]);

	if(!$sorter)	 $sorter = 1;

	if(strlen($name_1)<1){
		$err = "empty_fields";
		EditForm("edit", $err, $name_1 , $sorter);
		exit;
	}
	if ($editable){
		$strSQL = "update ".$spr_table."  set name='".Rep_Slashes($name_1)."', type='".$type."', des_type='".$des_type."', visible_in='".$visible_in."' WHERE id='".$id."'";
	$dbconn->Execute($strSQL);
	}

	$table_key = $multi_lang->TableKey($spr_table);
	$multi_lang->SaveDefaultRefNames($table_key, $name_1, $name_2, $id);

	if ($editable){
		$strSQL = "select sorter from ".$spr_table." where id = '".$id."' ";
		$rs = $dbconn->Execute($strSQL);

		$old_sorter = $rs->fields[0];
		SprSorter($sorter, $old_sorter, $id);
	}
	ListSpr();
	return;
}

function DelSpr(){
	global $smarty, $dbconn, $config, $multi_lang, $spr_table, $values_table, $lang_file_name, $editable, $html;
	$smarty->assign("editable", $editable);
	$id = $_POST["id"]?$_POST["id"]:$_GET["id"];
	$page = $_GET["page"]?$_GET["page"]:$_POST["page"];

	if(!$id){ ListSpr(); return;}

	$strSQL = "select sorter from ".$spr_table." where id = '".intval($id)."' ";
	$rs = $dbconn->Execute($strSQL);
	$old_sorter = $rs->fields[0];

	$strSQL = "delete from ".$spr_table."  where id='".intval($id)."'";
	$dbconn->Execute($strSQL);

	$table_key = $multi_lang->TableKey($spr_table);
	$multi_lang->DeleteRefName($id, $table_key);
	$val_arr = $multi_lang->ValuesIdArray(intval($id), $table_key+1);

	$strSQL = "delete from ".$values_table."  where id_spr='".intval($id)."'";
	$dbconn->Execute($strSQL);
	$multi_lang->DeleteRefNames($val_arr, $table_key+1);

	SprSorter("", $old_sorter);
	ListSpr(); return;
}

function AddSpr(){
	global $smarty, $dbconn, $config, $multi_lang, $spr_table, $values_table, $lang_file_name, $editable, $html;
	$smarty->assign("editable", $editable);
	$e = $_POST["e"];//first time clicking 'add' e will be eq 0
	$name = $_POST["name"];
	$type = intval($_POST["type"]);
	$des_type = intval($_POST["des_type"]);
	$visible_in = (isset($_POST["visible_in"]) && !empty($_POST["visible_in"])) ? intval($_POST["visible_in"]) : 1;

	$sorter = intval($_POST["sorter"]);
	$page = intval($_POST["page"]);

	if(!$sorter)	 $sorter = 1;
	if(strlen($name)<1){
		if ($e) {$err = "empty_fields";}
		EditForm("add", $err, "", $sorter);
		return;
	}

	$table_key = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName();

	$strSQL = "select count(*) from ".REFERENCE_LANG_TABLE." where ".$field_name." = '".Rep_Slashes($name)."' and table_key='".$table_key."'";
	$rs= $dbconn->Execute($strSQL);

	if($rs->fields[0]>0){
		$err = "exists_reference";
		EditForm("add", $err, $name , $sorter);
		return;
	}

	$strSQL = "INSERT INTO ".$spr_table." (name, sorter, type, des_type, visible_in) VALUES ('".Rep_Slashes($name)."', '".$sorter."','".$type."', '".$des_type."', '".$visible_in."')";
	$dbconn->Execute($strSQL);

	//// add sorter
	$rs = $dbconn->Execute("SELECT MAX(id) from ".$spr_table."");
	$rs_os = $dbconn->Execute("SELECT MAX(sorter)+1  from ".$spr_table."");

	$multi_lang->FirstLangInsert($table_key, $rs->fields[0], Rep_Slashes($name));
	SprSorter($sorter, $rs_os->fields[0], $rs->fields[0]);

	ListSpr(); return;
}

function ListOption($id_spr, $err="", $name=""){
	global $smarty, $dbconn, $config, $multi_lang, $section, $spr_table, $values_table, $lang_file_name, $editable, $html;
	$smarty->assign("editable", $editable);
	if ($err){
		GetErrors($err);
	}

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"])."?section=".$section;
	else
		$file_name = "admin_references.php?section=".$section;

	IndexAdminPage($lang_file_name);

	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	///// table
	if(!$id_spr){ ListSpr(); return;}

	$smarty->assign("add_to_lang", "&section=".$section."&sel=listopt&id_spr=".$id_spr."&page=".$page);

	$table_key = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName();

	$rs = $dbconn->Execute("Select ".$field_name." FROM ".REFERENCE_LANG_TABLE." where id_reference = '".$id_spr."' AND table_key='".$table_key."'");

	$reference_name = $rs->fields[0];

	$table_key = $multi_lang->TableKey($values_table);

	$field_name_1 = $multi_lang->DefaultFieldName();
	$field_name_2 = $multi_lang->DefaultFieldName('2');

	$strSQL = "	select distinct a.id, b.".$field_name_1." as name_1, b.".$field_name_2." as name_2, b.id as id_ref
				from ".$values_table." a
				left join ".REFERENCE_LANG_TABLE." b on b.table_key='".$table_key."' and b.id_reference=a.id
				where a.id_spr='".$id_spr."' order by b.id ";
	
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spr_arr[$i]["number"] = $i+1;
			$spr_arr[$i]["id"] = $row["id"];
			$spr_arr[$i]["id_ref"] = $row["id_ref"];
			$spr_arr[$i]["name_1"] = htmlspecialchars($row["name_1"]);
			$spr_arr[$i]["name_2"] = htmlspecialchars($row["name_2"]);
			$spr_arr[$i]["dellink"] = $file_name."&sel=delopt&page=".$page."&id=".$row["id"]."&id_spr=".$id_spr."&id_ref=".$row["id_ref"];
			$rs->MoveNext();
			$i++;
		}
		$smarty->assign("references", $spr_arr);
	}

	$smarty->assign("reference_name", $reference_name);
	$smarty->assign("back_link", $file_name);

	if(!$err){	$name = "";	}

	$form["hiddens"] = "<input type=hidden name=sel value=addopt>";
	$form["hiddens"] .= "<input type=hidden name=page value=".$page.">";
	$form["hiddens"] .= "<input type=hidden name=id_spr value=".$id_spr.">";

	$form["action"] = $file_name;

	$smarty->assign("name", $name);

	$smarty->assign("form", $form);

	$smarty->assign("page", $page);
	$smarty->assign("id_spr", $id_spr);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_spr_option_table.tpl");
	exit;
}

function AddOption($id_spr){
		global $smarty, $dbconn, $config, $multi_lang, $spr_table, $values_table, $lang_file_name, $editable, $html;
		$smarty->assign("editable", $editable);
		$name = $_POST["name"];
		$page = intval($_POST["page"]);

		if(!$id_spr){ListSpr(); return;}

		$table_key = $multi_lang->TableKey($values_table);
		$field_name = $multi_lang->DefaultFieldName();

		$strSQL = "select id from ".$values_table." where id_spr='".intval($id_spr)."'";
		$rs = $dbconn->Execute($strSQL);
		$opts = array();
		while(!$rs->EOF){
			$opts[] = $rs->fields[0];
			$rs->MoveNext();
		}
		if(is_array($opts)) $opts_str = implode(", ", $opts);

		$strSQL = "select count(*) from ".REFERENCE_LANG_TABLE." where ".$field_name." = '".Rep_Slashes($name)."' and id_reference in (".$opts_str.") and table_key='".$table_key."'";
		$rs= $dbconn->Execute($strSQL);
		if($rs->fields[0]>0){
			$err = "exists_option";
			ListOption($id_spr, $err, Rep_Slashes($name));
			return;
		}

		if(strlen($name)>0){
			$strSQL = "insert into ".$values_table." (id_spr, name) values ('".$id_spr."', '".Rep_Slashes($name)."')";
			$dbconn->Execute($strSQL);
			$rs=$dbconn->Execute("Select max(id) from ".$values_table." ");
			$last_id = $rs->fields[0];
			$multi_lang->FirstLangInsert($table_key, $last_id, Rep_Slashes($name));
		}
		ListOption($id_spr); return;
}

function DelOption($id_spr){
		global $smarty, $dbconn, $config, $multi_lang, $spr_table, $values_table, $lang_file_name, $editable, $html;
		$smarty->assign("editable", $editable);
		
		$id = intval($_REQUEST["id"]);

		if (!$id) { 
			ListOption($id_spr); 
			return;
		}
		$strSQL = "DELETE FROM ".$values_table." WHERE id='".$id."'";		
		$dbconn->Execute($strSQL);
		
		$multi_lang->DeleteRefLangValue(intval($_REQUEST["id_ref"]));

		ListOption($id_spr); return;
}

function UpdateOption($id_spr){
	global $smarty, $dbconn, $config, $multi_lang, $spr_table, $values_table, $lang_file_name, $editable, $html;
	$smarty->assign("editable", $editable);
	$page = intval($_POST["page"]);

	$spr_options = $_POST["spr_options"];

	$table_key = $multi_lang->TableKey($values_table);

	$multi_lang->SaveNames($table_key, $config["default_lang"],$spr_options);

	ListOption($id_spr);
	return;
}
function SprSorter($sorter, $old_sorter, $id=""){
	global $smarty, $dbconn, $spr_table, $values_table,$lang_file_name, $editable, $html;
	$smarty->assign("editable", $editable);
	if(!$id){
		$strSQL = "select id, sorter from ".$spr_table." where sorter >= '".$old_sorter."'  order by sorter";
		$rs = $dbconn->Execute($strSQL);
		while(!$rs->EOF){
			$rs_up = $dbconn->Execute("update ".$spr_table." set sorter = '".($rs->fields[1]-1)."' where id ='".$rs->fields[0]."' ");
			$rs->MoveNext();
		}
		return;
	}
	//// sorter
	if($old_sorter<$sorter){
		$strSQL = "select id, sorter from ".$spr_table." where sorter >= '".$old_sorter."' and  sorter <= '".$sorter."'  order by sorter";
		$rs = $dbconn->Execute($strSQL);
		while(!$rs->EOF){
			$rs_up = $dbconn->Execute("update ".$spr_table." set sorter = '".($rs->fields[1]-1)."' where id ='".$rs->fields[0]."' ");
			$rs->MoveNext();
		}
		//// add sorter
		$rs_up = $dbconn->Execute("update ".$spr_table." set sorter = '".$sorter."' where id ='".$id."' ");

	}elseif($old_sorter>$sorter){
		$strSQL = "select id, sorter from ".$spr_table." where sorter <= '".$old_sorter."' and  sorter >= '".$sorter."' order by sorter";
		$rs = $dbconn->Execute($strSQL);
		while(!$rs->EOF){
			$rs_up = $dbconn->Execute("update ".$spr_table." set sorter = '".($rs->fields[1]+1)."' where id ='".$rs->fields[0]."' ");
			$rs->MoveNext();
		}
		//// add sorter
		$rs_up = $dbconn->Execute("update ".$spr_table." set sorter = '".$sorter."' where id ='".$id."' ");
	}else{
		$rs_up = $dbconn->Execute("update ".$spr_table." set sorter = '".$sorter."' where id ='".$id."' ");
	}
	return;
}

?>