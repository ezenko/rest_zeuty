<?php
/**
* Class for working with images (upload, resize, delete)
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.6 $ $Date: 2009/01/29 15:08:55 $
**/

class Images{
	var $dbconn;
	var $gd_used;
	var $settings;		
	var $IMG_TYPE_ARRAY;
	var $IMG_EXT_ARRAY;
	var $site_path;
	var $safe_mode_used;
	var $waterlogo_path;
	var $users_table = USERS_TABLE;
	var $rent_plan_table = USER_RENT_PLAN_TABLE;
	var $user_rent_upload_table = USERS_RENT_UPLOADS_TABLE;
	var $settings_table = SETTINGS_TABLE;
	

	function Images($dbconn) {		
		$this->dbconn = $dbconn;		
		$this->site_path = dirname(__FILE__)."/..";
		$this->settings = $this->GetSiteSettings();
		$this->file_temp_path = $this->site_path."/templates_c";
		$this->IMG_TYPE_ARRAY = array("image/jpeg", "image/pjpeg", "image/gif", "image/bmp", "image/tiff", "image/png", "image/x-png" );
		$this->IMG_EXT_ARRAY = array("jpeg", "jpg", "gif", "wbmp", "tiff", "png" );
		$this->gd_used = extension_loaded('gd')?1:0;
		$this->safe_mode_used = (ini_get('safe_mode'))?1:0;
	}

	function GetSiteSettings($set_arr="") {
		// array
		if ($set_arr != ""  &&  is_array($set_arr) && count($set_arr)>0 ) {
			foreach($set_arr as $key => $set_name) {
				$set_arr[$key] = "'".$set_name."'";
			}
			$sett_string = implode(", ", $set_arr);
			$str_sql = "Select value, name from ".$this->settings_table." where name in (".$sett_string.")";
			$rs = $this->dbconn->Execute($str_sql);
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$settings[$row["name"]] = $row["value"];
				$rs->MoveNext();
			}
		}elseif (strlen($set_arr)>0) {
			$str_sql = "Select value, name from ".$this->settings_table." where name = '".strval($set_arr)."'";
			$rs = $this->dbconn->Execute($str_sql);
			$row = $rs->GetRowAssoc(false);
			$settings = $row["value"];
		}elseif (strval($set_arr)=="") {
			$str_sql = "Select value, name from ".$this->settings_table." order by id";
			$rs = $this->dbconn->Execute($str_sql);
			while(!$rs->EOF) {
				$row = $rs->GetRowAssoc(false);
				$settings[$row["name"]] = $row["value"];
				$rs->MoveNext();
			}
		}
		return $settings;
	}

	/**
	 * Upload users' company logo in registration.php
	 *
	 * @param array $upload
	 * @param integer $id_user
	 * @return string
	 */
	function UploadCompanyLogo($upload, $id_user, $admin_mode = 0, $add_par = "company_logo") {
		$folder = $this->settings["photo_folder"];
		$max_size = $this->settings["photo_max_size"];
		$err_type = "invalid_photo_type";
		$err_size = "invalid_photo_size";
		$use_approve = $this->settings["use_photo_approve"];

		if (!is_uploaded_file($upload["tmp_name"])) {
			return "upload_err";
		}
		if ($this->safe_mode_used) {
			$new_temp_path = $this->GetTempUploadFile($upload["name"]);
			if (move_uploaded_file($upload["tmp_name"], $new_temp_path)) {
				$upload["tmp_name"] = $new_temp_path;
			}
		}
		$filename_arr = explode(".", $upload["name"]);
		$nr = count($filename_arr);
		$ext = strtolower($filename_arr[$nr-1]);
		if ((!in_array($upload["type"], $this->IMG_TYPE_ARRAY)) || (!in_array($ext, $this->IMG_EXT_ARRAY)) ) {
			return $err_type;
		}
		if ($upload["size"] > $max_size) {			
			return $err_size;
		}
		$new_file_name = $this->GetNewFileName($upload["name"], $id_user);
		$upload_path = $this->site_path.$folder."/".$new_file_name;

		if (copy($upload["tmp_name"], $upload_path)) {
			if ($this->gd_used) {
				$thumb_upload_path = $this->site_path.$folder."/".$new_file_name;
				copy($upload["tmp_name"], $thumb_upload_path);
				if (file_exists($thumb_upload_path)) {
					$resize_res =  $this->ReSizeWithoutCropImage($thumb_upload_path, 150, 100, 0);
				}
			}
			unlink($upload["tmp_name"]);
			if (!$admin_mode) {
				$admin_approve = ($use_approve) ? 0 : 1;
			} else {
				$admin_approve = 1;
			}
			switch ($add_par) {
				case "company_logo":
					$strSQL = "UPDATE ".USER_REG_DATA_TABLE." SET logo_path='".$new_file_name."', admin_approve='$admin_approve' WHERE id_user='".$id_user."'";										
					break;
				case "agent_photo":
					$strSQL = "INSERT INTO ".USER_PHOTOS_TABLE." (id_user, photo_path, approve) VALUES ('$id_user', '$new_file_name', '$admin_approve');";										
					break;	
			}
			
			$this->dbconn->Execute($strSQL);
		} else {
			$err = "upload_err";
		}
		return $err;
	}

	function UploadImages($upload, $id_user, $upload_type, $id_file="", $admin_mode=0, $user_comment="", $id_ad="", $part="") {
		switch($upload_type) {
			case "icon":
				$folder = $this->settings["icons_folder"];
				$max_width = $this->settings["icon_max_width"];
				$max_height = $this->settings["icon_max_height"];
				$max_size = $this->settings["icon_max_size"];
				$err_type = $this->lang["err"]["invalid_icon_type"];
				$err_size = $this->lang["err"]["invalid_icon_size"];
				$err_width = $this->lang["err"]["invalid_icon_width"];
				$err_height = $this->lang["err"]["invalid_icon_height"];
				$use_approve = $this->settings["use_icon_approve"];
				break;
			case "f":
				$folder = $this->settings["photo_folder"];
				$max_width = $this->settings["photo_max_width"];
				$max_height = $this->settings["photo_max_height"];
				$max_size = $this->settings["photo_max_size"];
				$err_type = "invalid_photo_type";
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				$use_approve = $this->settings["use_photo_approve"];
				break;
			case "slide":
				$folder = $this->settings["slideshow_folder"];
				$max_width = $this->settings["photo_max_width"];
				$max_height = $this->settings["photo_max_height"];
				$max_size = $this->settings["slideshow_max_size"];
				$err_type = "invalid_photo_type";
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				break;
			case "plan":
				$folder = $this->settings["photo_folder"];
				$max_width = $this->settings["photo_max_width"];
				$max_height = $this->settings["photo_max_height"];
				$max_size = $this->settings["photo_max_size"];
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				$err_type = "invalid_photo_type";
				$use_approve = $this->settings["use_photo_approve"];
				break;
		}
		switch($part) {
			case "rent" : $table = $this->user_rent_upload_table;
			break;
		}
		if ($admin_mode == 1 )$use_approve = 0;

		if (!is_uploaded_file($upload["tmp_name"])) {
			$err = "upload_err";
			return $err;
		}
		if ($this->safe_mode_used) {
			$new_temp_path = $this->GetTempUploadFile($upload["name"]);
			if (move_uploaded_file($upload["tmp_name"],$new_temp_path)) {
				$upload["tmp_name"] = $new_temp_path;
			}
		}

		///// if we using picture resize: traing to resize picture
		if ($this->settings["use_image_resize"]) {
			$resize_res =  $this->ReSizeImage($upload["tmp_name"], $max_width, $max_height);
			$upload["size"] = filesize($upload["tmp_name"]);
		}
		// get width/height and size info and check on errors
		if (filesize($upload["tmp_name"]) == 0){
			$err = "upload_err";
			return $err;
		}
		$upload_info = GetImageSize($upload["tmp_name"]);
		if ($upload_info[0] > $max_width) {
			return $err_width;
		}
		if ($upload_info[1] > $max_height) {
			return $err_height;
		}
		$filename_arr = explode(".", $upload["name"]);
		$nr = count($filename_arr);
		$ext = strtolower($filename_arr[$nr-1]);
		if ((!in_array($upload["type"], $this->IMG_TYPE_ARRAY)) || (!in_array($ext, $this->IMG_EXT_ARRAY)) ) {
			return $err_type;
		}
		if ($upload["size"] > $max_size) {
			return $err_size;
		}

		$new_file_name = $this->GetNewFileName($upload["name"], $id_user);

		$upload_path = $this->site_path.$folder."/".$new_file_name;

		if (copy($upload["tmp_name"], $upload_path)) {
			if ($this->gd_used) {
				if ($this->settings["use_image_resize"]) {
					$resize_res =  $this->ReSizeImage($upload_path, $max_width, $max_height, 1);					
				}
				$thumb_upload_path = $this->site_path.$folder."/thumb_".$new_file_name;
				copy($upload["tmp_name"], $thumb_upload_path);
				if (file_exists($thumb_upload_path)) {
					$resize_res =  $this->ReSizeImage($thumb_upload_path, $this->settings["thumb_max_width"], $this->settings["thumb_max_height"], 0);
				}				
				$thumb_big_upload_path = $this->site_path.$folder."/thumb_big_".$new_file_name;
				copy($upload["tmp_name"], $thumb_big_upload_path);
				if (file_exists($thumb_big_upload_path)) {
					$resize_res =  $this->ReSizeImage($thumb_big_upload_path, $this->settings["thumb_big_max_width"], $this->settings["thumb_big_max_height"], 0);
				}
				
				
			}
			unlink($upload["tmp_name"]);
//			$this->DeleteUploadedFiles($upload_type, $id_file, $id_user);

			if ($upload_type == 'f') {
				if (intval($use_approve)) {
					$admin_approve = 0;
					$err = "file_upload";
				} else {
					$admin_approve = 1;
					$err = "file_upload_without_approve";
				}				
				$strSQL = "SELECT MAX(sequence) AS max_seq FROM ".$table." WHERE upload_type='".$upload_type."' AND id_ad='$id_ad'";
				$rs = $this->dbconn->Execute($strSQL);
				$sequence = ($rs->RowCount() > 0) ? $rs->fields[0]+1 : 1;
				
				$strSQL = "INSERT INTO ".$table." (id_user, upload_path, upload_type, file_type, admin_approve, user_comment, id_ad, sequence) VALUES ('".$id_user."', '".$new_file_name."', '".$upload_type."', '".$upload["type"]."', '".$admin_approve."', '".addslashes($user_comment)."', '".$id_ad."', '$sequence')";
				$this->dbconn->Execute($strSQL);
			} elseif ($upload_type == 'icon') {
				if (intval($use_approve)) {
					$this->dbconn->Execute("update ".$this->users_table." set icon_path='', icon_path_temp='".$new_file_name."'  where id= '".$id_user."'");
				} else {
					$this->dbconn->Execute("update ".$this->users_table." set icon_path='".$new_file_name."', icon_path_temp=''  where id= '".$id_user."'");
				}
			} elseif ($upload_type  == 'slide') {
				$strSQL = "INSERT INTO ".$this->slides_table." (id_user, id_ad, upload_path, file_type, admin_approve, user_comment, type )
							VALUES ('".$id_user."', '".$id_ad."', '".$new_file_name."', '".$upload["type"]."', '1', '".addslashes($user_comment)."', '".$part."')";
				$this->dbconn->Execute($strSQL);
				$err = "file_upload_without_approve";
			} elseif ($upload_type  == 'plan') {
				if (intval($use_approve)) {
					$admin_approve = 0;
					$err = "file_upload";
				} else {
					$admin_approve = 1;
					$err = "file_upload_without_approve";
				}
				switch ($part) {
					case "rent":
						$plan_table = $this->rent_plan_table;
					break;
				}
				$strSQL = "SELECT MAX(sequence) AS max_seq FROM ".$plan_table." WHERE id_ad='$id_ad'";
				$rs = $this->dbconn->Execute($strSQL);				
				$sequence = ($rs->RowCount() > 0) ? $rs->fields[0]+1 : 1;
								
				$strSQL = "INSERT INTO ".$plan_table." (id_user, id_ad, admin_approve, upload_path, user_comment, sequence) VALUES ('".$id_user."','".$id_ad."', '".$admin_approve."', '".$new_file_name."', '".addslashes($user_comment)."', '$sequence')";
				$this->dbconn->Execute($strSQL);
			}
		} else {
			$err = "upload_err";
		}
		return $err;
	}

	function UploadDefaultImages($upload, $upload_type, $settings_name = "", $man_width = "", $man_height = "", $lang="") {
	switch($upload_type) {
			case "icon":
				$folder = $this->settings["icons_folder"];
				$max_size = $this->settings["photo_max_size"];
				$max_width = $this->settings["thumb_max_width"];
				$max_height = $this->settings["thumb_max_height"];
				$err_type = "invalid_photo_type";
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				break;
			case "f":
				$folder = $this->settings["photo_folder"];
				$max_size = $this->settings["photo_max_size"];
				$max_width = $this->settings["thumb_max_width"];
				$max_height = $this->settings["thumb_max_height"];
				$err_type = "invalid_photo_type";
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				break;
			case "watermark":
				$folder = $this->settings["photo_folder"];
				$max_size = $this->settings["photo_max_size"];
				$max_width = $this->settings["watermark_width"];
				$max_height = $this->settings["watermark_height"];
				$err_type = "invalid_photo_type";
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				break;	
			case "anylogo":
				$folder = $this->settings["index_theme_path"].$this->settings["index_theme_images_path"]."/".$lang;
				$max_size = $this->settings["photo_max_size"];
				$max_width = intval($man_width);
				$max_height = intval($man_height);
				$err_type = "invalid_photo_type";
				$err_size = "invalid_photo_size";
				$err_width = "invalid_photo_width";
				$err_height = "invalid_photo_height";
				break;		
				
		}
		
		
		if (!is_uploaded_file($upload["tmp_name"])) {
			$err = "upload_err";
			return $err;
		}
		if ($this->safe_mode_used) {
			$new_temp_path = $this->GetTempUploadFile($upload["name"]);
			if (move_uploaded_file($upload["tmp_name"],$new_temp_path)) {
				$upload["tmp_name"] = $new_temp_path;
			}
		}

		///// try to resize default image anyway
		if ($this->gd_used) {
			$resize_res =  $this->ReSizeImage($upload["tmp_name"], $max_width, $max_height);
			$upload["size"] = filesize($upload["tmp_name"]);
		}

		// get width/height and size info and check on errors
		$upload_info = GetImageSize($upload["tmp_name"]);
		if ($upload_info[0] > $max_width) {
			return $err_width;
		}
		if ($upload_info[1] > $max_height) {
			return $err_height;
		}
		$filename_arr = explode(".", $upload["name"]);
		$nr = count($filename_arr);
		$ext = strtolower($filename_arr[$nr-1]);
		if ( (!in_array($upload["type"], $this->IMG_TYPE_ARRAY)) || (!in_array($ext, $this->IMG_EXT_ARRAY)) ) {
			return $err_type;
		}
		if ($upload["size"] > $max_size) {
			return $err_size;
		}
		// rename file
		$new_file_name = $this->GetNewFileName($upload["name"], $upload_type);
		
		
		//// get dist path for image
		if ($upload_type != 'anylogo'){
			$upload_path = $this->site_path.$folder."/".$new_file_name;
			if (copy($upload["tmp_name"], $upload_path)) {
				///create thumb if gd used
				unlink($upload["tmp_name"]);
	
				if ($upload_type == 'f') {
					/// insert entry into db
					$strSQL = "Update ".$this->settings_table." set  value='".$new_file_name."' where name='$settings_name'";
					$this->dbconn->Execute($strSQL);
				}elseif ($upload_type == 'icon') {
					//$strSQL = "INSERT INTO ".$this->icons_table." set  file_path='".$new_file_name."', admin_approve='1'";
					$this->dbconn->Execute($strSQL);
				}elseif ($upload_type == 'watermark') {
					unlink($this->site_path.$folder."/".$this->settings["watermark_image"]);
					$strSQL = "Update ".$this->settings_table." set  value='".$new_file_name."' where name='$settings_name'";
					$this->dbconn->Execute($strSQL);
				}
			} else {
				$err = "upload_err";
				return $err;
			}
		}else{
			$templates_arr = ScanTemplateFolder($this->site_path."/templates/");
			$upload_path_src = $upload["tmp_name"];
			foreach ($templates_arr AS $key=>$template){
				$upload_path = $this->site_path."/templates/".$template.$this->settings["index_theme_images_path"]."/".$lang."/".$new_file_name;				
				if (copy($upload_path_src, $upload_path)) {
					///create thumb if gd used
					unlink($upload["tmp_name"]);
					$err = $new_file_name;
				} else {
					$err = "upload_err";
					return $err;
				}
				$upload_path_src = $upload_path;
			}
			return $err;
		}
	}

	function DeleteUploadedFiles($type_upload, $id_file="", $id_user="") {
		switch($type_upload) {
			case "icon": $folder = $this->settings["icons_folder"]; break;
			case "f": $folder = $this->settings["photos_folder"]; break;
			case "slide": $folder = $this->settings["slideshow_folder"]; break;
			default: $folder = $this->settings["photos_folder"];
		}
		if ($type_upload == 'f') {
			$rs_upl=$this->dbconn->Execute("Select upload_path from ".$this->user_upload_table." where id='".$id_file."' and id_user= '".$id_user."'");
			if (strlen($rs_upl->fields[0])>0) {
				$old_file =$this->site_path.$folder."/".$rs_upl->fields[0];
				if (file_exists($old_file)) {
					unlink($old_file);
				}
				$old_thumb_file =$this->site_path.$folder."/thumb_".$rs_upl->fields[0];
				if (file_exists($old_thumb_file)) {
					unlink($old_thumb_file);
				}
				$this->dbconn->Execute("delete from ".$this->user_upload_table." where id='".$id_file."' and id_user= '".$id_user."'");
			}
		}elseif ($type_upload == 'icon' ) {
			$rs_upl=$this->dbconn->Execute("Select icon_path, icon_path_temp from ".$this->users_table." where id='".$id_user."'");
			$file = strlen($rs_upl->fields[0])?$rs_upl->fields[0]:$rs_upl->fields[1];
			if (strlen($file)>0) {
				$old_file =$this->site_path.$folder."/".$file;
				if (file_exists($old_file)) {
					unlink($old_file);
				}
				$old_thumb_file =$this->site_path.$folder."/thumb_".$file;
				if (file_exists($old_thumb_file)) {
					unlink($old_thumb_file);
				}
				$this->dbconn->Execute("update ".$this->users_table." set icon_path='', icon_path_temp=''  where id= '".$id_user."'");
			}
		}elseif ($type_upload == 'slide' ) {
			$rs_upl=$this->dbconn->Execute("Select upload_path from ".$this->slides_table." where id='".$id_user."'");
			$file = strlen($rs_upl->fields[0])?$rs_upl->fields[0]:$rs_upl->fields[1];
			if (strlen($file)>0) {
				$old_file =$this->site_path.$folder."/".$file;
				if (file_exists($old_file)) {
					unlink($old_file);
				}
				$old_thumb_file =$this->site_path.$folder."/thumb_".$file;
				if (file_exists($old_thumb_file)) {
					unlink($old_thumb_file);
				}
				$this->dbconn->Execute("update ".$this->users_table." set icon_path='', icon_path_temp=''  where id= '".$id_user."'");
			}
		}
		return "";
	}

	function ReSizeImage($path, $width_to, $height_to, $thumb=0) {
		if (file_exists($path) && $this->gd_used) {	//// if such image exists and gd lib is loaded
			$path_full = $path;
			$path_insert = $this->site_path.$this->settings["photo_folder"]."/".$this->settings["watermark_image"];
			$image_info = GetImageSize($path);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			$image_type = $image_info[2];
			if ($image_width > $width_to || $image_height > $height_to) {
				if ($this->settings["use_image_resize"] || $thumb) {
					$st = $this->ReSizeAction($path, $image_type, $image_width, $image_height, $width_to, $height_to);
					if ($this->settings["use_watermark"] && $thumb) {						
						$this->mergePix($path_full, $path_insert, $path_full, 3, 40);
					}
					if ($st) {
						return 'giffed';
					} else {
						return true;
					}
				} else {
					return true;
				}
			} else {
				if ($this->settings["use_watermark"] && $thumb) {						
					$this->mergePix($path_full, $path_insert, $path_full, 3, 40);
				}
				return true;
			}
		} else {
			return true;
		}
	}

	function ReSizeAction($path, $type, $image_width, $image_height, $width_to, $height_to) {
				switch($type) {
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
					$srcWidth  = ImageSX( $srcImage );
					$srcHeight = ImageSY( $srcImage );
					$k_1 = $srcWidth/$width_to;
					$k_2 = $srcHeight/$height_to;
					if ($k_1<$k_2) {	/// $k_1->1
						$resized_image_width = $width_to;
						$resized_image_height = round($srcHeight/$k_1);
						$src_x = 0;
						$src_y = round($k_1*abs($resized_image_height - $height_to)/2);
						$sample_image_width = round($k_1*$width_to);
						$sample_image_height = round($k_1*$height_to);
					}elseif ($k_1>=$k_2) {	/// $k_2->1
						$resized_image_height = $height_to;
						$resized_image_width = round($srcWidth/$k_2);
						$src_x = round($k_2*abs($resized_image_width - $width_to)/2);
						$src_y = 0;
						$sample_image_width = round($k_2*$width_to);
						$sample_image_height = round($k_2*$height_to);

					}
					$destImage = @imagecreatetruecolor($width_to, $height_to);
					$bg_color = imagecolorallocate($destImage, 255, 255, 255);
					imagefilledrectangle($destImage, 0, 0, $width_to, $height_to, $bg_color);
					imagecopyresampled( $destImage, $srcImage, 0, 0, $src_x, $src_y, $width_to, $height_to, $sample_image_width, $sample_image_height );

					switch($type) {
						case "1" :
							if (function_exists("imagegif")) ImageGif ( $destImage, $path );
							else return false;
							break;	/// GIF
						case "2" :
							if (function_exists("imagejpeg")) ImageJpeg( $destImage, $path );
							else return false;
							break;	/// JPG
						case "3" :
							if (function_exists("imagepng")) ImagePng( $destImage, $path );
							else return false;
							break;	/// PNG
						case "6" :
							if (function_exists("imagewbmp")) ImageWbmp( $destImage, $path );
							else return false;
							break;	/// BMP
					}
					ImageDestroy( $srcImage  );
					ImageDestroy( $destImage );
					return true;
				} else {
					return false;
				}
	}
	function ReSizeWithoutCropImage($path, $width_to, $height_to, $thumb=0) {
		if (file_exists($path) && $this->gd_used) {	//// if such image exists and gd lib is loaded
			$path_full = str_replace("thumb_","",$path);
			$path_insert = $this->site_path.$this->settings["photo_folder"]."/".$this->settings["watermark_image"];
			$image_info = GetImageSize($path);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			$image_type = $image_info[2];
//			if ($image_width > $width_to || $image_height > $height_to) {
				if ($this->settings["use_image_resize"] || $thumb) {
					$st = $this->ReSizeWithoutCropAction($path, $image_type, $image_width, $image_height, $width_to, $height_to);
					if ($this->settings["use_watermark"] && $thumb) {						
						$this->mergePix($path_full, $path_insert, $path_full, 3, 40);
					}
					if ($st) {
						return 'giffed';
					} else {
						return true;
					}
				} else {
					return true;
				}
//			} else {
//				return true;
//			}
		} else {
			return true;
		}
	}

	function ReSizeWithoutCropAction($path, $type, $image_width, $image_height, $width_to, $height_to) {
				switch($type) {
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
					$srcWidth  = ImageSX( $srcImage );
					$srcHeight = ImageSY( $srcImage );

					if ($image_width>$width_to) {
						$image_height = round($image_height*$width_to/$image_width);
						$image_width = $width_to;
					}
					if ($image_height>$height_to) {
						$image_width = round($image_width*$height_to/$image_height);
						$image_height = $height_to;
					}

					$destImage = @imagecreatetruecolor( $width_to, $height_to);

					$image_height_tmp = $image_height;
					$image_width_tmp = $image_width;
					if ($image_width<$width_to) {
						$x = round(($width_to-$image_width)/2);
					}
					if ($image_height<$height_to) {
						$y = round(($height_to-$image_height)/2);
					}

						$r = $g = $b = 255;//white borders
					$color = ImageColorAllocate($destImage, $r, $g, $b);
					imagefilledrectangle($destImage, 0,0,$width_to, $height_to,$color);

					imagecopyresampled( $destImage, $srcImage, $x, $y, 0, 0, $image_width_tmp, $image_height_tmp, $srcWidth, $srcHeight );
					switch($type) {
						case "1" :
							if (function_exists("imagegif")) ImageGif ( $destImage, $path );
							else return false;
							break;	/// GIF
						case "2" :
							if (function_exists("imagejpeg")) ImageJpeg( $destImage, $path );
							else return false;
							break;	/// JPG
						case "3" :
							if (function_exists("imagepng")) ImagePng( $destImage, $path );
							else return false;
							break;	/// PNG
						case "6" :
							if (function_exists("imagewbmp")) ImageWbmp( $destImage, $path );
							else return false;
							break;	/// BMP
					}

					ImageDestroy( $srcImage  );
					ImageDestroy( $destImage );
					return true;
				} else {
					return false;
				}
	}
	function GetResizeParametrs($path, $width_to="", $height_to="", $image_width="", $image_height="") {
		//// used then gdlib not allowed on the server
		//// return new parametrs of width and height for resizing pict
		if (!$width_to) $width_to = $this->settings["thumb_max_width"];
		if (!$height_to) $height_to = $this->settings["thumb_max_height"];
		if (!$image_width || !$image_height) {
			$image_info = GetImageSize($path);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
		}
		if ($image_width>$width_to) {
			$image_height = round($image_height*$width_to/$image_width);
			$image_width = $width_to;
		}
		if ($image_height>$height_to) {
			$image_width = round($image_width*$height_to/$image_height);
			$image_height = $height_to;
		}
		$ret_arr["width"] = $image_width;
		$ret_arr["height"] = $image_height;
		return $ret_arr;
	}

	function GetTempUploadFile($file_name) {
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
			$path_to_image = $this->file_temp_path."/". $prefix . $seed . '.' . $suffix;
		} while (file_exists($path_to_image));

		return $path_to_image;
	}
	function GetNewFileName($name, $user_id) {
		$ex_arr = explode(".",$name);
		$extension = $ex_arr[count($ex_arr)-1];
		$new_file_name = $user_id."_".substr(md5(microtime().getmypid()), 0, 8).".".$extension;		
		return $new_file_name;
	}
	function GetNewDefaultFileName($file_name, $upload_type, $id_file) {
		switch($upload_type) {
			case "icon":
				$prefix = "default_".$id_file;
			break;
			case "f":
				$prefix = "default_photo";
			break;
		}
		$ex_arr = explode(".",$file_name);
		$extension = $ex_arr[count($ex_arr)-1];
		$new_file_name = $prefix.".".$extension;
		return $new_file_name;
	}
	function mergePix($sourcefile, $insertfile, $targetfile, $pos,$transition) {

		$image_info = GetImageSize($insertfile);
		$image_type = $image_info[2];		
		switch($image_type) {
			case "1" :
			$insertfile_id = @ImageCreateFromGif ($insertfile);
			break;	/// GIF
			case "2" :
			$insertfile_id = @imagecreatefromjpeg($insertfile);
			break;	/// JPG
			case "3" :
			$insertfile_id = @imagecreatefrompng($insertfile);
			break;	/// PNG
			case "6" :
			$insertfile_id = @imagecreatefromwbmp($insertfile);
			break;	/// BMP
		}

		$image_info = GetImageSize($sourcefile);
		$image_type = $image_info[2];

		switch($image_type) {
			case "1" :
			$sourcefile_id = @ImageCreateFromGif ($sourcefile);
			break;	/// GIF
			case "2" :
			$sourcefile_id = @imagecreatefromjpeg($sourcefile);
			break;	/// JPG
			case "3" :
			$sourcefile_id = @imagecreatefrompng($sourcefile);
			break;	/// PNG
			case "6" :
			$sourcefile_id = @imagecreatefromwbmp($sourcefile);
			break;	/// BMP
		}


		//Get the sizes of both pix
   		$sourcefile_width=imageSX($sourcefile_id);
   		$sourcefile_height=imageSY($sourcefile_id);
   		$insertfile_width=imageSX($insertfile_id);
   		$insertfile_height=imageSY($insertfile_id);

		//middle
   		if ( $pos == 0 ) {
   			$dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
   			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
   		}
   		//top left
		if ( $pos == 1 ) {
			$dest_x = 0;
			$dest_y = 0;
		}
		//top right
		if ( $pos == 2 ) {
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = 0;
		}
		//bottom right
		if ( $pos == 3 ) {
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = $sourcefile_height - $insertfile_height;
		}
		//bottom left
		if ( $pos == 4 ) {
			$dest_x = 0;
			$dest_y = $sourcefile_height - $insertfile_height;
		}
		//top middle
		if ( $pos == 5 ) {
			$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			$dest_y = 0;
		}
		//middle right
		if ( $pos == 6 ) {
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
		}
		//bottom middle
		if ( $pos == 7 ) {
			$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			$dest_y = $sourcefile_height - $insertfile_height;
		}
		//middle left
		if ( $pos == 8 ) {
			$dest_x = 0;
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
		}
		//The main thing : merge the two pix
		imageCopyMerge($sourcefile_id, $insertfile_id,$dest_x,$dest_y,0,0,$insertfile_width,$insertfile_height,$transition);
		

		switch($image_type) {
			case "1" :
				if (function_exists("imagegif")) ImageGif ( $sourcefile_id, $targetfile);
				else return false;
				break;	/// GIF
			case "2" :
				if (function_exists("imagejpeg")) ImageJpeg( $sourcefile_id, $targetfile);
				else return false;
				break;	/// JPG
			case "3" :
				if (function_exists("imagepng")) ImagePng( $sourcefile_id, $targetfile);
				else return false;
				break;	/// PNG
			case "6" :
				if (function_exists("imagewbmp")) ImageWbmp( $sourcefile_id, $targetfile);
				else return false;
				break;	/// BMP
		}

		ImageDestroy($sourcefile_id);
		ImageDestroy($insertfile_id);

   }
   	function UploadSuccessImages($upload, $id_story, $num) {
		$folder = $this->settings["success_folder"];

		$max_width = $this->settings["photo_max_width"];
		$max_height = $this->settings["photo_max_height"];
		$max_size = $this->settings["photo_max_size"];
		$err_type = $this->lang["err"]["invalid_photo_type"];
		$err_size = $this->lang["err"]["invalid_photo_size"];
		$err_width = $this->lang["err"]["invalid_photo_width"];
		$err_height = $this->lang["err"]["invalid_photo_height"];

		if (!is_uploaded_file($upload["tmp_name"])) {
			$err = $this->lang["err"]["upload_err"];
			return $err;
		}

		if ($this->safe_mode_used) {
			$new_temp_path = $this->GetTempUploadFile($upload["name"]);
			if (move_uploaded_file($upload["tmp_name"],$new_temp_path)) {
				$upload["tmp_name"] = $new_temp_path;
			}
		}

		///// if we using picture resize: traing to resize picture
		if ($this->settings["use_image_resize"]) {
			$resize_res =  $this->ReSizeImage($upload["tmp_name"], $max_width, $max_height);
			$upload["size"] = filesize($upload["tmp_name"]);
		}
		// get width/height and size info and check on errors
		$upload_info = GetImageSize($upload["tmp_name"]);
		if ($upload_info[0] > $max_width) {
			if (!$err)
				$err=$this->lang["err"]["upload_err"].": <br>";
			else
				$err .= "<br>";
			$err .= $err_width;
		}
		if ($upload_info[1] > $max_height) {
			if (!$err)
				$err=$this->lang["err"]["upload_err"].": <br>";
			else
				$err .= "<br>";
			$err .= $err_height;
		}
		$filename_arr = explode(".", $upload["name"]);
		$nr = count($filename_arr);
		$ext = strtolower($filename_arr[$nr-1]);
		if ( (!in_array($upload["type"], $this->IMG_TYPE_ARRAY)) || (!in_array($ext, $this->IMG_EXT_ARRAY)) ) {
			if (!$err)
				$err=$this->lang["err"]["upload_err"].": <br>";
			else
				$err .= "<br>";
			$err .= $err_type;
		}
		if ($upload["size"] > $max_size) {
			if (!$err)
				$err=$this->lang["err"]["upload_err"].": <br>";
			else
				$err .= "<br>";
			$err .= $err_size;
		}
		////  return errrors if it was found
		if (!$err) {
			//// rename file
			$new_file_name = $this->GetNewFileName($upload["name"], $id_story);
			//// get dist path for image
			$upload_path =$this->site_path.$folder."/".$new_file_name;

			if (copy($upload["tmp_name"], $upload_path)) {
				///create thumb if gd used
				if ($this->gd_used) {
					$thumb_upload_path =$this->site_path.$folder."/thumb_".$new_file_name;
					copy($upload["tmp_name"], $thumb_upload_path);
					if (file_exists($thumb_upload_path)) $resize_res =  $this->ReSizeImage($thumb_upload_path, $this->settings["thumb_max_width"], $this->settings["thumb_max_height"], 1);
				}
				unlink($upload["tmp_name"]);
				$this->dbconn->Execute("Update ".SUCCESS_STORIES_TABLE." set  image_path_".$num."='".$new_file_name."' where id='".$id_story."'");

			} else {
				$err = $this->lang["err"]["upload_err"];
			}
		}
		return $err;
	}
	
	function CreateWatermarkImage($text, $font_size, $font_face) {
		
		if (($font_size == "") || ($font_face == ".ttf")) {
			return "not_font";
		}
		$fontsize = $font_size;
		$font = realpath("../include/fonts/".$font_face);
		
		$folder = $this->settings["photo_folder"];	
	
		// Create the image
		$size = imagettfbbox($fontsize, 0, $font, $text);
		$width = $size[2] + $size[0] + 8;
		$height = abs($size[1]) + abs($size[7]);
	
		$im = imagecreate($width, $height);
		$colourBlack = imagecolorallocate($im, 255, 255, 255);
	
		imagecolortransparent($im, $colourBlack);
	
		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
	
		// Add the text
		imagefttext($im, $fontsize, 0, 0, abs($size[5]), $black, $font, $text);	
		$new_file_name=$this->GetNewFileName($this->settings["watermark_image"], "watermark");
		copy($this->site_path.$folder."/".$this->settings["watermark_image"], $this->site_path.$folder."/".$new_file_name); 	
		if (imagepng($im,$this->site_path.$folder."/".$new_file_name)) {
			$strSQL = "UPDATE ".$this->settings_table." SET  value='".$new_file_name."' WHERE name='watermark_image'";
			unlink($this->site_path.$folder."/".$this->settings["watermark_image"]);			
			$this->dbconn->Execute($strSQL);
			
		}
		imagedestroy($im);
		return;
		}
		
	function CopyImages($source, $destination) {
		
		$image_info = GetImageSize($source);
		$image_type = $image_info[2];		
		switch($image_type) {
			case "1" :
			$source_id = @ImageCreateFromGif ($source);
			
			break;	/// GIF
			case "2" :
			$source_id = @imagecreatefromjpeg($source);
			
			break;	/// JPG
			case "3" :
			$source_id = @imagecreatefrompng($source);
			
			break;	/// PNG
			case "6" :
			$source_id = @imagecreatefromwbmp($source);
			
			break;	/// BMP
		}	
		$image_info = GetImageSize($destination);
		$image_type = $image_info[2];			
		switch($image_type) {
			case "1" :
			
			if (function_exists("imagegif")) ImageGif ( $source_id, $destination);
				else return false;
			break;	/// GIF
			case "2" :
			
			if (function_exists("imagejpeg")) ImageJpeg( $source_id, $destination);
				else return false;
			break;	/// JPG
			case "3" :
			
			if (function_exists("imagepng")) ImagePng( $source_id, $destination);
				else return false;
			break;	/// PNG
			case "6" :
			
			if (function_exists("imagewbmp")) ImageWbmp( $source_id, $destination);
				else return false;
			break;	/// BMP
		}	
		ImageDestroy($source_id);		
	}
	
}

?>