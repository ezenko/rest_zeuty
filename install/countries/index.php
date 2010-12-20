<?php
/*--------------------------------------------------------------------
*
*
--------------------------------------------------------------------*/
        //error_reporting  (E_ERROR | E_WARNING | E_PARSE);
        ini_set("display_errors", '1');
        error_reporting(E_ALL && ~E_NOTICE);
        ini_set("max_execution_time",600000);
	ini_set("memory_limit","16M");


        include "./config.php";
        include "./initial.php";
        include "./forms.php";
        include "./errors.php";
	include "./pclzip.lib.php";
        include "../../include/config.php";
        include "../../include/constants.php";
        include "../../include/class.restore.php";
        include "../../include/class.utf8.php";

//////////////////////////////////////////////////////////////////////////////////////
        $sel = $_GET["sel"]?$_GET["sel"]:$_POST["sel"];
        switch($sel){
                case "1": Step_2(); break;
				case "2": database_server_Install(); break;
				case "3": Step_3(); break;
				case "4": Settings_Install(); break;
				case "5": template_last_page(); break;
                default:  Step_1(); break;
        }

		function Step_1($err=""){	/// check requirements and return result table
                global $install, $errors, $config;

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

				//check PG RealEstate Installed
				$good = (isset($config["dbhost"]) && isset($config["dbname"]) && isset($config["dbuname"]) && isset($config["dbpass"]) && isset($config["table_prefix"])) ? 1 : 0;
				if($good){
					$link = mysql_connect($config["dbhost"], $config["dbuname"], $config["dbpass"]);
					$good = $link?1:0;

				}
				$ret_str .= permission_str("PG RealEstate Installed: ", $good);
				$next_step   = $next_step && $good;

				foreach($install["permission_files"] as $num=>$file){
					$good = isWriteable($install["install_path"].$file, 0777);
					$sub_ret_str .= permission_str($file.' is writable: ', $good, 1);
					$write_good   = $write_good && $good;
				}
				$ret_str .= permission_str(' Check file permissions: ', $write_good).$sub_ret_str;
				$next_step   = $next_step && $write_good;

				template_first_page($ret_str, $next_step, $err);
				exit;
		}
		function Step_2(){
			global $install, $errors, $bases, $default_lang, $config, $country;
			
			foreach ($country as $k => $v) {
				$sort[$k]['id'] = $k;
				$sort[$k]['name'] = $v['name'];
			}			
			$sort = sortByField($sort, 'name');
						
			$ret_str = "<table>";

			$country_cnt = count($country);
			$rows_cnt = ceil($country_cnt/4);
			$counter = 1;
			for ($row = 0; $row < $rows_cnt; $row++){
				$ret_str .= "<tr>";
				$col = 0;
				while ($col < 4 && $counter <= $country_cnt){
					$i = $sort[$counter]['id'];
					if ($country[$i]['name'] == '0') {
						$counter++;
						continue;
					}
					if ($col < 4) {
						$ret_str .= "<td class=\"main\">".$country[$i]['name']."</td>";
						$ret_str .= "<td class=\"main\"><input name=\"sel_country[".$i."]\" id=\"sel_country[".$i."]\" value=\"".$country[$i]['name']."\" type=\"checkbox\"></td>";
						$ret_str .= "<td class=\"main\"> &nbsp;  &nbsp;  &nbsp; </td>";
						$col++;
					}
					$counter++;
				}
				$ret_str .= "</tr>";
			}
			$ret_str .= "</table>";
			
			$link = DB_Connect();
			$strSQL = "SELECT id FROM ".COUNTRY_TABLE;
			$result = mysql_query($strSQL, $link);
			$countries = array();
			$i=0;
			while ($row = mysql_fetch_array($result)) {
				$countries[$i] = $row["id"];
				$i++;
			}
			
			template_indicate_countries_form($ret_str,$countries);
		}
		function database_server_Install(){
                global $install, $errors, $bases, $default_lang, $config, $country;

		$link = DB_Connect();
		
		if (!isset($_POST["do_not_reinstall"])) {
			$strSQL = "TRUNCATE TABLE ".CITY_TABLE;
			@mysql_query($strSQL);

			$strSQL = "TRUNCATE TABLE ".REGION_TABLE;
			@mysql_query($strSQL);

			$strSQL = "TRUNCATE TABLE ".COUNTRY_TABLE;
			@mysql_query($strSQL);
		}

		template_db_restore("Step 3: Updating Database", "./index.php?sel=3");

		$timer = array_sum(explode(' ', microtime()));
		ob_implicit_flush();

	        foreach ($_POST["sel_country"] as $id_country => $name_country){
		        foreach ($country[$id_country]['part'] as $part_number => $part_country){

						$notice = "Extract ".$name_country." from ".dirname(__FILE__)."/bases/".$part_country.".zip ...";
						print tpl_l($notice);
						insertLogData($notice);
				        	if ($archive = new PclZip(dirname(__FILE__)."/bases/".$part_country.".zip")) {
			        			$archive->extract("./bases/");

							if (file_exists(dirname(__FILE__)."/bases/".$part_country.".zip")) unlink(dirname(__FILE__)."/bases/".$part_country.".zip");

	                				$db_file = dirname(__FILE__)."/bases/".$part_country.".sql";
					                if(!file_exists($db_file)){
								print tpl_l($errors["not_valid_base_file"], C_ERROR);
					                        insertLogData($errors["not_valid_base_file"]);
								print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
				        	                return;
							}

							print tpl_l(str_repeat("-", 60));

							$q = $aff_rows = $c = 0;
						        $f_sql = fopen($db_file,"r");
						    $obj_utf8 = new utf8(CP1252);
							while (!feof($f_sql)) {
								$strSQL = fgets($f_sql);
								$strSQL = $obj_utf8->strToUtf8($strSQL);
								$strSQL = str_replace("[db_prefix]",$config["table_prefix"],$strSQL);
								if (@mysql_query($strSQL)){
									$q++;
									$aff_rows += mysql_affected_rows();
								}
								$c++;
								if ($c > 5000) {
									print tpl_l("Lines it is added : {$aff_rows}");
									$c = 1;
								}
							}
							fclose($f_sql);

							print tpl_l(str_repeat("-", 60));

							print tpl_l("In total inquiries to a DB: {$q}", C_RESULT);
							insertLogData("In total inquiries to a DB: {$q}.");
							print tpl_l("Lines it is added : {$aff_rows}", C_RESULT);
							insertLogData("Lines it is added : {$aff_rows}.");
							print tpl_l("End time point: ".round(array_sum(explode(' ', microtime())) - $timer, 4)." sec.", C_RESULT);

							print tpl_l(str_repeat("-", 60));

							if (file_exists(dirname(__FILE__)."/bases/".$part_country.".sql")) unlink(dirname(__FILE__)."/bases/".$part_country.".sql");						
							
				        	} else {
							$err = "Can not unpack ".$name_country." from ".dirname(__FILE__)."/bases/".$part_country.".zip";
							print tpl_l($err, C_ERROR);
				                        insertLogData($err);
							print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
							return;
						}
			}
			print tpl_l("Country ".$name_country." is successfully installed.", C_RESULT);
			insertLogData("Country ".$name_country." is successfully installed.");
			print tpl_l("End time point: ".round(array_sum(explode(' ', microtime())) - $timer, 4)." sec.", C_RESULT);
			print tpl_l(str_repeat("-", 60));
		}

		print "<SCRIPT>document.getElementById('timer').innerHTML = '" . round(array_sum(explode(' ', microtime())) - $timer, 4) . " sec.'</SCRIPT>";
		print "<SCRIPT>document.getElementById('next').disabled = 0;</SCRIPT>";

	        return;
		}

		function Step_3(){
			global $install, $errors;
			template_last_page();
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
	
		function isWriteable($file, $mode){
			@chmod($file, $mode);
			$good = is_writable($file) ? 1 : 0;
			return $good;
		}

		function br2n($str){
				return eregi_replace("<br>", "\n", $str);
		}

		function DB_Connect(){
                global $install, $errors, $bases, $default_lang, $config;

                $data["dbhost"] = $config["dbhost"];
                $data["dbname"] = $config["dbname"];
                $data["dbuser"] = $config["dbuname"];
                $data["dbpass"] = $config["dbpass"];
                $data["dbprefix"] = $config["table_prefix"];

                ////// try to connect to db
				$link = mysql_connect($data["dbhost"], $data["dbuser"], $data["dbpass"]);
                if(!$link){
                        insertLogData($errors["cant_connect_to_host"]."(".mysql_error().")");
                        Step_1($errors["cant_connect_to_host"]."(".mysql_error().")");
                        return;
                }
                ////// try to connect write a base
                if(!mysql_select_db($data["dbname"])){
                        insertLogData($errors["cant_select_db"]."(".mysql_error().")");
                        Step_1($errors["cant_select_db"]."(".mysql_error().")");
                        return;
                }
				return $link;
		}

		
		function sortByField($or_ar, $field) { 
			$sort_ar = array(); 
			
			foreach($or_ar as $k => $v) { 
					$sort_ar[] = $v[$field];
			}			
			sort($sort_ar); 
			$i = 0;			
			foreach($or_ar as $k => $v) { 				
				foreach($or_ar as $k2 => $v2) { 				
					if($v2[$field] == $sort_ar[$i]) { 
						$res_ar[$k] = $v2; 
						$i++; 
						break; 
					} 
				} 
			}
			
			return $res_ar; 
		}


?>
