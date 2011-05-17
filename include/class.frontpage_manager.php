<?php
/**
* Info pages management class
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

class FrontpageManager {
	/**
	 * Database connection instance of a class
	 *
	 * @var object
	 */
	var $_dbconn;

	/**
	* Class Constructor
	* @access public
	* @param void
	* @return void
	**/
	function FrontpageManager() {
		global $dbconn;

		$this->_dbconn = $dbconn;
	}

	/**
	 * Add info section
	 *
	 * @access public
	 *
	 * @param integer $language_id
	 * @param string $caption
	 * @param string $content
	 * @param string $description
	 * @param string $keywords
	 * @param integer $status
	 * @param string $menu_position - menu position name
	 * @return integer
	 */
	function AddFrontpage($language_id, $caption, $content, $link, $image) {
		$sql_query = "INSERT INTO ".FRONTPAGE_TABLE." SET ".
					  "language_id = '$language_id', image = '".addslashes($image)."', ".
					  "caption = '".addslashes($caption)."', content = '".addslashes($content)."', link = '".addslashes($link)."'," .
					  "sequence = '".($this->GetMaxSequence($language_id) + 1)."'";
		$record_set = $this->_dbconn->Execute($sql_query);
		return $this->_dbconn->Insert_ID();
	}

	/**
	 * Edit info section
	 *
	 * @access public
	 * @param integer $id
	 * @param string $caption
	 * @param string $content
	 * @param string $description
	 * @param string $keywords
	 * @param integer $status
	 * @return void
	 */
	function EditFrontpage($id, $caption, $content, $link, $image) {
		$sql_query = "UPDATE ".FRONTPAGE_TABLE." SET ".
					  ($image ? "image = '".addslashes($image) . "'," : "") . " content = '".addslashes($content)."', ".
					 "caption = '".addslashes($caption)."', link = '".addslashes($link)."' ".
				     "WHERE id = '$id'";

		$record_set = $this->_dbconn->Execute($sql_query);
		return;
	}

	/**
	* Get max sequence for info page section on one $language_id and $menu_position
	*
	* @access public
	* @param integer $language_id - language ID
	* @param string $menu_position - menu position name
	* @return integer
	**/
	function GetMaxSequence($language_id) {
		$sql_query = "SELECT MAX(sequence) AS max_sequence
					  FROM ".FRONTPAGE_TABLE."
					  WHERE language_id = '$language_id'";
		$record_set = $this->_dbconn->Execute($sql_query);
		return $record_set->fields[0];
	}

	/**
	 * Get Info Sections list for language and menu position
	 *
	 * @access public
	 * @param integer $language_id
	 * @param string $menu_position - menu position name
	 * @param boolean $only_active
	 * @return array
	 */
	function GetFrontpageList($language_id) {
		$sql_query = "SELECT id, sequence, caption, link, content, image ".
					 "FROM ".FRONTPAGE_TABLE." ".
					 "WHERE language_id = '$language_id' ";
		
		$sql_query .= "ORDER BY sequence";
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$pages[] = $page;
			$record_set->MoveNext();
		}
		return $pages;
	}

    
	/**
	 * Get info section by id
	 *
	 * @access public
	 * @param integer $id
	 * @return void
	 */
	function GetFrontpage($id) {
		$sql_query = "SELECT id, language_id, sequence, caption, content, ".
					 "link ".
					 "FROM ".FRONTPAGE_TABLE." ".
					 "WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);
		$page = $record_set->GetRowAssoc(false);
		$page["caption"] = stripslashes($page["caption"]);
		$page["content"] = stripslashes($page["content"]);
		$page["image"] = stripslashes($page["image"]);
    $page["link"] = stripslashes($page["link"]);
		return $page;
	}

	
	/**
	 * Move info page up on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function UpFrontpage($id) {
		$page = $this->GetFrontpage($id);

		$sql_query = "SELECT id, sequence FROM ".FRONTPAGE_TABLE." ".
					 "WHERE sequence < '{$page["sequence"]}' AND sequence > '0' AND ".
					 "language_id = '{$page["language_id"]}' ".
					 "ORDER BY sequence DESC";
		$record_set = $this->_dbconn->Execute($sql_query);
		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".FRONTPAGE_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".FRONTPAGE_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Move info page down on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function DownFrontpage($id) {
		$page = $this->GetFrontpage($id);

		$sql_query = "SELECT id, sequence FROM ".FRONTPAGE_TABLE." ".
					 "WHERE sequence > '{$page["sequence"]}' AND ".
					 "language_id = '{$page["language_id"]}' ".
					 "ORDER BY sequence ASC";
		$record_set = $this->_dbconn->Execute($sql_query);

		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".FRONTPAGE_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".FRONTPAGE_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Delete page by id and
	 *
	 * @access public
	 * @param integer $id - page ID
	 * @return void
	 */
	function DeleteFrontpage($id) {
		/**
		 * change sequence for all other pages of the same language
		 */
		$page = $this->GetFrontpage($id);
		if ($page["sequence"] < $this->GetMaxSequence($page["language_id"], $page["menu_position"])) {
			$sql_query = "SELECT id FROM ".FRONTPAGE_TABLE." ".
					 	 "WHERE sequence > '{$page["sequence"]}' AND ".
					 	 "language_id = '{$page["language_id"]}' ";
			$record_set = $this->_dbconn->Execute($sql_query);
			while (!$record_set->EOF) {
				$sql_query = "UPDATE ".FRONTPAGE_TABLE." SET ".
				 			 "sequence = sequence-1 ".
						 	 "WHERE id = '{$record_set->fields[0]}'";
				$this->_dbconn->Execute($sql_query);

				$record_set->MoveNext();
			}
		}
		/**
		 * delete page
		 */
		$sql_query = "DELETE FROM ".FRONTPAGE_TABLE." WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);

	}
}

?>