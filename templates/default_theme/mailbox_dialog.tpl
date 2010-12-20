<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>{$lang.title}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	<meta name="Description" content="{$lang.description}">
	<meta name="Keywords" content="{$lang.keywords}">
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css" media="all">
	{literal}
	<script type="text/javascript">
	function CheckReload(){
		if (window.opener.location.href.substring((window.opener.location.href.length-1), (window.opener.location.href.length)) == '#'){
			window.opener.location.href = window.opener.location.href.substring(0, (window.opener.location.href.length-1));
		} else {
			return;
		}
		return;
	}
	</script>
	{/literal}
</head>
<body style="margin: 15px;" onload="CheckReload();">
{if $user_info.black_id>0}
<div class="error">{$lang.content.black_err}</div>
<div style="padding-top: 10px;"><input class="btn_small" type="button" value="{$lang.content.close_window}" onclick="window.close();"></div>
{elseif $user_info.user_in_blacklist>0}
<div class="error">{$lang.content.user_in_black_err}</div>
<div style="padding-top: 10px;"><input class="btn_small" type="button" value="{$lang.content.close_window}" onclick="window.close();"></div>
{else}
<div id="mailbox">
<table cellpadding="0" cellspacing="0" border="0">
<form action="mailbox.php" method="POST" name="send_message">
<input type="hidden" name="sel" value="send_message">
<input type="hidden" name="user_id" value="{$user_id}">
{if $user_info}
<tr>
	<td valign="top" style="padding: 0px; padding-left: 10px;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<!--<td valign="top"><img src="{$user_info.pict_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;" alt="" hspace="0" vspace="0"></td>-->
			<td  height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
				<img src="{$user_info.pict_path}" style="border: none" alt="">
			</td>
			<td valign="middle" style="padding-left: 7px;">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="23">{$user_info.fname}&nbsp;{$user_info.sname},&nbsp;{if $user_info.status eq 1}<font color="#a50000">{$lang.content.online}</font>{else}{$lang.content.offline}{/if}</td>
					</tr>
					{if $user_info.status ne 1}
					<tr>
						<td height="23">{$lang.content.date_last_login}:&nbsp;{$user_info.date_seen}</td>
					</tr>
					{/if}
					{if $user_info.view_ad_link}
					<tr>
						<td height="23"><a href="{$user_info.view_ad_link}" target="_blank">{$lang.content.ads_from_user}</a></td>
					</tr>
					{/if}
				</table>
		</tr>
		</table>
	</td>
</tr>
{/if}
<tr>
	<td valign="top">
		{foreach item=item from=$error}
			<div style="padding-bottom: 5px; padding-left: 3px;" class="error">*&nbsp;{$item}</div>
		{/foreach}
		<table cellpadding="10" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
				<IFRAME height="300" width="550" src="mailbox.php?sel=chat&user_id={$user_id}" frameborder="no" hspace="0" vspace="0" scrolling="Yes" style="border: 1px solid #cccccc;"></IFRAME>
			</td>
		</tr>
		
		
		<tr>
			<td align="left">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td><TEXTAREA name="body" rows="5" style="width: 545px;"></TEXTAREA></td>
				</tr>
				<tr>
					<td style="padding-top: 5px;"><input type="button" class="btn" onclick="javascript: document.send_message.submit();" value="{$lang.buttons.send}"></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</form>
</table>
</div>
{literal}
<script language="javascript">
var doc = null;
function ajax() {
	// Make a new XMLHttp object
	if (typeof window.ActiveXObject != 'undefined' ) doc = new ActiveXObject("Microsoft.XMLHTTP");
	else doc = new XMLHttpRequest();
}

function UserEmailCheck(email) {
    	ajax();
	    if (doc){
	       doc.open("GET", "location.php?sec=hp&sel=email&email=" + email, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.responseText=='exists'){
	    		document.getElementById('email_error').style.display = '';
   				return 'exists';
	    	} else {
	    		document.getElementById('email_error').style.display = 'none';
	    	}
	    }
	    else{
	       alert ('Browser unable to create XMLHttp Object');
	    }
}

function CheckCorrect(obj) {
	if (obj.name == 'fname') {
		if (obj.value !="") {
			document.getElementById('fname_div').style.display = 'none';
		} else {
			document.getElementById('fname_div').style.display = '';
		}
	}
	if (obj.name == 'sname') {
		if (obj.value !="") {
			document.getElementById('sname_div').style.display = 'none';
		} else {
			document.getElementById('sname_div').style.display = '';
		}
	}
	if (obj.name == 'email') {
		if (obj.value !="" && obj.value.search('^.+@.+\\..+$') != -1) {
			document.getElementById('email_div').style.display = 'none';
		} else {
			document.getElementById('email_div').style.display = '';
		}
		document.getElementById('email_error').style.display = 'none';
		
	}
	return true;
}

function CheckValues() {
	if (document.getElementById('fname').value == ""){
		document.getElementById('fname_div').style.display = '';
		return false;
	} else if (document.getElementById('sname').value == ""){
		document.getElementById('sname_div').style.display = '';
		return false;
	} else if (document.getElementById('email').value == "" || document.getElementById('email').value.search('^.+@.+\\..+$') == -1){
		document.getElementById('email_div').style.display = '';
		return false;
	} else if (document.getElementById('email').value != "" && document.getElementById('email').value != "{/literal}{$self_info.email}{literal}" && UserEmailCheck(document.getElementById('email').value) == 'exists'){
		return false;
	}
	return true;
}
</script>
{/literal}
{/if}
</body>
</html>