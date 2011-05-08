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
include "../include/class.frontpage_manager.php";
include_once "../include/class.fields_validator.php";
include_once "../tinymce/tinymce.php";

$auth = auth_user();

if( (!($auth[0]>0)) || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

/*if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
} */

$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]): "admin_frontpage.php";
$file_virt_name = $config["server"].$config["site_root"]."/admin/".$file_name;
$smarty->assign("file_name", $file_name);
$smarty->assign("file_virt_name", $file_virt_name);

IndexAdminPage('admin_frontpage');
CreateMenu('admin_lang_menu');

$info_manager = new FrontpageManager();

$sel = isset($_REQUEST["sel"]) ? $_REQUEST["sel"] : "main";

$errors = array();
$multi_lang = new MultiLang($config, $dbconn);

switch ( $sel ) {
	/**
	* Main page
	**/
	case "main": {

		if ( isset($_REQUEST["delete"]) ) {
			$info_manager->DeleteFrontpage($_REQUEST["delete"]);
		}
		if ( isset($_REQUEST["section_move_up"]) ) {
			$info_manager->UpFrontpage($_REQUEST["section_move_up"]);
		}
		if ( isset($_REQUEST["section_move_down"]) ) {
			$info_manager->DownFrontpage($_REQUEST["section_move_down"]);
		}
		
		$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? $_REQUEST["language_id"] : $config["default_lang"];
		$langs = GetActiveLanguages();
    $smarty->assign( "langs", $langs );
		$smarty->assign( "langs_cnt", count($langs) );

		$smarty->assign( "current_lang_id", $current_lang_id );
		$smarty->assign("frontpage", $info_manager->GetFrontpageList( $current_lang_id));
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
      
      $link_field = new validator_text_field( "link" );
			$link = $link_field->field_value;
			if ( $link_field->incorrect_length_range( 5, 500 ) ) {
				$errors[] = "info_new_link_range";
			}

      if(count($_FILES) > 0) {
          $im = $_FILES['image'];
          if($im['name']) {
              $target_path = "/uploades/frontpage/";
              $fileName = time() . '_'. basename( $im['name']);
              $target_path = $target_path . $fileName; 
  
              if(!move_uploaded_file($im['tmp_name'], $config['site_path'].$target_path)) {
                  $errors[] = "frontpage_image";
              }
              $image = $fileName;
          }
      }
            
	    $language_id = $_REQUEST["language_id"];

			if (count($errors) == 0) {
				$info_manager->AddFrontpage($language_id, $caption, $content, $link, $image);
				header("Location: $file_virt_name?language_id=$language_id");
				exit();
			}
		}

		$smarty->assign("caption", $caption);
		$smarty->assign("content", $content);
    $smarty->assign("link", $link);
		$smarty->assign("image", $image);

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

		AddForm("add_frontpage_form", $file_name, $hiddens);
	}
	break;

	/**
	 * Edit section page
	 */
	case "edit_section": {
		if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {

			$id = intval($_REQUEST["id"]);

			$section = $info_manager->GetFrontpage( $id );
            
			$caption = $section["caption"];
			$content = $section["content"];
  		$image = $section["image"];
      $link = $section["link"];
      
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
        
        $link_field = new validator_text_field( "link" );
  			$link = $link_field->field_value;
  			if ( $link_field->incorrect_length_range( 5, 500 ) ) {
  				$errors[] = "info_new_link_range";
  			}

        $im = $_FILES['image'];
        if($im['name']) {
            $target_path = "/uploades/frontpage/";
            $fileName = time() . '_'. basename( $im['name']);
            $target_path = $target_path . $fileName; 

            if(!move_uploaded_file($im['tmp_name'], $config['site_path'].$target_path)) {
                $errors[] = "frontpage_image";
            }
            $image = $fileName;
        }
  
				if (count($errors) == 0) {
					$info_manager->EditFrontpage($id, $caption, $content, $link, $image);
					header("Location: $file_virt_name?language_id=".$_REQUEST["language_id"]);
					exit();
				}
			}
		
      $smarty->assign("caption", $caption);
    	$smarty->assign("content", $content);
    	$smarty->assign("image", $image);
    	$smarty->assign("link", $link);            
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
			AddForm("edit_frontpage_form", $file_name, $hiddens);

		} else {
			header("Location: ".$file_virt_name);
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
$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_frontpage.tpl");

?>