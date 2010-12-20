<?php
/**
* Payed services page
*
* @package RealEstate
* @subpackage User Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.18 $ $Date: 2008/12/23 11:03:33 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_common.php";
if (in_array("mhi_services", $config["mode_hide_ids"])) {		
	HidePage();
	exit;
}
include "./include/Snoopy.class.php";
include "./include/functions_index.php";
include "./include/functions_auth.php";
include "./include/class.gifmerge.php";
include "./include/functions_xml.php";
if (GetSiteSettings("use_pilot_module_newsletter")) {
	include "./include/functions_newsletter.php";
}
include "./include/class.lang.php";
include "./include/functions_mail.php";
include "./include/class.calendar_event.php";

$user = auth_index_user();
$cur = GetSiteSettings('site_unit_costunit');
$smarty->assign("cur", $cur);
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );

$multi_lang = new MultiLang($config, $dbconn);

if ($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)) {
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
		AlertPage();
		exit;
	} elseif ($mode == 0) {
		AlertPage(GetRightModulePath(__FILE__));
		exit;
	}
	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	$mes = (isset($_REQUEST["mes"]) && !empty($_REQUEST["mes"])) ? $_REQUEST["mes"] : "";
	switch ($sel) {
		//paid services
		case "top_search":			ServicesList("top_search");	break;
		case "slideshow":			ServicesList("slideshow", $mes);	break;
		case "featured":			ServicesList("featured"); break;
		case "group":				ServicesList("group"); break;
		case "sell_lease":			ServicesList("sell_lease"); break;		
		case "sms_notifications":	ServicesList("sms_notifications");break;

				
		case "all_banners":			ServicesList("all_banners"); break;
		case "add_banner":			ServicesList("add_banner");break;
		case "statistics":			ServicesList("statistics");break;
		case "save_properties":		SaveBanner();break;
		case "cost_banner":			ServicesList("cost_banner");break;
		case "activating_banner":	ActivateBanner();break;
		case "delete":				DeleteBanner();break;

		case "trial_membership":    TrialMembersip(); break;
		//bids form for the Region Leader service
		case "feature_ad" :			FeatureAdForm(); break;

		//withdraw from account
		case "top_search_ad" :		TopSearchAd(); break;
		case "slideshow_ad" :		SlideShow(); break;
		case "get_featured" :		MakeAdFeatured(); break;
		case "group_payment":		GroupSet(); break;
		case "sell_lease_payment": 	SellLeasePayment(); break;

		//payments
		case "payment_form":		PaymentForm();	break;
		case "add_to_account":		AddToAccount(); break;

		case "payment_between_user":PaymentBtwUser(); break;
		case "transfer_to_user":	TransferToUser(); break;

		//payment history
		case "payment_history":		PaymentHistory();	break;
		case "view_request":		PaymentHistory("view");	break;
		
		case "buy_sms":				BuySmsNotifications();break;
		case "subscribe":			SubscribeToSmsNotifications();break;


 		default:					ServicesList();	break;
	}
}

function ServicesList($par = "", $err = '') {
	global $config, $smarty, $dbconn, $user, $lang;

	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";
	
	IndexHomePage('services', 'homepage');

	CreateMenu('rental_menu');
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	//CreateMenu('account_menu');
	$smarty->assign("submenu", "services");

	if (isset($_GET["response"])) {
		if ($_GET["response"] == 'yes') {
			$err = "success_payment";
		} elseif ($_GET["response"] == 'no') {
			$err = "fail_payment";
		}
	}
	
	if ($err) {
		GetErrors($err);
	}

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	switch ($par) {
		case "top_search":
			$param = $file_name."?sel=top_search&amp;";
			if  (!isset($_GET["par"]) || ($_GET["par"] != 'search_ad_top') || (!isset($_POST["ad_num"])) || (intval($_POST["ad_num"]) < 1)) {
				$ads = GetUserAds($file_name, $param);
			} else {
				$ad_num = intval($_POST["ad_num"]);
				$smarty->assign("ad_num", $ad_num);
				$ads = GetUserAds($file_name, $param, $ad_num);
			}

			if (count($ads) > 0 ) {
				$smarty->assign("ads",$ads);
			} else {
				$smarty->assign("empty",'1');
			}
			$smarty->assign("service_cost", GetSiteSettings("top_search_cost"));
			break;
		case "slideshow":
			$param = $file_name."?sel=slideshow&amp;";
			if  (($_GET["par"]!='search_ad') || (intval($_POST["ad_num"])<1)) {
				$ads = GetUserAds($file_name, $param);
			} else {
				$ad_num = intval($_POST["ad_num"]);
				$smarty->assign("ad_num", $ad_num);
				$ads = GetUserAds($file_name, $param, $ad_num);
			}

			if (count($ads) > 0 ) {
				$smarty->assign("ads",$ads);
			} else {
				$smarty->assign("empty",'1');
			}
			$smarty->assign("service_cost", GetSiteSettings("slideshow_cost"));
			$smarty->assign("service_period", GetSiteSettings("slideshow_period"));
			break;
		case "featured":
			$param = $file_name."?sel=featured&amp;";

			if ((isset($_GET["par"]) && $_GET["par"]!='search_ad_featured') || (isset($_POST["ad_num"]) && intval($_POST["ad_num"]) < 1)) {
				$ads = GetUserAds($file_name, $param);
			} else {
				$ad_num = isset($_POST["ad_num"]) ? intval($_POST["ad_num"]) : 0;
				$smarty->assign("ad_num", $ad_num);
				$ads = GetUserAds($file_name, $param, $ad_num);
			}

			if (count($ads) > 0 ) {
				$smarty->assign("ads",$ads);
			} else {
				$smarty->assign("empty",'1');
			}
			break;
		case "sell_lease":
				$smarty->assign("sell_lease", GetSellLeaseSettings());
				$smarty->assign("user_sell_lease", GetSellLeaseUserPayment());
			break;
		case "group":
			$lang["pays"] = GetLangContent('admin/admin_billing');
			$lang["groups"] = GetLangContent('groups');
			$lang["modules"] = GetLangContent('modules');

			$data["account_currency"] = GetSiteSettings("site_unit_costunit");
			$selected_group = isset($_REQUEST["group"]) ? intval($_REQUEST["group"]) : 0;			

			/**
			 * Get users current billing account
			 */
			$rs = $dbconn->Execute("SELECT account_curr from ".BILLING_USER_ACCOUNT_TABLE." where id_user='".$user[0]."'");
			$row = $rs->GetRowAssoc(false);
			$data["count"] = round($row["account_curr"],2);
			/**
			 * Get users current group
			 */
			$strSQL = "SELECT a.id, a.speed ".
					  "FROM ".GROUPS_TABLE." a, ".USER_GROUP_TABLE." b ".
					  "WHERE a.id=b.id_group AND b.id_user='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->getRowAssoc( false );
			$user_group = $row["id"];
			$data["present_group_id"] = $row["id"];
			$data["present_group"] = $lang["groups"][$row["id"]];
			$data["selected_name"] = $lang["groups"][$row["id"]];
			$data["speed"] = $row["speed"];
			
			/**
			 * Get proprties of the selected group
			 */
			if ($selected_group) {
				$strSQL = "SELECT allow_trial, trial_period FROM ".GROUPS_TABLE." ".
						  "WHERE id='$selected_group'";
	  			$rs = $dbconn->Execute($strSQL);
				$row = $rs->getRowAssoc( false );
				$data["allow_trial"] = $row["allow_trial"];
				$data["trial_period"] = $row["trial_period"];
	
				$data["left"] = GetPeriodRest();
			}	

			/**
			 * Get groups array
			 */
			//Paid groups
			$strSQL = "SELECT a.id FROM ".GROUPS_TABLE." as a, ".GROUP_PERIOD_TABLE." as b ".
					  "WHERE a.type='f' AND b.id_group=a.id AND b.status='1' ".
					  "GROUP BY a.id";
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$groups[$i]["name"] = $lang["groups"][$row["id"]];
				$groups[$i]["id"] = $row["id"];
				$groups[$i]["sel"] = $row["id"]==$selected_group?"1":"";
				if ($row["id"]==$selected_group) {
					$data["selected_name"] = $lang["groups"][$row["id"]];
					$data["selected_group_id"] = $selected_group;
				}
				$rs->MoveNext(); $i++;
			}
			//Free group (it can be only one, all groups, added from admin, are paid by default)
			$strSQL = "SELECT id FROM ".GROUPS_TABLE."
					   WHERE type='d'";
			$rs = $dbconn->Execute($strSQL);
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$groups[$i]["name"] = $lang["groups"][$row["id"]];
				$groups[$i]["id"] = $row["id"];
				$groups[$i]["sel"] = ($row["id"] == $selected_group) ? "1" : "";
				if ($row["id"]==$selected_group) {
					$data["selected_name"] = $lang["groups"][$row["id"]];
					$data["selected_group_id"] = $selected_group;
				}
				$rs->MoveNext(); $i++;
			}
			$smarty->assign("groups", $groups);
			/**
			 * get settings of selected group in comparing with settings of the current users' group
			 */
			if ($user_group != $selected_group && $selected_group) {
				/**
				 * Get selected group perms
				 */
				$strSQL = "	SELECT id_module FROM ".GROUP_MODULE_TABLE."
		           			WHERE id_group='".$selected_group."'";
				$rs = $dbconn->Execute($strSQL);
				$selected_group_perms = array();
				while(!$rs->EOF) {
					$selected_group_perms[] = $rs->fields[0];
					$rs->MoveNext();
				}
				/**
				 * Get default group perms
				 */
				$strSQL = "	SELECT id_module FROM ".GROUP_MODULE_TABLE."
		           			WHERE id_group='".$user_group."'";
				$rs = $dbconn->Execute($strSQL);
				$user_group_perms = array();
				while(!$rs->EOF) {
					$user_group_perms[] = $rs->fields[0];
					$rs->MoveNext();
				}
				/**
				 * news available features
				 */
				$diff_new = array_diff($selected_group_perms, $user_group_perms);
				/**
				 * old features, which will not be available
				 */
				$diff_old = array_diff($user_group_perms, $selected_group_perms);

				$strSQL = "	SELECT file
		           			FROM ".MODULE_FILE_TABLE."
		           			WHERE id IN ('".implode("','", $diff_new)."')";
				$rs = $dbconn->Execute($strSQL);
				$i = 0;
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$descr_new[$i]["name"] = $lang["modules"][$row["file"]];
					$descr_new[$i]["descr"] = $lang["modules"]['descr_'.$row["file"]];
					$rs->MoveNext();
					$i++;
				}
				if (isset($descr_new)){
					$smarty->assign("descr_new", $descr_new);
				}				

				$strSQL = "SELECT file FROM ".MODULE_FILE_TABLE." ".
		           		  "WHERE id IN ('".implode("','", $diff_old)."')";
				$rs = $dbconn->Execute($strSQL);
				$i = 0;
				$descr_old = array();
				while (!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$descr_old[$i]["name"] = $lang["modules"][$row["file"]];
					$descr_old[$i]["descr"] = $lang["modules"]['descr_'.$row["file"]];
					$rs->MoveNext();
					$i++;
				}
				$smarty->assign("descr_old", $descr_old);
			}

			/**
			 * Get group permissions
			 */
			
			if ($selected_group) {

				$j = 0;
				$cost_group = array();
				$strSQL = "SELECT id, cost, period, amount FROM ".GROUP_PERIOD_TABLE." ".
					      "WHERE id_group='".$selected_group."' AND status='1' ORDER BY cost";
				$rs_p = $dbconn->Execute($strSQL);
				while(!$rs_p->EOF) {
					$row_p = $rs_p->GetRowAssoc(false);
					$cost_group[$j]["id"] = $row_p["id"];
					$cost_group[$j]["amount"] = $row_p["amount"];
					$cost_group[$j]["period"] = $lang["pays"]["periods_".$row_p['period']];
					$cost_group[$j]["cost"] = $row_p["cost"];
					$rs_p->MoveNext();
					$j++;
				}
				$smarty->assign("cost_group", $cost_group);
			}
			/**
			 * Get used trials period
			 */
			$strSQL = "SELECT id FROM ".GROUP_TRIAL_USER_PERIOD_TABLE." ".
		           	  "WHERE id_user='".$user[0]."' AND id_group='$selected_group'";
			$rs = $dbconn->Execute($strSQL);
			$trial_was_used = ($rs->RowCount() > 0) ? 1 : 0;
			$smarty->assign("trial_was_used", $trial_was_used);

			$form["action"] = $file_name;
			$form["hiddens"] = "<input type=hidden name=sel value=group_payment>";
			$form["hiddens"] .= "<input type=hidden name=period_id value=\"\">";
			$form["err"] = $err;
			$smarty->assign("selected_group", $selected_group);
			$smarty->assign("page", $page);
			$smarty->assign("form", $form);
			$smarty->assign("data", $data);
			break;
		case "all_banners":		
			$position = array("0" => "left", "1" => "bottom", "2" => "center");
			$upload_virtual_path = $config["server"].$config["site_root"]."/uploades/adcomps/";
			$upload_physical_path = $config["site_path"]."/uploades/adcomps/";
			$lang["banners"] = GetLangContent('admin/admin_banners');
			$smarty->assign("admin_template_root", $config["admin_theme_path"]);
			
			/**
			 * Sorter & Order
			 */
					
			$order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 2;
			$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 0;
					
			switch ($order) {
				case "1":
					$order_str = " ASC";
					$order_new = 2;
					$order_icon = "&darr;";
					break;
				default:
					$order_str = " DESC";
					$order_new = 1;
					$order_icon = "&uarr;";
					break;
			}
			$smarty->assign("order_icon", $order_icon);
		
			$sorter_str = "  ORDER BY ";
			switch($sorter) {
				case 0	: $sorter_str.= "a.name $order_str"; break;
				case 1	: $sorter_str.= "a.payment_status $order_str"; break;
				case 2	: $sorter_str.= "a.position $order_str"; break;
				case 3	: $sorter_str.= "a.stop_after_date_num $order_str"; break;								
			}
			$smarty->assign("sorter", $sorter);
			$sort_order_link = "$file_name?sel=all_banners&order=$order_new&sorter=";

			$smarty->assign("sort_order_link", $sort_order_link);		
			$strSQL = "SELECT a.*, DATE_FORMAT(a.stop_after_date_num, '".$config["date_format"]."') AS stop_date, ".
					  "c.size_x, c.size_y, c.able_place AS place, s.hits, s.views FROM ".BANNERS_TABLE." a ".
					  "LEFT JOIN ".BANNERS_SIZES_TABLE." c ON a.size_id=c.id ".
					  "LEFT JOIN ".BANNERS_TEMP_STATISTICS_TABLE." s ON s.banner_id=a.id WHERE a.id_user='".$user[0]."'".
					  $sorter_str;
					
			$rs = $dbconn->Execute($strSQL);
		
			$all_banners = array();
			while (!$rs->EOF) {
				$banner = array();
				$row = $rs->GetRowAssoc(false);
				$banner = $row;
				$banner["position_name"] = $position[$banner["position"]];
				/**
				 * resizing
				 */
				if ($banner["file_path"] != "") {
					$sizes = ResizeImage($row["size_x"], $row["size_y"]);
			        $banner["show_size_x"] = $sizes["width"];
			        $banner["show_size_y"] = $sizes["height"];
				}
				/**
				 * check if banner is stoped
				 */
				$banner["stoped_by_views"] = ($banner["stop_after_views"] && $banner["stop_after_views_num"] <$banner["views"]) ? 1 : 0;
				$banner["stoped_by_hits"] = ($banner["stop_after_hits"] && $banner["stop_after_hits_num"] <$banner["hits"]) ? 1 : 0;
				if ($banner["stop_after_date"] && ($banner["stop_after_date_num"] != "0000-00-00") && (strtotime($banner["stop_after_date_num"])<time()) ) {
					$banner["stoped_by_date"] = 1;
				} else {
					$banner["stoped_by_date"] = 0;
				}
		
				$rs->MoveNext();
				$all_banners[]=$banner;
			}
		
			$smarty->assign("rotate", GetRotateSettings());
		
			$smarty->assign('lang',$lang);
			$smarty->assign('all_banners',$all_banners);			
			
			break;
		case "add_banner":
			
			$position = array("0" => "left", "1" => "bottom", "2" => "center");
			$upload_virtual_path = $config["server"].$config["site_root"]."/uploades/adcomps/";
			$upload_physical_path = $config["site_path"]."/uploades/adcomps/";
			$lang["banners"] = GetLangContent('admin/admin_banners');
			$smarty->assign("admin_template_root", $config["admin_theme_path"]);
			
			if (isset($_REQUEST["error"]) && !empty($_REQUEST["error"])) { 				
				foreach ($_REQUEST["error"] as $err) {
					$errors[] = $err;	
				}				
				$smarty->assign("errors", $errors);
			}	
			
			$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : false;								
			if (!$id) {
				$smarty->assign("is_add_mode", 1);
			}
			if ($id) {
			/**
			 * Get Banner properties for editing
			 */
				$strSQL = "SELECT * ".
		  				  "FROM ".BANNERS_TABLE." WHERE id='$id'";
		  		$rs = $dbconn->Execute($strSQL);
		  		$banner = $rs->getRowAssoc( false );		  		
			} else {
				$banner["position"] = 0;
				$banner["stop_after_views"] = 0;
				$banner["stop_after_hits"] = 0;
				$banner["stop_after_date"] = 0;
				$banner["stop_after_date_num"] = "0000-00-00";
				$banner["open_in_new_window"] = 1;
				$banner["status"] = 1;
				$banner["type"] = "image";
			}
			/**
			 * Banners possible position and sizes
			 */
			$position_sizes = GetPositionsSizes();
			
			$smarty->assign("position_sizes", $position_sizes );
		
			$smarty->assign("banner_position", GetBannerPosition($position));
		/**
		 * End base settings
		 */
			//$date = GetBannerDate($banner["stop_after_date_num"]);
		
			//$smarty->assign("day", GetDaySelect($date["day"]));
			//$smarty->assign("month", GetMonthSelect($date["month"]));
			//$smarty->assign("year", GetYearSelect($date["year"], 3, (intval(date("Y")+2))));
		
			$form["hiddens"] = array();
			if ($id) {
				$form["hiddens"][] = array("name" => "id",
											"value" => $id);
			}
			$form["hiddens"][] = array("name" => "sel",
										"value" => "save_properties");
			$form["hiddens"][] = array("name" => "user_id",
										"value" => $user[0]);							
			$smarty->assign("form", $form);
		
			$smarty->assign("banner", $banner);
			$smarty->assign("id", $id);
		
			$smarty->assign("file_name", $file_name);
			$smarty->assign("action", "properties");
			$smarty->assign("lang", $lang);
			break;	
		case "cost_banner":
			$lang["banners"] = GetLangContent('admin/admin_banners');
			$banner_id = intval($_REQUEST["id"]);
			
			if (isset($_REQUEST["mode"])) {
				$mode = addslashes($_REQUEST["mode"]);
			}else{
				$mode = "activate";
			}
			if ($mode == "view") {
				$strSQL = "SELECT area_id, register_part, unregister_part FROM ".BANNERS_BELONGS_AREA_TABLE." WHERE banner_id='$banner_id'";
			  	$rs = $dbconn->Execute($strSQL);
			  	while (!$rs->EOF) {
			  		$row = $rs->getRowAssoc(false);
			  		
			  		if ($row["register_part"]) {
			  			$banner_areas_reg[$row["area_id"]]["register_part"] = 1;		  				
		  				$banner_areas_reg[$row["area_id"]]["cost_register_part"] = GetCostForBanner($row["area_id"], "reg");
		  				$banner_areas_reg[$row["area_id"]]["file_name"] = GetFileNameForBanner($row["area_id"]);
			  		}
			  		if ($row["unregister_part"]) {
			  			$banner_areas_unreg[$row["area_id"]]["unregister_part"] = 1;		  				
		  				$banner_areas_unreg[$row["area_id"]]["cost_unregister_part"] = GetCostForBanner($row["area_id"], "unreg");
		  				$banner_areas_unreg[$row["area_id"]]["file_name"] = GetFileNameForBanner($row["area_id"]);
			  		}
			  		
			  		$rs->MoveNext();
			  	}
			}else{
			
				$strSQL = "SELECT resolved_places FROM ".BANNERS_TABLE." WHERE id = '$banner_id'";
				$rs = $dbconn->Execute($strSQL);
				$all_areas = explode(",", $rs->fields[0]);  
				$max_area_id = 0;
		  		foreach ($all_areas AS $one_area) {
		  			list($area_id, $reg_unreg) = explode("_", $one_area);
		  			if ($reg_unreg == 'reg') {
		  				$banner_areas_reg[$area_id]["register_part"] = 1;
		  				
		  				$banner_areas_reg[$area_id]["cost_register_part"] = GetCostForBanner($area_id, "reg");
		  				$banner_areas_reg[$area_id]["file_name"] = GetFileNameForBanner($area_id);
		  			}
		  			if ($reg_unreg == 'unreg') {
		  				$banner_areas_unreg[$area_id]["unregister_part"] = 1;  			
		  				$banner_areas_unreg[$area_id]["cost_unregister_part"] = GetCostForBanner($area_id, "unreg");
		  				$banner_areas_unreg[$area_id]["file_name"] = GetFileNameForBanner($area_id);
		  			}
		  			if ($area_id > $max_area_id) {
		  				$max_area_id = $area_id;
		  			}
		  			
		  		}
			}  		
	  		
	  		$strSQL = "SELECT * FROM ".BANNERS_SETTINGS;
	  		$rs = $dbconn->Execute($strSQL);
	  		while(!$rs->EOF) {
	  			$row = $rs->GetRowAssoc(false);
	  			$banner_settings[$row["name"]] = $row["value"];
	  			$rs->MoveNext();
	  		}
	  		$form["hiddens"][] = array("name" => "sel",
										"value" => "activating_banner");
			$form["hiddens"][] = array("name" => "user_id",
										"value" => $user[0]);
			$form["hiddens"][] = array("name" => "banner_id",
										"value" => $banner_id);
			$smarty->assign("form", $form);
			$smarty->assign("areas_reg", $banner_areas_reg);
			$smarty->assign("areas_unreg", $banner_areas_unreg);
			$smarty->assign("lang", $lang);
			$smarty->assign("mode", $mode);
			if ($mode != "view") {
				$smarty->assign("max_area_id", $max_area_id);
			}
			$smarty->assign("banner_settings", $banner_settings);
			break;	
		case "statistics":
			$lang["banners"] = GetLangContent('admin/admin_banners');
			$id = intval($_REQUEST["id"]);
			
			if (!$id) {
				ServicesList('all_banner');exit();
			}
			$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]) : "admin_banners.php";
				
			$page = (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 1;
			$rows_num_page = (isset($_REQUEST["rows_num_page"]) && !empty($_REQUEST["rows_num_page"])) ? intval($_REQUEST["rows_num_page"]) : GetSiteSettings('admin_rows_per_page');
			$smarty->assign("rows_num_page", $rows_num_page);
			/**
			 * Sorter & Order
			 */
			$period_type = (isset($_REQUEST["period_type"]) && !empty($_REQUEST["period_type"])) ? $_REQUEST["period_type"] : "day";
		
			$order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 2;
			if (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) {
				$sorter = $_REQUEST["sorter"];
			} else {
				$sorter = $period_type;
			}
		
			switch ($order) {
				case "1":
					$order_str = " ASC";
					$order_new = 2;
					$order_icon = "&darr;";
					break;
				default:
					$order_str = " DESC";
					$order_new = 1;
					$order_icon = "&uarr;";
					break;
			}
			$smarty->assign("order_icon", $order_icon);
		
			$sorter_str = "  ORDER BY ";
			switch($sorter) {
				case "day": $sorter_str.=" date $order_str"; break;
				case "week": $sorter_str.=" date $order_str, week $order_str"; break;
				case "month": $sorter_str.=" date $order_str, month $order_str"; break;
				case "year": $sorter_str.=" date $order_str, year $order_str"; break;
				case "hits": $sorter_str.=" hits $order_str"; break;
				case "views": $sorter_str.=" views $order_str"; break;
				case "ctr": $sorter_str.=" ctr $order_str"; break;
			}
			$smarty->assign("sorter", $sorter);
			$sort_order_link = "$file_name?sel=statistics&id=$id&order=$order_new&rows_num_page=$rows_num_page&period_type=$period_type&sorter=";
		
			/**
			 * Pages executing
			 */
			$lim_min = ($page-1)*$rows_num_page;
			$lim_max = $rows_num_page;
			$limit_str = "LIMIT ".$lim_min.", ".$lim_max;
		
			/**
			 * Get statistics
			 */
			if ($period_type == "day") {
				$strSQL = "SELECT COUNT(id) AS cnt FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id'";
				$rs = $dbconn->Execute($strSQL);
				$num_records = $rs->fields[0];
		
				$strSQL = "SELECT SUM(hits) AS hits, SUM(views) AS views, ROUND((SUM(hits)/SUM(views))*100, 4) AS ctr, ".
						  "DATE_FORMAT(date, '".$config["date_format"]."') AS date_format ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ".
						  "GROUP BY date $sorter_str $limit_str";
			} elseif ($period_type == "week") {
				$strSQL = "SELECT COUNT(DISTINCT(week(date, 1))) AS week_cnt ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ";
				$rs = $dbconn->Execute($strSQL);
				$num_records = $rs->fields[0];
		
				$strSQL = "SELECT SUM(hits) AS hits, SUM(views) AS views, ROUND((SUM(hits)/SUM(views))*100, 4) AS ctr, ".
						  "week(date, 1) AS week, month(date) AS month, year(date) as year ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ".
						  "GROUP BY week, month, year $sorter_str $limit_str";
			} elseif ($period_type == "month") {
				$strSQL = "SELECT COUNT(DISTINCT(month(date))) AS month_cnt ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ";
				$rs = $dbconn->Execute($strSQL);
				$num_records = $rs->fields[0];
		
				$strSQL = "SELECT SUM(hits) AS hits, SUM(views) AS views, ROUND((SUM(hits)/SUM(views))*100, 4) AS ctr, ".
						  "month(date) AS month, year(date) as year ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ".
						  "GROUP BY month, year $sorter_str $limit_str";
			} elseif ($period_type == "year") {
				$strSQL = "SELECT COUNT(DISTINCT(year(date))) AS year_cnt ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ";
				$rs = $dbconn->Execute($strSQL);
				$num_records = $rs->fields[0];
		
				$strSQL = "SELECT SUM(hits) AS hits, SUM(views) AS views, ROUND((SUM(hits)/SUM(views))*100, 4) AS ctr, ".
						  "year(date) as year ".
						  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ".
						  "GROUP BY year $sorter_str $limit_str";
			}
			$rs = $dbconn->Execute($strSQL);
			$statistics = array();
		
			while (!$rs->EOF) {
				$statistics[] = $rs->GetRowAssoc( false );
				$rs->MoveNext();
			}
			$smarty->assign("statistics", $statistics);
		
			$smarty->assign("page", $page);
			$param = "$file_name?sel=statistics&id=$id&order=$order&rows_num_page=$rows_num_page&period_type=$period_type&sorter=".$sorter."&";
			$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page) );
		
			/**
			 * Total statistics for all period of banners' existing
			 */
			$strSQL = "SELECT SUM(hits) AS hits, SUM(views) AS views, ROUND((SUM(hits)/SUM(views))*100, 4) AS ctr ".
					  "FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id' ";
			$rs = $dbconn->Execute($strSQL);
			$total = $rs->getRowAssoc( false );
			$smarty->assign("total_stat", $total);
		
			$form["hiddens"] = array();
			$form["hiddens"][] = array("name" => "id",
										"value" => $id);
			$form["hiddens"][] = array("name" => "sel",
										"value" => "statistics");
			$form["hiddens"][] = array("name" => "sorter",
										"value" => $sorter);
			$form["hiddens"][] = array("name" => "order",
										"value" => $order);
			$form["hiddens"][] = array("name" => "period_type",
										"value" => $period_type);
			$smarty->assign("form", $form);
		
			/**
			 * Generate rows per page array
			 */
			$cnt = GetSiteSettings('admin_rows_per_page');
			$max = $cnt+50;
			$rows_per_page = array();
			for ($i=$cnt; $i<$max; $i+=10) {
				$rows_per_page[] = $i;
			}
			$smarty->assign('rows_per_page', $rows_per_page);
		
			$period_arr = array("day", "week", "month", "year");
		
			$smarty->assign("month_name", GetMonth());
		
			$smarty->assign("sort_order_link", $sort_order_link);
			/**
			 * Get Banners type
			 */
			$strSQL = "SELECT type FROM ".BANNERS_TABLE." WHERE id='$id'";
			$rs = $dbconn->Execute($strSQL);
			$banner_type = $rs->fields[0];
		
			$smarty->assign("type", $banner_type);
		
			$smarty->assign("period_arr", $period_arr);
			$smarty->assign("period_type", $period_type);
			$smarty->assign("lang", $lang);
			$smarty->assign('id', $id);
			$smarty->assign('file_name', $file_name);			
					
			break;
		case "sms_notifications":
			if (!GetSiteSettings("use_pilot_module_sms_notifications")){
				ServicesList();
			}
						
			$sms_settings = GetSmsSettings();
			if (!$sms_settings["use"]){
				$smarty->assign("not_turn_on", 1);
				GetErrors("sms_not_turn_on");
			}
			$strSQL = "SELECT sms_balance, phone FROM ".SMS_NOTIFICATIONS_USER_BALANCE." WHERE id_user='{$user[0]}'";
			$rs = $dbconn->Execute($strSQL);
			$sms_balance = 0;
			$phone_number = "";
			if (isset($rs->fields[0])){
				$sms_balance = $rs->fields[0];
				$phone_number = $rs->fields[1];
			}
			
			$strSQL = "SELECT id_subscribe FROM ".SMS_NOTIFICATIONS_USER_EVENT." WHERE id_user='{$user[0]}'";
			$rs = $dbconn->Execute($strSQL);
			while(!$rs->EOF){
				$sms_user_event[$rs->fields[0]] = 1;
				$rs->MoveNext();
			}	
			
			$strSQL = "SELECT id, description FROM ".SMS_NOTIFICATIONS_SUBSCRIBE." WHERE status='1'";
			$rs = $dbconn->Execute($strSQL);
			$sms_cases = array();
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$sms_cases[$row["id"]] = $row["description"];
				if (!isset($sms_user_event[$row["id"]])){
					$sms_user_event[$row["id"]] = 0;
				}
				$rs->MoveNext();
			}	
			
			$strSQL = "SELECT id, sms_packet, cost FROM ".SMS_NOTIFICATIONS_PRICES." ORDER BY sms_packet";
			
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$sms_cost["sms_packet"] = $row["sms_packet"];
				$sms_cost["cost"] = $row["cost"];
				$sms_cost["id"] = $row["id"];
				$sms_costs[$i++] = $sms_cost;
				$rs->MoveNext();
			}	
			$lang["sms_notifications"] = GetLangContent('admin/admin_sms_notifications');
			$form["hiddens"][] = array("name" => "sel",
										"value" => "sms_notifications");
			$form["hiddens"][] = array("name" => "user_id",
										"value" => $user[0]);	
																
			$smarty->assign("form", $form);
			$smarty->assign('sms_cases', $sms_cases);
			$smarty->assign('sms_costs', $sms_costs);
			$smarty->assign('sms_balance', $sms_balance);
			$smarty->assign('phone_number', $phone_number);			
			$smarty->assign('sms_user_event', $sms_user_event);	
			$smarty->assign('sms_settings', $sms_settings);		
			$smarty->assign("lang", $lang);
			break;		


			default:
			break;
	}
	if (!$par) {
		$use_listing_completion_bonus = GetSiteSettings("use_listing_completion_bonus");
		$smarty->assign("use_listing_completion_bonus", $use_listing_completion_bonus);
		if ($use_listing_completion_bonus) {
			$smarty->assign("bonus", GetBonusSettings());
		}

		$smarty->assign("use_sell_lease_payment", GetSiteSettings("use_sell_lease_payment"));
		$smarty->assign("use_pilot_module_banners", GetSiteSettings("use_pilot_module_banners"));	
		$use_pilot_module_sms_notifications = GetSiteSettings("use_pilot_module_sms_notifications");	
		$smarty->assign("use_pilot_module_sms_notifications", $use_pilot_module_sms_notifications);
		if ($use_pilot_module_sms_notifications) {
			$smarty->assign("sms_settings", GetSMSSettings('use'));
		}

	}
	$smarty->assign("par", $par);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/services_list_table.tpl");
	exit;
}

function PaymentForm($err='', $par='') {
	global $config, $smarty, $dbconn, $user, $lang;
	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";

	IndexHomePage('services', 'homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	$smarty->assign("submenu", "services");

	if ($err) {
		GetErrors($err);
	}

	$strSQL = " SELECT fname, sname, phone FROM ".USERS_TABLE." WHERE id='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$data["fname"] = $row["fname"];
	$data["sname"] = $row["sname"];
	$data["fname_sname"] = $data["sname"]." ".$data["fname"];
	$data["phone"] = $row["phone"];

	$strSQL = " SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$data["user_bill"] = round($rs->fields[0],2);
	if ($par) {
		$smarty->assign("par", $par);
	}
	$smarty->assign("data", $data);

	$strSQL = "select template_name, name from ".BILLING_PAYSYSTEMS_TABLE." where used='1'";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF) {
		$paysys[$i]["descr"] = $rs->fields[0];
		$paysys[$i]["name"] =  $rs->fields[1];
		if ($paysys[$i]["name"] == "smscoin") {
			$operators = GetSmsCoinTarification();
			if (!$operators) {
				unset($paysys[$i]);
			} else {
				$smarty->assign("smscoin_operators", $operators);
				$i++;
			}
		}else if ($paysys[$i]["name"] == "manual") {
			$strSQL_2 = "select information from ".BILLING_SYS_."manual where name='manual'";
			$rs_2 = $dbconn->Execute($strSQL_2);
			$payment_information = $rs_2->fields[0];
			$smarty->assign("payment_information", $payment_information);
		} else {
			$i++;
		}
		$rs->MoveNext();
	}
	$paysys_cnt = count($paysys);
	if ($paysys_cnt > 1) {
		$smarty->assign("paysys", $paysys);
	} else {
		$smarty->assign("paysys", $paysys[0]);
	}
	$smarty->assign("paysys_cnt", $paysys_cnt);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/services_payment_form.tpl");
	exit;
}

function AddToAccount() {
	global $config, $smarty, $dbconn, $user, $lang, $cur;

	$details = '';
	$paysys = $_REQUEST["paysys"];
	if ($paysys == "smscoin") {
		$amount = floatval($_REQUEST["smscoin_pay_sum"][$_REQUEST["smscoin_operator"]]);
	} else if($paysys == "manual"){ 
		$amount = floatval($_REQUEST["payment_amount"]);
		$details = strip_tags(addslashes($_REQUEST["payment_data"]));
	} else {
		$amount = floatval($_REQUEST["pay_sum"]);
	}
	if ($amount <= 0 || !$paysys) {
		PaymentForm('empty_fields');
		exit;
	}
	$strSQL =   "INSERT INTO ".BILLING_REQUESTS_TABLE." ".
				"(id_user, count_curr, currency, date_send, status, paysystem, user_info) ".
				"VALUES ('".$user[0]."', '".$amount."', '".$cur."', now(), 'send', '".$paysys."', '".$details."')";
				
	$dbconn->Execute($strSQL);
	
	if($paysys == "manual"){ 		
		
		$lang_id = $config["default_lang"];
		
		$strSQL = "SELECT fname, sname, login, email FROM ".USERS_TABLE." WHERE id='$user[0]'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc( false );
		
		$cont_arr["user_name"] = $row["fname"]." ".$row["sname"];		
		$cont_arr["user_login"] = $row["login"];		
		$cont_arr["user_email"] = $row["email"];
		$cont_arr["site"] = $config["server"].$config["site_root"];	
		$cont_arr["form_data"] = $details;
			
		$cont_arr["amount"] = $amount." ".$cur;
		$cont_arr["date"] = date("j/n/Y");
		
		$site_mail = GetSiteSettings("site_email");	
		
		$strSQL = "SELECT fname, sname, email FROM ".USERS_TABLE." WHERE root_user='1'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc( false );
		
		$cont_arr["admin_email"] = $row["email"];
		$cont_arr["admin_name"] = $row["fname"]." ".$row["sname"];	
		$mail_content = GetMailContentReplace("mail_content_mpayment_admin", $lang_id);//xml	
		$subject = $mail_content["subject"];	
		
		SendMail($cont_arr["admin_email"], $site_mail, $subject, $cont_arr, $mail_content, "mail_mpayment_for_admin","", $cont_arr["admin_name"], $mail_content["site_name"]);
		
		PaymentForm('manual_submit');
		exit;
	}

	$strSQL = " SELECT MAX(id) FROM  ".BILLING_REQUESTS_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);

	$id_service = 0; //пополнение счЄта
	$id_trunzaction =  $rs->fields[0];

	$lang["content"] = GetLangContent("services");
	$product_name = $lang["content"]["add_on_account"];

	$currency = $cur;
	$dopayment_flag = 1;
	$template_name = $paysys;
	include_once "include/systems/functions/".$template_name.".php";

	return;
}


function PaymentHistory($par='', $id_req='') {
	global $config, $smarty, $dbconn, $user, $lang;
	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";

	IndexHomePage('services', 'homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	//CreateMenu('account_menu');
	$smarty->assign("submenu", "services");
	
	$i = 0;
	if ($par == 'view') {
		if ($id_req<1) {
			$id_req = $_GET["id_req"];
		}
		$strSQL = "	SELECT b.id, b.id_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, b.status, b.paysystem, b.user_info
					FROM ".BILLING_REQUESTS_TABLE." b
					WHERE b.id_user='".$user[0]."' AND b.id='".intval($id_req)."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$row = $rs->GetRowAssoc(false);
			$data["id"] = $row["id"];
			$data["id_user"] = $row["id_user"];
			$data["count_curr"] = $row["count_curr"];
			$data["currency"] = $row["currency"];
			$data["status"] = $row["status"];
			$data["paysystem"] = $row["paysystem"];
			$data["date_send"] = $row["date_send"];
			$data["user_info"] = str_replace("!",", ",$row["user_info"]);
			$smarty->assign("par", $par);
			$smarty->assign("data", $data);						
		} else {
			PaymentHistory();
			exit;
		}
	} else {
		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, b.status
					FROM ".BILLING_REQUESTS_TABLE." b
					WHERE b.id_user='".$user[0]."'
					GROUP BY b.id ORDER BY b.date_send DESC  ";
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {

				$row = $rs->GetRowAssoc(false);
				$data[$i]["id"] = $row["id"];
				$data[$i]["id_user"] = $row["id_user"];
				$data[$i]["user_from_name"] = "";
				$data[$i]["count_curr"] = $row["count_curr"];
				$data[$i]["currency"] = $row["currency"];
				$data[$i]["status"] = $row["status"];
				if ( $data[$i]["status"] !='approve' ) {
					$data[$i]["link"] = $file_name."?sel=view_request&amp;id_req=".$data[$i]["id"];
				}
				$data[$i]["date_send"] = $row["date_send"];
				$data[$i]["timestamp"] = $row["timestamp"];
				$rs->MoveNext();
				$i++;
			}
		}

		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.id_from_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, u.fname, u.sname
					FROM ".BILLING_USER_RECIPIENTS_TABLE." b
					LEFT JOIN ".USERS_TABLE." u on u.id = b.id_from_user
					WHERE b.id_user='".$user[0]."'
					ORDER BY b.date_send DESC  ";

		$rs = $dbconn->Execute($strSQL);

		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {

				$row = $rs->GetRowAssoc(false);
				$data[$i]["id"] = $row["id"];
				$data[$i]["id_user"] = $row["id_user"];
				$data[$i]["user_from_name"] = $row["fname"]." ".$row["sname"];
				$data[$i]["count_curr"] = $row["count_curr"];
				$data[$i]["currency"] = $row["currency"];
				$data[$i]["date_send"] = $row["date_send"];
				$data[$i]["timestamp"] = $row["timestamp"];
				$data[$i]["status"] = "none";
				$data[$i]["link"] = "";
				$rs->MoveNext();
				$i++;
			}
		}
		
		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, u.fname, u.sname
					FROM ".BILLING_ADDING_BY_ADMIN_TABLE." b
					LEFT JOIN ".USERS_TABLE." u on u.id = b.id_user
					WHERE b.id_user='".$user[0]."'
					ORDER BY b.date_send DESC ";

		$rs = $dbconn->Execute($strSQL);

		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {

				$row = $rs->GetRowAssoc(false);
				$data[$i]["id"] = $row["id"];
				$data[$i]["id_user"] = $row["id_user"];
				$data[$i]["user_from_name"] = $row["fname"]." ".$row["sname"];
				$data[$i]["count_curr"] = $row["count_curr"];
				$data[$i]["currency"] = $row["currency"];
				$data[$i]["date_send"] = $row["date_send"];
				$data[$i]["timestamp"] = $row["timestamp"];
				$data[$i]["status"] = "by_admin";
				$data[$i]["link"] = "";
				$rs->MoveNext();
				$i++;
			}
		}

		$data = MultiSort($data, "timestamp");
		if ($i > 5) {
			$data[0]["all_req_link"] = 1;
		}
		$smarty->assign("data", $data);

		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.count_curr, b.currency, b.id_service, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp
					FROM ".BILLING_SPENDED_TABLE." b
					WHERE b.id_user='".$user[0]."' AND b.id_service != '6'
					GROUP BY b.id ORDER BY b.date_send DESC  ";
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		if ($rs->fields[0]>0) {
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				//				$spended[$i]["id_user"] = $row["id_user"];
				$spended[$i]["count_curr"] = $row["count_curr"];
				$spended[$i]["currency"] = $row["currency"];
				$spended[$i]["id_service"] = $row["id_service"];
				$spended[$i]["date_send"] = $row["date_send"];
				$spended[$i]["timestamp"] = $row["timestamp"];
				$rs->MoveNext();
				$i++;
			}
		}

		$strSQL = "	SELECT DISTINCT b.id, b.id_user, b.id_from_user, b.count_curr, b.currency, DATE_FORMAT(b.date_send, '".$config["date_format"]."') as date_send, UNIX_TIMESTAMP(b.date_send) AS timestamp, u.fname, u.sname
					FROM ".BILLING_USER_RECIPIENTS_TABLE." b
					LEFT JOIN ".USERS_TABLE." u on u.id = b.id_user
					WHERE b.id_from_user='".$user[0]."'
					ORDER BY b.date_send DESC  ";

		$rs = $dbconn->Execute($strSQL);
		
		if ($rs->fields[0]>0) {
			while (!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$spended[$i]["user_from_name"] = $row["fname"]." ".$row["sname"];
				$spended[$i]["count_curr"] = $row["count_curr"];
				$spended[$i]["currency"] = $row["currency"];
				$spended[$i]["date_send"] = $row["date_send"];
				$spended[$i]["timestamp"] = $row["timestamp"];
				$spended[$i]["id_service"] = 6;
				$rs->MoveNext();
				$i++;
			}
		}
		$spended = MultiSort($spended, "timestamp");
	}
	
	
	if ($i > 5) {
		$spended[0]["all_spend_link"] = 1;
	}
	if (isset($spended)){
		$smarty->assign("spended", $spended);
	}	
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/services_history_table.tpl");
	exit;
}

function SettingsSlideShow() {
	global $config, $smarty, $dbconn, $user, $lang, $cur, $REFERENCES;
	
	$id_ad = intval($_GET["id_ad"]);
	
	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";
	
	IndexHomePage('services', 'homepage');
	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('rental_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	
	$strSQL = 	"SELECT a.id, a.type, DATE_FORMAT(a.movedate, '".$config["date_format"]."' ) as movedate ".
				"FROM ".RENT_ADS_TABLE." a ".
				"WHERE a.id='".$id_ad."' ";
	
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$profile["type"] = $row["type"];
	$profile["id_ad"] = $row["id"];
	
	$photo_folder = GetSiteSettings('photo_folder');
	/**
	 * photo
	 */
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve, status FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND upload_type='f' ORDER BY sequence";
	$rs_img = $dbconn->Execute($strSQL_img);
	$j = 0;
	$profile["photo_approve_total"] = 0;
	if ($rs_img->fields[0]>0){
		while(!$rs_img->EOF){
			$row_img = $rs_img->GetRowAssoc(false);
			$profile["photo_id"][$j] = $row_img["photo_id"];
			$profile["photo_path"][$j] = $row_img["upload_path"];
			$profile["photo_user_comment"][$j] = $row_img["user_comment"];
			$profile["photo_admin_approve"][$j] = $row_img["admin_approve"];
			$profile["photo_approve_total"] += $profile["photo_admin_approve"][$j];
			$profile["photo_status"][$j] = $row_img["status"];

			$path = $config["site_path"].$photo_folder."/".$profile["photo_path"][$j];
			$thumb_path = $config["site_path"].$photo_folder."/thumb_".$profile["photo_path"][$j];

			if(file_exists($path) && strlen($profile["photo_path"][$j])>0){
				$profile["photo_file"][$j] = ".".$photo_folder."/".$profile["photo_path"][$j];
				$profile["photo_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$profile["photo_id"][$j]."&type_upload=f";
				$sizes = getimagesize($path);
				$profile["photo_width"][$j]  = $sizes[0];
				$profile["photo_height"][$j]  = $sizes[1];
			}
			if(file_exists($thumb_path) && strlen($profile["photo_path"][$j])>0)
			$profile["thumb_file"][$j] = ".".$photo_folder."/thumb_".$profile["photo_path"][$j];
			if(!file_exists($path) || !strlen($profile["photo_path"][$j])){
				$profile["photo_file"][$j] = ".".$photo_folder."/".$default_photo;
				$profile["thumb_file"][$j] = $profile["photo_file"][$j];
			}
			$rs_img->MoveNext();
			$j++;
		}
	}
	
	/**
	 *	plan
	 */
	
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve, status FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' ORDER BY sequence";
	$rs_img = $dbconn->Execute($strSQL_img);
	$j = 0;
	$profile["plan_approve_total"] = 0;
	if ($rs_img->fields[0]>0){
		while(!$rs_img->EOF){
				$row_img = $rs_img->GetRowAssoc(false);
				$profile["plan_photo_id"][$j] = $row_img["photo_id"];
				$profile["plan_photo_path"][$j] = $row_img["upload_path"];
				$profile["plan_user_comment"][$j] = $row_img["user_comment"];
				$profile["plan_admin_approve"][$j] = $row_img["admin_approve"];
				$profile["plan_approve_total"] += $profile["plan_admin_approve"][$j];
				$profile["plan_status"][$j] = $row_img["status"];
	
				$path = $config["site_path"].$photo_folder."/".$profile["plan_photo_path"][$j];
				$thumb_path = $config["site_path"].$photo_folder."/thumb_".$profile["plan_photo_path"][$j];
				if(file_exists($path) && strlen($profile["plan_photo_path"][$j])>0){
					$profile["plan_file"][$j] = ".".$photo_folder."/".$profile["plan_photo_path"][$j];
					$profile["plan_view_link"][$j] = "./".$file_name."?sel=plan_view&id_file=".$profile["plan_photo_id"][$j]."&type_upload=f";
					$sizes = getimagesize($path);
					$profile["plan_width"][$j]  = $sizes[0];
					$profile["plan_height"][$j]  = $sizes[1];
				}
				if(file_exists($thumb_path) && strlen($profile["plan_photo_path"][$j])>0)
				$profile["plan_thumb_file"][$j] = ".".$photo_folder."/thumb_".$profile["plan_photo_path"][$j];
				if(!file_exists($path) || !strlen($profile["plan_photo_path"][$j])){
					$profile["plan_file"][$j] = ".".$photo_folder."/".$default_photo;
					$profile["plan_thumb_file"][$j] = $profile["plan_file"][$j];
				}
				$rs_img->MoveNext();
				$j++;
		}
	}
	/* parse checkbox fields */
	$images_arr = array();
	if(!empty($_POST) && $_POST["action"] == "apply") {
		foreach ($_POST as $k => $v) {
			if(preg_match("/^(plan_)*photo_(\d+)$/", $k, $m)) {
					foreach ($profile["{$m[1]}photo_path"] as $k2 => $v2) {
						if($profile["{$m[1]}photo_id"][$k2] == $m[2]) {
							$images_arr[] = $v2;
						}
					}
			}
		}
		
		return $images_arr;
		
	}
	
	$smarty->assign("profile", $profile);
	$smarty->assign("file_name", $file_name);
	$smarty->assign("max_photo", 3);
	$smarty->display(TrimSlash($config["index_theme_path"])."/services_slide_show.tpl");
	
	return false;
	
}

/**
 * выделить объ€вление в поиске id_service = 2 (сделать слайдшоу)
 */
function SlideShow() {
	global $config, $smarty, $dbconn, $user, $lang, $cur,$REFERENCES;
	
	if(($images = SettingsSlideShow()) != false) {
	
		if (isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
		else
		$file_name = "services.php";
		IndexHomePage('services', 'homepage');
		$smarty->assign("file_name", $file_name);
	
		$id_ad = intval($_GET["id_ad"]);
		$type = $_GET["type"];
	
		if ( ($id_ad<1) || (strlen($type)<0) ) {
			ServicesList("slideshow");
			exit;
		}
		$service_cost = GetSiteSettings("slideshow_cost");
		$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."'";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]<$service_cost) {
			ServicesList("slideshow","no_money");
			exit;
		}
		$acc = $rs->fields[0];
		switch ($type) {
			case "rent":
				$table = USERS_RENT_UPLOADS_TABLE;
				$slide_table = RENT_ADS_TABLE;
				break;
			default:
				ServicesList("slideshow");
				exit;
				break;
		}
		//////////////////////
		$res = $dbconn->Execute("SELECT type FROM ".RENT_ADS_TABLE." WHERE id_user=".$user[0]." AND id='".$id_ad."' ");
		$ads_type = $res->fields[0];
	
		$strSQL = " SELECT min_payment, max_payment FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		if ($ads_type == "1" || $ads_type == "3") {
			$data_1["payment"] = PaymentFormat($row["max_payment"]);
		}elseif ($ads_type == "2" || $ads_type == "4") {
			$data_1["payment"] = PaymentFormat($row["min_payment"]);
		}
	
		$used_references = array("realty_type");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$data[$arr["key"]] = SprTableSelect($arr["spr_user_table"], $id_ad, $user[0], $arr["spr_table"]);
				$spr_order = ($arr["key"] == "description") ? "id" : "name";
				$arrq=GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order);
			}
		}
		$flag = 0;
		foreach ($arrq[0]["opt"] as $key=>$value) {
			if (isset($value["sel"])) {
				$flag = 1;
			}
		}
		if ($flag ==1 && $data_1["payment"]>0) {
		/////////////////////
			//$strSQL = " SELECT COUNT(id) FROM ".$table." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' AND status='1' AND admin_approve='1' AND upload_type='f'";
			//$rs = $dbconn->Execute($strSQL);
			/*if ($rs->fields[0]<2) {
				ServicesList("slideshow", "more_photo");
				exit;
			} else {*/
			if (count($images) < 3) {
				//ServicesList("slideshow", "more_photo");
				ServicesList("slideshow");
				exit;
			} else {
				/*$strSQL = "	SELECT upload_path FROM ".$table." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' AND status='1' AND admin_approve='1' AND upload_type='f' ORDER BY sequence ASC";*/
				/*$rs = $dbconn->Execute($strSQL);*/
				$i = 0;
				$profile_arr = array();
				$delay = array();
				$xy = array();
				foreach ($images as $k => $v) {
					$profile[$i]["upload_path"] = $v;
					array_push($profile_arr, GetGifFromImage($profile[$i]["upload_path"], $i+1));
					array_push($delay, 100);//1 second I suppose:)
					array_push($xy, 0);
					$i++;
				}
				/*while(!$rs->EOF) {
					$profile[$i]["upload_path"] = $rs->fields[0];
					array_push($profile_arr, GetGifFromImage($profile[$i]["upload_path"], $i+1));
					array_push($delay, 100);//1 second I suppose:)
					array_push($xy, 0);
					$rs->MoveNext();
					$i++;
				}*/
				$anim = new GifMerge($profile_arr, 255, 255, 255, 0, $delay, $xy, $xy, 'C_FILE');
				$image = $anim->getAnimation();
				$slide_path = "uploades/photo/".$user[0]."_".$id_ad."_slide.gif";
				$file = fopen($slide_path, 'w+');
				fputs($file, $image);
				fclose($file);
				foreach ($profile_arr as $image_to_delete) {
					unlink($image_to_delete);
				}
	
				$date_slided = date("Y-m-d H:i:s", time()+60*60*24*(GetSiteSettings("slideshow_period")));
				$date_spended = date("Y-m-d H:i:s", time());
				$dbconn->Execute("UPDATE ".$slide_table." SET upload_path='".$user[0]."_".$id_ad."_slide.gif"."', date_slided='".$date_slided."' WHERE id='".$id_ad."' AND id_user='".$user[0]."' ");
				$new_acc =round(($acc - $service_cost),2);
				$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='".$new_acc."', date_refresh=now() WHERE id_user='".$user[0]."' ");
	
				$dbconn->Execute("	INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
								VALUES ('".$user[0]."', '1', '".$cur."', '2', '".$date_spended."' ) ");
				ListingActivate($id_ad);
				header("Location: services.php?sel=slideshow&mes=your_ad_was_slideshowed");
				exit;
			}
		}else{
			ServicesList("slideshow","activate_alert");
			exit;
		}
	}
}

/**
 * подн€ть объвление в поиске  id_service = 1
 *
 */
function TopSearchAd() {
	global $config, $smarty, $dbconn, $user, $lang, $cur,$REFERENCES;

	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";
	IndexHomePage('services', 'homepage');
	$smarty->assign("file_name", $file_name);

	$id_ad = intval($_GET["id_ad"]);
	$type = $_GET["type"];

	if ( ($id_ad<1) || (strlen($type)<0) ) {
		ServicesList("top_search");
		exit;
	}

	$service_cost = GetSiteSettings("top_search_cost");
	$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]<$service_cost) {
		ServicesList("top_search","no_money");
		exit;
	}
	$acc = $rs->fields[0];

	switch ($type) {
		case "rent":
			$type = 1;
			break;
		default:
			ServicesList("top_search");
			break;
	}

	//////////////////////
	$res = $dbconn->Execute("SELECT type FROM ".RENT_ADS_TABLE." WHERE id_user=".$user[0]." AND id='".$id_ad."' ");
	$ads_type = $res->fields[0];

	$strSQL = " SELECT min_payment, max_payment FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	if ($ads_type == "1" || $ads_type == "3") {
		$data_1["payment"] = PaymentFormat($row["max_payment"]);
	}elseif ($ads_type == "2" || $ads_type == "4") {
		$data_1["payment"] = PaymentFormat($row["min_payment"]);
	}

	$used_references = array("realty_type");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data[$arr["key"]] = SprTableSelect($arr["spr_user_table"], $id_ad, $user[0], $arr["spr_table"]);
			$spr_order = ($arr["key"] == "description") ? "id" : "name";
			$arrq=GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, '', $spr_order);
		}
	}
	$flag = 0;
	foreach ($arrq[0]["opt"] as $key=>$value) {
		if (isset($value["sel"])) {
			$flag = 1;
		}
	}

	if ($flag ==1 && $data_1["payment"]>0) {
	/////////////////////

		$dbconn->Execute("DELETE FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_user='".$user[0]."' AND id_ad='".$id_ad."' AND type='".$type."' ");
		$timestamp = time();
		$dbconn->Execute("INSERT INTO ".TOP_SEARCH_ADS_TABLE." (id_user, id_ad, type, date_begin, date_end) VALUES ('".$user[0]."', '".$id_ad."','".$type."', '".date('Y-m-d H:i:s', $timestamp)."', '".date('Y-m-d H:i:s', $timestamp+1*24*60*60)."' ) ");

		$new_acc = round(($acc - $service_cost),2);
		$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='".$new_acc."', date_refresh=now() WHERE id_user='".$user[0]."' ");
		$date_spended = date("Y-m-d H:i:s", time());
		$dbconn->Execute("INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
							VALUES ('".$user[0]."', '1', '".$cur."', '1', '".$date_spended."' ) ");
		ListingActivate($id_ad);
		ServicesList("top_search","your_ad_was_topsearched");
	}else{
		ServicesList("top_search","activate_alert");
		exit;
	}
}

function FeatureAdForm($err='', $from='', $id_ad='') {
	global $config, $smarty, $dbconn, $user, $lang, $cur,$REFERENCES;
	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";
	IndexHomePage('services', 'homepage');
	$smarty->assign("file_name", $file_name);

	if ($err) {
		GetErrors($err);
	}
	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');

	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$smarty->assign("submenu", "services");

	if ( $from==="1") {
		$id_ad = $id_ad;
		$type = $type;
	} else {
		$id_ad = intval($_GET["id_ad"]);
		$type = $_GET["type"];
	}
	$smarty->assign("type", $type);
	$smarty->assign("id_ad", $id_ad);

	if ( ($id_ad<1) || (strlen($type)<0) ) {
		ServicesList("featured", "unknown_error");
		exit;
	}

	//////////////////////
	$res = $dbconn->Execute("SELECT type FROM ".RENT_ADS_TABLE." WHERE id_user=".$user[0]." AND id='".$id_ad."' ");
	$ads_type = $res->fields[0];

	$strSQL = " SELECT min_payment, max_payment FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	if ($ads_type == "1" || $ads_type == "3") {
		$data_1["payment"] = PaymentFormat($row["max_payment"]);
	}elseif ($ads_type == "2" || $ads_type == "4") {
		$data_1["payment"] = PaymentFormat($row["min_payment"]);
	}

	$used_references = array("realty_type");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$data[$arr["key"]] = SprTableSelect($arr["spr_user_table"], $id_ad, $user[0], $arr["spr_table"]);
			$spr_order = ($arr["key"] == "description") ? "id" : "name";
			$arrq=GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], $data, $lang_add, $spr_order);
		}
	}
	$flag = 0;
	foreach ($arrq[0]["opt"] as $key=>$value) {
		if (isset($value["sel"])) {
			$flag = 1;
		}
	}
	if ($flag ==1 && $data_1["payment"]>0) {
	/////////////////////

		$strSQL = "	SELECT id, upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' and upload_type='f' AND status='1' AND admin_approve='1'";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]<1) {
			ServicesList("featured", "no_photo_for_feature");
			exit;
		}
		$strSQL = " SELECT id_region FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_ad='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		$id_region = $rs->fields[0];
		$smarty->assign("id_region", $id_region);

		//get leaders in choosen ad's region
		$smarty->assign("featured_rent", GetFeaturedAd($id_region));

		$rs = $dbconn->Execute("SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."'");
		if ($rs->fields[0]<1) {
			ServicesList("featured", "no_money");
			exit;
		} else {
			$smarty->assign("on_account", $rs->fields[0]);
		}
		$smarty->assign("par", "featured_form");
		$smarty->assign("featured_in_region_cost", GetSiteSettings("featured_in_region_cost"));
		$smarty->assign("featured_in_region_period", GetSiteSettings("featured_in_region_period"));

		$smarty->display(TrimSlash($config["index_theme_path"])."/services_list_table.tpl");
		exit;
	}else{
		ServicesList("featured","activate_alert");
		exit;
	}
}

/**
 * сделать объ€вление лидером региона id_service = 3
 */
function MakeAdFeatured() {
	global $config, $smarty, $dbconn, $user, $lang, $cur;

	$id_ad = intval($_POST["id_ad"]);
	$id_region = $_POST["id_region"];
	$headline = strip_tags(trim($_POST["feature_headline"]));
	$value = floatval($_POST["curr_value"]);
	$type = $_REQUEST["type"];
	if ( $id_ad<1 ) {
		ServicesList("featured", "unknown_error");
		exit;
	}
	if (strlen($headline)<1) {
		FeatureAdForm("no_headline","1", $id_ad);
		exit;
	}
	if (BadWordsCont($headline)) {
		FeatureAdForm("badword","1", $id_ad);
		exit;
	}

	$strSQL = " SELECT curr_count, upload_path FROM ".FEATURED_TABLE." WHERE id_region='".$id_region."' AND type='1'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$current_count = $rs->fields[0];
		$old_photo_name = $rs->fields[1];
	} else {
		$current_count = 0;
		$old_photo_name = "";
	}
	if ($value <= $current_count) {
		FeatureAdForm("small_curr","1", $id_ad, $type);
		exit;
	}
	$strSQL = "	SELECT id, upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND id_user='".$user[0]."' AND upload_type='f' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]<1) {
		ServicesList("featured", "no_photo_for_feature");
		exit;
	} else {
		$photo_name = $rs->fields[1];
		$strSQL = " SELECT account FROM  ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
		$money = $dbconn->Execute($strSQL);
		if ($money->fields[0]<$value) {
			FeatureAdForm("bill_is_small","1", $id_ad, $type);
			exit;
		}
		$new_money = round(($money->fields[0]-$value),2);
		$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='".$new_money."', date_refresh=now() WHERE id_user='".$user[0]."' ");
		//copy user's thumb to special folder 'featured'
		copy($config["site_path"]."/uploades/photo/thumb_".$photo_name, $config["site_path"]."/uploades/featured/thumb_".$photo_name);
		//unlink old featured photo
		unlink($config["site_path"]."/uploades/featured/thumb_".$old_photo_name);

		$strSQL = " SELECT id FROM ".FEATURED_TABLE." WHERE id_region='".$id_region."' AND type='1' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$strSQL = " UPDATE ".FEATURED_TABLE."
						SET id_user='".$user[0]."', id_ad='".$id_ad."',  headline='".addslashes($headline)."',
							date_featured=now(), curr_count='".$value."', datenow=now(), upload_path='".$photo_name."'
						WHERE id_region='".$id_region."' AND type='1' ";
			$dbconn->Execute($strSQL);
		} else {
			$strSQL = " INSERT INTO ".FEATURED_TABLE." (id_user, id_ad, headline, date_featured, curr_count, datenow, upload_path, id_region, type)
						VALUES ('".$user[0]."', '".$id_ad."', '".addslashes($headline)."', now(), '".$value."', now(), '".$photo_name."', '".$id_region."', '1')
						";
			$dbconn->Execute($strSQL);
		}

		$date_spended = date("Y-m-d H:i:s", time());
		$dbconn->Execute("	INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
							VALUES ('".$user[0]."', '".$value."', '".$cur."', '3', '".$date_spended."' ) ");
		ListingActivate($id_ad);
		ServicesList("featured", "you_was_featured");
		exit;
	}
}

function GetGifFromImage ($name, $i) {
	global $user;
	$path = "uploades/photo/thumb_".$name;
	$new_path = "uploades/slideshow/".$user[0]."_".$i."_temp.gif";
	$image_info = GetImageSize($path);
	$image_type = $image_info[2];

	switch($image_type) {
		case "1" :
			$srcImage = @ImageCreateFromGif ($path);
			break;	/// GIF
		case "2" :
			$srcImage = @imagecreatefromjpeg($path);
			break;	/// JPG
		case "3" :
			$srcImage = @imagecreatefrompng($path);
			break;	/// PNG
		case "6" :
			$srcImage = @imagecreatefromwbmp($path);
			break;	/// BMP
	}
	if ($srcImage) {
		if (function_exists("imagegif")) ImageGif ( $srcImage, $new_path );
		else return false;
	}
	ImageDestroy( $srcImage  );
	return $new_path;
}

/**
 * јбоненска€ плата за нахождение пользовател€ в группе id_service = 4
 * если польз-ль уже принадлежит выбранной группе, то купленный период
 * нахождени€ в группе прибавл€етс€ к уже имеющемус€ у пользовател€
 *
 * @return void
 */
function GroupSet() {
	global $config, $smarty, $dbconn, $user, $lang, $cur;

	$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]<1) {
		ServicesList("group","no_money");
		exit;
	}
	$user_acc = $rs->fields[0];

	if (isset($_REQUEST["period_id"]) && !empty($_REQUEST["period_id"])) {
		$period_id = $_REQUEST["period_id"];
	} else {
		ServicesList();
	}

	/**
	 * payment information
	 */
	$pay_period["day"] = 1;
	$pay_period["week"] = 7;
	$pay_period["month"] = 30;
	$pay_period["year"] = 365;

	$strSQL = "select id_group, cost, period, amount from ".GROUP_PERIOD_TABLE." where id='".$_REQUEST["period_id"]."' ";
	$rs = $dbconn->Execute($strSQL);

	$group_id = $rs->fields[0];

	$price = $rs->fields[1];
	$period = $rs->fields[3]*$pay_period[$rs->fields[2]];
	$err = "group_get";
	if (!$group_id) {
		return "no_group_with_id".$data["period_id"];
	}
	/**
	 * check: if not enough money on user account
	 */
	if ($user_acc < $price) {
		$err = "bill_is_small";
		ServicesList('group',$err);
		exit;
	} else {
		$new_user_acc = round(($user_acc - $price), 2);

		$strSQL = " UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account=".$new_user_acc." WHERE id_user='".$user[0]."' ";
		$dbconn->Execute($strSQL);

		$date_spended = date("Y-m-d H:i:s", time());
		$dbconn->Execute("	INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
							VALUES ('".$user[0]."', '".$price."', '".$cur."', '4', '".$date_spended."' ) ");

		$date_begin = date("Y-m-d H:i:s");
		$ts = time() + $period*24*60*60;
		$date_end = date("Y-m-d H:i:s", $ts);

		$strSQL = "SELECT bup.date_end, gp.id_group FROM  ".BILLING_USER_PERIOD_TABLE." bup ".
				  "LEFT JOIN ".GROUP_PERIOD_TABLE." gp ON gp.id=bup.id_group_period ".
				  "WHERE bup.id_user='".$user[0]."' ";
		$rs = $dbconn->Execute($strSQL);

		if ($rs->RecordCount() == 0) {
			$strSQL = "INSERT INTO ".BILLING_USER_PERIOD_TABLE." (date_begin, date_end, id_user, id_group_period) values ('".$date_begin."', '".$date_end."', '".$user[0]."', $period_id) ";
			$dbconn->Execute($strSQL);
		} else {
			$_date_end = $rs->fields[0];
			$_id_group = $rs->fields[1];

			if ($_id_group == $group_id) {
				$new_date_end = date("Y-m-d H:i:s",strtotime($_date_end)+strtotime($date_end)-strtotime($date_begin));
				$strSQL = "UPDATE ".BILLING_USER_PERIOD_TABLE." SET date_end='".$new_date_end."' WHERE id_user='".$user[0]."'";
			} else {
				$strSQL = "UPDATE ".BILLING_USER_PERIOD_TABLE." SET date_begin='".$date_begin."', date_end='".$date_end."', id_group_period='".$period_id."' WHERE id_user='".$user[0]."'";
			}
			$dbconn->Execute($strSQL);
		}

		$strSQL = " SELECT id FROM ".USER_GROUP_TABLE." WHERE id_user='".$user[0]."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$dbconn->Execute("UPDATE ".USER_GROUP_TABLE." SET id_group='".$group_id."' WHERE id_user='".$user[0]."' ");
		} else {
			$dbconn->Execute("INSERT INTO ".USER_GROUP_TABLE." (id_user, id_group) values ('".$user[0]."', '".$group_id."')");
		}
        if (GetSiteSettings("use_pilot_module_newsletter")) {
			UpdateUserRealestateMailingList($user[0]);
		}
		
		ServicesList('group', $err);
	}
	return;
}

function TrialMembersip() {
	global $config, $dbconn, $user;

	$id_group = (isset($_REQUEST["id_group"]) && !empty($_REQUEST["id_group"])) ? intval($_REQUEST["id_group"]) : 0;

	if (!$id_group) {
		ServicesList();
		exit();
	}

	$strSQL = "SELECT type, allow_trial, trial_period FROM ".GROUPS_TABLE." WHERE id='$id_group'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->getRowAssoc( false );
	if (!($row["type"] == "f" && $row["allow_trial"])) {
		ServicesList();
		exit();
	}
	/**
	 * check if trial membership service for the group have already been used
	 */
	$strSQL = "SELECT id FROM ".GROUP_TRIAL_USER_PERIOD_TABLE." WHERE id_user='".$user[0]."' AND id_group='$id_group'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RecordCount() > 0) {
		ServicesList();
		exit();
	}
	//trial_period could be saved from admin only as days number
	$date_begin = date("Y-m-d H:i:s");
	$ts = time() + $row["trial_period"]*24*60*60;
	$date_end = date("Y-m-d H:i:s", $ts);

	//save users' trial period of membership in group in general table
	$dbconn->Execute("DELETE FROM ".BILLING_USER_PERIOD_TABLE." WHERE id_user='".$user[0]."'");

	$strSQL = "INSERT INTO ".BILLING_USER_PERIOD_TABLE." (date_begin, date_end, id_user, id_group_period) values ('".$date_begin."', '".$date_end."', '".$user[0]."', 0) ";
	$dbconn->Execute($strSQL);

	//save users' trial period of membership in group in trial statistics table
	$strSQL = "INSERT INTO ".GROUP_TRIAL_USER_PERIOD_TABLE." (date_begin, date_end, id_user, id_group) values ('".$date_begin."', '".$date_end."', '".$user[0]."', $id_group)";
	$dbconn->Execute($strSQL);

	//save users group
	$strSQL = " SELECT id FROM ".USER_GROUP_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$dbconn->Execute("UPDATE ".USER_GROUP_TABLE." SET id_group='".$id_group."' WHERE id_user='".$user[0]."' ");
	} else {
		$dbconn->Execute("INSERT INTO ".USER_GROUP_TABLE." (id_user, id_group) values ('".$user[0]."', '".$id_group."')");
	}
	
    if (GetSiteSettings("use_pilot_module_newsletter")) {
	        UpdateUserRealestateMailingList($user[0]);
    }
	header("Location: ".$config["server"].$config["site_root"]."/services.php?sel=group&group=$id_group");
	exit();
}

function SellLeasePayment() {
	global $config, $dbconn, $user, $cur;

	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : 0;
	if (!$id) {
		ServicesList("sell_lease");
		exit();
	}
	/**
	 * check: if there are money on the user account
	 */
	$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]<1) {
		ServicesList("sell_lease","no_money");
		exit;
	}
	$user_acc = $rs->fields[0];

	$strSQL = "SELECT ads_number, amount FROM ".SELL_LEASE_PAYMENT_SETTINGS_TABLE." WHERE id='$id'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount() == 0) {
		ServicesList("sell_lease");
		exit();
	}
	$payment = $rs->getRowAssoc( false );

	/**
	 * check: if not enough money on user account
	 */
		if ($user_acc < $payment["amount"]) {
		$err = "bill_is_small";
		ServicesList("sell_lease", $err);
		exit;
	}

	$new_user_acc = round(($user_acc - $payment["amount"]), 2);

	$strSQL = " UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account=".$new_user_acc." WHERE id_user='".$user[0]."' ";
	$dbconn->Execute($strSQL);

	$dbconn->Execute("INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
					  VALUES ('".$user[0]."', '".$payment["amount"]."', '".$cur."', '5', now() ) ");

	$strSQL = "SELECT id FROM ".USER_SELL_LEASE_PAYMENT_TABLE." WHERE id_user='{$user[0]}'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount() == 0) {
		$strSQL = "INSERT INTO ".USER_SELL_LEASE_PAYMENT_TABLE." (id_user, ads_number, amount, used_ads_number) ".
				  "VALUES ('".$user[0]."', '".$payment["ads_number"]."', '".$payment["amount"]."', 0) ";
	} else {
		$strSQL = "UPDATE ".USER_SELL_LEASE_PAYMENT_TABLE." SET ".
				  "ads_number=ads_number+'".$payment["ads_number"]."', amount=amount+'".$payment["amount"]."' ".
				  " WHERE id_user='{$user[0]}'";
	}
	$dbconn->Execute($strSQL);

	header("Location: ".$config["server"].$config["site_root"]."/services.php?sel=sell_lease");
	exit();
}

function GetSmsCoinTarification() {
	global $config, $dbconn;

    $strSQL = "SELECT o.id, o.name, FORMAT(t.amount,2) AS amount FROM ".BILLING_SYS_."smscoin"._OPERATOR." o ".
    		  "LEFT JOIN ".BILLING_SYS_."smscoin"._TARIF." t on t.operator_id=o.id ".
    		  "WHERE t.id IS NOT NULL ".
    		  "ORDER BY o.id, t.amount ";
    $rs = $dbconn->Execute($strSQL);

    $operator_cnt = $rs->RowCount();
    if ($operator_cnt == 0) {
    	return false;
    }

    $operators = array();
    while (!$rs->EOF) {
    	$row = $rs->GetRowAssoc( false );
    	$operators[$row["id"]]["tarif"][] = $row["amount"];
    	$rs->MoveNext();
    }

    $lang["operators"] = GetLangContent('smscoin_operators');

    foreach ($operators as $op_id=>$op_tarif) {
		$operators[$op_id]["name"] = $lang["operators"][$op_id];
    }
    return $operators;
}

function PaymentBtwUser($err='', $page='', $sorter='', $search='', $order='', $is_show='', $user_index='') {
	global $config, $smarty, $dbconn, $user, $lang;
	if (isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "services.php";

	IndexHomePage('services', 'homepage');

	CreateMenu('homepage_top_menu');
	CreateMenu('homepage_user_menu');
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('rental_menu');
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$smarty->assign("submenu", "services");
	if ($err) {
		GetErrors($err);
	}

	$strSQL = " SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$data["user_bill"] = round($rs->fields[0],2);
	$lang["users_types"] = GetLangContent("users_types");
	$lang["rentals"] = GetLangContent("rentals");
	$param = $file_name."?sel=payment_between_user&amp;";


	if ($page == "") $page = (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) ? intval($_REQUEST["page"]) : 1;
	if ($sorter == "") $sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 1;
	if ($search == "") $search = (isset($_REQUEST["search"]) && !empty($_REQUEST["search"])) ? $_REQUEST["search"] : "";
	if ($order == "") $order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 2;
	if ($is_show == "") $is_show = (isset($_REQUEST["is_show"]) && !empty($_REQUEST["is_show"])) ? intval($_REQUEST["is_show"]) : "";
	if ($user_index == "") $user_index = isset($_REQUEST["user_index"]) ? intval($_REQUEST["user_index"]) : -1;



	// search
	$search_str = "";

	if (strval($search)) {
		$search = strip_tags($search);
			$search_str .= " AND ( u.fname LIKE '%".$search."%'";
			$search_str .= " OR u.sname LIKE '%".$search."%'";
			$search_str .= " OR rd.company_name LIKE '%".$search."%' ) ";
	}

	$smarty->assign("search", $search);
	//$smarty->assign("s_type", $s_type);
	$smarty->assign("lang", $lang);
	$smarty->assign("is_show", $is_show);

	$data = getRealtySortOrder($sorter, $order, "user2");
	$smarty->assign("sorter", $sorter);
	if (strval($search)) {
		$where_str = " where u.id !='".$user[0]."' AND u.id !='1' AND u.id != '2' ";
		if ($search_str) {
			$where_str .= "AND u.id>0 ".$search_str." ";
		}else{
			$where_str .= "";
		}
	
		$strSQL = "SELECT COUNT(*) FROM ".USERS_TABLE." u LEFT JOIN ".USER_REG_DATA_TABLE." rd ON rd.id_user=u.id ".$where_str;
		$rs = $dbconn->Execute($strSQL);
		$num_records = $rs->fields[0];
		if (($num_records>0) && ($is_show)) {
			$rows_num_page = GetSiteSettings('admin_rows_per_page');
			$lim_min = ($page-1)*$rows_num_page;
			$lim_max = $page*$rows_num_page;
			$limit_str = ($sorter == 5) ? "" : " limit ".$lim_min.", ".$lim_max;
			$strSQL = "	SELECT DISTINCT ra.id_user as ads_user,
						u.id, u.fname, u.sname, u.status, u.access, u.login, u.email,
						u.active, u.user_type, rd.company_name
						FROM ".USERS_TABLE."  u
						LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id_user=u.id
						LEFT JOIN ".USER_REG_DATA_TABLE." rd ON rd.id_user=u.id
	 					".$where_str." ORDER BY ".$data["sorter_str"].$data["sorter_order"].$limit_str;
	
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			if ($rs->RowCount()>0) {
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$users[$i]["number"] = ($page-1)*$rows_num_page+($i+1);
					$users[$i]["id"] = $row["id"];
					$users[$i]["index"] = $i;
					$users[$i]["name"] = stripslashes($row["fname"]." ".$row["sname"]);
					$users[$i]["email"] = $row["email"];
					$users[$i]["company_name"] = $row["company_name"];
	
					//rent link
	
					$users[$i]["rent_link"] = "viewprofile.php"."?sel=more_ad&id_user=".$row["ads_user"]."&redirect=1&pageR=$page&sorter=$sorter&search=$search&order=$order&is_show=$is_show";
					$strSQL = "SELECT DISTINCT id FROM ".RENT_ADS_TABLE." WHERE id_user='".$users[$i]["id"]."' AND status='1'";
					$res = $dbconn->Execute($strSQL);
					if ( $res->RowCount()>0 ) {
						$users[$i]["rent_count"] = $res->RowCount();
					}
	
					$rs->MoveNext();
					$i++;
				}
				/**
				 * сортировка по объ€влени€м
				 */
				if ($sorter == 5) {
					function cmp_desc($a, $b) {
						if ($a["rent_count"] == $b["rent_count"]) {
					      return 0;
					    }
					    return ($a["rent_count"] > $b["rent_count"]) ? -1 : 1;
					}
					function cmp_asc($a, $b) {
						if ($a["rent_count"] == $b["rent_count"]) {
					   	   return 0;
					   	}
					   	return ($a["rent_count"] < $b["rent_count"]) ? -1 : 1;
					}
	
					if ($data["sorter_order"] == " ASC ") {
						usort($users, "cmp_asc");
					} else {
						usort($users, "cmp_desc");
					}
					$max_number = $lim_max;
					$max_number = ($num_records < $max_number) ? $num_records : $max_number;
					for ($i=$lim_min; $i<$max_number; $i++ ) {
						$res_user[] = $users[$i];
					}
					$users = $res_user;
				}
	
	
				$smarty->assign("page", $page);
				$smarty->assign("rows_num_page", $rows_num_page);
				$param = $param."is_show=1&search=".$search."&sorter=".$sorter."&order=".$order."&";
				$smarty->assign("links", GetLinkArray($num_records, $page, $param, $rows_num_page) );
			}
			$smarty->assign("users", $users);
		}
		
		$smarty->assign("user_index", $user_index);
	}
	//$smarty->assign("home", $lang_content["home"]);
	$smarty->assign("file_name", $file_name);

	$smarty->assign("par", "payment_between_user");
	$settings = GetSiteSettings(array("commission_percent"));

	$smarty->assign("data", $data);
	$smarty->assign("settings", $settings);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/services_list_table.tpl");
	exit;
}

function TransferToUser() {
	global $config, $smarty, $dbconn, $user, $lang, $cur;
	$amount = floatval($_REQUEST["amount"]);

	$smarty->assign("amount", $amount);
	if ($amount <= 0) {
		PaymentBtwUser('negative_number_error', $_POST["page"], $_POST["sorter"], $_POST["search"], $_POST["order"], 1, $_POST["user_index"]);
		exit;
	}

	$minimal_transfer_value = GetSiteSettings("minimal_transfer_value");
	$smarty->assign("minimal_transfer_value", $minimal_transfer_value);
	if ($amount < $minimal_transfer_value) {
		PaymentBtwUser('min_transfer_error', $_POST["page"], $_POST["sorter"], $_POST["search"], $_POST["order"], 1, $_POST["user_index"]);
		exit;
	}

	$commission_percent = GetSiteSettings("commission_percent");

	$strSQL = " SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$balance = round($rs->fields[0],2);

	if ($amount >= $balance) {
		PaymentBtwUser('few_balance', $_POST["page"], $_POST["sorter"], $_POST["search"], $_POST["order"], 1, $_POST["user_index"]);
		exit;
	}
	$balance = $balance - $amount;
	$without_commission = (1 - $commission_percent/100);
	$to_user_id	= $_POST["to_user_id"];

	$strSQL = "UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='$balance', date_refresh=now() WHERE id_user='".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);

	$strSQL = " SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$to_user_id."' ";
	$rs = $dbconn->Execute($strSQL);

	$add_on_account = round($without_commission * $amount, 2);
	if ($rs->RowCount() > 0) {
		$balance_to = round($rs->fields[0] + $without_commission * $amount,2);
		$strSQL = "UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='$balance_to', date_refresh=now(), is_send='0' WHERE id_user='".$to_user_id."'";
		$rs = $dbconn->Execute($strSQL);
	} else {
		$balance_to = $add_on_account;
		$dbconn->Execute("INSERT INTO ".BILLING_USER_ACCOUNT_TABLE." (id_user, account, date_refresh, account_curr, is_send) VALUES ('$to_user_id', '$balance_to', now(), '0', '0')");
	}

	$date_spended = date("Y-m-d H:i:s", time());
	$dbconn->Execute("INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
							VALUES ('".$user[0]."', '$amount', '".$cur."', '6', '".$date_spended."' ) ");

	$dbconn->Execute("INSERT INTO ".BILLING_USER_RECIPIENTS_TABLE." ".
				"(id_user, id_from_user, count_curr, commission, currency, date_send) ".
				"VALUES ('".$to_user_id."', '".$user[0]."', '".$add_on_account."', '".round(($commission_percent/100) * $amount, 2)."', '".$cur."', now()) ");

	/**
	 * Send mail to the recipient user
	 */
	$strSQL = "SELECT email, fname, sname, lang_id FROM ".USERS_TABLE." WHERE id='$to_user_id'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$user["email"] = $row["email"];
	$user["lang_id"] = $row["lang_id"];

	$data["name"] = stripslashes($row["fname"])." ".stripslashes($row["sname"]);
	$data["add_on_account"] = $add_on_account;
	$data["account"] = $balance_to;

	$site_email = GetSiteSettings('site_email');

	$mail_content = GetMailContentReplace("mail_content_money_add", GetUserLanguageId($user["lang_id"]));

	SendMail($user["email"], $site_email, $mail_content["subject"], $data, $mail_content, "mail_money_add_table", '', $data["name"] , $mail_content["site_name"], 'text');

	PaymentBtwUser('successful_transfer', $_POST["page"], $_POST["sorter"], $_POST["search"], $_POST["order"], 1);
	return;
}

function SaveBanner() {
	global $config, $dbconn;
	
	$upload_virtual_path = $config["server"].$config["site_root"]."/uploades/adcomps/";
	$upload_physical_path = $config["site_path"]."/uploades/adcomps/";
	$IMG_TYPE_ARRAY = array("image/jpeg", "image/pjpeg", "image/gif", "image/bmp", "image/tiff", "image/png", "image/x-png");
	$IMG_EXT_ARRAY = array("jpeg", "jpg", "gif", "wbmp", "tiff", "png");

	$error = array();
	$id = (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) ? intval($_REQUEST["id"]) : false;
	/**
	 * Check values from form
	 */
	$banner["type"] = $_REQUEST["type"];	
	$banner["name"] = htmlspecialchars(trim($_REQUEST["name"]), ENT_QUOTES);
	if ($banner["name"] == "") {
		$error[] = "enter_name";
	}
	$banner["position"] = $_REQUEST["banner_position"];
	$banner["user_id"] = intval($_REQUEST["user_id"]);
	$banner["payment_status"] = "toaprove";
	$banner["status"] = 0;

	/**
	 * users could add only image banners
	 */
	$banner["banner_size"] = $_REQUEST["banner_size"][$banner["position"]];		
	$banner["url"] = htmlspecialchars(trim($_REQUEST["url"]), ENT_QUOTES);
	$banner["alt_text"] = htmlspecialchars(trim($_REQUEST["alt_text"]), ENT_QUOTES);	
	
	$banner["open_in_new_window"] = intval($_REQUEST["open_in_new_window"]);
	$banner["file_path"] = $_REQUEST["file_path"];
	/**
	 * checking of image
	 */		
	if (isset($_FILES["file"])) {
		
		if (isset($_FILES["file"]["tmp_name"]) && !empty($_FILES["file"]["tmp_name"])) {		
			$file_name = $_FILES["file"]["name"];
			$temp_file = $_FILES["file"]["tmp_name"];
			$ex_arr = explode(".",$file_name);
			$extension = strtolower($ex_arr[count($ex_arr)-1]);
	
			if ((!in_array($_FILES["file"]["type"], $IMG_TYPE_ARRAY)) || (!in_array($extension, $IMG_EXT_ARRAY))) {
				$error[] = "err_not_image";
			} else {
				$f_short_name = "adcomp"."_".date("ymdhis").".".$extension;
				$new_file_name = $upload_physical_path.$f_short_name;
				$res = copy($temp_file, $new_file_name);
				if (!$res) {
					$error[] = "can_not_upload_image";
				} else {
					$banner["file_path"] = $f_short_name;
				}
			}
		} elseif (empty($_FILES["file"]["tmp_name"]) && $_FILES["file"]["error"] == 1 && $_FILES["file"]["size"] == 0) {
			//image is bigger than upload_max_filesize in php.ini
			$error[] = "invalid_image_size";			
		}
	} elseif ($banner["file_path"] == "") {
		$error[] = "enter_image";
	}

	if (count($error) > 0) {
		/**
		 * Invalid values from form
		 */
		if (isset($new_file_name) && file_exists($new_file_name)) {
			unlink($new_file_name);
			$banner["file_path"] = "";
		}
				
		$add_to_url = "";
		foreach ($error as $err) {
			$add_to_url .= "&error[]=".$err;
		}
		header("Location: services.php?sel=add_banner&id=$id".$add_to_url);
		exit();
		
	} else {
		$strSQL = "";
		if ($id) {
			$strSQL .= "UPDATE ".BANNERS_TABLE." SET ";
		} else {
			$strSQL .= "INSERT INTO ".BANNERS_TABLE." SET ";
		}
	
		$strSQL .= "name='".$banner["name"]."', type='".$banner["type"]."', status='".$banner["status"]."', ".
				   "id_user='".$banner["user_id"]."', id_group_for='-1', payment_status='".$banner["payment_status"]."', ".
				   "url='".$banner["url"]."', alt_text='".$banner["alt_text"]."', ".
				   "position='".$banner["position"]."', size_id='".$banner["banner_size"]."', ".
				   "file_path='".$banner["file_path"]."', ".				   
				   "open_in_new_window='".$banner["open_in_new_window"]."'";
		if ($id) {
			$strSQL .= " WHERE id='$id'";
		}
		$rs = $dbconn->Execute($strSQL);

		header( "Location: services.php?sel=all_banners");
		exit();
	}
}

function GetPositionsSizes() {
	global $dbconn, $config;
	$sizes = array();

	$strSQL = "SELECT id, size_x, size_y, able_place FROM ".BANNERS_SIZES_TABLE." ORDER BY able_place";
	$rs = $dbconn->Execute($strSQL);

	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc( false );
		$sizes[$row["able_place"]][] = $row;
		$rs->MoveNext();
	}
	return $sizes;
}

function GetBannerPosition($position) {
	
	$banner_position = array();
	foreach ($position as $key=>$name) {
		$pos = array();
		$pos["id"] = $key;
		$pos["name"] = $name;
		$banner_position[] = $pos;
	}
	return $banner_position;
}

function GetRotateSettings() {
	global $config, $dbconn, $position;

	$strSQL = "SELECT position, rotate_flag, rotate_time FROM ".BANNERS_ROTATE_TABLE;
	$rs = $dbconn->Execute($strSQL);

	$rotate_arr = array();

	while (!$rs->EOF) {
		$rotate = array();
		$rotate = $rs->GetRowAssoc(false);
		$rotate["position_name"] = $position[$rotate["position"]];

		$rotate_arr[] = $rotate;
		$rs->MoveNext();
	};

	return $rotate_arr;
}
/**
 * Get new image sizes
 *
 * @param integer $x
 * @param integer $y
 * @return array
 */
function ResizeImage($x, $y) {
	$maxy = 150;
	$maxx = 150;

    $newx = $x; $newy = $y;
    if (($x > $maxx)&&($y <= $maxy) || ($x > $maxx)&&($y > $maxy)) {
        $newx = $maxx;
        $newy = round($newx*$y/$x);
    } elseif (($y > $maxy)&&($x <= $maxx) || ($y > $maxy)&&($x > $maxx)) {
        $newy = $maxy;
        $newx = round($newy*$x/$y);
    }
    return array( "height" => $newy, "width" => $newx);
}

function GetCostForBanner($area_id, $reg_unreg) {
	global $dbconn;
	$strSQL = "SELECT cost_".$reg_unreg."ister_part FROM ".BANNERS_AREA_TABLE." WHERE id = '$area_id'";
	$rs = $dbconn->Execute($strSQL);
	return $rs->fields[0];
}

function GetFileNameForBanner($area_id) {
	global $dbconn;
	$strSQL = "SELECT file_name FROM ".BANNERS_AREA_TABLE." WHERE id = '$area_id'";
	$rs = $dbconn->Execute($strSQL);
	return $rs->fields[0];
}

function ActivateBanner() {
	global $dbconn, $config, $user, $cur;
	
	$banner_id = (isset($_REQUEST["banner_id"])) ? intval($_REQUEST["banner_id"]) : 0;
	$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user = '".$user[0]."'";
	$rs = $dbconn->Execute($strSQL);
	$balance = $rs->fields[0];
	if ($balance > 0) {
		$balance_out = 0;
		foreach ($_POST["area"] AS $area_id=>$item) {
			$item_reg = ((isset($item["reg"]) && $item["reg"])) ? 1 : 0;
			$item_unreg = ((isset($item["unreg"]) && $item["unreg"])) ? 1 : 0;
			if ($item_reg || $item_unreg) {
				$cost_reg = GetCostForBanner($area_id, "reg");
				$uncost_reg = GetCostForBanner($area_id, "unreg");
				$strSQL = "INSERT INTO ".BANNERS_BELONGS_AREA_TABLE." (banner_id, area_id, register_part, unregister_part, cost_register_part, cost_unregister_part)
							VALUES ('$banner_id', '$area_id', '$item_reg', '$item_unreg', '$cost_reg', '$uncost_reg')";
				$dbconn->Execute($strSQL);
				if ($item_reg) {
					$balance_out += $cost_reg;
				}
				if ($item_unreg) {
					$balance_out += $uncost_reg;
				}
			}
		}
		if ($balance > $balance_out) {
			if ($_POST["area"]) {
				
				
				$strSQL = "SELECT * FROM ".BANNERS_SETTINGS;
		  		$rs = $dbconn->Execute($strSQL);
		  		while(!$rs->EOF) {
		  			$row = $rs->GetRowAssoc(false);
		  			$banner_settings[$row["name"]] = $row["value"];
		  			$rs->MoveNext();
		  		}
		  		
				switch ($banner_settings["banner_period"]) {
					case "day":
						$kf = 24*60*60;
						break;
					case "week":
						$kf = 7*24*60*60;
						break;	
					case "month":
						$kf = 30.42*24*60*60;
						break;
					case "year":
						$kf = 365.25*24*60*60;
						break;		
				}
				$exp_date_int = time() + $kf * $banner_settings["banner_period_amount"];
				$exp_date = date("Y-m-d", $exp_date_int);
				$strSQL = "UPDATE ".BANNERS_TABLE." SET status = '1', resolved_places = '', stop_after_date = '1', stop_after_date_num = '$exp_date', payment_status = 'payed' WHERE id = '$banner_id' AND id_user = '$user[0]'";
				
				$dbconn->Execute($strSQL);
			}
			
		
			$new_balance = $balance - $balance_out;
					
			$strSQL = "UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='$new_balance', date_refresh=now() WHERE id_user='".$user[0]."'";
			$rs = $dbconn->Execute($strSQL);

			$date_spended = date("Y-m-d H:i:s", time());
			$dbconn->Execute("INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
							VALUES ('".$user[0]."', '$balance_out', '".$cur."', '8', '".$date_spended."' ) ");
			
			$rs = $dbconn->Execute($strSQL);
		}
	}	
	ServicesList("all_banners"); exit();
}

function DeleteBanner() {
	global $dbconn, $smarty, $user;
	$id = intval($_REQUEST["id"]);
	$mode = intval($_REQUEST["mode"]);
	$upload_physical_path = $config["site_path"]."/uploades/adcomps/";
	/**
	 * Delete Image
	 */
	$strSQL = "SELECT file_path FROM ".BANNERS_TABLE." WHERE id=".$id."";
	$rs = $dbconn->Execute($strSQL);
	$file_path = $rs->fields[0];
	if ($file_path != "") {
		unlink($upload_physical_path.$file_path);
	}
	/**
	 * Delete Banner
	 */
	$strSQL = "DELETE FROM ".BANNERS_TABLE." WHERE id=".$id."";
	$dbconn->Execute($strSQL);

	$strSQL = "DELETE FROM ".BANNERS_BELONGS_AREA_TABLE." WHERE banner_id=".$id."";
	$dbconn->Execute($strSQL);
	/**
	 * Delete statistics
	 */
	$strSQL = "DELETE FROM ".BANNERS_TEMP_STATISTICS_TABLE." WHERE banner_id='$id'";
	$rs = $dbconn->Execute($strSQL);
	$strSQL = "DELETE FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." WHERE banner_id='$id'";
	$rs = $dbconn->Execute($strSQL);

	ServicesList("all_banners"); exit();
}

function BuySmsNotifications(){
	global $config, $smarty, $dbconn, $user, $cur;
	$price_id = (isset($_REQUEST["sel_packet"])) ? intval($_REQUEST["sel_packet"]): 0;
	$smarty->assign("price_id", $price_id);
	if (!$price_id){
		ServicesList('sms_notifications','error_sel_packet');
		exit();
	}
	
	$strSQL = "SELECT sms_packet, cost FROM ".SMS_NOTIFICATIONS_PRICES." WHERE id='$price_id'";	
	$rs = $dbconn->Execute($strSQL);
	if (!$rs->fields[0]){
		ServicesList('sms_notifications','error_sel_packet');
		exit();
	}
	$sms_packet = $rs->fields[0];
	$cost = $rs->fields[1];
		
	$strSQL = " SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	$balance = round($rs->fields[0],2);
	
	if ($balance < $cost){
		ServicesList('sms_notifications','few_balance_for_sms');
		exit();
	}
	$balance = $balance - $cost;
	$strSQL = "UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET account='$balance', date_refresh=now(), is_send='0' WHERE id_user='".$user[0]."'";
	$dbconn->Execute($strSQL);
	
	$strSQL = "SELECT sms_balance FROM ".SMS_NOTIFICATIONS_USER_BALANCE." WHERE id_user='".$user[0]."'";	
	$rs = $dbconn->Execute($strSQL);
	
	if (!$rs->EOF){
		$sms_packet = $rs->fields[0] + $sms_packet;
		$strSQL = "UPDATE ".SMS_NOTIFICATIONS_USER_BALANCE." SET sms_balance='$sms_packet', date_refresh=now() WHERE id_user='".$user[0]."'";
	}else{
		$strSQL = "INSERT INTO ".SMS_NOTIFICATIONS_USER_BALANCE." (id_user, sms_balance, date_refresh) VALUES('".$user[0]."', '$sms_packet', now())";
	}	
	$dbconn->Execute($strSQL);
	
	
	$date_spended = date("Y-m-d H:i:s", time());
	$dbconn->Execute("INSERT INTO ".BILLING_SPENDED_TABLE." (id_user, count_curr, currency, id_service, date_send)
							VALUES ('".$user[0]."', '$cost', '".$cur."', '7', '".$date_spended."' ) ");
	
	ServicesList('sms_notifications');
}

function GetSmsSettings($name_sel = ""){
	global $dbconn;
	
	$strSQL= "SELECT value, name FROM ".SMS_NOTIFICATIONS_SETTINGS;
	if ($name_sel){
		$strSQL .= " WHERE name='".$name_sel."'";
	}
	
	$settings = array();
	$rs = $dbconn->Execute($strSQL);
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$settings[$row["name"]] = $row["value"];
		$rs->MoveNext();
	}	
	return $settings;
}

function SubscribeToSmsNotifications(){
	global $dbconn, $user;
	$strSQL = "SELECT sms_balance FROM ".SMS_NOTIFICATIONS_USER_BALANCE." WHERE id_user='".$user[0]."'";	
	$rs = $dbconn->Execute($strSQL);
	if (!$rs->fields[0]){
		ServicesList('sms_notifications','');
		exit();
	}
	$phone = preg_replace("/[^\d]/","", $_REQUEST["phone_number"]);
	$strSQL = "UPDATE ".SMS_NOTIFICATIONS_USER_BALANCE." SET phone='".$phone."' WHERE id_user='".$user[0]."'";	
	$dbconn->Execute($strSQL);
	
	if (GetSiteSettings("use_pilot_module_sms_notifications")){
		$strSQL = "DELETE FROM ".SMS_NOTIFICATIONS_USER_EVENT." WHERE id_user='".$user[0]."'";	
		$dbconn->Execute($strSQL);	
	
		if (isset($_REQUEST["sms_price"])){		
			
			$strSQL = "INSERT INTO ".SMS_NOTIFICATIONS_USER_EVENT." (id_subscribe, id_user) VALUES ";
			$i =0 ;
			foreach ($_REQUEST["sms_price"] AS $key=>$item){
				if ($i !=0){
					$strSQL .= ", ";
				}
				$strSQL.= "('$key','{$user[0]}')";
				$i++;
			}	
			$dbconn->Execute($strSQL);
		}
	}
	ServicesList('sms_notifications');
}

?>