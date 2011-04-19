<?
/**
* Info pages manager
*
* @package RealEstate
* @subpackage
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 11:50:23 $
**/

include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";

include "../include/class.lang.php";
include "../include/class.entertaiment_manager.php";
include_once "../include/class.fields_validator.php";
include_once "../tinymce/tinymce.php";

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

$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]): "admin_entertaiment.php";
$file_virt_name = $config["server"].$config["site_root"]."/admin/".$file_name;
$smarty->assign("file_name", $file_name);
$smarty->assign("file_virt_name", $file_virt_name);

IndexAdminPage('admin_entertaiment');
CreateMenu('admin_lang_menu');

$info_manager = new EntertaimentManager();

$sel = isset($_REQUEST["sel"]) ? $_REQUEST["sel"] : "main";

$errors = array();
$multi_lang = new MultiLang($config, $dbconn);

switch ( $sel ) {
	/**
	* Main page
	**/
	case "main": {

		if ( isset($_REQUEST["delete"]) ) {
			$info_manager->DeleteEntertaiment($_REQUEST["delete"]);
		}
		if ( isset($_REQUEST["section_move_up"]) ) {
			$info_manager->UpEntertaiment($_REQUEST["section_move_up"]);
		}
		if ( isset($_REQUEST["section_move_down"]) ) {
			$info_manager->DownEntertaiment($_REQUEST["section_move_down"]);
		}
		
		$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? $_REQUEST["language_id"] : $config["default_lang"];
		$langs = GetActiveLanguages();
        $smarty->assign( "langs", $langs );
		$smarty->assign( "langs_cnt", count($langs) );

		$smarty->assign( "current_lang_id", $current_lang_id );

		$smarty->assign("entertaiments", $info_manager->GetEntertaimentList( $current_lang_id));
	}
	break;
	/**
	 * Add section page
	 */
	case "add_section": {

		$caption = "";
		$content = "";
		$image = "";
		$type_id = "";
        $country_id = "";
        $region_id = "";
        $city_id = "";
        $lat = "";
        $lon = "";
        
		if (isset($_REQUEST["save"]) && $_REQUEST["save"] == 1) {
			$caption_field = new validator_text_field( "caption", 255 );
			$caption = $caption_field->field_value;
			if ( $caption_field->incorrect_length_range( 3, 255 ) ) {
				$errors[] = "info_new_caption_range";
			}

			$content_field = new validator_text_field( "content" );
			$content = $content_field->field_value;
			if ( $content_field->incorrect_length_range( 10, 50000 ) ) {
				$errors[] = "info_new_content_range";
			}

            if(count($_FILES) > 0) {
                $im = $_FILES['image'];
                if($im['name']) {
                    $target_path = "/uploades/entertaiments/";
                    $fileName = time() . '_'. basename( $im['name']);
                    $target_path = $target_path . $fileName; 
        
                    if(!move_uploaded_file($im['tmp_name'], $config['site_path'].$target_path)) {
                        $errors[] = "entertaiment_image";
                    }
                    $image = $fileName;
                }
            }
            
		    $language_id = $_REQUEST["language_id"];
			
    		$type_id = $_REQUEST["type"];
            $country_id = $_REQUEST["country"];
            $region_id = $_REQUEST["region"];
            $city_id = $_REQUEST["city"];

			if (count($errors) == 0) {
				$info_manager->AddEntertaiment($language_id, $caption, $content, $image, $type_id, $country_id, $region_id, $city_id, $lat, $lon);
				header("Location: $file_virt_name?language_id=$language_id");
				exit();
			}
		}
        
        $used_references = array("rest");
        foreach ($REFERENCES as $arr) { 
            if (in_array($arr["key"], $used_references)) {
                $smarty->assign($arr["key"], GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"]));
            }
        }
        
        $strSQL = "SELECT id, name FROM ".COUNTRY_TABLE." WHERE 1";
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
        
        if (isset($_REQUEST["save"]) && $_REQUEST["save"] == 1){

			$strSQL = "SELECT id, name FROM ".REGION_TABLE." WHERE id_country='".$country_id."' ORDER by name";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0) {
				$i = 0;
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$region[$i]["id"] = $row["id"];
					if ($config["lang_ident"]!='ru'){
						$region[$i]["name"] = RusToTranslit($row["name"]);
					} else {
						$region[$i]["name"] = $row["name"];
					}
					$rs->MoveNext();
					$i++;
				}
			}
			$smarty->assign("region", $region);

			$strSQL = "SELECT id, name FROM ".CITY_TABLE." WHERE id_region='".$region_id."' ORDER by name";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0) {
				$i = 0;
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$city[$i]["id"] = $row["id"];
					if ($config["lang_ident"]!='ru'){
						$city[$i]["name"] = RusToTranslit($row["name"]);
					} else {
						$city[$i]["name"] = $row["name"];
					}
					$rs->MoveNext();
					$i++;
				}
			}
			$smarty->assign("city", $city);
		}
		$smarty->assign("caption", $caption);
		$smarty->assign("content", $content);
		$smarty->assign("image", $image);
		$smarty->assign("country_id", $country_id);
        $smarty->assign("region_id", $region_id);
        $smarty->assign("city_id", $city_id);
        $smarty->assign("type_id", $type_id);

		$smarty->assign( "current_lang_id", $_REQUEST["language_id"]);

		$smarty->assign( "tinymce", $tinymce );

		$hiddens[] = array( "name" => "sel",
							"value" => "add_section" );
		$hiddens[] = array( "name" => "save",
							"value" => "1" );
		$hiddens[] = array( "name" => "language_id",
							"value" => $_REQUEST["language_id"] );
		$hiddens[] = array( "name" => "menu_position",
							"value" => $_REQUEST["menu_position"] );

		AddForm("add_entertaiment_form", $file_name, $hiddens);
	}
	break;

	/**
	 * Edit section page
	 */
	case "edit_section": {
		if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {

			$id = intval($_REQUEST["id"]);

			$section = $info_manager->GetEntertaiment( $id );
            
			$caption = $section["caption"];
			$content = $section["content"];
    		$image = $section["image"];
    		$type_id = $section["type_id"];
            $country_id = $section["country_id"];
            $region_id = $section["region_id"];
            $city_id = $section["city_id"];
            $lat = $section["lat"];
            $lon = $section["lon"];
            
            if((!$lat || !$lon) && $city_id)
            {
                $strSQL = "SELECT lat, lon FROM ".CITY_TABLE." WHERE id='".$city_id."'";
    			$rs = $dbconn->Execute($strSQL);
    			if ($rs->fields[0]>0) {
    				$i = 0;
    				while(!$rs->EOF) {
    					$row = $rs->GetRowAssoc(false);
    					$lat = $row['lat'];
                        $lon = $row['lon'];
    					$rs->MoveNext();
    					$i++;
    				}
    			}
            }
			if (isset($_REQUEST["save"]) && $_REQUEST["save"] == 1) {
				$caption_field = new validator_text_field( "caption", 255 );
				$caption = $caption_field->field_value;
				if ( $caption_field->incorrect_length_range( 3, 255 ) ) {
					$errors[] = "info_new_caption_range";
				}

				$content_field = new validator_text_field( "content" );
				$content = $content_field->field_value;
				if ( $content_field->incorrect_length_range( 10, 50000 ) ) {
					$errors[] = "info_new_content_range";
				}

                $im = $_FILES['image'];
                if($im['name']) {
                    $target_path = "/uploades/entertaiments/";
                    $fileName = time() . '_'. basename( $im['name']);
                    $target_path = $target_path . $fileName; 
        
                    if(!move_uploaded_file($im['tmp_name'], $config['site_path'].$target_path)) {
                        $errors[] = "entertaiment_image";
                    }
                    $image = $fileName;
                }
                
				$type_id = $_REQUEST["type"];
                $country_id = $_REQUEST["country"];
                $region_id = $_REQUEST["region"];
                $city_id = $_REQUEST["city"];
                $lat = $_REQUEST["lat"];
                $lon = $_REQUEST["lon"];
                
				if (count($errors) == 0) {
					$info_manager->EditEntertaiment($id, $caption, $content, $image, $type_id, $country_id, $region_id, $city_id, $lat, $lon);
					header("Location: $file_virt_name?language_id=".$_REQUEST["language_id"]);
					exit();
				}
			}
			$used_references = array("rest");
            foreach ($REFERENCES as $arr) { 
                if (in_array($arr["key"], $used_references)) {
                    $smarty->assign($arr["key"], GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"]));
                }
            }
        
            $strSQL = "SELECT id, name FROM ".COUNTRY_TABLE." WHERE 1";
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
        
        	$strSQL = "SELECT id, name FROM ".REGION_TABLE." WHERE id_country='".$country_id."' ORDER by name";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0) {
				$i = 0;
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$region[$i]["id"] = $row["id"];
					if ($config["lang_ident"]!='ru'){
						$region[$i]["name"] = RusToTranslit($row["name"]);
					} else {
						$region[$i]["name"] = $row["name"];
					}
					$rs->MoveNext();
					$i++;
				}
			}
			$smarty->assign("region", $region);

			$strSQL = "SELECT id, name FROM ".CITY_TABLE." WHERE id_region='".$region_id."' ORDER by name";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0) {
				$i = 0;
				while(!$rs->EOF) {
					$row = $rs->GetRowAssoc(false);
					$city[$i]["id"] = $row["id"];
					if ($config["lang_ident"]!='ru'){
						$city[$i]["name"] = RusToTranslit($row["name"]);
					} else {
						$city[$i]["name"] = $row["name"];
					}
					$rs->MoveNext();
					$i++;
				}
			}
			$smarty->assign("city", $city);
		
            $smarty->assign("caption", $caption);
    		$smarty->assign("content", $content);
    		$smarty->assign("image", $image);
    		$smarty->assign("country_id", $country_id);
            $smarty->assign("region_id", $region_id);
            $smarty->assign("city_id", $city_id);
            $smarty->assign("type_id", $type_id);
            $smarty->assign("lat", $lat);
            $smarty->assign("lon", $lon);
            
			$smarty->assign( "current_lang_id", $_REQUEST["language_id"]);

			$smarty->assign( "tinymce", $tinymce );

			$hiddens[] = array( "name" => "sel",
								"value" => "edit_section" );
			$hiddens[] = array( "name" => "save",
								"value" => "1" );
			$hiddens[] = array( "name" => "id",
								"value" => $id );
			$hiddens[] = array( "name" => "language_id",
								"value" => $_REQUEST["language_id"] );
			AddForm("edit_section_form", $file_name, $hiddens);

		} else {
			header("Location: ".$file_virt_name);
			exit();
		}
	}
	break;

	/**
	 * Change info sections activity status
	 */
	case "change_status": {
		if (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) {
			$language_id = $_REQUEST["language_id"];
			$menu_position = $_REQUEST["menu_position"];

			$info_manager->ChangeSectionStatus($_REQUEST["status"], $language_id, $menu_position);

			header("Location: {$file_virt_name}?language_id=".$language_id);
			exit();

		} else {
			header("Location: $file_virt_name");
			exit();
		}
	}
	break;

	default: {

	}
	break;
}

if (count($errors) > 0 ) {
	foreach ($errors as $err_str) {
		$err[] = $lang["errors"][$err_str];
	}
	$smarty->assign("error", $err);
}

$smarty->assign("sel", $sel);
$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_entertaiment.tpl");

?>