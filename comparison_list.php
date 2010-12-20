<?php
/**
* AJAX called file for manipulating with users' comparison list
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:23 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_xml.php";
include "./include/functions_auth.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/class.calendar_event.php";

header ("Content-Type: text/html; charset=utf-8");

$lang["default_select"] = GetLangContent("default_select");
$user = auth_index_user();

$action = (isset($_REQUEST["action"]) && !empty($_REQUEST["action"])) ? $_REQUEST["action"] : "";
$id_ad = (isset($_REQUEST["id_ad"]) && !empty($_REQUEST["id_ad"])) ? intval($_REQUEST["id_ad"]) : 0;

if ($action == "") {
	/**
	 * Add listing to users' comparison list
	 */
	if ($id_ad) {

		//for guest user
		$and_str = ($user[3] == 1) ? "AND session='".$user[12]."'" : "";
		$ins_str = ($user[3] == 1) ? ", session='".$user[12]."'" : "";

		$strSQL = "SELECT id FROM ".COMPARISON_LIST_TABLE." ".
				  "WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' $and_str";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->RowCount() == 0) {
			$strSQL = "INSERT INTO ".COMPARISON_LIST_TABLE." SET ".
				  	  "id_user='".$user[0]."', id_ad='".$id_ad."'".$ins_str;
			$rs = $dbconn->Execute($strSQL);
		}
		echo "<b>".$lang["default_select"]["in_your_comparison_list"]."</b>";
	}
} elseif ($action == "get_list") {
	/**
	 * Get users' comparison list
	 */
	$ads = GetUserComparisonIds();
	if (count($ads) == 0) {
		echo "empty";
	} else {
		foreach ($ads as $ad) {
			echo "<div class='comp_list_item'><a href=\"".$config["server"].$config["site_root"]."/viewprofile.php?id=".$ad["id"]."\">".$ad["fname"]."</a><a href='#' onclick=\"DeleteFromComparisonList('comparison_list', ".$ad["id"]."); title='".$lang["default_select"]["delete_from_comparison"]."'\"><img src='".$config["server"].$config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/delete.gif' alt='".$lang["default_select"]["delete_from_comparison"]."' class='comp_list_icon'></a><br>".$lang["default_select"][$ad["type"]]." ".$ad["realty_type"]."</div>";
		}
	}
} elseif ($action == "get_str") {
	/**
	 * Get users' comparison list
	 */
	$ads = GetUserComparisonIds();
	if (count($ads) == 0) {
		echo "empty";
	} else {
		echo "&nbsp;<a href=\"".$config["server"].$config["site_root"]."/compare.php\">".$lang["default_select"]["comparison_list"]." (".count($ads).")</a>&nbsp;&nbsp;<font class='text'>|</font>&nbsp;";
	}
} elseif ($action == "clear_list") {
	//for guest user
	$and_str = ($user[3] == 1) ? "AND session='".$user[12]."'" : "";

	$strSQL = "SELECT id_ad FROM ".COMPARISON_LIST_TABLE." ".
			  "WHERE id_user='".$user[0]."' $and_str";
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$row = $rs->getRowAssoc( false );
		echo GetAddElemCode($row["id_ad"]);
		$rs->MoveNext();
	}
	/**
	 * Clear users' comparison list
	 */
	$strSQL = "DELETE FROM ".COMPARISON_LIST_TABLE." ".
			  "WHERE id_user='".$user[0]."' $and_str";
	$rs = $dbconn->Execute($strSQL);
} elseif ($action == "delete_ad") {
	/**
	 * Delete ad from users' comparison list
	 */
	if ($id_ad) {
		$from_compare = (isset($_REQUEST["par"]) && $_REQUEST["par"] == "from_compare") ? true : false;

		if (!$from_compare) {
			echo GetAddElemCode($id_ad);
		}

		//for guest user
		$and_str = ($user[3] == 1) ? "AND session='".$user[12]."'" : "";

		$strSQL = "DELETE FROM ".COMPARISON_LIST_TABLE." ".
				  "WHERE id_user='".$user[0]."' AND id_ad='$id_ad' $and_str";
		$rs = $dbconn->Execute($strSQL);
		if ($from_compare) {
			header("Location: ".$config["server"].$config["site_root"]."/compare.php");
		}
	}
}

function GetAddElemCode($id_ad) {
	global $lang;

	return "if (document.getElementById('listing_add_to_comparison_".$id_ad."')) document.getElementById('listing_add_to_comparison_".$id_ad."').innerHTML=\"<a href='#' onclick=\\\"javascript: AddToComparisonList('".$id_ad."', 'listing_add_to_comparison_".$id_ad."');\\\">".$lang["default_select"]["add_to_comparison_list"]."</a>\";";
}

?>