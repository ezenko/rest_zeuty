<?php
/**
* Fuctions collection for users authorization
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.4 $ $Date: 2008/12/01 09:00:21 $
**/

global $functions_auth_php_included;

if (!isset($functions_auth_php_included)){
	$functions_auth_php_included="included";

///// read id_user from active sessions table accordingly his session id
function sess_read($sess_id){
	global $dbconn;

 	$strSQL = "SELECT id_user FROM ".ACTIVE_SESSIONS_TABLE." WHERE session = '".$sess_id."'";
	$rs = $dbconn->Execute($strSQL);

	if($rs->RowCount()){
		return $rs->fields[0];
	}else{
		return "";
	}
}
///// write session id and user id of sitting into base
function sess_write($sess_id, $id_user){
	global $dbconn;
	$ip_address = $_SERVER["REMOTE_ADDR"];
	@$file = $_SERVER["REQUEST_URI"];
	if(!$file) $file= $_SERVER["SCRIPT_NAME"];

	/////// delete all entries from table with the same session_id( if user was a guest earlier)
	$strSQL = "Delete from ".ACTIVE_SESSIONS_TABLE." where session = '".$sess_id."' ";
	$rs = $dbconn->Execute($strSQL);

	$strSQL = "select guest_user from ".USERS_TABLE." where id='".$id_user."'";
	$rs = $dbconn->Execute($strSQL);
	$guest_user = intval($rs->fields[0]);
	if ($guest_user == 1){
		$dbconn->Execute("Delete from ".ACTIVE_SESSIONS_TABLE." where id_user = '".$id_user."' and session='".$sess_id."'");
	}
	/////// insert
	$strSQL = "Insert into ".ACTIVE_SESSIONS_TABLE." (id_user, session, ip_address, file, update_date) values ( '".$id_user."', '".$sess_id."', '".$ip_address."', '".$file."', now()) ";
	$rs = $dbconn->Execute($strSQL);
    return true;
}
///// delete perfect sitting from database
function sess_delete($sess_id){
	global $dbconn;
	$strSQL = "Delete from ".ACTIVE_SESSIONS_TABLE." where session='".$sess_id."'";
	$rs = $dbconn->Execute($strSQL);
    return true;
}
///// delete expired sessions
function sess_clear($lifetime){
	global $dbconn;

    $strSQL = "Delete from ".ACTIVE_SESSIONS_TABLE." where UNIX_TIMESTAMP(update_date) < UNIX_TIMESTAMP(now())-".$lifetime." ";
	$rs = $dbconn->Execute($strSQL);

	/**
	 * delete expired comparison ids settings for guest users
	 */
	$strSQL = "SELECT cl.id FROM ".COMPARISON_LIST_TABLE." cl ".
			  "LEFT JOIN ".USERS_TABLE." u ON u.id=cl.id_user ".
			  "WHERE u.guest_user='1' AND UNIX_TIMESTAMP(cl.update_date) < UNIX_TIMESTAMP(now())-".$lifetime." ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->RowCount() > 0) {
		$del_ids = array();
		while (!$rs->EOF) {
			$del_ids[] = $rs->fields[0];
			$rs->MoveNext();
		}
		$strSQL = "DELETE FROM ".COMPARISON_LIST_TABLE." WHERE id IN ('".implode("','", $del_ids)."')";
		$rs = $dbconn->Execute($strSQL);
	}

    return true;
}

///// get users.id from user table
function auth_user_read($auth_user, $auth_pass, $type, $sess_id){
	global $dbconn;

	switch($type) {
		case "0":			
			$strSQL = "Select id, fname, sname, guest_user, root_user, login, status, active, confirm, user_type, access, lang_id FROM ".USERS_TABLE." where login = '".addslashes($auth_user)."' and password = '".md5($auth_pass)."' ";			
			break;
		case "1":
			$strSQL = "Select id, fname, sname, guest_user, root_user, login, status, active, confirm, user_type, access, lang_id from ".USERS_TABLE." where id = '".$auth_user."' ";
			break;
	}

	$rs = $dbconn->Execute($strSQL);
    if($rs->RowCount() == 0) return "";
	$row = $rs->GetRowAssoc(false);
	///////////       0         1            2                3                    4              5                 6          7		8				9				 10					11				12
    $ret = array($row["id"], $row["fname"], $row["sname"], $row["guest_user"], $row["root_user"], $row["login"], $type, $row["status"], $row["active"], $row["confirm"], $row["user_type"], $row["access"], $sess_id, $row["lang_id"]);
   	////// type needed for payments
	//////if user login firstly(with login pass) we dont remove points from his account and only refresh date of account
	//////else we remove points spended during period betveen last and present refreshes
	if ($ret[11] == 0) {
    	unset($ret);
    	$ret =  array("no_access", "", "", "1");
    }
    if ($ret[11] == 1 && $ret[3] == 0) {
    	/**
    	 * add all comparison ids for the user, wich he had set while he was not logged in, to the users' comparison list
    	 */
    	$guest = auth_guest_read();
    	$strSQL = "SELECT id, id_ad FROM ".COMPARISON_LIST_TABLE." ".
				  "WHERE id_user='".$guest[0]."' AND session='$sess_id'";
		$rs = $dbconn->Execute($strSQL);
		if ($rs->RowCount() > 0) {
			$temp_ids = array();
			while (!$rs->EOF) {
				$temp_ids[] = $rs->getRowAssoc( false );
				$rs->MoveNext();
			}
			$strSQL = "SELECT id_ad FROM ".COMPARISON_LIST_TABLE." ".
					  "WHERE id_user='".$ret[0]."'";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->RowCount() > 0) {
				$set_ids = array();
				while (!$rs->EOF) {
					$set_ids[] = $rs->fields[0];
					$rs->MoveNext();
				}
			}
			foreach ($temp_ids as $temp_id) {
				if (!in_array($temp_id["id_ad"], $set_ids)) {
					$strSQL = "INSERT INTO ".COMPARISON_LIST_TABLE." SET ".
				  		 	  "id_user='".$ret[0]."', id_ad='".$temp_id["id_ad"]."'";
					$rs = $dbconn->Execute($strSQL);
					$strSQL = "DELETE FROM ".COMPARISON_LIST_TABLE." ".
				  		 	  "WHERE id='".$temp_id["id"]."'";
					$rs = $dbconn->Execute($strSQL);
				}
			}
		}
    }
    return $ret;
}
function auth_guest_read($sess_id = ""){
	global $dbconn;
	$strSQL = "Select id, fname, sname, guest_user, root_user, login, status, active, confirm from ".USERS_TABLE." where guest_user ='1' ";
	$rs = $dbconn->Execute($strSQL);
    if($rs->RowCount() == 0) return "";
	$row = $rs->GetRowAssoc(false);

    $ret = array($row["id"], $row["fname"], $row["sname"], $row["guest_user"], $row["root_user"], $row["login"], 1, $row["status"], $row["active"], $row["confirm"], 1, 1, $sess_id);
    return $ret;
}

///// refresh session date
function auth_user_update_date($sess_id,$id_user){
	global $dbconn;
    $date = date("YmdHis");

	@$file = $_SERVER["REQUEST_URI"];
	if(!$file) $file= $_SERVER["SCRIPT_NAME"];

	$strSQL = "select guest_user from ".USERS_TABLE."  where id='".$id_user."'";
	$rs = $dbconn->Execute($strSQL);
	$guest_user = intval($rs->fields[0]);
	if($guest_user == 1){
		$ip_address = $_SERVER["REMOTE_ADDR"];
		$dbconn->Execute("Delete from ".ACTIVE_SESSIONS_TABLE." where id_user = '".$id_user."'");
		$dbconn->Execute("Insert into ".ACTIVE_SESSIONS_TABLE." (id_user, session, ip_address, file, update_date) values ( '".$id_user."', '".$sess_id."', '".$ip_address."', '".$file."', now())");
	}else{
		$strSQL = "Update ".ACTIVE_SESSIONS_TABLE." set update_date = now(), file='".$file."'  where session='".$sess_id."' and id_user = '".$id_user."'";
		$rs = $dbconn->Execute($strSQL);
	}
	/////// update
	$strSQL = "Update ".USERS_TABLE."  set date_last_seen='".$date."' where id = '".$id_user."'";
	$rs = $dbconn->Execute($strSQL);
    return true;
}


function auth_user(){
	global $dbconn;

	$sess_id = session_id();

	if(!$sess_id) $sess_id = $_REQUEST["PHPSESSID"];

	@$login = $_POST["login_lg"];
	@$pass = $_POST["pass_lg"];

	$login =  htmlspecialchars(trim($login));
	$pass =  htmlspecialchars(trim($pass));

	sess_clear(3600);

    $check_id = sess_read($sess_id);
	if(empty($check_id)){
		if( ($login !="") && ($pass != "") ){
			 $auth = auth_user_read($login, $pass, "0", $sess_id);
             if(empty($auth[0])){
					return "";
			 } elseif($auth[0] == "no_access"){
					return "no_access";
			 } else{
					if ($auth[4]!=1 && $auth[10]!=2 && $auth[10]!=3) {
						/**
						 * users, who can login here:
						 * - admin - to admin area,
						 * - realestate company to user area
						 * - agents of companies to user area
						 * private persons could not login
						 */
						return "";
					}
					sess_write($sess_id, $auth[0]);
					return $auth;
			 }
		}else{
			return "";
		}
	}else{
		$auth = auth_user_read($check_id, "", "1", $sess_id);
		if(empty($auth[0])) return "";

		if( ($login !="") && ($pass != "") && ($auth[3]=='1') ){
			$auth = auth_user_read($login, $pass, "0", $sess_id);
             if(empty($auth[0])){
					return "";
			 } elseif($auth[0] == "no_access"){
					return "no_access";
			 } else{
					sess_write($sess_id, $auth[0]);
					return $auth;
			 }
		}
		if($auth[3]=='1')
			return "";
		auth_user_update_date($sess_id,$auth[0]);
		return $auth;
	}

}

/**
 * authorization in users area
 * if user doesnt exists and login passw == 0
 * new user =>> guest
 * then take information and id from user table where guest_user = '1'
 *
 * @return mixed (string or array)
 */
function auth_index_user(){
	global $dbconn, $smarty;
	/**
	 * проверка на наличие строки &amp; в ключах $_GET, $_POST,
	 * если true, то кидаем в массив переменную с таким же именеи, но без этой строки
	 *
	 */
	foreach ($_GET as $key=>$value){
		$amp_pos = strstr($key, 'amp;');
		if ($amp_pos !== false) {
			$_GET[str_replace('amp;', '', $key)] = $value;
		}
	}
	foreach ($_POST as $key=>$value){
		$amp_pos = strstr($key, 'amp;');
		if ($amp_pos !== false) {
			$_POST[str_replace('amp;', '', $key)] = $value;
		}
	}
	if (isset($_GET["sess_id"])) {
		$sess_id = $_GET["sess_id"];
		setcookie("PHPSESSID", $sess_id);
	} else {
		$sess_id = session_id();

		if(!$sess_id) $sess_id = $_REQUEST["PHPSESSID"];
	}

	@$login = $_POST["login_lg"];
	@$pass = $_POST["pass_lg"];

	$login =  htmlspecialchars(trim($login));
	$pass =  htmlspecialchars(trim($pass));
	$smarty->assign("sess_id", $sess_id);
	sess_clear(3600);

	$check_id = sess_read($sess_id);

	if(empty($check_id)){
		if( ($login !="") && ($pass != "") ){
			$auth = auth_user_read($login, $pass, "0", $sess_id);

			if(empty($auth[0])){
					return "err";
			} elseif($auth[0] == "no_access"){
					return "no_access";
			}
					else {
					//////// say for all friends that we came
					SetLoginStatistic($auth[0]);
					sess_write($sess_id, $auth[0]);
					return $auth;
			}
		}else{
			///////// user not registered (guest)
			$auth = auth_guest_read($sess_id);
            if(empty($auth[0])){
					return "";
			}else{
					sess_write($sess_id, $auth[0]);
					return $auth;
			}
		}
	}else{
		$auth = auth_user_read($check_id, "", "1", $sess_id);

		if (empty($auth[0])) {
			return "";
		}


		if( ($login !="") && ($pass != "") && ($auth[3]=="1") ){
			$auth = auth_user_read($login, $pass, "0", $sess_id);


			if(empty($auth[0])){
					return "err";
			} elseif($auth[0] == "no_access"){
					return "no_access";
			}  else{
					//////// say for all friends what we came
					if ($auth[9]==0)
					{
						return "no_confirm";
					}
					else {
					SetLoginStatistic($auth[0]);
					sess_write($sess_id, $auth[0]);
					return $auth;
					}
			}
		}

		auth_user_update_date($sess_id,$auth[0]);
		return $auth;
	}

}

function SetLoginStatistic($id){
	global $dbconn;
	$dbconn->Execute("update ".USERS_TABLE." set login_count=login_count+1 where id='".$id."'");
	return;
}

function CheckInstallFolder(){
	global $config, $smarty;

	$dh = opendir($config["site_path"]);
	while (($entry = readdir($dh)) !== false) {
		if (is_dir($config["site_path"]."/".$entry) && strstr($entry,"install")) {
			$smarty->assign("folder", $entry);
			$smarty->display(TrimSlash($config["index_theme_path"])."/install_folder_error.tpl");
			exit;
		}
	}
	closedir($dh);
	return;
}

}
?>