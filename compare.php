<?php
/**
* Compare users' comparison list of ads
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:23 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/class.lang.php";
include "./include/class.calendar_event.php";

$multi_lang = new MultiLang($config, $dbconn);

$user = auth_index_user();

IndexHomePage('viewprofile', 'compare');

$ids = GetUserComparisonIds(false);
if (count($ids) == 0) {
	/**
	 * if view from admin - open page without any cimparison - show template for admin
	 */
	if (!(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
		if ($user[3] == 1) {
			echo "<script>location.href='".$config["site_root"]."/index.php'</script>";
		} else {	
			echo "<script>location.href='".$config["site_root"]."/homepage.php'</script>";
		}	
	}
} else {
	foreach ($ids as $ad) {
		$id_ad[] = $ad["id"];
	}

	$ads = Ad($id_ad, $user[0], "", "", true);
	
	$smarty->assign("listings", $ads);

	$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			if ($arr["spr_match_table"] != "") {
				/**
				 * human description
				 */
				$ref[$arr["key"]."_match"] = GetSprArrName($arr["spr_table"], 2);
			}
			$ref[$arr["key"]] = GetSprArrName($arr["spr_table"]);
		}
	}
	$smarty->assign("ref_names", $ref);
}

$smarty->assign("is_compare", 1);
$smarty->display(TrimSlash($config["index_theme_path"])."/compare.tpl");
?>