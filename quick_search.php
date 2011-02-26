<?php
/**
* Quick search
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.9 $ $Date: 2009/01/22 10:03:35 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";
include "./include/class.lang.php";
include "./include/class.calendar_event.php";

$user = auth_index_user();
$mode = IsFileAllowed($user[0], GetRightModulePath(__FILE__) );
if($user[4]==1 && !(isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
} else {
	if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
		if ($_REQUEST["for_unreg_user"] == 1) {
			$user = auth_guest_read();
		}
	}
	if ( ($user[9] == 0) || ($user[7] == 0) || ($user[8] == 0)) {
		AlertPage();
		exit;
	} elseif ($mode == 0) {
		AlertPage(GetRightModulePath(__FILE__));
		exit;
	}
	session_register("quick_search_arr");
	session_register("quick_search_pars");
	session_register("feature");
	$multi_lang = new MultiLang($config, $dbconn);

	$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
	$smarty->assign("sel",$sel);
	
	if ($user[3] == 0) {
		if (isset($_REQUEST["from_file"]) && $_REQUEST["from_file"] == "search_preferences" || (count(GetSearchPreferences($user[0])) == 0 && isset($_REQUEST["choise"]) && intval($_REQUEST["choise"]) > 0)) {			
			SaveSearchPreferences($user[0]);
		}
		SaveSearchLocation($user[0]);
	} else {
		if (isset($sel) && in_array($sel, array("online", "new_members", "most_active"))) {
			AlertPage();
		}
	}

	switch ($sel) {
		case "from_form": NewSearch("from_form"); break;
        case "category": NewSearch("category"); break;
		case "search": NewSearch("search"); break;
		case "online": NewSearch("online"); break;
		case "new_members": NewSearch("new_members"); break;
		case "most_active":	NewSearch("most_active"); break;
		case "keyword":	NewSearch("keyword"); break;
		case "search_preferences": NewSearch("search_preferences"); break;
		case "last_ads": AllAdsDescForm(); break;
		case "from_search_preferences": QuickSearchForm(); break;
		default: QuickSearchForm(); break;
	}
}

/**
 * Get search results and assign them to smarty variable
 *
 * @param stirng $par - search type
 * @return void
 */
function NewSearch($par='') {
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $values_arr, $REFERENCES;

	$post_data = array();
	$post_data["hidden_choise"] = (isset($_REQUEST["choise"]) && intval($_REQUEST["choise"])) ? intval($_REQUEST["choise"]) : 4;
	$smarty->assign("post_data", $post_data);

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "quick_search.php";

    $section_name = 'search';
    if($par == 'category') {
        $category_choise = (isset($_REQUEST["choise"]) && intval($_REQUEST["choise"])) ? intval($_REQUEST["choise"]) : 4;
        
        switch($category_choise){
            case 1:
                $section_name = 'dik';
                break;
            case 2:
                $section_name = 'tours';
                break;
            case 3:
                $section_name = 'realestate';
                break;
            case 4:
                $section_name = 'active';
                break;
        }
    }
	IndexHomePage('quick_search',$section_name);

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	CreateMenu('search_menu');

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);

	if (isset($_REQUEST["from_file"]) && !empty($_REQUEST["from_file"])) {
		$from_file = $_REQUEST["from_file"];
		if (file_exists($config["site_path"]."/".$from_file.".php")) {		
			$smarty->assign("back_link", $config["server"].$config["site_root"]."/".$from_file.".php?back=1");
		} else {
			$smarty->assign("back_link", $config["server"].$config["site_root"]."/quick_search.php?back=1");
		}
	}else{
		$smarty->assign("back_link", $config["server"].$config["site_root"]."/quick_search.php?back=1");
	}
	
	$smarty->assign("submenu", $par);

    if($par == 'category') {
        $category_choise = (isset($_REQUEST["choise"]) && intval($_REQUEST["choise"])) ? intval($_REQUEST["choise"]) : 4;
        
        if($_SESSION["quick_search_pars"]['choise'] != $category_choise) {
            unset($_SESSION['quick_search_arr']);
        }
        
        $title = '';
        switch($category_choise){
            case 1:
                $title = 'Отдых дикарем';
                break;
            case 2:
                $title = 'Туры';
                break;
            case 3:
                $title = 'Недвижимость';
                break;
            case 4:
                $title = 'Активный отдых';
                break;
        }
        $smarty->assign('category_title', $title);
        $smarty->assign('choise', $category_choise);        
    }

	if ($page<2) {
		/**
		 * $_SESSION["quick_search_arr"] - array with founded by first request listings ids
		 */
		if ($par == "search_preferences" || (sizeof($_SESSION["quick_search_arr"])<1) || $par == "keyword") {
			unset($_SESSION["quick_search_arr"]);
			unset($_SESSION["feature"]);
			switch ($par){
				case "online":
					$strSQL = "	SELECT DISTINCT ra.id
							FROM ".USERS_TABLE." u, ".RENT_ADS_TABLE." ra, ".ACTIVE_SESSIONS_TABLE." acs
							WHERE u.id=acs.id_user AND ra.id_user=u.id AND u.status='1'  AND u.active='1' AND u.guest_user='0' AND u.id != '".$user[0]."' AND ra.status='1' ORDER BY u.id ";
					break;
				case "new_members":
					$strSQL = "	SELECT DISTINCT ra.id
							FROM ".USERS_TABLE." u, ".RENT_ADS_TABLE." ra
							WHERE u.id=ra.id_user AND ra.datenow > (now() - interval 7 day) AND u.status='1' AND u.active='1' AND u.guest_user='0' AND u.id != '".$user[0]."' AND ra.status='1' ORDER BY u.id desc";
					break;
				case "most_active":
					$strSQL = "	SELECT DISTINCT ra.id
							FROM ".USERS_TABLE." u, ".RENT_ADS_TABLE." ra, ".MAILBOX_MESSAGES_TABLE." m
							WHERE u.id=ra.id_user AND u.status='1' AND u.active='1' AND u.guest_user='0' AND u.id != '".$user[0]."' AND m.from_user_id=u.id AND ra.status='1' ORDER BY ra.id";
					break;
				case "keyword": {
					/**
					 * Search listings by keyword is a search by:
					 * - comment && headline from listing
					 * - comments to uploads (uploads (photo, video) && plan photos)
					 * - country, region, city, zip_code, address, cross streets
					 * - owner first name, second name, phone, company name & url
					 * - references values
					 */					
					$keyword = (isset($_REQUEST["keyword"]) && !empty($_REQUEST["keyword"])) ? addslashes($_REQUEST["keyword"]) : "";
					if (!$keyword) {
						if ($from_file && file_exists($config["site_path"]."/".$from_file.".php")) {					
							header("Location: ". $config["server"].$config["site_root"]."/".$from_file.".php");
						} else {
							header("Location: ". $config["server"].$config["site_root"]."/index.php");
						}
					}
					/**
					 * Get listings id, which have keyword in references values
					 */					
					$rs = $dbconn->Execute("SELECT id FROM ".LANGUAGE_TABLE." WHERE visible='1'");
					$ref_langs = array();
					while(!$rs->EOF){
						$ref_langs[] = "a.lang_".$rs->fields[0]."_1 LIKE '%$keyword%'";
						$ref_langs[] = "a.lang_".$rs->fields[0]."_2 LIKE '%$keyword%'";

						$rs->MoveNext();
					}
					$like_substr = join(" or ", $ref_langs);

					$id_arr = array();

					$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
					foreach ($REFERENCES as $arr) {
						if (in_array($arr["key"], $used_references)) {
							$_spr = $multi_lang->TableKey($arr["val_table"]);
							if ($arr["spr_match_table"] != "") {
								$strSQL = "SELECT b.id_ad AS id FROM ".REFERENCE_LANG_TABLE." a, ".
										  $arr["spr_match_table"]." b ".
										  "WHERE a.table_key=$_spr and a.id_reference=b.id_value and (".$like_substr.")";
								$rs = $dbconn->Execute($strSQL);
								while(!$rs->EOF){
									if (!in_array($rs->fields[0], $id_arr)) {
										$id_arr[] = $rs->fields[0];
									}
									$rs->MoveNext();
								}
							}
							$strSQL = "SELECT b.id_ad AS id FROM ".REFERENCE_LANG_TABLE." a, ".
									  $arr["spr_user_table"]." b ".
									  "WHERE a.table_key=$_spr and a.id_reference=b.id_value and (".$like_substr.")";
							$rs = $dbconn->Execute($strSQL);
							while(!$rs->EOF){
								if (!in_array($rs->fields[0], $id_arr)) {
									$id_arr[] = $rs->fields[0];
								}
								$rs->MoveNext();
							}
						}
					}

					$or_ids = (count($id_arr)) ? " OR ra.id IN ('".implode("', '", $id_arr)."') " : "";

					$strSQL = "SELECT DISTINCT ra.id ".
							  "FROM ".USERS_TABLE." u, ".RENT_ADS_TABLE." ra ".
							  "LEFT JOIN ".USERS_RENT_UPLOADS_TABLE." upl ON upl.id_ad=ra.id ".
							  "LEFT JOIN ".USER_RENT_PLAN_TABLE." urp ON urp.id_ad=ra.id ".
							  "LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id ".
							  "LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country ".
							  "LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region ".
							  "LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city ".
							  "LEFT JOIN ".USER_REG_DATA_TABLE." urd ON urd.id_user=ra.id_user ".
							  "WHERE u.id=ra.id_user AND u.status='1' AND u.active='1' AND u.guest_user='0' AND ".
							  "u.id != '".$user[0]."' AND ra.status='1' AND (".
							  //comment & headline
							  "(ra.comment LIKE '%$keyword%') ".
							  "OR (ra.headline LIKE '%$keyword%') ".
							  //uploads - photo & video
							  "OR (upl.status='1' AND upl.admin_approve='1' AND (upl.user_comment LIKE '%$keyword%')) ".
							  //plan
							  "OR (urp.status='1' AND urp.admin_approve='1' AND (urp.user_comment LIKE '%$keyword%')) ".
							  //location
							  "OR (url.street_1 LIKE '%$keyword%') ".
							  "OR (url.street_2 LIKE '%$keyword%') ".
							  "OR (url.adress LIKE '%$keyword%') ".
							  "OR (url.zip_code LIKE '%$keyword%') ".
							  "OR (ct.name LIKE '%$keyword%') ".
							  "OR (rt.name LIKE '%$keyword%') ".
							  "OR (cit.name LIKE '%$keyword%') ".
							  //owner fname, sname, phone
							  "OR (u.fname LIKE '%$keyword%') ".
							  "OR (u.sname LIKE '%$keyword%') ".
							  "OR (u.phone LIKE '%$keyword%') ".
							  //owner company name, url
							  "OR (urd.company_name LIKE '%$keyword%') ".
							  "OR (urd.company_url LIKE '%$keyword%') ".
							  //references
							  " $or_ids".
							  ") ORDER BY ra.id DESC";						
					break;
				}
				default: {
					$qsform_more_opt = (isset($_REQUEST["qsform_more_opt"]) && !empty($_REQUEST["qsform_more_opt"])) ? intval($_REQUEST["qsform_more_opt"]) : 0;
					$choise = (isset($_REQUEST["choise"]) && intval($_REQUEST["choise"])) ? intval($_REQUEST["choise"]) : 4;
					
					$photo = ($qsform_more_opt && isset($_REQUEST["photo"]) && !empty($_REQUEST["photo"]))? intval($_REQUEST["photo"]) : 0;
					$video = ($qsform_more_opt && isset($_REQUEST["video"]) && !empty($_REQUEST["video"]))? intval($_REQUEST["video"]) : 0;
					$min_payment = (isset($_REQUEST["min_payment"]) && !empty($_REQUEST["min_payment"])) ? intval($_REQUEST["min_payment"]) : 0;
					$max_payment = (isset($_REQUEST["max_payment"]) && !empty($_REQUEST["max_payment"])) ? intval($_REQUEST["max_payment"]) :0;
					$country = (isset($_REQUEST["country"]) && !empty($_REQUEST["country"])) ? intval($_REQUEST["country"]) : 0;
					$region = (isset($_REQUEST["region"]) && !empty($_REQUEST["region"])) ? intval($_REQUEST["region"]) : 0;					
					$city = (isset($_REQUEST["city"]) && !empty($_REQUEST["city"])) ? intval($_REQUEST["city"]) : 0;
					
					$use_movedate = ($qsform_more_opt && isset($_REQUEST["use_movedate"])) ? intval($_REQUEST["use_movedate"]) : 0;
					$move_day = ($qsform_more_opt && isset($_REQUEST["move_day"]) && !empty($_REQUEST["move_day"])) ? intval($_REQUEST["move_day"]) : 0;
					$move_month = ($qsform_more_opt && isset($_REQUEST["move_month"]) && !empty($_REQUEST["move_month"])) ? intval($_REQUEST["move_month"]) : 0;

					
					//with video
					$video_table = "";
					$video_str = "";
					if ($video>0) {
						$video_table = " , ".USERS_RENT_UPLOADS_TABLE." ru_video ";
						$video_str = " AND ru_video.id_ad=ra.id AND ru_video.upload_path<>'' AND ru_video.upload_type='v' AND ru_video.status='1' AND ru_video.admin_approve='1'";
					}

					$payment_str = "";
					if ($choise == 2 || $choise == 4){
						//только поля типа min
						if ($min_payment > 0) {
							$payment_str .= " AND (rp.min_payment >= '".$min_payment."' || rp.min_payment = '0')";
						}
						if ($max_payment > 0) {
							$payment_str .= " AND (rp.min_payment <= '".$max_payment."' || rp.min_payment = '0')";
						}
					} elseif ($choise == 1 || $choise == 3){
						if ($min_payment > 0) {
							$payment_str .= " AND (rp.min_payment >= '".$min_payment."' || rp.min_payment = '0') ";
						}
						if ($max_payment > 0) {
							$payment_str .= " AND (rp.max_payment <= '".$max_payment."' || rp.max_payment = '0') ";
						}
					}
					$payment_str .= (strlen($payment_str) > 0) ? " AND rp.id_ad=ra.id " : "";
					$payment_table = (strlen($payment_str) > 0) ? " ,".USERS_RENT_PAYS_TABLE." rp " : "";
					//location
					$location_table = "";
					$country_str = "";
					if ($country){
						$location_table = " , ".USERS_RENT_LOCATION_TABLE." rl ";
						$country_str = " AND rl.id_ad=ra.id AND rl.id_country='".$country."' ";
					}
					$region_str = "";
					if ($region) {
						$region_str = " AND rl.id_region='".$region."' ";
					}
					$city_str = "";
					if ($city){
						$city_str = " AND rl.id_city='".$city."' ";
					}
					//move date
					$move_date_str = "";
					if ($use_movedate) {
						if ($move_month && $move_day) {
							if ($move_month>12) {
								$move_month = $move_month-12;
								$move_year = date("Y")+1;
							} else {
								$move_year = date("Y");
							}
							$move_date = sprintf("%04d-%02d-%02d", $move_year, $move_month, $move_day);
							$move_date_str = " AND ra.movedate <= '".$move_date."' ";
							
							$_SESSION["quick_search_pars"]["move_year"] = $move_year;
						}
					}

					$_SESSION["quick_search_pars"]["qsform_more_opt"] = $qsform_more_opt;
					$_SESSION["quick_search_pars"]["choise"] = $choise;
					$_SESSION["quick_search_pars"]["photo"] = $photo;
					$_SESSION["quick_search_pars"]["video"] = $video;
					$_SESSION["quick_search_pars"]["min_payment"] = $min_payment;
					$_SESSION["quick_search_pars"]["max_payment"] = $max_payment;
					$_SESSION["quick_search_pars"]["country"] = $country;
					$_SESSION["quick_search_pars"]["region"] = $region;
					$_SESSION["quick_search_pars"]["city"] = $city;
					$_SESSION["quick_search_pars"]["use_movedate"] = $use_movedate;
					$_SESSION["quick_search_pars"]["move_day"] = $move_day;
					$_SESSION["quick_search_pars"]["move_month"] = $move_month;					

					$used_references = array("realty_type", "description");
					foreach ($used_references as $key=>$value) {
						if (isset($_REQUEST["spr_".$value])) {							
							if ($value == "realty_type" || ($value == "description" && $qsform_more_opt)) {
								$_SESSION["quick_search_pars"]["spr_".$value] = $_REQUEST["spr_".$value];
								$_SESSION["quick_search_pars"][$value] = $_REQUEST[$value];
							}								 
							if ($value == "description" && !$qsform_more_opt) {
								foreach ($_REQUEST["spr_".$value] as $k=>$val) {
									if ($val == 1) {								
										$_SESSION["quick_search_pars"]["spr_".$value] = $_REQUEST["spr_".$value][$k];
										$_SESSION["quick_search_pars"][$value] = $_REQUEST[$value][$k];
										break;
									}							
								}
							}
						}	
					}

					$spr_str = "";
					$spr_table = "";
					/**
					 * references value must be equal to entered in search form
					 */
					$used_references = array("realty_type");
					foreach ($REFERENCES as $arr) {
						if (in_array($arr["key"], $used_references)) {
							if (isset($_REQUEST["spr_".$arr["key"]])) {
								foreach ($_REQUEST["spr_".$arr["key"]] as $id_key=>$id_subspr) {
									$subspr_name = "spr_".$arr["key"];
									$user_value[$arr["key"]] = implode(",", $_REQUEST[$arr["key"]][$id_key]);
									if ($user_value[$arr["key"]] != "") {
										$spr_str .= " AND ($subspr_name.id_value IN (".$user_value[$arr["key"]].")) ";
										$spr_table .= " LEFT JOIN ".$arr["spr_user_table"]." $subspr_name ON $subspr_name.id_ad=ra.id AND $subspr_name.id_spr='$id_subspr' ";
									}
								}
							}	
						}
					}

					/**
					 * references value equal or more, than in search parametres
					 */
					$used_references = array("description");
					foreach ($REFERENCES as $arr) {
						if (in_array($arr["key"], $used_references)) {
							if (isset($_REQUEST["spr_".$arr["key"]])) {
								foreach ( $_REQUEST["spr_".$arr["key"]] as $id_subspr) {
									if ($qsform_more_opt || (!$qsform_more_opt && $id_subspr == 1)) {
										$subref = GetRefQuickSearchArray($arr["spr_table"], $arr["val_table"], $id_subspr);
										$user_min_spr_val[$arr["key"]] = GetSearchUserArray($_REQUEST["spr_".$arr["key"]], $_REQUEST[$arr["key"]]);
										$min_value = (isset($user_min_spr_val[$arr["key"]][$id_subspr])) ? $user_min_spr_val[$arr["key"]][$id_subspr][0] : 0;
										if ($min_value) {
											foreach ($subref as $subref_val) {
												if ($subref_val >= $min_value) {
													$user_values[] = $subref_val;
												}
											}
											$subspr_name = "spr_".$arr["key"].$id_subspr;
											$spr_str .= " AND ($subspr_name.id_value IN (".implode(",", $user_values).")) ";
											$spr_table .= " LEFT JOIN ".$arr["spr_user_table"]." $subspr_name ON $subspr_name.id_ad=ra.id AND $subspr_name.id_spr='$id_subspr' ";
										}
									}	
								}
							}	
						}
					}

				$strSQL = "SELECT DISTINCT ra.id
							FROM ".USERS_TABLE." u ".$video_table.$location_table." , ".RENT_ADS_TABLE." ra ".$spr_table.$payment_table."
							WHERE ra.type='".$choise."' AND ra.id_user != '".$user[0]."'
						 	".$video_str.$country_str.$region_str.$city_str.$move_date_str.$spr_str.$payment_str."
							AND u.id=ra.id_user AND u.status='1' AND u.guest_user='0' AND u.active='1' AND ra.status='1'";
				}

                
				break;
			}			
			
			$rs = $dbconn->Execute($strSQL);			
			$i = 0;
			$id_arr = array();
			
			while (!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);							
				$_SESSION["quick_search_arr"][$i] = $row["id"];
				if ($par == "search" || $par == "from_form") {
					$id_arr[] = $row["id"];
				}
				$i++;				
				$rs->MoveNext();				
			}
		
			
		}

	}
	$qsform_more_opt = (isset($_REQUEST["qsform_more_opt"]) && !empty($_REQUEST["qsform_more_opt"])) ? intval($_REQUEST["qsform_more_opt"]) : 0;
	$photo = ($qsform_more_opt && isset($_REQUEST["photo"]) && !empty($_REQUEST["photo"]))? intval($_REQUEST["photo"]) : 0;
	//with photo
					
	if ($photo>0) {		
		$strSQL_photo = "SELECT DISTINCT id_ad FROM ".USERS_RENT_UPLOADS_TABLE." WHERE upload_path<>'' AND upload_type='f' AND status='1' AND admin_approve='1'";
		$rs_photo = $dbconn->Execute($strSQL_photo);
		while (!$rs_photo->EOF){
			$with_photo_arr[] = $rs_photo->fields[0];
			$rs_photo->MoveNext();
		}											
	}

	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 0;
	$smarty->assign("sorter", $sorter);
	
	$sorter_order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 1;
	$param = "&sel=".$par."&photo=".$photo."&qsform_more_opt=".$qsform_more_opt."&amp;";
	$order_link = "&sel=".$par."&page=".$page."&photo=".$photo."&qsform_more_opt=".$qsform_more_opt;
	if (isset($keyword)){
		$order_link .= "&keyword=".$keyword;
	}

	$search_size = (isset($_SESSION["quick_search_arr"])) ? sizeof($_SESSION["quick_search_arr"]) : 0;	
	
	getSearchArr(isset($_SESSION["quick_search_arr"]) ? $_SESSION["quick_search_arr"] : array(), $file_name, $page, $param, $order_link, $sorter, $sorter_order, $par, isset($region) ? $region : "", isset($choise) ? $choise : "", isset($with_photo_arr) ? $with_photo_arr : "");
	
	$smarty->assign("sect", "rent");
	$smarty->assign("file_name", $file_name);

	$smarty->assign("page", $page);
	$smarty->assign("sel", $par);
	$smarty->assign("search_size", $search_size);
	$smarty->assign("map", GetMapSettings());
    
	$smarty->display(TrimSlash($config["index_theme_path"])."/quick_search_table.tpl");
	exit;
}

/**
 * Quick search form initialization
 *
 * @return void
 */
function QuickSearchForm(){
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	unset($_SESSION["quick_search_arr"]);
	unset($_SESSION["feature"]);
	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "quick_search.php";

	IndexHomePage('quick_search','search');
	CreateMenu('search_menu');

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$smarty->assign("submenu", "quick_search");
	$smarty->assign("sect", "rental");

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	if (isset($_REQUEST["back"]) && intval($_REQUEST["back"]) == 1) {
		/**
		 * Load search settings
		 */
		$data = $_SESSION["quick_search_pars"];
		$used_references = array("realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$key = $multi_lang->TableKey($arr["spr_table"]);
				if (!empty($data[$arr["key"]])) {
					$data[$key] = GetBackData($data[$arr["key"]]);
				}
			}
		}
		$used_references = array("realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], $data));
			}
		}
		$search_pref = $data;		
		GetLocationContent($data["country"], $data["region"]);
	} else {
		/**
		 * Load users' search preferences
		 */
		$new_search_location = (isset($_REQUEST["subsel"]) && $_REQUEST["subsel"] == "new_search_location") ? 1 : 0;
		if ($new_search_location) {
			$search_location["id_country"] = $_REQUEST["country"];
			$search_location["id_region"] = $_REQUEST["region"];
			$search_location["id_city"] = $_REQUEST["city"];
		} else {
			$search_location_id = isset($_REQUEST["search_location_id"]) ? intval($_REQUEST["search_location_id"]) : 0;
			if ($search_location_id) {
				$search_location = GetSearchLocationById($search_location_id);
			} else {
				$search_location = GetPrimarySearchLocation($user[0]);
			}
		}
		GetLocationContent(isset($search_location["id_country"]) ? $search_location["id_country"] : "", isset($search_location["id_region"]) ? $search_location["id_region"] : "");
		$data["region"] = isset($search_location["id_region"]) ? $search_location["id_region"] : "";
		$data["city"] = isset($search_location["id_city"]) ? $search_location["id_city"] : "";

		$search_pref = GetSearchPreferences($user[0]);
		$used_references = array("realty_type", "description");
		if ($search_pref) {
			/**
			 * load search preferences to references array
			 */
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$key = $multi_lang->TableKey($arr["spr_table"]);
					if ($arr["key"] == "realty_type") {
						$data[$key][] = $search_pref["realty_type"];
					} elseif ($arr["key"] == "description") {
						$data[$key][] = $search_pref["beds_number"];
						$data[$key][] = $search_pref["bath_number"];
						$data[$key][] = $search_pref["garage_number"];
					}
				}
			}
		}
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], $data));
			}
		}
		$data["qsform_more_opt"] = 1;
	}					
	$day = (isset($search_pref["move_day"]) && $search_pref["move_day"]) ? $search_pref["move_day"] : date("d")+1;
	$month = (isset($search_pref["move_month"]) && $search_pref["move_month"]) ? $search_pref["move_month"]: date("m");

	$smarty->assign("day", GetDaySelect($day));
	$smarty->assign("month", GetMonthSelect($month));

	$smarty->assign("search_pref", $search_pref );

	$smarty->assign("last_ads", 1);
	if ($user[3] != 1) {
		$area_parametres = GetOrderAds("quick_search",1);
	} else {
		$area_parametres = GetOrderAds("quick_search",0);
	}

	if ($area_parametres["show_type"] != "off")	{
		GetLastAds("last_ads_num_at_page", 1, "?", $area_parametres["sorter"], $area_parametres["sorter_order"], "",
				 $area_parametres["show_type"], $area_parametres["ads_number"], $file_name);
	}

	$smarty->assign("area_parametres", $area_parametres);
	$smarty->assign("data", $data);
	$smarty->assign("from_file", "quick_search");
	$smarty->assign("file_name", $file_name);
	$smarty->assign("map", GetMapSettings());
	$smarty->display(TrimSlash($config["index_theme_path"])."/quick_search_form.tpl");
	exit;
}

/**
 * Get limited by settings variable "last_ads_num_at_page" number of ads in order dy desc date
 *
 * @return void
 */
function AllAdsDescForm(){
	global $config, $smarty, $dbconn, $user, $lang, $multi_lang, $REFERENCES;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "quick_search.php";

	IndexHomePage('quick_search','search');
	CreateMenu('search_menu');

	$link_count = GetCountForLinks($user[0]);
	$smarty->assign("link_count",$link_count);
	$left_links = GetLeftLinks("homepage.php");
	$smarty->assign("left_links", $left_links);
	$smarty->assign("submenu", "quick_search");
	$smarty->assign("sect", "rental");

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	$sorter = (isset($_REQUEST["sorter"]) && !empty($_REQUEST["sorter"])) ? intval($_REQUEST["sorter"]) : 0;
	$smarty->assign("sorter", $sorter);
	$sorter_order = (isset($_REQUEST["order"]) && !empty($_REQUEST["order"])) ? intval($_REQUEST["order"]) : 1;

	$param = "&sel=last_ads&amp;";
	$order_link = "&sel=last_ads&amp;page=".$page;

	GetLastAds("last_ads_num_at_page", $page, $param, $sorter, $sorter_order, $order_link, "", 0, $file_name);

	$smarty->assign("file_name", $file_name);
	$smarty->assign("sel", "last_ads");
	$smarty->assign("map", GetMapSettings());
	$smarty->display(TrimSlash($config["index_theme_path"])."/quick_search_table.tpl");
	exit;
}

?>