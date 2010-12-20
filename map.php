<?php
/**
* Site map
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.3 $ $Date: 2008/10/24 14:18:27 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

include "./include/class.info_manager.php";

$user = auth_index_user();

if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	if ($_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
}

if($user[3] != 1){
	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
} else {
	CreateMenu('index_top_menu');
	CreateMenu('index_user_menu');
}
CreateMenu('lang_menu');
CreateMenu('bottom_menu');

IndexHomePage('map', 'map');

/**
 * Get settings, wich could be switched of from admin mode, their values could be only 1/0
 */
$settings = GetSiteSettings(array("use_sell_lease_payment"));

$admin_hide_ids = array();
foreach ($settings as $set_name => $set_val) {
	if ($set_val == 0) {
		$admin_hide_ids[] = $set_name;
	}
}
if (GetSiteSettings("site_mode") == 2 && $user[3] == 1) {
	/**
	 * if site mode is 2 and user is and user is unregistered, not show some map items to him
	 */
	$admin_hide_ids[] = "hide_from_unreg";
}
if ($user[8] == 1) {
	$admin_hide_ids[] = "user_activate";
} else {
	$admin_hide_ids[] = "user_deactivate";
}
$mode_hide_ids = GetHideModeIds($config["site_mode"]);

$hide_ids = array_merge($admin_hide_ids, $mode_hide_ids);

$map = GetMapContent($hide_ids);

$info_manager = new InfoManager();
$info["top"] = $info_manager->GetSectSubsecContent( $config["default_lang"], "top" , true );
$info["bottom"] = $info_manager->GetSectSubsecContent( $config["default_lang"], "bottom" , true );

$map = AddInfoToMap($map, $info);

if (!isset($include)) {
	$smarty->assign("map", $map);
	$smarty->display(TrimSlash($config["index_theme_path"])."/map.tpl");
}

/**
 * Add info sections to the map array
 *
 * @param array $map
 * @param array $info
 * @return array
 */
function AddInfoToMap($map, $info) {
	$menu_posiotions = array("top", "bottom");

	foreach ($menu_posiotions as $menu_pos) {
		foreach ($map as $map_id=>$map_section) {
			if ($map_section["name"] == "info_$menu_pos") {
				$mi_section = array();
				foreach ($info[$menu_pos] as $info_id=>$info_section) {
					$section = array();
					$section["name"] = $info_section["caption"];
					$section["link"] = "/info.php?id=".$info_section["id"];

					$subsections = array();
					foreach ($info_section["subsections"] as $info_subsection) {
						$subsection = array();
						$subsection["name"] = $info_subsection["caption"];
						$subsection["link"] = "/info.php?id=".$info_section["id"]."&subsection_id=".$info_subsection["id"];
						$subsections[] = $subsection;
					}
					if (count($subsections) > 0) {
						$section["subsection"] = $subsections;
					}
					$mi_section[] = $section;
				}
				/**
				 * insert section to map array
				 */
				if (count($info[$menu_pos]) == 0) {
					$map[$map_id] = $mi_section[0];
				} else {
					$reserve = array();
					foreach ($map as $m_id=>$m_section) {
						if ($m_id > $map_id) {
							$reserve[] = $m_section;
						}
					}
					foreach ($mi_section as $mi_sect) {
						$map[$map_id] = $mi_sect;
						$map_id++;
					}
					foreach ($reserve as $m_reserve) {
						$map[$map_id] = $m_reserve;
						$map_id++;
					}
				}
				break;
			}
		}
	}
	return $map;
}
?>