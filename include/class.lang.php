<?php
/**
* Class wor work with references
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/15 13:06:00 $
**/

class MultiLang{
	var $config;
	var $dbconn;
	var $TABLE_KEY_ARR = array(
		1=> 	SPR_GENDER_TABLE,
		2=> 	VALUES_GENDER_TABLE,

		3=> 	SPR_APARTMENT_TABLE,
		4=> 	VALUES_APARTMENT_TABLE,

		5=> 	SPR_PEOPLE_TABLE,
		6=> 	VALUES_PEOPLE_TABLE,

		13=> 	SPR_DEACTIVATE_TABLE,
		14=> 	VALUES_DEACTIVATE_TABLE,

		15=> 	SPR_LANGUAGE_TABLE,
		16=> 	VALUES_LANGUAGE_TABLE,

		17=> 	SPR_TYPE_TABLE,
		18=> 	VALUES_TYPE_TABLE,

		19=> 	SPR_DESCRIPTION_TABLE,
		20=> 	VALUES_DESCRIPTION_TABLE,

		21=> 	SPR_PERIOD_TABLE,
		22=> 	VALUES_PERIOD_TABLE

	);


	function MultiLang($config, $dbconn){
		$this->config = $config;
		$this->dbconn = $dbconn;
	}


	function TableKey($table_name, $num=""){
		if($num>0){
			$num = $num - 1;
			$ret_arr = array_keys($this->TABLE_KEY_ARR, $table_name);
			if(is_array($ret_arr)){
				return $ret_arr[$num];
			}else{
				return $ret_arr[0];
			}
		}else{
			return array_search($table_name, $this->TABLE_KEY_ARR);
		}
	}

	function TableName($table_key){
		return $this->TABLE_KEY_ARR[$table_key];
	}

	function ValuesIdArray($id_spr, $table_key){
		$strSQL = "select id from ".$this->TableName($table_key)." where id_spr='".$id_spr."' ";
		$rs = $this->dbconn->Execute($strSQL);
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$id_arr[$i] = $row["id"];
			$rs->MoveNext();
			$i++;
		}
		return $id_arr;
	}

	/**
	 * return field name in table REFERENCE_LANG_TABLE
	 *
	 * @param string $lang_add (1 - when describe myself or my property / 2 - when describe what or whom i am seeking)
	 * @return string
	 */
	function DefaultFieldName($lang_add=''){
		if ($lang_add == ''){
			$lang_add = '1';
		}
		return "lang_".$this->config["default_lang"]."_".$lang_add;
	}
	function DiffFieldName($lang, $lang_add=''){
		if ($lang_add == ''){
			$lang_add = '1';
		}
		return "lang_".$lang."_".$lang_add;
	}
	
	/**
	 * Delete value by id
	 *
	 * @param integer $id
	 * @return void
	 */
	function DeleteRefLangValue($id) {
		$strSQL = "DELETE FROM ".REFERENCE_LANG_TABLE." WHERE id='$id'";
		$this->dbconn->Execute($strSQL);
	}
	
	/**
	 * Delete subreference values
	 *
	 * @param integer $id
	 * @return void
	 */
	function DeleteRefName($id, $table_key) {
		$strSQL = "DELETE FROM ".REFERENCE_LANG_TABLE." WHERE table_key='".$table_key."' AND id_reference='".$id."'";
		$this->dbconn->Execute($strSQL);
	}
	
	function DeleteRefNames($id_arr, $table_key) {
		if (is_array($id_arr)) {
			$id_str = implode(" ,", $id_arr);
			$strSQL = "DELETE FROM ".REFERENCE_LANG_TABLE." WHERE table_key='".$table_key."' AND id_reference IN (".$id_str.")";
			$this->dbconn->Execute($strSQL);
		}
	}

	function SelectDiffLangList($table_key, $lang, $where_str="", $order_str=""){
		$field_name = $this->DiffFieldName($lang);
		if($where_str!="") $where_str = " and ".$where_str;
		if($order_str=="") $order_str = " order by id";
		$strSQL = "select id, ".$field_name." as name from ".REFERENCE_LANG_TABLE." where table_key='".$table_key."' ".$where_str.$order_str;
		$rs = $this->dbconn->Execute($strSQL);
		$i = 0;
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$spr_arr[$i]["id"] = $row["id"];
			$spr_arr[$i]["name"] = htmlspecialchars(stripslashes($row["name"]));
			$rs->MoveNext();
			$i++;
		}
		return $spr_arr;
	}

	/**
	 * Return in array value for lang_<current_lang_id>_1
	 *
	 * @param integer $table_key
	 * @param integer $id_ref
	 * @return array
	 */
	function SelectDefaultLangName($table_key, $id_ref){
		$field_name = $this->DefaultFieldName();
		$strSQL = "select id, ".$field_name." as name from ".REFERENCE_LANG_TABLE." where table_key='".$table_key."' and id_reference='".$id_ref."'";
		$rs = $this->dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$spr_arr["id"] = $row["id"];
		$spr_arr["name"] = $row["name"];
		return $spr_arr;
	}

	/**
	 * Return in array values both for lang_<current_lang_id>_1 and lang_<current_lang_id>_2
	 *
	 * @param integer $table_key
	 * @param integer $id_ref
	 * @return array
	 */
	function SelectDefaultLangNames($table_key, $id_ref){
		$field_name_1 = $this->DefaultFieldName();
		$field_name_2 = $this->DefaultFieldName('2');
		$strSQL = "SELECT id, ".$field_name_1." AS name_1, ".$field_name_2." AS name_2
				   FROM ".REFERENCE_LANG_TABLE."
				   WHERE table_key='".$table_key."' AND id_reference='".$id_ref."'";
		$rs = $this->dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$spr_arr["id"] = $row["id"];
		$spr_arr["name_1"] = $row["name_1"];
		$spr_arr["name_2"] = $row["name_2"];
		return $spr_arr;
	}
	function SelectDiffLangName($table_key, $id_ref, $lang){
		$field_name = $this->DiffFieldName($lang);
		$strSQL = "select id, ".$field_name." as name from ".REFERENCE_LANG_TABLE." where table_key='".$table_key."' and id_reference='".$id_ref."'";
		$rs = $this->dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		$spr_arr["id"] = $row["id"];
		$spr_arr["name"] = $row["name"];
		return $spr_arr;
	}
	function FirstLangInsert($table_key, $id_reference, $name){
		$str_f = "table_key, id_reference";
		$str_v = "'".$table_key."', '".$id_reference."'";
		$strSQL = "show fields from ".REFERENCE_LANG_TABLE."";
		$rs = $this->dbconn->Execute($strSQL);
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			if(strpos($row["field"], "lang") !== false){
				$str_f .= ", ".$row["field"];
				$str_v .= ", '".$name."'";
			}
			$rs->MoveNext();
		}
		$strSQL = "insert into ".REFERENCE_LANG_TABLE." (".$str_f.") values (".$str_v.")";
		$this->dbconn->Execute($strSQL);
	}

	function SaveNames($table_key, $lang_code, $names){
		foreach($names as $lang_add_id => $lang_add_arr) {
			foreach($lang_add_arr as $id=>$name) {
				$strSQL = "update ".REFERENCE_LANG_TABLE." set ".$this->DiffFieldName($lang_code, $lang_add_id)."='".addslashes($name)."' where table_key='".$table_key."' and id='".$id."' ";
				$this->dbconn->Execute($strSQL);
				echo $this->dbconn->ErrorMsg();
			}
		}
		return;
	}

	function SaveDefaultRefNames($table_key, $name_1, $name_2, $id_ref){
		//save name_1
		$strSQL = "update ".REFERENCE_LANG_TABLE." set ".$this->DefaultFieldName()."='".addslashes($name_1)."' where table_key='".$table_key."'  and  id_reference='".$id_ref."' ";
		$this->dbconn->Execute($strSQL);
		//save name_2, if $name_2 is empty, set its' value equal to $name_1
		$name = (!empty($name_2)) ? $name_2 : $name_1;
		$strSQL = "update ".REFERENCE_LANG_TABLE." set ".$this->DefaultFieldName('2')."='".addslashes($name)."' where table_key='".$table_key."'  and  id_reference='".$id_ref."' ";
		$this->dbconn->Execute($strSQL);
	}
	function GetMLIdByRef($table_key, $id_ref){
		$strSQL = "select id from ".REFERENCE_LANG_TABLE." where  table_key='".$table_key."'  and  id_reference='".$id_ref."'";
		$rs = $this->dbconn->Execute($strSQL);
		$row = $rs->GetRowAssoc(false);
		return $row["id"];
	}
}
?>