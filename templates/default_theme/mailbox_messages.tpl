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
	<SCRIPT type="text/javascript">
	function SubmitRefreshForm() {
		document.forms["refresh"].submit();
	}
	function Refresh() {
		setTimeout("SubmitRefreshForm()", 45000);
	}
	</SCRIPT>
	{/literal}
</head>
<body style="margin: 0px;" onLoad="Refresh(); document.location = '#1'">
<FORM action="mailbox.php?sel=chat" name="refresh" id="refresh" method="POST">
	<INPUT type="hidden" name="user_id" value="{$user_id}">
</FORM>
<div>
{if $messages}
	<table cellpadding="0" cellspacing="0" style="margin: 0px; margin-left: 10px;">
	{section name=s loop=$messages}
	<tr>		
		<td>{if $messages[s].to_user_id == $current_user_id}<font class="mailbox_to_user">{else}<font class="text">{/if}<b>{$messages[s].from_user_name}</b>&nbsp;({$messages[s].timestamp}):</font></td>
	</tr>
	<tr>
		<td style="padding-left: 20px; padding-bottom: 5px;">{if $messages[s].to_user_id == $current_user_id}<font class="mailbox_to_user">{else}<font class="text">{/if}{$messages[s].body}</font></td>
	</tr>
	{/section}
	</table>
{else}
{/if}
</div>
<div><A name="1" id="1" style="font-color: #ffffff; color: #ffffff;">&nbsp;</A></div>
</body>
</html>