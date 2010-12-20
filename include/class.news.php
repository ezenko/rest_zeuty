<?php
/**
* A functions collection (not class) for working with rss news
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: aknyazev $
* @version $Revision: 1.5 $ $Date: 2009/01/16 13:06:55 $
**/
include dirname(__FILE__)."/Snoopy.class.php";
include dirname(__FILE__)."/class.utf8.php";

global $rss2array_globals, $item_limit, $snoopy;

$snoopy = new Snoopy;
$snoopy->read_timeout = 5;

function rss2array($url, $news_limit=false) {
    global $rss2array_globals, $item_limit, $snoopy;

    $rss2array_globals = array();
    $item_limit = $news_limit;

    if(preg_match("/^http:\/\/([^\/]+)(.*)$/", $url, $matches)){

        @$snoopy->fetch($url);
        $xml = @$snoopy->results;
        
        if (preg_match('/windows\-1251/i', $xml)) {
			$xml = preg_replace('/windows\-1251/i', 'utf-8', $xml);
			$obj_utf8 = new utf8(CP1251);
			$xml = $obj_utf8->strToUtf8($xml);			
        }
        $header = @$snoopy->response_code;
        $rss_timeout = @$snoopy->timed_out;

        if(!$rss_timeout){
            if(preg_match("/200/",$header)){

                $xml_parser = xml_parser_create();

                xml_set_element_handler($xml_parser, "startElement", "endElement");

                xml_set_character_data_handler($xml_parser, "characterData");

                xml_parse($xml_parser, trim($xml), true) or $rss2array_globals[errors][] = xml_error_string(xml_get_error_code($xml_parser)) . " at line " . xml_get_current_line_number($xml_parser);

                xml_parser_free($xml_parser);

             }else{
                $rss2array_globals[errors][] = "Can't get feed: HTTP status code $status";
             }

        } else {
            $rss2array_globals[errors][] = "Can't connect to $host";
        }

    }else {
        $rss2array_globals[errors][] = "Invalid url: $url";
    }

    // unset all the working vars
    unset($rss2array_globals[channel_title]);
    unset($rss2array_globals[channel_link]);
    unset($rss2array_globals[channel_description]);

    unset($rss2array_globals[image_title]);
    unset($rss2array_globals[image_link]);
    unset($rss2array_globals[image_url]);
    unset($rss2array_globals[image_width]);
    unset($rss2array_globals[image_height]);

    unset($rss2array_globals[inside_rdf]);
    unset($rss2array_globals[inside_rss]);
    unset($rss2array_globals[inside_channel]);
    unset($rss2array_globals[inside_item]);
    unset($rss2array_globals[inside_image]);

    unset($rss2array_globals[current_tag]);
    unset($rss2array_globals[current_title]);
    unset($rss2array_globals[current_link]);
    unset($rss2array_globals[current_description]);
    unset($rss2array_globals[current_content]);
    unset($rss2array_globals[current_pubdate]);

    return $rss2array_globals;

}

// this function will be called everytime a tag starts
function startElement($parser, $name, $attrs){
    global $rss2array_globals, $item_limit;

    $rss2array_globals[current_tag] = $name;

    if($name == "RSS") {
        $rss2array_globals[inside_rss] = true;
    } elseif($name == "RDF:RDF") {
        $rss2array_globals[inside_rdf] = true;
    } elseif($name == "CHANNEL") {
        $rss2array_globals[inside_channel] = true;
        $rss2array_globals[channel_title] = "";
    } elseif(($rss2array_globals[inside_rss] and $rss2array_globals[inside_channel]) or $rss2array_globals[inside_rdf]){
        if ($name == "ITEM") {
            $rss2array_globals[inside_item] = true;
        } elseif ($name == "IMAGE") {
            $rss2array_globals[inside_image] = true;
        }
    }
}

// this function will be called everytime there is a string between two tags
function characterData($parser, $data){
    global $rss2array_globals, $item_limit;

    if ($rss2array_globals[inside_item]){
        switch($rss2array_globals[current_tag]){
            case "TITLE":
            $rss2array_globals[current_title] .= $data;
            break;
            case "DESCRIPTION":
            $rss2array_globals[current_description] .= $data;
            break;
            case "CONTENT:ENCODED":
            $rss2array_globals[current_content] .= $data;
            break;
            case "LINK":
            $rss2array_globals[current_link] .= $data;
            break;
            case "PUBDATE":
            $rss2array_globals[current_pubdate] .= $data;
            break;
        }
    } elseif($rss2array_globals[inside_image]) {
        switch($rss2array_globals[current_tag]){
            case "TITLE":
            $rss2array_globals[image_title] .= $data;
            break;
            case "LINK":
            $rss2array_globals[image_link] .= $data;
            break;
            case "URL":
            $rss2array_globals[image_url] .= $data;
            break;
            case "WIDTH":
            $rss2array_globals[image_width] .= $data;
            break;
            case "HEIGHT":
            $rss2array_globals[image_height] .= $data;
            break;
        }
    } elseif($rss2array_globals[inside_channel]) {
        switch($rss2array_globals[current_tag]){
            case "TITLE":
            $rss2array_globals[channel_title] .= $data;
            break;
            case "DESCRIPTION":
            $rss2array_globals[channel_description] .= $data;
            break;
            case "LINK":
            $rss2array_globals[channel_link] .= $data;
            break;
        }
    }
}

// this function will be called everytime a tag ends
function endElement($parser, $name){

    global $rss2array_globals, $item_limit;

    // end of item, add complete item to array
    if ($name == "ITEM") {
		 $item_limit = intval($item_limit);
		 if(!$item_limit || (count($rss2array_globals[items]) < $item_limit) ){
		    $rss2array_globals[items][] =
				    array(
				      title => trim($rss2array_globals[current_title]),
				      link => trim($rss2array_globals[current_link]),
				      description => trim($rss2array_globals[current_description]),
				      content => trim($rss2array_globals[current_content]),
				      pubdate => trim($rss2array_globals[current_pubdate]),
				      unixtimestamp => intval(strtotime(trim($rss2array_globals[current_pubdate])))
				    );
		 }
        // reset these vars for next loop
        $rss2array_globals[current_title] = "";
        $rss2array_globals[current_description] = "";
        $rss2array_globals[current_content] = "";
        $rss2array_globals[current_link] = "";
        $rss2array_globals[current_pubdate] = "";

        $rss2array_globals[inside_item] = false;

    } elseif($name == "RSS") {
    	$rss2array_globals[inside_rss] = false;

    } elseif($name == "RDF:RDF") {
        $rss2array_globals[inside_rdf] = false;

    } elseif($name == "CHANNEL") {
        $rss2array_globals[channel][] =
			  array(
			    title => trim($rss2array_globals[channel_title]),
			    link => trim($rss2array_globals[channel_link]),
			    description => trim($rss2array_globals[channel_description]),
			  );
        $rss2array_globals[channel_title] = "";
        $rss2array_globals[channel_description] = "";
        $rss2array_globals[channel_link] = "";

        $rss2array_globals[inside_channel] = false;

    } elseif($name == "IMAGE"){
        $rss2array_globals[image][] =
			  array(
			    title => trim($rss2array_globals[image_title]),
			    link => trim($rss2array_globals[image_link]),
			    url => trim($rss2array_globals[image_url]),
			    width => trim($rss2array_globals[image_width]),
			    height => trim($rss2array_globals[image_height])
			  );
        $rss2array_globals[image_title] = "";
        $rss2array_globals[image_link] = "";
        $rss2array_globals[image_url] = "";
        $rss2array_globals[image_width] = "";
        $rss2array_globals[image_height] = "";

        $rss2array_globals[inside_image] = false;
    }

}

function NewsUpdater($id=0, $status=1, $language_id=0){
	global $dbconn, $config;

	$where_str = ($status) ? " status='1' " : "";
	if ($id) {
		if (strlen($where_str)) {
			$where_str .= " AND ";
		}
		$where_str .= " id='".$id."' ";
	}
	if ($language_id) {
		if (strlen($where_str)) {
			$where_str .= " AND ";
		}
		$where_str .= " language_id='".$language_id."' ";
	}
	if(strlen($where_str)) $where_str = " WHERE ".$where_str;

	$strSQL = "SELECT id, link, max_news, language_id FROM ".NEWS_FEEDS_TABLE.$where_str;
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	$patterns = array("/(size=\"\w+\")/","/(face=\"\w+\")/", "/(font-family:.*;)/", "/(color=\".*\")/", "/<img (.*)style=\"(.*)\"(.*)\/>/");
	$replace = array("","face=\"Tahoma\"", "font-family: Tahoma", "color=''", "<img \\1 style='padding-right:10px; padding-bottom:10px;'>");  
	

	while(!$rs->EOF){
	  $row = $rs->GetRowAssoc(false);
	  $rss_array[$i]["id"] = $row["id"];
	  $rss_array[$i]["language_id"] = $row["language_id"];
	  $row["max_news"] = (intval($row["max_news"])>0) ? intval($row["max_news"]) : false;
	  $rss_array[$i]["content"] = rss2array($row["link"], $row["max_news"]);
	  
	  if(isset($rss_array[$i]["content"]["items"]) && count($rss_array[$i]["content"]["items"])>0) {
	    $str = "DELETE FROM ".NEWS_TABLE." WHERE id_channel='".$rss_array[$i]["id"]."'";
	    
	    $dbconn->Execute($str);
	    foreach($rss_array[$i]["content"]["items"] as $rss_news){
	    	
	      $str = "	INSERT INTO ".NEWS_TABLE."
	      					(date_add, news_text, status, image_path, title, date_ts, channel_name, channel_link, news_link, id_channel, language_id)
	      			VALUES 	('".date("Y-m-d H:i:s", $rss_news["unixtimestamp"])."', '".addslashes($rss_news["content"] ? preg_replace($patterns, $replace, $rss_news["content"]) : preg_replace($patterns, $replace, $rss_news["description"]))."', '1', '', '".addslashes($rss_news["title"])."', '".$rss_news["unixtimestamp"]."', '".addslashes($rss_array[$i]["content"]["channel"][0]["title"])."', '".$rss_array[$i]["content"]["channel"][0]["link"]."', '".$rss_news["link"]."', '".$rss_array[$i]["id"]."', '".$rss_array[$i]["language_id"]."')";
	      $dbconn->Execute($str);
	    }
	    $str = "UPDATE ".NEWS_FEEDS_TABLE." SET date_update=now() WHERE id='".$rss_array[$i]["id"]."' ";
	    $dbconn->Execute($str);
	  }
	  $rs->MoveNext();
	  $i++;
	}
}

function GetLastNews($max_count = false, $page=1){
	global $dbconn, $config;

	if($max_count){
	  $lim_min = ($page-1)*$max_count;
	  $lim_max = $max_count;
	  $limit_str = " limit ".$lim_min.", ".$lim_max;
	}
	$strSQL  = "SELECT id, title, news_text, date_format(date_add, '".$config["date_format"]."') as date_add, news_link, channel_name, channel_link, image_path from ".NEWS_TABLE." where status='1' order by date_ts desc, id desc ".$limit_str;
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	while(!$rs->EOF){
	  $row = $rs->GetRowAssoc(false);
	  $news[$i]  =  $row;
	  $news[$i]["title"] = stripslashes($row["title"]);
	  $news[$i]["news_text"] = stripslashes($row["news_text"]);
	  $rs->MoveNext();
	  $i++;
	}
	return $news;
}

function GetNewsReadLink($id_news){
	global $dbconn, $config, $config_index;

	$strSQL  = "SELECT id FROM ".NEWS_TABLE." WHERE status='1' GROUP BY id ORDER BY date_ts desc, id desc";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	$links = array();
	while(!$rs->EOF){
	  $row = $rs->GetRowAssoc(false);
	  $page = floor($i/$config_index["news_numpage"])+1;
	  $links[$row["id"]] = $config["server"].$config["site_root"]."/news.php?page=".$page."#".$row["id"];
	  $rs->MoveNext();
	  $i++;
	}
	return $links[$id_news];
}
?>