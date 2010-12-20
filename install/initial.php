<?php
	define("_D_S_", DIRECTORY_SEPARATOR);
	define('NAVY_FILE_SIZE', 1992);//necessary for check uploaded in binary mode

	if (substr(php_uname(), 0, 7) == "Windows") {
			$install["system"] = "win";
	} else {
			$install["system"] = "unix";
	}
	$install["existing_files"]= array(
		1=> "adodb"._D_S_."adodb.inc.php",
		2=> "smarty"._D_S_."Smarty.class.php",
		3=> "templates_c"
	);
	$install["permission_files"] = array(
		"lang"._D_S_,
		"templates"._D_S_,
		"templates"._D_S_."default_theme"._D_S_."images"._D_S_,
		"templates"._D_S_."default_theme"._D_S_."images"._D_S_."english"._D_S_,
		"templates"._D_S_."default_theme"._D_S_."images"._D_S_."russian"._D_S_,
		"templates_c"._D_S_,
		"uploades"._D_S_,
		"uploades"._D_S_."editor"._D_S_,
		"uploades"._D_S_."featured"._D_S_,
		"uploades"._D_S_."photo"._D_S_,
		"uploades"._D_S_."slideshow"._D_S_,
		"uploades"._D_S_."video"._D_S_,
		"include"._D_S_."config.php",
		"include"._D_S_."badwords.txt",
		"install"._D_S_."install_log.txt",
		"install"._D_S_."bases"._D_S_,
		"install"._D_S_."bases"._D_S_."realestate_base_temp.sql"
	);

	$install["permission_lang_files"] = array(
		1=> "lang"._D_S_."english"._D_S_,
		2=> "lang"._D_S_."russian"._D_S_
	);

	$install["install_path"] = substr(__FILE__,0, -19);

	if(strlen($install["install_path"]) < 1){
		$install["install_path"] = $def_path;
	}

	$default_lang = array(
		1 => 1,
		2 => 2
	);
?>