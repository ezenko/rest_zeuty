<?php
/**
* News read page
*
* @package RealEstate
* @subpackage Public Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.2 $ $Date: 2008/10/13 07:59:20 $
**/

include "./include/config.php";
include "./common.php";
include "./include/functions_index.php";
include "./include/functions_common.php";
include "./include/functions_auth.php";
include "./include/functions_xml.php";

$user = auth_index_user();
if($user[4]==1 && (isset($_REQUEST["view_from_admin"]) && $_REQUEST["view_from_admin"] == 1)){
	if ($_REQUEST["for_unreg_user"] == 1) {
		$user = auth_guest_read();
	}
}
$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$smarty->assign("sel", $sel);

$news_id = (isset($_REQUEST["news_id"]) && !empty($_REQUEST["news_id"])) ? $_REQUEST["news_id"] : 0;

switch($sel){
	case "read":
		NewsRead($news_id);
	break;
	default:
		NewsIndexPage();
	break;
}

/**
 * reading news, added by admin
 *
 * @param id $news_id
 */
function NewsRead($news_id){
	global $smarty, $config, $dbconn, $user, $lang;
	if (!$news_id) {
		NewsIndexPage();
		exit();
	}

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
		CreateMenu('rental_menu');
		$link_count = GetCountForLinks($user[0]);
		$smarty->assign("link_count",$link_count);
		$left_links = GetLeftLinks("homepage.php");
		$smarty->assign("left_links", $left_links);
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');

	IndexHomePage('news', 'news');
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"news.php";

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = "	SELECT id, DATE_FORMAT(date_add,'".$config["date_format"]."')  as date_add,
				news_text, title
				FROM ".NEWS_TABLE." WHERE id='$news_id'";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0){
		$row = $rs->GetRowAssoc(false);
		$news["id"] = $row["id"];
		$news["date_add"] = $row["date_add"];
		$news["news_text"] = nl2br(stripslashes(unicon($row["news_text"])));
		$news["title"] = stripslashes(unicon($row["title"]));
	}
	$smarty->assign("news", $news);

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/news_index_table.tpl");
	exit;

}

function NewsIndexPage(){
	global $smarty, $config, $dbconn, $user, $lang;

	if($user[3] != 1){
		CreateMenu('homepage_top_menu');
		CreateMenu('homepage_user_menu');
		CreateMenu('rental_menu');
		$link_count = GetCountForLinks($user[0]);
		$smarty->assign("link_count",$link_count);
		$left_links = GetLeftLinks("homepage.php");
		$smarty->assign("left_links", $left_links);
	} else {
		CreateMenu('index_top_menu');
		CreateMenu('index_user_menu');
	}
	CreateMenu('lang_menu');
	CreateMenu('bottom_menu');
	  
	IndexHomePage('news', 'news');
	$file_name = (isset($_SERVER["PHP_SELF"]))?AfterLastSlash($_SERVER["PHP_SELF"]):"news.php";

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;

	$strSQL = " SELECT COUNT(*) FROM ".NEWS_TABLE." WHERE status='1' AND language_id='{$config["default_lang"]}'";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	$news_numpage = GetSiteSettings("news_per_page");
	$lim_min = ($page-1)*$news_numpage;
	$lim_max = $news_numpage;
	$limit_str = " limit ".$lim_min.", ".$lim_max;

	if($num_records>0){
		$strSQL = "	SELECT DISTINCT id, DATE_FORMAT(date_add,'".$config["date_format"]."')  as date_add,
					news_text, title, news_link, channel_name, channel_link, id_channel
					FROM ".NEWS_TABLE." WHERE status='1' AND language_id='{$config["default_lang"]}'
					GROUP BY id ORDER BY date_ts desc, id_channel asc ".$limit_str;
		$rs = $dbconn->Execute($strSQL);
		if ($rs->fields[0]>0){
			$i = 0;
			while(!$rs->EOF){
				$row = $rs->GetRowAssoc(false);
				$news[$i]["id"] = $row["id"];
				$news[$i]["date_add"] = $row["date_add"];
				$news[$i]["id_channel"] = $row["id_channel"];
				if ($news[$i]["id_channel"] != 0) {
					$news[$i]["news_text"] = nl2br(stripslashes(unicon($row["news_text"])));
				} else {
					$news[$i]["news_text"] = substr(nl2br(stripslashes(unicon($row["news_text"]))), 0, GetSiteSettings("news_short_length"))."...";
				}
				$news[$i]["title"] = stripslashes(unicon($row["title"]));
				$news[$i]["channel_name"] = stripslashes(unicon($row["channel_name"]));
				$news[$i]["news_link"] = $row["news_link"];
				$news[$i]["channel_link"] = $row["channel_link"];
				if ($news[$i]["news_link"] == ''){
					$news[$i]["news_link"] = $config["server"].$config["site_root"]."/";
					$news[$i]["news_link"] .= ($news[$i]["id_channel"] == 0) ? "$file_name?sel=read&amp;news_id=".$news[$i]["id"] : "";
				}
				if ($news[$i]["channel_link"] == ''){
					$news[$i]["channel_link"] = $config["server"].$config["site_root"]."/";
				}
				$rs->MoveNext();
				$i++;
			}
			foreach ($news as $key=>$n) {
				$news[$key]["image"] = "";
				if (isset($n["image_path"])) {
					$file_path = $config["site_path"]."/news/".$n["image_path"];
					if(strlen($n["image_path"])>0 && file_exists($file_path)){
						$news[$key]["image"] = $config["server"].$config["site_root"]."/news/".$n["image_path"];
					}
				}
			}
		}
		$param = $file_name."?";
		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $news_numpage));
		$smarty->assign("news", $news);
	}

	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["index_theme_path"])."/news_index_table.tpl");
	exit;
}

function unicon($str, $to_uni = true) {
	if (!seems_utf8($str)){
	    $cp = Array (
	        "À" => "&#x410;", "à" => "&#x430;",
	        "Á" => "&#x411;", "á" => "&#x431;",
	        "Â" => "&#x412;", "â" => "&#x432;",
	        "Ã" => "&#x413;", "ã" => "&#x433;",
	        "Ä" => "&#x414;", "ä" => "&#x434;",
	        "Å" => "&#x415;", "å" => "&#x435;",
	        "¨" => "&#x401;", "¸" => "&#x451;",
	        "Æ" => "&#x416;", "æ" => "&#x436;",
	        "Ç" => "&#x417;", "ç" => "&#x437;",
	        "È" => "&#x418;", "è" => "&#x438;",
	        "É" => "&#x419;", "é" => "&#x439;",
	        "Ê" => "&#x41A;", "ê" => "&#x43A;",
	        "Ë" => "&#x41B;", "ë" => "&#x43B;",
	        "Ì" => "&#x41C;", "ì" => "&#x43C;",
	        "Í" => "&#x41D;", "í" => "&#x43D;",
	        "Î" => "&#x41E;", "î" => "&#x43E;",
	        "Ï" => "&#x41F;", "ï" => "&#x43F;",
	        "Ð" => "&#x420;", "ð" => "&#x440;",
	        "Ñ" => "&#x421;", "ñ" => "&#x441;",
	        "Ò" => "&#x422;", "ò" => "&#x442;",
	        "Ó" => "&#x423;", "ó" => "&#x443;",
	        "Ô" => "&#x424;", "ô" => "&#x444;",
	        "Õ" => "&#x425;", "õ" => "&#x445;",
	        "Ö" => "&#x426;", "ö" => "&#x446;",
	        "×" => "&#x427;", "÷" => "&#x447;",
	        "Ø" => "&#x428;", "ø" => "&#x448;",
	        "Ù" => "&#x429;", "ù" => "&#x449;",
	        "Ú" => "&#x42A;", "ú" => "&#x44A;",
	        "Û" => "&#x42B;", "û" => "&#x44B;",
	        "Ü" => "&#x42C;", "ü" => "&#x44C;",
	        "Ý" => "&#x42D;", "ý" => "&#x44D;",
	        "Þ" => "&#x42E;", "þ" => "&#x44E;",
	        "ß" => "&#x42F;", "ÿ" => "&#x44F;",
	        "“" => "&#x201C;", "”" => "&#x201D;"
	    );
	    if ($to_uni) {
	        $str = strtr($str, $cp);
	    } else {
	        foreach ($cp as $c) {
	            $cpp[$c] = array_search($c, $cp);
	        }
	        $str = strtr($str, $cpp);
	    }
	}
    return $str;
}

function seems_utf8($Str) {
 for ($i=0; $i<strlen($Str); $i++) {
  if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
  elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
  elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
  elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
  elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
  elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
  else return false; # Does not match any model
  for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
   if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
    return false;
  }
 }
 return true;
}

?>