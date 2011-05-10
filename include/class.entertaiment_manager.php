<?php
/**
* Info pages management class
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

class EntertaimentManager {
	/**
	 * Database connection instance of a class
	 *
	 * @var object
	 */
	var $_dbconn;

	/**
	* Class Constructor
	* @access public
	* @param void
	* @return void
	**/
	function EntertaimentManager() {
		global $dbconn;

		$this->_dbconn = $dbconn;
	}

	/**
	 * Add info section
	 *
	 * @access public
	 *
	 * @param integer $language_id
	 * @param string $caption
	 * @param string $content
	 * @param string $description
	 * @param string $keywords
	 * @param integer $status
	 * @param string $menu_position - menu position name
	 * @return integer
	 */
	function AddEntertaiment($language_id, $caption, $content, $image, $type, $country, $region, $city, $lat, $lon, $address, $contacts, $video) {
		$sql_query = "INSERT INTO ".ENTERTAIMENT_TABLE." SET ".
					  "language_id = '$language_id', image = '".addslashes($image)."', ".
					  "caption = '".addslashes($caption)."', content = '".addslashes($content)."', city_id = '".$city."', ".
                      "country_id = '".$country."', region_id = '".$region."', type_id = '".$type."', address= '". addslashes($address). "', contacts= '". addslashes($contacts). "', video= '". addslashes($video). "', ".
                      "lat = '".$lat."', lon = '".$lon."', ".
					  "sequence = '".($this->GetMaxSequence($language_id) + 1)."'";
		$record_set = $this->_dbconn->Execute($sql_query);
		return $this->_dbconn->Insert_ID();
	}

	/**
	 * Edit info section
	 *
	 * @access public
	 * @param integer $id
	 * @param string $caption
	 * @param string $content
	 * @param string $description
	 * @param string $keywords
	 * @param integer $status
	 * @return void
	 */
	function EditEntertaiment($id, $caption, $content, $image, $type, $country, $region, $city, $lat, $lon, $address, $contacts, $video) {
		$sql_query = "UPDATE ".ENTERTAIMENT_TABLE." SET ".
					 "image = '".addslashes($image)."', content = '".addslashes($content)."', ".
					 "caption = '".addslashes($caption)."', city_id = '".addslashes($city)."', ".
                     "lat = '".$lat."', lon = '".$lon."', address= '". addslashes($address). "', contacts= '". addslashes($contacts). "', video= '". addslashes($video). "', ".
                     "country_id = '".$country."', region_id = '".$region."', type_id = '".$type."' ".
				     "WHERE id = '$id'";
                     
		$record_set = $this->_dbconn->Execute($sql_query);
		return;
	}

	/**
	* Get max sequence for info page section on one $language_id and $menu_position
	*
	* @access public
	* @param integer $language_id - language ID
	* @param string $menu_position - menu position name
	* @return integer
	**/
	function GetMaxSequence($language_id) {
		$sql_query = "SELECT MAX(sequence) AS max_sequence
					  FROM ".ENTERTAIMENT_TABLE."
					  WHERE language_id = '$language_id'";
		$record_set = $this->_dbconn->Execute($sql_query);
		return $record_set->fields[0];
	}

	/**
	 * Get Info Sections list for language and menu position
	 *
	 * @access public
	 * @param integer $language_id
	 * @param string $menu_position - menu position name
	 * @param boolean $only_active
	 * @return array
	 */
	function GetEntertaimentList($language_id) {
		$sql_query = "SELECT id, sequence, caption, image ".
					 "FROM ".ENTERTAIMENT_TABLE." ".
					 "WHERE language_id = '$language_id' ";
		
		$sql_query .= "ORDER BY sequence";
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$pages[] = $page;
			$record_set->MoveNext();
		}
		return $pages;
	}

    function GetEntertaimentListWithCoords($language_id) {
		$sql_query = "SELECT id, sequence, caption, image, lat, lon, type_id ".
					 "FROM ".ENTERTAIMENT_TABLE." ".
					 "WHERE language_id = '$language_id' ";
		
		$sql_query .= "AND lat > 0 and lon > 0 ORDER BY sequence";
        
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$pages[] = $page;
			$record_set->MoveNext();
		}
        
		return $pages;
	}
    
    function GetEntertaimentImages($id) {
		$sql_query = "SELECT image_id, image ".
					 "FROM ".ENTERTAIMENT_IMAGES_TABLE." ".
					 "WHERE entertaiment_id = '$id' ";
		
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$pages[] = $page;
			$record_set->MoveNext();
		}
		return $pages;
	}
	/**
	 * Get info section by id
	 *
	 * @access public
	 * @param integer $id
	 * @return void
	 */
	function GetEntertaiment($id) {
		$sql_query = "SELECT id, language_id, sequence, caption, content, ".
					 "image, type_id, country_id, region_id, city_id, lat, lon, address, contacts, video ".
					 "FROM ".ENTERTAIMENT_TABLE." ".
					 "WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);
		$page = $record_set->GetRowAssoc(false);
		$page["caption"] = stripslashes($page["caption"]);
		$page["content"] = stripslashes($page["content"]);
		$page["image"] = stripslashes($page["image"]);
        $page["address"] = stripslashes($page["address"]);
        $page["contacts"] = stripslashes($page["contacts"]);
        $page["video"] = stripslashes($page["video"]);
		return $page;
	}

	
	/**
	 * Move info page up on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function UpEntertaiment($id) {
		$page = $this->GetEntertaiment($id);

		$sql_query = "SELECT id, sequence FROM ".ENTERTAIMENT_TABLE." ".
					 "WHERE sequence < '{$page["sequence"]}' AND sequence > '0' AND ".
					 "language_id = '{$page["language_id"]}' ".
					 "ORDER BY sequence DESC";
		$record_set = $this->_dbconn->Execute($sql_query);
		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".ENTERTAIMENT_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".ENTERTAIMENT_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Move info page down on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function DownEntertaiment($id) {
		$page = $this->GetEntertaiment($id);

		$sql_query = "SELECT id, sequence FROM ".ENTERTAIMENT_TABLE." ".
					 "WHERE sequence > '{$page["sequence"]}' AND ".
					 "language_id = '{$page["language_id"]}' ".
					 "ORDER BY sequence ASC";
		$record_set = $this->_dbconn->Execute($sql_query);

		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".ENTERTAIMENT_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".ENTERTAIMENT_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Delete page by id and
	 *
	 * @access public
	 * @param integer $id - page ID
	 * @return void
	 */
	function DeleteEntertaiment($id) {
		/**
		 * change sequence for all other pages of the same language
		 */
		$page = $this->GetEntertaiment($id);
		if ($page["sequence"] < $this->GetMaxSequence($page["language_id"], $page["menu_position"])) {
			$sql_query = "SELECT id FROM ".ENTERTAIMENT_TABLE." ".
					 	 "WHERE sequence > '{$page["sequence"]}' AND ".
					 	 "language_id = '{$page["language_id"]}' ";
			$record_set = $this->_dbconn->Execute($sql_query);
			while (!$record_set->EOF) {
				$sql_query = "UPDATE ".ENTERTAIMENT_TABLE." SET ".
				 			 "sequence = sequence-1 ".
						 	 "WHERE id = '{$record_set->fields[0]}'";
				$this->_dbconn->Execute($sql_query);

				$record_set->MoveNext();
			}
		}
		/**
		 * delete page
		 */
		$sql_query = "DELETE FROM ".ENTERTAIMENT_TABLE." WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);

	}
    
    function SaveUploadForm($upload){
    	global $smarty, $dbconn, $config, $auth, $VIDEO_TYPE_ARRAY, $VIDEO_EXT_ARRAY;
    
    	$err = "";
    	$id = intval($auth[0]);
    
    	$settings = GetSiteSettings(array("use_video_approve", "video_max_count", "video_max_size", "video_folder",
    				 "use_ffmpeg", "path_to_ffmpeg", "flv_output_dimension", "flv_output_audio_sampling_rate", "flv_output_audio_bit_rate", "flv_output_foto_dimension"));
    
    	
		$folder = $settings["video_folder"];
		$type_array = $VIDEO_TYPE_ARRAY;
		$ext_array = $VIDEO_EXT_ARRAY;
		$max_size = $settings["video_max_size"];
		$err_type = "invalid_video_type";
		$err_size = "invalid_video_size";
		$use_approve = $settings["use_video_approve"];
	
        if ($admin_mode == 1 )$use_approve = 0;
    	$new_temp_path = GetTempUploadFile($upload["name"]);
    
    	/// for save mode restrict
    	if(is_uploaded_file($upload["tmp_name"]) && move_uploaded_file($upload["tmp_name"],$new_temp_path)){
    		$upload["tmp_name"] = $new_temp_path;
    	}
    	$filename_arr = explode(".", $upload["name"]);
    	$nr = count($filename_arr);
    	$ext = strtolower($filename_arr[$nr-1]);
    
    	if (!in_array($upload["type"], $type_array) || !in_array($ext, $ext_array) ){
    		$err .= $err_type;
    	}
    	if($upload["size"] > $max_size){
    		if($err)$err .= "<br>";
    		$err .= $err_size;
    	}
    
    	if($err){
    		return array( 'error' => $err, 'success' => 0);
    	}else{
    		$new_file_name = GetNewFileName($upload["name"], $id);
            
    		$upload_path =$config["site_path"].$folder."/".$new_file_name;
    		if(copy($upload["tmp_name"], $upload_path)){
    			unlink($upload["tmp_name"]);
    			if ($upload_type == "v" && $settings['use_ffmpeg'] == 1) {	
    				
    				$new_file_name_arr = explode(".", $new_file_name);
    				$flv_name = $new_file_name_arr[0].".flv";
    				$flv_path = $config["site_path"].$folder."/".$flv_name;				
    				
    								
    				@exec($settings['path_to_ffmpeg']."ffmpeg.exe -y -i ".$upload_path." -s ".$settings['flv_output_dimension']."  -ar ".$settings['flv_output_audio_sampling_rate']." -ab ".$settings['flv_output_audio_bit_rate']." ".$flv_path, $res);					
    				@exec($settings['path_to_ffmpeg']."ffmpeg.exe -i ".$upload_path." -an -ss 00:00:00 -t 00:00:01 -r 1 -y -s ".$settings['flv_output_foto_dimension']." ".$config["site_path"].$folder."/".$new_file_name_arr[0]."%d.jpg ", $res);
    				
    			}
    
    			///// insert entry into db
    			//$admin_approve = (intval($use_approve)) ? 0 : 1;
//     	$strSQL = "SELECT MAX(sequence) AS max_seq FROM ".USERS_RENT_UPLOADS_TABLE." WHERE upload_type='".$upload_type."' AND id_ad='$id_ad'";
//    			$rs = $dbconn->Execute($strSQL);
//    			$sequence = ($rs->RowCount() > 0) ? $rs->fields[0]+1 : 1;
//    					
//    $strSQL = "insert into ".USERS_RENT_UPLOADS_TABLE." (id_user, id_ad, upload_path, upload_type, file_type, admin_approve, user_comment, sequence) values ('".$id."', '".$id_ad."', '".$new_file_name."', '".$upload_type."', '".$upload["type"]."', '".$admin_approve."', '".$user_comment."', '$sequence')";
//    			$dbconn->Execute($strSQL);
//    			AdUpdateDate($id_ad);
    		}else{
    			return array( 'error' => "upload_err", 'success' => 0);
    		}
    	}
        
    	return array( 'file' => $new_file_name, 'success' => 1);
    }
}

?>