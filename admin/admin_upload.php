<?php
/**
* Approvement for the uploaded files
*
* @package RealEstate
* @subpackage Admin area
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 12:22:39 $
**/

include "../include/config.php";
include_once "../common.php";
include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/class.images.php";
include "../include/class.phpmailer.php";
include "../include/functions_mail.php";

$auth = auth_user();

if( (!($auth[0]>0)) || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

$sel = (isset($_REQUEST["sel"])) ? $_REQUEST["sel"] : "list_upload";
$type_upload = (isset($_REQUEST["type_upload"])) ? $_REQUEST["type_upload"] : "rent_photo";

switch($sel){
	case "list_upload": ListUpload($type_upload); break;
	case "save_status": SaveStatus($type_upload); break;
	default: ListUpload($type_upload); break;
}

function ListUpload($type_upload=""){
	global $smarty, $dbconn, $config;

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_upload.php";

	IndexAdminPage('admin_upload');
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;	

	/**
	 * Get not approved files count fot each type
	 */
	$strSQL = "SELECT COUNT(id) FROM ".USERS_RENT_UPLOADS_TABLE." ".
			  "WHERE admin_approve='0' AND upload_type='f'";
	$rs = $dbconn->Execute($strSQL);
	$data["rent_photo_count"] = $rs->fields[0];

	$strSQL = "SELECT COUNT(id) FROM ".USER_RENT_PLAN_TABLE." ".
			  "WHERE admin_approve='0'";
	$rs = $dbconn->Execute($strSQL);
	$data["rent_plan_count"] = $rs->fields[0];

	$strSQL = "SELECT COUNT(id) FROM ".USERS_RENT_UPLOADS_TABLE." ".
			  "WHERE admin_approve='0' AND upload_type='v'";
	$rs = $dbconn->Execute($strSQL);
	$data["rent_video_count"] = $rs->fields[0];

	$strSQL = "SELECT COUNT(id_user) FROM ".USER_REG_DATA_TABLE." ".
			  "WHERE logo_path<>'' AND admin_approve='0'";
	$rs = $dbconn->Execute($strSQL);
	$data["user_photo_count"] = $rs->fields[0];
	$strSQL = "SELECT COUNT(id) FROM ".USER_PHOTOS_TABLE." ".
			  "WHERE photo_path<>'' AND approve='0'";
	$rs = $dbconn->Execute($strSQL);
	$data["user_photo_count"] = $data["user_photo_count"] + $rs->fields[0];	
	/**
	 * Get files for the selected $type_upload
	 */
	$settings["admin_upload_numpage"] = GetSiteSettings("admin_upload_numpage");
	$lim_min = ($page-1)*$settings["admin_upload_numpage"];
	$lim_max = $settings["admin_upload_numpage"];
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	switch ($type_upload) {
		case "rent_photo": {
			$folder = GetSiteSettings("photo_folder");
			$icon = "thumb_";
			$num_records = $data["rent_photo_count"];
			$strSQL = "SELECT id, id_user, id_ad, upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." ".
					  "WHERE admin_approve='0' AND upload_type='f'";
			$view_link = "./admin_users.php?sel=upload_view&category=rental&type_upload=f&id_file=";
		}
		break;
		case "rent_plan": {
			$folder = GetSiteSettings("photo_folder");
			$icon = "thumb_";
			$num_records = $data["rent_plan_count"];
			$strSQL = "SELECT id, id_user, id_ad, upload_path, user_comment FROM ".USER_RENT_PLAN_TABLE." ".
					  "WHERE admin_approve='0'";
			$view_link = "./admin_users.php?sel=plan_view&type_upload=f&id_file=";
		}
		break;
		case "rent_video": {
			$folder = GetSiteSettings("video_folder");
			$icon = GetSiteSettings("default_video_icon");
			$num_records = $data["rent_video_count"];
			$strSQL = "SELECT id, id_user, id_ad, upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." ".
					  "WHERE admin_approve='0' AND upload_type='v'";
			$is_flv = GetSiteSettings("use_ffmpeg");				  
			$view_link = "./admin_users.php?sel=upload_view&category=rental&type_upload=v&id_file=";
		}
		break;
		case "user_photo": {
			$folder = GetSiteSettings("photo_folder");
			$icon = "";
			$num_records = $data["user_photo_count"];
			$strSQL = "SELECT 1 AS uid,a.id_user AS id, a.logo_path AS upload_path, a.company_name AS user_comment, b.fname, b.sname FROM ".USER_REG_DATA_TABLE." a ".
					  "LEFT JOIN ".USERS_TABLE." b ON a.id_user=b.id ".
					  "WHERE (a.admin_approve='0' AND a.logo_path <> '') 
					   UNION ALL 
					   SELECT 2 AS uid,uf.id_user AS id, uf.photo_path AS upload_path, a.company_name AS user_comment, b.fname, b.sname
					   FROM ".USER_PHOTOS_TABLE. " uf
					   LEFT JOIN ".USER_REG_DATA_TABLE." a ON a.id_user = uf.id_user 
					   LEFT JOIN ".USERS_TABLE." b ON b.id = uf.id_user 
					   WHERE uf.approve = '0'";
					  
			$view_link = "./admin_users.php?sel=logo_view&category=rental&type_upload=f&id_file=";
		}
		break;
		default: break;
	}	
	$rs = $dbconn->Execute($strSQL.$limit_str);
	$files = array();
	while (!$rs->EOF) {
		$file = $rs->GetRowAssoc( false );		
		//sizes
		if ($type_upload != "rent_video") {
			$sizes = getimagesize($config["site_path"].$folder."/".$file["upload_path"]);
			$file["width"]  = $sizes[0];
			$file["height"]  = $sizes[1];
		} else {
			$file["width"]  = 400;
			$file["height"]  = 300;
		}
		
		if (($type_upload == "rent_video") && $is_flv) {			
				$flv_name = explode('.', $file["upload_path"]);				
				if (file_exists($config["site_path"].GetSiteSettings("video_folder")."/".$flv_name[0].".flv")) {
					$file["is_flv"] = 1;					
					$file["icon"] = $flv_name[0]."1.jpg";
					$file["upload_path"] = $flv_name[0].".flv";
					$size = explode('x', GetSiteSettings("flv_output_dimension"));
					$file["width"] = $size[0];			
					$file["height"] = $size[1];
				} else {					
					$file["icon"]  = GetSiteSettings("default_video_icon");
					$file["upload_path"] = $file["upload_path"];
					$file["is_flv"] = 0;
				}
			} elseif ($type_upload == "rent_video") {				
				$file["upload_path"] = $file["upload_path"];
				$file["icon"] = GetSiteSettings("default_video_icon");
				$file["is_flv"] = 0;
			} else {
				$file["icon"] = $icon.$file["upload_path"];
			}
		if 	($type_upload == "rent_video") {
			$file["view_link"] = "./admin_users.php?sel=upload_view&category=rental&type_upload=v&is_flv=".$file["is_flv"]."&id_file=";
		}
		if ($type_upload != "user_photo") {
			$file["relate_link"] = "./admin_users.php?sel=user_rent&id_user=".$file["id_user"]."&id_ad=".$file["id_ad"];			
		} else {
			$file["relate_link"] = "./admin_users.php?sel=edit_user&id_user=".$file["id"];
		}
		$files[] = $file;
		$rs->MoveNext();
	}
	$smarty->assign("files", $files);
	$smarty->assign("page", $page);

	$param = $file_name."?sel=list_upload&type_upload=".$type_upload."&";
	$smarty->assign("links", GetLinkArray($num_records, $page, $param, $settings["admin_upload_numpage"]) );

	$counter_start = ($page == 1) ? 0 : $settings["admin_upload_numpage"]*($page-1);
	$smarty->assign("counter_start", $counter_start );
	$smarty->assign("view_link", $view_link);
	$smarty->assign("folder", $folder);
	$smarty->assign("type_upload", $type_upload);
	$smarty->assign("data", $data);

	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_upload.tpl");
}

function SaveStatus($type_upload) {
	global $smarty, $dbconn, $config;

	if (isset($_REQUEST["approve"]) && count($_REQUEST["approve"]) > 0) {
		$approve_ids = implode("', '", $_REQUEST["approve"]);

		ChangeFilesStatus($type_upload, 1, $approve_ids);
	}
	if (isset($_REQUEST["decline"]) && count($_REQUEST["decline"]) > 0) {
		$decline_ids = implode("', '", $_REQUEST["decline"]);

		ChangeFilesStatus($type_upload, 2, $decline_ids);
	}
	ListUpload($type_upload);
	exit();
}

function ChangeFilesStatus($type_upload, $status, $file_ids) {
	global $smarty, $dbconn, $config;

	switch ($type_upload) {
		case "rent_photo": {
			$strSQL = "UPDATE ".USERS_RENT_UPLOADS_TABLE." SET admin_approve='$status' ".
					  "WHERE id IN('".$file_ids."')";
		}
		break;
		case "rent_plan": {
			$strSQL = "UPDATE ".USER_RENT_PLAN_TABLE." SET admin_approve='$status' ".
					  "WHERE id IN('".$file_ids."')";
		}
		break;
		case "rent_video": {
			$strSQL = "UPDATE ".USERS_RENT_UPLOADS_TABLE." SET admin_approve='$status' ".
					  "WHERE id IN('".$file_ids."')";
		}
		break;
		case "user_photo": {			
			
			$file_ids_arr = explode("', '",$file_ids);
			foreach ($file_ids_arr as $i => $id){
				$uid = $id[0];
				$id = substr($id,2);
				if ($uid == 1)
				$strSQL = "UPDATE ".USER_REG_DATA_TABLE." SET admin_approve='$status' ".
					  "WHERE id_user = '$id';";					 
				if ($uid == 2)
				$strSQL = "UPDATE ".USER_PHOTOS_TABLE." SET approve='$status' ".
					  "WHERE id_user = '$id';";	
				
				$rs = $dbconn->Execute($strSQL);	  
			}			
		}
		break;
		default: break;
	}
	if ($type_upload != "user_photo"){
		$rs = $dbconn->Execute($strSQL);
	}	
}

?>