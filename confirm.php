<?php
/**
* Users registration confirmation page
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:23 $
**/

include "./include/config.php";
include "./common.php";

include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

CreateMenu('index_top_menu');
CreateMenu('lang_menu');
CreateMenu('bottom_menu');

IndexHomePage("confirm");

$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"confirm.php";

$id = $_POST["id"]?$_POST["id"]:$_GET["id"];
$id = intval($id);
$mail = $_POST["mail"]?$_POST["mail"]:$_GET["mail"];
if ((!$id)||(!$mail))
	GetErrors("error_not_user");
else {
	$rs = $dbconn->Execute("SELECT confirm,email FROM ".USERS_TABLE." WHERE id=".$id);
	if (!($rs->RecordCount()))
		GetErrors("error_not_user");
	else {
		if ($rs->fields[0])
			GetErrors("error_confirm_user");
		else {
			if ($mail!=md5($rs->fields[1])) {
				GetErrors("email_not_eq");
			}
			else {
				$rs = $dbconn->Execute("UPDATE ".USERS_TABLE." SET confirm='1', status='1' WHERE id=".$id);
				sess_write(session_id(), $id);
				$user = auth_index_user();
				echo "<script>location.href=\"".$config["server"].$config["site_root"]."/homepage.php?sel=after_confirm\"</script>";				
			}
		}
		
	}
}

$smarty->display(TrimSlash($config["index_theme_path"])."/confirm_table.tpl");

?>