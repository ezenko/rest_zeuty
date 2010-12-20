<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>{if $page_title}{$page_title}{else}{$lang.title}{/if}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	<meta name="Description" content="{$lang.description}">
	<meta name="Keywords" content="{$lang.keywords}">
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css" media="all">
{if $head_add}
	{foreach from=$head_add item=add_code}
		{$add_code}
	{/foreach}
{/if}
</head>
<body>
<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin: 10px 20px 10px 20px;">
{*<tr valign="top">
	<td width="100%" height="9" class="top_header_line">&nbsp;</td>
</tr>*}
<tr valign="top">
	<td>
		<table cellpadding="0" cellspacing="0" class="index_page" border="0" align="center">
		<tr>
			<td height="48" align="left" style="padding: 10px 0px 15px 15px;">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="left" align="left" valign="middle">
						<!--//logo-->
						<a href="{$server}{$site_root}/"><img src="{$site_root}{$template_root}{$template_images_root}/{$logo_settings.logotype.img}" border="0" alt="{$logo_settings.logotype.alt}"></a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		{if !$is_compare}
		<tr><td height="4" class="main_header_line">&nbsp;</td></tr>
		{/if}
		<tr>
			<td {if !$is_compare}style="padding: 15px 0px 25px 0px;"{/if}>