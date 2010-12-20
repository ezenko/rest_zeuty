<?php
/**
* Common functions, which used both in user and admin mode
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.3 $ $Date: 2008/10/24 14:09:39 $
**/

function Cmp ( $a, $b ){
	if ( $a["field_sort"] == $b["field_sort"] ) {
		return 0;
	}
	if ( $a["field_sort"] < $b["field_sort"] ) {
		return 1;
	}
	return -1;
}

function MultiSort($data, $field) {
	foreach ($data as $key => $row) {
		$array[$key] = array("field_sort" => $row[$field], "temp" => $row);
	}
	usort($array, "Cmp");
	foreach ($array as $key => $row) {
		$data[$key] = $row["temp"];
	}
	return $data;
}

/**
 * Get admin language id
 *
 * @param void
 * @return integer
 */
function GetAdminLanguageId() {
	global $dbconn;
	/**
	 * The structure is so, that it can be only one site administrator
	 */
	$strSQL = "SELECT lang_id FROM ".USERS_TABLE." WHERE root_user='1'";
	$rs = $dbconn->Execute($strSQL);
	$lang_id = $rs->fields[0];
	/**
	 * Check for visibility
	 */
	$rs = $dbconn->Execute("SELECT visible FROM ".LANGUAGE_TABLE." WHERE id='$lang_id'");
	if (!($rs->RowCount() > 0 && $rs->fields[0] == 1)) {
		$lang_id = GetDefaultLanguageId();
	}
	return $lang_id;
}

/**
 * Get admin name
 *
 * @param void
 * @return integer
 */
function GetAdminName() {
	global $dbconn;
	/**
	 * The structure is so, that it can be only one site administrator
	 */
	$strSQL = "SELECT fname, sname FROM ".USERS_TABLE." WHERE root_user='1'";
	$rs = $dbconn->Execute($strSQL);
	$row = $rs->GetRowAssoc( false );

	return $row["fname"]." ".$row["sname"];
}

/**
 * Get user language id, check users' language for visibility, if language is not visible - return default site language
 *
 * @param integer $lang_id
 * @return integer
 */
function GetUserLanguageId($lang_id) {
	global $dbconn;
	/**
	 * Check for visibility
	 */
	$rs = $dbconn->Execute("SELECT visible FROM ".LANGUAGE_TABLE." WHERE id='$lang_id'");
	if (!($rs->RowCount() > 0 && $rs->fields[0] == 1)) {
		$lang_id = GetDefaultLanguageId();
	}
	return $lang_id;
}

/**
 * Check default site language on visibility, if not visible, get min language id, which is visible
 *
 * @param void
 * @return integer
 */
function GetDefaultLanguageId() {
	global $dbconn;

	$rs = $dbconn->Execute("SELECT value FROM ".SETTINGS_TABLE." WHERE name='default_lang'");
	if($rs->fields[0]) {
		$default_lang = $rs->fields[0];
	} else {
		$rs = $dbconn->Execute("SELECT MIN(id) AS id FROM ".LANGUAGE_TABLE." WHERE visible='1'");
		$default_lang = $rs->fields[0];
	}
	return $default_lang;
}

/**
 * Get substring from string in utf
 * @see www.yeap.lv
 *
 * @param string $str
 * @param integer $from
 * @param integer $len
 * @return string
 */
function utf8_substr($str,$from,$len) {
	return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
	'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
	'$1',$str);
}

function utf8_strlen($s) {
    return preg_match_all('/./u', $s, $tmp);
}

/**
 * Get Region Leader by id_region
 *
 * @param integer $id_region
 * @return array
 */
function GetFeaturedAd($id_region){
	global $config, $dbconn;
	
	$featured_rent = array();

	if (strlen($id_region)>1){
		$strSQL  = " 	SELECT DISTINCT f.id, f.id_user, f.id_ad, f.type, f.headline, UNIX_TIMESTAMP(f.date_featured) as time_featured, f.curr_count, f.upload_path, ut.fname, f.id_region, rt.name as region_name
						FROM ".FEATURED_TABLE." f
						LEFT JOIN ".USERS_TABLE." ut ON ut.id=f.id_user
						LEFT JOIN ".REGION_TABLE." rt ON rt.id=f.id_region
						WHERE f.type='1' AND rt.id='".$id_region."'
						GROUP BY id";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0){
			$row = $rs->GetRowAssoc(false);
			$featured_rent["id"] = $row["id"];
			$featured_rent["id_user"] = $row["id_user"];
			$featured_rent["fname"] = stripslashes($row["fname"]);
			$featured_rent["id_ad"] = $row["id_ad"];
			$featured_rent["type"] = $row["type"];
			$featured_rent["headline"] = stripslashes($row["headline"]);
			$featured_rent["time_featured"] = $row["time_featured"];
			$featured_rent["id_region"] = $row["id_region"];
			if ($config["lang_ident"]!='ru'){
				$featured_rent["region_name"] = RusToTranslit($row["region_name"]);
			} else {
				$featured_rent["region_name"] = $row["region_name"];
			}
			$diff = time() - $featured_rent["time_featured"];
			$featured_rent["period"]["days"] = intval( $diff/(60*60*24) );
			$featured_rent["period"]["hours"] = intval( ( $diff-(60*60*24*$featured_rent["period"]["days"]) )/(60*60) );
			$featured_rent["period"]["minutes"] = intval( ($diff-(60*60*24*$featured_rent["period"]["days"])-(60*60*$featured_rent["period"]["hours"]))/(60) );
			$featured_rent["period"]["seconds"] = intval($diff-(60*60*24*$featured_rent["period"]["days"])-(60*60*$featured_rent["period"]["hours"])-60*$featured_rent["period"]["minutes"]);

			$featured_rent["curr_count"] = $row["curr_count"];
			$featured_rent["upload_path"] = $config["server"].$config["site_root"]."/uploades/featured/thumb_".$row["upload_path"];			
			$featured_rent["link"] = $config["server"].$config["site_root"]."/viewprofile.php?id=".$featured_rent["id_ad"];
		}
	}
	return $featured_rent;
}

/**
 * Activate Listing
 *
 * @param integerr $id_ad
 * @return void
 */
function ListingActivate($id_ad) {
	global $dbconn;
		
	$period = intval(GetSiteSettings("ads_activity_period"));
	if ($period && GetSiteSettings("use_ads_activity_period")) {					
		$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET status='1', date_unactive='".date("Y-m-d H:i:s", time()+60*60*24*$period)."' WHERE id='".$id_ad."' ");
	} else {
		$dbconn->Execute("UPDATE ".RENT_ADS_TABLE." SET status='1' WHERE id='".$id_ad."' ");
	}
}

/**
 * Update date of listing modification
 *
 * @param integer $id
 * @return void
 */
function AdUpdateDate($id){
	global $dbconn;
	$id = intval($id);
	if ($id) {
		$strSQL = "UPDATE ".RENT_ADS_TABLE." SET datenow=NOW() WHERE id='$id'";
		$dbconn->Execute($strSQL);
	}	
	return;
}

/**
 * Get sort order for the listings from table
 *
 * @param integer $sorter
 * @param integer $sort_order
 * @param integer $select_type
 * @return array
 */
function getRealtySortOrder($sorter, $sort_order, $select_type = "realty") {
	switch ($sort_order){
		case "1":
			$sorter_order = " DESC ";
			$sorter_tolink = "2";
			$sorter_topage = "1";
			$order_icon = "&darr;";
			break;
		case "2":
			$sorter_order = " ASC ";
			$sorter_tolink = "1";
			$sorter_topage = "2";
			$order_icon = "&uarr;";
			break;
		default:
			$sorter_order = ($sorter != 1) ? " DESC " : " ASC ";
			$sorter_tolink = ($sorter != 1) ? "2" : "1";
			$sorter_topage = ($sorter != 1) ? "1" : "2";
			$order_icon = ($sorter != 1) ? "&darr;" : "&uarr;";
			break;
	}
	if ($select_type == "realty") {
		switch ($sorter){
			case 0:
				$sorter_str = " ra.datenow";
				break;
			case 1:
				$sorter_str = " u.fname";
				break;
			case 2:
				$sorter_str = " u.date_last_seen";
				break;
			case 3:
				$sorter_str = " ra.movedate";
				break;
			case 4:
				$sorter_str = " urp.min_payment";
				break;
			case 5:
				$sorter_str = " sp.status DESC, sp.order_id";
				break;
			case 6:
				$sorter_str = " visits";
				break;
			default:
				$sorter_str = " ra.datenow";
				break;
		}
	} elseif ($select_type == "user") {
		switch ($sorter) {
			case 0://deafult sorter - order by ID
				$sorter_str = " a.date_registration";
				break;
			case 1:
				$sorter_str = " a.fname";
				break;
			case 2:
				$sorter_str = " a.date_last_seen";
				break;
			default:
				$sorter_str = " a.date_registration";
				break;
		}
	} elseif ($select_type == "user2") {
		switch ($sorter) {
			case 1:
				$sorter_str = " u.fname";
				break;
			case 2:
				$sorter_str = " rd.company_name";
				break;
			case 5:
				$sorter_str = " ads_user $order_str, u.fname $order_str";
				break;
			default :
				$sorter_str = " u.fname";
				break;
		}
	}
	$data["sorter_order"] = $sorter_order;
	$data["sorter_tolink"] = $sorter_tolink;
	$data["sorter_topage"] = $sorter_topage;
	$data["sorter_str"] = $sorter_str;
	$data["order_icon"] = $order_icon;
	return $data;
}

/**
 * Redirect from hidden in the current site mode page
 *
 * @param string $file
 * @return void
 */
function HidePage() {
	global $config;
	
	$redirect_url = $config["server"].$config["site_root"]."/error.php?code=404";
	
	echo "<script>
		if (opener) { opener.location.href='$redirect_url'; window.close(); opener.focus();}
		else{ location.href='$redirect_url';}
		</script>";
	exit;
}

?>