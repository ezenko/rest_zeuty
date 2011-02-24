<?php
/**
* Main functions for admin mode
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.12 $ $Date: 2008/12/30 09:19:41 $
**/

/**
 * Assign main values from config to smarty variables
 *
 * @param void
 * @return void
 */
function MenuAdds(){
	global $config, $smarty;

	$smarty->assign("server", $config["server"]);
	$smarty->assign("site_root", $config["site_root"]);
	$smarty->assign("template_root", $config["admin_theme_path"]);
	$smarty->assign("template_css_root", $config["index_theme_css_path"]);
	$smarty->assign("template_images_root", $config["index_theme_images_path"]);
	return;
}

/**
 * function for assigning general config and language variables to the template
 * @see function_index.php
 *
 * @param string $content_name
 * @return void
 */
function IndexAdminPage($content_name="", $user_content = 0){
	global $config, $smarty, $lang, $dbconn, $user;

	$lang["content"] = GetLangContent($user_content ? $content_name : "admin/".$content_name);				
	if ($user_content) {
		$lang["admin_content"] = GetLangContent("admin/admin_".$content_name);		
	}
				
	$lang["buttons"] = GetLangContent("admin/admin_buttons");
	$lang["default_select"] = GetLangContent("admin/admin_default_select");
	$lang["errors"] = GetLangContent("errors");
	$lang["menu"] = GetLangContent("admin/admin_menu");	
	$metatags = GetLangContent("admin/admin_metatags");
		
	$pos = strpos($content_name, "/");	//subfolder - use module
	$title_name = ($pos !== false) ? substr($content_name, 0, $pos) : $content_name;
	if (isset($metatags[$title_name."_title"])){
		$lang["title"] = $metatags[$title_name."_title"];
	}	
	
	$smarty->assign("lang", $lang);

	$thumb_width = GetSiteSettings('thumb_max_width');
	$thumb_height = GetSiteSettings('thumb_max_height');
	$smarty->assign("thumb_width", $thumb_width);
	$smarty->assign("thumb_alt_width", $thumb_width+20);
	$smarty->assign("thumb_height", $thumb_height);

	$strSQL = " SELECT symbol FROM ".UNITS_TABLE." WHERE abbr='".GetSiteSettings("site_unit_costunit")."' ";
	$rs = $dbconn->Execute($strSQL);
	$smarty->assign("cur_symbol", $rs->fields[0]);

	$smarty->assign("site_root", $config["site_root"]);
	$smarty->assign("server", $config["server"]);
	$smarty->assign("template_root", $config["admin_theme_path"]);
	$smarty->assign("index_template_root", $config["index_theme_path"]);
	$smarty->assign("template_css_root", $config["index_theme_css_path"]);
	$smarty->assign("template_images_root", $config["index_theme_images_path"]);
					
	return;
}

/**
 * Get site settings from SETTINGS_TABLE
 * @see function_index.php
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
 * @see function_index.php
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
		$ret_links[$j]["link"] = $param."".$param["page_var_name"]."=".($p_page*$p_page_count);
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

//----------------------- Select Functions ------------------------//

/**
 * Get array with possible for days numbers
 * @see function_index.php
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
 * @see function_index.php
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
 * @see function_index.php
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
 * @see function_index.php
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
 * @see function_index.php
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
/**
 * @see function_index.php
 */
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


function LettersLink_eng($url_param, $active_leter, $used_search_form = false) {
	$leter_str = "";
	for($i=65;$i<=90;$i++){
		if($i == $active_leter){
			$leter_str .= "&nbsp;<font class=\"page_link\" style=\"font-weight: bold; text-decoration: none;\">".chr($i)."</font>";
		}else{
			$leter_str .= "&nbsp;<a class=\"page_link\" href=\"".$url_param."".$i."\">".chr($i)."</a>";
		}
	}
	if($active_leter == "*" && !$used_search_form){
		$leter_str .= "&nbsp;&nbsp;&nbsp; &nbsp;<font class=\"page_link\" style=\"font-weight: bold; text-decoration: none;\">".chr(65)."-".chr(90)."</font>&nbsp;";
	}else{
		$leter_str .= "&nbsp;&nbsp;&nbsp; &nbsp;<a class=\"page_link\" href=\"".$url_param."*\">".chr(65)."-".chr(90)."</a>&nbsp;";
	}
	return $leter_str;
}

/**
 * Get modules id array for wich user with $id_user have access
 * @see function_index.php
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
 * Check if access to the file is allowed for the user with $id_user
 * @see function_index.php
 *
 * @param integer $id_user
 * @param string $file - file name without extension
 * @return string
 */
function IsFileAllowed($id_user, $file, $lang_type=""){
	global $dbconn, $config;
	$mod_arr = array();
	$mod_arr = GetPermissionsUser($id_user);

	$strSQL = "select id from ".MODULE_FILE_TABLE." where file='".$file."' ";
    
	$rs = $dbconn->Execute($strSQL);
	$id_module = $rs->fields[0];

	if(is_array($mod_arr["id"]) && in_array($id_module, $mod_arr["id"]) ){
		return "1";
	}else{
		return "0";
	}
}

/**
 * Get module name for file from file name
 * @see function_index.php
 *
 * @param string $file
 * @return string
 */
function GetRightModulePath($file){
	global $config;
	$file_name = substr($file, strlen($config["site_path"]));
	$file_name = str_replace("\\", "/", $file_name);
	$file_name = str_replace("admin/", "", $file_name);
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
 * @see function_index.php
 *
 * @param string $file
 * @return void
 */
function AlertPage($file=""){
	global $smarty, $dbconn, $config, $lang, $user;
	if (strlen($file)>0) {
		$strSQL = "select id FROM ".MODULE_FILE_TABLE." where file='".$file."' ";
		$rs = $dbconn->Execute($strSQL);
		$id_module = $rs->fields[0];
		$suffix = "?sel=access&id_module=".$id_module;
	} else {
		$suffix = "?sel=status";
	}

	echo "<script>
			if(opener){ opener.location.href='".$config["server"].$config["site_root"]."/alert.php".$suffix."'; window.close(); opener.focus();}
			else{ location.href='".$config["server"].$config["site_root"]."/alert.php".$suffix."';}
	      	</script>";
	exit;
}

/**
 * Get translit fot russian symbols
 * @see function_index.php
 *
 * @param string $string
 * @return string
 */
function RusToTranslit( $string ){
	$translit = array();
	$translit['Ð°'] = "a";
	$translit['Ð±'] = "b";
	$translit['Ð²'] = "v";
	$translit['Ð³'] = "g";
	$translit['Ð´'] = "d";
	$translit['Ðµ'] = "e";
	$translit['Ñ‘'] = "yo";
	$translit['Ð¶'] = "j";
	$translit['Ð·'] = "z";
	$translit['Ð¸'] = "i";
	$translit['Ð¹'] = "i";
	$translit['Ðº'] = "k";
	$translit['Ð»'] = "l";
	$translit['Ð¼'] = "m";
	$translit['Ð½'] = "n";
	$translit['Ð¾'] = "o";
	$translit['Ð¿'] = "p";
	$translit['Ñ€'] = "r";
	$translit['Ñ'] = "s";
	$translit['Ñ‚'] = "t";
	$translit['Ñƒ'] = "u";
	$translit['Ñ„'] = "f";
	$translit['Ñ…'] = "kh";
	$translit['Ñ†'] = "ts";
	$translit['Ñ‡'] = "tch";
	$translit['Ñˆ'] = "sh";
	$translit['Ñ‰'] = "sh";
	$translit['Ñ‹'] = "i";
	$translit['Ñ'] = "e";
	$translit['ÑŽ'] = "yu";
	$translit['Ñ'] = "ya";
	$translit['ÑŒ'] = "";
	$translit['ÑŠ'] = "";

	$translit['Ð'] = "A";
	$translit['Ð‘'] = "B";
	$translit['Ð’'] = "V";
	$translit['Ð“'] = "G";
	$translit['Ð”'] = "D";
	$translit['Ð•'] = "E";
	$translit['Ð'] = "Yo";
	$translit['Ð–'] = "J";
	$translit['Ð—'] = "Z";
	$translit['Ð?'] = "I";
	$translit['Ð™'] = "Y";
	$translit['Ðš'] = "K";
	$translit['Ð›'] = "L";
	$translit['Ðœ'] = "M";
	$translit['Ð'] = "N";
	$translit['Ðž'] = "O";
	$translit['ÐŸ'] = "P";
	$translit['Ð '] = "R";
	$translit['Ð¡'] = "S";
	$translit['Ð¢'] = "T";
	$translit['Ð£'] = "U";
	$translit['Ð¤'] = "F";
	$translit['Ð¥'] = "Kh";
	$translit['Ð¦'] = "Ts";
	$translit['Ð§'] = "Ch";
	$translit['Ð¨'] = "Sh";
	$translit['Ð©'] = "Sh";
	$translit['Ð«'] = "I";
	$translit['Ð­'] = "E";
	$translit['Ð®'] = "Yu";
	$translit['Ð¯'] = "Ya";
	$translit['Ðª'] = "";
	$translit['Ð¬'] = "";
	$result = "";
	$result = strtr( $string, $translit );
	return $result;
}

/**
 * Get user defined references' values
 * @see function_index.php
 *
 * @param string $table - table with user defined references' values
 * @param integer $id_ad
 * @param integer $id_user
 * @param string $spr_table - reference table
 * @return array
 */
function SprTableSelect($table, $id_ad, $id_user){
	global $smarty, $config, $dbconn, $user;

	$arr= array();
	$strSQL = "SELECT DISTINCT id_spr FROM ".$table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ORDER BY id_spr";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
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

function SprTableSelectAdmin($table, $id_ad, $id_user, $spr_table=''){
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
 * Get array of user defined values of reference $spr_table for the listing with $id_ad
 * @see function_index.php
 *
 * @param string $user_table - reference user defined values table name, defined in /include/constants.php
 * @param integer $id_user
 * @param integer $id_ad
 * @param string $spr_table
 * @param string $value_table
 * @return mixed (array or void)
 */
function GetResArrName($user_table, $id_user, $id_ad, $spr_table, $value_table){
	global $smarty, $config, $dbconn, $user, $multi_lang;

	$_val = $multi_lang->TableKey($value_table);
	$_spr = $multi_lang->TableKey($spr_table);
	$field_name = $multi_lang->DefaultFieldName();

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
 * Return percent of matching $arr_name to $user_val
 * @see function_index.php
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
 * Get countries array, if isset $country_id - get regions array,
 * if isset $region_id - get cities array, and assign results to smarty variables
 * @see function_index.php
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
		$strSQL = "SELECT id, name FROM ".REGION_TABLE." WHERE id_country='".$country_id."' ORDER by name";
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
		$strSQL =  " SELECT id, name FROM ".CITY_TABLE." WHERE id_region='".$region_id."' ORDER by name ";
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
 * Get default user icon (if user hadn't upload his icon to profile)
 * @see function_index.php
 *
 * @param integer $user_type (1 - private person, 2 - agency)
 * @param array $gender
 */
function getDefaultUserIcon($user_type, $gender) {
	global $smarty, $dbconn, $config;
	$site_settings = GetSiteSettings(array('default_photo_group', 'default_photo_male', 'default_photo_female', 'default_photo_fwithc', 'default_photo_fwithoutc', 'default_photo_agency', 'default_photo_man'));

	if ($user_type == 1){
		if (count($gender)>1){
			$num_gender = 4;
			/*group for future - now is not useable in RE, because user icons are formed from the
			info, getting from user profile*/
			$icon_name = $site_settings["default_photo_group"];
		} elseif (count($gender)==0) {
			$num_gender = 5;		//no gender
			$icon_name = $site_settings["default_photo_man"];
		} else {
			if ($gender[0] == 1){
				$num_gender = 3;	//male
				$icon_name = $site_settings["default_photo_male"];
			} elseif ($gender[0] == 2) {
				$num_gender = 2;	//female
				$icon_name = $site_settings["default_photo_female"];
			} elseif ($gender[0] == 3) {
				$num_gender = 4;	//family with children
				$icon_name =  $site_settings["default_photo_fwithc"];
			} elseif ($gender[0] == 4) {
				$num_gender = 4;	//family without children
				$icon_name = $site_settings["default_photo_fwithoutc"];
			}
		}
	} else {
		$num_gender = 1;			//agency
		$icon_name = $site_settings["default_photo_agency"];
	}

	return array("num_gender" => $num_gender, "icon_name" => $icon_name);
}

/**
 * Get user ads
 *
 * @param integer $id_user
 * @param string $file_name
 * @param string $param
 * @param string $id_ad - if isset, get only one listing by id
 * @return array
 */
function GetUserAds($id_user, $file_name, $param, $id_ad = ""){
	global $smarty, $dbconn, $user, $config, $REFERENCES;

	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = "SELECT COUNT(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$id_user."' ";

	if (($id_ad) && ($id_ad != -1)) {
		$strSQL .= "AND id='$id_ad'";
	} else {
		$strSQL .= "";
	}
	$rs = $dbconn->Execute($strSQL);

	$num_records = $rs->fields[0];
	$smarty->assign("num_records", $num_records);

	$ads_numpage = GetSiteSettings("admin_user_ads_numpage");
	if ($id_ad == -1) {
		$ads_numpage = GetSiteSettings("max_ads_admin");
	}

	$photo_folder = GetSiteSettings("photo_folder");
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));
	$lim_min = ($page-1)*$ads_numpage;
	$lim_max = $ads_numpage;

	$limit_str = " limit ".$lim_min.", ".$lim_max;

	$ads = array();
	if ($num_records == 0){
		return $ads;
	}
	$strSQL = "	SELECT 	a.id, a.id_user, a.type, DATE_FORMAT(a.movedate, '".$config["date_format"]."' ) as movedate,
				a.people_count, a.comment, a.sold_leased_status, a. headline,
				a.with_photo, a.with_video,
				urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress,
				count.name as country_name, reg.name as region_name, cit.name as city_name, ut.login, ut.user_type, 
				hlt.id_friend, blt.id_enemy,
				tsat.type as topsearched, tsat.date_begin as topsearch_date_begin, tsat.date_end as topsearch_date_end,
				a.status, a.room_type, ft.id as featured
				FROM ".RENT_ADS_TABLE." a
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON a.id=urlt.id_ad
				LEFT JOIN ".COUNTRY_TABLE." count ON count.id=urlt.id_country
				LEFT JOIN ".REGION_TABLE." reg ON reg.id=urlt.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=urlt.id_city
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=a.id_user
				LEFT JOIN ".HOTLIST_TABLE." hlt on a.id_user=hlt.id_friend and hlt.id_user='".$id_user."'
				LEFT JOIN ".BLACKLIST_TABLE." blt on a.id_user=blt.id_enemy and blt.id_user='".$id_user."'
				LEFT JOIN ".TOP_SEARCH_ADS_TABLE." tsat ON tsat.id_ad=a.id AND tsat.type='1'
				LEFT JOIN ".FEATURED_TABLE." ft ON ft.id_ad=a.id
				WHERE a.id_user='".$id_user."' ";
	if (($id_ad) && ($id_ad != -1)) {
		$strSQL .= "AND a.id='$id_ad'";
	} else {
		$strSQL .= "GROUP BY a.id ORDER BY a.id ".$limit_str;
	}
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF){
		$row = $rs->GetRowAssoc(false);

		$ads[$i]["id"] = $row["id"];
		$ads[$i]["type"] = $row["type"];
		$ads[$i]["status"] = $row["status"];
		$ads[$i]["is_active"] = $row["status"];
		$ads[$i]["id_user"] = $row["id_user"];
		$ads[$i]["user_type"] = $row["user_type"];
		$ads[$i]["issponsor"] = IsSponsor($ads[$i]["id"]);
		$ads[$i]["sold_leased_status"] = $row["sold_leased_status"];
		$ads[$i]["featured"] = $row["featured"];
		$ads[$i]["topsearched"] = $row["topsearched"];
		
		if ($ads[$i]["type"] == 2){			
			$calendar_event = new CalendarEvent();
			$ads[$i]["reserve"] = $calendar_event->GetEmptyPeriod($ads[$i]["id"], $ads[$i]["id_user"]);					
		}
		
		if ($row["topsearch_date_end"] > date('Y-m-d H:i:s', time())) {
			$ads[$i]["show_topsearch_icon"] = true;
			$ads[$i]["topsearch_date_begin"] = $row["topsearch_date_begin"];
		}

		$ads[$i]["zip_code"] = $row["zip_code"];
		$ads[$i]["street_1"] = stripslashes($row["street_1"]);
		$ads[$i]["street_2"] = stripslashes($row["street_2"]);
		$ads[$i]["adress"] = stripslashes($row["adress"]);
		if ($row["movedate"] != '00.00.0000'){
			$ads[$i]["movedate"] = $row["movedate"];
		}

		$ads[$i]["people_count"] = $row["people_count"];

		$ads[$i]["with_photo"] = $row["with_photo"];
		$ads[$i]["with_video"] = $row["with_video"];
		//if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
			//$ads[$i]["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"))."...";
		//} else {
			$ads[$i]["headline"] = stripslashes($row["headline"]);
		//}

		$ads[$i]["comment"] = stripslashes($row["comment"]);
		if ($config["lang_ident"]!='ru') {
			$ads[$i]["country_name"] = RusToTranslit($row["country_name"]);
			$ads[$i]["region_name"] = RusToTranslit($row["region_name"]);
			$ads[$i]["city_name"] = RusToTranslit($row["city_name"]);
		} else {
			$ads[$i]["country_name"] = $row["country_name"];
			$ads[$i]["region_name"] = $row["region_name"];
			$ads[$i]["city_name"] = $row["city_name"];
		}

		if ($id_ad != -1) {

			$used_references = array("info", "gender", "people", "language", "period",  "description","realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {

					if ($arr["spr_match_table"] != "") {
						/**
					 * human description
					 */
						$lang_add = 2; //describe who match my criteries
						$ads[$i][$arr["key"]."_match"] = GetResArrName($arr["spr_match_table"], $ads[$i]["id_user"], $ads[$i]["id"], $arr["spr_table"], $arr["val_table"], $lang_add);
						//describe myself or my listing
						$ads[$i][$arr["key"]] = GetResArrName($arr["spr_user_table"], $ads[$i]["id_user"], 0, $arr["spr_table"], $arr["val_table"]);
					} else {
						$ads[$i][$arr["key"]] = GetResArrName($arr["spr_user_table"], $ads[$i]["id_user"], $ads[$i]["id"], $arr["spr_table"], $arr["val_table"]);
					}
				}
			}
		}

		if ($id_ad == -1) {
			$lang_ad = 2;
			$used_references = array("realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $ads[$i]["id_user"], $ads[$i]["id"], $arr["val_table"], $lang_ad);
					if (count($name) == 0 && $arr["spr_match_table"] != ""){
						$name = GetUserAdSprValues($arr["spr_match_table"], $ads[$i]["id_user"], $ads[$i]["id"], $arr["val_table"], $lang_ad);
						$ads[$i][$arr["key"]."_match"] = implode(",", $name);
					} elseif ($name != "") {						
					$ads[$i][$arr["key"]] = implode(",", $name);
					}
				}
			}
		}

		/**
		 * photo
		 */

		$gender_info = getDefaultUserIcon($ads[$i]["user_type"], 0);

		$default_photo =  $gender_info["icon_name"];

		$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$ads[$i]["id"]."' AND upload_type='f' AND status='1' ORDER BY sequence ASC";
		$rs_img = $dbconn->Execute($strSQL_img);
		$j = 0;
		if ($rs_img->fields[0]>0){
			while(!$rs_img->EOF){
				$row_img = $rs_img->GetRowAssoc(false);
				$ads[$i]["photo_id"][$j] = $row_img["photo_id"];
				$ads[$i]["photo_path"][$j] = $row_img["upload_path"];
				$ads[$i]["photo_path"][$j] = $row_img["upload_path"];
				$ads[$i]["photo_admin_approve"][$j] = $row_img["admin_approve"];

				$path = $config["site_path"].$photo_folder."/".$ads[$i]["photo_path"][$j];
				$thumb_path = $config["site_path"].$photo_folder."/thumb_".$ads[$i]["photo_path"][$j];

				if(file_exists($path) && strlen($ads[$i]["photo_path"][$j])>0){
					$ads[$i]["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$ads[$i]["photo_path"][$j];
					$ads[$i]["photo_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$ads[$i]["photo_id"][$j]."&type_upload=f";
					$ads[$i]["del_upload_photo_link"][$j] = "./".$file_name."?sel=upload_delete&id_file=".$ads[$i]["photo_id"][$j]."&type_upload=f&id_user=".$id_user."&page=".$page;

					$sizes = getimagesize($path);
					$ads[$i]["photo_width"][$j]  = $sizes[0];
					$ads[$i]["photo_height"][$j]  = $sizes[1];
				}
				if(file_exists($thumb_path) && strlen($ads[$i]["photo_path"][$j])>0)
				$ads[$i]["thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/thumb_".$ads[$i]["photo_path"][$j];
				if(!file_exists($path) || !strlen($ads[$i]["photo_path"][$j])){
					$ads[$i]["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
					$ads[$i]["thumb_file"][$j] = $ads[$i]["photo_file"][$j];
				}
				$rs_img->MoveNext();
				$j++;
			}
		} else {
			$ads[$i]["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
			$ads[$i]["thumb_file"][$j] = $ads[$i]["photo_file"][$j];
		}
		/**
		 * video
		 */
		$strSQL_video = "SELECT id as video_id, upload_path, user_comment, admin_approve FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$ads[$i]["id"]."' AND upload_type='v' AND status='1' ORDER BY sequence";
		$rs_video = $dbconn->Execute($strSQL_video);
		$j = 0;
		if ($rs_video->fields[0]>0){
			$video_folder = GetSiteSettings('video_folder');
			while(!$rs_video->EOF){
				$row_video = $rs_video->GetRowAssoc(false);
				$ads[$i]["video_id"][$j] = $row_video["video_id"];
				$ads[$i]["video_user_comment"][$j] = $row_video["user_comment"];
				$ads[$i]["video_admin_approve"][$j] = $row_video["admin_approve"];
				
				if (GetSiteSettings("use_ffmpeg") == 1) {
					$flv_name = explode('.', $row_video["upload_path"]);
					if (file_exists($config["site_path"].$video_folder."/".$flv_name[0].".flv")) {
						$ads[$i]["is_flv"][$j] = 1;
						$ads[$i]["video_icon"][$j] = $flv_name[0]."1.jpg";
						$ads[$i]["video_path"][$j] = $flv_name[0].".flv";
						$size = explode('x', GetSiteSettings("flv_output_dimension"));
						$ads[$i]["width"][$j] = $size[0];			
						$ads[$i]["height"][$j] = $size[1];
					} else {
						$ads[$i]["video_path"][$j] = $row_video["upload_path"];
						$ads[$i]["video_icon"][$j] = GetSiteSettings("default_video_icon");
						$ads[$i]["is_flv"][$j] = 0;
						$ads[$i]["width"][$j] = 320;			
						$ads[$i]["height"][$j] = 240;
					}
				} else {
				$ads[$i]["video_path"][$j] = $row_video["upload_path"];
				$ads[$i]["video_icon"][$j] = GetSiteSettings("default_video_icon");
				$ads[$i]["is_flv"][$j] = 0;
				$ads[$i]["width"][$j] = 320;			
				$ads[$i]["height"][$j] = 240;
				}
				$ads[$i]["video_user_comment"][$j] = addslashes($row_video["user_comment"]);

				$path = $config["site_path"].$video_folder."/".$ads[$i]["video_path"][$j];

				if(file_exists($path) && strlen($ads[$i]["video_path"][$j])>0){
					$ads[$i]["video_file_name"][$j] = $ads[$i]["video_path"][$j];
					$ads[$i]["video_file"][$j] = $config["server"].$config["site_root"].$video_folder."/".$ads[$i]["video_path"][$j];
					
					$ads[$i]["video_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$ads[$i]["video_id"][$j]."&type_upload=v&is_flv=".$ads[$i]["is_flv"][$j];
					$ads[$i]["del_upload_video_link"][$j] = "./".$file_name."?sel=upload_delete&id_file=".$ads[$i]["video_id"][$j]."&type_upload=v&id_user=".$id_user."&page=".$page;;

				}
				$ads[$i]["video_icon"][$j] = $config["server"].$config["site_root"].$video_folder."/".$ads[$i]["video_icon"][$j];
				$rs_video->MoveNext();
				$j++;
			}
		}
		/**
		 * plan
		 */
		$strSQL_img = "SELECT id as photo_id, upload_path, user_comment, admin_approve FROM ".USER_RENT_PLAN_TABLE." WHERE id_ad='".$ads[$i]["id"]."' AND id_user='".$id_user."' AND status='1' ORDER BY sequence";
		$rs_img = $dbconn->Execute($strSQL_img);
		$j = 0;
		if ($rs_img->fields[0]>0){
			while(!$rs_img->EOF){
				$row_img = $rs_img->GetRowAssoc(false);
				$ads[$i]["plan_photo_id"][$j] = $row_img["photo_id"];
				$ads[$i]["plan_photo_path"][$j] = $row_img["upload_path"];
				$ads[$i]["plan_user_comment"][$j] = $row_img["user_comment"];
				$ads[$i]["plan_admin_approve"][$j] = $row_img["admin_approve"];
				$path = $config["site_path"].$photo_folder."/".$ads[$i]["plan_photo_path"][$j];
				$thumb_path = $config["site_path"].$photo_folder."/thumb_".$ads[$i]["plan_photo_path"][$j];
				if(file_exists($path) && strlen($ads[$i]["plan_photo_path"][$j])>0){
					$ads[$i]["plan_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$ads[$i]["plan_photo_path"][$j];
					$ads[$i]["plan_view_link"][$j] = "./".$file_name."?sel=plan_view&id_file=".$ads[$i]["plan_photo_id"][$j]."&type_upload=f";
					$ads[$i]["del_upload_plan_link"][$j] = "./".$file_name."?sel=upload_delete&sub_sel=plan&id_file=".$ads[$i]["plan_photo_id"][$j]."&type_upload=f&id_user=".$id_user."&page=".$page;

					$sizes = getimagesize($path);
					$ads[$i]["plan_width"][$j]  = $sizes[0];
					$ads[$i]["plan_height"][$j]  = $sizes[1];
				}
				if(file_exists($thumb_path) && strlen($ads[$i]["plan_photo_path"][$j])>0)
				$ads[$i]["plan_thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/thumb_".$ads[$i]["plan_photo_path"][$j];
				if(!file_exists($path) || !strlen($ads[$i]["plan_photo_path"][$j])){
					$ads[$i]["plan_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
					$ads[$i]["plan_thumb_file"][$j] = $ads[$i]["plan_file"][$j];
				}
				$rs_img->MoveNext();
				$j++;
			}
		} else {
			$ads[$i]["plan_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
			$ads[$i]["plan_thumb_file"][$j] = $ads[$i]["plan_file"][$j];
		}
		if ($ads[$i]["type"] == "1" || $ads[$i]["type"] == "3") {
		
			$strSQL_payment = " SELECT min_payment, max_payment, auction, min_deposit, max_deposit,
								min_live_square, max_live_square, min_total_square, max_total_square,
								min_land_square, max_land_square, min_floor,  max_floor, floor_num, subway_min,
								min_year_build, max_year_build
								FROM ".USERS_RENT_PAYS_TABLE."
								WHERE id_ad='".$ads[$i]["id"]."' AND id_user='".$ads[$i]["id_user"]."' ";
			$rs_payment = $dbconn->Execute($strSQL_payment);
			$row_payment = $rs_payment->GetRowAssoc(false);
			$ads[$i]["min_payment"] = PaymentFormat($row_payment["min_payment"]);
			$ads[$i]["min_payment_show"] = FormatPrice($ads[$i]["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);			
			$ads[$i]["max_payment"] = PaymentFormat($row_payment["max_payment"]);
			$ads[$i]["max_payment_show"] = FormatPrice($ads[$i]["max_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["auction"] = $row_payment["auction"];
			$ads[$i]["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
			$ads[$i]["min_deposit_show"] = FormatPrice($ads[$i]["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["max_deposit"] = PaymentFormat($row_payment["max_deposit"]);
			$ads[$i]["max_deposit_show"] = FormatPrice($ads[$i]["max_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["min_live_square"] = $row_payment["min_live_square"];
			$ads[$i]["max_live_square"] = $row_payment["max_live_square"];
			$ads[$i]["min_total_square"] = $row_payment["min_total_square"];
			$ads[$i]["max_total_square"] = $row_payment["max_total_square"];
			$ads[$i]["min_land_square"] = $row_payment["min_land_square"];
			$ads[$i]["max_land_square"] = $row_payment["max_land_square"];
			$ads[$i]["min_floor"] = $row_payment["min_floor"];
			$ads[$i]["max_floor"] = $row_payment["max_floor"];
			$ads[$i]["floor_num"] = $row_payment["floor_num"];
			$ads[$i]["subway_min"] = $row_payment["subway_min"];
			$ads[$i]["min_year_build"] = $row_payment["min_year_build"];
			$ads[$i]["max_year_build"] = $row_payment["max_year_build"];

		} elseif ($ads[$i]["type"] == "2" || $ads[$i]["type"] == "4") {
			/**
			 * fixed values for listing types 2&4 (lease&sell) hold in min_<field_name>
			 */
			$strSQL_payment = "	SELECT min_payment, auction, min_deposit,
								min_live_square, min_total_square,
								min_land_square, min_floor, floor_num, subway_min, min_year_build
								FROM ".USERS_RENT_PAYS_TABLE."
								WHERE id_ad='".$ads[$i]["id"]."' AND id_user='".$ads[$i]["id_user"]."' ";
			$rs_payment = $dbconn->Execute($strSQL_payment);
			$row_payment = $rs_payment->GetRowAssoc(false);
			$ads[$i]["min_payment"] = PaymentFormat($row_payment["min_payment"]);
			$ads[$i]["min_payment_show"] = FormatPrice($ads[$i]["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);	
			$ads[$i]["auction"] = $row_payment["auction"];
			$ads[$i]["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
			$ads[$i]["min_deposit_show"] = FormatPrice($ads[$i]["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);	
			$ads[$i]["min_live_square"] = $row_payment["min_live_square"];
			$ads[$i]["min_total_square"] = $row_payment["min_total_square"];
			$ads[$i]["min_land_square"] = $row_payment["min_land_square"];
			$ads[$i]["min_floor"] = $row_payment["min_floor"];
			$ads[$i]["floor_num"] = $row_payment["floor_num"];
			$ads[$i]["subway_min"] = $row_payment["subway_min"];
			$ads[$i]["min_year_build"] = $row_payment["min_year_build"];
		}

		$strSQL_age = "SELECT his_age_1, his_age_2 FROM ".USERS_RENT_AGES_TABLE." WHERE id_user='".$ads[$i]["id_user"]."' AND id_ad='".$ads[$i]["id"]."' ";
		$rs_age = $dbconn->Execute($strSQL_age);
		$row_age = $rs_age->GetRowAssoc(false);

		$ads[$i]["his_age_1"] = $row_age["his_age_1"];
		$ads[$i]["his_age_2"] = $row_age["his_age_2"];

		$ads[$i]["viewprofile_link"] = "./admin_users.php?sel=user_rent&amp;id_user=".$ads[$i]["id_user"]."&amp;id_ad=".$ads[$i]["id"]."&amp;referer=to_user_ads";

		$i++;
		$rs->MoveNext();
	}

	if ($id_ad != -1) {
		$file_name="./admin_users.php?sel=user_rent&amp;type=add&amp;id_user=".$id_user;
	}
	$smarty->assign("ads_numpage", $ads_numpage);
	$smarty->assign("links", GetLinkArray($num_records, $page, $param, $ads_numpage));
	$smarty->assign("file_name", $file_name);
	$smarty->assign("page", $page);

	return $ads;
}

function GetUserAdsAdmin($file_name, $param="", $ad_id_in_array = "", $user_id = "1") {
	global $smarty, $dbconn, $auth, $config, $REFERENCES;
	if ($user_id == "") {
		$user_id = $auth[0];
	}
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = "SELECT count(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$user_id."' ";
	$rs = $dbconn->Execute($strSQL);

	$num_records = $rs->fields[0];
	$smarty->assign("num_records", $num_records);
  
  $strSQL = "SELECT count(id) FROM ".RENT_ADS_TABLE." WHERE id_user='".$user_id."' AND parent_id = 0 ";
	$rs = $dbconn->Execute($strSQL);

	$num_records_parent = $rs->fields[0];
	$smarty->assign("num_records_parent", $num_records_parent);

	$ads_numpage = GetSiteSettings("max_ads_admin");
	$photo_folder = GetSiteSettings("photo_folder");
	$smarty->assign("use_sold_leased_status", GetSiteSettings("use_sold_leased_status"));

	$lim_min = ($page-1)*$ads_numpage;
	$lim_max = $ads_numpage;
	$limit_str = ($ad_id_in_array == "") ? " limit ".$lim_min.", ".$lim_max : "";

	$ads = array();
	if ($num_records>0){
		$strSQL = "	SELECT DISTINCT ra.id, ra.type, DATE_FORMAT(ra.movedate, '".$config["date_format"]."') as movedate, ra.comment, ra.upload_path, ut.fname, ut.user_type, cn.name as country_name, rg.name as region_name, ct.name as city_name, ra.datenow as clean_move, ra.room_type, ra.status, ra.sold_leased_status, sp.status as spstatus, ra.headline
					FROM ".RENT_ADS_TABLE." ra
					LEFT JOIN ".USERS_TABLE." ut ON ut.id='".$user_id."'
					LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON urlt.id_ad=ra.id
					LEFT JOIN ".COUNTRY_TABLE." cn ON cn.id=urlt.id_country
					LEFT JOIN ".REGION_TABLE." rg ON rg.id=urlt.id_region
					LEFT JOIN ".CITY_TABLE." ct ON ct.id=urlt.id_city
					LEFT JOIN ".SPONSORS_ADS_TABLE." sp ON sp.id_ad=ra.id
					WHERE ra.id_user='".$user_id."' AND ra.parent_id = 0 GROUP BY ra.id ORDER BY ra.id ".$limit_str;
		$rs = $dbconn->Execute($strSQL);
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$ads[$i]["id"] = $row["id"];

			$strSQL = "SELECT id as featured FROM ".FEATURED_TABLE." WHERE id_ad='{$row["id"]}'";
			$rs_featured = $dbconn->Execute($strSQL);
			if ($rs_featured->RowCount() > 0) {
				$row_featured = $rs_featured->getRowAssoc( false );
				$ads[$i]["featured"] = $row_featured["featured"];
			} else {
				$ads[$i]["featured"] = "";
			}
			$arr = array();
			$arr = VisitedMyAdAdmin($ads[$i]["id"]);

			$ads[$i]["visit_day"] = $arr["visit_day"];
			$ads[$i]["visit_month"] = $arr["visit_month"];
			$ads[$i]["visit_not_guest"] = $arr["visit_not_guest"];

			$ads[$i]["fname"] = stripslashes($row["fname"]);
			$ads[$i]["sold_leased_status"] = $row["sold_leased_status"];
			$ads[$i]["issponsor"] = $row["spstatus"];
									
			if (utf8_strlen($row["headline"]) > GetSiteSettings("headline_preview_size")) {
				$ads[$i]["headline"] = utf8_substr(stripslashes($row["headline"]), 0, GetSiteSettings("headline_preview_size"));
			} else {
			$ads[$i]["headline"] = stripslashes($row["headline"]);
			}

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
			/// region
			$strSQL = " SELECT id_region FROM ".USERS_RENT_LOCATION_TABLE." WHERE id_ad='".$row["id"]."' ";
			$rs_region = $dbconn->Execute($strSQL);
			$ads[$i]["id_region"] = $rs_region->fields[0];

			$ads[$i]["edit_link"] = "admin_rentals.php?sel=my_ad&amp;id_ad=".$row["id"];
			$ads[$i]["del_link"] = "admin_rentals.php?sel=del&amp;id_ad=".$row["id"];

			$ads[$i]["top_search_link"] = "admin_rentals.php?sel=top_search_ad&amp;type=rent&amp;id_ad=".$row["id"];
			$ads[$i]["slideshow_link"] = "admin_rentals.php?sel=slideshow_ad&amp;type=rent&amp;id_ad=".$row["id"];
			$ads[$i]["feature_link"] = "admin_rentals.php?sel=feature_ad&amp;type=rent&amp;id_ad=".$row["id"];

			$ads[$i]["visited_ad_link"] = "homepage.php?sel=visited_ad&amp;id_ad=".$row["id"];

			$ads[$i]["type"] = $row["type"];
			$ads[$i]["movedate"] = $row["movedate"];
			$ads[$i]["status"] = intval($row["status"]);			
			if ($ads[$i]["type"] == 2){			
				$calendar_event = new CalendarEvent();				
				$ads[$i]["reserve"] = $calendar_event->GetEmptyPeriod($ads[$i]["id"], $user_id);						
			}
			
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

			$lang_ad = 2; //ò.ê. âûâîäèì èíôîðìàöèþ î òîì, ÷òî èùåò ÷åëîâåê
			$used_references = array("realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $user_id, $ads[$i]["id"], $arr["val_table"], $lang_ad);
					if ($name){
						$ads[$i][$arr["key"]] = implode(",", $name);
					}
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
				$strSQL_img = "SELECT id as photo_id, upload_path FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$ads[$i]["id"]."' AND upload_type='f' AND status='1' AND admin_approve='1' ORDER BY sequence";
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
							$ads[$i]["file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$ads[$i]["photo_path"][$j];
						}
						if(file_exists($thumb_path) && strlen($ads[$i]["photo_path"][$j])>0)
						$ads[$i]["thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/thumb_".$ads[$i]["photo_path"][$j];
						if(!file_exists($path) || !strlen($ads[$i]["photo_path"][$j])){
							$ads[$i]["file"][$j] = ".".$photo_folder."/".$default_photo;
							$ads[$i]["thumb_file"][$j] = $ads[$i]["file"][$j];
						}
						$rs_img->MoveNext();
						$j++;
					}
				} else {
					$ads[$i]["thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
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
 * Get visits' number on the listing with $id_ad of the current user owner
 *
 * @param integer $id_ad
 * @return array
 */
function VisitedMyAdAdmin($id_ad){
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
 * function is an analog to GetUserGender, but uses $lang_add
 * @see functions_index.php
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
	$name = array();
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
	return ($name) ? $name[0] : "";
}

/**
 * Get user own gender (from profile for user with $id_user), or user defined
 * gender in listing with $id_add
 * @see functions_index.php
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

	$strSQL = "SELECT DISTINCT id_spr FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' ORDER BY id_spr";
	$rs = $dbconn->Execute($strSQL);
	//òîëüêî îäèí ñïðàâî÷íèê ñâÿçàí ñ ïîëîì
	$row = $rs->GetRowAssoc(false);
	$strSQL_opt = "SELECT id_value FROM ".$user_table." WHERE id_ad='".$id_ad."' AND id_user='".$id_user."' AND id_spr='".$row["id_spr"]."' ORDER BY id_value";
	$rs_opt = $dbconn->Execute($strSQL_opt);
	$i = 0;
	$name = array();
	while(!$rs_opt->EOF){
		$row_opt = $rs_opt->GetRowAssoc(false);
		$name[$i] = $row_opt["id_value"];
		$rs_opt->MoveNext();
		$i++;
	}
	return $name;
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
 * Save reference values for the listing with $id_ad
 *
 * @param string $table - references user defined values
 * @param interes $id_ad
 * @param array $spr -reference keys
 * @param array $values - reference values
 * @return void
 */
function SprTableEditAdmin($table, $id_ad, $spr, $values){
	global $dbconn, $user;

	$dbconn->Execute("DELETE FROM ".$table." WHERE id_ad='".$id_ad."'");
  $keys = array_keys($values);  
	for($i=0; $i<count($spr); $i++){

		if (isset($values[$keys[$i]])){
			for($j=0; $j<count($values[$keys[$i]]); $j++){
				if ($values[$keys[$i]][$j]>0) {
					$dbconn->Execute("	INSERT INTO ".$table." (id_ad, id_user, id_spr, id_value)
										VALUES ('".$id_ad."', '1', '".$spr[$keys[$i]]."', '".$values[$keys[$i]][$j]."')");
				}
			}
		}
	}
	return;
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
		if (($data["id_country"] && $data["country_name"] == '')||($data["id_region"] && $data["region_name"] == '')||($data["id_city"] && $data["city_name"] == '')) {
			$data["in_base"]=0;
		}
		else {$data["in_base"]=1;
		}
		$data["city_name"]=$row["name"];				
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
 * Delete uploaded file
 *
 * @param string $type_upload (a = audio, v = video)
 * @param integer $id_file
 * @return void
 */
function DeleteUploadedFiles($type_upload, $id_file=""){
	global $smarty, $dbconn, $config, $auth;
	$id = intval($auth[0]);
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
 * Check, if value is positive integer more than 0
 *
 * @param sting $x
 * @return boolean
 */
function IsPositiveInt($x) {
	return ( is_numeric ($x ) ?  (intval(0+$x) == $x && $x>0) :  false );
}

/**
 * Check, if value is positive float number more than 0
 *
 * @param sting $x
 * @return boolean
 */
function IsPositiveFloat($x) {
	return ( is_numeric ($x ) ?  (floatval(0+$x) == $x && $x>0) :  false );
}

/**
 * Add string from language xml file of ech language
 *
 * @param string $lang_folder_path
 * @param string $change_file - whole path to file will be: $lang_folder_path."language_name/".$file_path
 * @param string $string_name - name of the string, which will be added
 * @param string $tag
 * @param array $add_attrs
 * @return void
 */
function AddLangString($lang_folder_path, $change_file, $string_name, $string_value, $tag = "lines", $add_attrs = array("descr" => "")) {
	$d = dir($lang_folder_path);
	while ($entry = $d->read()) {
		if ($entry != "." && $entry != ".." && is_dir($lang_folder_path.$entry) && strcasecmp($entry, "CVS") != 0) {
			$file_path = $lang_folder_path.$entry."/".$change_file;
			if (file_exists($file_path)) {
				$xml_parser = new SimpleXmlParser( $file_path );
				$xml_root = $xml_parser->getRoot();

				$new_elem_id = $xml_root->childrenCount;
				$xml_root->children[$new_elem_id]->tag = $tag;
				$xml_root->children[$new_elem_id]->attrs["name"] = $string_name;
				foreach ($add_attrs as $key=>$val) {
					$xml_root->children[$new_elem_id]->attrs[$key] = $val;
				}
				$xml_root->children[$new_elem_id]->value = $string_value;

				$obj_saver = new Object2Xml( true );
				$obj_saver->Save( $xml_root, $file_path );
			}
		}
	}
	unset($xml_parser, $obj_saver);
	$d->close();
}

/**
 * Delete string from language xml file of ech language
 *
 * @param string $lang_folder_path
 * @param string $change_file - whole path to file will be: $lang_folder_path."language_name/".$file_path
 * @param string $string_name - name of the string, which will be deleted
 */
function DeleteLangString($lang_folder_path, $change_file, $string_name) {
	$d = dir($lang_folder_path);
	while ($entry = $d->read()) {
		if ($entry != "." && $entry != ".." && is_dir($lang_folder_path.$entry) && strcasecmp($entry, "CVS") != 0) {
			$file_path = $lang_folder_path.$entry."/".$change_file;
			if (file_exists($file_path)) {
				$xml_parser = new SimpleXmlParser( $file_path );
				$xml_root = $xml_parser->getRoot();
				for ( $i = 0; $i < $xml_root->childrenCount; $i++ ) {
					if ( $xml_root->children[$i]->attrs["name"] == $string_name ) {
						unset($xml_root->children[$i]);
						$xml_root->childrenCount = $xml_root->childrenCount-1;
					}
				}
				$obj_saver = new Object2Xml( true );
				$obj_saver->Save( $xml_root, $file_path );
			}
		}
	}
	$d->close();
}

/**
 * Delete directory and all of its subdirectories
 *
 * @param string $path - path to dir with "/" at the end
 */
function DeleteDir($path){
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_file($path.$file)){
					unlink($path.$file);
				}elseif (is_dir($path.$file)){
					DeleteDir($path.$file."/");
				}
			}
		}
		closedir($handle);
		rmdir($path);
	}
}

/**
 * Check if $text include badwords, defined in $config["site_path"]."/include/badwords.txt"
 *
 * @param string $text
 * @return string
 */
function BadWordsCont($text){
	global $config;

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
 * Get sponsors id for this $id_ad
 *
 * @param integer $id_ad
 * @return integer
 */
function IsSponsor($id_ad){
	global $smarty, $dbconn, $user, $config;


	$strSQL = " SELECT id FROM ".SPONSORS_ADS_TABLE."
				WHERE id_ad='".intval($id_ad)."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>-1) {
		$sponsor = $rs->fields[0];
	} else {
		$sponsor = -1;
	}

	return $sponsor;
}

/**
 * Assign form to smarty variable
 *
 * @param string $name - form name
 * @param stirng $action
 * @param array $hiddens
 * @return void
 */
function AddForm($name, $action, $hiddens) {
	global $smarty;
	$form["name"] = $name;
	$form["action"] = $action;
	$form["hiddens"] = $hiddens;
	$smarty->assign("form", $form);
}

/**
 * Get site active languages
 *
 * @return array
 */
function GetActiveLanguages() {
	global $dbconn;

	$sql_query = "SELECT id, name FROM ".LANGUAGE_TABLE." WHERE visible='1'";
	$record_set = $dbconn->Execute( $sql_query );

	$langs = array();
	while ( !$record_set->EOF ) {
		$langs[] = $record_set->GetRowAssoc( false );
		$record_set->MoveNext();
	}
	return $langs;
}

/**
 * Get sponsor ads
 *
 * @return array
 */
function GetSponsorAds($param){
	global $smarty, $dbconn, $user, $config, $REFERENCES;
	$smarty->assign("sq_meters", GetSiteSettings('sq_meters'));
	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;
	$file_name = "admin_sponsors.php";

	$strSQL = "SELECT count(id) FROM ".SPONSORS_ADS_TABLE;
	$rs = $dbconn->Execute($strSQL);

	$num_records = $rs->fields[0];
	$smarty->assign("num_records", $num_records);
	$settings_price = GetSiteSettings(array('cur_position', 'cur_format'));

	$ads_numpage = GetSiteSettings("max_ads_admin");
	$lim_min = ($page-1)*$ads_numpage;
	$lim_max = $ads_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;


	$strSQL = "SELECT DISTINCT  sp.id, sp.id_ad, sp.order_id, sp.status as status,
								a.id_user, a.type, DATE_FORMAT(a.movedate, '".$config["date_format"]."' ) as movedate,
								a.comment, a.with_photo, a.with_video, a.room_type, a.status as is_active,
								urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress,
								count.name as country_name, reg.name as region_name, cit.name as city_name,
								ut.login, ut.fname, ut.sname, ut.user_type
					FROM ".SPONSORS_ADS_TABLE." sp
				LEFT JOIN ".RENT_ADS_TABLE." a ON sp.id_ad=a.id
				LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON a.id=urlt.id_ad
				LEFT JOIN ".COUNTRY_TABLE." count ON count.id=urlt.id_country
				LEFT JOIN ".REGION_TABLE." reg ON reg.id=urlt.id_region
				LEFT JOIN ".CITY_TABLE." cit ON cit.id=urlt.id_city
				LEFT JOIN ".USERS_TABLE." ut ON ut.id=a.id_user
				ORDER BY sp.status DESC,sp.order_id ".$limit_str;
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	$ads = array();
	if($rs->RowCount()>0){
		while(!$rs->EOF) {
			$row = $rs->GetRowAssoc(false);
				$ads[$i]["number"] = $i+1;
				$ads[$i]["id"] = $row["id"];
				$ads[$i]["id_user"] = $row["id_user"];

				$strSQLt = " SELECT fname, sname FROM ".USERS_TABLE." WHERE id='".$ads[$i]["id_user"]."' ";
				$rst = $dbconn->Execute($strSQLt);
				$ads[$i]["user_login"]=$rst->fields[0]." ".$rst->fields[1];

				$ads[$i]["id_ad"] = $row["id_ad"];
				$ads[$i]["order_id"] = $row["order_id"];
				$ads[$i]["status"] =$row["status"];
				$ads[$i]["is_active"] =$row["is_active"];
				$ads[$i]["type"] =$row["type"];
				$ads[$i]["comment"] = stripslashes($row["comment"]);
				$ads[$i]["with_photo"]=intval($row["with_photo"]);
				$ads[$i]["with_video"]=intval($row["with_video"]);
				$ads[$i]["room_type"]=intval($row["room_type"]);
				$ads[$i]["login"]=$row["login"];
				$ads[$i]["fname"]=$row["fname"];
				$ads[$i]["sname"]=$row["sname"];
				$ads[$i]["user_type"]=$row["user_type"];

				$ads[$i]["zip_code"] = $row["zip_code"];
				$ads[$i]["street_1"] = stripslashes($row["street_1"]);
				$ads[$i]["street_2"] = stripslashes($row["street_2"]);
				$ads[$i]["adress"] = stripslashes($row["adress"]);
				if ($row["movedate"] != '00.00.0000'){
					$ads[$i]["movedate"] = $row["movedate"];
				}

				if ($config["lang_ident"]!='ru') {
					$ads[$i]["country_name"] = RusToTranslit($row["country_name"]);
					$ads[$i]["region_name"] = RusToTranslit($row["region_name"]);
					$ads[$i]["city_name"] = RusToTranslit($row["city_name"]);
				} else {
					$ads[$i]["country_name"] = $row["country_name"];
					$ads[$i]["region_name"] = $row["region_name"];
					$ads[$i]["city_name"] = $row["city_name"];
				}

				if ($ads[$i]["type"] == "1" || $ads[$i]["type"] == "3") {

			$strSQL_payment = " SELECT min_payment, max_payment, auction, min_deposit, max_deposit,
								min_live_square, max_live_square, min_total_square, max_total_square,
								min_land_square, max_land_square, min_floor,  max_floor, floor_num, subway_min,
								min_year_build, max_year_build
								FROM ".USERS_RENT_PAYS_TABLE."
								WHERE id_ad='".$ads[$i]["id_ad"]."' AND id_user='".$ads[$i]["id_user"]."' ";
			$rs_payment = $dbconn->Execute($strSQL_payment);
			$row_payment = $rs_payment->GetRowAssoc(false);
			$ads[$i]["min_payment"] = PaymentFormat($row_payment["min_payment"]);
			$ads[$i]["min_payment_show"] = FormatPrice($ads[$i]["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["max_payment"] = PaymentFormat($row_payment["max_payment"]);
			$ads[$i]["max_payment_show"] = FormatPrice($ads[$i]["max_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["auction"] = $row_payment["auction"];
			$ads[$i]["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
			$ads[$i]["min_deposit_show"] = FormatPrice($ads[$i]["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["max_deposit"] = PaymentFormat($row_payment["max_deposit"]);
			$ads[$i]["max_deposit_show"] = FormatPrice($ads[$i]["max_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["min_live_square"] = $row_payment["min_live_square"];
			$ads[$i]["max_live_square"] = $row_payment["max_live_square"];
			$ads[$i]["min_total_square"] = $row_payment["min_total_square"];
			$ads[$i]["max_total_square"] = $row_payment["max_total_square"];
			$ads[$i]["min_land_square"] = $row_payment["min_land_square"];
			$ads[$i]["max_land_square"] = $row_payment["max_land_square"];
			$ads[$i]["min_floor"] = $row_payment["min_floor"];
			$ads[$i]["max_floor"] = $row_payment["max_floor"];
			$ads[$i]["floor_num"] = $row_payment["floor_num"];
			$ads[$i]["subway_min"] = $row_payment["subway_min"];
			$ads[$i]["min_year_build"] = $row_payment["min_year_build"];
			$ads[$i]["max_year_build"] = $row_payment["max_year_build"];

		} elseif ($ads[$i]["type"] == "2" || $ads[$i]["type"] == "4") {
			/**
			 * fixed values for listing types 2&4 (lease&sell) hold in min_<field_name>
			 */
			$strSQL_payment = "	SELECT min_payment, auction, min_deposit,
								min_live_square, min_total_square,
								min_land_square, min_floor, floor_num, subway_min, min_year_build
								FROM ".USERS_RENT_PAYS_TABLE."
								WHERE id_ad='".$ads[$i]["id_ad"]."' AND id_user='".$ads[$i]["id_user"]."' ";
			$rs_payment = $dbconn->Execute($strSQL_payment);
			$row_payment = $rs_payment->GetRowAssoc(false);
			$ads[$i]["min_payment"] = PaymentFormat($row_payment["min_payment"]);
			$ads[$i]["min_payment_show"] = FormatPrice($ads[$i]["min_payment"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["auction"] = $row_payment["auction"];
			$ads[$i]["min_deposit"] = PaymentFormat($row_payment["min_deposit"]);
			$ads[$i]["min_deposit_show"] = FormatPrice($ads[$i]["min_deposit"], $settings_price["cur_position"], $settings_price["cur_format"]);
			$ads[$i]["min_live_square"] = $row_payment["min_live_square"];
			$ads[$i]["min_total_square"] = $row_payment["min_total_square"];
			$ads[$i]["min_land_square"] = $row_payment["min_land_square"];
			$ads[$i]["min_floor"] = $row_payment["min_floor"];
			$ads[$i]["floor_num"] = $row_payment["floor_num"];
			$ads[$i]["subway_min"] = $row_payment["subway_min"];
			$ads[$i]["min_year_build"] = $row_payment["min_year_build"];
		}

			$lang_ad = 2;
			$used_references = array("realty_type");
			foreach ($REFERENCES as $arr) {
				if (in_array($arr["key"], $used_references)) {
					$name = GetUserAdSprValues($arr["spr_user_table"], $ads[$i]["id_user"], $ads[$i]["id_ad"], $arr["val_table"], $lang_ad);
					if (count($name) == 0 && $arr["spr_match_table"] != ""){
						$name = GetUserAdSprValues($arr["spr_match_table"], $ads[$i]["id_user"], $ads[$i]["id_ad"], $arr["val_table"], $lang_ad);
						$ads[$i][$arr["key"]."_match"] = implode(",", $name);
					} elseif (isset($name) && $name != "") {
					$ads[$i][$arr["key"]] = implode(",", $name);
					}
				}
			}

			$photo_folder = GetSiteSettings("photo_folder");
			$gender_info = getDefaultUserIcon($ads[$i]["user_type"], "");
			$default_photo =  $gender_info["icon_name"];

			/**
		 * photo
		 */
			$strSQL_img = "SELECT id as photo_id, upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='".$ads[$i]["id_ad"]."' AND upload_type='f' AND status='1' AND admin_approve='1'";
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
						$ads[$i]["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$ads[$i]["photo_path"][$j];
						$ads[$i]["photo_view_link"][$j] = "./".$file_name."?sel=upload_view&category=rental&id_file=".$ads[$i]["photo_id"][$j]."&type_upload=f";
						$ads[$i]["del_upload_photo_link"][$j] = "./".$file_name."?sel=upload_delete&id_file=".$ads[$i]["photo_id"][$j]."&type_upload=f&id_user=".$ads[$i]["id_user"];

						$sizes = getimagesize($path);
						$ads[$i]["photo_width"][$j]  = $sizes[0];
						$ads[$i]["photo_height"][$j]  = $sizes[1];
					}
					if(file_exists($thumb_path) && strlen($ads[$i]["photo_path"][$j])>0)
					$ads[$i]["thumb_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/thumb_".$ads[$i]["photo_path"][$j];
					if(!file_exists($path) || !strlen($ads[$i]["photo_path"][$j])){
						$ads[$i]["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
						$ads[$i]["thumb_file"][$j] = $ads[$i]["photo_file"][$j];
					}
					$rs_img->MoveNext();
					$j++;
				}
			} else {
				$ads[$i]["photo_file"][$j] = $config["server"].$config["site_root"].$photo_folder."/".$default_photo;
				$ads[$i]["thumb_file"][$j] = $ads[$i]["photo_file"][$j];
			}


			$ads[$i]["viewprofile_link"] = "./admin_users.php?sel=user_rent&amp;id_user=".$ads[$i]["id_user"]."&amp;id_ad=".$ads[$i]["id_ad"]."&amp;referer=to_sponsor_ads";

				$rs->MoveNext();
				$i++;
			}
	}
	$smarty->assign("links", GetLinkArray($num_records, $page, $param, $ads_numpage));
	$smarty->assign("page", $page);
	$smarty->assign("ads_numpage", $ads_numpage);

	return $ads;
}


/**
 * Get referer by referer_code
 *
 * @param string $referer_code
 * @param int $id_user
 * @return string
 */
function GetReferer ($referer_code, $id_user) {

	$referers = array( "to_user_ads" => "admin_sponsors.php?sel=user_rent&type=add&id_user=".$id_user,
						"to_sponsor_ads" => "admin_sponsors.php?type=list");

	return $referers[$referer_code];
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
 * Get language name by language id
 *
 * @param integer $language_id
 * @return string
 */
function LangNameById($language_id) {
	global $dbconn;

	$name = "";
	$strSQL = "SELECT name FROM ".LANGUAGE_TABLE." WHERE id='$language_id'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount() > 0) {
		$name = $rs->fields[0];
	}
	return $name;
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