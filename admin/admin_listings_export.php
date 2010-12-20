<?php
/**

**/
include "../include/config.php";
include "../common.php";

include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";

include "../include/functions_xml.php";
include "../include/class.lang.php";
include "../include/class.object2xml.php";

define ("ZIF_FORMAT_URL", "http://www.zillow.com/howto/api/APIOverview.htm");
define ("TFF_FORMAT_URL", "http://developer.trulia.com/");
define ("HOTPADS_FORMAT_URL", "http://hotpads.com/pages/partners/feeds.htm");
define ("GOOGLE_FORMAT_URL", "http://base.google.com/support/bin/answer.py?answer=66779&hl=en_US");

$auth = auth_user();
$cur = GetSiteSettings('site_unit_costunit');
if ( (!($auth[0]>0))  || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );
$multi_lang = new MultiLang($config, $dbconn);

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

CreateMenu('admin_lang_menu');


$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$feed_type = (isset($_REQUEST["feed_type"])) ? intval($_REQUEST["feed_type"]) : 0;
/**
 * $feed_type
 * 		0	-	ZIF
 * 		1	-	TFF
 * 		2	-	Hotpads Feed
 * 		3	-	Google Data Feed
 */


if (isset($_REQUEST["err"]) && !empty($_REQUEST["err"])) {
	GetErrors($_REQUEST["err"]);
}

$smarty->assign("sel", $sel);

switch($sel){
	case "create_feed":
		EditFeedFile(intval($_REQUEST["feed_type"]), 0);// 0 - create mode
		break;
	case "update_feed":
		EditFeedFile(intval($_REQUEST["feed_type"]), 1);// 1 - update mode
		break;	
	default: ExportSettings($feed_type);
}

function ExportSettings($feed_type, $msg="") {
	global $smarty, $dbconn, $config, $lang;	

	if(isset($_SERVER["PHP_SELF"]))
		$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
		$file_name = "admin_listings_export.php";
	IndexAdminPage('admin_listings_export');

	if ($msg != ""){
		GetErrors($msg);
	}
	$feeds = array();
	$feeds["feed_key"] = array(0, 1, 2, 3);
	$feeds["url_read_more"] = array(ZIF_FORMAT_URL, TFF_FORMAT_URL, HOTPADS_FORMAT_URL, GOOGLE_FORMAT_URL);	
	$feeds["feed_shortname"] = array("zillow_feed", "trulia_feed", "hotpads_feed", "google_feed");	
	
	if (!file_exists(dirname(__FILE__)."/../include/feeds_export/".$feeds["feed_shortname"][$feed_type].".xml")){
		$smarty->assign("not_croned", 1);		
	}else{		
		$feeds["feed_url"] = $config["server"].$config["site_root"]."/include/feeds_export/".$feeds["feed_shortname"][$feed_type].".xml";
	}	
	$site_name = htmlspecialchars(GetSiteSettings("rss_site_name"));
	if (!$site_name){
		$site_name = preg_replace("/http:\/\//","",$config["server"]);
	}
	$title_rss = htmlspecialchars(GetSiteSettings("rss_title_for_export"));
	$description_rss = htmlspecialchars(GetSiteSettings("rss_description_for_export"));
	$title_ads_rss = htmlspecialchars(GetSiteSettings("rss_title_ads_for_export"));
	$description_ads_rss = htmlspecialchars(GetSiteSettings("rss_description_ads_for_export"));
	$form["action"] = 	$file_name;
	$smarty->assign("form", $form);
	$smarty->assign("feed_type", $feed_type);
	$smarty->assign("feeds", $feeds);
	$smarty->assign("site_name", $site_name);
	$smarty->assign("title_rss", $title_rss);
	$smarty->assign("description_rss", $description_rss);
	$smarty->assign("title_ads_rss", $title_ads_rss);
	$smarty->assign("description_ads_rss", $description_ads_rss);
	

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_ads_export_main.tpl");
	exit;
}

function EditFeedFile($feed_type, $mode){
	global $dbconn, $config;
	$feeds["feed_shortname"] = array("zillow_feed", "trulia_feed", "hotpads_feed", "google_feed");
				
	$good = isWriteable(dirname(__FILE__)."/../include/feeds_export/", 0777);				
	if (!$good){
		ExportSettings($feed_type, "need_perm_to_feed_folder");
	}
	$template_feed_xml = dirname(__FILE__)."/../include/feeds_export/".$feeds["feed_shortname"][$feed_type]."_template.xml";
	if (!file_exists($template_feed_xml)){
		ExportSettings($feed_type, "feed_template_none");
	}
	$file_feed_xml = dirname(__FILE__)."/../include/feeds_export/".$feeds["feed_shortname"][$feed_type].".xml";	
	
	$handle_xml = fopen($file_feed_xml, "w+");
	fclose($handle_xml);
	$good = isWriteable($file_feed_xml, 0777);				
	if (!$good){
		ExportSettings($feed_type, "need_perm_to_feed_file");
	}
	
	CreateFeedFile($template_feed_xml, $file_feed_xml, $feed_type);
	
	/*
	$feeds["fields"] = GetFieldsFromXMLtemplate($feeds["feed_shortname"][$feed_type]);
	if ($feeds["fields"] == "file_not_found"){
		ExportSettings($feed_type, "xml_template_not_found");
	}else{
		GetFieldsFromArray($feeds["fields"], 1, "", $feeds["obligatory_fields"]);
		GetFieldsFromArray($feeds["fields"], 0, "", $feeds["not_obligatory_fields"]);
	}*/
	ExportSettings($feed_type, "successful_creating");
}

function isWriteable($file, $mode){			
	//echo decoct(fileperms($file))."<br>";
	@chmod($file, $mode);			
	$good = is_writable($file) ? 1 : 0;
	return $good;
}

function EditXMLArray1(&$xml_root, $xml_array, &$children_xml){
	$children_num = 0;

	foreach ($xml_array AS $index=>$property){
		
		if($index == "Appliances"){
			$xml_root->children[$children_num] = new XmlNode($index, NULL, $children_xml, NULL);
			$xml_root->childrenCount++;
					
			foreach ($property AS $appliance){								
				EditXMLArray1($xml_root->children[$children_num], $appliance, $children_xml);
			}
			$children_num++;	
		}elseif($index == "Appliance"){
			$xml_root->children[$xml_root->childrenCount] = new XmlNode($index, NULL, NULL, $property);
			$xml_root->childrenCount++;
		}elseif ($index == "Picture" || $index == "picture"){					
			foreach ($property AS $picture){		
				$xml_root->children[$children_num] = new XmlNode($index, NULL, $children_xml, NULL);
				$xml_root->childrenCount++;						
				EditXMLArray1($xml_root->children[$children_num], $picture, $children_xml);
				$children_num++;
			}				
		}elseif ($index == "g:image_link"){
			foreach ($property AS $picture){
				$xml_root->children[$children_num] = new XmlNode($index, NULL, NULL, $picture);
				$xml_root->childrenCount++;	
				$children_num++;
			}
		}
		else{
			if (is_array($property)){
				$xml_root->children[$children_num] = new XmlNode($index, NULL, $children_xml, NULL);
				$xml_root->childrenCount++;
				EditXMLArray1($xml_root->children[$children_num], $property, $children_xml);
				$children_num++;
			}else{				
				$xml_root->children[$children_num] = new XmlNode($index, NULL, NULL, $property);
				$xml_root->childrenCount++;			
				$children_num++;	
			}
		}
	}
}

function EditXMLArray2(&$xml_root, $xml_array, &$children_xml){
	$children_num = 0;

	foreach ($xml_array AS $index=>$property){
		if ($index == "xml_properties"){
			foreach ($property AS $ind=>$item){
				$xml_root->attrs[$ind] = $item;
			}
		}elseif($index == "xml_value"){
			$xml_root->value = $property;
		}elseif($index == "ListingTag"){
			foreach ($property AS $listing_tag_type=>$listing_tag){
				foreach ($listing_tag AS $listing_tag_value){
					$xml_root->children[$children_num] = new XmlNode($index, NULL, $children_xml, NULL);
					$xml_root->children[$children_num]->attrs["type"] = $listing_tag_type;
					EditXMLArray2($xml_root->children[$children_num], $listing_tag_value, $children_xml);
					$children_num++;
					$xml_root->childrenCount++;
				}
			}
			$xml_root->childrenCount--;
			
		}elseif($index == "ListingPhoto"){
			foreach ($property AS $photo){
					$xml_root->children[$children_num] = new XmlNode($index, NULL, $children_xml, NULL);					
					EditXMLArray2($xml_root->children[$children_num], $photo, $children_xml);
					$children_num++;	
					$xml_root->childrenCount++;			
			}
			$xml_root->childrenCount--;
		}else{
			
			$xml_root->children[$children_num] = new XmlNode($index, NULL, $children_xml, NULL);
			$xml_root->childrenCount++;
			EditXMLArray2($xml_root->children[$children_num], $property, $children_xml);
			$children_num++;
		}
	}
}

function CreateFeedFile($template_feed_xml, $file_feed_xml, $feed_type){
	global $config, $dbconn;
	$xml_parser_template = new SimpleXmlParser( $template_feed_xml );
	$xml_root_template = $xml_parser_template->getRoot();		
			
	$xml_root = new XmlNode($xml_root_template->tag, NULL, $xml_root_template->children, NULL);
	$company_id = "";
	if (isset($_REQUEST["site_name"])){
		$rss_site_name = addslashes(strip_tags($_REQUEST["site_name"]));
		$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '$rss_site_name' WHERE name = 'rss_site_name'";
		$dbconn->Execute($strSQL);
	}
	if ($feed_type == 2){
		$strSQL = "SELECT pic_{$config["lang_code"]} FROM ".LOGO_SETTINGS_TABLE." WHERE type='logotype'";
		$rs = $dbconn->Execute($strSQL);
		$site_name = htmlspecialchars($_GET["site_name"]);
		
		$company["xml_properties"]["id"] = "company_".$site_name;
		$company_id = $company["xml_properties"]["id"];
		$company["name"]["xml_value"] = htmlspecialchars(GetSiteSettings("rss_site_name"));;
		$company["website"]["xml_value"] = $config["server"].$config["site_root"];
		$company["CompanyLogo"]["xml_properties"]["source"] = $config["server"].$config["site_root"].$config["index_theme_path"].$config["index_theme_images_path"]."/".$rs->fields[0];	
		
		$xml_root = new XmlNode($xml_root_template->tag, $xml_root_template->attrs, $xml_root_template->children, NULL);
	
		EditXMLArray2($xml_root->children[0], $company, $xml_root_template->children[0]->children) ;		
		
	}elseif ($feed_type == 3){
		$site_name = htmlspecialchars(GetSiteSettings("rss_site_name"));;
		$xml_root = new XmlNode($xml_root_template->tag, $xml_root_template->attrs, $xml_root_template->children, NULL);
		$xml_root->children[0] = new XmlNode($xml_root_template->children[0]->tag, NULL, $xml_root_template->children, NULL);
		$rss_header["title"]["xml_value"] = addslashes(strip_tags(($_POST["title_rss"])));		
		$rss_header["description"]["xml_value"] = addslashes(strip_tags(($_POST["description_rss"])));
		if ($rss_header["title"]["xml_value"] == "" || $rss_header["description"]["xml_value"] == "" ||
			strlen($_POST["title_ads_rss"]) == 0 || strlen($_POST["description_ads_rss"]) == 0){
			ExportSettings($feed_type, "incorrect_rss_field");
		}elseif (strlen($rss_header["title"]["xml_value"]) > 80){
			$rss_header["title"]["xml_value"] = substr($rss_header["title"]["xml_value"], 0, 80);
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '{$rss_header["title"]["xml_value"]}' WHERE name = 'rss_title_for_export'";
			$dbconn->Execute($strSQL);
			ExportSettings($feed_type, "incorrect_rss_field");
		}elseif (strlen($_POST["title_ads_rss"]) > 80){
			$title_ads_rss = substr($_POST["title_ads_rss"], 0, 80);
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '$title_ads_rss' WHERE name = 'rss_title_ads_for_export'";
			$dbconn->Execute($strSQL);
			ExportSettings($feed_type, "incorrect_rss_field");
		}else{			
			$title_ads_rss = addslashes(strip_tags(substr($_POST["title_ads_rss"], 0, 80)));
			$description_ads_rss = addslashes(strip_tags($_POST["description_ads_rss"]));
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '{$rss_header["title"]["xml_value"]}' WHERE name = 'rss_title_for_export'";
			$dbconn->Execute($strSQL);
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '{$rss_header["description"]["xml_value"]}' WHERE name = 'rss_description_for_export'";
			$dbconn->Execute($strSQL);
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '$title_ads_rss' WHERE name = 'rss_title_ads_for_export'";
			$dbconn->Execute($strSQL);
			$strSQL = "UPDATE ".SETTINGS_TABLE." SET value = '$description_ads_rss' WHERE name = 'rss_description_ads_for_export'";
			$dbconn->Execute($strSQL);
		}
		$rss_header["link"]["xml_value"] = $config["server"].$config["site_root"];
				
		EditXMLArray2($xml_root->children[0], $rss_header, $xml_root_template->children[0]->children[0]->children) ;
	}
	
	$listings = GetListings($feed_type, $company_id);

	$i = 0;
	switch ($feed_type){
		case 0:
		case 1:	
			foreach ($listings AS $listing){
				$xml_root->children[$i] = new XmlNode($xml_root_template->children[0]->tag,NULL, $xml_root_template->children, NULL);
				EditXMLArray1($xml_root->children[$i], $listing, $xml_root_template->children[0]->children) ;	
				$i++;
			}
		break;
		case 2:
			foreach ($listings AS $listing){
				
				$xml_root->children[$i+1] = new XmlNode($xml_root_template->children[1]->tag, NULL, $xml_root_template->children, NULL);
				
				EditXMLArray2($xml_root->children[$i+1], $listing, $xml_root_template->children[1]->children) ;	
				$i++;
			}			
		break;	
		case 3:
			foreach ($listings AS $listing){
				
				$xml_root->children[0]->children[$i+3] = new XmlNode($xml_root_template->children[0]->children[0]->tag, NULL, $xml_root_template->children[0]->children[0]->children, NULL);
				
				EditXMLArray1($xml_root->children[0]->children[$i+3], $listing, $xml_root_template->children[0]->children[0]->children) ;	
				$i++;
			}			
		break;	
		
	}

	$obj_saver = new Object2Xml();
	$obj_saver->Save( $xml_root, $file_feed_xml);	
}

function GetListings($feed_type, $company_id){
	global $dbconn, $config, $REFERENCES, $smarty, $multi_lang;
	
	$listings = array();
	$used_references = array("info", "period", "realty_type", "description");
	foreach ($REFERENCES as $arr) {
		if (in_array($arr["key"], $used_references)) {
			$spr_order = ($arr["key"] == "description") ? "id" : "name";
			$all_references[$arr["key"]] = GetReferenceArray($arr["spr_table"], $arr["val_table"], $arr["key"], '', 1, $spr_order);
			
		}
	}
/*echo "<pre>";
print_r($all_references);
echo "</pre>";	*/
	$strSQL = "SELECT ra.id, ra.id_user, ra.type, ra.status, ra.movedate, ra.comment, ra.headline, ra.sold_leased_status,	  
					  ut.fname AS agent_fname, ut.sname AS agent_sname, ut.email AS agent_email, ut.phone AS agent_phone,
					  urdt.company_name,
					  urlt.zip_code, urlt.street_1, urlt.street_2, urlt.adress AS address,
					  regt.id AS region_id,
					  regabt.region_abb, 
					  ct.name AS country, regt.name AS state, citt.name AS city, citt.lat, citt.lon,
					  urpt.min_payment AS price, urpt.min_live_square AS living_area,
					  urpt.min_floor AS num_floors,urpt.min_land_square AS lot_size, urpt.min_year_build
						FROM ".RENT_ADS_TABLE." ra 
						LEFT JOIN ".USERS_TABLE." ut ON ut.id = ra.id_user 
						LEFT JOIN ".USER_REG_DATA_TABLE." urdt ON urdt.id_user = ra.id_user 
						LEFT JOIN ".USERS_RENT_LOCATION_TABLE." urlt ON ra.id = urlt.id_ad 
						LEFT JOIN ".COUNTRY_TABLE." ct ON urlt.id_country = ct.id 
						LEFT JOIN ".REGION_TABLE." regt ON urlt.id_region = regt.id 
						LEFT JOIN ".REGION_ABB_TABLE." regabt ON regt.id = regabt.id_region 
						LEFT JOIN ".CITY_TABLE." citt ON urlt.id_city = citt.id 
						LEFT JOIN ".USERS_RENT_PAYS_TABLE." urpt ON urpt.id_ad = ra.id 
						WHERE ra.status = '1'";
	switch ($feed_type){
		case 0:
			$strSQL .= " AND urlt.zip_code > 9999 AND urlt.zip_code < 100000 AND ra.type = '4' AND urpt.min_payment > 0";
			break;
		case 1:
			$strSQL .= " AND urlt.zip_code > 9999 AND urlt.zip_code < 100000 AND ra.type = '4' OR ra.type = '2' AND urpt.min_payment > 0";
			break;
		case 2:
			$strSQL .= " AND ra.type = '4' OR ra.type = '2' AND urpt.min_payment > 0";
			break;
		case 3:
			$strSQL .= " AND ra.type = '4' OR ra.type = '2' AND urpt.min_payment > 0";
			break;			
	}
	$rs = $dbconn->Execute($strSQL);
	while(!$rs->EOF){
		$row = $rs->getRowAssoc(false);
		$used_references = array("info", "period", "realty_type", "description");
		$listing = array();
		foreach ($REFERENCES as $arr) {
			if (in_array($arr["key"], $used_references)) {
				$data[$arr["key"]] = SprTableSelect($arr["spr_user_table"], $row["id"], $row["id_user"], $arr["spr_table"]);
			}
		}
//		echo "<pre>";
//print_r($data);
//echo "</pre>";
		switch ($feed_type){
			case 0:
				$listing["Location"]["StreetAddress"] = htmlspecialchars($row["address"]);
				$listing["Location"]["City"] = $row["city"];
				$listing["Location"]["State"] = $row["region_abb"];
				$listing["Location"]["Country"] = $row["country"];
				$listing["Location"]["Zip"] = $row["zip_code"];
				if ($row["lat"]){
					$listing["Location"]["Lat"] = $row["lat"];
				}
				if ($row["lon"]){
					$listing["Location"]["Long"] = $row["lon"];
				}
				if ($row["street_1"] != "" && $row["street_2"] !=""){
					$listing["Location"]["StreetIntersection"] = htmlspecialchars($row["street_1"])." + ".htmlspecialchars($row["street_2"]);
				}
				$listing["Location"]["DisplayAddress"] = "Yes";
				$listing["ListingDetails"]["Status"] = ($row["sold_leased_status"]) ? "Sold" : "Active";
				$listing["ListingDetails"]["Price"] = $row["price"];
				$listing["ListingDetails"]["ListingUrl"] = $config["server"].$config["site_root"]."/viewprofile.php?id=".$row["id"];
				unset($value_arr);
				foreach ($data["realty_type"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}	
				$listing["BasicDetails"]["PropertyType"] = GetPropertyTypeForFeed($value_arr, $feed_type, "realty_type", 2);
				
				$listing["BasicDetails"]["Title"] = htmlspecialchars($row["headline"]);
				$listing["BasicDetails"]["Description"] = htmlspecialchars($row["comment"]);
				
				unset($value_arr);
				foreach ($data["description"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}			
				if (isset($value_arr)){
					$listing["BasicDetails"]["Bedrooms"] = GetPropertyTypeForFeed($value_arr, $feed_type, "description", 1);
					$listing["BasicDetails"]["Bathrooms"] = GetPropertyTypeForFeed($value_arr, $feed_type, "description", 2);
					$listing["BasicDetails"]["FullBathrooms"] = $listing["BasicDetails"]["Bathrooms"];
				}
				if ($row["living_area"]){		
					$listing["ListingDetails"]["LivingArea"] = $row["living_area"];		
				}
				if ($row["lot_size"]){
					$listing["ListingDetails"]["LotSize"] = round($row["lot_size"]/43560, 2);//from f^2 to acres	
				}
				if ($row["min_year_build"]){
					$listing["ListingDetails"]["YearBuilt"] = $row["min_year_build"];
				}
				$listing["Pictures"]["Picture"] = GetPhotoForFeed($row["id"], $feed_type);
				
				$listing["Agent"]["FirstName"] = htmlspecialchars($row["agent_fname"]);
				$listing["Agent"]["LastName"] = htmlspecialchars($row["agent_sname"]);
				$listing["Agent"]["EmailAddress"] = $row["agent_email"];
				$listing["Agent"]["OfficeLineNumber"] = GetPhoneFormated($row["agent_phone"]);
				$listing["Office"]["BrokerageName"] = htmlspecialchars($row["company_name"]);
				//Appliances
				$additional_features = array();
				$listing["RichDetails"]["Appliances"] = array();
				$i=0;
				foreach ($data["info"] AS $item){
					foreach ($item AS $subitem){
						$appliance = GetPropertyTypeForFeed(array($subitem), $feed_type, "info", 16);
						if ($appliance == ""){				
							$additional_features[] = $subitem;
						}else{
							$listing["RichDetails"]["Appliances"][]["Appliance"] = $appliance;
							$i++;
						}							
					}
				}	
				
				
				$listing["RichDetails"]["AdditionalFeatures"] = "";
				foreach ($all_references["info"][2]["opt"] AS $spr_value){
					if (in_array($spr_value["value"], $additional_features)){
						$listing["RichDetails"]["AdditionalFeatures"][] = $spr_value["name"];
					}
				}	
				if ($listing["RichDetails"]["AdditionalFeatures"]){
					$listing["RichDetails"]["AdditionalFeatures"] = implode(",", $listing["RichDetails"]["AdditionalFeatures"]);
				}
				
				unset($value_arr);
				foreach ($data["info"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}			
				if (isset($value_arr)){
					$listing["RichDetails"]["Other"] = GetPropertyTypeForFeed($value_arr, $feed_type, "info", 15);		
					if (is_array($listing["RichDetails"]["Other"])){
						foreach ($listing["RichDetails"]["Other"] AS $rich_detail){
							$listing["RichDetails"][$rich_detail] = "Yes";
						}
					}elseif ($listing["RichDetails"]["Other"] != ""){
						$listing["RichDetails"][$listing["RichDetails"]["Other"]] = "Yes";
					}
				}		
				unset($listing["RichDetails"]["Other"]);
			break;	
				/////TRULIA/////
			case 1:
				$listing["location"]["street-address"] = htmlspecialchars($row["address"]);
				$listing["location"]["city-name"] = $row["city"];
				$listing["location"]["state-code"] = $row["region_abb"];
				$listing["location"]["zipcode"] = $row["zip_code"];
				if ($row["lat"]){
					$listing["location"]["latitude"] = $row["lat"];
				}
				if ($row["lon"]){
					$listing["location"]["longitude"] = $row["lon"];
				}
				$listing["location"]["display-address"] = "Yes";
				
				$listing["details"]["price"] = $row["price"];
				if ($row["min_year_build"]){
					$listing["details"]["year-built"] = $row["min_year_build"];
				}
				
				unset($value_arr);
				
				foreach ($data["description"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}			
				if (isset($value_arr)){
					$listing["details"]["num-bedrooms"] = GetPropertyTypeForFeed($value_arr, 0, "description", 1);
					$listing["details"]["num-bathrooms"] = GetPropertyTypeForFeed($value_arr, 0, "description", 2);
				}
				if ($row["lot_size"]){
					$listing["details"]["lot-size"] = round($row["lot_size"]/43560, 2);//from f^2 to acres	
				}
				if ($row["living_area"]){
					$listing["details"]["square-feet"] = $row["living_area"];	
				}
				
				unset($value_arr);
				
				foreach ($data["realty_type"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}		
				if (isset($value_arr)){
					$listing["details"]["property-type"] = GetPropertyTypeForFeed($value_arr, $feed_type, "realty_type", 2);
				}else{
					$listing["details"]["property-type"] = "Other";
				}
				$listing["details"]["description"] = htmlspecialchars($row["comment"]);
				
				$listing["landing-page"]["lp-url"] = $config["server"].$config["site_root"]."/viewprofile.php?id=".$row["id"];
				
				$listing["status"] = ($row["sold_leased_status"]) ? "Sold" : (($row["type"] == 4) ? "For Sale" : "For Rent");
				
				$listing["site"]["site-url"] = $config["server"].$config["site_root"]."/index.php";
				$listing["site"]["site-name"] = htmlspecialchars(GetSiteSettings("rss_site_name"));
				$listing["pictures"]["picture"] = GetPhotoForFeed($row["id"], $feed_type);
				
				unset($value_arr);
				foreach ($data["realty_type"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}				
				
				
				$listing["agent"]["agent-name"] = htmlspecialchars($row["agent_fname"]." ".$row["agent_sname"]);				
				$listing["agent"]["agent-email"] = $row["agent_email"];
				$listing["agent"]["agent-phone"] = GetPhoneFormated($row["agent_phone"]);
				$listing["office"]["office-name"] = htmlspecialchars($row["company_name"]);
				
				break;
			case 2:
				$listing["xml_properties"]["id"] = "id_number_".$row["id"];
				$listing["xml_properties"]["type"] = ($row["type"] == '2') ? "RENTAL" : "SALE";
				$listing["xml_properties"]["companyId"] = $company_id;
				unset($value_arr);
				
				foreach ($data["realty_type"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}	
				if (isset($value_arr)){
					$listing["xml_properties"]["propertyType"] = GetPropertyTypeForFeed($value_arr, $feed_type, "realty_type", 2);
				}else{
					$listing["xml_properties"]["propertyType"] = "OTHER";
				}
				if ($listing["xml_properties"]["propertyType"] == ""){
					$listing["xml_properties"]["propertyType"] = "OTHER";
				}
				if ($row["headline"]){
					$listing["name"]["xml_value"] = htmlspecialchars($row["headline"]);
				}
				$listing["street"]["xml_value"] = htmlspecialchars($row["address"]);
				$listing["street"]["xml_properties"]["hide"] = "false";
				$listing["city"]["xml_value"] = $row["city"];
				$listing["state"]["xml_value"] = $row["region_abb"];
				$listing["zip"]["xml_value"] = $row["zip_code"];
				$listing["country"]["xml_value"] = "US";
				$listing["latitude"]["xml_value"] = $row["lat"];
				$listing["longitude"]["xml_value"] = $row["lon"];
				$listing["contactName"]["xml_value"] = htmlspecialchars($row["agent_fname"]." ".$row["agent_sname"]);
				$listing["contactEmail"]["xml_value"] = $row["agent_email"];
				$listing["contactPhone"]["xml_value"] = $row["agent_phone"];
				if ($row["headline"]){
					$listing["previewMessage"]["xml_value"] = htmlspecialchars($row["headline"]);
				}
				if ($row["comment"]){
					$listing["description"]["xml_value"] = htmlspecialchars($row["comment"]);
				}
				$listing["website"]["xml_value"] = $config["server"].$config["site_root"]."/viewprofile.php?id=".$row["id"];
				
				
				
				foreach ($all_references["info"] AS $item){
					if ($item["name"] == "Accomodations"){
						foreach ($item["opt"] AS $subitem){
							$value_spr["PROPERTY_AMENITY"][$subitem["value"]] = $subitem["name"];
						}
					}
					if ($item["name"] == "Appliances"){
						foreach ($item["opt"] AS $subitem){
							$value_spr["MODEL_AMENITY"][$subitem["value"]] = $subitem["name"];
						}
					}
				}
				$i1 =0 ;$i2 =0 ;
				foreach ($data["info"] AS $item){
					foreach ($item AS $subitem){
						if (isset($value_spr["PROPERTY_AMENITY"][$subitem])){
							$listing["ListingTag"]["PROPERTY_AMENITY"][$i1++]["tag"]["xml_value"] = $value_spr["PROPERTY_AMENITY"][$subitem];
						}
						if (isset($value_spr["MODEL_AMENITY"][$subitem])){
							$listing["ListingTag"]["MODEL_AMENITY"][$i2++]["tag"]["xml_value"] = $value_spr["MODEL_AMENITY"][$subitem];
						}
					}
				}	
				if ($row["min_year_build"]){
					$listing["ListingTag"]["YEAR_BUILT"][0]["tag"]["xml_value"] = $row["min_year_build"];
				}
				if ($row["lot_size"]){
					$listing["ListingTag"]["LOT_SIZE"][0]["tag"]["xml_value"] = round($row["lot_size"]/43560, 2);//from f^2 to acres	
				}
				$photos = GetPhotoForFeed($row["id"], $feed_type);
				$i =0 ;
				foreach ($photos AS $photo){
					$listing["ListingPhoto"][$i]["xml_properties"]["source"] = $photo["source"];
					if ($photo["caption"] != ""){
						$listing["ListingPhoto"][$i]["caption"]["xml_value"] = $photo["caption"];
					}
					$i++;
				}
				$listing["Model"]["xml_properties"]["id"] = "m_ads_id".$row["id"];
				$listing["Model"]["xml_properties"]["searchable"] = "true";
				$listing["Model"]["xml_properties"]["pricingType"] = "FLAT";
				$listing["Model"]["lowPrice"]["xml_value"] = $row["price"];
				$listing["Model"]["highPrice"]["xml_value"] = $row["price"];
				
				unset($value_arr);
				
				foreach ($data["description"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}			
				if (isset($value_arr)){
					$listing["Model"]["numBedrooms"]["xml_value"] = GetPropertyTypeForFeed($value_arr, 0, "description", 1);
					$listing["Model"]["numFullBaths"]["xml_value"] = GetPropertyTypeForFeed($value_arr, 0, "description", 2);
				}
				if ($row["living_area"]){
					$listing["Model"]["squareFeet"]["xml_value"] = $row["living_area"];
				}
				
				
				break;
			case 3:
				$listing["title"] = ($row["headline"]) ? htmlspecialchars($row["headline"]) : GetSiteSettings("rss_title_ads_for_export");
				$listing["description"] = ($row["comment"]) ? htmlspecialchars($row["comment"]) : GetSiteSettings("rss_description_ads_for_export");
				$listing["link"] = $config["server"].$config["site_root"]."/viewprofile.php?id=".$row["id"];
				$listing["g:listing_type"] = ($row["type"] == '2') ? "for rent" : "for sale";
				$listing["g:location"] = "";
				if ($row["address"]) {
					$listing["g:location"] .= htmlspecialchars($row["address"]);
				}
				if ($row["city"]) {
					$listing["g:location"] .= ", ".$row["city"];
				}
				if ($row["region_abb"]) {
					$listing["g:location"] .= ", ".$row["region_abb"];
				}elseif ($row["state"]){
					$listing["g:location"] .= ", ".$row["state"];
					}				
				if ($row["zip_code"]) {
					$listing["g:location"] .= ", ".$row["zip_code"];
				}
				if ($row["country"]) {
					$listing["g:location"] .= ", ".$row["country"];
				}
				$listing["g:price"] = $row["price"];
				unset($value_arr);
				
				foreach ($data["description"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}		
				if (isset($value_arr)){
					$listing["g:bedrooms"] = GetPropertyTypeForFeed($value_arr, 0, "description", 1);
					$listing["g:bathrooms"] = GetPropertyTypeForFeed($value_arr, 0, "description", 2);
				}
				if ($row["company_name"]){
					$listing["g:broker"] = htmlspecialchars($row["company_name"]);
				}
				$listing["g:id"] = "id_number_".$row["id"];
				$listing["g:image_link"] = GetPhotoForFeed($row["id"], $feed_type);
				$listing["g:listing_status"] = "active";
				
				unset($value_arr);
				
				foreach ($data["realty_type"] AS $item){
					foreach ($item AS $subitem){
						$value_arr[] = $subitem;
					}
				}		
				if (isset($value_arr)){
					$listing["g:property_type"] = GetPropertyTypeForFeed($value_arr, $feed_type, "realty_type", 2);
				}else{
					$listing["g:property_type"] = "other";
				}
				if ($listing["g:property_type"] == ""){
					$listing["g:property_type"] = "other";
				}
				
				$listing["g:year"] = $row["min_year_build"];
				
				
				if ($row["agent_fname"] || $row["agent_sname"]){
					$listing["g:agent"] = htmlspecialchars($row["agent_fname"]." ".$row["agent_sname"]);				
				}
				if ($row["living_area"]){
					$listing["g:area"] = $row["living_area"]." square ft.";
				}
				if ($row["lot_size"]){
					$listing["g:lot_size"] = $row["lot_size"]." square ft.";
				}
				
				break;			
		}

		$rs->MoveNext();
		$listings[] = $listing;				
	}	
	return $listings;
}


/*
10 => Apartment		11 => Apartment Complex		33 => Beach Property		29 => Bed and Breakfast
14 => Bungalow		39 => Business				18 => Cabin/Cottage			21 => Castle
22 => Chateau		34 => Coastal Property		37 => Commercial Property	25 => Condo
26 => Condo Hotel	12 => Duplex				24 => Farm/Ranch			17 => Guest House
30 => Hotel			19 => House					27 => Inn/Lodge				48 => Island
36 => Loft			31 => Lots/Land				20 => Mansion				41 => Manufactured Home
35 => Marina		 8 => Mobile Home			28 => Motel					38 => Office Space
49 => Other			32 => Plantation			46 => Resort				43 => Restaurant
 9 => Room			16 => Single Family Home	40 => Store Front			15 => Townhouse
13 => Triplex		47 => Very Exotic Property	23 => Villa					45 => Vineyard
42 => Warehouse		44 => Winery
*/
function GetPropertyTypeForFeed($property_type_RE_arr, $feed_type, $reference, $subreference){
	global $dbconn, $config;
	
	$i = 0;
	
	$strSQL = "SELECT re_spr_value, feed_str_id FROM ".EXPORT_SPR_TABLE." WHERE
				feed_type = '$feed_type' AND reference='$reference' AND subreference='$subreference'";
	$rs = $dbconn->Execute($strSQL);
	foreach ($property_type_RE_arr AS $property_type_RE){				
		while (!$rs->EOF){
			if (in_array($property_type_RE, explode(",",$rs->fields[0]))){
				$feed_str_id = $rs->fields[1];
				break;
			}
			$rs->MoveNext();
		}
		if (isset($feed_str_id)){
			$strSQL_2 = "SELECT feed_str FROM ".EXPORT_FIELDS_TABLE." WHERE id = '$feed_str_id'";													$rs_2 = $dbconn->Execute($strSQL_2);
			$return_arr[$i] = $rs_2->fields[0];
			$i++;
			unset($feed_str_id);
		}else{					
		}
		$rs = $dbconn->Execute($strSQL);
	}			
	
	if ($i == 0){
		return "";
	}
	elseif ($i == 1){
		return $return_arr[0];
	}else{
		return $return_arr;
	}
}

function GetPhotoForFeed($id_ad, $feed_type){
	global $dbconn, $config;
	
	$photo_folder = GetSiteSettings('photo_folder');
	$images = array();
	$strSQL_img = "SELECT id as photo_id, upload_path, user_comment FROM ".USERS_RENT_UPLOADS_TABLE." WHERE id_ad='$id_ad' AND upload_type='f' AND admin_approve = '1' AND status='1' ORDER BY id";
	if ($feed_type == 3){
		$strSQL_img .= " LIMIT 10";
	}
	$rs_img = $dbconn->Execute($strSQL_img);	
	$i = 0;
	while(!$rs_img->EOF){
		$row_img = $rs_img->GetRowAssoc(false);
		$path = $config["site_path"].$photo_folder."/".$row_img["upload_path"];
		if (file_exists($path)){
			switch ($feed_type){
				case 0:
					$images[$i]["PictureUrl"] = $config["server"].$config["site_root"].$photo_folder."/".$row_img["upload_path"];
					$images[$i]["Caption"] = $row_img["user_comment"];
				break;
				case 1:
					$images[$i]["picture-url"] = $config["server"].$config["site_root"].$photo_folder."/".$row_img["upload_path"];
				break;
				case 2:
					$images[$i]["source"] = $config["server"].$config["site_root"].$photo_folder."/".$row_img["upload_path"];
					$images[$i]["caption"] = substr($row_img["user_comment"], 0, 60);
				break;
				case 3:
					$images[$i]= $config["server"].$config["site_root"].$photo_folder."/".$row_img["upload_path"];	
				break;
			}
			
			$i++;
		}		
		$rs_img->MoveNext();		
	}
	return $images;	
}

function GetPhoneFormated($phone){
	$phone = preg_replace("/[()\s-\*_]/","", $phone);
	if (strlen($phone) == 10){
		$phone = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/","\\1-\\2-\\3", $phone);
	}else{
		$phone = "";
	}
	return $phone;
}
?>

                               

