<?php
/**
* Settings management class
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 13:51:23 $
**/

class SettingsManager {
	/**
	 * Database connection instance of a class
	 *
	 * @var object
	 * @var object
	 */
	var $_dbconn;
	var $config;

	/**
	* Class Constructor
	* @access public
	* @param void
	* @return void
	**/
	function SettingsManager() {
		global $dbconn, $config;

		$this->_dbconn = $dbconn;	
		$this->config = $config;			
	}
	
	/**
	 * Get server errors, which admin has edited yet
	 *
	 * @param int $language_id	 
	 * @return array
	 */
	function GetErrorsList( $language_id) {
		$sql_query = "SELECT id, code, description, message_$language_id AS message ".
					 "FROM ".SERVER_ERRORS_TABLE;		
		$sql_query .= " ORDER BY id";
		$record_set = $this->_dbconn->Execute($sql_query);
		$errors = array();
		$i = 0;
		while (!$record_set->EOF) {
			$errors[$i] = $record_set->GetRowAssoc( false );
			$record_set->MoveNext();
			$i++;
		}
		return $errors;
	}
	/**
	 * Save server errors list
	 *
	 * @param int $language_id
	 */
	function SaveErrorsList( $language_id) {
		foreach ($_REQUEST["message"] as $message=>$err_id) {
			$err_id = addslashes(trim($err_id));
			$strSQL = "UPDATE ".SERVER_ERRORS_TABLE." SET message_$language_id='$err_id' WHERE id='$message'";
			$this->_dbconn->Execute($strSQL);		
		}
	}
	/**
	 * Set message default ( = description)
	 *
	 * @param int $language_id
	 */
	function SetDefault( $language_id) {
		$sql_query = "SELECT description ".
					 "FROM ".SERVER_ERRORS_TABLE." WHERE id='".$_REQUEST["pos"]."'";				
		$record_set = $this->_dbconn->Execute($sql_query);
		if (!$record_set->EOF) {
			$row = $record_set->GetRowAssoc( false );
			$default = $row["description"];
		}
		
		$strSQL = "UPDATE ".SERVER_ERRORS_TABLE." SET message_$language_id='$default' WHERE id='".$_REQUEST["pos"]."'";
		$this->_dbconn->Execute($strSQL);	
		
	}
	/**
	 * Get message by error's code
	 *
	 * @param int $errors_code
	 * @param int $language_id
	 * @return string
	 */
	function GetErrorByCode( $errors_code, $language_id) {
		$message = "";
		$sql_query = "SELECT message_$language_id ".
					 "FROM ".SERVER_ERRORS_TABLE." WHERE code='$errors_code'";
		$record_set = $this->_dbconn->Execute($sql_query);
		if ($record_set->RowCount() > 0) {
			$row = $record_set->GetRowAssoc( false );
			$message = $row["message_$language_id"];
		}			 
		return $message;					 
	}
	/**
	 * Get site settings
	 *
	 * @param unknown_type $set_arr
	 * @return unknown
	 */
	function GetSiteSettings( $set_arr="") {
		
		// array
		if($set_arr != ""  &&  is_array($set_arr) && count($set_arr)>0 ){
			foreach($set_arr as $key => $set_name){
				$set_arr[$key] = "'".$set_name."'";
			}
			$sett_string = implode(", ", $set_arr);
			$str_sql = "Select value, name from ".SETTINGS_TABLE." where name in (".$sett_string.")";
			$rs = $this->_dbconn->Execute($str_sql);
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$settings[$row["name"]] = $row["value"];
				$rs->MoveNext();
			}
		}elseif(strlen($set_arr)>0){
			$str_sql = "Select value, name from ".SETTINGS_TABLE." where name = '".strval($set_arr)."'";
			$rs = $this->_dbconn->Execute($str_sql);
			$row = $rs->GetRowAssoc(false);
			$settings = $row["value"];
		}elseif(strval($set_arr)==""){
			$str_sql = "Select value, name from ".SETTINGS_TABLE." order by id";
			$rs = $this->_dbconn->Execute($str_sql);
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$settings[$row["name"]] = $row["value"];
				$rs->MoveNext();
			}
		}
		return $settings;
	}
	
	/**
	 * Save Site Settings
	 *
	 * @param array $set_arr
	 */
	function SaveSiteSettings( $set_arr="") {		
		if($set_arr != ""  &&  is_array($set_arr) && count($set_arr)>0 ){
			foreach($set_arr as $name => $value){	
				$str_sql = "UPDATE ".SETTINGS_TABLE." SET value='$value' WHERE name='$name'";							
				$rs = $this->_dbconn->Execute($str_sql);
			}					
		}
	}
	
	/**
	 * Return files name in directory
	 *
	 * @param string $dir
	 * @param string $ext
	 * @return array
	 */
	function GetFilesName($dir, $ext) {		
		$dh = opendir($dir);
		$ret_arr = array();
		while (($file = readdir($dh)) !== false) {
			if ($file != "." && $file != ".." && $file != "CVS") {
				$filename_arr = explode(".", $file);
				$ext = strtolower($filename_arr[1]);
				if ($ext == "ttf") {
					$ret_arr[$filename_arr[0]] = $file;
				}
			}
		}
		return $ret_arr;		
	}
	
	/**
	 * Get logo settings for current languages
	 *
	 * @param int $language_id
	 * @return array
	 */
	
	function GetLogoSettings( $language_id) {
		$sql_query = "SELECT id, type, width, height, pic_$language_id AS img, alt_$language_id AS alt  ".
					 "FROM ".LOGO_SETTINGS_TABLE;		
		$sql_query .= " ORDER BY id";		
		$record_set = $this->_dbconn->Execute($sql_query);
		$ret_arr = array();
		$i = 0;
		while (!$record_set->EOF) {
			$row = $record_set->GetRowAssoc( false );
			$ret_arr[$row["type"]] = $row;			
			$record_set->MoveNext();
			$i++;
		}
		return $ret_arr;
	}
	
	/**
	 * Save alt for any logo
	 *
	 * @param int $language_id
	 */
	function SaveLogoSettings( $language_id, $default_id=0, $default_alt="") {
		if (!$default_id) {
			foreach ($_REQUEST["alt"] as $alt=>$alt_text) {
				$alt_text = addslashes(trim($alt_text));
				$strSQL = "UPDATE ".LOGO_SETTINGS_TABLE." SET alt_$language_id='$alt_text' WHERE id='$alt'";
				$this->_dbconn->Execute($strSQL);		
			}
		}
		else {
			
			$alt_text = $default_alt;
			$strSQL = "UPDATE ".LOGO_SETTINGS_TABLE." SET alt_$language_id='$alt_text' WHERE id='$default_id'";
			$this->_dbconn->Execute($strSQL);
		}
	}
	
	function UpdateLogo( $language_id, $id, $new_logo_name, $lang){
		
		$sql_query = "SELECT pic_$language_id AS img FROM ".LOGO_SETTINGS_TABLE." WHERE id='$id'";		
		$record_set = $this->_dbconn->Execute($sql_query);
		$row = $record_set->GetRowAssoc( false );		
		unlink($this->config["site_path"].$this->GetSiteSettings("index_theme_path").$this->GetSiteSettings("index_theme_images_path")."/".$lang."/".$row["img"]);
		$strSQL = "UPDATE ".LOGO_SETTINGS_TABLE." SET pic_$language_id='$new_logo_name' WHERE id='$id'";
		$this->_dbconn->Execute($strSQL);			
	}		
}

?>