<?php
/**
* Contain constants (database tables names with prefix, references array, etc)
* @package RealEstate
* @subpackage
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

define("_OPERATOR", "_operator");
define("_TARIF", "_tarif");

//ARRAY WITH TABLE NAMES FOR WORK WITH REFERENCES
$REFERENCES = array (
		array("key" => "info", "spr_table" => SPR_APARTMENT_TABLE, "spr_user_table" => SPR_RENT_APARTMENT_USER_TABLE, "spr_match_table" => "", "val_table" => VALUES_APARTMENT_TABLE),
		array("key" => "gender", "spr_table" => SPR_GENDER_TABLE, "spr_user_table" => SPR_RENT_GENDER_USER_TABLE, "spr_match_table" => SPR_RENT_GENDER_MATCH_TABLE, "val_table" => VALUES_GENDER_TABLE),
		array("key" => "people", "spr_table" => SPR_PEOPLE_TABLE, "spr_user_table" => SPR_RENT_PEOPLE_USER_TABLE, "spr_match_table" => SPR_RENT_PEOPLE_MATCH_TABLE, "val_table" => VALUES_PEOPLE_TABLE),
		array("key" => "language", "spr_table" => SPR_LANGUAGE_TABLE, "spr_user_table" => SPR_RENT_LANGUAGE_USER_TABLE, "spr_match_table" => SPR_RENT_LANGUAGE_MATCH_TABLE, "val_table" => VALUES_LANGUAGE_TABLE),
    array("key" => "rest", "spr_table" => SPR_THEME_REST_TABLE, "spr_user_table" => SPR_RENT_REST_USER_TABLE, "spr_match_table" => SPR_RENT_REST_MATCH_TABLE, "val_table" => VALUES_THEME_REST_TABLE),
		array("key" => "period", "spr_table" => SPR_PERIOD_TABLE, "spr_user_table" => SPR_RENT_PERIOD_USER_TABLE, "spr_match_table" => "", "val_table" => VALUES_PERIOD_TABLE),
		array("key" => "realty_type", "spr_table" => SPR_TYPE_TABLE, "spr_user_table" => SPR_RENT_TYPE_USER_TABLE, "spr_match_table" => "", "val_table" => VALUES_TYPE_TABLE),
		array("key" => "description", "spr_table" => SPR_DESCRIPTION_TABLE, "spr_user_table" => SPR_RENT_DESCRIPTION_USER_TABLE, "spr_match_table" => "", "val_table" => VALUES_DESCRIPTION_TABLE),
    array("key" => "theme_rest", "spr_table" => SPR_THEME_REST_TABLE, "spr_user_table" => SPR_THEME_REST_USER_TABLE, "spr_match_table" => "", "val_table" => VALUES_THEME_REST_TABLE)
	);

$VIDEO_TYPE_ARRAY = array("video/mpeg", "video/avi", "video/x-ms-asf", "video/x-ms-wmv", "video/x-msvideo");
$VIDEO_EXT_ARRAY = array("mpeg", "mpg", "avi", "asf", "wmv");

$fonts["arial"] = "Arial";	
$fonts["tahoma"] = "Tahoma";
$fonts["verdana"] = "Verdana";
$fonts_size = array(8,9,10,11,12,14,16,18,20);

?>