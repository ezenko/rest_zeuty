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

include "../include/class.info_manager.php";
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

$file_name = (isset($_SERVER["PHP_SELF"])) ? AfterLastSlash($_SERVER["PHP_SELF"]): "admin_info_manager.php";
$file_virt_name = $config["server"].$config["site_root"]."/admin/".$file_name;
$smarty->assign("file_name", $file_name);
$smarty->assign("file_virt_name", $file_virt_name);

IndexAdminPage('admin_info_manager');
CreateMenu('admin_lang_menu');

$info_manager = new InfoManager();

$sel = isset($_REQUEST["sel"]) ? $_REQUEST["sel"] : "main";

$errors = array();

switch ( $sel ) {
	/**
	* Main page
	**/
	case "main": {

		if ( isset($_REQUEST["delete"]) ) {
			$info_manager->DeleteSection($_REQUEST["delete"]);
		}
		if ( isset($_REQUEST["section_move_up"]) ) {
			$info_manager->UpSection($_REQUEST["section_move_up"]);
		}
		if ( isset($_REQUEST["section_move_down"]) ) {
			$info_manager->DownSection($_REQUEST["section_move_down"]);
		}
		if ( isset($_REQUEST["copy"]) ) {
			$info_manager->CopySection($_REQUEST["copy"]);
			$errors[] = "info_successfull_copy";
		}
		if (isset($_REQUEST["section_menu_move"]) && isset($_REQUEST["menu_position"])) {
			$info_manager->MenuMoveSection($_REQUEST["section_menu_move"], $_REQUEST["menu_position"]);
		}

		if ( isset($_REQUEST["subsection_move_up"]) ) {
			$info_manager->UpSubsection($_REQUEST["subsection_move_up"], $_REQUEST["section_id"]);
			header("Location: $file_virt_name?sel=subsection_list&id=".$_REQUEST["section_id"]);
			exit();
		}
		if ( isset($_REQUEST["subsection_move_down"]) ) {
			$info_manager->DownSubsection($_REQUEST["subsection_move_down"], $_REQUEST["section_id"]);
			header("Location: $file_virt_name?sel=subsection_list&id=".$_REQUEST["section_id"]);
			exit();
		}
		if ( isset($_REQUEST["subsection_delete"]) ) {
			$info_manager->DeleteSubsection($_REQUEST["subsection_delete"], $_REQUEST["section_id"]);
			header("Location: $file_virt_name?sel=subsection_list&id=".$_REQUEST["section_id"]);
			exit();
		}

		$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? $_REQUEST["language_id"] : $config["default_lang"];
		$langs = GetActiveLanguages();
		$smarty->assign( "langs", $langs );
		$smarty->assign( "langs_cnt", count($langs) );

		$smarty->assign( "current_lang_id", $current_lang_id );

		$smarty->assign("top_sections", $info_manager->GetSectionsList( $current_lang_id, "top" ));
		$smarty->assign("bottom_sections", $info_manager->GetSectionsList( $current_lang_id, "bottom" ));
	}
	break;
	/**
	 * Add section page
	 */
	case "add_section": {

		$caption = "";
		$content = "";
		$description = "";
		$keywords = "";
		$status = 1;

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

			$description = $_REQUEST["description"];
			$keywords = $_REQUEST["keywords"];
			$status = (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) ? intval($_REQUEST["status"]) : 0;
			$language_id = $_REQUEST["language_id"];
			$menu_position = $_REQUEST["menu_position"];

			if (count($errors) == 0) {
				$info_manager->AddSection($language_id, $caption, $content, $description, $keywords, $status, $menu_position);
				header("Location: $file_virt_name?language_id=$language_id");
				exit();
			}
		}
		$smarty->assign("caption", $caption);
		$smarty->assign("content", $content);
		$smarty->assign("description", $description);
		$smarty->assign("keywords", $keywords);
		$smarty->assign("status", $status);

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

		AddForm("add_section_form", $file_name, $hiddens);
	}
	break;

	/**
	 * Edit section page
	 */
	case "edit_section": {
		if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {

			$id = intval($_REQUEST["id"]);

			$section = $info_manager->GetSection( $id );
			$caption = $section["caption"];
			$content = $section["content"];
			$description = $section["description"];
			$keywords = $section["keywords"];
			$status = $section["status"];

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

				$description = $_REQUEST["description"];
				$keywords = $_REQUEST["keywords"];
				$status = (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) ? intval($_REQUEST["status"]) : 0;

				if (count($errors) == 0) {
					$info_manager->EditSection($id, $caption, $content, $description, $keywords, $status);
					header("Location: $file_virt_name?language_id=".$_REQUEST["language_id"]);
					exit();
				}
			}
			$smarty->assign("caption", $caption);
			$smarty->assign("content", $content);
			$smarty->assign("description", $description);
			$smarty->assign("keywords", $keywords);
			$smarty->assign("status", $status);

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

	/**
	 * Get list of subsections for the section
	 */
	case "subsection_list": {
		if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {
			$section_id = intval($_REQUEST["id"]);

			$smarty->assign("section", $info_manager->GetSection($section_id));
			$smarty->assign("subsections", $info_manager->GetSubsectionsList($section_id));

			$section = $info_manager->GetSection( $section_id );

			$smarty->assign("section_id", $section_id);
			$smarty->assign("current_lang_id", $section["language_id"]);
		}
	}
	break;

	/**
	 * Add subsection page
	 */
	case "add_subsection": {

		$caption = "";
		$content = "";
		$description = "";
		$keywords = "";
		$status = 1;

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

			$description = (isset($_REQUEST["description"]) && !empty($_REQUEST["description"])) ? $_REQUEST["description"] : "";
			$keywords = (isset($_REQUEST["keywords"]) && !empty($_REQUEST["keywords"])) ? $_REQUEST["keywords"] : "";
			$status = (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) ? intval($_REQUEST["status"]) : 0;
			$section_id = $_REQUEST["section_id"];

			if (count($errors) == 0) {
				$info_manager->AddSubsection($section_id, $caption, $content, $description, $keywords, $status);
				header("Location: $file_virt_name?sel=subsection_list&id=$section_id");
				exit();
			}
		}
		$smarty->assign("caption", $caption);
		$smarty->assign("content", $content);
		$smarty->assign("description", $description);
		$smarty->assign("keywords", $keywords);
		$smarty->assign("status", $status);

		$smarty->assign( "section_id", $_REQUEST["section_id"]);

		$smarty->assign( "tinymce", $tinymce );

		$hiddens[] = array( "name" => "sel",
							"value" => "add_subsection" );
		$hiddens[] = array( "name" => "save",
							"value" => "1" );
		$hiddens[] = array( "name" => "section_id",
							"value" => $_REQUEST["section_id"] );
		AddForm("add_subsection_form", $file_name, $hiddens);
	}
	break;

	/**
	 * Edit subsection page
	 */
	case "edit_subsection": {
		if (isset($_REQUEST["id"]) && !empty($_REQUEST["id"])) {

			$id = intval($_REQUEST["id"]);

			$subsection = $info_manager->GetSubsection( $id );
			$caption = $subsection["caption"];
			$content = $subsection["content"];
			$description = $subsection["description"];
			$keywords = $subsection["keywords"];
			$status = $subsection["status"];

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

				$description = (isset($_REQUEST["description"]) && !empty($_REQUEST["description"])) ? $_REQUEST["description"] : "";
				$keywords = (isset($_REQUEST["keywords"]) && !empty($_REQUEST["keywords"])) ? $_REQUEST["keywords"] : "";
				$status = (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) ? intval($_REQUEST["status"]) : 0;

				if (count($errors) == 0) {
					$info_manager->EditSubsection($id, $caption, $content, $description, $keywords, $status);
					header("Location: $file_virt_name?sel=subsection_list&id=".$_REQUEST["section_id"]);
					exit();
				}
			}
			$smarty->assign("caption", $caption);
			$smarty->assign("content", $content);
			$smarty->assign("description", $description);
			$smarty->assign("keywords", $keywords);
			$smarty->assign("status", $status);

			$smarty->assign( "section_id", $_REQUEST["section_id"]);

			$smarty->assign( "tinymce", $tinymce );

			$hiddens[] = array( "name" => "sel",
								"value" => "edit_subsection" );
			$hiddens[] = array( "name" => "save",
								"value" => "1" );
			$hiddens[] = array( "name" => "id",
								"value" => $id );
			$hiddens[] = array( "name" => "section_id",
								"value" => $_REQUEST["section_id"] );
			AddForm("edit_subsection_form", $file_name, $hiddens);

		} else {
			header("Location: ".$file_virt_name);
			exit();
		}
	}
	break;

	/**
	 * Change info subsections activity status
	 */
	case "change_subsections_status": {
		if (isset($_REQUEST["section_id"]) && !empty($_REQUEST["section_id"])) {
			$section_id = $_REQUEST["section_id"];

			$info_manager->ChangeSubsectionStatus($_REQUEST["status"], $section_id);

			header("Location: {$file_virt_name}?sel=subsection_list&id=".$section_id);
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
$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_info_manager.tpl");

?>