<?php
/**
* Badwords managing
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 13:06:00 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";

include "../include/functions_xml.php";

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

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$file_path = "../include/badwords.txt";

switch($sel){
	case "save": SaveToFile();
	default: BadWordsMain();
}

function BadWordsMain(){
	global $smarty, $dbconn, $config, $file_path;
	$story = array();
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_badwords.php";

	IndexAdminPage('admin_badwords');
	CreateMenu('admin_lang_menu');


	if(file_exists($file_path) && is_readable($file_path)){
		$data["content"] = implode("", file($file_path));
	}else{
		GetErrors('cant_read_file');
	}

	$smarty->assign("data", $data);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_badwords_table.tpl");
	exit;
}

function SaveToFile() {
	global $smarty, $dbconn, $config, $file_path;

	$content = $_POST["content"];

	$content = stripslashes($content);
	$content = ereg_replace("\n", "", $content);

	if(file_exists($file_path) && is_writeable($file_path)){
		$fp = fopen ($file_path, "w");
		fputs($fp, $content);
		fclose( $fp );
	}else{
		GetErrors('cant_write_file');
	}

	BadWordsMain();
	return;
}

?>