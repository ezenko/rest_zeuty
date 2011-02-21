<?php
/**
* File for inclusion to each file, contains database connection, copy of Smarty object,
* assign general config variables to smarty variables
*
* @package RealEstate
* @subpackage Public mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.3 $ $Date: 2008/10/24 14:18:54 $
**/

ini_set("display_errors", "1");
error_reporting(E_ALL & ~E_NOTICE);

ini_set("default_charset", "utf8");

session_start();
header('Content-type: text/html; charset=utf-8');

if ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || ini_get('magic_quotes_sybase')) {	
    ArrStripSlashes($_POST);    
    ArrStripSlashes($_GET);
    ArrStripSlashes($_REQUEST);
}
        
if(!$config["server"] || !$config["dbname"] || !$config["dbhost"] || !$config["dbuname"]){
  $path = substr(__FILE__,0, -10);
  echo "<script>location.href='./install/index.php'</script>";
  exit;
}

if (substr(php_uname(), 0, 7) == "Windows") {
	$config["system"] = "win";
} else {
	$config["system"] = "unix";
}

$dir = $config["site_path"];
if (strlen($dir)==0) {
	$dir = ".";
	$dir = dirname(__FILE__);
}

define('PAYMENT_DIR', $dir."/include/");
define('SYSTEMS_DIR', $dir."/include/systems/classes/");
include_once PAYMENT_DIR."Payment_Config.php";
include_once PAYMENT_DIR."Payment_Engine.php";

$config["file_temp_path"] = $config["site_path"]."/templates_c";

switch ($config["system"]) {
	case "unix":
		ini_set("include_path", ".:".$dir.":".$dir."/include:".$dir."/adodb:".$dir."/smarty");
		break;
	case "win":
		ini_set("include_path", ".;".$dir.";".$dir."/include;".$dir."/adodb;".$dir."/smarty");
		break;
}

include "constants.php";

include_once "adodb/tohtml.inc.php";
include_once "adodb/adodb.inc.php";

function PN_DBMsgError($db='',$prg='',$line=0,$message='Error accesing to the database') {
	$lcmessage = $message . "<br>" .
	"Program: " . $prg . " - " . "Line N.: " . $line . "<br>" .
	"Database: " . $db->database . "<br> ";

	if ($db->ErrorNo()<>0) {
		$lcmessage .= "Error (" . $db->ErrorNo() . ") : " . $db->ErrorMsg() . "<br>";
	}
	die($lcmessage);
}
$dbconn = &ADONewConnection($config['dbtype']);
GLOBAL $ADODB_FETCH_MODE;

if ($config['dbtype'] == "ado_mssql") {
	if ($config['useoledb'] == 1) {
		$connectString = "SERVER=".$config['dbhost'].";DATABASE=".$config['dbname'].";";
		$dbh = $dbconn->Connect($connectString, $config["dbuname"], $config["dbpass"], "SQLOLEDB");
	} else {
		$connectString="PROVIDER=MSDASQL;DRIVER={SQL Server};"."SERVER=".$config['dbhost'].";DATABASE=".$config['dbname'].";id_user=".$config['dbuname'].";PWD=".$config['dbpass'].";";
		$dbh = $dbconn->Connect($connectString, "", "", "");
	}
} else {
	$connectString = $config['dbtype'].":".$config['dbuname'].":".$config['dbpass']."@".$config['dbhost']."/".$config['dbname'];
	$dbh = $dbconn->Connect($config['dbhost'],($config['dbuname']),($config['dbpass']),$config['dbname']);
}
$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

if ($dbh === false) {
	error_log ("connect string: $connectString");
	error_log ("error: " . $dbconn->ErrorMsg());
	PN_DBMsgError($dbconn, __FILE__ , __LINE__, "Error connecting to db".$config['dbname']);
}
$dbconn->Execute("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");

$rs_smarty=$dbconn->Execute("select value from ".SETTINGS_TABLE." where name='admin_theme_path'");
if($rs_smarty->fields[0])
$config["admin_theme_path"] = $rs_smarty->fields[0];
else
$config["admin_theme_path"] = "/templates/admin";

$rs_smarty=$dbconn->Execute("select value from ".SETTINGS_TABLE." where name='index_theme_path'");
if($rs_smarty->fields[0])
$config["index_theme_path"] = $rs_smarty->fields[0];
else
$config["index_theme_path"] = "/templates/default_theme";

$rs_smarty=$dbconn->Execute("select value from ".SETTINGS_TABLE." where name='index_theme_css_path'");
if($rs_smarty->fields[0])
$config["index_theme_css_path"] = $rs_smarty->fields[0];
else
$config["index_theme_css_path"] = "/css";

$rs_smarty=$dbconn->Execute("select value from ".SETTINGS_TABLE." where name='index_theme_images_path'");
if($rs_smarty->fields[0])
$config["index_theme_images_path"] = $rs_smarty->fields[0];
else
$config["index_theme_images_path"] = "/images";

$rs_smarty=$dbconn->Execute("select value from ".SETTINGS_TABLE." where name='menu_path'");
if($rs_smarty->fields[0])
$config["menu_path"] = $rs_smarty->fields[0];
else
$config["menu_path"] = "menu/";

include_once "smarty/Smarty.class.php";

if ((isset($smarty) && !is_object($smarty)) || !isset($smarty))
$smarty = new Smarty;
$smarty->force_compile = true;			// $smarty->force_compile = false;
$smarty->template_dir = $dir."/templates/";
$smarty->compile_dir = $dir."/templates_c";
$smarty->plugins_dir = $dir."/smarty/plugins";

if ($config["system"] == "win"){
	$smarty->assign("gentemplates", "file:".$dir.$config["index_theme_path"]);
	$smarty->assign("admingentemplates", "file:".$dir.$config["admin_theme_path"]);
} else {
	$smarty->assign("gentemplates", "file:".$config["site_path"].$config["index_theme_path"]);
	$smarty->assign("admingentemplates", "file:".$config["site_path"].$config["admin_theme_path"]);
}
if (!$smarty)  die("Smarty error");

$rs = $dbconn->Execute("SELECT value FROM ".SETTINGS_TABLE." WHERE name='default_lang'");
if($rs->fields[0]) {
	$config["default_lang"] = $rs->fields[0];
} else {
	$rs = $dbconn->Execute("SELECT MIN(id) AS id FROM ".LANGUAGE_TABLE." WHERE visible='1'");
	$config["default_lang"] = $rs->fields[0];
}

if(isset($_GET["lang_code"]) && strlen($_GET["lang_code"])>0){
	$_SESSION["lang_cd"] = $_GET["lang_code"];
}

if(isset($_SESSION["lang_cd"]) && strval($_SESSION["lang_cd"])!=""){
	/**
	 * check for visibility
	 */
	$rs = $dbconn->Execute("SELECT visible FROM ".LANGUAGE_TABLE." WHERE id='".intval($_SESSION["lang_cd"])."'");
	if ($rs->RowCount() > 0 && $rs->fields[0] == 1) {
		$lang_code = $_SESSION["lang_cd"];
		$config["default_lang"] = $_SESSION["lang_cd"];
	} else {
		$lang_code = $config["default_lang"];
	}
}else{
	$lang_code = $config["default_lang"];
}

if(isset($_GET["lang_from_admin"]) && strlen($_GET["lang_from_admin"])>0){
	$lang_code = $_GET["lang_from_admin"];
	$config["default_lang"] = $_GET["lang_from_admin"];
}

$strSQL = "SELECT charset, lang_path, code FROM ".LANGUAGE_TABLE." WHERE id='".$lang_code."'";
$rs = $dbconn->Execute($strSQL);

$charset = $rs->fields[0];
$config["lang_path"] = $rs->fields[1];
$config["lang_ident"] = $rs->fields[2];
$lang_cod = $rs->fields[2];

$smarty->assign("lang_code", $lang_code);
$smarty->assign("default_lang", $lang_cod);
$smarty->assign("charset", $charset);
$config["charset"] = $charset;

$langpath_for_image = str_replace('lang/','',$config["lang_path"]);
$langpath_for_image = substr($langpath_for_image, 0,strlen($langpath_for_image)-1);
$config["index_theme_images_path"] = "/images".$langpath_for_image;

$smarty->assign("site_root", $config["site_root"]);
$smarty->assign("server", $config["server"]);
$smarty->assign("image_lang_path", $config["index_theme_images_path"]);
$smarty->assign("mail_template_root", $config["index_theme_path"]);

$rs = $dbconn->Execute("SELECT value FROM ".SETTINGS_TABLE." WHERE name='date_format'");
$config["date_format"] = trim($rs->fields[0]);

$rs = $dbconn->Execute("SELECT value FROM ".SETTINGS_TABLE." WHERE name='site_mode'");
$config["site_mode"] = trim($rs->fields[0]);
$smarty->assign("site_mode", $config["site_mode"]);

$config["mode_hide_ids"] = GetHideModeIds($config["site_mode"]);
AssignHideModeIds();

$rs = $dbconn->Execute("SELECT type, pic_$lang_code AS img, alt_$lang_code AS alt FROM ".LOGO_SETTINGS_TABLE);
while (!$rs->EOF) {
	$row = $rs->getRowAssoc( false );
	$logo_settings[$row["type"]]["img"] = $row["img"];
	$logo_settings[$row["type"]]["alt"] = $row["alt"];
	$rs->MoveNext();
}
$smarty->assign("logo_settings", $logo_settings);

function AssignHideModeIds(){
	global $dbconn, $smarty, $config;

	foreach ($config["mode_hide_ids"] as $id) {		
		$smarty->assign($id, "none");
	}
}
/**
 * Get an array with elements ids, which will not be shown in templates (use "style="display: none;")
 *
 * @param int $site_mode)
 * @return array
 */
function GetHideModeIds($site_mode) {
	global $dbconn, $smarty, $config;
	$str_sql = "SELECT mi.id_elem FROM ".MODE_IDS_TABLE." mi
				LEFT JOIN ".MODE_HIDE_IDS_TABLE." m ON mi.id=m.id_elem
				WHERE m.id_mode='$site_mode'";
	$rs = $dbconn->Execute($str_sql);
	$ids = array();
	while (!$rs->EOF) {
		$row = $rs->getRowAssoc( false );
		$ids[] = $row["id_elem"];
		$rs->MoveNext();
	}
	return $ids;
}

/**
 * StripSlashes from each value
 *
 * @param array $data_arr
 * @return void
 */
function ArrStripSlashes(&$data_arr) {
	foreach($data_arr as $k => $v) {
		if (is_array($v)) { 
			ArrStripSlashes($v);
		} else {
			$data_arr[$k] = trim(stripslashes($v));	
		}		
	}	
}
?>