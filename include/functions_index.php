<?php
/**
* Main functions for user mode
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.5 $ $Date: 2009/02/10 11:58:33 $
**/

/**
 * Function for assigning general variables to the template
 * and user account refreshing (call RefreshAccount($user) function)
 *
 * @param string $content_name
 * @param string $section_name
 * @return void
 */
function IndexHomePage($content_name, $section_name="", $is_module=0) {
	global $config, $dbconn, $smarty, $user, $lang, $REFERENCES, $multi_lang;
	$smarty->assign("user", $user);
	$smarty->assign("content_name", $content_name);

	$lang["content"] = ($is_module) ? GetLangContent($section_name."/".$content_name) : GetLangContent($content_name);
	$lang["headers"] = GetLangContent("headers");
	$lang["buttons"] = GetLangContent("buttons");
	$lang["errors"] = GetLangContent("errors");
	$lang["default_select"] = GetLangContent("default_select");
	$lang["testimonials_block"] = GetLangContent("testimonials_block");
	$metatags = GetMetatagsContent("metatags");
	
	$content_name  = ($is_module) ? $section_name."_".$content_name : $content_name;

	$lang["title"] = isset($metatags[$content_name]["metatags"]["title"]) ? $metatags[$content_name]["metatags"]["title"] : "";	
	$lang["description"] = isset($metatags[$content_name]["metatags"]["description"]) ? $metatags[$content_name]["metatags"]["description"] : "";
	$lang["keywords"] = isset($metatags[$content_name]["metatags"]["keywords"]) ? $metatags[$content_name]["metatags"]["keywords"] : "";

	$smarty->assign("lang", $lang);

	if ($section_name)
	$smarty->assign("section_name", $section_name);
	else
	$smarty->assign("section_name", $content_name);

	if(intval($user[0]) && $user[3] != 1)
	$smarty->assign("registered", 1);
	else
	$smarty->assign("registered", 0);

	$smarty->assign("site_root", $config["site_root"]);	
	$smarty->assign("server", $config["server"]);
	$smarty->assign("template_root", $config["index_theme_path"]);
	$smarty->assign("template_css_root", $config["index_theme_css_path"]);
	$smarty->assign("template_images_root", $config["index_theme_images_path"]);

	$thumb_width = GetSiteSettings('thumb_max_width');
	$thumb_height = GetSiteSettings('thumb_max_height');
	$smarty->assign("thumb_width", $thumb_width);
	$smarty->assign("thumb_alt_width", $thumb_width+20);
	$smarty->assign("thumb_height", $thumb_height);
		
	$smarty->assign("thumb_big_width", GetSiteSettings('thumb_big_max_width'));
	$smarty->assign("thumb_big_height", GetSiteSettings('thumb_big_max_height'));
		
	$config["one_country"] = GetSiteSettings('one_country');
	if ($config["one_country"] == '1') {
		$config["id_country"] = GetSiteSettings('id_country');
	}
	GetUserTopAd();
	RefreshAccount($user);
	if (isset($_GET["sess_id"])) {
		$sess_id = $_GET["sess_id"];
	} else {
		$sess_id = session_id();
		if(!$sess_id) $sess_id = $_REQUEST["PHPSESSID"];
	}
	$smarty->assign('sess_id', $sess_id);

	$site_unit_costunit = GetSiteSettings("site_unit_costunit");
	$strSQL = " SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='$site_unit_costunit' ";
	$rs = $dbconn->Execute($strSQL);
	$smarty->assign("cur_symbol", $rs->fields[0]);
	$smarty->assign("cur", $site_unit_costunit);

	$smarty->assign("contact_for_free", GetSiteSettings("contact_for_free"));
	$smarty->assign("contact_for_unreg", GetSiteSettings("contact_for_unreg"));
	
	$smarty->assign("use_agent_user_type", GetSiteSettings("use_agent_user_type"));

	if (GetSiteSettings("use_pilot_module_banners")) {
		Banners($content_name);
	}

	$smarty->assign("comparison_ids", GetUserComparisonIds());
	$smarty->assign("comparison_ids_cnt", count(GetUserComparisonIds()));

    $used_references = array("rest");
    foreach ($REFERENCES as $arr) {
        if (in_array($arr["key"], $used_references)) {
            $smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], ''));
        }
    }
    
    if ( ($_SERVER['SCRIPT_NAME'] == '/quick_search.php') || (($_REQUEST["back"]) && intval($_REQUEST["back"])==1))
    {
		/**
		 * Load search settings
		 */
		$data = $_SESSION["quick_search_pars"];
        
		$used_references = array("realty_type", "description", "theme_rest");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$key = $multi_lang->TableKey($arr["spr_table"]);
				if (!empty($data[$arr["key"]])) {
					$data[$key] = GetBackData($data[$arr["key"]]);
				}
			}
		}
		$used_references = array("realty_type", "description", "theme_rest");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {

				$smarty->assign($arr["key"], GetRefSearchArray($arr["spr_table"], $arr["val_table"], $data));
			}
		}
		$search_pref = $data;
		GetLocationContent($data["country"], $data["region"]);
        GetToursContent($data);
	} else {
		/**
		 * Load users' search preferences
		 */
		$search_location = GetPrimarySearchLocation($user[0]);
		GetLocationContent($search_location["id_country"], $search_location["id_region"]);
		$data["region"] = $search_location["id_region"];
		$data["city"] = $search_location["id_city"];

        GetToursContent();
		$search_pref = GetSearchPreferences($user[0]);
		$used_references = array("realty_type", "description", "theme_rest");
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
    
    
    require_once ('class.entertaiment_manager.php');
    $entertaiment_manager = new EntertaimentManager();
    
    $smarty->assign("entertaiments", $entertaiment_manager->GetEntertaimentList($config['default_lang']));
    
    $hot = getHot();
    $smarty->assign("hot", $hot);
	return;
}

function getToursContent($data = NULL)
{
    global $dbconn, $smarty;
    $strSQL = "SELECT c.* FROM ".CITY_TABLE." c".
        " INNER JOIN ".USERS_RENT_LOCATION_TABLE." p ON p.id_city = c.id ".
        " INNER JOIN ".RENT_ADS_TABLE." a ON a.id = p.id_ad WHERE a.parent_id=0 AND a.type = 2";
    
    $rs = $dbconn->Execute($strSQL);
    $i = 0;
	while(!$rs->EOF) {
	   $row = $rs->GetRowAssoc(false);
       $tours_from[$i] = $row; 
       
       $rs->MoveNext();
       $i++;
    }
    $smarty->assign('tours_from', $tours_from);
    
    if($data['tours_from'])
    {
        $smarty->assign('tours_from_id', $data['tours_from']);
    }
    
    $strSQL = "SELECT c.* FROM ".COUNTRY_TABLE." c".
        " INNER JOIN ".USERS_RENT_LOCATION_TABLE." p ON p.id_country = c.id ".
        " INNER JOIN ".RENT_ADS_TABLE." a ON a.id = p.id_ad WHERE a.parent_id<>0 AND a.type = 2";
    
    $rs = $dbconn->Execute($strSQL);
    $i = 0;
	while(!$rs->EOF) {
	   $row = $rs->GetRowAssoc(false);
       $tours_country[$i] = $row; 
       
       $rs->MoveNext();
       $i++;
    }
    $smarty->assign('tours_country', $tours_country);
    
    if($data['tours_country'])
    {
        $smarty->assign('tours_country_id', $data['tours_country']);
    }
    
    $strSQL = "SELECT p.hotel FROM ".USERS_RENT_PAYS_TABLE." p ".
        " INNER JOIN ".RENT_ADS_TABLE." a ON a.id = p.id_ad WHERE a.parent_id<>0 AND a.type = 2";
    
    $rs = $dbconn->Execute($strSQL);
    $i = 0;
	while(!$rs->EOF) {
	   $row = $rs->GetRowAssoc(false);
       $tours_country[$i] = $row; 
       
       $rs->MoveNext();
       $i++;
    }
    $smarty->assign('tours_hotel', $tours_hotel);
    
    if($data['tours_hotel'])
    {
        $smarty->assign('tours_hotel_id', $data['tours_hotel']);
    }
    return;
}

function getHot()
{
    global $dbconn;
    $settings = GetSiteSettings();
    $strSQL = "SELECT * FROM ".RENT_ADS_TABLE." a INNER JOIN ".USERS_RENT_PAYS_TABLE." p ON a.id = p.id_ad WHERE a.parent_id=0 AND p.is_hot = 1";
    if(isset($_REQUEST['choise']))
    {
        $strSQL .= " AND a.type='{$_REQUEST['choise']}'";
    }

    $rs = $dbconn->Execute($strSQL);
    $i = 0;
	while(!$rs->EOF) {
	   $row = $rs->GetRowAssoc(false);
       $hot[$i][id] = $row[id]; 
       $hot[$i][headline] = $row[headline];  
       $hot[$i][id_type] = $row[type];
       //$hot[$i] = $row; 
       $hot[$i]["viewprofile_link"] = "./viewprofile.php?id=".$hot[$i][id];    
        if($hot[$i][id_type] == '1') {
            $strSQL_payment = "SELECT * FROM ".USERS_RENT_PAYS_TABLE_BY_MONTH." a WHERE id_ad in (".
                "SELECT id FROM ".RENT_ADS_TABLE." WHERE id ='{$row['id']}' OR parent_id ='{$row['id']}')";
            $priceRS = $dbconn->Execute($strSQL_payment);
            $prices = array();
            if($priceRS) {
                while(!$priceRS->EOF) {
                    $priceRow = $priceRS->GetRowAssoc(false);
                    $pr = array($priceRow['january'], $priceRow['february'], $priceRow['march'], 
                        $priceRow['april'], $priceRow['may'], $priceRow['june'], $priceRow['july'], 
                        $priceRow['august'], $priceRow['september'], $priceRow['october'], 
                        $priceRow['november'], $priceRow['december']);
                    $prices = array_merge($prices, $pr);
                    $priceRS->MoveNext();
                }
                foreach($prices as $k=>$p)
                {
                    if($p == 0) unset($prices[$k]);
                }
                if(count($prices)) {
                    $hot[$i]["min_payment"] = PaymentFormat(min($prices));
                    $hot[$i]["show_from"] = count($prices > 1) ? 1 : 0;
                }
            }
        }
        elseif(!$row["min_payment"]) {
            $strSQL_payment = "SELECT min_payment as price FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad IN(SELECT id FROM ".RENT_ADS_TABLE." WHERE parent_id = '{$row['id']}')";
            $priceRS = $dbconn->Execute($strSQL_payment);
            $prices = array();
            if($priceRS) {
                while(!$priceRS->EOF) {
                    $priceRow = $priceRS->GetRowAssoc(false);
                    $prices[] = $priceRow['price'];
                    $priceRS->MoveNext();
                }
                if(count($prices)){
                    $hot[$i]["min_payment"] = PaymentFormat(min($prices));
                    $hot[$i]["show_from"] = count($prices > 1) ? 1 : 0;
                }
            }
        }
		$strSQL2 = "SELECT upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." ".
				   "WHERE id_ad='".$row["id"]."' AND upload_type='f' AND status='1' AND admin_approve='1' ".
				   "ORDER BY sequence ASC LIMIT 1";
		$rs2 = $dbconn->Execute($strSQL2);

		if (strlen($row["slide_path"])>1){
			$hot[$i]["image"] = $settings["photo_folder"]."/".$row["slide_path"];
			$hot[$i]["alt"] = $lang["default_select"]["slideshow"];
			$hot[$i]["slideshowed"] = 1;
		} elseif ($rs2->RowCount() > 0){
			$img = $rs2->GetRowAssoc(false);
			$hot[$i]["image"] = $settings["photo_folder"]."/thumb_".$img["upload_path"];
			$hot[$i]["alt"] = $img["user_comment"];
		} else {
			$used_references = array("gender");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserGenderIds($arr["spr_user_table"], $search_result[$i]["id_user"], 0, $arr["val_table"]);
					$search_result[$i][$arr["key"]] = $name;
				}
			}
			$gender_info = getDefaultUserIcon($hot[$i]["user_type"], $hot[$i]["gender"]);
			$hot[$i]["num_gender"] =  $gender_info["num_gender"];
			$hot[$i]["image"] =  $settings["photo_folder"]."/".$gender_info["icon_name"];
			$hot[$i]["alt"] =  $gender_info["icon_alt"];
		}
       $rs->MoveNext();
       $i++;
    }
    
    return $hot;
}

/**
 * Get site settings from SETTINGS_TABLE
 *
 * @param mixed (array or string) $set_arr
 * @return mixed (array or string)
 */
function GetSiteSettings($set_arr=""){
	global $dbconn, $smarty, $config;
	// array
	if($set_arr != ""  &&  is_array($set_arr) && count($set_arr)>0 ){
		foreach($set_arr as $key => $set_name){
			$set_arr[$key] = "'".$set_name."'";
		}
		$sett_string = implode(", ", $set_arr);
		$str_sql = "Select value, name from ".SETTINGS_TABLE." where name in (".$sett_string.")";
		$rs = $dbconn->Execute($str_sql);
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$settings[$row["name"]] = $row["value"];
			$rs->MoveNext();
		}
	}elseif(strlen($set_arr)>0){
		$str_sql = "Select value, name from ".SETTINGS_TABLE." where name = '".strval($set_arr)."'";
		$rs = $dbconn->Execute($str_sql);
		$row = $rs->GetRowAssoc(false);
		$settings = $row["value"];
	}elseif(strval($set_arr)==""){
		$str_sql = "Select value, name from ".SETTINGS_TABLE." order by id";
		$rs = $dbconn->Execute($str_sql);
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$settings[$row["name"]] = $row["value"];
			$rs->MoveNext();
		}
	}
	return $settings;
}

/**
 * Return array with links for page viewing of array (for example, array with search results)
 *
 * @param integer $num_records - array size
 * @param integer $page - current page
 * @param string $param - parametres to link
 * @param integer $max_record - number per page
 * @param array $dop_param
 * @return array
 */
function GetLinkArray($num_records, $page, $param, $max_record, $dop_param=""){
	/// settings
	$dop_param["page_var_name"] = (!isset($dop_param["page_var_name"]) && empty($dop_param["page_var_name"])) ? "page" : $dop_param["page_var_name"];
	$dop_param["left_arrow_name"] = (!isset($dop_param["left_arrow_name"]) && empty($dop_param["left_arrow_name"])) ? "..." : $dop_param["left_arrow_name"];
	$dop_param["right_arrow_name"] = (!isset($dop_param["right_arrow_name"]) && empty($dop_param["right_arrow_name"])) ? "..." : $dop_param["right_arrow_name"];

	$num_page = ceil($num_records/$max_record);
	if($num_page<2){
		return array();
	}
	$p_page_count = 10;
	$p_page = floor(($page-1)/$p_page_count);
	$j = 0;

	if($p_page>0){
		$ret_links[$j]["name"] = $dop_param["left_arrow_name"];
		$ret_links[$j]["link"] = $param."".$dop_param["page_var_name"]."=".($p_page*$p_page_count);
		$ret_links[$j]["selected"] = 0;
		$j++;
	}

	$top_limit = ((($p_page+1)*$p_page_count+1)<=$num_page)?(($p_page+1)*$p_page_count+1):$num_page+1;
	for($i=($p_page*$p_page_count+1);$i<$top_limit;$i++){
		$ret_links[$j]["name"] = $i;
		$ret_links[$j]["link"] = $param."".$dop_param["page_var_name"]."=".$i;
		$ret_links[$j]["selected"] = ($i == $page)?1:0;
		$j++;
	}
	if( (($p_page+1)*$p_page_count) < $num_page){
		$ret_links[$j]["name"] = $dop_param["right_arrow_name"];
		$ret_links[$j]["link"] = $param."".$dop_param["page_var_name"]."=".(($p_page+1)*$p_page_count+1);
		$ret_links[$j]["selected"] =0;
		$j++;
	}
	return $ret_links;
}

//---------------------- Select Functions ------------------------//

/**
 * Get array with possible for days numbers
 *
 * @param integer $day_active - active day in array (selected in <select>)
 * @return array
 */
function GetDaySelect($day_active=''){
	for($i=0;$i<31;$i++){
		$day[$i]["value"] = $i+1;
		if(intval($day_active) == $i+1)
			$day[$i]["sel"] = 1;
		else
			$day[$i]["sel"] = 0;
	}
	return $day;
}

/**
 * Get array with possible month
 *
 * @param integer $month_active - active month in array (selected in <select>)
 * @param integer $month_first - first month in array
 * @return array
 */
function GetMonthSelect($month_active='', $month_first=''){

	$month_name = GetMonth();
	$start_month = $month_first ? $month_first : 1;
	for($i = $start_month; $i < 13; $i++){
		$month[$i]["value"] = $i;
		$month[$i]["name"] = $month_name[$month[$i]["value"]];
		if(intval($month_active) == $i)
		$month[$i]["sel"] = 1;
		else
		$month[$i]["sel"] = 0;
	}
	if ( sizeof($month) <6 ) {
		$finish_month = 6 - sizeof($month);
		for ($i = 1; $i < $finish_month; $i++) {
			$month[$i+12]["value"] = $i+12;
			$month[$i+12]["name"] = $month_name[$i];
		}
	}

	return $month;
}

/**
 * Get array with year numbers
 *
 * @param integer $year_active - active year in array (selected in <select>)
 * @param integer $year_limit - year elements in array
 * @param integer $year_count - first year in array
 * @return array
 */
function GetYearSelect($year_active="", $year_limit='', $year_count=''){
	for($i=0;$i<$year_limit;$i++){
		$y = $year_count-$year_limit+$i+1;
		$year[$i]["value"] = $y;
		if(intval($year_active) == $y)
		$year[$i]["sel"] = 1;
		else
		$year[$i]["sel"] = 0;
	}
	return $year;
}

/**
 * Get array with possible hours' numbers
 *
 * @param integer $sel_hour - active hour in array (selected in <select>)
 * @return array
 */
function GetHourSelect($sel_hour=""){
	for($i=0; $i<24; $i++){
		$hour[$i]["value"] = sprintf("%02d",$i);
		if($sel_hour == $i)
		$hour[$i]["sel"] = 1;
		else
		$hour[$i]["sel"] = 0;
	}
	return $hour;
}

/**
 * Get array with possible minutes' numbers
 *
 * @param integer $sel_min - active minute in array (selected in <select>)
 * @return array
 */
function GetMinSelect($sel_min=""){
	for($i=0; $i<60; $i++){
		$min[$i]["value"] = sprintf("%02d",$i);
		if($sel_min == $i)
		$min[$i]["sel"] = 1;
		else
		$min[$i]["sel"] = 0;
	}
	return $min;
}

//---------------------- 'slash' Functions ------------------------//
function AfterLastSlash($str){
	$arr = explode("/", $str);
	return $arr[count($arr)-1];
}

function DelFirstSlash($str){
	$str = strval($str);
	if($str[0]=="/")
	return substr($str,1);
	else
	return $str;
}
function DelLastSlash($str){
	$str = strval($str);
	if($str[strlen($str)-1]=="/")
	return substr($str,0,-1);
	else
	return $str;
}
function TrimSlash($str){
	return DelFirstSlash(DelLastSlash(strval($str)));
}
function Rep_Slashes($str){
	$str = stripslashes($str);
	$str = str_replace("\"", "&quot;", $str);
	$str = str_replace("'", "&#039;", $str);
	return  $str;
}

//---------------------- Filters Functions ------------------------//
function FormFilter($str){
	$str = trim(strval($str));
	$str = stripslashes($str);
	$str = htmlspecialchars($str);
	$str = str_replace("\"", "&quot;", $str);
	$str = str_replace("'", "&#039;", $str);
	return  $str;
}

function LoginFilter($str){
	if(strlen($str)<3 || strlen($str)>12 ){
		//-a-zA-Z0-9_/ - letters, numbers and '/'
		GetErrors("wrong_login");
		return true;
	} else {
		return false;
	}

}
function EmailFilter($str){
	if (!(strlen($str)>0) || (!eregi('^.+@.+\\..+$', $str))){
		GetErrors("wrong_email");
		return true;
	} else {
		return false;
	}
}
function PasswFilter($str){
	if (strlen($str)<4 || strlen($str)>10 || (!eregi("^[-a-zA-Z0-9_/]*$", $str))){
		GetErrors("wrong_pass");
		return true;
	} else {
		return false;
	}
}

//---------------------- User Interface Functions ------------------------//

/**
 * Get active ads count
 *
 * @return integer
 */
function GetAdsCount(){
	global $dbconn, $smarty, $config;

	$strSQL = "SELECT COUNT(*) FROM ".RENT_ADS_TABLE." WHERE status='1'";
	$rs = $dbconn->Execute ($strSQL);
	$count_ads["rent"] = $rs->fields[0];
	return $count_ads;
}

function GetFeatureRentAd($id_ad){
	global $config, $smarty, $dbconn, $user, $REFERENCES;
	$settings = GetSiteSettings();
	$smarty->assign("use_sold_leased_status", $settings["use_sold_leased_status"]);
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));
	if ($id_ad>0){

		$strSQL = "	SELECT DISTINCT ra.id, ra.id_user, DATE_FORMAT(ra.movedate,'".$config["date_format"]."') as movedate, ra.type, ra.people_count, ra.sold_leased_status, ra.headline, sp.status as spstatus ,
				u.fname, u.phone,
				urp.min_payment, urp.max_payment, urp.auction, uru.upload_path,
				ct.name as country_name, rt.name as region_name, cit.name as city_name,
				hlt.id_friend, blt.id_enemy, ra.upload_path as slide_path, tsat.type as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end, ft.id as featured
				FROM ".RENT_ADS_TABLE." ra
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=ra.id AND tsat.type='1'
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id
				LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
				LEFT JOIN ".USERS_RENT_PAYS_TABLE." urp ON urp.id_ad=ra.id
				LEFT JOIN ".USERS_RENT_UPLOADS_TABLE." uru ON uru.id_ad=ra.id AND uru.upload_type='f' AND uru.status='1' AND uru.admin_approve='1'
				LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country
				LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
				LEFT JOIN ".HOTLIST_TABLE." hlt on ra.id_user=hlt.id_friend and hlt.id_user='".$user[0]."'
				LEFT JOIN ".BLACKLIST_TABLE." blt on ra.id_user=blt.id_enemy and blt.id_user='".$user[0]."'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=ra.id
				WHERE ra.id='".$id_ad."' GROUP BY ra.id " ;
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$row = $rs->GetRowAssoc(false);
			$featured_ad["number"] = ($page-1)*$search_numpage+($i+1);
			$featured_ad["id_ad"] = $row["id"];
			$featured_ad["people_count"] = $row["people_count"];
			$featured_ad["id_user"] = $row["id_user"];
			$featured_ad["movedate"] = $row["movedate"];
			$featured_ad["id_type"] = $row["type"];
			$featured_ad["sold_leased_status"] = $row["sold_leased_status"];
			if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
				$featured_ad["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
			} else {
			$featured_ad["headline"] = stripslashes($row["headline"]);
			}
			
			if ($featured_ad["id_type"] == 2){			
				$calendar_event = new CalendarEvent();
				$featured_ad["reserve"] = $calendar_event->GetEmptyPeriod($featured_ad["id_ad"], $featured_ad["id_user"]);		
			}
			
			$featured_ad["issponsor"] = $row["spstatus"];
			$featured_ad["topsearched"] = $row["topsearched"];
			if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
				$featured_ad["show_topsearch_icon"] = true;
				$featured_ad["topsearch_date_begin"] = $row["topsearch_date_begin"];
			}
			$featured_ad["login"] = $row["fname"];
			$featured_ad["phone"] = $row["phone"];
			$featured_ad["featured"] = $row["featured"];

			$lang_ad = 2; //т.к. выводим информацию о том, что ищет человек
			$used_references = array("gender", "realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $featured_ad["id_user"], $featured_ad["id_ad"], $arr["val_table"], $lang_ad);
					if (count($name) == 0 && $arr["spr_match_table"] != ""){
						$name = GetUserAdSprValues($arr["spr_match_table"], $featured_ad["id_user"], $featured_ad["id_ad"], $arr["val_table"], $lang_ad);
					}
					$featured_ad[$arr["key"]] = implode(",", $name);
				}
			}

			$featured_ad["max_payment"] = PaymentFormat($row["max_payment"]);
			$featured_ad["max_payment_show"] = FormatPrice($featured_ad["max_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$featured_ad["min_payment"] = PaymentFormat($row["min_payment"]);
			$featured_ad["min_payment_show"] = FormatPrice($featured_ad["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$featured_ad["auction"] = $row["auction"];

			if (strlen($row["slide_path"])>1){
				$featured_ad["slideshowed"] = 1;
				$featured_ad["image"] = $settings["photo_folder"]."/".$row["slide_path"];
			} else {
				if (strlen($row["upload_path"])>1){
					$featured_ad["image"] = $settings["photo_folder"]."/thumb_".$row["upload_path"];
				} else {
					$featured_ad["image"] = $settings["photo_folder"]."/".$settings["default_photo"];
				}
			}
			$featured_ad["viewprofile_link"] = "./viewprofile.php?id=".$featured_ad["id_ad"];

			if ($config["lang_ident"]!='ru') {
				$featured_ad["country_name"] = RusToTranslit($row["country_name"]);
				$featured_ad["region_name"] = RusToTranslit($row["region_name"]);
				$featured_ad["city_name"] = RusToTranslit($row["city_name"]);
			} else {
				$featured_ad["country_name"] = $row["country_name"];
				$featured_ad["region_name"] = $row["region_name"];
				$featured_ad["city_name"] = $row["city_name"];
			}
			$suffix = "&amp;id=".$featured_ad["id_user"]."&amp;section=rent&amp;id_ad=".$featured_ad["id_ad"];
			$featured_ad["mail_link"] = "./mailbox.php?sel=fs".$suffix;
			$featured_ad["contact_link"] = "./contact.php?sel=fs".$suffix;
			$featured_ad["interest_link"] = "./viewprofile.php?sel=interest".$suffix;


			if(intval($row["id_friend"])==0 && intval($row["id_enemy"])==0) {
				$featured_ad["addfriend_link"] = "./viewprofile.php?sel=addtohot".$suffix;
				$featured_ad["blacklist_link"] = "./viewprofile.php?sel=addtoblack".$suffix;
			}
			return $featured_ad;
		} else {
			return;
		}
	} else {
		return;
	}
}

function GetFeatureInfo($id_region, $par, $choise=''){
	global $config, $smarty, $dbconn, $user;

	/*
	returns id_ad, id_user, featured headilne, featured period
	*/
	if ( (strlen($id_region)>0) && (strlen($par)>0) ){
		switch ($par){
			case "rent":
				$type = 1;
				break;
			case "room":
				$type = 2;
				break;
			default:
				return;
				break;
		}
		$strSQL  = "SELECT f.id, f.id_user, f.id_ad, f.headline, UNIX_TIMESTAMP(f.date_featured) as time_featured, f.curr_count, f.id_region, ra.type, ra.headline AS headline2  
					FROM ".FEATURED_TABLE." f						
					LEFT JOIN ".USERS_TABLE." ut ON ut.id=f.id_user
					LEFT JOIN ".REGION_TABLE." rt ON rt.id=f.id_region
					LEFT JOIN ".RENT_ADS_TABLE." ra ON ra.id=f.id_ad						
					WHERE f.type='".$type."' AND ra.type='$choise' AND rt.id='".$id_region."'
					GROUP BY id";
		
		$rs = $dbconn->Execute($strSQL);
		if (!$rs->EOF){				
			$row = $rs->GetRowAssoc(false);
			$featured["id"] = $row["id"];
			$featured["id_user"] = $row["id_user"];
			$featured["id_ad"] = $row["id_ad"];
			$featured["headline"] = stripslashes($row["headline"]);
			$featured["headline2"] = stripslashes($row["headline2"]);			
			$featured["time_featured"] = $row["time_featured"];
			$diff = time() - $featured["time_featured"];
			$featured["period"]["days"] = intval( $diff/(60*60*24) );
			$featured["period"]["hours"] = intval( ( $diff-(60*60*24*$featured["period"]["days"]) )/(60*60) );
			$featured["period"]["minutes"] = intval( ($diff-(60*60*24*$featured["period"]["days"])-(60*60*$featured["period"]["hours"]))/(60) );
			$featured["period"]["seconds"] = intval($diff-(60*60*24*$featured["period"]["days"])-(60*60*$featured["period"]["hours"])-60*$featured["period"]["minutes"]);
			$featured["curr_count"] = $row["curr_count"];	
			
			return $featured;			
		}
	}
	return;
}

/**
 * Get Age from birthday date
 *
 * @param sting $date - datetime
 * @return integer
 */
function AgeFromBDate($date){
	///// date in Y-m-d h:i:s format
	$year = intval(substr($date,0,4));
	$month = intval(substr($date,5,2));
	$day = intval(substr($date,8,2));
	$n_year = date("Y");
	$n_month = date("m");
	$n_day = date("d");
	if ($month==$n_month) {
		if ($day>$n_day) {
			$new_age = floor(($n_year - $year)+($n_month - $month-1)/12);
		} else {
			$new_age = floor(($n_year - $year) + ($n_month - $month)/12);
		}
	} else {
		$new_age = floor(($n_year - $year) + ($n_month - $month)/12);
	}
	return $new_age;
}

/**
 * Save reference values for the listing with $id_ad
 *
 * @param string $table - references user defined values
 * @param interes $id_ad
 * @param array $spr -reference keys
 * @param array $values - reference values
 * @return void
 */
function SprTableEdit($table, $id_ad, $spr, $values){
	global $dbconn, $user;

	$dbconn->Execute("DELETE FROM ".$table." WHERE id_ad='".$id_ad."'");
	for($i=0; $i<count($spr); $i++){
		if (isset($values[$i])) {
			$val_cnt = count($values[$i]);
			for($j=0; $j<$val_cnt; $j++){
				if ($values[$i][$j]>0) {
					$dbconn->Execute("	INSERT INTO ".$table." (id_ad, id_user, id_spr, id_value)
										VALUES ('".$id_ad."', '".$user[0]."', '".$spr[$i]."', '".$values[$i][$j]."')");								
				}
			}
		}	
	}
	return;
}

function SprTableEditAdmin($table, $id_ad, $spr, $values){
	global $dbconn, $user;

	$dbconn->Execute("DELETE FROM ".$table." WHERE id_ad='".$id_ad."'");
	for($i=0; $i<count($spr); $i++){
		for($j=0; $j<count($values[$i]); $j++){
			if ($values[$i][$j]>0) {
				$dbconn->Execute("	INSERT INTO ".$table." (id_ad, id_user, id_spr, id_value)
									VALUES ('".$id_ad."', '1', '".$spr[$i]."', '".$values[$i][$j]."')");
			}
		}
	}
	return;
}

/**
 * Edit saved search with $id_save (search references values)
 *
 * @param string $table - table name
 * @param integer $id_save
 * @param array $spr
 * @param array $values
 * @return void
 */
function SearchSprEdit($table, $id_save, $spr, $values){
	global $dbconn, $user;

	for ($i=0; $i<count($spr); $i++){
		if (isset($values[$i])) {
			$val_cnt = count($values[$i]);
			for ($j = 0; $j < $val_cnt; $j++) {
				$strSQL = "INSERT INTO ".$table." (id_save, id_spr, id_info) ".
						  "VALUES ('".$id_save."', '".$spr[$i]."', '".$values[$i][$j]."')";
				$dbconn->Execute($strSQL);
			}
		}	
	}
	return;
}

/**
 * Get back data when no results were found for user defined search criteria
 *
 * @param array $data
 * @return array
 */
function GetBackData($data='') {	
	$out_arr = array();
 	foreach ($data as $_arr1) {
 		if (is_array($_arr1)) {
	 		foreach ($_arr1 as $value){
	 			array_push($out_arr, $value);
	 		}
 		}	
 	}
 	return $out_arr;
}

/**
 * Get references search array
 *
 * @param string $spr_table - reference table
 * @param string $value_table - references values table
 * @param array $data - array with already defined (checked/selected) search
 * 						values for references
 * @param integer $lang_add
 * @return array
 */
function GetRefSearchArray($spr_table, $value_table, $data='', $lang_add = 1){
	global $smarty, $config, $dbconn, $user, $multi_lang;
	$_spr = $multi_lang->TableKey($spr_table);
	$_val = $multi_lang->TableKey($value_table);

	$field_name = $multi_lang->DefaultFieldName($lang_add);

	$strSQL = "	SELECT DISTINCT a.id, b.".$field_name." as name, a.type, a.des_type, a.visible_in
				FROM ".$spr_table." a
				LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_spr."' AND b.id_reference=a.id
				WHERE 1 order by a.sorter ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF){
		$arr[$i]["id"] = $rs->fields[0];
		$arr[$i]["id_spr"] = $_spr;
		$arr[$i]["name"] = $rs->fields[1];
		$arr[$i]["type"] = $rs->fields[2];
		$arr[$i]["des_type"] = $rs->fields[3];
		$arr[$i]["visible_in"] = $rs->fields[4];
		$arr[$i]["num"] = $i;

		$strSQL_opt = "	SELECT DISTINCT a.id, b.".$field_name." as name
						FROM ".$value_table." a
						LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_val."' and b.id_reference=a.id
						WHERE a.id_spr='".$rs->fields[0]."' ORDER BY ";
		/**
		 * there are only integer values in SPR_DESCRIPTION_TABLE, so sort by id
		 */
		$strSQL_opt .= ($spr_table == SPR_DESCRIPTION_TABLE || $spr_table == SPR_THEME_REST_TABLE) ? "id" : "name";

		$rs_opt = $dbconn->Execute($strSQL_opt);
		$j = 0;
		while(!$rs_opt ->EOF){
			$arr[$i]["opt"][$j]["value"] = $rs_opt->fields[0];
			$arr[$i]["opt"][$j]["name"] = $rs_opt->fields[1];
			if(isset($data[$_spr]) && sizeof($data[$_spr])>0){
				if ( in_array($arr[$i]["opt"][$j]["value"], $data[$_spr]) ){
					$arr[$i]["opt"][$j]["sel"] = 1;
				}
			}

			$rs_opt->MoveNext();
			$j++;
		}
		$rs->MoveNext();
		$i++;
	}
	return $arr;
}

/**
 * Get an array with subreferences values id-s
 *
 * @param string $spr_table
 * @param string $value_table
 * @param integer $subref_id - subreference id
 * @param string $lang_add
 * @return array
 */
function GetRefQuickSearchArray($spr_table, $value_table, $subref_id, $lang_add = 1){
	global $smarty, $config, $dbconn, $user, $multi_lang;
	$_spr = $multi_lang->TableKey($spr_table);
	$_val = $multi_lang->TableKey($value_table);

	$field_name = $multi_lang->DefaultFieldName($lang_add);

	$strSQL_opt = "	SELECT DISTINCT a.id
					FROM ".$value_table." a
					LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_val."' and b.id_reference=a.id
					WHERE a.id_spr='".$subref_id."' ORDER BY id";
	$rs_opt = $dbconn->Execute($strSQL_opt);
	while(!$rs_opt ->EOF){
		$arr[] = $rs_opt->fields[0];
		$rs_opt->MoveNext();
	}
	return $arr;
}

/**
 * Get reference array with selected user defined values if not empty $data array
 *
 * @param string $spr_table - reference table
 * @param string $value_table - subreferences' values table
 * @param string $area - key from $REFERENCES array
 * @param array $data - array of user defined references values
 * @param string $lang_add
 * @param string $order_by
 * @return array
 */
function GetReferenceArray($spr_table, $value_table, $area, $data='', $lang_add='', $order_by = 'name'){
	global $smarty, $config, $dbconn, $user, $multi_lang;

	$_spr = $multi_lang->TableKey($spr_table);
	$_val = $multi_lang->TableKey($value_table);

	$field_name = $multi_lang->DefaultFieldName($lang_add);

	$strSQL = "	SELECT DISTINCT a.id, b.".$field_name." as name, a.type, a.des_type, a.visible_in
				FROM ".$spr_table." a
				LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_spr."' AND b.id_reference=a.id
				WHERE 1 ORDER by a.sorter ";

	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	$arr = array();
	while(!$rs->EOF){
		$arr[$i]["id"] = $rs->fields[0];
		$arr[$i]["name"] = $rs->fields[1];
		$arr[$i]["type"] = $rs->fields[2];
		$arr[$i]["des_type"] = $rs->fields[3];
		$arr[$i]["visible_in"] = $rs->fields[4];
		$arr[$i]["num"] = $i;

		$strSQL_opt = "	SELECT DISTINCT a.id, b.".$field_name." as name
						FROM ".$value_table." a
						LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_val."' and b.id_reference=a.id
						WHERE a.id_spr='".$rs->fields[0]."'
						ORDER BY $order_by";

		$rs_opt = $dbconn->Execute($strSQL_opt);
		$j = 0;
		while(!$rs_opt ->EOF){
			$arr[$i]["opt"][$j]["value"] = $rs_opt->fields[0];
			$arr[$i]["opt"][$j]["name"] = $rs_opt->fields[1];

			if( isset($data[$area][$i]) && (is_array($data[$area][$i])) ){
				if ( in_array($arr[$i]["opt"][$j]["value"],  $data[$area][$i]) ){
					$arr[$i]["opt"][$j]["sel"] = 1;
				}
			}
			$rs_opt->MoveNext();
			$j++;
		}
		$rs->MoveNext();
		$i++;
	}

	return $arr;
}

/**
 * Get user defined references' values
 *
 * @param string $table - table with user defined references' values
 * @param integer $id_ad
 * @param integer $id_user
 * @param string $spr_table - reference table
 * @return array
 */
function SprTableSelect($table, $id_ad, $id_user, $spr_table=''){
	global $smarty, $config, $dbconn, $user;

	$strSQL = "	SELECT DISTINCT spr.id, ust.id_spr as id_spr
				FROM ".$spr_table." spr
				LEFT JOIN ".$table." ust ON (id_ad='".$id_ad."' AND id_user='".$id_user."' AND spr.id=ust.id_spr)
				WHERE 1 GROUP BY spr.id ORDER BY spr.sorter";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	$arr = array();
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$strSQL_opt = "SELECT id_value FROM ".$table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' AND id_spr='".$row["id_spr"]."' ORDER BY id_value";
		$rs_opt = $dbconn->Execute($strSQL_opt);
		$j = 0;
		while(!$rs_opt->EOF){
			$row_opt = $rs_opt->GetRowAssoc(false);
			$arr[$i][$j] = $row_opt["id_value"];
			$rs_opt->MoveNext();
			$j++;
		}
		$rs->MoveNext();
		$i++;
	}
	return $arr;
}

/**
 * Get translit fot russian symbols
 *
 * @param string $string
 * @return string
 */
function RusToTranslit( $string ){
	if ( $string == 'Р РѕСЃСЃРёСЏ') {
		return "Russia";
	} else {
		$translit = array();
		$translit['Р°'] = "a";
		$translit['Р±'] = "b";
		$translit['РІ'] = "v";
		$translit['Рі'] = "g";
		$translit['Рґ'] = "d";
		$translit['Рµ'] = "e";
		$translit['С‘'] = "yo";
		$translit['Р¶'] = "j";
		$translit['Р·'] = "z";
		$translit['Рё'] = "i";
		$translit['Р№'] = "i";
		$translit['Рє'] = "k";
		$translit['Р»'] = "l";
		$translit['Рј'] = "m";
		$translit['РЅ'] = "n";
		$translit['Рѕ'] = "o";
		$translit['Рї'] = "p";
		$translit['СЂ'] = "r";
		$translit['СЃ'] = "s";
		$translit['С‚'] = "t";
		$translit['Сѓ'] = "u";
		$translit['С„'] = "f";
		$translit['С…'] = "kh";
		$translit['С†'] = "ts";
		$translit['С‡'] = "tch";
		$translit['С‰'] = "sh";
		$translit['С€'] = "sh";
		$translit['С‹'] = "i";
		$translit['СЌ'] = "e";
		$translit['СЋ'] = "yu";
		$translit['СЏ'] = "ya";
		$translit['СЉ'] = "";
		$translit['СЊ'] = "";

		$translit['Рђ'] = "A";
		$translit['Р‘'] = "B";
		$translit['Р’'] = "V";
		$translit['Р“'] = "G";
		$translit['Р”'] = "D";
		$translit['Р•'] = "E";
		$translit['РЃ'] = "Yo";
		$translit['Р–'] = "J";
		$translit['Р—'] = "Z";
		$translit['пїЅ?'] = "I";
		$translit['Р™'] = "Y";
		$translit['Рљ'] = "K";
		$translit['Р›'] = "L";
		$translit['Рњ'] = "M";
		$translit['Рќ'] = "N";
		$translit['Рћ'] = "O";
		$translit['Рџ'] = "P";
		$translit['Р '] = "R";
		$translit['РЎ'] = "S";
		$translit['Рў'] = "T";
		$translit['РЈ'] = "U";
		$translit['Р¤'] = "F";
		$translit['РҐ'] = "Kh";
		$translit['Р¦'] = "Ts";
		$translit['Р§'] = "Ch";
		$translit['Р©'] = "Sh";
		$translit['РЁ'] = "Sh";
		$translit['Р«'] = "I";
		$translit['Р­'] = "E";
		$translit['Р®'] = "Yu";
		$translit['РЇ'] = "Ya";
		$translit['РЄ'] = "";
		$translit['Р¬'] = "";
		$result = "";
		$result = strtr( $string, $translit );
		return $result;
	}
}

/**
 * Get user own gender (from profile for user with $id_user), or user defined
 * gender in listing with $id_add
 *
 * @param array $user_table - user defined values fo reference array
 * @param integer $id_user
 * @param integer $id_ad - if $id_ad==0, it is a reference value for the
 * 						   user with $id_user (values from users' profile)
 * @param array $value_table - reference values array
 * @return array
 */
function GetUserGenderIds($user_table, $id_user, $id_ad, $value_table){
	global $smarty, $config, $dbconn, $user;

	$name = array();
	$strSQL = "SELECT DISTINCT id_spr FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ORDER BY id_spr";
	$rs = $dbconn->Execute($strSQL);
	//only one reference assigned to gender
	$row = $rs->GetRowAssoc(false);
	$strSQL_opt = "SELECT id_value FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' AND id_spr='".$row["id_spr"]."' ORDER BY id_value";
	$rs_opt = $dbconn->Execute($strSQL_opt);
	$i = 0;
	while(!$rs_opt->EOF){
		$row_opt = $rs_opt->GetRowAssoc(false);
		$name[$i] = $row_opt["id_value"];
		$rs_opt->MoveNext();
		$i++;
	}
	return $name;
}

/**
 * function is an analog to GetUserGender, but uses $lang_add
 *
 * @param array $user_table
 * @param integer $id_user
 * @param integer $id_ad
 * @param array $value_table
 * @param integer $lang_add
 * @return array
 */
function GetUserAdSprValues($user_table, $id_user, $id_ad, $value_table, $lang_ad = 1){
	global $smarty, $config, $dbconn, $user, $multi_lang;

	$_val = $multi_lang->TableKey($value_table);

	$field_name = $multi_lang->DefaultFieldName($lang_ad);

	$strSQL = "SELECT DISTINCT id_spr FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ORDER BY id_spr";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;	
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$strSQL_opt = "SELECT id_value FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' AND id_spr='".$row["id_spr"]."' ORDER BY id_value";
		$rs_opt = $dbconn->Execute($strSQL_opt);
		$j = 0;
		while(!$rs_opt->EOF){
			$row_opt = $rs_opt->GetRowAssoc(false);
			$arr[$i][$j] = $row_opt["id_value"];
			$strSQL_name = "SELECT ".$field_name." as name
							FROM ".REFERENCE_LANG_TABLE."
							WHERE table_key='".$_val."' AND id_reference='".$arr[$i][$j]."' ";
			$rs_name = $dbconn->Execute($strSQL_name);

			$name[$i][$j] = $rs_name->fields[0];
			$rs_opt->MoveNext();
			$j++;
		}
		$rs->MoveNext();
		$i++;
	}
	return isset($name[0]) ? $name[0] : array();
}

/**
 * Return percent of matching $arr_name to $user_val
 *
 * @param array $arr_name listing references values array
 * @param array $user_val search references values array (set in search form)
 * @return mixed (boolean or integer)
 */
function SearchMatches($arr_name, $user_val) {

	if (sizeof($arr_name) > 0) {
		$count = 0;
		foreach ($arr_name as $id_spr=>$val_arr) {
			$yes = 0;
			foreach ($val_arr as $id_val) {
				if (in_array($id_val, $user_val[$id_spr])){
					$yes = 1;
				}
			}
			if ($yes == 1){
				$count++;
			}
		}
		if ($count == sizeof($arr_name)) {
			return 100;
		} else {
			return (($count/sizeof($arr_name))*100);
		}
	} else {
		return false;
	}
}

/**
 * Return true, if all reference values, which were set for the listing are exist
 * in search array
 *
 * @param array $arr_name listing references values array
 * @param array $user_val search references values array
 * @return boolean
 */
function SearchPowers($arr_name, $user_val) {

	if (sizeof($arr_name) > 0) {
		$count = 0;
		foreach ($arr_name as $id_spr=>$val_arr) {
			$yes = 0;
			foreach ($val_arr as $id_val) {
				if (in_array($id_val, $user_val[$id_spr])){
					$yes = 1;
				}
			}
			if ($yes == 1){
				$count++;
			}
		}
		if ($count == sizeof($arr_name)){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function GetSearchUserArray ($spr, $values) {
	if ( sizeof($spr) ) {
		$out_arr = array();
		foreach ($spr as $i=>$id_spr) {
			$j = 0;
			foreach ($values[$i] as $id_value) {
				if (!empty($id_value)) {
					$out_arr[$id_spr][$j] = $id_value;
				}
				$j++;
			}
		}
		return $out_arr;
	} else {
		return false;
	}
}

/**
 * Refresh user account:
 * 1. update for payed services if their period has expired: make slideshow inactive
 * 2. update bids for featured listings (single out the listing in the region)
 * 3. refresh last seen date for user, get account info and new messages count
 *
 * @param array $user
 * @return void
 */
function RefreshAccount($user){
	global $smarty, $dbconn, $config;

	//update slideshow (service for 24 hours)
	$rs = $dbconn->Execute("SELECT upload_path FROM ".RENT_ADS_TABLE." WHERE date_slided<NOW() AND date_slided<>'0000-00-00 00:00:00'");
	if ($rs->RowCount()>0){
		while (!$rs->EOF) {
			unlink($config["site_path"]."/uploades/photo/".$rs->fields[0]);
			$rs->MoveNext();
		}
	}
	$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET upload_path='', date_slided='0000-00-00 00:00:00' WHERE date_slided<NOW()");

	$settings = GetSiteSettings(array("featured_in_region_cost", "featured_in_region_period"));
	//update featured table
	$strSQL = " SELECT DISTINCT id, UNIX_TIMESTAMP(datenow) as date_now, curr_count FROM ".FEATURED_TABLE." GROUP BY id";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0){
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			if ( ((time()-$row["date_now"])/(60*$settings["featured_in_region_period"]))>1 ) {
				$curr_count = 0;
				$upd_array[$i]["id"] = $row["id"];
				$upd_array[$i]["min"] = intval((time()-$row["date_now"])/(60*$settings["featured_in_region_period"]));
				$curr_count = $row["curr_count"] - $upd_array[$i]["min"]*$settings["featured_in_region_cost"];
				if ($curr_count<1){
					$curr_count = 1;
				}
				$dbconn->Execute("UPDATE ".FEATURED_TABLE." SET curr_count='".$curr_count."' WHERE id='".$upd_array[$i]["id"]."'");
			}
			$rs->MoveNext();
			$i++;
		}
	}
	//get site settings
	$rs = $dbconn->Execute("Select name, value from ".SETTINGS_TABLE." where name in ('site_unit_costunit', 'payment_type')");
	while (!$rs->EOF) {
		$settings[$rs->fields[0]] = $rs->fields[1];
		$rs->MoveNext();
	}
	$id_user = $user[0];
	$type = (isset($user[6])) ? $user[6] : '';

	if (isset($user[3]) && isset($user[4]) && $user[3]==0 && $user[4]==0) {
		$dbconn->Execute("UPDATE ".BILLING_USER_ACCOUNT_TABLE." SET date_refresh='".date("Y-m-d H:i:s")."' WHERE id_user='".$id_user."'");
		if ($type == 0) {	// user refresh page in first time (with login /pass)

		} elseif ($type == 1) {
			$strSQL = "SELECT account FROM ".BILLING_USER_ACCOUNT_TABLE." WHERE id_user='".$user[0]."' ";
			$rs = $dbconn->Execute($strSQL);
			$user_account = ($rs->RowCount() > 0) ? round($rs->fields[0], 2) : 0;
			$smarty->assign("user_account", $user_account);
		}

		$strSQL = "	SELECT 	UNIX_TIMESTAMP(a.date_end)-UNIX_TIMESTAMP(a.date_begin) AS all_res,
							UNIX_TIMESTAMP(a.date_end)-UNIX_TIMESTAMP('".date("Y-m-d H:i:s")."') AS now_res
					FROM ".BILLING_USER_PERIOD_TABLE." a
					WHERE a.id_user='".$user[0]."'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$now_rest = $row["now_res"];
		$all_rest = $row["all_res"];

		$strSQL = " SELECT gt.type
					FROM ".USER_GROUP_TABLE." ugt
					LEFT JOIN ".GROUPS_TABLE." gt ON gt.id=ugt.id_group
					WHERE ugt.id_user='".$user[0]."'";
		$rs = $dbconn->Execute($strSQL);
		$group_type = $rs->fields[0];
		if ($group_type == 'f'){
			$smarty->assign("group_type", 1);
		} else {
			$smarty->assign("group_type", 0);
		}

		if ($now_rest <= 0) {
			if ($group_type != 'd') {
				$strSQL = " SELECT id FROM ".GROUPS_TABLE." WHERE type='d' ";
				$rs = $dbconn->Execute($strSQL);
				$d_id = $rs->fields[0];
				$dbconn->Execute(" UPDATE ".USER_GROUP_TABLE." SET id_group='".$d_id."' WHERE id_user='".$user[0]."' ");
			}
			$dbconn->Execute(" DELETE FROM ".BILLING_USER_PERIOD_TABLE." WHERE id_user='".$user[0]."' ");
		}
		$strSQL = "SELECT COUNT(*) FROM ".MAILBOX_MESSAGES_TABLE." WHERE to_user_id = '".$user[0]."' AND seen='0' ";
		$rs = $dbconn->Execute($strSQL);
		$count = intval($rs->fields[0]);
		$config["inbox_new"] = $count;

		GetPeriodRest();
	}
	return;
}

/**
 * Get days number in group for the current user
 *
 * @return void
 */
function GetPeriodRest(){
	global $smarty, $config, $dbconn, $user;

	$strSQL = "SELECT UNIX_TIMESTAMP(date_end) FROM ".BILLING_USER_PERIOD_TABLE." WHERE id_user='".$user[0]."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0){
		$period_rest = round( (($rs->fields[0])-time())/(24*60*60) );
		if ($period_rest == 0){
			$period_rest = 1;
		}
		$smarty->assign('day_id', GetDayId($period_rest));
		$smarty->assign('period_rest', $period_rest);
	}
	return;
}

/**
 * Get countries array, if isset $country_id - get regions array,
 * if isset $region_id - get cities array, and assign results to smarty variables
 *
 * @param string $country_id
 * @param string $region_id
 * @return void
 */
function GetLocationContent($country_id='', $region_id='') {
	global $smarty, $config, $dbconn;

	$strSQL = "SELECT id, name FROM ".COUNTRY_TABLE." ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$i = 0;
		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
			$country[$i]["id"] = $row["id"];
			if ($config["lang_ident"]!='ru'){
				$country[$i]["name"] = RusToTranslit($row["name"]);
			} else {
				$country[$i]["name"] = $row["name"];
			}
			$rs->MoveNext();
			$i++;
		}
	}
	$smarty->assign("country", $country);

	if ($country_id){
		$smarty->assign("country_id", $country_id);
		$strSQL = "SELECT id, name FROM ".REGION_TABLE." WHERE id_country='".$country_id."' ORDER BY name";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$i = 0;
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$region[$i]["id"] = $row["id"];
				if ($config["lang_ident"]!='ru') {
					$region[$i]["name"] = RusToTranslit($row["name"]);
				} else {
					$region[$i]["name"] = $row["name"];
				}
				$rs->MoveNext();
				$i++;
			}
			$smarty->assign("region", $region);
		}		
	} elseif (GetSiteSettings('one_country') == "1") {
		$smarty->assign("country_id", GetSiteSettings('id_country'));
		$strSQL = "SELECT id, name FROM ".REGION_TABLE." WHERE id_country='".GetSiteSettings('id_country')."' ORDER BY name";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0) {
			$i = 0;
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$region[$i]["id"] = $row["id"];
				if ($config["lang_ident"]!='ru') {
					$region[$i]["name"] = RusToTranslit($row["name"]);
				} else {
					$region[$i]["name"] = $row["name"];
				}
				$rs->MoveNext();
				$i++;
			}
			$smarty->assign("region", $region);
		}		
	}

	if ($region_id){
		$strSQL =  "SELECT id, name FROM ".CITY_TABLE." WHERE id_region='".$region_id."' ORDER BY name";
		$rs = $dbconn->Execute($strSQL);
		if (strlen($rs->fields[0])>1) {
			$i = 0;
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$city[$i]["id"] = $row["id"];
				if ($config["lang_ident"]!='ru') {
					$city[$i]["name"] = RusToTranslit($row["name"]);
				} else {
					$city[$i]["name"] = $row["name"];
				}
				$rs->MoveNext();
				$i++;
			}
			$smarty->assign("city", $city);
		}
	}

	return ;
}

/**
 * Check if access to the file is allowed for the user with $id_user
 *
 * @param integer $id_user
 * @param string $file - file name without extension
 * @return string
 */
function IsFileAllowed($id_user, $file){
	global $dbconn, $config;
	
	if ($config["site_mode"] == 2) {
		/**
		 * not use pay services, so all modules are available
		 */
		return "1";
	}
	
	$mod_arr = array();
	$mod_arr = GetPermissionsUser($id_user);

	$id_module = getModuleId($file);

	if(is_array($mod_arr["id"]) && in_array($id_module, $mod_arr["id"]) ){
		return "1";
	}else{
		return "0";
	}
}

/**
 * Get modules id array for wich user with $id_user have access
 *
 * @param integer $id_user
 * @return array
 */
function GetPermissionsUser($id_user){
	global $dbconn;
	$strSQL = "	SELECT DISTINCT a.id_module FROM ".GROUP_MODULE_TABLE." a, ".USER_GROUP_TABLE." b
				WHERE b.id_user='".$id_user."' AND b.id_group=a.id_group ";

	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF) {
		$module["id"][$i] = $rs->fields[0];
		$rs->MoveNext();
		$i++;
	}
	return $module;
}

/**
 * Get id for module
 *
 * @param string $file - file name without extension
 * @return integer
 */
function getModuleId($file) {
	global $dbconn, $config;

	$strSQL = "select id from ".MODULE_FILE_TABLE." where file='".$file."' ";
	$rs = $dbconn->Execute($strSQL);
	return $rs->fields[0];
}

/**
 * Get module name for file from file name
 *
 * @param string $file
 * @return string
 */
function GetRightModulePath($file){
	global $config;

	$file_name = substr($file, strlen($config["site_path"]));
	$file_name = str_replace("\\", "/", $file_name);
	$file_name = str_replace(".php", "", $file_name);
	/**
	 * check for first slash in filename
	 */
	if (substr($file_name, 0, 1) == "/") {
		$file_name = substr($file_name, 1);
	}
	return trim($file_name);
}

/**
 * Alert page for user if he has no access to the file
 *
 * @param string $file
 * @return void
 */
function AlertPage($file=""){
	global $smarty, $dbconn, $config, $lang, $user;

	if ($file=="noconfirm")
	{
		$suffix = "?sel=status&need=no";
	}
	else if (strlen($file)>0) {
		$strSQL = "select id FROM ".MODULE_FILE_TABLE." where file='".$file."' ";
		$rs = $dbconn->Execute($strSQL);
		$id_module = $rs->fields[0];
		$suffix = "?sel=access&id_module=".$id_module;
	} else {
		$suffix = "?sel=status&need=yes";
	}
	echo "<script>
			if(opener){ opener.location.href='".$config["server"].$config["site_root"]."/alert.php".$suffix."'; window.close(); opener.focus();}
		else{ location.href='".$config["server"].$config["site_root"]."/alert.php".$suffix."';}
		</script>";
	exit;
}

/**
 * Get count for my contacts pages (who visited my profiles, hotlisted,
 * blacklisted and interested in me )
 *
 * @param integer $id_user
 * @return array
 */
function GetCountForLinks($id_user) {
	global $smarty, $config, $dbconn;

	$id_user = intval($id_user);

	//view MY profile week
	$strSQL = " SELECT DISTINCT id_visiter FROM ".PROFILE_VISIT_TABLE." WHERE (id_user='$id_user' AND last_visit_date>(now()-INTERVAL 7 DAY) AND id_visiter NOT IN ('$id_user','1','2') ) GROUP BY id_visiter ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_my_week"] = $rs->RowCount();
	} else {
		$data["visit_my_week"] = 0;
	}
	//view MY profile month
	$strSQL = " SELECT DISTINCT id_visiter FROM ".PROFILE_VISIT_TABLE." WHERE (id_user='$id_user' AND last_visit_date>(now()-INTERVAL 1 MONTH) AND id_visiter NOT IN ('$id_user','1','2') ) GROUP BY id_visiter ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_my_month"] = $rs->RowCount();
	} else {
		$data["visit_my_month"] = 0;
	}
	//view THEIR profile week
	$strSQL = " SELECT DISTINCT id_user FROM ".PROFILE_VISIT_TABLE." WHERE (id_visiter='$id_user' AND last_visit_date>(now()-INTERVAL 7 DAY) AND id_user<>'$id_user'  AND id_visiter NOT IN ('1','2') ) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_their_week"] = $rs->RowCount();
	} else {
		$data["visit_their_week"] = 0;
	}
	//view THEIR profile month
	$strSQL = " SELECT DISTINCT id_user FROM ".PROFILE_VISIT_TABLE." WHERE (id_visiter='$id_user' AND last_visit_date>(now()-INTERVAL 1 MONTH) AND id_user<>'$id_user'  AND id_visiter NOT IN ('1','2') ) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_their_month"] = $rs->RowCount();
	} else {
		$data["visit_their_month"] = 0;
	}

	//hotlisted ME week
	$strSQL = " SELECT DISTINCT id_user FROM ".HOTLIST_TABLE." WHERE id_friend='$id_user' AND datenow>(now()-INTERVAL 7 DAY) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["hotlisted_my_week"] = $rs->RowCount();
	} else {
		$data["hotlisted_my_week"] = 0;
	}
	//hotlisted ME month
	$strSQL = " SELECT DISTINCT id_user FROM ".HOTLIST_TABLE." WHERE id_friend='$id_user' AND datenow>(now()-INTERVAL 1 MONTH) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["hotlisted_my_month"] = $rs->RowCount();
	} else {
		$data["hotlisted_my_month"] = 0;
	}

	//hotlisted THEIR week
	$strSQL = " SELECT DISTINCT id_friend FROM ".HOTLIST_TABLE." WHERE id_user='$id_user' AND datenow>(now()-INTERVAL 7 DAY) GROUP BY id_friend ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["hotlisted_their_week"] = $rs->RowCount();
	} else {
		$data["hotlisted_their_week"] = 0;
	}
	//hotlisted THEIR month
	$strSQL = " SELECT DISTINCT id_friend FROM ".HOTLIST_TABLE." WHERE id_user='$id_user' AND datenow>(now()-INTERVAL 1 MONTH) GROUP BY id_friend ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["hotlisted_their_month"] = $rs->RowCount();
	} else {
		$data["hotlisted_their_month"] = 0;
	}

	//interested ME week
	$strSQL = " SELECT DISTINCT id_user FROM ".INTERESTS_TABLE." WHERE id_interest_user='$id_user' AND interest_date>(now()-INTERVAL 7 DAY) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["interested_my_week"] = $rs->RowCount();
	} else {
		$data["interested_my_week"] = 0;
	}
	//interested ME month
	$strSQL = " SELECT DISTINCT id_user FROM ".INTERESTS_TABLE." WHERE id_interest_user='$id_user' AND interest_date>(now()-INTERVAL 1 MONTH) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["interested_my_month"] = $rs->RowCount();
	} else {
		$data["interested_my_month"] = 0;
	}
	//interested THEIR week
	$strSQL = " SELECT DISTINCT id_interest_user FROM ".INTERESTS_TABLE." WHERE id_user='$id_user' AND interest_date>(now()-INTERVAL 7 DAY) GROUP BY id_interest_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["interested_their_week"] = $rs->RowCount();
	} else {
		$data["interested_their_week"] = 0;
	}
	//interested THEIR month
	$strSQL = " SELECT DISTINCT id_interest_user FROM ".INTERESTS_TABLE." WHERE id_user='$id_user' AND interest_date>(now()-INTERVAL 1 MONTH) GROUP BY id_interest_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["interested_their_month"] = $rs->RowCount();
	} else {
		$data["interested_their_month"] = 0;
	}

	//blacklisted THEIR week
	$strSQL = " SELECT DISTINCT id_enemy FROM ".BLACKLIST_TABLE." WHERE id_user='$id_user' AND datenow>(now()-INTERVAL 7 DAY) GROUP BY id_enemy ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["blacklisted_their_week"] = $rs->RowCount();
	} else {
		$data["blacklisted_their_week"] = 0;
	}
	//blacklisted THEIR month
	$strSQL = " SELECT DISTINCT id_enemy FROM ".BLACKLIST_TABLE." WHERE id_user='$id_user' AND datenow>(now()-INTERVAL 1 MONTH) GROUP BY id_enemy ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["blacklisted_their_month"] = $rs->RowCount();
	} else {
		$data["blacklisted_their_month"] = 0;
	}

	//blacklisted ME week
	$strSQL = " SELECT DISTINCT id_user FROM ".BLACKLIST_TABLE." WHERE id_enemy='$id_user' AND datenow>(now()-INTERVAL 7 DAY) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["blacklisted_my_week"] = $rs->RowCount();
	} else {
		$data["blacklisted_my_week"] = 0;
	}
	//blacklisted ME month
	$strSQL = " SELECT DISTINCT id_user FROM ".BLACKLIST_TABLE." WHERE id_enemy='$id_user' AND datenow>(now()-INTERVAL 1 MONTH) GROUP BY id_user ";
	$rs = $dbconn->Execute($strSQL);	
	if ($rs->RowCount() > 0) {
		$data["blacklisted_my_month"] = $rs->RowCount();
	} else {
		$data["blacklisted_my_month"] = 0;
	}
	return $data;
}

/**
 * Left links array for logged user (the same as at homepage in MY CONTACTS section)
 *
 * @param string $file_name
 * @return array
 */
function GetLeftLinks ($file_name) {
	global $config, $smarty, $dbconn, $user, $lang;

	$links["rent_viewed_my"] = $file_name."?sel=viewed_my";
	$links["rent_hotlisted_me"] = $file_name."?sel=hotlisted_me";
	$links["rent_interested_me"] = $file_name."?sel=inter_me";
	$links["rent_my_match"] = $file_name."?sel=match_my";
	$links["rent_blacklisted_me"] = $file_name."?sel=blacklisted_me";

	$links["rent_viewed_their"] = $file_name."?sel=viewed_their";
	$links["rent_hotlisted_them"] = $file_name."?sel=hotlisted_them";
	$links["rent_interested_them"] = $file_name."?sel=inter_them";
	$links["rent_match_for_them"] = $file_name."?sel=match_their&amp;section=rent";
	$links["rent_blacklisted_them"] = $file_name."?sel=blacklisted_them";

	$links_lang = GetLangContent('left_links');
	$smarty->assign('links_lang', $links_lang);
	return $links;
}

/**
 * Check if $text include badwords, defined in $config["site_path"]."/include/badwords.txt"
 *
 * @param string $text
 * @return string
 */
function BadWordsCont($text){
	global $smarty, $dbconn, $user, $config;

	$bw_array = array();
	$text_array = array();
	$err = "";

	$text = trim(strtolower($text));

	$file_path = $config["site_path"]."/include/badwords.txt";

	if(file_exists($file_path) && is_readable($file_path) && strlen($text)>0){
		$bw_file = strtolower(implode("", file($file_path)));
		$bw_file = explode(",", $bw_file);

		foreach($bw_file as $k => $v){
			if(strlen(trim($v))>0){
				$pos = eregi("(^| |[[:punct:]])".trim($v)."($| |[[:punct:]])", $text);
				if(intval($pos) != 0){ /// find
					$err = "badword";
					break;
				}
			}
		}
	}

	return $err;
}

/**
 * Get percent of listings' fiilling - for getting bonus
 *
 * @param integer $id_user
 * @param integer $id_ad
 * @param integer $type
 * @return integer
 */
function GetPercent($id_user='', $id_ad='', $type=''){
	global $smarty, $dbconn, $user, $config, $REFERENCES;

	if (!empty($id_user) && !empty($id_ad) && !empty($type)) {
		$upload_type = array("f", "v");
		switch ($type) {
			case "1":  //rent
				$used_references = array("info", "period", "realty_type", "description");
				$plan_photo = 0;
				$from_db_table = array("user_rent_location" => 
											array("table" => USERS_RENT_LOCATION_TABLE,
												  "fields" => "id_country, id_region, id_city"),
									   "user_rent_pays" => 
											array("table" => USERS_RENT_PAYS_TABLE,
												  "fields" => "min_payment, max_payment, min_deposit, max_deposit, min_live_square, max_live_square, min_total_square, max_total_square, min_land_square, max_land_square, min_floor, max_floor, floor_num, subway_min, min_year_build, max_year_build")
									  );
			break;
			case "2":  //lease
				//not use values of "My preferences" section (about human)
				//$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
				$used_references = array("info", "period", "realty_type", "description");
				$plan_photo = 1;
				$from_db_table = array("user_rent_location" => 
											array("table" => USERS_RENT_LOCATION_TABLE,
												  "fields" => "id_country, id_region, id_city, zip_code, street_1, street_2, adress"),
									   "user_rent_pays" => 
											array("table" => USERS_RENT_PAYS_TABLE,
												  "fields" => "min_payment, min_deposit, min_live_square, min_total_square, min_land_square, min_floor, floor_num, subway_min, min_year_build")
									  );
			break;
			case "3":  //buy
				$used_references = array("info", "realty_type", "description");				
				$plan_photo = 0;
				$from_db_table = array("user_rent_location" => 
											array("table" => USERS_RENT_LOCATION_TABLE,
												  "fields" => "id_country, id_region, id_city"),
									   "user_rent_pays" => 
											array("table" => USERS_RENT_PAYS_TABLE,
												  "fields" => "min_payment, max_payment, min_deposit, max_deposit, min_live_square, max_live_square, min_total_square, max_total_square, min_land_square, max_land_square, min_floor, max_floor, floor_num, subway_min, min_year_build, max_year_build")
									  );
			break;
			case "4":  //sell
				//not use values of "My preferences" section (about human)
				//$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description");
				$used_references = array("info", "realty_type", "description");				
				$plan_photo = 1;
				$from_db_table = array("user_rent_location" => 
											array("table" => USERS_RENT_LOCATION_TABLE,
												  "fields" => "id_country, id_region, id_city, zip_code, street_1, street_2, adress"),
									   "user_rent_pays" => 
											array("table" => USERS_RENT_PAYS_TABLE,
												  "fields" => "min_payment, min_deposit, min_live_square, min_total_square, min_land_square, min_floor, floor_num, subway_min, min_year_build")
									  );
			break;
		}

		$counts_spr = array();
		$counts_spr_sum = 0;
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$strSQL = "	SELECT COUNT(id) FROM ".$arr["spr_table"]." ";				
				$rs = $dbconn->Execute($strSQL);
				//subreferences count in each reference
				$counts_spr[$arr["key"]] = $rs->fields[0];
				$counts_spr_sum += $counts_spr[$arr["key"]];				
			}
		}		
		
		$count_user = array();
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {

//				if ($arr["spr_match_table"] != "" && ($type == 2 || $type == 4)) {
//					/**
//					 * my preferences in listing
//					 */
//					$strSQL = " SELECT COUNT(DISTINCT id_spr) FROM ".$arr["spr_match_table"]." WHERE id_user='".$id_user."' AND id_ad='".$id_ad."' ";
//				} else {
					$strSQL = " SELECT COUNT(DISTINCT id_spr) FROM ".$arr["spr_user_table"]." WHERE id_user='".$id_user."' AND id_ad='".$id_ad."' ";
//				}
				$rs = $dbconn->Execute($strSQL);
				
				$a = $rs->fields[0];								
				array_push($count_user, $a);
			}
		}
		
		foreach ($upload_type as $up_type){			
			$strSQL = "SELECT id FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$id_ad."' AND admin_approve = '1' AND upload_type='$up_type' LIMIT 1";			
			$rs = $dbconn->Execute($strSQL);
			$counts_spr_sum += 1;
			array_push($count_user, $rs->RowCount());
		}
		
		if ($plan_photo){
			$strSQL = "SELECT id FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$id_ad."' AND admin_approve = '1' AND id_user='".$id_user."' LIMIT 1";
			$rs = $dbconn->Execute($strSQL);
			$counts_spr_sum += 1;
			array_push($count_user, $rs->RowCount());
		}		
		
		foreach ($from_db_table as $db_table){
			$strSQL = "SELECT ".$db_table["fields"]." FROM ".$db_table["table"]." WHERE id_ad='".$id_ad."' AND id_user='$id_user' LIMIT 1";
			$rs = $dbconn->Execute($strSQL);						
			
			$row = $rs->GetRowAssoc(false);			
			foreach ($row AS $item){
				if ($item){					
					array_push($count_user, 1);
				}
			$counts_spr_sum += 1;	
			}			
		}
		$percent = intval(100*(array_sum($count_user))/$counts_spr_sum);		
		
		return $percent;
	} else {
		return 0;
	}
}


function GetDayId($num){
	$str = "".$num."";
	$str = substr($str, (strlen($str)-1),1);
	if ($num >10 && $num<20){
		$mess_id = "1";
	} else {
		if ($num == 4){
			$mess_id = "3";
		} else {
			if ($str == 1) {
				$mess_id = "2";
			} elseif (($str == 2) || ($str == 3) || ($str == 4)){
				$mess_id = "3";
			} else{
				$mess_id = "1";
			}
		}
	}
	return $mess_id;
}

/**
 * Check if listing was featured (a payed service)
 *
 * @param integer $user_id
 * @param integer $id_ad - listing is
 * @return boolean
 */
function IsFeatured($user_id, $id_ad){
	global $smarty, $dbconn, $user, $config;
	if (($user_id>0) && ($id_ad>0)){
		$strSQL = "SELECT id FROM ".FEATURED_TABLE." WHERE id_user='".$user_id."' AND id_ad='".$id_ad."' ";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Get visits' number on the listing with $id_ad of the current user owner
 *
 * @param integer $id_ad
 * @return array
 */
function VisitedMyAd($id_ad){
	global $smarty, $dbconn, $user, $config;

	$strSQL = " SELECT DISTINCT id_visiter FROM ".RENT_AD_VISIT_TABLE."
				WHERE (id_ad='".intval($id_ad)."'
				AND last_visit_date>(now()-INTERVAL 1 DAY)
				AND id_visiter NOT IN ('".intval($user[0])."','1')) GROUP BY id_visiter ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_day"] = $rs->RowCount();
	} else {
		$data["visit_day"] = 0;
	}

	$strSQL = " SELECT DISTINCT id_visiter FROM ".RENT_AD_VISIT_TABLE."
				WHERE (id_ad='".intval($id_ad)."'
				AND last_visit_date>(now()-INTERVAL 1 MONTH)
				AND id_visiter NOT IN ('".intval($user[0])."','1')) GROUP BY id_visiter ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_month"] = $rs->RowCount();
	} else {
		$data["visit_month"] = 0;
	}

	$strSQL = " SELECT DISTINCT id_visiter FROM ".RENT_AD_VISIT_TABLE."
				WHERE (id_ad='".intval($id_ad)."'
				AND last_visit_date>(now()-INTERVAL 1 MONTH)
				AND id_visiter NOT IN ('".intval($user[0])."','1','2')) GROUP BY id_visiter ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0) {
		$data["visit_not_guest"] = $rs->RowCount();
	} else {
		$data["visit_not_guest"] = 0;
	}
	return $data;
}

/**
 * Get ads' place in search 
 *
 * @param integer $id_ad
 * @param integer $ad_type
 * @param string $ad_update_date
 * @param integer $ad_status
 * @return integer
 */
function GetAdPlace($id_ad, $ad_type, $ad_update_date, $ad_status) {
	global $dbconn;
	
	$place = 0;
	if ($ad_status == 1){
		$str_new = " SELECT id, date_begin, date_end FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_ad='$id_ad'";
		$rs_ts = $dbconn->Execute($str_new);
		
		if ($rs_ts->fields[0]>0){
			
			$dbg = $rs_ts->fields[1];
			
			$str_new = "SELECT tsat.id FROM ".TOP_SEARCH_ADS_TABLE." tsat, ".RENT_ADS_TABLE." ra ".
					   "WHERE ra.id=tsat.id_ad AND tsat.id_ad<>$id_ad AND tsat.date_begin>'".$dbg."' ".
					   "AND ra.type='$ad_type' AND ra.status!='0'";
			$rs_place = $dbconn->Execute($str_new);
			$place = $rs_place->RowCount()+1;					
		} else {
			$id_arr = array();
			$str_new = "SELECT tsat.id FROM ".TOP_SEARCH_ADS_TABLE." tsat, ".RENT_ADS_TABLE." ra ".
						"WHERE ra.id=tsat.id_ad AND ra.type='$ad_type' AND ra.status!='0'";						
			$rs_place = $dbconn->Execute($str_new);
			$place = $rs_place->RowCount();
			
			if ($rs_place->fields[0]>0){
				while(!$rs_place->EOF){
					array_push($id_arr, $rs_place->fields[0]);
					$rs_place->MoveNext();
				}
				$id_str = " AND ra.id NOT IN ( ".implode(" , ", $id_arr)." )";
			} else {
				$id_str = "";
			}
			$str_new = "SELECT ra.id FROM ".RENT_ADS_TABLE." ra ".
					   "WHERE ra.type='$ad_type' AND ra.datenow>'$ad_update_date' AND ra.status!='0' ".
					   "AND ra.id !='$id_ad' $id_str GROUP BY ra.id";					   
			$rs_place = $dbconn->Execute($str_new);
			$place = $place+$rs_place->RowCount()+1;
		}		
	}
	return $place;		
}

/**
 * Get users' top listing and assign listing place information to smarty variables
 * @return void
 */
function GetUserTopAd(){
	global $smarty, $dbconn, $user, $config;

	$strSQL = " SELECT DISTINCT id, room_type, type, datenow FROM ".RENT_ADS_TABLE."
				WHERE id_user='".$user[0]."' AND status!='0' GROUP BY id ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF) {
		$arr["id"][$i] = $rs->fields[0];

		$str_new = " SELECT id, date_begin FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_ad='".$arr["id"][$i]."' ";
		$rs_ts = $dbconn->Execute($str_new);
		if ($rs_ts->fields[0]>0){
			$dbg = $rs_ts->fields[1];
			$str_new = " SELECT tsat.id FROM ".TOP_SEARCH_ADS_TABLE." tsat, ".RENT_ADS_TABLE." ra
						 WHERE ra.id=tsat.id_ad AND tsat.id_ad<>".$arr["id"][$i]."
						 AND tsat.date_begin>'".$dbg."' AND ra.type='".$rs->fields[2]."'
						 AND ra.status!='0' AND ra.room_type='".$rs->fields[1]."'  ";
			$rs_place = $dbconn->Execute($str_new);
			$place = $rs_place->RowCount()+1;
		} else {
			$id_arr = array();
			$str_new = "SELECT tsat.id FROM ".TOP_SEARCH_ADS_TABLE." tsat, ".RENT_ADS_TABLE." ra
						WHERE ra.id=tsat.id_ad AND ra.type='".$rs->fields[2]."'
						AND ra.status!='0' AND ra.room_type='".$rs->fields[1]."'  ";
			$rs_place = $dbconn->Execute($str_new);
			$place = $rs_place->RowCount();
			if ($rs_place->fields[0]>0){
				while(!$rs_place->EOF){
					array_push($id_arr, $rs_place->fields[0]);
					$rs_place->MoveNext();
				}
				$id_str = " AND ra.id NOT IN ( ".implode(" , ", $id_arr)." )";
			} else {
				$id_str = "";
			}
			$str_new = "SELECT ra.id FROM ".RENT_ADS_TABLE." ra, ".SPR_RENT_APARTMENT_USER_TABLE." sraut
						WHERE sraut.id_ad=ra.id AND sraut.id_spr='1'
						AND sraut.id_value IN (1,2,3) AND ra.type='".$rs->fields[2]."'
						AND ra.datenow>'".$rs->fields[3]."' AND ra.status!='0'
						AND ra.room_type='".$rs->fields[1]."' AND ra.id !='".$arr["id"][$i]."' ".$id_str."
						GROUP BY ra.id";
			$rs_place = $dbconn->Execute($str_new);
			$place = $place+$rs_place->RowCount()+1;

		}
		$arr["place"][$i] = $place;

		$rs->MoveNext();
		$i++;
	}
	if (isset($arr) && sizeof($arr)>0){
		array_multisort($arr["place"], SORT_NUMERIC, SORT_DESC, $arr["id"]);
		$data["place"] = $arr["place"][$i-1];
		$data["id"] = $arr["id"][$i-1];
		$smarty->assign('place_id',$data["id"]);
		$smarty->assign('place_num',$data["place"]);
		$new = VisitedMyAd($data["id"]);
		$smarty->assign('place_day', $new["visit_day"]);
		$smarty->assign('place_month', $new["visit_month"]);
		$strSQL = " SELECT DISTINCT id_visiter FROM ".RENT_AD_VISIT_TABLE."
					WHERE (id_ad='".intval($data["id"])."'
					AND last_visit_date>(now()-INTERVAL 1 MONTH)
					AND id_visiter NOT IN ('".intval($user[0])."','1','2')) GROUP BY id_visiter ";

		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0){
			$smarty->assign('place_link', 1);
		}
	} else {
		return ;
	}
}

/**
 * Get users' account info, except info from references
 *
 * @param integer $user_id
 * @return array
 */
function GetAccountTableInfo($user_id) {
	global $config, $smarty, $dbconn;
	$strSQL = "SELECT login, fname, sname, DATE_FORMAT(date_birthday, '%d') as birth_day, DATE_FORMAT(date_birthday, '%m') as birth_month, DATE_FORMAT(date_birthday, '%Y') as birth_year, email, phone, user_type FROM ".USERS_TABLE." WHERE id='".$user_id."' ";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc(false);
	$data["login"] = stripslashes($row["login"]);
	$data["fname"] = stripcslashes($row["fname"]);
	$data["sname"] = stripslashes($row["sname"]);
	$data["birth_day"] = $row["birth_day"];
	$data["birth_month"] = $row["birth_month"];
	$data["birth_year"] = $row["birth_year"];
	$data["email"] = $row["email"];
	$data["phone"] = stripslashes($row["phone"]);
	$data["user_type"] = intval($row["user_type"]);

	$week = GetWeek();
	$smarty->assign("week", $week);

	$time_arr = GetHourSelect();
	$smarty->assign("time_arr", $time_arr);

	if ($data["user_type"] == 2){
		$strSQL = " SELECT company_name, company_url, company_rent_count, company_how_know, company_quests_comments, weekday_str, work_time_begin, work_time_end, lunch_time_begin, lunch_time_end, logo_path, admin_approve,id_country,id_region,id_city,address,postal_code FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user_id."' ";	
			
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data["company_name"] = stripslashes($row["company_name"]);
		$data["company_url"] = stripslashes($row["company_url"]);
		if ($data["company_url"] && strcasecmp(substr($data["company_url"], 0, 4), "http") != 0){
			$data["company_url"] = "http://".$data["company_url"];
		}

		$data["company_rent_count"] = stripslashes($row["company_rent_count"]);
		$data["company_how_know"] = stripslashes($row["company_how_know"]);
		$data["company_quests_comments"] = stripslashes($row["company_quests_comments"]);
		$data["weekday_str"] = stripslashes($row["weekday_str"]);
		if ($data["weekday_str"] != "") {
			$data["weekday_1"] = explode(",",$data["weekday_str"]);
			foreach ($data["weekday_1"] as $value){
				$data["weekday"][$value-1] = $value;
			}
		}
		$data["work_time_begin"] = intval($row["work_time_begin"]);
		$data["work_time_end"] = intval($row["work_time_end"]);
		$data["logo_path"] = $row["logo_path"];
		$data["admin_approve"] = $row["admin_approve"];
		
		if ((strlen($data["logo_path"])>0) && (file_exists($config["site_path"]."/uploades/photo/".$data["logo_path"]))){
			$data["logo_path"] = $config["server"].$config["site_root"]."/uploades/photo/".$data["logo_path"];
		} else {
			$data["logo_path"] = $config["server"].$config["site_root"]."/uploades/photo/agency.gif";
		}
		$data["use_photo_approve"] = GetSiteSettings('use_photo_approve');
		$data["lunch_time_begin"] = intval($row["lunch_time_begin"]);
		$data["lunch_time_end"] = intval($row["lunch_time_end"]);
		$data["id_user"] = $user_id;
		$data["id_country"] = intval($row["id_country"]);
		$data["id_region"] = intval($row["id_region"]);
		$data["id_city"] = intval($row["id_city"]);
		$data["postal_code"] = $row["postal_code"];
		$data["address"] = $row["address"];
		$strSQL = " SELECT name FROM ".COUNTRY_TABLE." where id=".$data["id_country"];
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data["country_name"]=$row["name"];
		$strSQL = " SELECT name FROM ".REGION_TABLE." where id=".$data["id_region"];
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data["region_name"]=$row["name"];
		$strSQL = " SELECT name,lat,lon FROM ".CITY_TABLE." where id=".$data["id_city"];
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$data["city_name"]=$row["name"];
		if (($data["id_country"] && $data["country_name"] == '')||($data["id_region"] && $data["region_name"] == '')||($data["id_city"] && $data["city_name"] == '')) {
			$data["in_base"]=0;
		}
		else {
			$data["in_base"]=1;
		}
		$data["lon"]=$row["lon"];
		$data["lat"]=$row["lat"];
		if ($data["id_region"]==0) {
			$data["region_name"]='';
			$data["lon"]=0;
			$data["lat"]=0;
		}
		if ($data["id_city"]==0) {
			$data["city_name"]='';
			$data["lon"]=0;
			$data["lat"]=0;
		}
	}
	return $data;
}

/**
 * Delete uploaded file
 *
 * @param string $type_upload (a = audio, v = video)
 * @param integer $id_file
 * @return void
 */
function DeleteUploadedFiles($type_upload, $id_file=""){
	global $smarty, $dbconn, $config, $user;
	$id = intval($user[0]);
	$settings = GetSiteSettings(array( "audio_folder", "video_folder"));

	switch($type_upload){
		case "a": $folder = $settings["audio_folder"]; break;
		case "v": $folder = $settings["video_folder"]; break;
		default: $folder = $settings["video_folder"];
	}
	if($type_upload == 'a' || $type_upload == 'v'){
		$rs_upl=$dbconn->Execute("Select upload_path from ".USERS_RENT_UPLOADS_TABLE." where id='".$id_file."' and id_user= '".$id."'");
		if(strlen($rs_upl->fields[0])>0){
			$old_file =$config["site_path"].$folder."/".$rs_upl->fields[0];
			if(file_exists($old_file)){
				unlink($old_file);
			}
			$dbconn->Execute("delete from ".USERS_RENT_UPLOADS_TABLE." where id='".$id_file."' and id_user= '".$id."'");
		}
	}
	return;
}

/**
 * get temp upload file
 *
 * @param string $file_name
 * @return string
 */
function GetTempUploadFile($file_name){
	global $config;
	$path_to_image = "";

	$matches = array();

	$forbidden_chars = strtr("$/\\:*?&quot;'&lt;&gt;|`", array('&amp;' => '&', '&quot;' => '"', '&lt;' => '<', '&gt;' => '>'));

	if (get_magic_quotes_gpc()) $file_name = stripslashes($file_name);

	$picture_name = strtr($file_name, $forbidden_chars, str_repeat('_', strlen("$/\\:*?&quot;'&lt;&gt;|`")));

	if (!preg_match("/(.+)\.(.*?)\Z/", $picture_name, $matches)) {
		$matches[1] = 'invalid_fname';
		$matches[2] = 'xxx';
	}

	$prefix = "mHTTP_temp_";
	$suffix = $matches[2];

	do {
		$seed = substr(md5(microtime().getmypid()), 0, 8);
		$path_to_image = $config["site_path"]."/templates_c/". $prefix . $seed . '.' . $suffix;
	} while (file_exists($path_to_image));

	return $path_to_image;
}

/**
 * Get new name to file, depending on user id
 *
 * @param string $name - filename
 * @param integer $user_id
 * @return string
 */
function GetNewFileName($name, $user_id){
	$ex_arr = explode(".",$name);
	$extension = $ex_arr[count($ex_arr)-1];
	$new_file_name = $user_id."_".substr(md5(microtime().getmypid()), 0, 8).".".$extension;
	return $new_file_name;
}

/**
 * Get array of property types
 *
 * @return array
 */
function GetPropertyTypeArr() {
	$realty_type = GetReferenceArray(SPR_TYPE_TABLE, VALUES_TYPE_TABLE, "realty_type", '', 2);
	/**
	 * в справочнике "тип недвижимости" - только один подсправочник, и добавление
	 * других подсправочников не разрешено => берем по id=0
	 */
	return $realty_type[0]["opt"];
}

/**
 * Get default user icon (if user hadn't upload his icon to profile)
 *
 * @param integer $user_type (1 - private person, 2 - agency)
 * @param array $gender
 */
function getDefaultUserIcon($user_type, $gender) {
	global $dbconn, $config, $lang;

	if ($user_type == 1){
		if (count($gender)>1){
			$num_gender = 4;
			/*group for future - now is not useable in RE, because user icons are formed from the
			info, getting from user profile*/
			$icon_name = "default_photo_group";
		} elseif (count($gender)==0) {
			$num_gender = 5;		//no gender
			$icon_name = "default_photo_man";
		} else {
			if ($gender[0] == 1){
				$num_gender = 3;	//male
				$icon_name = "default_photo_male";
			} elseif ($gender[0] == 2) {
				$num_gender = 2;	//female
				$icon_name = "default_photo_female";
			} elseif ($gender[0] == 3) {
				$num_gender = 4;	//family with children
				$icon_name =  "default_photo_fwithc";
			} elseif ($gender[0] == 4) {
				$num_gender = 4;	//family without children
				$icon_name = "default_photo_fwithoutc";
			}
		}
	} else {
		$num_gender = 1;			//agency
		$icon_name = "default_photo_agency";
	}

	$icon = GetSiteSettings($icon_name);
	$alt = $lang["default_select"][$icon_name."_alt"];
	return array("num_gender" => $num_gender, "icon_name" => $icon, "icon_alt" => $alt);
}

/**
 * Get ads order by date and assign result array to the smarty variable $search_result
 *
 * @param string $num_at_page_name  - name of the value from settings
 * @param integer $page
 * @param string $param
 * @param integer $sorter
 * @param integer $sort_order
 * @param string $order_link
 * @return void
 */
function GetLastAds($num_at_page_name, $page=1, $param = "?", $sorter=0, $sort_order=1, $order_link="", $show_type="", $ads_number=0, $file_name = "") {
	global $smarty, $dbconn, $user, $config, $REFERENCES, $lang;
	$site_mode = GetSiteSettings("site_mode");
	$where_str = "";
	if ($site_mode == 2) {
		//a separate realtor or realestate agent/broker
		//show ads, which type is 2 (have realty) and 4 (sell realty)
		$where_str = " AND ra.type IN (2,4) ";
	}
	if ($show_type == "admin_choose") {

		$where_str .= " AND sp.status='1' ";
	}
	
	$settings = GetSiteSettings(array("photo_folder", "default_photo", $num_at_page_name, "cur_position", "cur_format" ));
	$ads_numpage = $settings[$num_at_page_name];

	if ($show_type != "") {
		$ads_numpage = $ads_number;
	}

	$lim_min = ($page-1)*$ads_numpage;
	$lim_max = $ads_numpage;
	$limit_str = "LIMIT ".$lim_min.", ".$lim_max;
	switch ($sort_order){
		case "1":
			$order_icon = "&darr;";
			break;
		default:
			$order_icon = "&uarr;";
			break;
	}
	$smarty->assign("order_icon", $order_icon);
	$smarty->assign("use_maps_in_search_results", GetSiteSettings("use_maps_in_search_results"));
	$sort_arr = getRealtySortOrder($sorter, $sort_order);
	$sorter_str = " GROUP by ra.id ORDER BY ".$sort_arr["sorter_str"].$sort_arr["sorter_order"];

	$sorter_tolink = $sort_arr["sorter_tolink"];
	$sorter_topage = $sort_arr["sorter_topage"];

	$strSQL = " SELECT ra.id, ra.id_user FROM ".RENT_ADS_TABLE." ra
				LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
				WHERE u.status='1' AND u.guest_user='0' AND u.active='1' AND ra.status='1'";
	$rs = $dbconn->Execute($strSQL);
	$smarty->assign("search_size",$rs->RowCount());

	$strSQL = "	SELECT ra.id, ra.id_user, DATE_FORMAT(ra.movedate,'".$config["date_format"]."') as movedate,
				ra.type, ra.people_count, ra.room_type, ra.sold_leased_status, ra.headline,
				u.fname, u.phone, u.user_type,
				urp.min_payment, urp.max_payment, urp.auction,
				url.adress as address,
				ct.name as country_name, rt.name as region_name, cit.name as city_name,
				ra.upload_path as slide_path, tsat.id as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
				ft.id as featured, sp.order_id, sp.status as spstatus, SUM(vst.visits_count) as visits
				FROM ".RENT_ADS_TABLE." ra
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id
				LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
				LEFT JOIN ".USERS_RENT_PAYS_TABLE." urp ON urp.id_ad=ra.id
				LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country
				LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=ra.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=ra.id
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
				LEFT JOIN ".RENT_AD_VISIT_TABLE." vst ON vst.id_ad=ra.id
				WHERE u.status='1' AND u.guest_user='0' AND u.active='1' AND ra.status='1'
				".$where_str.$sorter_str.$limit_str;

	$rs = $dbconn->Execute($strSQL);

	$i = 0;

	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);

		$search_result[$i]["id_ad"] = $row["id"];
		$search_result[$i]["people_count"] = $row["people_count"];
		$search_result[$i]["id_user"] = $row["id_user"];
		if ($row["movedate"] != '00.00.0000'){
			$search_result[$i]["movedate"] = $row["movedate"];
		}
		$search_result[$i]["id_type"] = $row["type"];
		$search_result[$i]["topsearched"] = $row["topsearched"];
		if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
			$search_result[$i]["show_topsearch_icon"] = true;
			$search_result[$i]["topsearch_date_begin"] = $row["topsearch_date_begin"];
		}
		$search_result[$i]["featured"] = $row["featured"];
		$search_result[$i]["login"] = $row["fname"];
		$search_result[$i]["phone"] = $row["phone"];
		$search_result[$i]["room_type"] = $row["room_type"];
		$search_result[$i]["user_type"] = $row["user_type"];
		
		$search_result[$i]["min_payment"] = PaymentFormat($row["min_payment"]);
		$search_result[$i]["min_payment_show"] = FormatPrice($search_result[$i]["min_payment"], $settings["cur_position"], $settings["cur_format"]);
		$search_result[$i]["max_payment"] = PaymentFormat($row["max_payment"]);
		$search_result[$i]["max_payment_show"] = FormatPrice($search_result[$i]["max_payment"], $settings["cur_position"], $settings["cur_format"]);
		$search_result[$i]["auction"] = $row["auction"];
		
		if ($search_result[$i]["id_type"] == 2){			
			$calendar_event = new CalendarEvent();
			$search_result[$i]["reserve"] = $calendar_event->GetEmptyPeriod($search_result[$i]["id_ad"], $search_result[$i]["id_user"]);		}

		$search_result[$i]["issponsor"] = $row["spstatus"];
		$search_result[$i]["sold_leased_status"] = $row["sold_leased_status"];
		if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
			$search_result[$i]["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
		} else {
			$search_result[$i]["headline"] = stripslashes($row["headline"]);
		}
		$lang_ad = 2;
		$used_references = array("gender", "realty_type", "description");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$name = GetUserAdSprValues($arr["spr_user_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
				if (count($name) == 0 && $arr["spr_match_table"] != ""){
					$name = GetUserAdSprValues($arr["spr_match_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
					$search_result[$i][$arr["key"]."_match"] = implode(",", $name);
				} else {
					$search_result[$i][$arr["key"]] = implode(",", $name);
				}
			}
		}

		$used_references = array("gender");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$name = GetUserGenderIds($arr["spr_user_table"], $search_result[$i]["id_user"], 0, $arr["val_table"]);
				$search_result[$i][$arr["key"]] = $name;
			}
		}
		$gender_info = getDefaultUserIcon($search_result[$i]["user_type"], $search_result[$i]["gender"]);
		$search_result[$i]["num_gender"] =  $gender_info["num_gender"];
		$search_result[$i]["icon_name"] =  $gender_info["icon_name"];
		$search_result[$i]["icon_alt"] =  $gender_info["icon_alt"];

		if (strlen($row["slide_path"])>1){
			$search_result[$i]["image"] = $settings["photo_folder"]."/".$row["slide_path"];
			$search_result[$i]["alt"] = $lang["default_select"]["slideshow"];
			$search_result[$i]["slideshowed"] = 1;
		} else {
			$strSQL2 = "SELECT upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." ".
					   "WHERE id_ad='".$row["id"]."' AND upload_type='f' AND status='1' AND admin_approve='1' ".
					   "ORDER BY sequence ASC LIMIT 1";
			$rs2 = $dbconn->Execute($strSQL2);

			if ($rs2->RowCount() > 0) {
				$img = $rs2->GetRowAssoc(false);
				$search_result[$i]["image"] = $settings["photo_folder"]."/thumb_".$img["upload_path"];
				$search_result[$i]["alt"] = $img["user_comment"];
			} else {
				$search_result[$i]["image"] = $settings["photo_folder"]."/".$search_result[$i]["icon_name"];
				$search_result[$i]["alt"] = $search_result[$i]["icon_alt"];
			}
		}
		$search_result[$i]["viewprofile_link"] = "./viewprofile.php?id=".$search_result[$i]["id_ad"];
		$search_result[$i]["id"] = $row["id"];
		$search_result[$i]["adress"] = $row["address"];
		
		if ($config["lang_ident"]!='ru') {
			$search_result[$i]["country_name"] = RusToTranslit($row["country_name"]);
			$search_result[$i]["region_name"] = RusToTranslit($row["region_name"]);
			$search_result[$i]["city_name"] = RusToTranslit($row["city_name"]);
		} else {
			$search_result[$i]["country_name"] = $row["country_name"];
			$search_result[$i]["region_name"] = $row["region_name"];
			$search_result[$i]["city_name"] = $row["city_name"];
		}
		$suffix = "&amp;id=".$search_result[$i]["id_user"]."&amp;section=rent&amp;id_ad=".$search_result[$i]["id_ad"];
		$search_result[$i]["mail_link"] = "./mailbox.php?sel=fs".$suffix;
		$search_result[$i]["contact_link"] = "./contact.php?sel=fs".$suffix;
		$search_result[$i]["interest_link"] = "./viewprofile.php?sel=interest".$suffix;

		if (isset($row["id_friend"]) && intval($row["id_friend"]) == 0 && isset($row["id_enemy"]) && intval($row["id_enemy"]) == 0) {
			$search_result[$i]["addfriend_link"] = "./viewprofile.php?sel=addtohot".$suffix;
			$search_result[$i]["blacklist_link"] = "./viewprofile.php?sel=addtoblack".$suffix;
		}
		$rs->MoveNext();
		$i++;
	}
	$smarty->assign("search_result", $search_result);

	$strSQL = " SELECT COUNT(DISTINCT ra.id)
				FROM ".RENT_ADS_TABLE." ra
				LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
				WHERE u.status='1' AND u.guest_user='0' AND u.active='1' AND ra.status='1' $where_str";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	if ($order_link != "") {
		$param = "sorter=".$sorter."&amp;order=".$sorter_topage.$param;
		$smarty->assign("order_active_link", "&order=".$sorter_tolink.$order_link);
		$smarty->assign("order_link", "&order=".$sorter_topage.$order_link);
	}
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
	$smarty->assign("links", GetLinkArray($num_records, $page, $file_name."?".$param, $ads_numpage));
}

/**
 * Get user ads
 *
 * @param string $file_name
 * @param string $param
 * @param integer $ad_id_in_array
 * @param integer $user_id - if not set, use current user id
 * @return array
 */
function GetUserAds($file_name, $param="", $ad_id_in_array = "", $user_id = "") {
	global $smarty, $dbconn, $user, $config, $REFERENCES;
	if ($user_id == "") {
		$user_id = $user[0];
	}
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	
	$strSQL = "SELECT count(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$user_id."' ";
	$rs = $dbconn->Execute($strSQL);

	$num_records = $rs->fields[0];
	$smarty->assign("num_records", $num_records);

	$ads_numpage = GetSiteSettings("ads_num_page");
	$photo_folder = GetSiteSettings("photo_folder");
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));

	$lim_min = ($page-1)*$ads_numpage;
	$lim_max = $ads_numpage;
	$limit_str = ($ad_id_in_array == "") ? " limit ".$lim_min.", ".$lim_max : "";

	$ads = array();
	if ($num_records>0){
		$strSQL = "	SELECT DISTINCT ra.id, ra.type, DATE_FORMAT(ra.movedate, '".$config["date_format"]."') as movedate, ra.comment, ra.upload_path, ut.fname, ut.user_type, cn.name as country_name, rg.name as region_name, ct.name as city_name, ra.datenow as clean_move, ra.room_type, ra.status, ra.sold_leased_status, ra.headline, sp.status as spstatus
					FROM ".RENT_ADS_TABLE." ra
					LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON urlt.id_ad=ra.id
					LEFT JOIN ".USERS_TABLE." ut ON ut.id='".$user_id."'
					LEFT JOIN ".COUNTRY_TABLE." cn ON cn.id=urlt.id_country
					LEFT JOIN ".REGION_TABLE." rg ON rg.id=urlt.id_region
					LEFT JOIN ".CITY_TABLE." ct ON ct.id=urlt.id_city
					LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
					WHERE ra.id_user='".$user_id."' GROUP BY ra.id ORDER BY ra.id ".$limit_str;
		$rs = $dbconn->Execute($strSQL);

		$i = 0;

		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$ads[$i]["id"] = $row["id"];
			$ads[$i]["sold_leased_status"] = $row["sold_leased_status"];
			$ads[$i]["issponsor"] = $row["spstatus"];
			if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
				$ads[$i]["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
			} else {
			$ads[$i]["headline"] = stripslashes($row["headline"]);
			}

			$strSQL = "SELECT id as featured FROM ".FEATURED_TABLE."
					   WHERE id_ad='{$row["id"]}'";
			$rs_featured = $dbconn->Execute($strSQL);
			if ($rs_featured->RowCount() > 0) {
				$row_featured = $rs_featured->getRowAssoc( false );
				$ads[$i]["featured"] = $row_featured["featured"];
			} else {
				$ads[$i]["featured"] = "";
			}
			$arr = array();
			$arr = VisitedMyAd($ads[$i]["id"]);
			$ads[$i]["visit_day"] = $arr["visit_day"];
			$ads[$i]["visit_month"] = $arr["visit_month"];
			$ads[$i]["visit_not_guest"] = $arr["visit_not_guest"];

			$ads[$i]["fname"] = stripslashes($row["fname"]);

			$ads[$i]["country_name"] = stripslashes($row["country_name"]);
			if ($config["lang_ident"]!='ru') {
				$ads[$i]["country_name"] = RusToTranslit($ads[$i]["country_name"]);
			}
			$ads[$i]["region_name"] = stripslashes($row["region_name"]);
			if ($config["lang_ident"]!='ru') {
				$ads[$i]["region_name"] = RusToTranslit($ads[$i]["region_name"]);
			}
			$ads[$i]["city_name"] = stripslashes($row["city_name"]);
			if ($config["lang_ident"]!='ru') {
				$ads[$i]["city_name"] = RusToTranslit($ads[$i]["city_name"]);
			}
			$ads[$i]["user_type"] = $row["user_type"];
			if ($row["user_type"] == 2){
				$strSQL_company = " SELECT company_name FROM ".USER_REG_DATA_TABLE." WHERE id_user='".$user_id."' ";
				$rs_name = $dbconn->Execute($strSQL_company);
				if ($rs_name->fields[0]){
					$ads[$i]["company_name"] = stripslashes($rs_name->fields[0]);
				}
			}

			$ads[$i]["edit_link"] = "rentals.php?sel=my_ad&amp;id_ad=".$row["id"];
			$ads[$i]["del_link"] = "rentals.php?sel=del&amp;id_ad=".$row["id"];

			$ads[$i]["top_search_link"] = "services.php?sel=top_search_ad&amp;type=rent&amp;id_ad=".$row["id"];
			$ads[$i]["slideshow_link"] = "services.php?sel=slideshow_ad&amp;type=rent&amp;id_ad=".$row["id"];
			$ads[$i]["feature_link"] = "services.php?sel=feature_ad&amp;type=rent&amp;id_ad=".$row["id"];

			$ads[$i]["visited_ad_link"] = "homepage.php?sel=visited_ad&amp;id_ad=".$row["id"];

			$ads[$i]["type"] = $row["type"];
			
			if ($ads[$i]["type"] == 2){			
				$calendar_event = new CalendarEvent();
				$ads[$i]["reserve"] = $calendar_event->GetEmptyPeriod($ads[$i]["id"], $user_id);	
			}
			
			$ads[$i]["movedate"] = $row["movedate"];
			$ads[$i]["status"] = intval($row["status"]);
			$place = 0;
			if ($row["status"] == 1){
				$str_new = " SELECT id, date_begin, date_end FROM ".TOP_SEARCH_ADS_TABLE." WHERE id_ad='".$ads[$i]["id"]."'";
				$rs_ts = $dbconn->Execute($str_new);
				if ($rs_ts->fields[0]>0){
					$ads[$i]["topsearched"] = 1;
					$dbg = $rs_ts->fields[1];
					if ($rs_ts->fields[2] > date('Y-m-d H:i:s', time())) {
						$ads[$i]["show_topsearch_icon"] = true;
						$ads[$i]["topsearch_date_begin"] = $dbg;
					}
					$str_new = " SELECT tsat.id FROM ".TOP_SEARCH_ADS_TABLE." tsat, ".RENT_ADS_TABLE." ra WHERE ra.id=tsat.id_ad AND tsat.id_ad<>".$ads[$i]["id"]." AND tsat.date_begin>'".$dbg."' AND ra.type='".$ads[$i]["type"]."' AND ra.status!='0' AND ra.room_type='".$row["room_type"]."'  ";
					$rs_place = $dbconn->Execute($str_new);
					$place = $rs_place->RowCount()+1;
				} else {
					$id_arr = array();
					$str_new = " SELECT tsat.id FROM ".TOP_SEARCH_ADS_TABLE." tsat, ".RENT_ADS_TABLE." ra
									WHERE 	ra.id=tsat.id_ad AND ra.type='".$ads[$i]["type"]."' AND ra.status!='0' AND ra.room_type='".$row["room_type"]."'  ";
					$rs_place = $dbconn->Execute($str_new);
					$place = $rs_place->RowCount();
					if ($rs_place->fields[0]>0){
						while(!$rs_place->EOF){
							array_push($id_arr, $rs_place->fields[0]);
							$rs_place->MoveNext();
						}
						$id_str = " AND ra.id NOT IN ( ".implode(" , ", $id_arr)." )";
					} else {
						$id_str = "";
					}
					$str_new = " 	SELECT ra.id FROM ".RENT_ADS_TABLE." ra, ".SPR_RENT_APARTMENT_USER_TABLE." sraut
									WHERE sraut.id_ad=ra.id AND sraut.id_spr='1' AND sraut.id_value IN (1,2,3) AND ra.type='".$ads[$i]["type"]."' AND ra.datenow>'".$row["clean_move"]."' AND ra.status!='0' AND ra.room_type='".$row["room_type"]."' AND ra.id !='".$ads[$i]["id"]."' ".$id_str."
									GROUP BY ra.id";
					$rs_place = $dbconn->Execute($str_new);
					$place = $place+$rs_place->RowCount()+1;

				}
				$ads[$i]["place"] = $place;
			}

			$ads[$i]["comment"] = stripslashes($row["comment"]);
			$ads[$i]["number"] = ($page-1)*$ads_numpage+($i+1);

			$lang_ad = 2; //т.к. выводим информацию о том, что ищет человек
			$used_references = array("realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $user_id, $ads[$i]["id"], $arr["val_table"], $lang_ad);
					$ads[$i][$arr["key"]] = implode(",", $name);
				}
			}

			$used_references = array("gender");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserGenderIds($arr["spr_user_table"], $user_id, 0, $arr["val_table"]);
					$ads[$i][$arr["key"]] = $name;
				}
			}
			$gender_info = getDefaultUserIcon($ads[$i]["user_type"], $ads[$i]["gender"]);
			$default_photo =  $gender_info["icon_name"];

			if (strlen($row["upload_path"])>1){
				$ads[$i]["slideshowed"] = 1;
				$ads[$i]["thumb_file"][0] = $config["server"].$config["site_root"]."/uploades/photo/".$row["upload_path"];
			} else {
				$strSQL_img = "SELECT id as photo_id, upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$ads[$i]["id"]."' AND upload_type='f' AND status='1' AND admin_approve='1' ORDER BY sequence ASC";
				$rs_img = $dbconn->Execute($strSQL_img);
				$j = 0;
				if ($rs_img->fields[0]>0){
					while(!$rs_img->EOF){
						$row_img = $rs_img->GetRowAssoc(false);
						$ads[$i]["photo_id"][$j] = $row_img["photo_id"];

						$ads[$i]["photo_path"][$j] = $row_img["upload_path"];

						$path = $config["site_path"].$photo_folder."/".$ads[$i]["photo_path"][$j];
						$thumb_path = $config["site_path"].$photo_folder."/thumb_".$ads[$i]["photo_path"][$j];

						if(file_exists($path) && strlen($ads[$i]["photo_path"][$j])>0){
							$ads[$i]["file"][$j] = ".".$photo_folder."/".$ads[$i]["photo_path"][$j];
						}
						if(file_exists($thumb_path) && strlen($ads[$i]["photo_path"][$j])>0)
						$ads[$i]["thumb_file"][$j] = ".".$photo_folder."/thumb_".$ads[$i]["photo_path"][$j];
						if(!file_exists($path) || !strlen($ads[$i]["photo_path"][$j])){
							$ads[$i]["file"][$j] = ".".$photo_folder."/".$default_photo;
							$ads[$i]["thumb_file"][$j] = $ads[$i]["file"][$j];
						}
						$rs_img->MoveNext();
						$j++;
					}
				} else {
					$ads[$i]["thumb_file"][$j] = ".".$photo_folder."/".$default_photo;
				}
			}
			$rs->MoveNext();
			$i++;
		}
	}
	if ($ad_id_in_array == ""){
		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $ads_numpage));
	}

 	if ($ad_id_in_array != "") {
 		return (array_key_exists($ad_id_in_array-1, $ads)) ? array($ads[$ad_id_in_array-1]) : array();
 	} else {
 		return $ads;
 	}
}

/**
 * Get full information for listings wich ids are from $id_arr,
 * assign result array with smarty variable $search_result,
 * keep sort order and pages listing.
 *
 * @param array $id_arr
 * @param string $file_name
 * @param integer $page
 * @param string $param
 * @param string $order_link
 * @param integer $sorter
 * @param integer $sorter_order
 * @param string $par
 * @return void
 */
function getSearchArr($id_arr, $file_name, $page, $param, $order_link, $sorter=0, $sorter_order=1, $par="", $region="", $type="", $with_photo_arr=""){
	global $smarty, $dbconn, $user, $config, $REFERENCES;
	
	$search_result = array();
	$num_records = sizeof($id_arr);
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));

	// page
	$search_numpage = GetSiteSettings("ads_num_page");
	$lim_min = ($page-1)*$search_numpage;
	$lim_max = $search_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;
	switch ($sorter_order){
		case "1":
			$order_icon = "&darr;";
			break;
		default:
			$order_icon = "&uarr;";
			break;
	}
	$smarty->assign("order_icon", $order_icon);
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
	$smarty->assign("use_maps_in_search_results", GetSiteSettings("use_maps_in_search_results"));

	if ($num_records>0){
		$settings = GetSiteSettings();

		$sort_arr = getRealtySortOrder($sorter, $sorter_order);
		$sorter_str = " GROUP by ra.id ORDER BY ";
		$sorter_str .= ($sorter == 0 && $sorter_order == 0 && $par != "user_ads") ? "tsat.type DESC, tsat.date_begin DESC, " : "";
		$sorter_str .= $sort_arr["sorter_str"].$sort_arr["sorter_order"];

		$sorter_tolink = $sort_arr["sorter_tolink"];
		$sorter_topage = $sort_arr["sorter_topage"];

		/**
		 * in sort order all payed features are not used
		 */
		$topsearch_str = " LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=ra.id AND tsat.type='1' ";
		if ($sorter == 0 && $sorter_order == 0) {			
			if ($region){								
				$feature = GetFeatureInfo($region, "rent", $type);				
				
				if (sizeof($feature)>0){
					unset($_SESSION["feature"]);
					$_SESSION["feature"] = $feature;					
				}
			}						
			if ($_SESSION["feature"]["id_ad"]>0 && $_SESSION["feature"]["id_user"]!=$user[0] && $par !='by_nick' && $par !='new_members'){
				$feature_ad = GetFeatureRentAd($_SESSION["feature"]["id_ad"]);
				$feature_ad["headline"] = $_SESSION["feature"]["headline"];				
				$feature_ad["headline2"] = $_SESSION["feature"]["headline2"];				
				$feature_ad["period"] = $_SESSION["feature"]["period"];
				$feature_ad["curr_count"] = $_SESSION["feature"]["curr_count"];
				if ((is_array($feature_ad)) && sizeof($feature_ad)>0) {
					$smarty->assign("feature_ad", $feature_ad);
				}
				$feature_str = " AND ra.id <>'".$_SESSION["feature"]["id_ad"]."' ";
			}
		} else {
			$feature_str = "";
		}
		$if_photo = (!empty($with_photo_arr)) ? ", IF ( ra.id in ( ".implode(",", $with_photo_arr)." ),'1','0') AS with_photo" : "";
		if (!empty($with_photo_arr)){
			$sorter_str = str_replace("ORDER BY", "ORDER BY with_photo DESC, ", $sorter_str);
		}
		$add_fields = "";
        if($type == '2') {
            $add_fields = 'urp.payment_not_season, ';
        }
		$strSQL = "	SELECT DISTINCT	ra.id, ra.id_user, DATE_FORMAT(ra.movedate,'".$config["date_format"]."') as movedate, ra.type, ra.people_count, ra.room_type, ra.sold_leased_status, ra.status, ra.headline,
					u.fname, u.phone, u.user_type, tsat.type as topsearched,
					tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
					urp.min_payment, urp.max_payment, urp.auction,
					ct.name as country_name, rt.name as region_name, cit.name as city_name,
					hlt.id_friend, blt.id_enemy,
					url.id_region, url.adress as address, 
					ra.upload_path as slide_path, sp.order_id, sp.status as spstatus, " . $add_fields ."
					SUM(vst.visits_count) as visits, ft.id as featured". $if_photo."
					FROM ".RENT_ADS_TABLE." ra
					".$topsearch_str."
					LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id
					LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
					LEFT JOIN ".USERS_RENT_PAYS_TABLE." urp ON urp.id_ad=ra.id
					LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country
					LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region
					LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city
					LEFT JOIN ".HOTLIST_TABLE." hlt on ra.id_user=hlt.id_friend and hlt.id_user='".$user[0]."'
					LEFT JOIN ".BLACKLIST_TABLE." blt on ra.id_user=blt.id_enemy and blt.id_user='".$user[0]."'
					LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
					LEFT JOIN ".RENT_AD_VISIT_TABLE." vst ON vst.id_ad=ra.id
					LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=ra.id
					WHERE ra.id in ( ".implode(",", $id_arr)." ) ".$feature_str.$sorter_str.$limit_str;
		$rs = $dbconn->Execute($strSQL);

		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$search_result[$i]["number"] = ($page-1)*$search_numpage+($i+1);
			$search_result[$i]["id_ad"] = $row["id"];
			$search_result[$i]["people_count"] = $row["people_count"];
			$search_result[$i]["id_user"] = $row["id_user"];
			$search_result[$i]["id_region"] = $row["id_region"];
			if ($row["movedate"] != '00.00.0000'){
				$search_result[$i]["movedate"] = $row["movedate"];
			}
			$search_result[$i]["id_type"] = $row["type"];
			$search_result[$i]["topsearched"] = $row["topsearched"];
			if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
				$search_result[$i]["show_topsearch_icon"] = true;
				$search_result[$i]["topsearch_date_begin"] = $row["topsearch_date_begin"];
			}
			$search_result[$i]["login"] = $row["fname"];
			$search_result[$i]["user_type"] = $row["user_type"];
			$search_result[$i]["issponsor"] = $row["spstatus"];
			$search_result[$i]["sold_leased_status"] = $row["sold_leased_status"];
			$search_result[$i]["status"] = $row["status"];
			if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
				$search_result[$i]["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
			} else {
				$search_result[$i]["headline"] = stripslashes($row["headline"]);
			}
			$search_result[$i]["featured"] = $row["featured"];
			
			
			if ($search_result[$i]["id_type"] == 2){			
				$calendar_event = new CalendarEvent();
				$search_result[$i]["reserve"] = $calendar_event->GetEmptyPeriod($search_result[$i]["id_ad"], $search_result[$i]["id_user"]);
                $search_result[$i]['payment_not_season'] = $row['payment_not_season'];	
			}

			$lang_ad = 2; //т.к. выводим информацию о том, что ищет человек
			$used_references = array("gender", "realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
					if (count($name) == 0 && $arr["spr_match_table"] != ""){
						$name = GetUserAdSprValues($arr["spr_match_table"], $search_result[$i]["id_user"], $search_result[$i]["id_ad"], $arr["val_table"], $lang_ad);
					}
					$search_result[$i][$arr["key"]] = implode(",", $name);
				}
			}
			$search_result[$i]["min_payment"] = PaymentFormat($row["min_payment"]);
			$search_result[$i]["min_payment_show"] = FormatPrice($search_result[$i]["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$search_result[$i]["max_payment"] = PaymentFormat($row["max_payment"]);
			$search_result[$i]["max_payment_show"] = FormatPrice($search_result[$i]["max_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$search_result[$i]["auction"] = $row["auction"];

            if($type == '1') {
                $strSQL_payment = "SELECT * FROM ".USERS_RENT_PAYS_TABLE_BY_MONTH." a WHERE id_ad in (".
                    "SELECT id FROM ".RENT_ADS_TABLE." WHERE id ='{$row['id']}' OR parent_id ='{$row['id']}')";
                $priceRS = $dbconn->Execute($strSQL_payment);
                $prices = array();
                if($priceRS) {
                    while(!$priceRS->EOF) {
                        $priceRow = $priceRS->GetRowAssoc(false);
                        $pr = array($priceRow['january'], $priceRow['february'], $priceRow['march'], 
                            $priceRow['april'], $priceRow['may'], $priceRow['june'], $priceRow['july'], 
                            $priceRow['august'], $priceRow['september'], $priceRow['october'], 
                            $priceRow['november'], $priceRow['december']);
                        $prices = array_merge($prices, $pr);
                        $priceRS->MoveNext();
                    }
                    if(count($prices))
                        $search_result[$i]["min_payment"] = PaymentFormat(min($prices));
                }
            }
            elseif(!$row["min_payment"]) {
                $strSQL_payment = "SELECT min_payment as price FROM ".USERS_RENT_PAYS_TABLE." WHERE id_ad IN(SELECT id FROM ".RENT_ADS_TABLE." WHERE parent_id = '{$row['id']}')";
                $priceRS = $dbconn->Execute($strSQL_payment);
                $prices = array();
                if($priceRS) {
                    while(!$priceRS->EOF) {
                        $priceRow = $priceRS->GetRowAssoc(false);
                        $prices[] = $priceRow['price'];
                        $priceRS->MoveNext();
                    }
                    if(count($prices)){
                        $search_result[$i]["min_payment"] = PaymentFormat(min($prices));
                        $search_result[$i]["show_from"] = count($prices > 1) ? 1 : 0;
                    }
                }
            }
			$strSQL2 = "SELECT upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." ".
					   "WHERE id_ad='".$row["id"]."' AND upload_type='f' AND status='1' AND admin_approve='1' ".
					   "ORDER BY sequence ASC LIMIT 1";
			$rs2 = $dbconn->Execute($strSQL2);

			if (strlen($row["slide_path"])>1){
				$search_result[$i]["image"] = $settings["photo_folder"]."/".$row["slide_path"];
				$search_result[$i]["alt"] = $lang["default_select"]["slideshow"];
				$search_result[$i]["slideshowed"] = 1;
			} elseif ($rs2->RowCount() > 0){
				$img = $rs2->GetRowAssoc(false);
				$search_result[$i]["image"] = $settings["photo_folder"]."/thumb_".$img["upload_path"];
				$search_result[$i]["alt"] = $img["user_comment"];
			} else {
				$used_references = array("gender");
				foreach ($REFERENCES as $arr) {
					if (in_array($arr["key"], $used_references)) {
						$name = GetUserGenderIds($arr["spr_user_table"], $search_result[$i]["id_user"], 0, $arr["val_table"]);
						$search_result[$i][$arr["key"]] = $name;
					}
				}
				$gender_info = getDefaultUserIcon($search_result[$i]["user_type"], $search_result[$i]["gender"]);
				$search_result[$i]["num_gender"] =  $gender_info["num_gender"];
				$search_result[$i]["image"] =  $settings["photo_folder"]."/".$gender_info["icon_name"];
				$search_result[$i]["alt"] =  $gender_info["icon_alt"];
			}
			$search_result[$i]["adress"] = $row["address"];
			$search_result[$i]["id"] = $row["id"];
			$search_result[$i]["viewprofile_link"] = "./viewprofile.php?id=".$search_result[$i]["id_ad"];
			if ($config["lang_ident"]!='ru') {
				$search_result[$i]["country_name"] = RusToTranslit($row["country_name"]);
				$search_result[$i]["region_name"] = RusToTranslit($row["region_name"]);
				$search_result[$i]["city_name"] = RusToTranslit($row["city_name"]);
			} else {
				$search_result[$i]["country_name"] = $row["country_name"];
				$search_result[$i]["region_name"] = $row["region_name"];
				$search_result[$i]["city_name"] = $row["city_name"];
			}

			$suffix = "&id=".$search_result[$i]["id_user"]."&section=rent&id_ad=".$search_result[$i]["id_ad"];
			$search_result[$i]["mail_link"] = "./mailbox.php?sel=fs".$suffix;
			$search_result[$i]["contact_link"] = "./contact.php?sel=fs".$suffix;
			$search_result[$i]["interest_link"] = "./viewprofile.php?sel=interest".$suffix;

			if(intval($row["id_friend"])==0 && intval($row["id_enemy"])==0) {
				$search_result[$i]["addfriend_link"] = "./viewprofile.php?sel=addtohot".$suffix;
				$search_result[$i]["blacklist_link"] = "./viewprofile.php?sel=addtoblack".$suffix;
			}
			$rs->MoveNext();
			$i++;
		}
		
		if ($order_link != "") {
			$param = "sorter=".$sorter."&amp;order=".$sorter_topage.$param;
			$smarty->assign("order_active_link", "&order=".$sorter_tolink.$order_link);
			$smarty->assign("order_link", "&order=".$sorter_topage.$order_link);
		}
		$smarty->assign("links", GetLinkArray($num_records, $page, $file_name."?".$param, $lim_max));
		$smarty->assign("search_result", $search_result);
        
		$smarty->assign("empty_result", 0);
		
	} else {
		$smarty->assign("empty_result", 1);
	}
}


/**
 * Get info on listing, for wich user want to send contact message to the listing owner
 *
 * @param integer $id_ad
 * @param integer $id_user
 * @return array
 */
function GetContactAd($id_ad, $id_user) {
	global $dbconn, $config, $REFERENCES;

	$settings["photo_folder"] = GetSiteSettings("photo_folder");

	$strSQL = "	SELECT ra.type, ra.sold_leased_status, DATE_FORMAT(ra.movedate,'".$config["date_format"]."') as movedate,
				ra.headline,
				u.fname, u.phone, u.user_type,
				urp.min_payment, urp.max_payment, urp.auction, uru.upload_path,
				ct.name as country_name, rt.name as region_name, cit.name as city_name,
				ra.upload_path as slide_path, tsat.id as topsearched,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
				ft.id as featured, sp.status as spstatus
				FROM ".RENT_ADS_TABLE." ra
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." url ON url.id_ad=ra.id
				LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user
				LEFT JOIN ".USERS_RENT_PAYS_TABLE." urp ON urp.id_ad=ra.id
				LEFT JOIN ".USERS_RENT_UPLOADS_TABLE." uru ON uru.id_ad=ra.id AND uru.upload_type='f' AND uru.status='1' AND uru.admin_approve='1'
				LEFT JOIN ".COUNTRY_TABLE." ct ON ct.id=url.id_country
				LEFT JOIN ".REGION_TABLE." rt ON rt.id=url.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=url.id_city
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=ra.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=ra.id
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
				WHERE ra.id='$id_ad' AND u.id='$id_user'";

	$rs = $dbconn->Execute($strSQL);

	$row = $rs->GetRowAssoc(false);
	$profile["id_ad"] = $id_ad;
	$profile["id_user"] = $id_user;
	$profile["sold_leased_status"] = $row["sold_leased_status"];
	if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
		$profile["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
	} else {
		$profile["headline"] = stripslashes($row["headline"]);
	}
	$profile["issponsor"] = $row["spstatus"];
	if ($row["movedate"] != '00.00.0000'){
		$profile["movedate"] = $row["movedate"];
	}
	$profile["type"] = $row["type"];
	$profile["topsearched"] = $row["topsearched"];
	if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
		$profile["show_topsearch_icon"] = true;
		$profile["topsearch_date_begin"] = $row["topsearch_date_begin"];
	}
	$profile["featured"] = $row["featured"];
	$profile["fname"] = $row["fname"];
	$profile["phone"] = $row["phone"];
	$profile["user_type"] = $row["user_type"];
	
	if ($profile["type"] == 2){			
		$calendar_event = new CalendarEvent();
		$profile["reserve"] = $calendar_event->GetEmptyPeriod($profile["id_ad"], $profile["id_user"]);	
	}

	$profile["min_payment"] = PaymentFormat($row["min_payment"]);
	$profile["max_payment"] = PaymentFormat($row["max_payment"]);
	$profile["auction"] = $row["auction"];

	$lang_ad = 2;
	$used_references = array("realty_type");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$name = GetUserAdSprValues($arr["spr_user_table"], $profile["id_user"], $profile["id_ad"], $arr["val_table"], $lang_ad);
			if (count($name) == 0 && $arr["spr_match_table"] != ""){
				$name = GetUserAdSprValues($arr["spr_match_table"], $profile["id_user"], $profile["id_ad"], $arr["val_table"], $lang_ad);
				$profile[$arr["key"]."_match"] = implode(",", $name);
			} else {
				$profile[$arr["key"]] = implode(",", $name);
			}
		}
	}

	$used_references = array("gender");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$name = GetUserGenderIds($arr["spr_user_table"], $profile["id_user"], 0, $arr["val_table"]);
			$profile[$arr["key"]] = $name;
		}
	}
	$gender_info = getDefaultUserIcon($profile["user_type"], $profile["gender"]);
	$profile["num_gender"] =  $gender_info["num_gender"];
	$profile["icon_name"] =  $gender_info["icon_name"];

	if (strlen($row["slide_path"])>1){
		$profile["image"] = $settings["photo_folder"]."/".$row["slide_path"];
		$profile["slideshowed"] = 1;
	} else {
		if (strlen($row["upload_path"])>1){
			$profile["image"] = $settings["photo_folder"]."/thumb_".$row["upload_path"];
		} else {
			$profile["image"] = $settings["photo_folder"]."/".$profile["icon_name"];
		}
	}
	if ($config["lang_ident"]!='ru') {
		$profile["country_name"] = RusToTranslit($row["country_name"]);
		$profile["region_name"] = RusToTranslit($row["region_name"]);
		$profile["city_name"] = RusToTranslit($row["city_name"]);
	} else {
		$profile["country_name"] = $row["country_name"];
		$profile["region_name"] = $row["region_name"];
		$profile["city_name"] = $row["city_name"];
	}

	return $profile;
}

/**
 * Get array of user defined values of reference $spr_table for the listing with $id_ad
 *
 * @param string $user_table - reference user defined values table name, defined in /include/constants.php
 * @param integer $id_user
 * @param integer $id_ad
 * @param string $spr_table
 * @param string $value_table
 * @param integer $lang_add
 * @return mixed (array or void)
 */
function GetResArrName($user_table, $id_user, $id_ad, $spr_table, $value_table, $lang_add = 1){
	global $smarty, $config, $dbconn, $user, $multi_lang;

	$_val = $multi_lang->TableKey($value_table);
	$_spr = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName($lang_add);

	$strSQL = "SELECT DISTINCT id_spr FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ORDER BY id_spr";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);

		$strSQL = "	SELECT DISTINCT b.".$field_name." as name
					FROM ".$spr_table." a
					LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_spr."' AND b.id_reference=a.id
					WHERE a.id= '".$row["id_spr"]."' ";
		$rs_fname = $dbconn->Execute($strSQL);
		$name[$i]["name"] = $rs_fname->fields[0];
		$name[$i]["id_spr"] = $row["id_spr"];
		$strSQL_opt = "SELECT id_value FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' AND id_spr='".$row["id_spr"]."' ORDER BY id_value";
		$rs_opt = $dbconn->Execute($strSQL_opt);
		$j = 0;
		while(!$rs_opt->EOF){
			$row_opt = $rs_opt->GetRowAssoc(false);
			$arr[$i][$j] = $row_opt["id_value"];
			$strSQL_name = " 	SELECT ".$field_name." as name
								FROM ".REFERENCE_LANG_TABLE."
								WHERE table_key='".$_val."' AND id_reference='".$arr[$i][$j]."' ";
			$rs_name = $dbconn->Execute($strSQL_name);

			$name[$i]["fields"][$j] = $rs_name->fields[0];
			$rs_opt->MoveNext();
			$j++;
		}
		$rs->MoveNext();
		$i++;
	}
	if (isset($name)) {
		return $name;
	} else {
		return;
	}
}

/**
 * Save Search Preferences for the user
 *
 * @param integer $id_user
 * @return void
 */
function SaveSearchPreferences($id_user) {
	global $config, $dbconn, $REFERENCES;

	$strSQL = "SELECT id FROM ".SEARCH_PREFERENCES_TABLE." ".
			  "WHERE id_user = '".$id_user."' ";
	$rs = $dbconn->Execute($strSQL);

	/**
	 * Get values from $_REQUEST
	 */
	$type = (isset($_REQUEST["choise"]) && intval($_REQUEST["choise"])) ? intval($_REQUEST["choise"]) : 4;
	$min_payment = (isset($_REQUEST["min_payment"]) && !empty($_REQUEST["min_payment"])) ? intval($_REQUEST["min_payment"]) : 0;
	$max_payment = (isset($_REQUEST["max_payment"]) && !empty($_REQUEST["max_payment"])) ? intval($_REQUEST["max_payment"]) :0;
	$use_movedate = (isset($_REQUEST["use_movedate"])) ? intval($_REQUEST["use_movedate"]) : 0;
	$move_day = (isset($_REQUEST["move_day"]) && !empty($_REQUEST["move_day"])) ? intval($_REQUEST["move_day"]) : 0;
	$move_month = (isset($_REQUEST["move_month"]) && !empty($_REQUEST["move_month"])) ? intval($_REQUEST["move_month"]) : 0;	
	$with_photo = (isset($_REQUEST["photo"]) && !empty($_REQUEST["photo"]))? intval($_REQUEST["photo"]) : 0;
	$with_video = (isset($_REQUEST["video"]) && !empty($_REQUEST["video"]))? intval($_REQUEST["video"]) : 0;
	$used_references = array("realty_type", "description");
	$realty_type = 0;
	
	$beds_number = 0;
	$bath_number = 0;
	$garage_number = 0;
	
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$values = (isset($_REQUEST[$arr["key"]]) && !empty($_REQUEST[$arr["key"]])) ? $_REQUEST[$arr["key"]] : array();
			$spr = $_REQUEST["spr_".$arr["key"]];
			if (is_array($values) && is_array($spr)) {
				for ($i=0; $i<count($spr); $i++) {
					for ($j=0; $j<count($values[$i]); $j++) {
						if ($values[$i][$j]>0) {
							if ($arr["key"] == "realty_type") {
								$realty_type = $values[$i][$j];
							} elseif ($arr["key"] == "description") {
								switch ($spr[$i]) {
									case 1: {
										$beds_number = $values[$i][$j];		
									}
									break;
									case 2: {
										$bath_number = $values[$i][$j];		
									}
									break;
									case 3: {
										$garage_number = $values[$i][$j];		
									}
									break;									
								}								
							}
						}
					}
				}
			}
		}
	}
	/**
	 * Save values
	 */
	$strSQL = "type='$type', realty_type='$realty_type', beds_number='$beds_number', ".
			  "min_payment='$min_payment', max_payment='$max_payment', use_movedate='$use_movedate', ".
			  "move_day='$move_day', move_month='$move_month', with_photo='$with_photo', ".
			  "bath_number='$bath_number', garage_number='$garage_number', with_video='$with_video'";
	if ($rs->RowCount() > 0) {
		$strSQL = "UPDATE ".SEARCH_PREFERENCES_TABLE." SET ".$strSQL." WHERE id_user='$id_user'";
	} else {
		$strSQL = "INSERT INTO ".SEARCH_PREFERENCES_TABLE." SET id_user='$id_user', ".$strSQL;
	}
	$rs = $dbconn->Execute($strSQL);
	return;
}

/**
 * Get user search preferences
 *
 * @param intereg $id_user
 * @return array
 */
function GetSearchPreferences($id_user) {
	global $config, $dbconn;

	$strSQL = "SELECT id, type, use_movedate, realty_type, beds_number, ".
			  "min_payment, max_payment, move_day, move_month, with_photo, ".
			  "bath_number, garage_number, with_video ".
			  "FROM ".SEARCH_PREFERENCES_TABLE." ".
			  "WHERE id_user = '".$id_user."' ";
			  
	$rs = $dbconn->Execute($strSQL);
	$preferences = array();
	if ($rs->RowCount() > 0) {
		$preferences = $rs->GetRowAssoc( false );
		$preferences["choise"] = $preferences["type"];
		$preferences["photo"] = $preferences["with_photo"];
		$preferences["video"] = $preferences["with_video"];
	}

	return $preferences;
}

/**
 * Save users' search location preferences
 *
 * @param integer $id_user
 * @return void;
 */
function SaveSearchLocation($id_user) {
	global $config, $dbconn;

	$id_country = (isset($_REQUEST["country"]) && !empty($_REQUEST["country"])) ? intval($_REQUEST["country"]) : 0;
	if ($id_country > 0) {
		$id_region = intval($_REQUEST["region"]);
		$id_city = intval($_REQUEST["city"]);

		$strSQL = "SELECT id FROM ".SEARCH_LOCATION_TABLE." ".
				  "WHERE id_user = '".$id_user."' AND id_country='$id_country' AND ".
				  "id_region='$id_region' AND id_city='$id_city'";
		$rs = $dbconn->Execute($strSQL);

		if ($rs->RowCount() == 0) {
			$strSQL = "SELECT id FROM ".SEARCH_LOCATION_TABLE." ".
				  	  "WHERE id_user = '".$id_user."'";
			$rs = $dbconn->Execute($strSQL);
			/**
			 * if it is first search - save it as primary and preferred
			 */
			$is_primary = ($rs->RowCount() > 0) ? 0 : 1;
			$is_preferred = ($rs->RowCount() > 0) ? 0 : 1;

			$strSQL = "INSERT INTO ".SEARCH_LOCATION_TABLE." SET ".
					  "id_user = '".$id_user."', id_country='$id_country', id_region='$id_region', ".
					  "id_city='$id_city', is_preferred='$is_preferred', is_primary='$is_primary'";
			$rs = $dbconn->Execute($strSQL);
		}
	}
	return;
}

/**
 * Get users' primary search location
 *
 * @param integer $id_user
 * @return array
 */
function GetPrimarySearchLocation($id_user) {
	global $config, $dbconn;

	$strSQL = "SELECT lt.id, lt.id_country, lt.id_region, lt.id_city, ".
			  "country.name AS country_name, region.name AS region_name, city.name AS city_name ".
			  "FROM ".SEARCH_LOCATION_TABLE." lt ".
			  "LEFT JOIN ".COUNTRY_TABLE." country ON country.id=lt.id_country ".
			  "LEFT JOIN ".REGION_TABLE." region ON region.id=lt.id_region ".
			  "LEFT JOIN ".CITY_TABLE." city ON city.id=lt.id_city ".
			  "WHERE lt.id_user = '".$id_user."' AND lt.is_primary='1'";
	$rs = $dbconn->Execute($strSQL);
	$location = array();
	if ($rs->RowCount() > 0) {
		$location = $rs->GetRowAssoc( false );
	} else {
		$location["id_country"] = 0;
		$location["id_region"] = 0;
		$location["id_city"] = 0;
		$location["country_name"] = "";
		$location["region_name"] = "";
		$location["city_name"] = "";
	}
	return $location;
}

/**
 * Get users' search location by search id
 *
 * @param integer $id_user
 * @return array
 */
function GetSearchLocationById($id) {
	global $config, $dbconn;

	$strSQL = "SELECT id_country, id_region, id_city FROM ".SEARCH_LOCATION_TABLE." ".
			  "WHERE id='".$id."'";
	$rs = $dbconn->Execute($strSQL);
	$location = array();
	if ($rs->RowCount() > 0) {
		$location = $rs->GetRowAssoc( false );
	}
	return $location;
}

/**
 * Get users' search location list
 *
 * @param integer $id_user
 * @param boolean $is_preferred
 * @return array
 */
function GetSearchLocationList($id_user, $is_preferred = true) {
	global $config, $dbconn;
	$strSQL = "SELECT lt.id, lt.id_country, lt.id_region, lt.id_city, lt.is_primary, ".
			  "country.name AS country_name, region.name AS region_name, city.name AS city_name ".
			  "FROM ".SEARCH_LOCATION_TABLE." lt ".
			  "LEFT JOIN ".COUNTRY_TABLE." country ON country.id=lt.id_country ".
			  "LEFT JOIN ".REGION_TABLE." region ON region.id=lt.id_region ".
			  "LEFT JOIN ".CITY_TABLE." city ON city.id=lt.id_city ".
			  "WHERE lt.id_user = '".$id_user."' AND lt.is_preferred='$is_preferred' ORDER BY ".(($is_preferred) ? "lt.is_primary" : "lt.id")." DESC";
	$rs = $dbconn->Execute($strSQL);
	$locations = array();
	if ($rs->RowCount() > 0) {
		while (!$rs->EOF) {
			$locations[] = $rs->GetRowAssoc( false );
			$rs->MoveNext();
		}
	}
	return $locations;
}

/**
 * Genarte HTML code for displaying banners, save viewing statistics
 *
 * @param string $file
 * @return string
 */
function Banners($file){
	global $config, $smarty, $dbconn, $user;

	/**
	* Check, if page is viewed from Admin area, not increment banners showing history
	*/
	$view_from_admin = (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1) ? 1 : 0;

	// Seaching for area id
	$strSQL = "SELECT id, left_place, bottom_place, center_place, register_part, unregister_part ".
			  "FROM ".BANNERS_AREA_TABLE." ".
			  "WHERE file_name='".$file."'";
	$rs = $dbconn->Execute($strSQL);
	if (($rs===false) || ($rs->EOF)) {
		return;
	}
	$area = $rs->GetRowAssoc(false);

	$unregister_user = ($user[3] == 1) ? 1 : 0;
	// Get possible for the area banners
	$strSQL = "SELECT banner_id ".
			  "FROM ".BANNERS_BELONGS_AREA_TABLE." ".
			  "WHERE area_id='".$area["id"]."' AND ".
			  (($unregister_user) ? "unregister_part='1'" : "register_part='1'");
	$rs = $dbconn->Execute($strSQL);
	$banners_ids = array();
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc( false );
		$banners_ids[] = $row["banner_id"];
		$rs->MoveNext();
	}

	// Get rotate banners settings
	$rotate_left_flag=0; $rotate_bottom_flag=0; $rotate_center_flag=0;
	$strSQL = "select a.position, a.rotate_flag, a.rotate_time from ".BANNERS_ROTATE_TABLE." a";
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);
		if ($row["position"]==0) { $rotate_left_flag=$row["rotate_flag"]; $rotate_left_time=$row["rotate_time"]; }
		if ($row["position"]==1) { $rotate_bottom_flag=$row["rotate_flag"]; $rotate_bottom_time=$row["rotate_time"]; }
		if ($row["position"]==2) { $rotate_center_flag=$row["rotate_flag"]; $rotate_center_time=$row["rotate_time"]; }
		$rs->MoveNext();
	}

	$banners_html= array();
	$banners_html["left"] = "";
	$banners_html["bottom"] = "";
	$banners_html["center"] = "";
	if ($rotate_left_flag) {
		$banners_html["left"] ="\n<script language=\"JavaScript\" type=\"text/javascript\">\n";
		$banners_html["left"].=" var rotate_left_banner_timer = setInterval(RotateBannersLeft, 0);\n";
		$banners_html["left"].=" var rotate_left_banner_id=0;\n";
		$banners_html["left"].="function RotateBannersLeft()\n";
		$banners_html["left"].="{\n";
		$banners_html["left"].="clearInterval(rotate_left_banner_timer);\n";
	}
	if ($rotate_bottom_flag) {
		$banners_html["bottom"] ="\n<script language=\"JavaScript\" type=\"text/javascript\">\n";
		$banners_html["bottom"].=" var rotate_bottom_banner_timer = setInterval(RotateBannersBottom, 0);\n";
		$banners_html["bottom"].=" var rotate_bottom_banner_id=0;\n";
		$banners_html["bottom"].="function RotateBannersBottom()\n";
		$banners_html["bottom"].="{\n";
		$banners_html["bottom"].="clearInterval(rotate_bottom_banner_timer);\n";
	}
	if ($rotate_center_flag){
		$banners_html["center"] ="\n<script language=\"JavaScript\" type=\"text/javascript\">\n";
		$banners_html["center"].=" var rotate_center_banner_timer = setInterval(RotateBannersCenter, 0);\n";
		$banners_html["center"].=" var rotate_center_banner_id=0;\n";
		$banners_html["center"].="function RotateBannersCenter()\n";
		$banners_html["center"].="{\n";
		$banners_html["center"].="clearInterval(rotate_center_banner_timer);\n";
	}

	// Get banners
	$left_banners_count=0; $left_divs="";
	$bottom_banners_count=0; $bottom_divs="";
	$center_banners_count=0; $center_divs="";

	$current_time_formated=date('Y-m-d', time());

	$strSQL = "SELECT a.*, c.size_x, c.size_y FROM ".BANNERS_TABLE." a ".
			  "LEFT JOIN ".BANNERS_SIZES_TABLE." c ON a.size_id=c.id ".
	          "WHERE a.id IN ('".implode("','", $banners_ids)."') AND a.status=1 AND ".
	          "(a.stop_after_date_num>'".$current_time_formated."' OR a.stop_after_date_num='0000-00-00')";

	$rs = $dbconn->Execute($strSQL);

	while (!$rs->EOF) {
		$banner = $rs->GetRowAssoc(false);
		if ($banner["type"] == "image") {
			$banner["html_code"] = "";
			if ($banner["url"] != "") {
				$banner["html_code"] .= "<a href='".$config["server"].$config["site_root"]."/admin/admin_banners_activate.php?id=".$banner["id"].(($view_from_admin) ? "&view_from_admin=1" : "")."' ".(($banner["open_in_new_window"]) ? "target='_blank'" : "" ).">";
			}
			$banner["html_code"] .= "<img src='".$config["server"].$config["site_root"]."/uploades/adcomps/".$banner["file_path"]."' width='".$banner["size_x"]."' height='".$banner["size_y"]."' alt='".$banner["alt_text"]."' style='border: none;'>";
			if ($banner["url"] != "") {
				$banner["html_code"] .= "</a>";
			}
		}

		$not_stoped = 1;

		$hits = 0;
		$views = 0;
		$strSQL = "SELECT hits, views FROM ".BANNERS_TEMP_STATISTICS_TABLE." ".
				  "WHERE banner_id='".$banner["id"]."'";
		$temp_stat_rs = $dbconn->Execute($strSQL);
		if ($temp_stat_rs->RowCount() > 0) {
			$temp_stat = $temp_stat_rs->getRowAssoc(false);
			$hits = $temp_stat["hits"];
			$views = $temp_stat["views"];
		}

		/**
		 * Check if banners is stoped by its properties
		 */
		if ($banner["stop_after_views"] == 1 && $banner["stop_after_views_num"] < $views) {
			$not_stoped = 0;
		}
		if ($banner["stop_after_hits"] == 1 && $banner["stop_after_hits_num"] < $hits) {
			$not_stoped = 0;
		}

		if ($not_stoped) {
		/**
		 * save statistics
		 */
			if (!$view_from_admin) {
				/**
				 * Save views number to temp statistics
				 */
				$strSQL = "";
				$strSQL .= "views='".($views+1)."'";
				if ($views == 0) {
					$strSQL = "INSERT INTO ".BANNERS_TEMP_STATISTICS_TABLE." SET ".$strSQL.", ".
							  "banner_id='".$banner["id"]."'";
				} else {
					$strSQL = "UPDATE ".BANNERS_TEMP_STATISTICS_TABLE." SET ".$strSQL." ".
							  "WHERE banner_id='".$banner["id"]."'";
				}
				$dbconn->Execute($strSQL);
				/**
				 * Save views number to global statistics
				 */
				$strSQL = "SELECT id, views FROM ".BANNERS_GLOBAL_STATISTICS_TABLE." ".
						  "WHERE banner_id='".$banner["id"]."' AND date='".date('Y-m-d')."'";
				$glob_stat_rs = $dbconn->Execute($strSQL);
				if ($glob_stat_rs->RowCount() > 0) {
					$glob_stat = $glob_stat_rs->getRowAssoc( false );

					$strSQL = "UPDATE ".BANNERS_GLOBAL_STATISTICS_TABLE." SET ".
							  "views='".++$glob_stat["views"]."' ".
							  "WHERE id='".$glob_stat["id"]."'";
				} else {
					$strSQL = "INSERT INTO ".BANNERS_GLOBAL_STATISTICS_TABLE." SET ".
							  "views='1', banner_id='".$banner["id"]."', date=NOW()";
				}
				$dbconn->Execute($strSQL);
			}
		/**
		 * Generate code for showing banner
		 */
			if ($banner["position"]==0) {
			// Left
				
				if ($rotate_left_flag) {
				  $left_divs.="\n".'<DIV align=center name="left_banners_div'.$left_banners_count.'" id="left_banners_div'.$left_banners_count.'" style="visibility:hidden; position: absolute; left:0; top:0;">';
				  $left_divs.="\n".$banner["html_code"];
				  $left_divs.="\n</DIV>\n";
				  $banners_html["left"].='if (document.getElementById("left_banners_div'.$left_banners_count.'")) document.getElementById("left_banners_div'.$left_banners_count.'").style.visibility="hidden";'."\n";
				  $banners_html["left"].='if (document.getElementById("left_banners_div'.$left_banners_count.'")) document.getElementById("left_banners_div'.$left_banners_count.'").style.position="absolute";'."\n";
				} else {
				  $banners_html["left"].= "\n<div>".$banner["html_code"]."\n</div>\n";
				}
				$left_banners_count++;
			} elseif ($banner["position"]==1) {
			//Bottom
				
				if ($rotate_bottom_flag) {
					$bottom_divs.="\n".'<DIV align=center name="bottom_banners_div'.$bottom_banners_count.'" id="bottom_banners_div'.$bottom_banners_count.'" style="visibility:hidden; position: absolute; left:0; top:0;">';
					$bottom_divs.="\n".$banner["html_code"];
					$bottom_divs.="\n</DIV>\n";
					$banners_html["bottom"].='if (document.getElementById("bottom_banners_div'.$bottom_banners_count.'")) document.getElementById("bottom_banners_div'.$bottom_banners_count.'").style.visibility="hidden";'."\n";
					$banners_html["bottom"].='if (document.getElementById("bottom_banners_div'.$bottom_banners_count.'")) document.getElementById("bottom_banners_div'.$bottom_banners_count.'").style.position="absolute";'."\n";;
				} else {
				 	$banners_html["bottom"].=$banner["html_code"];
				}
				$bottom_banners_count++;
	        } elseif ($banner["position"]==2) {
	        //Center
	        	
				if ($rotate_center_flag) {
					$center_divs.="\n".'<DIV align=center name="center_banners_div'.$center_banners_count.'" id="center_banners_div'.$center_banners_count.'" style="visibility:hidden; position: absolute; left:0; top:0;">';
					$center_divs.="\n".$banner["html_code"];
					$center_divs.="\n</DIV>\n";
					$banners_html["center"].='if (document.getElementById("center_banners_div'.$center_banners_count.'")) document.getElementById("center_banners_div'.$center_banners_count.'").style.visibility="hidden";'."\n";
					$banners_html["center"].='if (document.getElementById("center_banners_div'.$center_banners_count.'")) document.getElementById("center_banners_div'.$center_banners_count.'").style.position="absolute";'."\n";;
				} else {
					$banners_html["center"].=$banner["html_code"];
				}
				$center_banners_count++;
	        }
		}
		$rs->MoveNext();
	}

	if ($rotate_left_flag) {
		if ($left_banners_count) {
		    $banners_html["left"].="if (document.getElementById('left_banners_div'+rotate_left_banner_id)) document.getElementById('left_banners_div'+rotate_left_banner_id).style.visibility=\"\";\n";
		    $banners_html["left"].="if (document.getElementById('left_banners_div'+rotate_left_banner_id)) document.getElementById('left_banners_div'+rotate_left_banner_id).style.position=\"\";\n";
		    $banners_html["left"].="rotate_left_banner_id++;\n";
		    $banners_html["left"].="if (rotate_left_banner_id>".($left_banners_count-1).") rotate_left_banner_id=0;\n";
		    $banners_html["left"].="rotate_left_banner_timer = setInterval(RotateBannersLeft, $rotate_left_time);\n";
		    $banners_html["left"].="}\n";
		    $banners_html["left"].="</script>\n";
		    $banners_html["left"].=$left_divs;
		} else {
		    $banners_html["left"].="}\n";
		    $banners_html["left"].="</script>\n";
		}
	}

	if ($rotate_bottom_flag) {
		if ($bottom_banners_count) {
			$banners_html["bottom"].="if (document.getElementById('bottom_banners_div'+rotate_bottom_banner_id)) document.getElementById('bottom_banners_div'+rotate_bottom_banner_id).style.visibility=\"\";\n";
			$banners_html["bottom"].="if (document.getElementById('bottom_banners_div'+rotate_bottom_banner_id)) document.getElementById('bottom_banners_div'+rotate_bottom_banner_id).style.position=\"\";\n";
			$banners_html["bottom"].="rotate_bottom_banner_id++;\n";
			$banners_html["bottom"].="if (rotate_bottom_banner_id>".($bottom_banners_count-1).") rotate_bottom_banner_id=0;\n";
			$banners_html["bottom"].="rotate_bottom_banner_timer = setInterval(RotateBannersBottom, $rotate_bottom_time);\n";
			$banners_html["bottom"].="}\n";
			$banners_html["bottom"].="</script>\n";
			$banners_html["bottom"].=$bottom_divs;
		} else {
			$banners_html["bottom"].="}\n";
			$banners_html["bottom"].="</script>\n";
		}
	}

	if ($rotate_center_flag) {
		if ($center_banners_count) {
			$banners_html["center"].="if (document.getElementById('center_banners_div'+rotate_center_banner_id)) document.getElementById('center_banners_div'+rotate_center_banner_id).style.visibility=\"\";\n";
			$banners_html["center"].="if (document.getElementById('center_banners_div'+rotate_center_banner_id)) document.getElementById('center_banners_div'+rotate_center_banner_id).style.position=\"\";\n";
			$banners_html["center"].="rotate_center_banner_id++;\n";
			$banners_html["center"].="if (rotate_center_banner_id>".($center_banners_count-1).") rotate_center_banner_id=0;\n";
			$banners_html["center"].="rotate_center_banner_timer = setInterval(RotateBannersCenter, $rotate_center_time);\n";
			$banners_html["center"].="}\n";
			$banners_html["center"].="</script>\n";
			$banners_html["center"].=$center_divs;
		} else {
			$banners_html["center"].="}\n";
			$banners_html["center"].="</script>\n";
		}
	}

	$smarty->assign("banner", $banners_html);
	return;
}

/**
 * Get bonus settings array
 *
 * @param void
 * @return array
 */
function GetBonusSettings() {
	global $config, $dbconn;

	$bonus = array();
	$strSQL = "SELECT id, percent, amount FROM ".BONUS_SETTINGS_TABLE." ORDER BY percent ASC";
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$bonus[] = $rs->getRowAssoc( false );
		$rs->MoveNext();
	}
	return $bonus;
}

/**
 * Get sell/lease settings
 *
 * @param void
 * @return array
 */
function GetSellLeaseSettings() {
	global $config, $dbconn;

	$sell_lease = array();
	$strSQL = "SELECT id, ads_number, amount FROM ".SELL_LEASE_PAYMENT_SETTINGS_TABLE." ORDER BY ads_number ASC";
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$sell_lease[] = $rs->getRowAssoc( false );
		$rs->MoveNext();
	}
	return $sell_lease;
}

/**
 * Get user sell/lease payment and already posted number of ads with sell/lease type
 *
 * @param void
 * @return array
 */
function GetSellLeaseUserPayment() {
	global $config, $dbconn, $user;

	$user_sell_lease = array();
	$strSQL = "SELECT ads_number, amount, used_ads_number ".
			  "FROM ".USER_SELL_LEASE_PAYMENT_TABLE." ".
			  "WHERE id_user='{$user[0]}'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount() > 0) {
		$user_sell_lease = $rs->GetRowAssoc( false );
	}
	return $user_sell_lease;
}

/**
 * Get string name or ad type by its id
 *
 * @param integer $id_type
 * @return string
 */
function GetAdTypeName($id_type) {
	switch ($id_type) {
		case 1:
			return "rent";
		case 2:
			return "lease";
		case 3:
			return "buy";
		case 4:
			return "sell";
	}
}
/**
 * Get ads' id which are in users' comparison list
 *
 * @return array
 */
function GetUserComparisonIds($reference_values = true) {
	global $config, $dbconn, $user, $REFERENCES, $multi_lang;

	if (!$multi_lang) {
		include_once "class.lang.php";
		$multi_lang = new MultiLang($config, $dbconn);
	}

	//for guest user
	if (isset($user[3])){
		$and_str = ($user[3] == 1) ? "AND cl.session='".$user[12]."'" : "";
	}else{
		$and_str = "";
	}

	$strSQL = "SELECT DISTINCT ra.id, ra.id_user, ra.type, u.fname ".
			  "FROM ".RENT_ADS_TABLE." ra ".
			  "LEFT JOIN ".USERS_TABLE." u ON u.id=ra.id_user ".
			  "LEFT JOIN ".COMPARISON_LIST_TABLE." cl ON cl.id_ad=ra.id ".
			  "WHERE cl.id_user='".$user[0]."' $and_str ORDER BY cl.id DESC";
	$rs = $dbconn->Execute($strSQL);
	$ads = array();
	while (!$rs->EOF) {
		$ad = $rs->getRowAssoc( false );
		if ($reference_values) {
			$lang_ad = 2; //show info about what user want
			$used_references = array("realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $ad["id_user"], $ad["id"], $arr["val_table"], $lang_ad);
					if (count($name) == 0 && $arr["spr_match_table"] != ""){
						$name = GetUserAdSprValues($arr["spr_match_table"], $ad["id_user"], $ad["id"], $arr["val_table"], $lang_ad);
					}
					$ad[$arr["key"]] = implode(",", $name);
				}
			}
		}
		$ad["type"] = GetAdTypeName($ad["type"]);

		$ads[] = $ad;
		$rs->MoveNext();
	}
	return $ads;
}


/**
 * Get Ads' profile settings
 *
 * @param integer $id_ad
 * @param integer $id_user - current user id
 * @return array
 */
function Ad($id_ad, $id_user, $file_name = "", $sect = "", $order_by_comparison = false) {
	global $config, $dbconn, $smarty, $user, $REFERENCES, $multi_lang;
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	$photo_folder = GetSiteSettings("photo_folder");
	$video_folder = GetSiteSettings('video_folder');
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));

	$ads = array();

	$ids = (is_array($id_ad)) ? implode("','", $id_ad) : $id_ad;

	$strSQL = "	SELECT DISTINCT(a.id) AS id, a.id_user, a.type, a.status, ".
			  "	DATE_FORMAT(a.datenow, '".$config["date_format"]."' ) as movedate,
				a.comment, a.with_photo, a.with_video, a.upload_path, a.sold_leased_status, a.headline,
				urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress,
				count.name as country_name, reg.name as region_name, cit.name as city_name, cit.lat as lat, cit.lon as lon,
				hlt.id_friend, blt.id_enemy,
				tsat.type as topsearched, ut.user_type,
				tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end, sp.status as spstatus
				FROM ".RENT_ADS_TABLE." a
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON a.id=urlt.id_ad
				LEFT JOIN ".COUNTRY_TABLE." count ON count.id=urlt.id_country
				LEFT JOIN ".REGION_TABLE." reg ON reg.id=urlt.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=urlt.id_city
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=a.id_user
				LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=a.id
				LEFT JOIN ".HOTLIST_TABLE." hlt on a.id_user=hlt.id_friend and hlt.id_user='".$id_user."'
				LEFT JOIN ".BLACKLIST_TABLE." blt on a.id_user=blt.id_enemy and blt.id_user='".$id_user."'
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=a.id AND tsat.type='1' ";
	if ($order_by_comparison) {
		//for guest user
		$and_str = ($user[3] == 1) ? "AND cl.session='".$user[12]."'" : "";

		$strSQL .= "LEFT JOIN ".COMPARISON_LIST_TABLE." cl ON cl.id_ad=a.id $and_str ";
	}
	$strSQL .= "WHERE a.id IN ('".$ids."') ";
	if (!$order_by_comparison) {
		/**
		 * get Ad not for comparison - so, could not get info on unactive ad
		 */
		$strSQL .= "AND a.status='1' ";
	}
	if ($order_by_comparison) {
		$strSQL .= "AND cl.id_user='".$id_user."' ORDER BY cl.id DESC";
	}
	$rs = $dbconn->Execute($strSQL);
	while (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);
		$profile = $row;

		$profile["street_1"] = stripslashes(strip_tags($row["street_1"]));
		$profile["street_2"] = stripslashes(strip_tags($row["street_2"]));
		$profile["adress"] = stripslashes(strip_tags($row["adress"]));
		$profile["sold_leased_status"] = $row["sold_leased_status"];
		$profile["issponsor"] = $row["spstatus"];
		$profile["headline"] = stripslashes($row["headline"]);
		if (utf8_strlen($profile["headline"]) > GetSiteSettings("headline_preview_size")) {
			$profile["headline_short"] = utf8_substr(stripslashes($profile["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
		} else {
			$profile["headline_short"] = $profile["headline"];
		}
						
		if ($row["movedate"] != '00.00.0000'){
			$profile["movedate"] = $row["movedate"];
		}

		$profile["comment"] = stripslashes($row["comment"]);

		if ($config["lang_ident"]!='ru') {
			$profile["country_name"] = RusToTranslit($row["country_name"]);
			$profile["region_name"] = RusToTranslit($row["region_name"]);
			$profile["city_name"] = RusToTranslit($row["city_name"]);
		}
		/**
		 * Check if pay services were used for this ad
		 */
		if ($row["upload_path"] !=''){
			$profile["slideshowed"] = 1;
		}
		if (IsFeatured($profile["id_user"], $profile["id"])) {
			$profile["featured"] = 1;
		}
		if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
			$profile["show_topsearch_icon"] = true;
		}
				
		if ($profile["type"] == 2){			
			$calendar_event = new CalendarEvent();
			$profile["reserve"] = $calendar_event->GetEmptyPeriod($profile["id"], $profile["id_user"]);		
		}	
		
		
		/**
		 * Get links array for displaying in viewprofile.php
		 */
		if ($sect) {
			$suffix = "&amp;id=".$profile["id_user"]."&amp;section=".$sect."&amp;id_ad=".$profile["id"];
			$smarty->assign("suffix_2", "?id=".$profile["id"]);

			$profile["mail_link"] = "./mailbox.php?sel=fs".$suffix;
			$profile["contact_link"] = "./contact.php?sel=fsv".$suffix;
			$profile["print_link"] = "./$file_name?sel=print&id=".$profile["id"];

			$str_int = "SELECT id FROM ".INTERESTS_TABLE." WHERE id_user='".$id_user."' AND id_interest_user='".$profile["id_user"]."' AND id_interest_ad='".$profile["id"]."'";
			$rs_int = $dbconn->Execute($str_int);
			if ($rs_int->fields[0]>0) {
				$profile["interested"] = 1;
			}
			$profile["interest_link"] = "./$file_name?sel=interest".$suffix;

			$str = " SELECT COUNT(id) AS kol FROM ".RENT_ADS_TABLE." WHERE id_user='".$profile["id_user"]."' AND id<>'".$profile["id"]."' AND status='1' ";
			$rs_more = $dbconn->Execute($str);
			if ($rs_more->fields[0] > 0) {
				$profile["more_link"] = "./$file_name?sel=more_ad&id_user=".$profile["id_user"];
			}

			if(intval($row["id_friend"])==0 && intval($row["id_enemy"])==0) {
				$profile["addfriend_link"] = "./$file_name?sel=addtohot".$suffix;
				$profile["blacklist_link"] = "./$file_name?sel=addtoblack".$suffix;
			}
		}

		/**
		 * Get ads' owner preferences
		 */
		$strSQL_age = "SELECT his_age_1, his_age_2 FROM ".USERS_RENT_AGES_TABLE." ".
					  "WHERE id_user='".$profile["id_user"]."' AND id_ad='".$profile["id"]."'";
		$rs_age = $dbconn->Execute($strSQL_age);
		$row_age = $rs_age->GetRowAssoc(false);
		$profile["his_age_1"] = $row_age["his_age_1"];
		$profile["his_age_2"] = $row_age["his_age_2"];

		/**
		 * Get numerical values
		 */
		if ($profile["type"] == "1" || $profile["type"] == "3") {
			$strSQL_payment = "SELECT min_payment, max_payment, auction, min_deposit, max_deposit, ".
							  "min_live_square, max_live_square, min_total_square, max_total_square, ".
							  "min_land_square, max_land_square, min_floor,  max_floor, floor_num, subway_min, ".
							  "min_year_build, max_year_build, furniture, floor, floors, min_flats_square, max_flats_square, total_square, ceil_height, sea_distance, term, investor, parking " .
							  "FROM ".USERS_RENT_PAYS_TABLE." ".
							  "WHERE id_ad='".$profile["id"]."' AND id_user='".$profile["id_user"]."' ";
                              
		} elseif ($profile["type"] == "2" || $profile["type"] == "4") {
			/**
			 * hold fixed values for listings type (lease, sell) in fields min_<field_name>
			 */
			$strSQL_payment = "SELECT min_payment, auction, min_deposit, ".
							  "min_live_square, min_total_square, ".
							  "min_land_square, min_floor, floor_num, subway_min, min_year_build, furniture, payment_not_season, hotel, days, route, facilities, meals  ".
							  "FROM ".USERS_RENT_PAYS_TABLE." ".
							  "WHERE id_ad='".$profile["id"]."' AND id_user='".$profile["id_user"]."' ";
		}
		$rs_payment = $dbconn->Execute($strSQL_payment);
		$row_payment = $rs_payment->GetRowAssoc(false);
		$profile = array_merge($profile, $row_payment);

		$profile["type_name"] = GetAdTypeName($profile["type"]);
		$profile["min_payment"] = PaymentFormat($profile["min_payment"]);
		$profile["min_payment_show"] = FormatPrice($profile["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["max_payment"] = (isset($profile["max_payment"])) ? PaymentFormat($profile["max_payment"]) : 0;
		$profile["max_payment_show"] = FormatPrice($profile["max_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["min_deposit"] = PaymentFormat($profile["min_deposit"]);
		$profile["min_deposit_show"] = FormatPrice($profile["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
		$profile["max_deposit"] = (isset($profile["max_deposit"])) ? PaymentFormat($profile["max_deposit"]) : 0;
		$profile["max_deposit_show"] = FormatPrice($profile["max_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);

		/**
		 * Get references values for the ad
		 */
		$lang_add = 2; //describe match variant
		$used_references = array("info", "gender", "people", "language", "period", "realty_type", "description", "theme_rest");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {

				if ($arr["spr_match_table"] != "") {
				/**
				 * human description
				 */
					//match human description
					$profile[$arr["key"]."_match"] = GetResArrName($arr["spr_match_table"], $profile["id_user"], $profile["id"], $arr["spr_table"], $arr["val_table"], $lang_add);
					//my description
					$profile[$arr["key"]] = GetResArrName($arr["spr_user_table"], $profile["id_user"], 0, $arr["spr_table"], $arr["val_table"]);
				} else {
					$profile[$arr["key"]] = GetResArrName($arr["spr_user_table"], $profile["id_user"], $profile["id"], $arr["spr_table"], $arr["val_table"]);
				}
			}
		}
		$lang_add = 2;
		$used_references = array("realty_type");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$name = GetUserAdSprValues($arr["spr_user_table"], $profile["id_user"], $profile["id"], $arr["val_table"], $lang_add);
				$profile[$arr["key"]."_in_line"] = implode(",", $name);
			}
		}

		/**
		 * Get owners account info
		 */
		$profile["account"] = GetAccountTableInfo($profile["id_user"]);

		$used_references = array("gender");
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$name = GetUserGenderIds($arr["spr_user_table"], $profile["id_user"], 0, $arr["val_table"]);
				$tmp_arr[$arr["key"]] = $name;
			}
		}
		$gender_info = getDefaultUserIcon($profile["user_type"], $tmp_arr["gender"]);
		$default_photo = $gender_info["icon_name"];
		$default_photo_alt = $gender_info["icon_alt"];
		
		/**
		 *  Get company data
		 */
		if ($profile["user_type"] == 3){
			$strSQL2 = "SELECT aoc.id_company, uf.photo_path, uf.approve FROM ".AGENT_OF_COMPANY_TABLE." aoc 
					LEFT JOIN ".USER_PHOTOS_TABLE." uf ON aoc.id_agent = uf.id_user WHERE aoc.id_agent = '".$profile["id_user"]."' AND aoc.approve = '1'";
			
			$rs2 = $dbconn->Execute($strSQL2);
			if ($rs2->fields[0] > 0){
				$profile["company_data"] = GetAccountTableInfo($rs2->fields[0]);
				if ($profile["company_data"]["country_name"] == ''){
					$profile["company_data"]["in_base"]=0;
				}
				else {$profile["company_data"]["in_base"]=1;
				}							
				$profile["company_data"]["photo_path"]=$config["server"].$config["site_root"]."/uploades/photo/".addslashes($rs2->fields[1]);				
				$profile["company_data"]["photo_admin_approve"] = 1;			
				if (GetSiteSettings("use_photo_approve")){				
					$profile["company_data"]["photo_admin_approve"] = $rs2->fields[2];				
				}				
			}
		}

		/**
		 * Get uploads for the ad
		 */
		$view_file_name = "viewprofile.php";
		$profile = array_merge($profile, GetAdUploads($profile["id"], USERS_RENT_UPLOADS_TABLE, "f", "photo", $photo_folder, $default_photo, $view_file_name, $default_photo_alt));

		$profile = array_merge($profile, GetAdUploads($profile["id"], USERS_RENT_UPLOADS_TABLE, "v", "video", $video_folder, GetSiteSettings('default_video_icon'), $view_file_name));

		$profile = array_merge($profile, GetAdUploads($profile["id"], USER_RENT_PLAN_TABLE, "f", "plan", $photo_folder, "", $view_file_name));

		$rs->MoveNext();
		$ads[] = $profile;					
	}

	return (is_array($id_ad)) ? $ads : $ads[0];
}

/**
 * Get Ad uploads array
 *
 * @param integer $id_ad
 * @param string $table_name
 * @param char $upload_type
 * @param string $type_name
 * @param string $folder
 * @param string $default_file
 * @param string $file_name
 * @param string $default_file_alt
 * @return array
 */
function GetAdUploads($id_ad, $table_name, $upload_type="", $type_name, $folder, $default_file="", $file_name="", $default_file_alt="") {
	global $config, $dbconn, $settings;

	$upload = array();

	$strSQL = "SELECT id as ".$type_name."_id, upload_path, user_comment FROM ".$table_name." ".
			  "WHERE id_ad='".$id_ad."' AND ";
	$strSQL .= ($table_name == USERS_RENT_UPLOADS_TABLE) ? "upload_type='$upload_type' AND " : "";
	$strSQL .= "status='1' AND admin_approve='1' ORDER BY sequence";

	$rs = $dbconn->Execute($strSQL);
	$j = 0;
	
	if ($rs->RowCount() > 0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);

			$upload[$type_name."_id"][$j] = $row[$type_name."_id"];
			$upload[$type_name."_path"][$j] = $row["upload_path"];
			$upload[$type_name."_user_comment"][$j] = $row["user_comment"];
			$upload[$type_name."_alt"][$j] = $row["user_comment"];
			
			if (GetSiteSettings("use_ffmpeg") == 1 && $type_name == "video") {
				
				$flv_name = explode('.', $row["upload_path"]);

				if (file_exists($config["site_path"].GetSiteSettings("video_folder")."/".$flv_name[0].".flv")) {
					$upload["is_flv"][$j] = 1;
					

					$upload["video_icon"][$j] = $flv_name[0]."1.jpg";					
					$upload["video_path"][$j] = $flv_name[0].".flv";
					$size = explode('x', GetSiteSettings("flv_output_dimension"));
					$upload["video_width"][$j] = $size[0];
					
					$upload["video_height"][$j] = $size[1];
				} else {					
					$upload["video_path"][$j] = $row["upload_path"];
					$upload["video_icon"][$j] = GetSiteSettings("default_video_icon");
					$upload["video_width"][$j] = 320;			
					$upload["video_height"][$j] = 240;
					$upload["is_flv"][$j] = 0;
					
				}					
			} elseif($type_name == 'video') {								
				$upload["is_flv"][$j] = 0;
				$upload["video_width"][$j] = 320;			
				$upload["video_height"][$j] = 240;												
			}			

			$path = $config["site_path"].$folder."/".$upload[$type_name."_path"][$j];			
			if ($upload_type == "f") {
				$thumb_path = $config["site_path"].$folder."/thumb_".$upload[$type_name."_path"][$j];
				if ($type_name == "photo") {
					$thumb_big_path = $config["site_path"].$folder."/thumb_big_".$upload[$type_name."_path"][$j];
				}
			}

			if (strlen($upload[$type_name."_path"][$j]) > 0) {
				if(file_exists($path)) {
					$upload[$type_name."_file_name"][$j] = $upload[$type_name."_path"][$j];
					$upload[$type_name."_file"][$j] = ".".$folder."/".$upload[$type_name."_path"][$j];
					$upload[$type_name."_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$upload[$type_name."_id"][$j]."&type_upload=".$upload_type;
					if ($upload_type == "v"){
						$upload[$type_name."_view_link"][$j] .= "&is_flv=".$upload["is_flv"][$j];
						$upload["video_is_flv"][$j] = $upload["is_flv"][$j];
					}

					if ($upload_type == "f") {
						$sizes = getimagesize($path);
						$upload[$type_name."_width"][$j]  = $sizes[0];
						$upload[$type_name."_height"][$j]  = $sizes[1];
					}
					if ($upload_type == "v") {
						if (GetSiteSettings("use_ffmpeg")){
							$upload[$type_name."_icon"][$j]  = ".".$folder."/".$upload["video_icon"][$j];						
						
						}else{
							$upload[$type_name."_icon"][$j]  = ".".$folder."/".$default_file;						
						}
					}
				}
				if (isset($thumb_path) && file_exists($thumb_path)) {
					$upload[$type_name."_thumb_file"][$j] = ".".$folder."/thumb_".$upload[$type_name."_path"][$j];
				}
				if (isset($thumb_big_path) && file_exists($thumb_big_path)) {
					$upload[$type_name."_thumb_big_file"][$j] = ".".$folder."/thumb_big_".$upload[$type_name."_path"][$j];
				}
			} elseif ($default_file) {
				$upload[$type_name."_file"][$j] = ".".$folder."/".$default_file;
				$upload[$type_name."_thumb_file"][$j] = $upload[$type_name."_file"][$j];
				$upload[$type_name."_alt"][$j] = $default_file_alt;
			}

			$rs->MoveNext();
			$j++;
		}		
	} elseif ($type_name == "photo") {
		$upload[$type_name."_file"][$j] = ".".$folder."/".$default_file;
		$upload[$type_name."_thumb_file"][$j] = $upload[$type_name."_file"][$j];
		$upload[$type_name."_alt"][$j] = $default_file_alt;
	}
	return $upload;
}


function GetSprArrName($spr_table, $lang_add = 1){
	global $config, $dbconn, $multi_lang;

	$_spr = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName($lang_add);

	$strSQL = "SELECT id AS id_spr FROM ".$spr_table." ORDER BY sorter";
	$rs = $dbconn->Execute($strSQL);
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);
		$strSQL = "	SELECT DISTINCT b.".$field_name." as name
					FROM ".$spr_table." a
					LEFT JOIN ".REFERENCE_LANG_TABLE." b on b.table_key='".$_spr."' AND b.id_reference=a.id
					WHERE a.id= '".$row["id_spr"]."' ";
		$rs_fname = $dbconn->Execute($strSQL);
		$name[$row["id_spr"]] = $rs_fname->fields[0];

		$rs->MoveNext();
	}
	return $name;
}

/**
 * Get user ads number
 *
 * @param integer $user_id
 * @param boolean $only_active
 * @return integerr
 */
function GetUserAdsNumber($user_id, $only_active = false) {
	global $dbconn;
	$strSQL = "SELECT COUNT(id) AS cnt FROM ".RENT_ADS_TABLE." ".
			  "WHERE id_user='".$user_id."'";
	$strSQL .= ($only_active) ? " AND status=1" : "";
	$rs = $dbconn->Execute($strSQL);
	return $rs->fields[0];

}

/**
 *  Get parametres of showing ads for unregistered users from TABLE_SHOW_ADS_AREA
 *
 * @param string $area_type (name of area)
 * @param int $for_reg (1 - for reg user, 0 - for unreg)
 * @return array
 */
function GetOrderAds($area_type, $for_reg) {
	global $smarty, $dbconn, $config;

	$str_arr = array();
	$strSQL = "SELECT DISTINCT show_type, ads_number, view_type  FROM ".SHOW_ADS_AREA_TABLE.
					" WHERE area='".$area_type."' AND for_registered='".$for_reg."'";
	$rs = $dbconn->Execute($strSQL);
	if (!$rs->EOF) {
		$row = $rs->GetRowAssoc(false);
		switch ($row["show_type"])	{
			case "last_added"	: $sorter = 0; $sorter_order = 1; break;
			case "admin_choose"	: $sorter = 5; $sorter_order = 2; break;
			case "max_views"	: $sorter = 6; $sorter_order = 1; break;
			case "off"			: $sorter = 0; $sorter_order = 1; break;
			default				: $sorter = 0; $sorter_order = 1; break;
		}
		$str_arr = array("show_type" => $row["show_type"],
					 "ads_number" => $row["ads_number"],
					 "view_type" => $row["view_type"],
					 "sorter" => $sorter,
					 "sorter_order" => $sorter_order,
					 "area" => $area_type,
					 "for_reg" => $for_reg);
	}
	return $str_arr;
}

/**
 * Return number after number_format function
 *
 * @param float $number
 * @return float
 */
function PaymentFormat($number){
	$format = GetSiteSettings(array("thousands_separator", "decimal_point", "decimals_after_point"));
	switch ($format["thousands_separator"]) {
		case "nbsp": $format["thousands_separator"]=" ";
			break;
		case ",": $format["thousands_separator"]=",";
			break;
		case "empty": $format["thousands_separator"]="";
			break;
		default:
			break;
	}
	return number_format($number, $format["decimals_after_point"], $format["decimal_point"], $format["thousands_separator"]);
}

/**
 * Get site active map
 *
 * @return array
 */
function GetMapSettings(){
	global $dbconn;
		$map=array();
		$strSQL="SELECT name, app_id FROM ".MAPS_TABLE." WHERE used='1'";
		$rs = $dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$map["name"] = $row["name"];
		$map["app_id"] = $row["app_id"];	
	return $map;
}


function FormatPrice($price, $cur_position, $cur_format){
	global $dbconn;
	if ($price){
		$cur = GetSiteSettings('site_unit_costunit');
		switch ($cur_format){
			case "abbr":
				$cur_show = $cur;
				$space = "&nbsp;";
				break;
			case "symbol":
				$strSQL = " SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='$cur' ";
				$rs = $dbconn->Execute($strSQL);
				$cur_show = $rs->fields[0];
				$space = "";
				break;	
		}
		switch ($cur_position){
			case "begin":
				$price_format = $cur_show.$space.$price;
				break;
			case "end":
				$price_format = $price."&nbsp;".$cur_show;
				break;	
		}
		return $price_format;
	}else{
		return "";
	}
}

?>