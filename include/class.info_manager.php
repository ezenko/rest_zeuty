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

class InfoManager {
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
	function InfoManager() {
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
	function AddSection($language_id, $caption, $content, $description, $keywords, $status, $menu_position) {
		$sql_query = "INSERT INTO ".INFO_SECTION_TABLE." SET ".
					  "language_id = '$language_id', caption = '".addslashes($caption)."', ".
					  "content = '".addslashes($content)."', description = '".addslashes($description)."', ".
					  "keywords = '".addslashes($keywords)."', status = '$status', ".
					  "menu_position = '$menu_position', ".
					  "sequence = '".($this->GetMaxSectionSequence($language_id, $menu_position) + 1)."'";
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
	function EditSection($id, $caption, $content, $description, $keywords, $status) {
		$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
					 "caption = '".addslashes($caption)."', content = '".addslashes($content)."', ".
					 "description = '".addslashes($description)."', keywords = '".addslashes($keywords)."', ".
					 "status = '$status' ".
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
	function GetMaxSectionSequence($language_id, $menu_position) {
		$sql_query = "SELECT MAX(sequence) AS max_sequence
					  FROM ".INFO_SECTION_TABLE."
					  WHERE language_id = '$language_id' AND menu_position = '$menu_position'";
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
	function GetSectionsList($language_id, $menu_position, $only_active = false) {
		$sql_query = "SELECT id, sequence, caption, status ".
					 "FROM ".INFO_SECTION_TABLE." ".
					 "WHERE language_id = '$language_id' AND menu_position = '$menu_position' ";
		$sql_query .= ($only_active) ? "AND status = '1' " : "";
		$sql_query .= "ORDER BY sequence";
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$page["subsections_cnt"] = $this->GetSubsectionsCount($page["id"]);
			$pages[] = $page;
			$record_set->MoveNext();
		}
		return $pages;
	}

	/**
	 * Get subsections count for the section with $section_id
	 *
	 * @param integre $section_id
	 * @param boolean $only_active
	 * @return integer
	 */
	function GetSubsectionsCount($section_id, $only_active = false) {
		$sql_query = "SELECT COUNT(id) AS cnt
					  FROM ".INFO_SUBSECTION_TABLE."
					  WHERE section_id = '$section_id'";
		$sql_query .= ($only_active) ? " AND status = '1'" : "";
		$record_set = $this->_dbconn->Execute($sql_query);
		return $record_set->fields[0];
	}

	/**
	 * Get info section by id
	 *
	 * @access public
	 * @param integer $id
	 * @return void
	 */
	function GetSection($id) {
		$sql_query = "SELECT id, language_id, sequence, caption, content, ".
					 "description, keywords, status, menu_position ".
					 "FROM ".INFO_SECTION_TABLE." ".
					 "WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);
		$page = $record_set->GetRowAssoc(false);
		$page["caption"] = stripslashes($page["caption"]);
		$page["content"] = stripslashes($page["content"]);
		$page["description"] = stripslashes($page["description"]);
		$page["keywords"] = stripslashes($page["keywords"]);
		return $page;
	}

	/**
	 * Activate section page
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function SectionActivate($id) {
		$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET status = '1' ".
					 "WHERE id = '".$id."'";
		$record_set = $this->_dbconn->Execute($sql_query);
		return;
	}

	/**
	 * Change section pages activity status for all pages of the language with $language_id
	 *
	 * @access public
	 * @param array $statuses - array of pages $id, which should to be active
	 * @param integer $language_id
	 * @param string $menu_position - menu position name
	 * @return void
	 */
	function ChangeSectionStatus($statuses, $language_id, $menu_position) {
		/**
		 * make all pages for the language inactive
		 */
		$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET status = '0' ".
					 "WHERE language_id = '".$language_id."' AND ".
					 "menu_position = '".$menu_position."'";
		$record_set = $this->_dbconn->Execute($sql_query);
		foreach ($statuses as $id=>$value) {
			$this->SectionActivate($id);
		}
	}

	/**
	 * Move info page up on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function UpSection($id) {
		$page = $this->GetSection($id);

		$sql_query = "SELECT id, sequence FROM ".INFO_SECTION_TABLE." ".
					 "WHERE sequence < '{$page["sequence"]}' AND sequence > '0' AND ".
					 "language_id = '{$page["language_id"]}' AND ".
					 "menu_position = '{$page["menu_position"]}' ".
					 "ORDER BY sequence DESC";
		$record_set = $this->_dbconn->Execute($sql_query);
		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
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
	function DownSection($id) {
		$page = $this->GetSection($id);

		$sql_query = "SELECT id, sequence FROM ".INFO_SECTION_TABLE." ".
					 "WHERE sequence > '{$page["sequence"]}' AND ".
					 "language_id = '{$page["language_id"]}' AND ".
					 "menu_position = '{$page["menu_position"]}' ".
					 "ORDER BY sequence ASC";
		$record_set = $this->_dbconn->Execute($sql_query);

		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Copy page to all active languages
	 *
	 * @access public
	 * @param integer $id - page id
	 * @return void
	 */
	function CopySection($id) {
		$page = $this->GetSection($id);
		$subsections = $this->GetSubsectionsList($id);

		$langs = GetActiveLanguages();
		foreach ($langs as $lang) {
			if ($lang["id"] != $page["language_id"]) {
				$new_section_id = $this->AddSection($lang["id"], $page["caption"], $page["content"], $page["description"], $page["keywords"], 0, $page["menu_position"]);
				/**
				 * copy all subsections
				 */
				foreach ($subsections as $subsect) {
					$this->AddSubsection($new_section_id, $subsect["caption"], $subsect["content"], $subsect["description"], $subsect["keywords"], $subsect["status"]);
				}
			}
		}
	}

	/**
	 * Delete page by id and
	 *
	 * @access public
	 * @param integer $id - page ID
	 * @return void
	 */
	function DeleteSection($id) {
		/**
		 * change sequence for all other pages of the same language
		 */
		$page = $this->GetSection($id);
		if ($page["sequence"] < $this->GetMaxSectionSequence($page["language_id"], $page["menu_position"])) {
			$sql_query = "SELECT id FROM ".INFO_SECTION_TABLE." ".
					 	 "WHERE sequence > '{$page["sequence"]}' AND ".
					 	 "language_id = '{$page["language_id"]}' AND ".
					 	 "menu_position = '{$page["menu_position"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
			while (!$record_set->EOF) {
				$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
				 			 "sequence = sequence-1 ".
						 	 "WHERE id = '{$record_set->fields[0]}'";
				$this->_dbconn->Execute($sql_query);

				$record_set->MoveNext();
			}
		}
		/**
		 * delete page
		 */
		$sql_query = "DELETE FROM ".INFO_SECTION_TABLE." WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);

		/**
		 * delete all subsections
		 */
		$sql_query = "DELETE FROM ".INFO_SUBSECTION_TABLE." WHERE section_id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);
	}

	/**
	 * Move Section to another menu position
	 *
	 * @param integer $id
	 * @param string $menu_position
	 * @return void
	 */
	function MenuMoveSection($id, $menu_position) {
		$page = $this->GetSection($id);
		if ($page["sequence"] < $this->GetMaxSectionSequence($page["language_id"], $page["menu_position"])) {
			$sql_query = "SELECT id FROM ".INFO_SECTION_TABLE." ".
					 	 "WHERE sequence > '{$page["sequence"]}' AND ".
					 	 "language_id = '{$page["language_id"]}' AND ".
					 	 "menu_position = '{$page["menu_position"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
			while (!$record_set->EOF) {
				$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
				 			 "sequence = sequence-1 ".
						 	 "WHERE id = '{$record_set->fields[0]}'";
				$this->_dbconn->Execute($sql_query);

				$record_set->MoveNext();
			}
		}
		/**
		 * move section to the $menu_position
		 */
		$sql_query = "UPDATE ".INFO_SECTION_TABLE." SET ".
					 "menu_position = '$menu_position', ".
		 			 "sequence = '".($this->GetMaxSectionSequence($page["language_id"], $menu_position) + 1)."' ".
				 	 "WHERE id = '$id'";
		$record_set = $this->_dbconn->Execute($sql_query);
	}


	/**
	 * Get Subsections list of the section
	 *
	 * @access public
	 * @param integer $section_id
	 * @param boolean $only_active
	 * @return array
	 */
	function GetSubsectionsList($section_id, $only_active = false) {
		$sql_query = "SELECT id, sequence, caption, content, description, keywords, status ".
					 "FROM ".INFO_SUBSECTION_TABLE." ".
					 "WHERE section_id = '{$section_id}' ";
		$sql_query .= ($only_active) ? "AND status = '1' " : "";
		$sql_query .= "ORDER BY sequence";
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$page["caption"] = stripslashes($page["caption"]);
			$page["content"] = stripslashes($page["content"]);
			$page["description"] = stripslashes($page["description"]);
			$page["keywords"] = stripslashes($page["keywords"]);
			$pages[] = $page;
			$record_set->MoveNext();
		}
		return $pages;
	}

	/**
	 * Add info subsection
	 *
	 * @access public
	 *
	 * @param integer $section_id
	 * @param string $caption
	 * @param string $content
	 * @param string $description
	 * @param string $keywords
	 * @param integer $status
	 * @return void
	 */
	function AddSubsection($section_id, $caption, $content, $description, $keywords, $status) {
		$sql_query = "INSERT INTO ".INFO_SUBSECTION_TABLE." SET ".
					  "section_id = '$section_id', caption = '".addslashes($caption)."', ".
					  "content = '".addslashes($content)."', description = '".addslashes($description)."', ".
					  "keywords = '".addslashes($keywords)."', status = '$status', ".
					  "sequence = '".($this->GetMaxSubsectionSequence($section_id) + 1)."'";
		$record_set = $this->_dbconn->Execute($sql_query);
	}

	/**
	* Get max sequence for info subsection by $section_id
	* @access public
	* @param integer $section_id - section ID
	* @return integer
	**/
	function GetMaxSubsectionSequence($section_id) {
		$sql_query = "SELECT MAX(sequence) AS max_sequence
					  FROM ".INFO_SUBSECTION_TABLE."
					  WHERE section_id = '$section_id'";
		$record_set = $this->_dbconn->Execute($sql_query);
		return $record_set->fields[0];
	}

	/**
	 * Get info subsection by id
	 *
	 * @access public
	 * @param integer $id
	 * @return void
	 */
	function GetSubsection($id) {
		$sql_query = "SELECT id, section_id, sequence, caption, content, description, keywords, status ".
					 "FROM ".INFO_SUBSECTION_TABLE." ".
					 "WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);
		$page = $record_set->GetRowAssoc(false);
		$page["caption"] = stripslashes($page["caption"]);
		$page["content"] = stripslashes($page["content"]);
		$page["description"] = stripslashes($page["description"]);
		$page["keywords"] = stripslashes($page["keywords"]);

		return $page;
	}

	/**
	 * Edit info subsection
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
	function EditSubsection($id, $caption, $content, $description, $keywords, $status) {
		$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET ".
					 "caption = '".addslashes($caption)."', content = '".addslashes($content)."', ".
					 "description = '".addslashes($description)."', keywords = '".addslashes($keywords)."', ".
					 "status = '$status' ".
				     "WHERE id = '$id'";
		$record_set = $this->_dbconn->Execute($sql_query);
	}

	/**
	 * Change subsection pages activity status for all pages of the section with $section_id
	 *
	 * @access public
	 * @param array $statuses - array of pages $id, which should to be active
	 * @param integer $section_id
	 * @return void
	 */
	function ChangeSubsectionStatus($statuses, $section_id) {
		/**
		 * make all pages for the section inactive
		 */
		$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET status = '0' ".
					 "WHERE section_id = '".$section_id."'";
		$record_set = $this->_dbconn->Execute($sql_query);
		foreach ($statuses as $id=>$value) {
			$this->SubsectionActivate($id);
		}
	}

	/**
	 * Activate subsection page
	 *
	 * @access public
	 * @param integer $id - subsection page id
	 * @return void
	 */
	function SubsectionActivate($id) {
		$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET status = '1' ".
					 "WHERE id = '".$id."'";
		$record_set = $this->_dbconn->Execute($sql_query);
	}

	/**
	 * Move info subsection page up on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @param integer $section_id - section page id
	 * @return void
	 */
	function UpSubsection($id, $section_id) {
		$page = $this->GetSubsection($id);

		$sql_query = "SELECT id, sequence FROM ".INFO_SUBSECTION_TABLE." ".
					 "WHERE sequence < '{$page["sequence"]}' AND sequence > '0' AND ".
					 "section_id = '{$page["section_id"]}' ".
					 "ORDER BY sequence DESC";
		$record_set = $this->_dbconn->Execute($sql_query);
		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Move info subsection page down on one position in sequence
	 *
	 * @access public
	 * @param integer $id - page id
	 * @param integer $section_id - section page id
	 * @return void
	 */
	function DownSubsection($id, $section_id) {
		$page = $this->GetSubsection($id);

		$sql_query = "SELECT id, sequence FROM ".INFO_SUBSECTION_TABLE." ".
					 "WHERE sequence > '{$page["sequence"]}' AND ".
					 "section_id = '{$page["section_id"]}' ".
					 "ORDER BY sequence ASC";
		$record_set = $this->_dbconn->Execute($sql_query);

		if ($record_set->RowCount() > 0) {
			$neighbour_page = $record_set->GetRowAssoc(false);

			$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET ".
			 			 "sequence = '{$neighbour_page["sequence"]}'".
					 	 "WHERE id = '$id'";
			$record_set = $this->_dbconn->Execute($sql_query);

			$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET ".
			 			 "sequence = '{$page["sequence"]}'".
					 	 "WHERE id = '{$neighbour_page["id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
		}
	}

	/**
	 * Delete subsection page by id
	 *
	 * @access public
	 * @param integer $id - page ID
	 * @param integer $section_id - section ID
	 * @return void
	 */
	function DeleteSubsection($id, $section_id) {
		/**
		 * change sequence for all other pages of the same section
		 */

		$page = $this->GetSubsection($id);
		if ($page["sequence"] < $this->GetMaxSubsectionSequence($section_id)) {
			$sql_query = "SELECT id FROM ".INFO_SUBSECTION_TABLE." ".
					 	 "WHERE sequence > '{$page["sequence"]}' AND ".
					 	 "section_id = '{$page["section_id"]}'";
			$record_set = $this->_dbconn->Execute($sql_query);
			while (!$record_set->EOF) {
				$sql_query = "UPDATE ".INFO_SUBSECTION_TABLE." SET ".
				 			 "sequence = sequence-1 ".
						 	 "WHERE id = '{$record_set->fields[0]}'";
				$this->_dbconn->Execute($sql_query);

				$record_set->MoveNext();
			}
		}
		/**
		 * delete page
		 */
		$sql_query = "DELETE FROM ".INFO_SUBSECTION_TABLE." WHERE id = '$id' ";
		$record_set = $this->_dbconn->Execute($sql_query);
	}

	/**
	 * Get Info Sections and subsections list for language and menu position
	 *
	 * @access public
	 * @param integer $language_id
	 * @param string $menu_position - menu position name
	 * @param boolean $only_active
	 * @return array
	 */
	function GetSectSubsecContent($language_id, $menu_position, $only_active = false) {
		$sql_query = "SELECT id, sequence, caption, status ".
					 "FROM ".INFO_SECTION_TABLE." ".
					 "WHERE language_id = '$language_id' AND menu_position = '$menu_position' ";
		$sql_query .= ($only_active) ? "AND status = '1' " : "";
		$sql_query .= "ORDER BY sequence";
		$record_set = $this->_dbconn->Execute($sql_query);
		$pages = array();
		while (!$record_set->EOF) {
			$page = $record_set->GetRowAssoc(false);
			$page["subsections"] = $this->GetSubsectionsList($page["id"], $only_active);
			$pages[] = $page;
			$record_set->MoveNext();
		}
		return $pages;
	}

}

?>