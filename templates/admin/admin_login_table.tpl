<html>
<head>
	<title>{$lang.title}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	{if $script}<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/{$script}.js"></script>{/if}
	<script type="text/javascript" src="{$site_root}{$template_root}/js/yahoo.js"></script>
	<script type="text/javascript" src="{$site_root}{$template_root}/js/treeview.js"></script>
	<script type="text/javascript" src="{$site_root}/admin/admin_menu.php?js"></script>
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css">
	<link href="{$site_root}/admin/admin_menu.php?css" rel="stylesheet" type="text/css">
</head>
<body style="margin:0px;" bgcolor="#FFFFFF">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
        <td width="100%" height="28px" background="{$site_root}{$template_root}/images/menu/header_top.gif"><div style="padding-left:10px"><img src="{$site_root}{$template_root}/images/admin_title.gif" border="0"></div></td>
</tr>
<tr>
        <td width="100%" height="25px" background="{$site_root}{$template_root}/images/menu/header_bottom.gif" align="right" class="top_menu"><div style="padding-right:10px"></div></td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="10px">
<tr>
        <td colspan=2 width="100%" valign="top">
		{if $error}
                <table width="100%" height="100%" cellspacing="0" cellpadding="0">
                        <tr><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topleft.gif" width="9" height="9" border="0"></td>
                        <td width="100%" height="8px" class="line_bw_top">&nbsp;</td><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topright.gif" width="9" height="9"></td>
                        </tr>
                        <tr><td width="9px" class="line_bw_left">&nbsp;</td>
                        <td width="100%" height="100%" class="white_block" style="padding-left:10px">
				<b><font class="error">{foreach item=item from=$error}&nbsp;*&nbsp;{$item}{/foreach}</font></b>
                        </td><td width="9px" class="line_bw_right">&nbsp;</td></tr>
                        <tr><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_bottomleft.gif" width="9" height="9" border="0"></td>
                        <td width="100%" height="8px" class="line_bw_bottom">&nbsp;</td><td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_bottomright.gif" width="9" height="9"></td>
                        </tr>
                </table><br>
		{/if}
                <!-- Center panel -->
                <table width="100%" height="100%" cellspacing="0" cellpadding="0">
                        <tr>
						<td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topleft.gif" width="9" height="9" border="0"></td>
			                        <td width="100%" height="8px" class="line_bw_top">&nbsp;</td>
						<td width="9px"><img src="{$site_root}{$template_root}/images/corner_bw_topright.gif" width="9" height="9"></td>
                        </tr>
                        <tr>
						<td width="9px" class="line_bw_left">&nbsp;</td>
						<td width="100%" height="100%" class="white_block">

                        <table width="100%" border=0 cellspacing="3px" cellpadding="10px">
                                <tr>
                                        <td width="100%" class="blue_block" valign="top"><font class="text">


		<form method="POST" name="admin_login" id="admin_login" action="{$file_name}">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td class="header" align="left">{$lang.content.page_header} <font class="subheader">| {$lang.content.page_subheader}</font></td>
		</tr>
		<tr><td>
			<table cellpadding="3" cellspacing="1" border="0">
			<tr>
				<td class="main_header_text">{$lang.content.login}&nbsp;:&nbsp;</td>
				<td><input type="text" name="login_lg" size="30"></td>
			</tr>
			<tr>
				<td  class="main_header_text">{$lang.content.password}&nbsp;:&nbsp;</td>
				<td><input type="password" name="pass_lg" size="30"></td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
		<br><input type="submit" value="{$lang.buttons.login}" class="button_3">
		</form>
{include file="$admingentemplates/admin_bottom.tpl"}