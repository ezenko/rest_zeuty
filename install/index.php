<?php
/*--------------------------------------------------------------------
*
*
--------------------------------------------------------------------*/
        //error_reporting  (E_ERROR | E_WARNING | E_PARSE);
        ini_set("display_errors", '0');
        error_reporting(E_ALL);
        ini_set("max_execution_time", 1000);
        include "./initial.php";
        include "./forms.php";
        include "./errors.php";
        include "../include/class.restore.php";

//////////////////////////////////////////////////////////////////////////////////////
        $sel = $_GET["sel"]?$_GET["sel"]:$_POST["sel"];
        switch($sel){
                case "1": Step_2(); break;
                case "2": database_server_Install(); break;
				case "3": Step_3(); break;
				case "4": Settings_Install(); break;
				case "5": RecoverPermission();break;
				case "6": template_last_page(); break;
				case "first": Step_1(); break;
                default:  Step_Lisence(); break;
        }
        function Step_Lisence(){
        	global $install, $errors;
        		include("license.php");
        		$ret_str = $license;
				$next_step = true;
				template_lisence_page($ret_str, $next_step);
				exit;
        }
		function Step_1(){	/// check requirements and return result table
                global $install, $errors;
				$ret_str = "";
				$next_step = true;
				$write_good = true;

				//check PHP version
				$good = phpversion() >= '4.1.2' ? 1 : 0;
				$ret_str .= permission_str("PHP version >= 4.1.2: ", $good);
				$next_step   = $next_step && $good;

				//check mySQL
				$good = function_exists( 'mysql_connect' ) ? 1 : 0;
				$ret_str .= permission_str("MySQL support exists: ", $good);
				$next_step   = $next_step && $good;

				//check for GD library
				$good = extension_loaded('gd') ? 1 : 0;
				$ret_str .= permission_str("GD library (responsible for working with graphics and images) is installed: ", $good);
				$next_step   = $next_step && $good;

				foreach($install["permission_files"] as $num=>$file){
					$good = isWriteable($install["install_path"].$file, 0777);
					$sub_ret_str .= permission_str($file.' is writable: ', $good, 1);
					$write_good   = $write_good && $good;
				}

				foreach($install["permission_lang_files"] as $num=>$file){
					$good = isWriteable($install["install_path"].$file, 0777);
					$sub_ret_str .= permission_str($file.' is writable: ', $good, 1);
					$write_good   = $write_good && $good;
					$lang_files = array();
					$lang_files = scan_dir_file($install["install_path"].$file);
					$lang_admin_files = scan_dir_file($install["install_path"].$file._D_S_."admin"._D_S_);
					$lang_menu_files = scan_dir_file($install["install_path"].$file._D_S_."menu"._D_S_);

					$lang_files_count = sizeof($lang_files) + sizeof($lang_admin_files) + sizeof($lang_menu_files);

					$counter = 0;
					$deny_files = array();
					//lang_files
					foreach ($lang_files as $file_name){
						$good = isWriteable($install["install_path"].$file.$file_name, 0777);
						if ($good == true){
							$counter++;
						} else {
							array_push($deny_files, $file_name);
						}
						$write_good   = $write_good && $good;
					}
					//admin lang_files
					foreach ($lang_admin_files as $file_name){
						$good = isWriteable($install["install_path"].$file._D_S_."admin"._D_S_.$file_name, 0777);
						if ($good == true){
							$counter++;
						} else {
							array_push($deny_files, _D_S_."admin"._D_S_.$file_name);
						}
						$write_good   = $write_good && $good;
					}
					//menu lang_files
					foreach ($lang_menu_files as $file_name){
						$good = isWriteable($install["install_path"].$file._D_S_."menu"._D_S_.$file_name, 0777);
						if ($good == true){
							$counter++;
						} else {
							array_push($deny_files, _D_S_."menu"._D_S_.$file_name);
						}
						$write_good   = $write_good && $good;
					}

					if ($counter == $lang_files_count){
						$sub_ret_str .= permission_str($counter." files in ".$file.' is writable: ', true, 1);
					} else {
						$sub_ret_str .= permission_str($counter." files in ".$file.' is writable: ', true, 1);
						$sub_ret_str .= permission_str(($lang_files_count - $counter)." files in ".$file.' is writable: ', false, 1);
						foreach ($deny_files as $file_name){
							$sub_ret_str .= permission_str($file_name.' is writable: ', false, 1);
						}
					}
				}

				$ret_str .= permission_str(' Check file permissions: ', $write_good).$sub_ret_str;
				$next_step   = $next_step && $write_good;

				$ret_str .= permission_str(' File rename permission:', $good);

				template_first_page($ret_str, $next_step);
				exit;
		}

		function Step_2(){
			global $install, $errors;
			template_database_server_form();
		}
		function database_server_Install(){
                global $install, $errors, $bases, $default_lang;
                
				$is_magic_quotes = intval(ini_get("magic_quotes_gpc"));
				if ($is_magic_quotes) {
					foreach ($_POST as $post_key=>$post_val) {
						$_POST[$post_key] = stripslashes($post_val);
					}
				}
				
                $data["dbhost"] = $_POST["dbhost"];
                $data["dbname"] = $_POST["dbname"];
                $data["dbuser"] = $_POST["dbuser"];
                $data["dbpass"] = $_POST["dbpass"];
                $data["dbprefix"] = $_POST["dbprefix"];
                $data["dblang"] = $_POST["dblang"];

                $data["server"] = $_POST["server"];
                $data["site_root"] = $_POST["site_root"];
                $data["site_path"] = $_POST["site_path"];

                ////////////////////////////////////////////
                if(!strlen($data["dbhost"])){
                        template_database_server_form($errors["not_valid_field"]." dbhost", $data);
                        insertLogData($errors["not_valid_field"]." dbhost");
                        return;
                }
                ////////////////////////////////////////////
                if(!strlen($data["dbname"])){
                        template_database_server_form($errors["not_valid_field"]." dbname", $data);
                        insertLogData($errors["not_valid_field"]." dbname");
                        return;
                }
                ////////////////////////////////////////////
                if(!strlen($data["dbuser"])){
                        template_database_server_form($errors["not_valid_field"]." dbuser", $data);
                        insertLogData($errors["not_valid_field"]." dbuser");
                        return;
                }
                if($data["site_root"]){
                        $pos = strpos($data["site_path"], $data["site_root"]);
                        if ($pos === false) {
                                template_database_server_form($errors["not_valid_site_root"], $data);
                                insertLogData($errors["not_valid_site_root"]);
                                return;
                        }
                }
                //$data["site_root"] = str_replace(DIRECTORY_SEPARATOR, "/", stripslashes($_POST["site_root"]));
                $data["site_root"]=str_replace("\\","/", $_POST["site_root"]);
				$data["site_path"]=str_replace("\\","/", $_POST["site_path"]);

                ////// try to connect to db
				$link = mysql_connect($data["dbhost"], $data["dbuser"], $data["dbpass"]);
                if(!$link){
                        template_database_server_form($errors["cant_connect_to_host"]."(".mysql_error().")", $data);
                        insertLogData($errors["cant_connect_to_host"]."(".mysql_error().")");
                        return;
                }

                ////// try to connect write a base
                if(!mysql_select_db($data["dbname"])){
                        template_database_server_form($errors["cant_select_db"]."(".mysql_error().")", $data);
                        insertLogData($errors["cant_select_db"]."(".mysql_error().")");
                        return;
                }
                $db_file = dirname(__FILE__)."/bases/realestate_base.sql";
                if(!file_exists($db_file)){
                        template_database_server_form($errors["not_valid_base_file"], $data);
                        insertLogData($errors["not_valid_base_file"]);
                        return;
                }
                $db_content = implode("\n", file($db_file));
				$db_content = str_replace("[db_prefix]", $data["dbprefix"], $db_content);
                $db_content = str_replace("[default_lang]", 1, $db_content);
                $db_content = str_replace("[script_url]", $data["server"].$data["site_root"], $db_content);
				$use_resize = extension_loaded('gd')?1:0;
                $db_content = str_replace("[use_resize]", $use_resize, $db_content);

                $db_file_temp = dirname(__FILE__)."/bases/realestate_base_temp.sql";
				$fp = fopen($db_file_temp, "w");
                if($fp){
						fwrite($fp, $db_content);
                        fclose($fp);
                }

				$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
				if (!$is_safe_mode) set_time_limit(600);

				template_db_restore("Step 2: Database & Server Configuration : Creating DB", "./index.php?sel=3");
				$restore_obj = new  BaseRestore();
				$timer = array_sum(explode(' ', microtime()));
				ob_implicit_flush();
				$err = $restore_obj->restore($data["dbhost"], $data["dbuser"], $data["dbpass"], $data["dbname"], $db_file_temp);
				print "<SCRIPT>document.getElementById('timer').innerHTML = '" . round(array_sum(explode(' ', microtime())) - $timer, 4) . " sec.'</SCRIPT>";
				if($err !=0){
					print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
				}else{
					if(file_exists($db_file_temp)) unlink($db_file_temp);
					////// write a /include/config.php
					$string = implode("", file("./config_dist"));
					$string = str_replace("[server]", $data["server"], $string);
					$string = str_replace("[site_root]", $data["site_root"], $string);
					$string = str_replace("[site_path]", $data["site_path"], $string);
					$string = str_replace("[db_host]", $data["dbhost"], $string);
					$string = str_replace("[db_user]", $data["dbuser"], $string);
					$string = str_replace("[db_pass]", $data["dbpass"], $string);
					$string = str_replace("[db_name]", $data["dbname"], $string);
					$string = str_replace("[db_prefix]", $data["dbprefix"], $string);

					$config_path = "../include/config.php";
					$fp = fopen($config_path, "w");
					if($fp){
							fputs($fp, $string);
							fclose($fp);
					}
					//// write info to the files
					print "<SCRIPT>document.getElementById('next').disabled = 0;</SCRIPT>";
				}
				return;
		}
		function Step_3(){
			global $install, $errors;
			template_misc_parametrs_form();
		}
		function Settings_Install(){
                global $install, $errors, $bases, $default_lang;

                $data["admin_name"] = $_POST["admin_name"];
                $data["admin_login"] = $_POST["admin_login"];
                $data["admin_pass"] = $_POST["admin_pass"];
                $data["admin_repass"] = $_POST["admin_repass"];
                $data["admin_email"] = $_POST["admin_email"];

                ////////////////////////////////////////////
                if(!strlen($data["admin_name"])){
                        template_misc_parametrs_form($errors["empty_admin_name"], $data);
                        insertLogData($errors["empty_admin_name"]);
                        return;
                }
                ////////////////////////////////////////////
                if($err = LoginFilter($data["admin_login"])){
                        template_misc_parametrs_form($err, $data);
                        insertLogData($err);
                        return;
                }
                ////////////////////////////////////////////
                if($err = EmailFilter($data["admin_email"])){
                        template_misc_parametrs_form($err, $data);
                        insertLogData($err);
                        return;
                }
                ////////////////////////////////////////////
                if(!strlen($data["admin_email"])){
                        template_misc_parametrs_form($errors["email_bad"], $data);
                        insertLogData($errors["email_bad"]);
                        return;
                }
                ////////////////////////////////////////////
                if($err = PasswFilter($data["admin_pass"])){
                        template_misc_parametrs_form($err, $data);
                        insertLogData($err);
                        return;
                }
                ////////////////////////////////////////////
                if($data["admin_pass"] != $data["admin_repass"]){
                        template_misc_parametrs_form($errors["pass_eq_repass"], $data);
                        insertLogData($errors["pass_eq_repass"]);
                        return;
                }
                ////////////////////////////////////////////
                if($data["admin_pass"] == $data["admin_login"]){
                        template_misc_parametrs_form($errors["pass_eq_log"], $data);
                        insertLogData($errors["pass_eq_log"]);
                        return;
                }
                $db_admin_file = dirname(__FILE__)."/bases/realestate_update_admin.sql";
                if(!file_exists($db_admin_file)){
                        template_database_server_form($errors["not_valid_base_file"], $data);
                        insertLogData($errors["not_valid_base_file"]." ". $db_file);
                        return;
                }
                /*$db_users_file = dirname(__FILE__)."/bases/realestate_update_users.sql";
                if(!file_exists($db_users_file)){
                        template_database_server_form($errors["not_valid_base_file"], $data);
                        insertLogData($errors["not_valid_base_file"]." ". $db_file);
                        return;
                }*/
				include "../include/config.php";

                $db_content = implode("\n", file($db_admin_file));
                $db_content = str_replace("[db_prefix]", $config["table_prefix"], $db_content);
                $db_content = str_replace("[admin_email]", $data["admin_email"], $db_content);
                $db_content = str_replace("[admin_name]", $data["admin_name"], $db_content);
                $db_content = str_replace("[admin_login]", $data["admin_login"], $db_content);
                $db_content = str_replace("[admin_passw]", md5($data["admin_pass"]), $db_content);
				$db_content = str_replace("[script_url]", $config["server"].$config["site_root"], $db_content);

				$db_users_content = "\n".implode("\n", file($db_users_file));
                $db_users_content = str_replace("[db_prefix]", $config["table_prefix"], $db_users_content);

                $db_file_temp = dirname(__FILE__)."/bases/realestate_base_temp.sql";
				$fp = fopen($db_file_temp, "w");
                if($fp){
						fwrite($fp, $db_content);
                        fclose($fp);
                }
				$fp = fopen($db_file_temp, "a+");
                if($fp){
						fwrite($fp, $db_users_content);
                        fclose($fp);
                }

				$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
				if (!$is_safe_mode) set_time_limit(600);

				template_db_restore("Step 2: Site Settings : Updating DB", "./index.php?sel=5");
				$restore_obj = new  BaseRestore();
				$timer = array_sum(explode(' ', microtime()));
				ob_implicit_flush();
				$err = $restore_obj->restore($config["dbhost"], $config["dbuname"], $config["dbpass"], $config["dbname"], $db_file_temp);
				print "<SCRIPT>document.getElementById('timer').innerHTML = '" . round(array_sum(explode(' ', microtime())) - $timer, 4) . " sec.'</SCRIPT>";
				if($err !=0){
					print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
				}else{
					print "<SCRIPT>document.getElementById('next').disabled = 0;</SCRIPT>";
				}
				return;
		}

///////////////////////////////////////////////////////////////////////////////////////////
function LoginFilter($str){
        global $errors;
        $err = "";
        if(strlen($str)<5 || strlen($str)>20){
                $err = $errors["login_length"];
        }
        if(!eregi("^[0-9a-z_\sA-Z]*$", $str)){
                $err = $errors["login_cont"];
        }
        return $err;
}
///////////////////////////////////////////////////////////////////////////////////////////
function EmailFilter($str){
        global $errors;
        $err = "";
        if(strlen($str)>0)
                if(!eregi("^.+@.+\\..+$", $str)){
                        $err = $errors["email_bad"];
                }
        return $err;
}
///////////////////////////////////////////////////////////////////////////////////////////
function PasswFilter($str){
        global $errors;
        $err = "";
        if(strlen($str)<6 || strlen($str)>20){
                $err = $errors["pass_length"];
        }
        if(!eregi("^[0-9a-z_]*$", $str)){
                $err = $errors["pass_cont"];
        }
        return $err;
}
function insertLogData($err=""){
        global $install;
        $file_path = "./install_log.txt";
        if(!$err){      /// simply create log file /install/install_log_file.txt
                $fp = fopen($file_path, "a+");
                if($fp) fclose($fp);    return;
        }
        $err = br2n($err);
        $err = explode("\n", $err);
        $string = "";
        for($i=0;$i<count($err);$i++){
                $string .= date("d-m-y H:i:s")." ".$err[$i]."\n";
        }
        $fp = fopen($file_path, "a+");
        if($fp){
                fputs($fp, $string);
                fclose($fp);
        }
        return;
}
function n2br($str){
        return eregi_replace("\n", "<br>", $str);
}
function br2n($str){
        return eregi_replace("<br>", "\n", $str);
}

function isWriteable($file, $mode){
	@chmod($file, $mode);
	$good = is_writable($file) ? 1 : 0;
	return $good;
}

function scan_dir_file($dirname){
	$mass = array();
	$dir = "";
	$dir = opendir($dirname);
	while (($file = readdir($dir)) !== false){
		if($file != "." && $file != ".."){
			if(is_file($dirname."/".$file)){
				$mass[] = $file;
			}
		}
	}
	closedir($dir);
	return $mass;
}

function RecoverPermission(){	
	$folder = dirname(__FILE__)."/../";
	$install["check_permission_files"] = array(
		"include"._D_S_."constants.xml",
		"include"._D_S_."admin_menu.xml",
		"include"._D_S_."config.php"				
	);
	$sub_ret_str = "";
	$ret_str = "";
	$next_step = true;
	$write_good = true;
	$err="";
					
	foreach($install["check_permission_files"] as $file){		
		if (strpos($file, "ang") == 0){
			$file_perms = substr(sprintf('%o', fileperms($folder.$file)), -4);		
				
			if ($file_perms == "0644"){
				$good = true;						
			}else{
				$good = false;
			}
			$sub_ret_str .= permission_str($file.' is 644: ', $good, 1);
			$write_good   = $write_good && $good;
		}
	}
	$ret_str .= permission_str(' Check file permissions: ', $write_good).$sub_ret_str;
	$next_step   = $next_step && $write_good;

	template_permission_check_page($ret_str, $next_step, $err);
	exit;
}
?>