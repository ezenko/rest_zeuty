<?php
/**
* Site Neews and RSS Feeds management
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.3 $ $Date: 2008/10/15 12:22:39 $
**/

include "../include/config.php";
include "../common.php";
include "../include/functions_admin.php";
include "../include/functions_common.php";
include "../include/functions_auth.php";
include "../include/functions_xml.php";
include "../include/class.news.php";

include_once "../tinymce/tinymce.php";
$auth = auth_user();

if ( (!($auth[0]>0))  || (!($auth[4]==1))){
	echo "<script>location.href='".$config["site_root"]."/admin/index.php'</script>";
}

$mode = IsFileAllowed($auth[0], GetRightModulePath(__FILE__) );

if ( ($auth[9] == 0) || ($auth[7] == 0) || ($auth[8] == 0)) {
	AlertPage();
	exit;
} elseif ($mode == 0) {
	AlertPage(GetRightModulePath(__FILE__));
	exit;
}

$sel = (isset($_REQUEST["sel"]) && !empty($_REQUEST["sel"])) ? $_REQUEST["sel"] : "";
$current_lang_id = (isset($_REQUEST["language_id"]) && !empty($_REQUEST["language_id"])) ? $_REQUEST["language_id"] : $config["default_lang"];
$smarty->assign("current_lang_id", $current_lang_id);

switch($sel){
	case "add":		NewsForm('add'); break;
	case "edit": 	NewsForm('edit'); break;
	case "del": 	DeleteNews(); break;
	case "del_f": 	DeleteFeed(); break;
	case "add_n":	SaveNews('add'); break;
	case "edit_n":	SaveNews('edit'); break;
	case "edit_f":	FeedsForm('edit'); break;
	case "add_f":	FeedsForm('add'); break;
	case "save_f":	SaveFeeds('edit'); break;
	case "save_fn":	SaveFeeds('add'); break;
	case "update_all_f": UpdateFeeds(); break;
	case "update_feed": UpdateFeed(); break;
	default: FeedsIndex(); break;
}

function FeedsIndex() {
	global $smarty, $dbconn, $config, $current_lang_id;

	$news_per_page = GetSiteSettings("admin_rows_per_page");
	$story = array();
	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_news.php";

	IndexAdminPage('admin_news');
	CreateMenu('admin_lang_menu');

	$page = (isset($_REQUEST["page"]) && intval($_REQUEST["page"]) > 0) ? intval($_REQUEST["page"]) : 1;	
	$sorter_news = (isset($_REQUEST["sorter_news"]) && intval($_REQUEST["sorter_news"]) > 0) ? intval($_REQUEST["sorter_news"]) : 1;
	$sorter_feeds = (isset($_REQUEST["sorter_feeds"]) && intval($_REQUEST["sorter_feeds"]) > 0) ? intval($_REQUEST["sorter_feeds"]) : 1;
	$order_news = (isset($_REQUEST["order_news"]) && intval($_REQUEST["order_news"]) > 0) ? intval($_REQUEST["order_news"]) : 1;
	$order_feeds = (isset($_REQUEST["order_feeds"]) && intval($_REQUEST["order_feeds"]) > 0) ? intval($_REQUEST["order_feeds"]) : 1;
	
	switch ($order_news){
		case "1":
			$order_news_str = " ASC";
			$smarty->assign("order_news", 2);
			$smarty->assign("order_news_old", 1);
			$news_order_icon = "&darr;";
			break;
		default:
			$order_news_str = " DESC";
			$smarty->assign("order_news", 1);
			$smarty->assign("order_news_old", 2);
			$news_order_icon = "&uarr;";
			break;
	}
	$smarty->assign("news_order_icon", $news_order_icon);
	switch ($order_feeds){
		case "1":
			$order_feeds_str = " ASC";
			$smarty->assign("order_feeds", 2);
			$smarty->assign("order_feeds_old", 1);
			$feeds_order_icon = "&darr;";
			break;
		default:
			$order_feeds_str = " DESC";
			$smarty->assign("order_feeds", 1);
			$smarty->assign("order_feeds_old", 2);
			$feeds_order_icon = "&uarr;";
			break;
	}
	$smarty->assign("feeds_order_icon", $feeds_order_icon);
	if(intval($sorter_news)>0){
		$sorter_news_str = "  ORDER BY ";
		switch($sorter_news) {
			case "1": $sorter_news_str.=" date_add"; break;
			case "2": $sorter_news_str.=" title"; break;
			case "3": $sorter_news_str.=" status"; break;
		}
		$sorter_news_str .= $order_news_str;
	} else {
		$sorter_news_str = "";
	}
	$smarty->assign("sorter_news", $sorter_news);
	if(intval($sorter_feeds)>0){
		$sorter_feeds_str = "  ORDER BY ";
		switch($sorter_feeds) {
			case "1": $sorter_feeds_str.=" date_update"; break;
			case "2": $sorter_feeds_str.=" link"; break;
			//case "3": $sorter_feeds_str.=" max_news"; break;
			case "3": $sorter_feeds_str.=" id"; break;
			case "4": $sorter_feeds_str.=" status"; break;
		}
		$sorter_feeds_str .= $order_feeds_str;
	} else {
		$sorter_feeds_str = "";
	}
	$smarty->assign("sorter_feeds", $sorter_feeds);

	//select Site news for ADMIN
	$strSQL = "SELECT COUNT(*) FROM ".NEWS_TABLE." WHERE id_channel='0' AND language_id='$current_lang_id'";
	$rs = $dbconn->Execute($strSQL);
	$num_records = $rs->fields[0];

	$lim_min = ($page-1)*$news_per_page;
	$lim_max = $news_per_page;
	$limit_str = " limit ".$lim_min.", ".$lim_max;
	$strSQL = "	SELECT DISTINCT id, DATE_FORMAT(date_add,'".$config["date_format"]." %H:%i:%s')  as date_add,  news_text, status, title
				FROM ".NEWS_TABLE." WHERE id_channel='0' AND language_id='$current_lang_id'
				GROUP BY id $sorter_news_str $limit_str";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$news[$i]["number"] = ($page-1)*$news_per_page+($i+1);
			$news[$i]["id"] = $row["id"];
			$news[$i]["title"] = strip_tags(stripslashes($row["title"]));
			$news[$i]["text"] = strip_tags(stripslashes($row["news_text"]));
			if(utf8_strlen($news[$i]["text"])>100)
			$news[$i]["text"] = utf8_substr($news[$i]["text"], 0, 100)."...";
			$news[$i]["date"] = $row["date_add"];
			$news[$i]["status"] = $row["status"] ? 1 : 0;
			$news[$i]["deletelink"] = $file_name."?sel=del&id={$row["id"]}&language_id=$current_lang_id";
			$news[$i]["editlink"] = $file_name."?sel=edit&page=$page&id={$row["id"]}&language_id=$current_lang_id";
			$rs->MoveNext();
			$i++;
		}
		$param = $file_name."?sorter_news=".$sorter_news."&order_news=".$order_news."&language_id=$current_lang_id&";
		$smarty->assign("links", GetLinkArray($num_records, $page, $param, $news_per_page));
		$smarty->assign("news", $news);
	}

	$strSQL = "SELECT DISTINCT id, DATE_FORMAT(date_update,'".$config["date_format"]." %H:%i:%s')  as date_update, ".
			  "link, status, max_news ".
			  "FROM ".NEWS_FEEDS_TABLE." ".
			  "WHERE language_id='$current_lang_id' ".
			  "GROUP BY id $sorter_feeds_str ";
	$rs = $dbconn->Execute($strSQL);
	$i = 0;
	if($rs->RowCount()>0){
		while(!$rs->EOF){
			$row = $rs->GetRowAssoc(false);
			$feeds[$i]["number"] = $i+1;
			$feeds[$i]["id"] = $row["id"];
			$strSQL = " SELECT COUNT(id) FROM ".NEWS_TABLE." WHERE id_channel='".$feeds[$i]["id"]."' ";
			$rs_2 = $dbconn->Execute($strSQL);
			$feeds[$i]["news_count"] = $rs_2->fields[0];
			$news_cnt[] = $rs_2->fields[0];
			$feeds[$i]["link"] = $row["link"];
			$feeds[$i]["date"] = $row["date_update"];
			$feeds[$i]["status"] = $row["status"] ? 1 : 0;
			$feeds[$i]["deletelink"] = $file_name."?sel=del_f&id={$row["id"]}&language_id=$current_lang_id";
			$feeds[$i]["editlink"] = $file_name."?sel=edit_f&page=$page&id={$row["id"]}&language_id=$current_lang_id";
			$rs->MoveNext();
			$i++;
		}

		if ($sorter_feeds == 3) {
			// sort by imported news count
			if ($order_feeds_str == " ASC") {
				sort($news_cnt, SORT_NUMERIC);
			} else {
				rsort($news_cnt, SORT_NUMERIC);
			}

			$sort_feeds = array();
			$sort_feed_ids = array();
			foreach ($news_cnt as $nk=>$value) {
				foreach ($feeds as $fk=>$feed) {
					if ($feed["news_count"] == $value && !in_array($fk, $sort_feed_ids)) {
						array_push($sort_feed_ids, $fk);
						$sort_feeds[$nk] = $feed;
						break;
					}
				}
			}
			$feeds = $sort_feeds;
		}
		$smarty->assign("feeds", $feeds);
	}
	$smarty->assign("add_link", $file_name."?sel=add&page=$page&language_id=$current_lang_id");
	$smarty->assign("rss_add_link", $file_name."?sel=add_f&page=$page&language_id=$current_lang_id");
	$smarty->assign("rss_update_link", $file_name."?sel=update_all_f&language_id=$current_lang_id");

	$smarty->assign("page", $page);
	$smarty->assign("total_news", $num_records);
	$smarty->assign("script", "div_actions");
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_news_index_table.tpl");
	exit;
}


function NewsForm($par='', $data='', $err='') {
	global $smarty, $dbconn, $config, $tinymce;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_news.php";

	IndexAdminPage('admin_news');
	CreateMenu('admin_lang_menu');

	switch ($par){
		case "edit":
			$id_new = intval($_GET["id"]);
			if ($id_new < 1){
				FeedsIndex();
				exit;
			}
			$strSQL = " SELECT id, UNIX_TIMESTAMP(date_add) as date_add, news_text, status, image_path, title, date_ts, channel_name, channel_link, news_link, id_channel
						FROM ".NEWS_TABLE."
						WHERE id='".$id_new."' ";
			$rs = $dbconn->Execute($strSQL);
			if ($rs->fields[0]>0){
				$row = $rs->GetRowAssoc(false);
				$data["id_news"] = $row["id"];
				$data["status"] = $row["status"];
				$data["title"] = stripslashes($row["title"]);
				$data["news_text"] = stripslashes($row["news_text"]);
				$data["year"] = date("Y", $row["date_add"]);
				$data["month"] = date("n", $row["date_add"]);
				$data["day"] = date("j", $row["date_add"]);
			}
			break;
		case "add":
			$data["status"] = 1;
			break;
		case "back":
			GetErrors($err);
			$par = $data["par"];
			break;
	}

	$smarty->assign( "tinymce", $tinymce );
	$smarty->assign("data", $data);
	$smarty->assign("par", $par);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_news_form.tpl");
	exit;
}

function DeleteNews(){
	global $smarty, $dbconn, $config;

	$id = intval($_GET["id"]);
	if($id < 1)	{	FeedsIndex(); return;}

	$id_feed = 0;
	$strSQL = "SELECT id_channel FROM ".NEWS_TABLE." WHERE id='".$id."' ";
	$rs = $dbconn->Execute($strSQL);
	if ($rs->fields[0]>0){
		$id_feed = $rs->fields[0];
	}

	$rs_img = $dbconn->Execute(" SELECT image_path FROM ".NEWS_TABLE." WHERE id='".$id."'");

	if(strlen($rs_img->fields[0])>0){
		$image_old_file = $config["site_path"]."/editor/".$rs_img->fields[0];
		if(file_exists($image_old_file))	unlink($image_old_file);
	}
	$dbconn->Execute("DELETE FROM ".NEWS_TABLE." WHERE id='".$id."' ");
	if ($id_feed>0){
		FeedsForm('edit','','',$id_feed);
	} else {
		FeedsIndex();
	}

	return;
}

function DeleteFeed(){
	global $smarty, $dbconn, $config;

	$id = intval($_GET["id"]);

	if ($id < 1)	{	FeedsIndex(); return;}
	$dbconn->Execute("DELETE FROM ".NEWS_FEEDS_TABLE." WHERE id='".$id."'");
	$dbconn->Execute("DELETE FROM ".NEWS_TABLE." WHERE id_channel='".$id."'");
	FeedsIndex();
	return;
}

function SaveNews($par='') {
	global $smarty, $dbconn, $config, $current_lang_id;

	$data["status"] = (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) ? intval($_REQUEST["status"]) : 0;
	$data["title"] = strip_tags(trim($_POST["title"]));
	$data["news_text"] = $_POST["news_text"];
	$data["id_news"] = (isset($_REQUEST["id_news"]) && !empty($_REQUEST["id_news"])) ? intval($_REQUEST["id_news"]) : 0;

	if (($data["title"] == '') || ($data["news_text"] == '')){
		$data["par"] = $par;
		NewsForm('back', $data, 'empty_fields');
		return;
	}
	$datenow = time();
	if ($par == 'edit') {
		$data["id_news"] = intval($_POST["id_news"]);
		if ($data["id_news"]<1){
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_news.php?language_id=$current_lang_id");
			exit();
		}
		$strSQL = " UPDATE ".NEWS_TABLE." SET date_add='".date("Y-m-d H:i:s", $datenow)."', news_text='".addslashes($data["news_text"])."', title='".addslashes($data["title"])."', status='".$data["status"]."', date_ts='".$datenow."' WHERE id='".$data["id_news"]."' ";
	} else {
		$strSQL = " INSERT INTO ".NEWS_TABLE." (date_add, news_text, title, status, date_ts, language_id) VALUES ('".date("Y-m-d H:i:s", $datenow)."', '".addslashes($data["news_text"])."', '".addslashes($data["title"])."', '".$data["status"]."', '".$datenow."', '$current_lang_id') ";
	}
	$dbconn->Execute($strSQL);

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_news.php?language_id=$current_lang_id");
	exit();
}

function SaveFeeds($par=''){
	global $smarty, $dbconn, $config, $current_lang_id;

	$data["link"] = trim($_POST["link"]);
	$data["max_news"] = intval($_POST["max_news"]);
	$data["status"] = (isset($_REQUEST["status"]) && !empty($_REQUEST["status"])) ? intval($_REQUEST["status"]) : 0;
	$data["id_feed"] = (isset($_REQUEST["id_feed"]) && !empty($_REQUEST["id_feed"])) ? intval($_REQUEST["id_feed"]) : 0;

	if (($data["link"] == '') || ($data["max_news"] < 0)){
		$data["par"] = $par;
		FeedsForm('back', $data, 'empty_fields');
		return;
	}
	if ($par == 'edit'){
		$data["id_feed"] = intval($_POST["id_feed"]);
		if ($data["id_feed"]<1){
			header("Location: ".$config["server"].$config["site_root"]."/admin/admin_news.php?language_id=$current_lang_id");
			exit();
		}
		$strSQL = "UPDATE ".NEWS_FEEDS_TABLE." SET link='".addslashes($data["link"])."', max_news='".$data["max_news"]."', status='".$data["status"]."', date_update=now() WHERE id='".$data["id_feed"]."' ";
	} else {
		$strSQL = "INSERT INTO ".NEWS_FEEDS_TABLE." (link, max_news, status, date_update, language_id) VALUES ('".$data["link"]."', '".$data["max_news"]."', '".$data["status"]."', now(), '$current_lang_id') ";
	}
	$dbconn->Execute($strSQL);

	$id_channel = ($par == 'edit') ? $data["id_feed"] : $dbconn->Insert_ID();

	if ($data["status"] == 1) {
		NewsUpdater($id_channel);
	} else {
		$str = "DELETE FROM ".NEWS_TABLE." WHERE id_channel='$id_channel'";
	    $dbconn->Execute($str);
	}

	header("Location: ".$config["server"].$config["site_root"]."/admin/admin_news.php?language_id=$current_lang_id");
	exit();
}


function FeedsForm($par='', $data='', $err='', $id_feed=''){
	global $smarty, $dbconn, $config;

	if(isset($_SERVER["PHP_SELF"]))
	$file_name = AfterLastSlash($_SERVER["PHP_SELF"]);
	else
	$file_name = "admin_news.php";

	IndexAdminPage('admin_news');
	CreateMenu('admin_lang_menu');

	$min_feeds_news_cnt = GetSiteSettings("min_feeds_news_cnt");
	$smarty->assign("min_feeds_news_cnt", $min_feeds_news_cnt);

	switch ($par){
		case "edit":
			if ($id_feed == ''){
				$id_feed = intval($_GET["id"]);
			}
			if ($id_feed < 1){
				FeedsIndex();
				exit;
			}
			$strSQL = " SELECT id, link, max_news, status
						FROM ".NEWS_FEEDS_TABLE."
						WHERE id='".$id_feed."' ";
			$rs = $dbconn->Execute($strSQL);
			$row = $rs->GetRowAssoc(false);
			$data["id_feed"] = $row["id"];
			$data["link"] = stripslashes($row["link"]);
			$data["max_news"] = $row["max_news"];
			$data["status"] = $row["status"];

			$strSQL = "	SELECT DISTINCT id, DATE_FORMAT(date_add,'".$config["date_format"]."')  as date_add,  news_text, status, title
						FROM ".NEWS_TABLE." WHERE id_channel='".$data["id_feed"]."'
						GROUP BY id ORDER BY date_ts desc, id desc ";
			$rs = $dbconn->Execute($strSQL);
			$i = 0;
			if($rs->RowCount()>0){
				while(!$rs->EOF){
					$row = $rs->GetRowAssoc(false);
					$news[$i]["number"] = ($i+1);
					$news[$i]["id"] = $row["id"];
					$news[$i]["title"] = strip_tags(stripslashes(unicon($row["title"])));
					$news[$i]["text"] = strip_tags(stripslashes(unicon($row["news_text"])));
					if(utf8_strlen($news[$i]["text"])>100)
					$news[$i]["text"] = utf8_substr($news[$i]["text"], 0, 100)."...";
					$news[$i]["date"] = $row["date_add"];
					$news[$i]["status"] = $row["status"] ? 1 : 0;
					$news[$i]["deletelink"] = $file_name."?sel=del&id=".$rs->fields[0];
					$news[$i]["editlink"] = $file_name."?sel=edit_fn&id=".$rs->fields[0];
					$rs->MoveNext();
					$i++;
				}
				$smarty->assign("news", $news);
				$smarty->assign("total_news", sizeof($news));
			}			
			break;
		case "add":
			$data["status"] = 1;
			break;
		case "back":
			GetErrors($err);
			$par = $data["par"];
			break;
	}

	$smarty->assign("data", $data);
	$smarty->assign("par", $par);
	$smarty->assign("file_name", $file_name);
	$smarty->display(TrimSlash($config["admin_theme_path"])."/admin_feeds_form.tpl");
	exit;
}

function UpdateFeeds(){
	global $smarty, $dbconn, $config, $current_lang_id;
	NewsUpdater(0, 1, $current_lang_id);
	FeedsIndex();
	return;
}

function UpdateFeed(){
	global $smarty, $dbconn, $config;
	$data["id_feed"] = intval($_GET["id_feed"]);
	NewsUpdater($data["id_feed"]);
	FeedsForm('edit','','',$data["id_feed"]);
	return ;
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