<?php
/**
* Class restore database from file
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

define('C_DEFAULT', 1);
define('C_RESULT', 2);
define('C_ERROR', 3);
class BaseRestore {
	function BaseRestore() {
		$this->SET['last_action'] = 0;
		$this->SET['tables'] = '';
		$this->SET['last_db_restore'] = '';
		$this->tabs = 0;
		$this->records = 0;
	}
	function restore($db_host, $db_uname, $db_passw, $db_name, $file){
		@mysql_connect($db_host, $db_uname, $db_passw);
		
		$str = "SET NAMES 'utf8' COLLATE 'utf8_general_ci'";
		if (!mysql_query($str)) {
			print tpl_l(htmlspecialchars("Query isnt valid."));
			insertLogData("Query isn't valid: " . mysql_error());
		}
		$str = "ALTER DATABASE $db_name CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'";
		if (!mysql_query($str)) {
			print tpl_l(htmlspecialchars("Query isnt valid."));
			insertLogData("Query isn't valid: " . mysql_error());
		}
		
		$this->SET['last_action'] = 1;
		$this->SET['last_db_restore'] = $db_name;
		$db = $this->SET['last_db_restore'];

		if (!$db) {
			print tpl_l("Error! The database is not specified!", C_ERROR);
			mysql_close();
			insertLogData("Error! The database is not specified! ".mysql_error());
			print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
			return 1;
		    exit;
		}
		print tpl_l("Connection to DB `{$db}`.");
		if(!mysql_select_db($db)){
			print tpl_l("Error! It is not possible to choose a database.", C_ERROR);
			mysql_close();
			insertLogData("Error! It is not possible to choose a database.");
			print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
			return 2;
		    exit;
		}

		preg_match("/^(\d+)\.(\d+)\.(\d+)/", mysql_get_server_info(), $m);

		$this->mysql_version = sprintf("%d%02d%02d", $m[1], $m[2], $m[3]);

		// Определение формата файла
		$this->SET['comp_method'] = 0;
		$this->SET['comp_level'] = '';

		if (!file_exists($file)) {
    	    print tpl_l("Error!  The file  is not found !", C_ERROR);
			mysql_close();
			insertLogData("Error!  The file is not found !");
			print "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
			return 3;
		    exit;
    	}
		print tpl_l("Reading of a file  `{$file}`.");
		print tpl_l(str_repeat("-", 60));

        $is_skd = $query_len = $execute = $q =$t = $i = $aff_rows = 0;
		$limit = 300;
        $index = 4;
		$tabs = 0;
		$cache = '';
		$info = array();
		$file_content = implode("", file($file));
		$file_content = str_replace("\n", "", $file_content);
		$file_content = preg_replace("/;[\s]*(INSERT|DROP|CREATE|UPDATE)/", "\n\\1", $file_content);
		$file_content_str = explode("\n", $file_content);
		$temp_insert_table_name = "";
		$insert_table_name="";
		
		$bar_total = sizeof($file_content_str);
		$k = 0;
		
		foreach($file_content_str as $str){
			if(!mysql_query($str)){
				print tpl_l(htmlspecialchars("Query isnt valid."));
				insertLogData("Query isn't valid: " . mysql_error());
			}else{
				$q++;
				$aff_rows += mysql_affected_rows();
			}
			if(preg_match("/^[\s]*CREATE TABLE[\s]*(IF NOT EXISTS)?[\s]*([^\(\)]*)/i", $str, $ret_arr)){
				$table_name = trim(str_replace("`", "", $ret_arr[2]));
				print tpl_l("Table `{$table_name}` was created.");
				insertLogData("Table `{$table_name}` was created.");
				$tabs++;
			}
			if(preg_match("/^[\s]*DROP TABLE[\s]*(IF EXISTS)?[\s]*([^,]*)/i", $str, $ret_arr)){
				//$table_name = trim(str_replace("`", "", $ret_arr[2]));
				//print tpl_l("Table `{$table_name}` was removed.");
				//insertLogData("Table `{$table_name}` was removed.");
			}
			if(preg_match("/^[\s]*INSERT INTO[\s]*([^\(\)]*)/i", $str, $ret_arr)){
				$insert_table_name = trim(str_replace("`", "", $ret_arr[1]));
				$insert_table_name = trim(str_replace("VALUES", "", $insert_table_name));
				if($insert_table_name!=$temp_insert_table_name){
					print tpl_l("Table `{$insert_table_name}` was updated.");
					insertLogData("Table `{$insert_table_name}` was updated.");
				}
				$temp_insert_table_name = $insert_table_name;
			}
			$k++;
			print tpl_s($k/$bar_total , $k/$bar_total);
		}

		print tpl_s(1 , 1);
		print tpl_l(str_repeat("-", 60));
		print tpl_l("The database is successfully created.", C_RESULT);
		insertLogData("The database is successfully created.");
		if (isset($info[3])) print tpl_l("Creation date: {$info[3]}", C_RESULT);
		print tpl_l("In total inquiries to a DB: {$q}", C_RESULT);
		insertLogData("In total inquiries to a DB: {$q}.");
		print tpl_l("Tables it is created: {$tabs}", C_RESULT);
		insertLogData("Tables it is created: {$tabs}.");
		print tpl_l("Lines it is added : {$aff_rows}", C_RESULT);
		insertLogData("Lines it is added : {$aff_rows}.");

		$this->tabs = $tabs;
		$this->records = $aff_rows;
		//$this->size = filesize($this->filename);
		return 0;
	}

	function fn_open($name, $mode){
		$this->filename = $name;
		return fopen($this->filename, "{$mode}b");
	}

	function fn_write($fp, $str){
		fwrite($fp, $str);
	}

	function fn_read($fp){
		return fread($fp, 4096);
	}

	function fn_read_str($fp){
		$string = '';
		$this->file_cache = ltrim($this->file_cache);
		$pos = strpos($this->file_cache, "\n", 0);
		if ($pos < 1) {
			while (!$string && ($str = $this->fn_read($fp))){
    			$pos = strpos($str, "\n", 0);
    			if ($pos === false) {
    			    $this->file_cache .= $str;
    			}
    			else{
    				$string = $this->file_cache . substr($str, 0, $pos);
    				$this->file_cache = substr($str, $pos + 1);
    			}
    		}
			if (!$str) {
			    if ($this->file_cache) {
					$string = $this->file_cache;
					$this->file_cache = '';
				    return trim($string);
				}
			    return false;
			}
		}
		else {
  			$string = substr($this->file_cache, 0, $pos);
  			$this->file_cache = substr($this->file_cache, $pos + 1);
		}
		return trim($string);
	}

	function fn_close($fp){
		fclose($fp);
	}
}

function tpl_s($st, $so){
	$st = round($st * 100);
	$st = $st > 100 ? 100 : $st;
	$so = round($so * 100);
	$so = $so > 100 ? 100 : $so;
	return "<SCRIPT>s({$st},{$so});</SCRIPT>";
}
function tpl_l($str, $color = C_DEFAULT){
	$str = preg_replace("/\s{2}/", " &nbsp;", $str);
	return "<SCRIPT>l('{$str}', $color);</SCRIPT>";
}
?>