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
		"install"._D_S_."countries"._D_S_."bases"._D_S_,	
		"install"._D_S_."countries"._D_S_."install_log.txt",	
	);
	
	$def_path = "../../";

	$install["install_path"] = dirname(__FILE__)."/".$def_path;
	if(strlen($install["install_path"]) < 1){
		$install["install_path"] = $def_path;
	}
?>