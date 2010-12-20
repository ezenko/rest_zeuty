<?php
/**
* Functions collection for mail send, use class.phpmailer.php
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:30 $
**/

include_once "include/class.phpmailer.php";
$PHPmailer = new PHPMailer();

function SendMail($email_to, $email_from, $subject , $content_array, $mail_content, $content_file, $embedded="", $email_to_name="",$email_from_name="",$text='text'){
	global $config, $smarty, $dbconn, $PHPmailer;

	$err = "";

	$PHPmailer->From = $email_from;
	$PHPmailer->FromName = $email_from_name ? $email_from_name : $email_from;
	$email_to_name = $email_to_name ? $email_to_name : $email_to;
	$PHPmailer->AddAddress($email_to, $email_to_name);
	if ($text == "html"){
		$PHPmailer->IsHTML(true);                               // send as HTML
		$content = MakeContent($content_file, $content_array, $mail_content, $embedded, "html");
	} elseif ($text == "text") {
		$PHPmailer->IsHTML(false);                               // send as TEXT
		$content = MakeContent($content_file, $content_array, $mail_content, $embedded, "text");
	}
	if(is_array($embedded) && count($embedded["id"])>0){
		for($i=0;$i<count($embedded["id"]);$i++){
			$PHPmailer->AddEmbeddedImage($embedded["image_path"][$i], $embedded["id"][$i], $embedded["image_name"][$i], $encoding = "base64", $type = $embedded["image_type"][$i]);
		}
	}
	$PHPmailer->Subject  =  $subject;
	$PHPmailer->Body     =  $content;
	if($content != ""){
		if(!$PHPmailer->Send()){
			GetErrors("mail_error");
			$err = true;
		}
	}
	sleep (1);
	$PHPmailer->ClearAddresses();
	$PHPmailer->ClearAttachments();

	return $err;
}

function MakeContent($content_file, $content_array, $mail_content, $embedded="", $type=""){
	global $config, $smarty, $dbconn;

	if (is_array($embedded)) {
		$smarty->assign("attaches", $embedded);
	}
	$smarty->assign("data", $content_array);
	$smarty->assign("mail_content", $mail_content);
	if ($type !='text') {
		$cont = $smarty->fetch(TrimSlash($config["index_theme_path"])."/".$content_file.".tpl");
	} else {
		$cont = $smarty->fetch(TrimSlash($config["index_theme_path"])."/".$content_file."_text.tpl");
	}

	return $cont;
}
?>